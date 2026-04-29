<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Level;
use App\Models\School;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Validation\ValidationException;

trait HandlesSchoolContext
{
    protected function currentAdmin(): User
    {
        return auth()->user();
    }

    protected function ensurePermission(string $permission): void
    {
        abort_unless($this->currentAdmin()->can($permission), 403);
    }

    protected function availableSchools()
    {
        return School::forSchoolContext($this->currentAdmin())
            ->orderBy('name')
            ->get();
    }

    protected function resolveSchoolId(?int $schoolId = null): int
    {
        $user = $this->currentAdmin();

        if ($user->hasRole('super_admin')) {
            if (! $schoolId) {
                throw ValidationException::withMessages([
                    'school_id' => 'The school field is required.',
                ]);
            }

            $schoolExists = School::query()->whereKey($schoolId)->exists();
            abort_unless($schoolExists, 404);

            return $schoolId;
        }

        abort_unless($user->school_id, 403);

        return $user->school_id;
    }

    protected function availableAcademicYears(?int $schoolId = null)
    {
        $query = AcademicYear::forSchoolContext($this->currentAdmin())
            ->with('school')
            ->orderByDesc('is_current')
            ->orderByDesc('start_date');

        if ($schoolId) {
            $query->where('school_id', $schoolId);
        }

        return $query->get();
    }

    protected function availableSubjects(?int $schoolId = null)
    {
        $query = Subject::forSchoolContext($this->currentAdmin())
            ->orderBy('name');

        if ($schoolId) {
            $query->where('school_id', $schoolId);
        }

        return $query->get();
    }

    protected function syncCurrentAcademicYear(AcademicYear $academicYear): void
    {
        if (! $academicYear->is_current) {
            return;
        }

        AcademicYear::where('school_id', $academicYear->school_id)
            ->where('id', '!=', $academicYear->id)
            ->update(['is_current' => false]);
    }

    protected function assertLevelInSchool(int $levelId, int $schoolId): Level
    {
        return Level::forSchoolContext($this->currentAdmin())
            ->where('school_id', $schoolId)
            ->findOrFail($levelId);
    }

    protected function assertAcademicYearInSchool(int $academicYearId, int $schoolId): AcademicYear
    {
        return AcademicYear::forSchoolContext($this->currentAdmin())
            ->where('school_id', $schoolId)
            ->findOrFail($academicYearId);
    }

    protected function assertClassroomInSchool(int $classroomId, int $schoolId): Classroom
    {
        return Classroom::forSchoolContext($this->currentAdmin())
            ->where('school_id', $schoolId)
            ->findOrFail($classroomId);
    }
}
