@extends('layouts.school-admin')

@section('title', 'Utilisateurs')

@section('content')
    @php
        $roleTabs = [
            'student' => 'Élèves',
            'parent' => 'Parents',
            'teacher' => 'Enseignants',
            'school_admin' => 'Staff',
        ];

        $roleLabels = [
            'student' => 'Élève',
            'parent' => 'Parent',
            'teacher' => 'Enseignant',
            'school_admin' => 'Staff',
            'super_admin' => 'Super admin',
        ];

        $profilePhone = function ($user) {
            return $user->studentProfile?->phone
                ?? $user->parentProfile?->phone
                ?? $user->teacherProfile?->phone
                ?? $user->administrationProfile?->phone
                ?? '-';
        };

        $classOrScope = function ($user) {
            $role = $user->primaryRole();

            return match ($role) {
                'student' => $user->studentProfile?->classroom?->name ?? '-',
                'parent' => $user->children->pluck('name')->take(2)->join(', ') ?: '-',
                'teacher' => $user->teacherProfile?->specialty ?? '-',
                'school_admin', 'super_admin' => $user->administrationProfile?->position ?? 'Administration',
                default => '-',
            };
        };
    @endphp

    <section class="space-y-6 lg:space-y-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0A4FAF]">Communauté scolaire</p>
                <h1 class="mt-2 text-3xl font-semibold text-slate-950 sm:text-4xl">Utilisateurs</h1>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Gérez les élèves, parents, enseignants et staff depuis une table claire, filtrable et dense.</p>
            </div>

            <x-button :href="route('admin.user-management.create')">Ajouter utilisateur</x-button>
        </div>

        <section class="admin-card p-5 sm:p-6">
            <form method="GET" action="{{ route('admin.user-management.index') }}" class="grid gap-4 xl:grid-cols-[1.1fr_0.7fr_0.7fr_auto]">
                <input type="search" name="search" value="{{ $search }}" class="admin-toolbar-input" placeholder="Rechercher un utilisateur">

                <select name="role" class="admin-toolbar-input">
                    <option value="">Tous les rôles</option>
                    @foreach ($roleTabs as $role => $label)
                        <option value="{{ $role }}" @selected($selectedRole === $role)>{{ $label }}</option>
                    @endforeach
                </select>

                <select name="status" class="admin-toolbar-input">
                    <option value="">Tous les statuts</option>
                    <option value="active" @selected($selectedStatus === 'active')>Actif</option>
                    <option value="inactive" @selected($selectedStatus === 'inactive')>Inactif</option>
                </select>

                <x-button type="submit" variant="secondary" class="w-full justify-center">Filtrer</x-button>
            </form>

            <div class="mt-5 flex flex-wrap gap-2">
                @foreach ($roleTabs as $role => $label)
                    <a href="{{ route('admin.user-management.index', array_filter(['role' => $role, 'status' => $selectedStatus, 'search' => $search])) }}"
                        class="rounded-2xl px-4 py-2 text-sm font-semibold {{ $selectedRole === $role ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600' }}">
                        {{ $label }}
                    </a>
                @endforeach

                @if($selectedRole || $selectedStatus || $search)
                    <a href="{{ route('admin.user-management.index') }}" class="rounded-2xl px-4 py-2 text-sm font-semibold bg-slate-100 text-slate-600">
                        Réinitialiser
                    </a>
                @endif
            </div>
        </section>

        <section class="admin-table-wrap">
            <div class="overflow-x-auto">
                <table class="admin-table min-w-[980px]">
                    <thead>
                        <tr>
                            <th>Avatar</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Classe / portée</th>
                            <th>Téléphone</th>
                            <th>Statut</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($users as $user)
                            @php
                                $role = $user->primaryRole();
                                $initials = \Illuminate\Support\Str::of($user->name)->explode(' ')->map(fn ($part) => mb_substr($part, 0, 1))->take(2)->implode('');
                            @endphp
                            <tr class="transition hover:bg-slate-50/80">
                                <td>
                                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-[#0A4FAF]/10 text-sm font-semibold text-[#0A4FAF]">
                                        {{ $initials }}
                                    </span>
                                </td>
                                <td class="font-semibold text-slate-950">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $roleLabels[$role] ?? $role }}</td>
                                <td>{{ $classOrScope($user) }}</td>
                                <td>{{ $profilePhone($user) }}</td>
                                <td><span class="admin-pill {{ $user->is_active ? 'is-success' : 'is-warning' }}">{{ $user->is_active ? 'Actif' : 'Inactif' }}</span></td>
                                <td>
                                    <div class="flex justify-end gap-2">
                                        <x-button :href="route('admin.user-management.edit', $user)" variant="secondary" size="sm">Modifier</x-button>
                                        @if($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('admin.user-management.destroy', $user) }}" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                                @csrf
                                                @method('DELETE')
                                                <x-button type="submit" variant="ghost" size="sm" class="text-red-600 hover:bg-red-50 hover:text-red-700">Supprimer</x-button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-sm text-slate-500">Aucun utilisateur trouvé pour ce filtre.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="border-t border-slate-100 px-4 py-4">
                    {{ $users->links() }}
                </div>
            @endif
        </section>
    </section>
@endsection
