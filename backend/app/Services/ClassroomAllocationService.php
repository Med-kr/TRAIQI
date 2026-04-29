<?php

namespace App\Services;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Level;
use App\Models\School;
use App\Models\StudentProfile;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ClassroomAllocationService
{
    public function allocateForSchool(School $school, int $maxStudentsPerClass = 25): array
    {
        $academicYear = AcademicYear::query()
            ->where('school_id', $school->id)
            ->orderByDesc('is_current')
            ->orderByDesc('start_date')
            ->first();

        if (! $academicYear) {
            return [
                'allocated_students' => 0,
                'created_classrooms' => 0,
                'updated_classrooms' => 0,
                'classrooms' => [],
            ];
        }

        $createdClassrooms = 0;
        $updatedClassrooms = 0;
        $allocatedStudents = 0;
        $classroomNames = [];

        $levels = Level::query()
            ->where('school_id', $school->id)
            ->orderBy('order')
            ->get();

        foreach ($levels as $level) {
            $students = StudentProfile::query()
                ->whereHas('user', fn ($query) => $query->where('school_id', $school->id)->where('level_id', $level->id))
                ->with('user')
                ->orderBy('id')
                ->get();

            if ($students->isEmpty()) {
                continue;
            }

            $chunks = $students->chunk(max(1, $maxStudentsPerClass));

            foreach ($chunks as $index => $chunk) {
                $classroomName = sprintf('%s-%s', $level->code, Str::upper($this->indexToLetters($index)));

                $classroom = Classroom::firstOrCreate(
                    [
                        'school_id' => $school->id,
                        'academic_year_id' => $academicYear->id,
                        'level_id' => $level->id,
                        'name' => $classroomName,
                    ]
                );

                if ($classroom->wasRecentlyCreated) {
                    $createdClassrooms++;
                } else {
                    $updatedClassrooms++;
                }

                $chunk->each(function (StudentProfile $profile) use ($classroom, &$allocatedStudents) {
                    if ($profile->classroom_id !== $classroom->id) {
                        $profile->update(['classroom_id' => $classroom->id]);
                    }

                    $allocatedStudents++;
                });

                $classroomNames[] = $classroom->name;
            }
        }

        return [
            'allocated_students' => $allocatedStudents,
            'created_classrooms' => $createdClassrooms,
            'updated_classrooms' => $updatedClassrooms,
            'classrooms' => array_values(array_unique($classroomNames)),
        ];
    }

    protected function indexToLetters(int $index): string
    {
        $letters = '';
        $current = $index;

        do {
            $letters = chr(65 + ($current % 26)) . $letters;
            $current = intdiv($current, 26) - 1;
        } while ($current >= 0);

        return $letters;
    }
}
