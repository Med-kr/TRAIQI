<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesSchoolContext;
use App\Http\Controllers\Controller;
use App\Models\Import;
use App\Services\SpreadsheetImportService;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    use HandlesSchoolContext;

    public function __construct(protected SpreadsheetImportService $importService)
    {
    }

    public function index()
    {
        $this->ensurePermission('manage imports');

        return view('admin.imports.index', [
            'imports' => Import::forSchoolContext($this->currentAdmin())
                ->with(['school', 'user'])
                ->latest()
                ->paginate(15),
            'schools' => $this->availableSchools(),
            'expectedColumns' => [
                'students' => $this->importService->expectedColumnsForType('students'),
                'parents' => $this->importService->expectedColumnsForType('parents'),
                'teachers' => $this->importService->expectedColumnsForType('teachers'),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $this->ensurePermission('manage imports');

        $schoolId = $this->resolveSchoolId($request->integer('school_id'));

        $validated = $request->validate([
            'school_id' => ['nullable', 'integer'],
            'type' => ['required', 'in:students,parents,teachers'],
            'file' => ['required', 'file', 'mimes:csv,txt,xlsx'],
            'max_students_per_class' => ['nullable', 'integer', 'min:1', 'max:60'],
        ]);

        $school = $this->availableSchools()->firstWhere('id', $schoolId);

        $import = $this->importService->import(
            $request->file('file'),
            $validated['type'],
            $school,
            $this->currentAdmin(),
            (int) ($validated['max_students_per_class'] ?? 25)
        );

        return redirect()
            ->route('admin.imports.index')
            ->with('status', 'Import completed successfully. Import #' . $import->id . ' processed ' . $import->processed_rows . ' rows.');
    }
}
