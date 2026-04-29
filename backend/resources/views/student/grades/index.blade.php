@extends('layouts.student')

@section('title', 'Mes notes')

@section('content')
    @php
        $grades = [
            ['subject' => 'Mathématiques', 'evaluation' => 'Contrôle continu', 'grade' => '17/20', 'coef' => '2', 'date' => '26 avr. 2026', 'comment' => 'Très bon niveau.'],
            ['subject' => 'Français', 'evaluation' => 'Devoir', 'grade' => '14/20', 'coef' => '1', 'date' => '23 avr. 2026', 'comment' => 'Bonne structure, encore un peu d’attention.'],
            ['subject' => 'Anglais', 'evaluation' => 'Examen', 'grade' => '13/20', 'coef' => '2', 'date' => '18 avr. 2026', 'comment' => 'Compréhension correcte, écrit à renforcer.'],
        ];

        $subjects = [
            ['name' => 'Mathématiques', 'avg' => '16.8', 'status' => 'Très solide'],
            ['name' => 'Français', 'avg' => '14.2', 'status' => 'Stable'],
            ['name' => 'Anglais', 'avg' => '12.9', 'status' => 'À renforcer'],
        ];
    @endphp

    <section class="space-y-6 lg:space-y-8">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0A4FAF]">Résultats scolaires</p>
            <h1 class="mt-2 text-3xl font-semibold text-slate-950 sm:text-4xl">Mes notes</h1>
            <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Une lecture claire de tes évaluations, de tes coefficients et des commentaires enseignants pour comprendre où tu progresses le mieux.</p>
        </div>

        <section class="admin-card p-5 sm:p-6">
            <div class="grid gap-4 xl:grid-cols-3">
                <select class="admin-toolbar-input">
                    <option>Semestre</option>
                    <option>Semestre 1</option>
                    <option>Semestre 2</option>
                </select>
                <select class="admin-toolbar-input">
                    <option>Matière</option>
                    <option>Mathématiques</option>
                    <option>Français</option>
                </select>
                <select class="admin-toolbar-input">
                    <option>Type évaluation</option>
                    <option>Contrôle continu</option>
                    <option>Devoir</option>
                    <option>Examen</option>
                </select>
            </div>
        </section>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ([['Moyenne actuelle', '15.4/20'], ['Dernière note', '17/20'], ['Meilleure matière', 'Mathématiques'], ['Matière à améliorer', 'Anglais']] as $item)
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
                            <th>Évaluation</th>
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
                                <td>{{ $item['evaluation'] }}</td>
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
                <h2 class="text-xl font-semibold text-slate-950">Performance par matière</h2>
                <div class="mt-6 grid gap-4 md:grid-cols-3">
                    @foreach ($subjects as $item)
                        <article class="rounded-2xl bg-slate-50 p-4">
                            <h3 class="font-semibold text-slate-950">{{ $item['name'] }}</h3>
                            <p class="mt-2 text-2xl font-semibold text-slate-950">{{ $item['avg'] }}/20</p>
                            <p class="mt-2 text-sm text-slate-500">{{ $item['status'] }}</p>
                        </article>
                    @endforeach
                </div>
            </section>

            <section class="admin-card p-6 sm:p-7">
                <h2 class="text-xl font-semibold text-slate-950">Export</h2>
                <p class="mt-3 text-sm leading-7 text-slate-600">Préparation d’un export ou d’une impression synthétique des résultats du semestre.</p>
                <div class="mt-6">
                    <x-button href="#" variant="secondary">Exporter / imprimer</x-button>
                </div>
            </section>
        </div>
    </section>
@endsection
