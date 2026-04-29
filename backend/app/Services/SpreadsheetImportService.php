<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Import;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use ZipArchive;

class SpreadsheetImportService
{
    public function __construct(
        protected UserProvisioningService $provisioningService,
        protected ClassroomAllocationService $allocationService,
    ) {
    }

    public function import(UploadedFile $file, string $type, School $school, User $actor, int $maxStudentsPerClass = 25): Import
    {
        $rows = $this->parseSpreadsheet($file);
        $normalizedRows = $this->normalizeRows($rows);
        $this->ensureRequiredColumns($normalizedRows, $type);
        $duplicateEmails = $this->detectDuplicateEmails($normalizedRows);

        $summary = [
            'created' => [],
            'updated' => [],
            'duplicates' => $duplicateEmails,
            'generated_passwords' => [],
            'links' => [],
            'errors' => [],
            'allocation' => null,
            'expected_columns' => $this->expectedColumnsForType($type),
        ];

        $counts = [
            'created' => 0,
            'updated' => 0,
        ];

        DB::transaction(function () use ($normalizedRows, $duplicateEmails, $type, $school, &$summary, &$counts, $maxStudentsPerClass) {
            foreach ($normalizedRows as $index => $row) {
                try {
                    if ($type === 'students') {
                        $studentResult = $this->provisioningService->upsertStudent($school, $row);
                        $this->recordResult($summary, $counts, $studentResult, $studentResult['user']->email);

                        if (! empty($row['parent_email']) || ! empty($row['parent_name'])) {
                            $parentResult = $this->provisioningService->upsertParent($school, [
                                'name' => $row['parent_name'] ?: ('Parent of ' . $studentResult['user']->name),
                                'email' => $row['parent_email'] ?? null,
                                'phone' => $row['parent_phone'] ?? null,
                            ]);

                            $this->recordResult($summary, $counts, $parentResult, $parentResult['user']->email);
                            $this->provisioningService->linkParentToStudent($parentResult['user'], $studentResult['user']);
                            $summary['links'][] = $parentResult['user']->email . ' -> ' . $studentResult['user']->email;
                        }
                    } elseif ($type === 'parents') {
                        $parentResult = $this->provisioningService->upsertParent($school, $row);
                        $this->recordResult($summary, $counts, $parentResult, $parentResult['user']->email);

                        if (! empty($row['student_email'])) {
                            $student = User::query()
                                ->where('school_id', $school->id)
                                ->where('email', $row['student_email'])
                                ->first();

                            if ($student) {
                                $this->provisioningService->linkParentToStudent($parentResult['user'], $student);
                                $summary['links'][] = $parentResult['user']->email . ' -> ' . $student->email;
                            }
                        }
                    } elseif ($type === 'teachers') {
                        $teacherResult = $this->provisioningService->upsertTeacher($school, $row);
                        $this->recordResult($summary, $counts, $teacherResult, $teacherResult['user']->email);
                    }
                } catch (\Throwable $exception) {
                    $summary['errors'][] = 'Row ' . ($index + 2) . ': ' . $exception->getMessage();
                }
            }

            if ($type === 'students') {
                $summary['allocation'] = $this->allocationService->allocateForSchool($school, $maxStudentsPerClass);
            }
        });

        $storedFilePath = $file->storeAs(
            'imports',
            now()->format('Ymd_His') . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension(),
            'local'
        );

        $import = Import::create([
            'school_id' => $school->id,
            'user_id' => $actor->id,
            'type' => $type,
            'file_name' => basename($storedFilePath),
            'status' => empty($summary['errors']) ? 'completed' : 'completed_with_errors',
            'processed_rows' => count($normalizedRows),
            'created_users' => $counts['created'],
            'updated_users' => $counts['updated'],
            'duplicates_count' => count($duplicateEmails),
            'summary' => $summary,
        ]);

        AuditLog::create([
            'user_id' => $actor->id,
            'action' => 'import_completed',
            'description' => sprintf(
                'Import #%d (%s) processed %d rows for school %s.',
                $import->id,
                $type,
                $import->processed_rows,
                $school->name
            ),
        ]);

        return $import;
    }

    protected function recordResult(array &$summary, array &$counts, array $result, string $identifier): void
    {
        if ($result['created']) {
            $counts['created']++;
            $summary['created'][] = $identifier;

            if (! empty($result['generated_password'])) {
                $summary['generated_passwords'][$identifier] = $result['generated_password'];
            }

            return;
        }

        $counts['updated']++;
        $summary['updated'][] = $identifier;
    }

    protected function normalizeRows(array $rows): array
    {
        return collect($rows)
            ->filter(fn ($row) => collect($row)->filter(fn ($value) => trim((string) $value) !== '')->isNotEmpty())
            ->map(function ($row) {
                $normalized = [];

                foreach ($row as $key => $value) {
                    $normalized[Str::snake(trim((string) $key))] = is_string($value) ? trim($value) : $value;
                }

                return $normalized;
            })
            ->values()
            ->all();
    }

    protected function detectDuplicateEmails(array $rows): array
    {
        $emails = collect($rows)
            ->flatMap(function ($row) {
                return [
                    $row['email'] ?? null,
                    $row['parent_email'] ?? null,
                    $row['student_email'] ?? null,
                ];
            })
            ->filter()
            ->map(fn ($email) => Str::lower($email));

        return $emails
            ->countBy()
            ->filter(fn ($count) => $count > 1)
            ->keys()
            ->values()
            ->all();
    }

    protected function ensureRequiredColumns(array $rows, string $type): void
    {
        if ($rows === []) {
            throw new RuntimeException('The import file is empty.');
        }

        $expectedColumns = $this->expectedColumnsForType($type);
        $availableColumns = array_keys($rows[0]);
        $missingColumns = array_values(array_diff($expectedColumns, $availableColumns));

        if ($missingColumns !== []) {
            throw new RuntimeException('Missing required columns: ' . implode(', ', $missingColumns) . '.');
        }
    }

    public function expectedColumnsForType(string $type): array
    {
        return match ($type) {
            'students' => ['name', 'email', 'phone', 'level_code', 'level_name'],
            'parents' => ['name', 'email'],
            'teachers' => ['name', 'email'],
            default => ['name', 'email'],
        };
    }

    protected function parseSpreadsheet(UploadedFile $file): array
    {
        $extension = Str::lower($file->getClientOriginalExtension());

        return match ($extension) {
            'csv', 'txt' => $this->parseCsv($file->getRealPath()),
            'xlsx' => $this->parseXlsx($file->getRealPath()),
            default => throw new RuntimeException('Unsupported import file. Use CSV or XLSX.'),
        };
    }

    protected function parseCsv(string $path): array
    {
        $handle = fopen($path, 'r');

        if (! $handle) {
            throw new RuntimeException('Unable to open CSV file.');
        }

        $headers = null;
        $rows = [];

        while (($data = fgetcsv($handle, 0, ',')) !== false) {
            if (! $headers) {
                $headers = $data;
                continue;
            }

            $rows[] = array_combine($headers, array_pad($data, count($headers), null));
        }

        fclose($handle);

        return $rows;
    }

    protected function parseXlsx(string $path): array
    {
        $zip = new ZipArchive();

        if ($zip->open($path) !== true) {
            throw new RuntimeException('Unable to open XLSX file.');
        }

        $sharedStrings = [];
        $sharedStringsXml = $zip->getFromName('xl/sharedStrings.xml');

        if ($sharedStringsXml !== false) {
            $xml = simplexml_load_string($sharedStringsXml);

            foreach ($xml->si as $item) {
                $sharedStrings[] = isset($item->t) ? (string) $item->t : collect($item->r)->map(fn ($run) => (string) $run->t)->implode('');
            }
        }

        $sheetXmlString = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();

        if ($sheetXmlString === false) {
            throw new RuntimeException('Sheet1 not found in XLSX file.');
        }

        $sheetXml = simplexml_load_string($sheetXmlString);
        $rows = [];

        foreach ($sheetXml->sheetData->row as $row) {
            $cells = [];

            foreach ($row->c as $cell) {
                $value = (string) $cell->v;
                $type = (string) $cell['t'];

                if ($type === 's') {
                    $value = $sharedStrings[(int) $value] ?? null;
                }

                $cells[] = $value;
            }

            $rows[] = $cells;
        }

        if (count($rows) < 2) {
            return [];
        }

        $headers = array_map('trim', $rows[0]);

        return collect(array_slice($rows, 1))
            ->map(fn ($row) => array_combine($headers, array_pad($row, count($headers), null)))
            ->all();
    }
}
