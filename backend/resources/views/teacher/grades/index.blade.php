@extends('layouts.teacher')

@section('title', 'Saisie des notes')

@section('content')
    @php
        $rows = [
            ['name' => 'Imane Tazi', 'grade' => '15.5', 'comment' => 'Très bon raisonnement', 'updated' => 'Aujourd’hui 09:21', 'status' => 'Sauvé'],
            ['name' => 'Yassir Ouali', 'grade' => '09.0', 'comment' => 'Revoir les exercices de base', 'updated' => 'Aujourd’hui 09:24', 'status' => 'À vérifier'],
            ['name' => 'Salma Benkirane', 'grade' => '12.5', 'comment' => 'Bon effort général', 'updated' => 'Aujourd’hui 09:28', 'status' => 'Sauvé'],
        ];
    @endphp

    <section class="space-y-6 lg:space-y-8" x-data="{ saved: true }">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0A4FAF]">Correction & publication</p>
            <h1 class="mt-2 text-3xl font-semibold text-slate-950 sm:text-4xl">Saisie des notes</h1>
            <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Saisissez, ajustez et publiez les résultats avec une table lisible et des repères visuels rapides.</p>
        </div>

        <section class="admin-card p-5 sm:p-6">
            <div class="grid gap-4 xl:grid-cols-3">
                <select class="admin-toolbar-input">
                    <option>Sélectionner classe</option>
                    <option>1ère Bac Sciences A</option>
                    <option>2AC-B</option>
                </select>
                <select class="admin-toolbar-input">
                    <option>Sélectionner évaluation</option>
                    <option>Contrôle continu - Algèbre</option>
                    <option>Examen partiel - Fonctions</option>
                </select>
                <input type="search" class="admin-toolbar-input" placeholder="Rechercher un élève">
            </div>
        </section>

        <section class="admin-table-wrap">
            <div class="flex items-center justify-between gap-4 border-b border-slate-200 px-6 py-5">
                <div>
                    <h2 class="text-xl font-semibold text-slate-950">Table de notes</h2>
                    <p class="mt-1 text-sm text-slate-500">Validation automatique entre 0 et 20 avec indicateur de sauvegarde.</p>
                </div>
                <span class="admin-pill is-success" x-text="saved ? 'Auto save actif' : 'Enregistrement...'"></span>
            </div>

            <div class="overflow-x-auto">
                <table class="admin-table min-w-[980px]">
                    <thead>
                        <tr>
                            <th>Élève</th>
                            <th>Note /20</th>
                            <th>Commentaire</th>
                            <th>Dernière modification</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($rows as $row)
                            <tr class="transition hover:bg-slate-50/80">
                                <td class="font-semibold text-slate-950">{{ $row['name'] }}</td>
                                <td>
                                    <input
                                        type="number"
                                        min="0"
                                        max="20"
                                        step="0.25"
                                        value="{{ $row['grade'] }}"
                                        class="w-28 rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm focus:border-[#0A4FAF] focus:ring-[#0A4FAF]/20"
                                        @input="saved = false; setTimeout(() => saved = true, 900)"
                                    >
                                </td>
                                <td>
                                    <input
                                        type="text"
                                        value="{{ $row['comment'] }}"
                                        class="w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm focus:border-[#0A4FAF] focus:ring-[#0A4FAF]/20"
                                        @input="saved = false; setTimeout(() => saved = true, 900)"
                                    >
                                </td>
                                <td>{{ $row['updated'] }}</td>
                                <td><span class="admin-pill {{ $row['status'] === 'Sauvé' ? 'is-success' : 'is-warning' }}">{{ $row['status'] }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <div class="flex flex-wrap gap-3">
            <x-button href="#">Save all</x-button>
            <x-button href="#" variant="dark">Publish results</x-button>
        </div>
    </section>
@endsection
