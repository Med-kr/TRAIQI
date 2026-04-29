<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesSchoolContext;
use App\Http\Controllers\Controller;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LevelController extends Controller
{
    use HandlesSchoolContext;

    public function index()
    {
        $this->ensurePermission('manage levels');

        return view('admin.levels.index', [
            'levels' => Level::forSchoolContext($this->currentAdmin())
                ->with('school')
                ->orderBy('order')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function create()
    {
        $this->ensurePermission('manage levels');

        return view('admin.levels.form', [
            'level' => new Level(),
            'schools' => $this->availableSchools(),
        ]);
    }

    public function store(Request $request)
    {
        $this->ensurePermission('manage levels');

        $schoolId = $this->resolveSchoolId($request->integer('school_id'));

        $validated = $request->validate([
            'school_id' => ['nullable', 'integer'],
            'code' => ['required', 'string', 'max:50', Rule::unique('levels', 'code')->where(fn ($query) => $query->where('school_id', $schoolId))],
            'name' => ['required', 'string', 'max:255'],
            'order' => ['required', 'integer', 'min:1'],
        ]);

        Level::create([
            'school_id' => $schoolId,
            'code' => strtoupper($validated['code']),
            'name' => $validated['name'],
            'order' => $validated['order'],
        ]);

        return redirect()
            ->route('admin.levels.index')
            ->with('status', 'Level created successfully.');
    }

    public function edit(int $level)
    {
        $this->ensurePermission('manage levels');

        $level = Level::forSchoolContext($this->currentAdmin())
            ->findOrFail($level);

        return view('admin.levels.form', [
            'level' => $level,
            'schools' => $this->availableSchools(),
        ]);
    }

    public function update(Request $request, int $level)
    {
        $this->ensurePermission('manage levels');

        $level = Level::forSchoolContext($this->currentAdmin())
            ->findOrFail($level);

        $schoolId = $this->resolveSchoolId($request->integer('school_id') ?: $level->school_id);

        $validated = $request->validate([
            'school_id' => ['nullable', 'integer'],
            'code' => ['required', 'string', 'max:50', Rule::unique('levels', 'code')->ignore($level->id)->where(fn ($query) => $query->where('school_id', $schoolId))],
            'name' => ['required', 'string', 'max:255'],
            'order' => ['required', 'integer', 'min:1'],
        ]);

        $level->update([
            'school_id' => $schoolId,
            'code' => strtoupper($validated['code']),
            'name' => $validated['name'],
            'order' => $validated['order'],
        ]);

        return redirect()
            ->route('admin.levels.index')
            ->with('status', 'Level updated successfully.');
    }

    public function destroy(int $level)
    {
        $this->ensurePermission('manage levels');

        $level = Level::forSchoolContext($this->currentAdmin())
            ->findOrFail($level);

        abort_if($level->users()->exists() || $level->classrooms()->exists(), 422, 'This level still has related data.');

        $level->delete();

        return redirect()
            ->route('admin.levels.index')
            ->with('status', 'Level deleted successfully.');
    }
}
