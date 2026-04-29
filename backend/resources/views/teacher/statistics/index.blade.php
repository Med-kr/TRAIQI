@extends('layouts.teacher')

@section('title', 'Statistiques classe')

@section('content')
    @php
        $kpis = [
            ['label' => 'Moyenne classe', 'value' => '14.8/20'],
            ['label' => 'Plus haute note', 'value' => '19.5'],
            ['label' => 'Plus basse note', 'value' => '07.0'],
            ['label' => 'Taux réussite', 'value' => '86%'],
        ];

        $ranking = [
            ['name' => 'Omar Ait Said', 'avg' => '18.2', 'presence' => '99%'],
            ['name' => 'Imane Tazi', 'avg' => '16.9', 'presence' => '97%'],
            ['name' => 'Salma Benkirane', 'avg' => '14.7', 'presence' => '89%'],
            ['name' => 'Yassir Ouali', 'avg' => '10.4', 'presence' => '81%'],
        ];
    @endphp

    <section class="space-y-6 lg:space-y-8">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0A4FAF]">Lecture des performances</p>
            <h1 class="mt-2 text-3xl font-semibold text-slate-950 sm:text-4xl">Statistiques classe</h1>
            <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Analysez rapidement la répartition des notes, les dynamiques de progression et les signaux d’accompagnement.</p>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($kpis as $item)
                <article class="admin-stat-card">
                    <p class="text-sm font-medium text-slate-500">{{ $item['label'] }}</p>
                    <p class="mt-4 text-3xl font-semibold text-slate-950">{{ $item['value'] }}</p>
                </article>
            @endforeach
        </div>

        <div class="grid gap-6 xl:grid-cols-3">
            <section class="admin-card p-6 sm:p-7">
                <h2 class="text-xl font-semibold text-slate-950">Distribution notes</h2>
                <div class="mt-8 flex h-64 items-end gap-3 rounded-[1.5rem] bg-slate-50 p-5">
                    @foreach ([22, 44, 67, 83, 59, 34] as $point)
                        <div class="flex flex-1 items-end">
                            <div class="w-full rounded-t-2xl bg-gradient-to-t from-[#083B82] via-[#0A4FAF] to-[#18A558]" style="height: {{ $point }}%"></div>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="admin-card p-6 sm:p-7">
                <h2 class="text-xl font-semibold text-slate-950">Progression mensuelle</h2>
                <div class="mt-6 space-y-5">
                    @foreach ([74, 77, 79, 81, 85] as $value)
                        <div>
                            <div class="mb-2 flex items-center justify-between text-sm">
                                <span class="font-medium text-slate-700">Mois</span>
                                <span class="text-slate-500">{{ $value }}%</span>
                            </div>
                            <div class="h-3 rounded-full bg-slate-100">
                                <div class="h-3 rounded-full bg-gradient-to-r from-[#0A4FAF] to-[#18A558]" style="width: {{ $value }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="admin-card p-6 sm:p-7">
                <h2 class="text-xl font-semibold text-slate-950">Présence</h2>
                <div class="mt-8 grid grid-cols-2 gap-4">
                    <div class="rounded-2xl bg-slate-50 p-4 text-center">
                        <p class="text-sm text-slate-500">Présents</p>
                        <p class="mt-2 text-2xl font-semibold text-slate-950">92%</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4 text-center">
                        <p class="text-sm text-slate-500">Absents</p>
                        <p class="mt-2 text-2xl font-semibold text-slate-950">8%</p>
                    </div>
                </div>
            </section>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.08fr_0.92fr]">
            <section class="admin-table-wrap">
                <div class="flex items-center justify-between gap-4 border-b border-slate-200 px-6 py-5">
                    <div>
                        <h2 class="text-xl font-semibold text-slate-950">Classement élèves</h2>
                        <p class="mt-1 text-sm text-slate-500">Lecture synthétique des résultats et de la présence.</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="admin-table min-w-[760px]">
                        <thead>
                            <tr>
                                <th>Rang</th>
                                <th>Élève</th>
                                <th>Moyenne</th>
                                <th>Présence</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($ranking as $index => $student)
                                <tr class="transition hover:bg-slate-50/80">
                                    <td>{{ $index + 1 }}</td>
                                    <td class="font-semibold text-slate-950">{{ $student['name'] }}</td>
                                    <td>{{ $student['avg'] }}/20</td>
                                    <td>{{ $student['presence'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="admin-card p-6 sm:p-7">
                <h2 class="text-xl font-semibold text-slate-950">Recommandations</h2>
                <div class="mt-6 space-y-4">
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="font-semibold text-slate-950">Élèves à accompagner</p>
                        <p class="mt-2 text-sm leading-7 text-slate-600">Yassir Ouali, Lina B. et Hamza T. ont besoin d’un renforcement ciblé sur les fondamentaux.</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="font-semibold text-slate-950">Élèves performants</p>
                        <p class="mt-2 text-sm leading-7 text-slate-600">Omar Ait Said et Imane Tazi peuvent être valorisés sur des tâches d’approfondissement.</p>
                    </div>
                </div>
            </section>
        </div>
    </section>
@endsection
