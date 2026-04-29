<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesSchoolContext;
use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    use HandlesSchoolContext;

    public function index()
    {
        $this->ensurePermission('manage subjects');

        return view('admin.subjects.index', [
            'subjects' => Subject::forSchoolContext($this->currentAdmin())
                ->with('school')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function create()
    {
        $this->ensurePermission('manage subjects');

        return view('admin.subjects.form', [
            'subject' => new Subject(),
            'schools' => $this->availableSchools(),
        ]);
    }

    public function store(Request $request)
    {
        $this->ensurePermission('manage subjects');

        $schoolId = $this->resolveSchoolId($request->integer('school_id'));

        $validated = $request->validate([
            'school_id' => ['nullable', 'integer'],
            'name' => ['required', 'string', 'max:255', Rule::unique('subjects', 'name')->where(fn ($query) => $query->where('school_id', $schoolId))],
        ]);

        Subject::create([
            'school_id' => $schoolId,
            'name' => $validated['name'],
        ]);

        return redirect()
            ->route('admin.subjects.index')
            ->with('status', 'Subject created successfully.');
    }

    public function edit(int $subject)
    {
        $this->ensurePermission('manage subjects');

        $subject = Subject::forSchoolContext($this->currentAdmin())
            ->findOrFail($subject);

        return view('admin.subjects.form', [
            'subject' => $subject,
            'schools' => $this->availableSchools(),
        ]);
    }

    public function update(Request $request, int $subject)
    {
        $subject = Subject::forSchoolContext($this->currentAdmin())
            ->findOrFail($subject);

        $this->ensurePermission('manage subjects');

        $schoolId = $this->resolveSchoolId($request->integer('school_id') ?: $subject->school_id);

        $validated = $request->validate([
            'school_id' => ['nullable', 'integer'],
            'name' => ['required', 'string', 'max:255', Rule::unique('subjects', 'name')->ignore($subject->id)->where(fn ($query) => $query->where('school_id', $schoolId))],
        ]);

        $subject->update([
            'school_id' => $schoolId,
            'name' => $validated['name'],
        ]);

        return redirect()
            ->route('admin.subjects.index')
            ->with('status', 'Subject updated successfully.');
    }

    public function destroy(int $subject)
    {
        $this->ensurePermission('manage subjects');

        $subject = Subject::forSchoolContext($this->currentAdmin())
            ->findOrFail($subject);

        abort_if($subject->evaluations()->exists(), 422, 'This subject still has related data.');

        $subject->delete();

        return redirect()
            ->route('admin.subjects.index')
            ->with('status', 'Subject deleted successfully.');
    }
}
