@extends('layouts.school-admin')

@section('title', 'Imports Excel')

@section('content')
    @php
        $history = [
            ['file' => 'eleves_terminale_sciences.xlsx', 'type' => 'Élèves', 'date' => '27 avr. 2026 - 10:30', 'status' => 'Terminé', 'rows' => 148, 'errors' => 0],
            ['file' => 'parents_cycle_college.xlsx', 'type' => 'Parents', 'date' => '26 avr. 2026 - 16:10', 'status' => 'Partiel', 'rows' => 96, 'errors' => 4],
            ['file' => 'enseignants_semestre_2.xlsx', 'type' => 'Enseignants', 'date' => '25 avr. 2026 - 09:05', 'status' => 'Terminé', 'rows' => 22, 'errors' => 0],
        ];
    @endphp

    <section class="space-y-6 lg:space-y-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0A4FAF]">Moteur d’intégration</p>
                <h1 class="mt-2 text-3xl font-semibold text-slate-950 sm:text-4xl">Imports Excel</h1>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Chargez vos fichiers, sécurisez les correspondances et suivez les traitements avec une interface pensée pour les opérations répétées.</p>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.15fr_0.85fr]">
            <section class="admin-card p-6 sm:p-7">
                <div class="rounded-[1.75rem] border-2 border-dashed border-[#0A4FAF]/18 bg-slate-50 px-6 py-10 text-center">
                    <span class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-white text-[#0A4FAF] shadow-sm">
                        <x-traiqi-icon name="upload" class="h-7 w-7" />
                    </span>
                    <h2 class="mt-5 text-2xl font-semibold text-slate-950">Glissez vos fichiers ici</h2>
                    <p class="mx-auto mt-3 max-w-xl text-sm leading-7 text-slate-600">Déposez un fichier Excel ou choisissez-le manuellement pour lancer un import contrôlé et documenté.</p>
                    <div class="mt-6">
                        <x-button href="#">Choisir un fichier</x-button>
                    </div>
                </div>

                <div class="mt-6 grid gap-3 md:grid-cols-3">
                    <x-button href="#" class="w-full justify-center">Élèves</x-button>
                    <x-button href="#" variant="secondary" class="w-full justify-center">Parents</x-button>
                    <x-button href="#" variant="dark" class="w-full justify-center">Enseignants</x-button>
                </div>

                <div class="mt-6 grid gap-3 sm:grid-cols-3">
                    <x-button href="#" variant="secondary" size="sm" class="w-full justify-center">Template élèves</x-button>
                    <x-button href="#" variant="secondary" size="sm" class="w-full justify-center">Template parents</x-button>
                    <x-button href="#" variant="secondary" size="sm" class="w-full justify-center">Template enseignants</x-button>
                </div>
            </section>

            <section class="admin-card p-6 sm:p-7">
                <h2 class="text-xl font-semibold text-slate-950">Moteur intelligent</h2>
                <div class="mt-6 space-y-4">
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="font-semibold text-slate-950">Détection doublons</p>
                        <p class="mt-2 text-sm leading-7 text-slate-600">Repère les emails, codes et liaisons déjà présents pour éviter les collisions silencieuses.</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="font-semibold text-slate-950">Création comptes auto</p>
                        <p class="mt-2 text-sm leading-7 text-slate-600">Prépare les comptes avec données minimales et structure propre pour accélérer l’onboarding.</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="font-semibold text-slate-950">Affectation classes auto</p>
                        <p class="mt-2 text-sm leading-7 text-slate-600">Assigne les utilisateurs aux classes et cycles selon les règles de gestion définies.</p>
                    </div>
                </div>
            </section>
        </div>

        <section class="admin-table-wrap">
            <div class="flex items-center justify-between gap-4 border-b border-slate-200 px-6 py-5">
                <div>
                    <h2 class="text-xl font-semibold text-slate-950">Historique des imports</h2>
                    <p class="mt-1 text-sm text-slate-500">Derniers traitements exécutés avec leur statut détaillé.</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="admin-table min-w-[900px]">
                    <thead>
                        <tr>
                            <th>Fichier</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th>Lignes importées</th>
                            <th>Erreurs</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($history as $item)
                            <tr class="transition hover:bg-slate-50/80">
                                <td class="font-semibold text-slate-950">{{ $item['file'] }}</td>
                                <td>{{ $item['type'] }}</td>
                                <td>{{ $item['date'] }}</td>
                                <td><span class="admin-pill {{ $item['status'] === 'Terminé' ? 'is-success' : 'is-warning' }}">{{ $item['status'] }}</span></td>
                                <td>{{ $item['rows'] }}</td>
                                <td>{{ $item['errors'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </section>
@endsection
