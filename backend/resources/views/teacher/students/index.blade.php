@extends('layouts.teacher')

@section('title', 'Liste élèves')

@section('content')
    @php
        $students = [
            ['name' => 'Imane Tazi', 'class' => '1ère Bac Sciences A', 'last_grade' => '15.5', 'avg' => '16.2', 'attendance' => '97%', 'status' => 'Excellent'],
            ['name' => 'Yassir Ouali', 'class' => '2AC-B', 'last_grade' => '09.0', 'avg' => '10.4', 'attendance' => '81%', 'status' => 'À suivre'],
            ['name' => 'Salma Benkirane', 'class' => 'Terminale PC', 'last_grade' => '12.5', 'avg' => '13.7', 'attendance' => '89%', 'status' => 'Stable'],
            ['name' => 'Omar Ait Said', 'class' => '3AC-A', 'last_grade' => '18.0', 'avg' => '17.3', 'attendance' => '99%', 'status' => 'Excellent'],
        ];
    @endphp

    <section class="space-y-6 lg:space-y-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0A4FAF]">Suivi individualisé</p>
                <h1 class="mt-2 text-3xl font-semibold text-slate-950 sm:text-4xl">Liste élèves</h1>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Repérez rapidement les niveaux, la présence et les tendances de performance pour intervenir au bon moment.</p>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.12fr_0.88fr]">
            <div class="space-y-6">
                <section class="admin-card p-5 sm:p-6">
                    <div class="grid gap-4 xl:grid-cols-3">
                        <input type="search" class="admin-toolbar-input" placeholder="Rechercher un élève">
                        <select class="admin-toolbar-input">
                            <option>Toutes les classes</option>
                            <option>1ère Bac Sciences A</option>
                            <option>2AC-B</option>
                        </select>
                        <select class="admin-toolbar-input">
                            <option>Tous les niveaux</option>
                            <option>Collège</option>
                            <option>Lycée</option>
                        </select>
                    </div>
                </section>

                <section class="admin-table-wrap">
                    <div class="overflow-x-auto">
                        <table class="admin-table min-w-[980px]">
                            <thead>
                                <tr>
                                    <th>Photo</th>
                                    <th>Nom complet</th>
                                    <th>Classe</th>
                                    <th>Dernière note</th>
                                    <th>Moyenne</th>
                                    <th>Présence</th>
                                    <th>Statut</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($students as $student)
                                    <tr class="transition hover:bg-slate-50/80">
                                        <td>
                                            <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-[#0A4FAF]/10 text-sm font-semibold text-[#0A4FAF]">
                                                {{ \Illuminate\Support\Str::of($student['name'])->explode(' ')->map(fn ($part) => mb_substr($part, 0, 1))->take(2)->implode('') }}
                                            </span>
                                        </td>
                                        <td class="font-semibold text-slate-950">{{ $student['name'] }}</td>
                                        <td>{{ $student['class'] }}</td>
                                        <td>{{ $student['last_grade'] }}/20</td>
                                        <td>{{ $student['avg'] }}/20</td>
                                        <td>{{ $student['attendance'] }}</td>
                                        <td>
                                            <span class="admin-pill {{ $student['status'] === 'Excellent' ? 'is-success' : ($student['status'] === 'À suivre' ? 'is-warning' : 'is-neutral') }}">
                                                {{ $student['status'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="flex justify-end gap-2">
                                                <x-button href="#" variant="ghost" size="sm">Profil</x-button>
                                                <x-button href="#" variant="secondary" size="sm">Notes</x-button>
                                                <x-button href="#" size="sm">Commentaire</x-button>
                                                <x-button href="#" variant="dark" size="sm">Historique</x-button>
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
                    <h2 class="text-xl font-semibold text-slate-950">Repères rapides</h2>
                    <div class="mt-6 space-y-4">
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="font-semibold text-slate-950">Élèves excellents</p>
                            <p class="mt-2 text-sm text-slate-600">12 élèves avec une moyenne supérieure à 16/20.</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="font-semibold text-slate-950">Élèves en difficulté</p>
                            <p class="mt-2 text-sm text-slate-600">8 profils demandent une remédiation ciblée cette semaine.</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="font-semibold text-slate-950">Absences fréquentes</p>
                            <p class="mt-2 text-sm text-slate-600">5 élèves présentent une présence inférieure à 85%.</p>
                        </div>
                    </div>
                </section>
            </aside>
        </div>
    </section>
@endsection
