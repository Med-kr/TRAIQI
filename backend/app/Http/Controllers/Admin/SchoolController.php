<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesSchoolContext;
use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SchoolController extends Controller
{
    use HandlesSchoolContext;

    public function index()
    {
        $this->ensurePermission('manage schools');

        return view('admin.schools.index', [
            'schools' => School::query()->withCount(['users', 'academicYears', 'classrooms'])->latest()->get(),
        ]);
    }

    public function create()
    {
        $this->ensurePermission('manage schools');

        return view('admin.schools.form', [
            'school' => new School(),
        ]);
    }

    public function store(Request $request)
    {
        $this->ensurePermission('manage schools');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('schools', 'name')],
        ]);

        School::create($validated);

        return redirect()
            ->route('admin.schools.index')
            ->with('status', 'School created successfully.');
    }

    public function edit(School $school)
    {
        $this->ensurePermission('manage schools');

        return view('admin.schools.form', [
            'school' => $school,
        ]);
    }

    public function update(Request $request, School $school)
    {
        $this->ensurePermission('manage schools');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('schools', 'name')->ignore($school->id)],
        ]);

        $school->update($validated);

        return redirect()
            ->route('admin.schools.index')
            ->with('status', 'School updated successfully.');
    }

    public function destroy(School $school)
    {
        $this->ensurePermission('manage schools');

        abort_if($school->users()->exists() || $school->academicYears()->exists() || $school->classrooms()->exists(), 422, 'This school still has related data.');

        $school->delete();

        return redirect()
            ->route('admin.schools.index')
            ->with('status', 'School deleted successfully.');
    }
}
