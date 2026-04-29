@extends('layouts.admin')

@section('title', 'Admins des ecoles')

@section('content')
    @php
        $admins = [
            ['name' => 'Laila Benjelloun', 'email' => 'laila@atlas.ma', 'school' => 'Lycée Atlas Excellence', 'last_login' => 'Aujourd’hui, 08:14', 'status' => 'Actif'],
            ['name' => 'Yassine El Fassi', 'email' => 'yassine@alamal.ma', 'school' => 'Groupe Scolaire Al Amal', 'last_login' => 'Hier, 18:22', 'status' => 'Actif'],
            ['name' => 'Meryem Boussaid', 'email' => 'meryem@alqods.ma', 'school' => 'Ecole Al Qods', 'last_login' => 'Il y a 2 jours', 'status' => 'Inactif'],
            ['name' => 'Hicham Berrada', 'email' => 'hicham@nour.ma', 'school' => 'Institut Nour', 'last_login' => 'Jamais', 'status' => 'Provisionné'],
        ];

        $skeletonRows = range(1, 3);
    @endphp

    <section class="space-y-6 lg:space-y-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0A4FAF]">Comptes établissements</p>
                <h1 class="mt-2 text-3xl font-semibold text-slate-950 sm:text-4xl">Admins des écoles</h1>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Suivez les profils administrateurs, leur activité récente et leur rattachement institutionnel depuis un tableau de pilotage compact et professionnel.</p>
            </div>

            <x-button :href="route('admin.user-management.create')">Ajouter un admin</x-button>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.15fr_0.85fr]">
            <div class="space-y-6">
                <section class="admin-card p-5 sm:p-6">
                    <div class="grid gap-4 lg:grid-cols-[1.1fr_0.9fr_auto]">
                        <input type="search" class="admin-toolbar-input" placeholder="Rechercher un admin">
                        <select class="admin-toolbar-input">
                            <option>Toutes les écoles</option>
                            <option>Lycée Atlas Excellence</option>
                            <option>Groupe Scolaire Al Amal</option>
                            <option>Ecole Al Qods</option>
                        </select>
                        <x-button variant="secondary" class="w-full justify-center">Filtrer</x-button>
                    </div>
                </section>

                <section class="admin-table-wrap">
                    <div class="overflow-x-auto">
                        <table class="admin-table min-w-[860px]">
                            <thead>
                                <tr>
                                    <th>Avatar</th>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>École liée</th>
                                    <th>Dernière connexion</th>
                                    <th>Status</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($admins as $admin)
                                    <tr class="transition hover:bg-slate-50/80">
                                        <td>
                                            <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-[#0A4FAF]/10 text-sm font-semibold text-[#0A4FAF]">
                                                {{ \Illuminate\Support\Str::of($admin['name'])->explode(' ')->map(fn ($part) => mb_substr($part, 0, 1))->take(2)->implode('') }}
                                            </span>
                                        </td>
                                        <td class="font-semibold text-slate-950">{{ $admin['name'] }}</td>
                                        <td>{{ $admin['email'] }}</td>
                                        <td>{{ $admin['school'] }}</td>
                                        <td>{{ $admin['last_login'] }}</td>
                                        <td>
                                            <span class="admin-pill {{ $admin['status'] === 'Actif' ? 'is-success' : ($admin['status'] === 'Inactif' ? 'is-warning' : 'is-neutral') }}">
                                                {{ $admin['status'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="flex justify-end gap-2">
                                                <x-button :href="route('admin.user-management.edit', 1)" variant="secondary" size="sm">Modifier</x-button>
                                                <x-button href="#" variant="ghost" size="sm">Voir</x-button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <aside class="space-y-6">
                <section class="admin-card p-6 sm:p-7">
                    <h2 class="text-xl font-semibold text-slate-950">Synthèse</h2>
                    <div class="mt-6 grid gap-4">
                        <div class="rounded-[1.5rem] bg-slate-50 p-5">
                            <p class="text-sm text-slate-500">Admins actifs</p>
                            <p class="mt-3 text-3xl font-semibold text-slate-950">182</p>
                        </div>
                        <div class="rounded-[1.5rem] bg-slate-50 p-5">
                            <p class="text-sm text-slate-500">Inactifs</p>
                            <p class="mt-3 text-3xl font-semibold text-slate-950">24</p>
                        </div>
                        <div class="rounded-[1.5rem] bg-slate-50 p-5">
                            <p class="text-sm text-slate-500">Dernières connexions</p>
                            <p class="mt-3 text-3xl font-semibold text-slate-950">91%</p>
                        </div>
                    </div>
                </section>

                <section class="admin-card p-6 sm:p-7">
                    <div class="flex items-center justify-between gap-4">
                        <h2 class="text-xl font-semibold text-slate-950">Loading skeleton</h2>
                        <span class="admin-pill is-neutral">UX</span>
                    </div>
                    <div class="mt-6 space-y-4">
                        @foreach ($skeletonRows as $row)
                            <div class="flex items-center gap-4 rounded-2xl border border-slate-100 p-4">
                                <div class="h-12 w-12 animate-pulse rounded-2xl bg-slate-100"></div>
                                <div class="flex-1 space-y-3">
                                    <div class="h-3.5 w-1/3 animate-pulse rounded-full bg-slate-100"></div>
                                    <div class="h-3 w-2/3 animate-pulse rounded-full bg-slate-100"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            </aside>
        </div>
    </section>
@endsection
