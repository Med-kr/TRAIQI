@extends('layouts.school-admin')

@section('title', 'Utilisateurs')

@section('content')
    @php
        $tabs = ['Élèves', 'Parents', 'Enseignants', 'Staff'];
        $users = [
            ['name' => 'Sara El Idrissi', 'email' => 's.elidrissi@school.ma', 'role' => 'Élève', 'class' => '2AC-B', 'phone' => '+212 6 11 22 33 44', 'status' => 'Actif'],
            ['name' => 'Youssef Benali', 'email' => 'y.benali@school.ma', 'role' => 'Parent', 'class' => '1BAC-SM', 'phone' => '+212 6 52 11 45 90', 'status' => 'Actif'],
            ['name' => 'Nadia Chraibi', 'email' => 'n.chraibi@school.ma', 'role' => 'Enseignant', 'class' => 'Terminale PC', 'phone' => '+212 6 65 10 00 15', 'status' => 'Inactif'],
            ['name' => 'Hicham Lamrani', 'email' => 'h.lamrani@school.ma', 'role' => 'Staff', 'class' => 'Administration', 'phone' => '+212 6 00 22 11 88', 'status' => 'Actif'],
        ];
    @endphp

    <section class="space-y-6 lg:space-y-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0A4FAF]">Communauté scolaire</p>
                <h1 class="mt-2 text-3xl font-semibold text-slate-950 sm:text-4xl">Utilisateurs</h1>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Gérez les élèves, parents, enseignants et staff depuis une table claire, filtrable et dense, pensée pour une vraie charge opérationnelle.</p>
            </div>

            <x-button :href="route('admin.user-management.create')">Ajouter utilisateur</x-button>
        </div>

        <section class="admin-card p-5 sm:p-6">
            <div class="grid gap-4 xl:grid-cols-[1.1fr_0.7fr_0.7fr_auto]">
                <input type="search" class="admin-toolbar-input" placeholder="Rechercher un utilisateur">
                <select class="admin-toolbar-input">
                    <option>Tous les rôles</option>
                    <option>Élèves</option>
                    <option>Parents</option>
                    <option>Enseignants</option>
                    <option>Staff</option>
                </select>
                <select class="admin-toolbar-input">
                    <option>Tous les statuts</option>
                    <option>Actif</option>
                    <option>Inactif</option>
                </select>
                <x-button variant="secondary" class="w-full justify-center">Filtrer</x-button>
            </div>

            <div class="mt-5 flex flex-wrap gap-2">
                @foreach ($tabs as $index => $tab)
                    <button type="button" class="rounded-2xl px-4 py-2 text-sm font-semibold {{ $index === 0 ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600' }}">
                        {{ $tab }}
                    </button>
                @endforeach
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
                            <th>Classe</th>
                            <th>Téléphone</th>
                            <th>Statut</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($users as $user)
                            <tr class="transition hover:bg-slate-50/80">
                                <td>
                                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-[#0A4FAF]/10 text-sm font-semibold text-[#0A4FAF]">
                                        {{ \Illuminate\Support\Str::of($user['name'])->explode(' ')->map(fn ($part) => mb_substr($part, 0, 1))->take(2)->implode('') }}
                                    </span>
                                </td>
                                <td class="font-semibold text-slate-950">{{ $user['name'] }}</td>
                                <td>{{ $user['email'] }}</td>
                                <td>{{ $user['role'] }}</td>
                                <td>{{ $user['class'] }}</td>
                                <td>{{ $user['phone'] }}</td>
                                <td><span class="admin-pill {{ $user['status'] === 'Actif' ? 'is-success' : 'is-warning' }}">{{ $user['status'] }}</span></td>
                                <td>
                                    <div class="flex justify-end gap-2">
                                        <x-button href="#" variant="ghost" size="sm">Voir</x-button>
                                        <x-button :href="route('admin.user-management.edit', 1)" variant="secondary" size="sm">Modifier</x-button>
                                        <x-button href="#" variant="dark" size="sm">Désactiver</x-button>
                                        <form method="POST" action="{{ route('admin.user-management.destroy', 1) }}">
                                            @csrf
                                            @method('DELETE')
                                            <x-button type="submit" variant="ghost" size="sm" class="text-red-600 hover:bg-red-50 hover:text-red-700">Supprimer</x-button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </section>
@endsection
