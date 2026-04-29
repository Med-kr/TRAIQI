<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesSchoolContext;
use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AcademicYearController extends Controller
{
    use HandlesSchoolContext;

    public function index()
    {
        $this->ensurePermission('manage academic years');

        return view('admin.academic-years.index', [
            'academicYears' => AcademicYear::forSchoolContext($this->currentAdmin())
                ->with('school')
                ->latest('start_date')
                ->get(),
        ]);
    }

    public function create()
    {
        $this->ensurePermission('manage academic years');

        return view('admin.academic-years.form', [
            'academicYear' => new AcademicYear(),
            'schools' => $this->availableSchools(),
        ]);
    }

    public function store(Request $request)
    {
        $this->ensurePermission('manage academic years');

        $schoolId = $this->resolveSchoolId($request->integer('school_id'));

        $validated = $request->validate([
            'school_id' => ['nullable', 'integer'],
            'name' => ['required', 'string', 'max:255', Rule::unique('academic_years', 'name')->where(fn ($query) => $query->where('school_id', $schoolId))],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'is_current' => ['nullable', 'boolean'],
        ]);

        $academicYear = AcademicYear::create([
            'school_id' => $schoolId,
            'name' => $validated['name'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'is_current' => (bool) ($validated['is_current'] ?? false),
        ]);

        $this->syncCurrentAcademicYear($academicYear);

        return redirect()
            ->route('admin.academic-years.index')
            ->with('status', 'Academic year created successfully.');
    }

    public function edit(int $academicYear)
    {
        $this->ensurePermission('manage academic years');

        $academicYear = AcademicYear::forSchoolContext($this->currentAdmin())
            ->findOrFail($academicYear);

        return view('admin.academic-years.form', [
            'academicYear' => $academicYear,
            'schools' => $this->availableSchools(),
        ]);
    }

    public function update(Request $request, int $academicYear)
    {
        $this->ensurePermission('manage academic years');

        $academicYear = AcademicYear::forSchoolContext($this->currentAdmin())
            ->findOrFail($academicYear);

        $schoolId = $this->resolveSchoolId($request->integer('school_id') ?: $academicYear->school_id);

        $validated = $request->validate([
            'school_id' => ['nullable', 'integer'],
            'name' => ['required', 'string', 'max:255', Rule::unique('academic_years', 'name')->ignore($academicYear->id)->where(fn ($query) => $query->where('school_id', $schoolId))],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'is_current' => ['nullable', 'boolean'],
        ]);

        $academicYear->update([
            'school_id' => $schoolId,
            'name' => $validated['name'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'is_current' => (bool) ($validated['is_current'] ?? false),
        ]);

        $this->syncCurrentAcademicYear($academicYear);

        return redirect()
            ->route('admin.academic-years.index')
            ->with('status', 'Academic year updated successfully.');
    }

    public function destroy(int $academicYear)
    {
        $this->ensurePermission('manage academic years');

        $academicYear = AcademicYear::forSchoolContext($this->currentAdmin())
            ->findOrFail($academicYear);

        abort_if($academicYear->classrooms()->exists() || $academicYear->evaluations()->exists(), 422, 'This academic year still has related data.');

        $academicYear->delete();

        return redirect()
            ->route('admin.academic-years.index')
            ->with('status', 'Academic year deleted successfully.');
    }
}
