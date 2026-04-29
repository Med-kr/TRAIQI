@extends('layouts.teacher')

@section('title', 'Statistiques classe')

@section('content')
    @php
        $kpis = [
            ['label' => 'Moyenne classe', 'value' => number_format((float) $classAverage, 2) . '/20'],
            ['label' => 'Plus haute note', 'value' => $grades->max('value') !== null ? number_format((float) $grades->max('value'), 2) : 'N/A'],
            ['label' => 'Plus basse note', 'value' => $grades->min('value') !== null ? number_format((float) $grades->min('value'), 2) : 'N/A'],
            ['label' => 'Taux réussite', 'value' => $grades->count() > 0 ? round(($grades->where('value', '>=', 10)->count() / $grades->count()) * 100) . '%' : '0%'],
        ];

        $ranking = $grades
            ->groupBy('student_id')
            ->map(fn ($studentGrades) => [
                'name' => $studentGrades->first()?->student?->name ?? 'Élève',
                'avg' => $studentGrades->avg('value'),
                'count' => $studentGrades->count(),
            ])
            ->sortByDesc('avg')
            ->values();
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
                    @foreach ([0, 5, 8, 10, 12, 15] as $min)
                        @php
                            $max = $min === 15 ? 20 : $min + 5;
                            $count = $grades->filter(fn ($grade) => $grade->value >= $min && $grade->value <= $max)->count();
                            $point = $grades->count() > 0 ? max(8, round(($count / $grades->count()) * 100)) : 8;
                        @endphp
                        <div class="flex flex-1 items-end">
                            <div class="w-full rounded-t-2xl bg-gradient-to-t from-[#083B82] via-[#0A4FAF] to-[#18A558]" style="height: {{ $point }}%"></div>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="admin-card p-6 sm:p-7">
                <h2 class="text-xl font-semibold text-slate-950">Progression mensuelle</h2>
                <div class="mt-6 space-y-5">
                    @foreach ($assignments->take(5) as $assignment)
                        @php
                            $classGrades = $grades->filter(fn ($grade) => $grade->evaluation?->classroom_id === $assignment->classroom_id);
                            $value = $classGrades->count() > 0 ? min(100, round(($classGrades->where('value', '>=', 10)->count() / $classGrades->count()) * 100)) : 0;
                        @endphp
                        <div>
                            <div class="mb-2 flex items-center justify-between text-sm">
                                <span class="font-medium text-slate-700">{{ $assignment->classroom?->name ?? 'Classe' }}</span>
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
                        <p class="text-sm text-slate-500">Évaluations</p>
                        <p class="mt-2 text-2xl font-semibold text-slate-950">{{ $evaluationsCount }}</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4 text-center">
                        <p class="text-sm text-slate-500">Copies restantes</p>
                        <p class="mt-2 text-2xl font-semibold text-slate-950">{{ $remainingCopiesCount }}</p>
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
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($ranking as $index => $student)
                                <tr class="transition hover:bg-slate-50/80">
                                    <td>{{ $index + 1 }}</td>
                                    <td class="font-semibold text-slate-950">{{ $student['name'] }}</td>
                                    <td>{{ number_format((float) $student['avg'], 2) }}/20</td>
                                    <td>{{ $student['count'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-sm text-slate-500">Aucune note disponible pour calculer un classement.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="admin-card p-6 sm:p-7">
                <h2 class="text-xl font-semibold text-slate-950">Recommandations</h2>
                <div class="mt-6 space-y-4">
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="font-semibold text-slate-950">Élèves à accompagner</p>
                        <p class="mt-2 text-sm leading-7 text-slate-600">{{ $studentsInDifficultyCount }} élève(s) ont au moins une note inférieure à 10/20.</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="font-semibold text-slate-950">Élèves performants</p>
                        <p class="mt-2 text-sm leading-7 text-slate-600">{{ $ranking->filter(fn ($student) => $student['avg'] >= 16)->count() }} élève(s) ont une moyenne supérieure ou égale à 16/20.</p>
                    </div>
                </div>
            </section>
        </div>
    </section>
@endsection
