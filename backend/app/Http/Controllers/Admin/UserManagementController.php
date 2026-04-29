<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesSchoolContext;
use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Level;
use App\Models\School;
use App\Models\User;
use App\Services\UserProvisioningService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    use HandlesSchoolContext;

    public function __construct(protected UserProvisioningService $provisioningService)
    {
    }

    public function index(Request $request)
    {
        $this->ensurePermission('manage users');

        $role = $request->string('role')->value();
        $status = $request->string('status')->value();
        $search = trim((string) $request->string('search')->value());

        $users = User::forSchoolContext($this->currentAdmin())
            ->with(['roles', 'studentProfile.classroom', 'parentProfile', 'teacherProfile', 'children'])
            ->when($role, fn ($query) => $query->role($role))
            ->when($status === 'active', fn ($query) => $query->where('is_active', true))
            ->when($status === 'inactive', fn ($query) => $query->where('is_active', false))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('global_code', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'selectedRole' => $role,
            'selectedStatus' => $status,
            'search' => $search,
        ]);
    }

    public function create()
    {
        $this->ensurePermission('manage users');

        return view('admin.users.form', [
            'managedUser' => new User(),
            'levels' => Level::forSchoolContext($this->currentAdmin())->orderBy('order')->get(),
            'classrooms' => Classroom::forSchoolContext($this->currentAdmin())->orderBy('name')->get(),
            'students' => User::forSchoolContext($this->currentAdmin())->role('student')->orderBy('name')->get(),
            'assignableRoles' => $this->assignableRoles(),
        ]);
    }

    public function store(Request $request)
    {
        $this->ensurePermission('manage users');

        $validated = $this->validateUser($request);
        $school = $this->resolveManagedSchool($validated);

        $result = match ($validated['role']) {
            'student' => $this->provisioningService->upsertStudent($school, $validated),
            'parent' => $this->provisioningService->upsertParent($school, $validated),
            'teacher' => $this->provisioningService->upsertTeacher($school, $validated),
            'school_admin' => $this->provisioningService->upsertAdministration($school, $validated, 'school_admin'),
            'super_admin' => $this->provisioningService->upsertAdministration($school, $validated, 'super_admin'),
            default => abort(422, 'Unsupported role.'),
        };

        $user = $result['user'];
        $user->update(['is_active' => (bool) ($validated['is_active'] ?? true)]);

        if ($validated['role'] === 'student' && ! empty($validated['classroom_id'])) {
            $user->studentProfile()->updateOrCreate(['user_id' => $user->id], ['classroom_id' => $validated['classroom_id'], 'phone' => $validated['phone'] ?? null]);
        }

        if ($validated['role'] === 'parent' && ! empty($validated['children'])) {
            $user->children()->sync($validated['children']);
        }

        return redirect()
            ->route('admin.user-management.index', ['role' => $validated['role']])
            ->with('status', 'User created successfully.' . ($result['generated_password'] ? ' Initial password: ' . $result['generated_password'] : ''));
    }

    public function edit(int $user)
    {
        $this->ensurePermission('manage users');

        $managedUser = User::forSchoolContext($this->currentAdmin())
            ->with(['roles', 'studentProfile', 'parentProfile', 'teacherProfile', 'children'])
            ->findOrFail($user);

        return view('admin.users.form', [
            'managedUser' => $managedUser,
            'levels' => Level::forSchoolContext($this->currentAdmin())->orderBy('order')->get(),
            'classrooms' => Classroom::forSchoolContext($this->currentAdmin())->orderBy('name')->get(),
            'students' => User::forSchoolContext($this->currentAdmin())->role('student')->orderBy('name')->get(),
            'assignableRoles' => $this->assignableRoles(),
        ]);
    }

    public function update(Request $request, int $user)
    {
        $this->ensurePermission('manage users');

        $managedUser = User::forSchoolContext($this->currentAdmin())->with('roles')->findOrFail($user);
        $validated = $this->validateUser($request, $managedUser);
        $school = $this->resolveManagedSchool($validated, $managedUser);

        $validated['email'] = $managedUser->email;

        $result = match ($validated['role']) {
            'student' => $this->provisioningService->upsertStudent($school, $validated),
            'parent' => $this->provisioningService->upsertParent($school, $validated),
            'teacher' => $this->provisioningService->upsertTeacher($school, $validated),
            'school_admin' => $this->provisioningService->upsertAdministration($school, $validated, 'school_admin'),
            'super_admin' => $this->provisioningService->upsertAdministration($school, $validated, 'super_admin'),
            default => abort(422, 'Unsupported role.'),
        };

        $userModel = $result['user'];
        $userModel->update(['is_active' => (bool) ($validated['is_active'] ?? $userModel->is_active)]);

        if ($validated['role'] === 'student') {
            $userModel->studentProfile()->updateOrCreate(
                ['user_id' => $userModel->id],
                [
                    'classroom_id' => $validated['classroom_id'] ?? null,
                    'phone' => $validated['phone'] ?? null,
                ]
            );
        }

        if ($validated['role'] === 'parent') {
            $userModel->children()->sync($validated['children'] ?? []);
        }

        return redirect()
            ->route('admin.user-management.index', ['role' => $validated['role']])
            ->with('status', 'User updated successfully.');
    }

    public function destroy(int $user)
    {
        $this->ensurePermission('manage users');

        $managedUser = User::forSchoolContext($this->currentAdmin())->findOrFail($user);

        abort_if($managedUser->id === $this->currentAdmin()->id, 422, 'You cannot delete your own account.');

        $managedUser->delete();

        return redirect()
            ->route('admin.user-management.index')
            ->with('status', 'User deleted successfully.');
    }

    protected function validateUser(Request $request, ?User $user = null): array
    {
        $validated = $request->validate([
            'school_id' => ['nullable', 'integer'],
            'role' => ['required', Rule::in($this->assignableRoles())],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user?->id)],
            'phone' => ['nullable', 'string', 'max:50'],
            'level_code' => ['nullable', 'string', 'max:50'],
            'level_name' => ['nullable', 'string', 'max:255'],
            'classroom_id' => ['nullable', 'integer'],
            'specialty' => ['nullable', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'children' => ['nullable', 'array'],
            'children.*' => ['integer'],
        ]);

        $school = $this->resolveManagedSchool($validated, $user);

        if (($validated['role'] ?? null) === 'student' && ! empty($validated['classroom_id'])) {
            $this->assertClassroomInSchool((int) $validated['classroom_id'], $school->id);
        }

        if (($validated['role'] ?? null) === 'parent' && ! empty($validated['children'])) {
            $allowedStudentIds = User::forSchoolContext($this->currentAdmin())
                ->where('school_id', $school->id)
                ->role('student')
                ->whereIn('id', $validated['children'])
                ->pluck('id')
                ->all();

            abort_unless(count($allowedStudentIds) === count($validated['children']), 422, 'One or more selected children are invalid for this school.');
        }

        return $validated;
    }

    protected function assignableRoles(): array
    {
        $roles = ['student', 'parent', 'teacher', 'school_admin'];

        if ($this->currentAdmin()->hasRole('super_admin')) {
            $roles[] = 'super_admin';
        }

        return $roles;
    }

    protected function resolveManagedSchool(array $validated, ?User $managedUser = null): School
    {
        if (($validated['role'] ?? null) === 'super_admin') {
            return new School(['id' => null, 'name' => 'Global']);
        }

        $requestedSchoolId = $validated['school_id'] ?? $managedUser?->school_id;
        $schoolId = $this->resolveSchoolId($requestedSchoolId ? (int) $requestedSchoolId : null);

        return $this->availableSchools()->firstWhere('id', $schoolId)
            ?? School::query()->findOrFail($schoolId);
    }
}
