@extends('layouts.school-admin')

@section('title', 'Dashboard administration')

@section('content')
    @php
        $kpis = [
            ['label' => 'Total élèves', 'value' => '1 284', 'tone' => 'is-neutral'],
            ['label' => 'Total enseignants', 'value' => '84', 'tone' => 'is-success'],
            ['label' => 'Total parents', 'value' => '1 106', 'tone' => 'is-neutral'],
            ['label' => 'Classes actives', 'value' => '36', 'tone' => 'is-success'],
            ['label' => 'Notes saisies ce mois', 'value' => '8 420', 'tone' => 'is-warning'],
            ['label' => 'Présence moyenne', 'value' => '93%', 'tone' => 'is-success'],
        ];

        $feed = [
            ['title' => 'Import élèves terminé', 'copy' => 'Le lot Terminale Sciences a été intégré avec 0 erreur critique.', 'time' => 'Il y a 12 min'],
            ['title' => 'Nouvelle classe créée', 'copy' => 'La classe 1ère Bac Lettres B a été activée pour l’année en cours.', 'time' => 'Il y a 37 min'],
            ['title' => 'Rapport mensuel généré', 'copy' => 'Le rapport synthétique des notes a été exporté au format PDF.', 'time' => 'Aujourd’hui, 08:51'],
        ];

        $tasks = [
            'Valider les imports parents avant 16h00',
            'Finaliser l’affectation des enseignants de sciences',
            'Contrôler les classes avec présence inférieure à 90%',
            'Exporter le rapport de suivi pédagogique hebdomadaire',
        ];
    @endphp

    <section class="space-y-6 lg:space-y-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0A4FAF]">Administration établissement</p>
                <h1 class="mt-2 text-3xl font-semibold text-slate-950 sm:text-4xl">Bienvenue Administration</h1>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Un cockpit opérationnel pour suivre les effectifs, les classes, les imports et la cadence pédagogique avec un niveau de clarté premium.</p>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-6">
            @foreach ($kpis as $item)
                <article class="admin-stat-card">
                    <p class="text-sm font-medium text-slate-500">{{ $item['label'] }}</p>
                    <div class="mt-4 flex items-end justify-between gap-3">
                        <p class="text-3xl font-semibold text-slate-950">{{ $item['value'] }}</p>
                        <span class="admin-pill {{ $item['tone'] }}">actif</span>
                    </div>
                </article>
            @endforeach
        </div>

        <section class="admin-card p-6 sm:p-7">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-semibold text-slate-950">Actions rapides</h2>
                    <p class="mt-2 text-sm text-slate-500">Accès direct aux tâches les plus fréquentes de l’administration.</p>
                </div>
            </div>

            <div class="mt-6 grid gap-3 md:grid-cols-2 xl:grid-cols-4">
                <x-button :href="route('admin.imports.index')" class="w-full justify-center">Importer Excel</x-button>
                <x-button :href="route('admin.user-management.create')" variant="secondary" class="w-full justify-center">Ajouter utilisateur</x-button>
                <x-button :href="route('admin.classrooms.index')" variant="dark" class="w-full justify-center">Créer classe</x-button>
                <x-button :href="route('admin.reports.index')" variant="secondary" class="w-full justify-center">Générer rapport</x-button>
            </div>
        </section>

        <div class="grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
            <section class="admin-card p-6 sm:p-7">
                <h2 class="text-xl font-semibold text-slate-950">Évolution inscriptions</h2>
                <div class="mt-8 flex h-64 items-end gap-3 rounded-[1.5rem] bg-slate-50 p-5">
                    @foreach ([48, 52, 64, 70, 66, 74, 78, 81, 85, 91, 88, 95] as $index => $height)
                        <div class="flex flex-1 flex-col items-center justify-end gap-3">
                            <div class="w-full rounded-t-2xl bg-gradient-to-t from-[#083B82] via-[#0A4FAF] to-[#18A558]" style="height: {{ $height }}%"></div>
                            <span class="text-xs font-medium text-slate-500">{{ ['J','F','M','A','M','J','J','A','S','O','N','D'][$index] }}</span>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="admin-card p-6 sm:p-7">
                <h2 class="text-xl font-semibold text-slate-950">Activité plateforme</h2>
                <div class="mt-6 space-y-5">
                    @foreach ([79, 92, 68] as $index => $value)
                        <div>
                            <div class="mb-2 flex items-center justify-between text-sm">
                                <span class="font-medium text-slate-700">{{ ['Sessions utilisateurs', 'Imports réussis', 'Mises à jour profils'][$index] }}</span>
                                <span class="text-slate-500">{{ $value }}%</span>
                            </div>
                            <div class="h-3 rounded-full bg-slate-100">
                                <div class="h-3 rounded-full bg-gradient-to-r from-[#0A4FAF] to-[#18A558]" style="width: {{ $value }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <h3 class="mt-8 text-lg font-semibold text-slate-950">Notes mensuelles</h3>
                <div class="mt-4 grid grid-cols-6 gap-3">
                    @foreach ([12, 16, 18, 22, 25, 28] as $point)
                        <div class="rounded-2xl bg-slate-50 p-3 text-center">
                            <p class="text-xs text-slate-500">Semaine</p>
                            <p class="mt-2 text-lg font-semibold text-slate-950">{{ $point }}k</p>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.05fr_0.95fr]">
            <section class="admin-card p-6 sm:p-7">
                <div class="flex items-center justify-between gap-4">
                    <h2 class="text-xl font-semibold text-slate-950">Activité récente</h2>
                    <span class="admin-pill is-neutral">Temps réel</span>
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

            <section class="admin-card p-6 sm:p-7">
                <div class="flex items-center justify-between gap-4">
                    <h2 class="text-xl font-semibold text-slate-950">Tâches à venir</h2>
                    <span class="admin-pill is-warning">4 priorités</span>
                </div>

                <div class="mt-6 space-y-4">
                    @foreach ($tasks as $task)
                        <div class="flex items-start gap-4 rounded-2xl border border-slate-100 p-4">
                            <span class="mt-1 inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-[#18A558]/12 text-[#18A558]">
                                <span class="h-2 w-2 rounded-full bg-current"></span>
                            </span>
                            <p class="text-sm leading-7 text-slate-700">{{ $task }}</p>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    </section>
@endsection
