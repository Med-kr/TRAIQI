<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesSchoolContext;
use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\TeacherAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TeacherAssignmentController extends Controller
{
    use HandlesSchoolContext;

    public function index()
    {
        $this->ensurePermission('manage teacher assignments');

        return view('admin.teacher-assignments.index', [
            'assignments' => TeacherAssignment::forSchoolContext($this->currentAdmin())
                ->with(['school', 'teacher', 'classroom', 'subject'])
                ->latest()
                ->get(),
        ]);
    }

    public function create()
    {
        $this->ensurePermission('manage teacher assignments');

        return view('admin.teacher-assignments.form', [
            'assignment' => new TeacherAssignment(),
            'schools' => $this->availableSchools(),
            'teachers' => User::forSchoolContext($this->currentAdmin())
                ->role('teacher')
                ->orderBy('name')
                ->get(),
            'classrooms' => Classroom::forSchoolContext($this->currentAdmin())
                ->with('subjects')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $this->ensurePermission('manage teacher assignments');

        $schoolId = $this->resolveSchoolId($request->integer('school_id'));

        $validated = $request->validate([
            'school_id' => ['nullable', 'integer'],
            'teacher_id' => ['required', 'integer'],
            'classroom_id' => ['required', 'integer'],
            'subject_id' => [
                'required',
                'integer',
                Rule::unique('teacher_assignments')->where(fn ($query) => $query
                    ->where('school_id', $schoolId)
                    ->where('teacher_id', $request->integer('teacher_id'))
                    ->where('classroom_id', $request->integer('classroom_id'))
                    ->where('subject_id', $request->integer('subject_id'))),
            ],
        ]);

        $teacher = User::forSchoolContext($this->currentAdmin())
            ->role('teacher')
            ->where('school_id', $schoolId)
            ->findOrFail($validated['teacher_id']);

        $classroom = Classroom::forSchoolContext($this->currentAdmin())
            ->where('school_id', $schoolId)
            ->with('subjects')
            ->findOrFail($validated['classroom_id']);

        abort_unless($classroom->subjects->contains('id', $validated['subject_id']), 422, 'This subject is not linked to the selected classroom.');

        TeacherAssignment::create([
            'school_id' => $schoolId,
            'teacher_id' => $teacher->id,
            'classroom_id' => $classroom->id,
            'subject_id' => $validated['subject_id'],
        ]);

        return redirect()
            ->route('admin.teacher-assignments.index')
            ->with('status', 'Teacher assignment created successfully.');
    }

    public function edit(int $teacherAssignment)
    {
        $this->ensurePermission('manage teacher assignments');

        $assignment = TeacherAssignment::forSchoolContext($this->currentAdmin())
            ->findOrFail($teacherAssignment);

        return view('admin.teacher-assignments.form', [
            'assignment' => $assignment,
            'schools' => $this->availableSchools(),
            'teachers' => User::forSchoolContext($this->currentAdmin())
                ->role('teacher')
                ->orderBy('name')
                ->get(),
            'classrooms' => Classroom::forSchoolContext($this->currentAdmin())
                ->with('subjects')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function update(Request $request, int $teacherAssignment)
    {
        $this->ensurePermission('manage teacher assignments');

        $assignment = TeacherAssignment::forSchoolContext($this->currentAdmin())
            ->findOrFail($teacherAssignment);

        $schoolId = $this->resolveSchoolId($request->integer('school_id') ?: $assignment->school_id);

        $validated = $request->validate([
            'school_id' => ['nullable', 'integer'],
            'teacher_id' => ['required', 'integer'],
            'classroom_id' => ['required', 'integer'],
            'subject_id' => [
                'required',
                'integer',
                Rule::unique('teacher_assignments')->ignore($assignment->id)->where(fn ($query) => $query
                    ->where('school_id', $schoolId)
                    ->where('teacher_id', $request->integer('teacher_id'))
                    ->where('classroom_id', $request->integer('classroom_id'))
                    ->where('subject_id', $request->integer('subject_id'))),
            ],
        ]);

        $teacher = User::forSchoolContext($this->currentAdmin())
            ->role('teacher')
            ->where('school_id', $schoolId)
            ->findOrFail($validated['teacher_id']);

        $classroom = Classroom::forSchoolContext($this->currentAdmin())
            ->where('school_id', $schoolId)
            ->with('subjects')
            ->findOrFail($validated['classroom_id']);

        abort_unless($classroom->subjects->contains('id', $validated['subject_id']), 422, 'This subject is not linked to the selected classroom.');

        $assignment->update([
            'school_id' => $schoolId,
            'teacher_id' => $teacher->id,
            'classroom_id' => $classroom->id,
            'subject_id' => $validated['subject_id'],
        ]);

        return redirect()
            ->route('admin.teacher-assignments.index')
            ->with('status', 'Teacher assignment updated successfully.');
    }

    public function destroy(int $teacherAssignment)
    {
        $this->ensurePermission('manage teacher assignments');

        $assignment = TeacherAssignment::forSchoolContext($this->currentAdmin())
            ->findOrFail($teacherAssignment);

        abort_if($assignment->evaluationCount() > 0, 422, 'This assignment already has evaluations.');

        $assignment->delete();

        return redirect()
            ->route('admin.teacher-assignments.index')
            ->with('status', 'Teacher assignment deleted successfully.');
    }
}
