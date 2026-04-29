@extends('layouts.admin')

@section('title', 'Dashboard Super Admin')

@section('content')
    @php
        $kpis = [
            ['label' => 'Total ecoles', 'value' => '128', 'trend' => '+8 ce mois', 'tone' => 'is-neutral'],
            ['label' => 'Total admins ecoles', 'value' => '214', 'trend' => '+12 actifs', 'tone' => 'is-success'],
            ['label' => 'Total eleves', 'value' => '48 320', 'trend' => '+5.6%', 'tone' => 'is-success'],
            ['label' => 'Croissance mensuelle', 'value' => '12.4%', 'trend' => 'stable', 'tone' => 'is-warning'],
        ];

        $recentSchools = [
            ['name' => 'Lycée Atlas Excellence', 'city' => 'Rabat', 'type' => 'Privé', 'status' => 'Actif', 'created_at' => '12 avr. 2026'],
            ['name' => 'Groupe Scolaire Al Amal', 'city' => 'Casablanca', 'type' => 'Public', 'status' => 'Audit', 'created_at' => '08 avr. 2026'],
            ['name' => 'Institut Anoual', 'city' => 'Fès', 'type' => 'Privé', 'status' => 'Actif', 'created_at' => '03 avr. 2026'],
            ['name' => 'Ecole Al Qods', 'city' => 'Marrakech', 'type' => 'Partenaire', 'status' => 'Actif', 'created_at' => '28 mars 2026'],
        ];

        $feed = [
            ['title' => 'Nouvelle ecole validee', 'copy' => 'Lycée Atlas Excellence a termine sa configuration initiale.', 'time' => 'Il y a 18 min'],
            ['title' => 'Admin ecole cree', 'copy' => 'Un nouvel administrateur a ete rattache a Groupe Scolaire Al Amal.', 'time' => 'Il y a 54 min'],
            ['title' => 'Export rapport termine', 'copy' => 'Le rapport national d’activite a ete genere et archive.', 'time' => 'Aujourd’hui, 09:42'],
        ];
    @endphp

    <section class="space-y-6 lg:space-y-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0A4FAF]">Pilotage central</p>
                <h1 class="mt-2 text-3xl font-semibold text-slate-950 sm:text-4xl">Bienvenue Super Admin</h1>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Une vue de supervision premium pour piloter les établissements, la croissance des inscriptions et la cadence opérationnelle de la plateforme.</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <x-button :href="route('admin.schools.create')" variant="secondary">Ajouter ecole</x-button>
                <x-button :href="route('admin.user-management.create')">Creer admin ecole</x-button>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($kpis as $item)
                <article class="admin-stat-card">
                    <div class="flex items-start justify-between gap-3">
                        <p class="text-sm font-medium text-slate-500">{{ $item['label'] }}</p>
                        <span class="admin-pill {{ $item['tone'] }}">{{ $item['trend'] }}</span>
                    </div>
                    <p class="mt-5 text-3xl font-semibold text-slate-950 sm:text-4xl">{{ $item['value'] }}</p>
                </article>
            @endforeach
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
            <section class="admin-card p-6 sm:p-7">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-semibold text-slate-950">Inscriptions par mois</h2>
                        <p class="mt-2 text-sm text-slate-500">Tendance nationale consolidée sur les douze derniers mois.</p>
                    </div>
                    <span class="admin-pill is-neutral">2026</span>
                </div>

                <div class="mt-8 flex h-72 items-end gap-3 rounded-[1.5rem] bg-slate-50 p-5">
                    @foreach ([42, 58, 51, 69, 74, 80, 77, 88, 92, 95, 102, 110] as $index => $height)
                        <div class="flex flex-1 flex-col items-center justify-end gap-3">
                            <div class="w-full rounded-t-2xl bg-gradient-to-t from-[#083B82] via-[#0A4FAF] to-[#18A558] transition duration-300 hover:opacity-90" style="height: {{ $height }}%"></div>
                            <span class="text-xs font-medium text-slate-500">{{ ['J','F','M','A','M','J','J','A','S','O','N','D'][$index] }}</span>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="admin-card p-6 sm:p-7">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-semibold text-slate-950">Activité système</h2>
                        <p class="mt-2 text-sm text-slate-500">Lecture rapide des mouvements les plus sensibles.</p>
                    </div>
                    <span class="admin-pill is-success">Normal</span>
                </div>

                <div class="mt-8 space-y-4">
                    @foreach ([74, 68, 82, 77, 91] as $index => $value)
                        <div>
                            <div class="mb-2 flex items-center justify-between text-sm">
                                <span class="font-medium text-slate-700">{{ ['Authentification', 'Imports', 'Creation ecoles', 'Exports', 'Sessions admins'][$index] }}</span>
                                <span class="text-slate-500">{{ $value }}%</span>
                            </div>
                            <div class="h-3 rounded-full bg-slate-100">
                                <div class="h-3 rounded-full bg-gradient-to-r from-[#0A4FAF] to-[#18A558]" style="width: {{ $value }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.15fr_0.85fr]">
            <section class="admin-table-wrap">
                <div class="flex items-center justify-between gap-4 border-b border-slate-200 px-6 py-5">
                    <div>
                        <h2 class="text-xl font-semibold text-slate-950">Ecoles recentes</h2>
                        <p class="mt-1 text-sm text-slate-500">Derniers établissements ajoutés ou activés sur la plateforme.</p>
                    </div>
                    <x-button :href="route('admin.schools.index')" variant="secondary" size="sm">Voir tout</x-button>
                </div>

                <div class="overflow-x-auto">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Nom ecole</th>
                                <th>Ville</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Date creation</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($recentSchools as $school)
                                <tr class="transition hover:bg-slate-50/80">
                                    <td class="font-semibold text-slate-950">{{ $school['name'] }}</td>
                                    <td>{{ $school['city'] }}</td>
                                    <td>{{ $school['type'] }}</td>
                                    <td>
                                        <span class="admin-pill {{ $school['status'] === 'Actif' ? 'is-success' : 'is-warning' }}">{{ $school['status'] }}</span>
                                    </td>
                                    <td>{{ $school['created_at'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            <div class="space-y-6">
                <section class="admin-card p-6 sm:p-7">
                    <h2 class="text-xl font-semibold text-slate-950">Actions rapides</h2>
                    <div class="mt-6 grid gap-3">
                        <x-button :href="route('admin.schools.create')" class="w-full justify-center">Ajouter ecole</x-button>
                        <x-button :href="route('admin.user-management.create')" variant="secondary" class="w-full justify-center">Creer admin ecole</x-button>
                        <x-button :href="route('admin.reports.index')" variant="dark" class="w-full justify-center">Export rapport</x-button>
                    </div>
                </section>

                <section class="admin-card p-6 sm:p-7">
                    <div class="flex items-center justify-between gap-4">
                        <h2 class="text-xl font-semibold text-slate-950">Fil d’activité</h2>
                        <span class="admin-pill is-neutral">En direct</span>
                    </div>

                    <div class="mt-6 space-y-4">
                        @foreach ($feed as $item)
                            <article class="rounded-2xl bg-slate-50 p-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <h3 class="font-semibold text-slate-950">{{ $item['title'] }}</h3>
                                        <p class="mt-2 text-sm leading-7 text-slate-600">{{ $item['copy'] }}</p>
                                    </div>
                                    <span class="text-xs font-medium text-slate-400">{{ $item['time'] }}</span>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            </div>
        </div>
    </section>
@endsection
