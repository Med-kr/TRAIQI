@extends('layouts.parent')

@section('title', 'Notes enfant')

@section('content')
    @php
        $grades = [
            ['subject' => 'Mathématiques', 'type' => 'Contrôle continu', 'grade' => '17/20', 'coef' => '2', 'date' => '26 avr. 2026', 'comment' => 'Très bonne maîtrise du chapitre.'],
            ['subject' => 'Français', 'type' => 'Devoir', 'grade' => '14/20', 'coef' => '1', 'date' => '23 avr. 2026', 'comment' => 'Expression claire, orthographe à renforcer.'],
            ['subject' => 'Anglais', 'type' => 'Examen', 'grade' => '12/20', 'coef' => '2', 'date' => '18 avr. 2026', 'comment' => 'Bonne compréhension orale, écrit à consolider.'],
        ];
    @endphp

    <section class="space-y-6 lg:space-y-8">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0A4FAF]">Lecture des résultats</p>
            <h1 class="mt-2 text-3xl font-semibold text-slate-950 sm:text-4xl">Notes enfant</h1>
            <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Une vue claire des résultats, des commentaires enseignants et des matières qui progressent le mieux.</p>
        </div>

        <section class="admin-card p-5 sm:p-6">
            <div class="grid gap-4 xl:grid-cols-3">
                <select class="admin-toolbar-input">
                    <option>Choisir un enfant</option>
                    <option>Imane Tazi</option>
                    <option>Youssef Tazi</option>
                </select>
                <select class="admin-toolbar-input">
                    <option>Choisir un semestre</option>
                    <option>Semestre 1</option>
                    <option>Semestre 2</option>
                </select>
                <select class="admin-toolbar-input">
                    <option>Toutes les matières</option>
                    <option>Mathématiques</option>
                    <option>Français</option>
                    <option>Anglais</option>
                </select>
            </div>
        </section>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ([['Moyenne', '15.3/20'], ['Meilleure matière', 'Mathématiques'], ['Matière à améliorer', 'Anglais'], ['Dernière note', '17/20']] as $item)
                <article class="admin-stat-card">
                    <p class="text-sm font-medium text-slate-500">{{ $item[0] }}</p>
                    <p class="mt-4 text-2xl font-semibold text-slate-950">{{ $item[1] }}</p>
                </article>
            @endforeach
        </div>

        <section class="admin-table-wrap">
            <div class="overflow-x-auto">
                <table class="admin-table min-w-[980px]">
                    <thead>
                        <tr>
                            <th>Matière</th>
                            <th>Type évaluation</th>
                            <th>Note</th>
                            <th>Coefficient</th>
                            <th>Date</th>
                            <th>Commentaire enseignant</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($grades as $item)
                            <tr class="transition hover:bg-slate-50/80">
                                <td class="font-semibold text-slate-950">{{ $item['subject'] }}</td>
                                <td>{{ $item['type'] }}</td>
                                <td>{{ $item['grade'] }}</td>
                                <td>{{ $item['coef'] }}</td>
                                <td>{{ $item['date'] }}</td>
                                <td>{{ $item['comment'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <div class="grid gap-6 xl:grid-cols-[1.08fr_0.92fr]">
            <section class="admin-card p-6 sm:p-7">
                <h2 class="text-xl font-semibold text-slate-950">Évolution des résultats</h2>
                <div class="mt-8 flex h-64 items-end gap-3 rounded-[1.5rem] bg-slate-50 p-5">
                    @foreach ([52, 64, 71, 69, 82, 88] as $point)
                        <div class="flex flex-1 items-end">
                            <div class="w-full rounded-t-2xl bg-gradient-to-t from-[#083B82] via-[#0A4FAF] to-[#18A558]" style="height: {{ $point }}%"></div>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="admin-card p-6 sm:p-7">
                <h2 class="text-xl font-semibold text-slate-950">Recommandations enseignant</h2>
                <div class="mt-6 space-y-4">
                    <div class="rounded-2xl bg-slate-50 p-4 text-sm leading-7 text-slate-600">Poursuivre la dynamique en mathématiques avec des exercices de consolidation ciblés.</div>
                    <div class="rounded-2xl bg-slate-50 p-4 text-sm leading-7 text-slate-600">Renforcer la pratique écrite en anglais sur un rythme régulier et court.</div>
                </div>
            </section>
        </div>
    </section>
@endsection
