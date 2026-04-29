<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesSchoolContext;
use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClassroomController extends Controller
{
    use HandlesSchoolContext;

    public function index()
    {
        $this->ensurePermission('manage classrooms');

        return view('admin.classrooms.index', [
            'classrooms' => Classroom::forSchoolContext($this->currentAdmin())
                ->with(['school', 'academicYear', 'level', 'subjects'])
                ->latest()
                ->get(),
        ]);
    }

    public function create()
    {
        $this->ensurePermission('manage classrooms');

        return view('admin.classrooms.form', [
            'classroom' => new Classroom(),
            'schools' => $this->availableSchools(),
            'levels' => Level::forSchoolContext($this->currentAdmin())->orderBy('order')->get(),
            'academicYears' => $this->availableAcademicYears(),
            'subjects' => $this->availableSubjects(),
        ]);
    }

    public function store(Request $request)
    {
        $this->ensurePermission('manage classrooms');

        $schoolId = $this->resolveSchoolId($request->integer('school_id'));

        $validated = $request->validate([
            'school_id' => ['nullable', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'level_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255', Rule::unique('classrooms', 'name')->where(fn ($query) => $query->where('school_id', $schoolId)->where('academic_year_id', $request->integer('academic_year_id')))],
            'subject_ids' => ['nullable', 'array'],
            'subject_ids.*' => ['integer'],
        ]);

        $academicYear = $this->assertAcademicYearInSchool($validated['academic_year_id'], $schoolId);
        $level = $this->assertLevelInSchool($validated['level_id'], $schoolId);

        $classroom = Classroom::create([
            'school_id' => $schoolId,
            'academic_year_id' => $academicYear->id,
            'level_id' => $level->id,
            'name' => $validated['name'],
        ]);

        $subjectIds = $this->availableSubjects($schoolId)
            ->whereIn('id', $validated['subject_ids'] ?? [])
            ->pluck('id')
            ->all();

        $classroom->subjects()->sync($subjectIds);

        return redirect()
            ->route('admin.classrooms.index')
            ->with('status', 'Classroom created successfully.');
    }

    public function edit(int $classroom)
    {
        $this->ensurePermission('manage classrooms');

        $classroom = Classroom::forSchoolContext($this->currentAdmin())
            ->findOrFail($classroom);

        return view('admin.classrooms.form', [
            'classroom' => $classroom,
            'schools' => $this->availableSchools(),
            'levels' => Level::forSchoolContext($this->currentAdmin())->orderBy('order')->get(),
            'academicYears' => $this->availableAcademicYears(),
            'subjects' => $this->availableSubjects(),
        ]);
    }

    public function update(Request $request, int $classroom)
    {
        $this->ensurePermission('manage classrooms');

        $classroom = Classroom::forSchoolContext($this->currentAdmin())
            ->findOrFail($classroom);

        $schoolId = $this->resolveSchoolId($request->integer('school_id') ?: $classroom->school_id);

        $validated = $request->validate([
            'school_id' => ['nullable', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'level_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255', Rule::unique('classrooms', 'name')->ignore($classroom->id)->where(fn ($query) => $query->where('school_id', $schoolId)->where('academic_year_id', $request->integer('academic_year_id')))],
            'subject_ids' => ['nullable', 'array'],
            'subject_ids.*' => ['integer'],
        ]);

        $academicYear = $this->assertAcademicYearInSchool($validated['academic_year_id'], $schoolId);
        $level = $this->assertLevelInSchool($validated['level_id'], $schoolId);

        $classroom->update([
            'school_id' => $schoolId,
            'academic_year_id' => $academicYear->id,
            'level_id' => $level->id,
            'name' => $validated['name'],
        ]);

        $subjectIds = $this->availableSubjects($schoolId)
            ->whereIn('id', $validated['subject_ids'] ?? [])
            ->pluck('id')
            ->all();

        $classroom->subjects()->sync($subjectIds);

        return redirect()
            ->route('admin.classrooms.index')
            ->with('status', 'Classroom updated successfully.');
    }

    public function destroy(int $classroom)
    {
        $this->ensurePermission('manage classrooms');

        $classroom = Classroom::forSchoolContext($this->currentAdmin())
            ->findOrFail($classroom);

        abort_if($classroom->students()->exists() || $classroom->evaluations()->exists(), 422, 'This classroom still has related data.');

        $classroom->delete();

        return redirect()
            ->route('admin.classrooms.index')
            ->with('status', 'Classroom deleted successfully.');
    }
}
