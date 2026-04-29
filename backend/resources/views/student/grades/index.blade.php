@extends('layouts.student')

@section('title', 'Mes notes')

@section('content')
    @php
        $lastGrade = $grades->first();
        $bestSubject = $subjects->first();
        $lowestSubject = $subjects->sortBy('avg')->first();
    @endphp

    <section class="space-y-6 lg:space-y-8">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0A4FAF]">Résultats scolaires</p>
            <h1 class="mt-2 text-3xl font-semibold text-slate-950 sm:text-4xl">Mes notes</h1>
            <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Une lecture claire de tes évaluations, de tes coefficients et des commentaires enseignants pour comprendre où tu progresses le mieux.</p>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ([['Moyenne actuelle', $averageGrade !== null ? number_format((float) $averageGrade, 2) . '/20' : 'N/A'], ['Dernière note', $lastGrade ? number_format((float) $lastGrade->value, 2) . '/20' : 'N/A'], ['Meilleure matière', $bestSubject['name'] ?? 'N/A'], ['Matière à améliorer', $lowestSubject['name'] ?? 'N/A']] as $item)
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
                            <th>Date</th>
                            <th>Commentaire enseignant</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($grades as $item)
                            @php $comment = $item->comments->first(); @endphp
                            <tr class="transition hover:bg-slate-50/80">
                                <td class="font-semibold text-slate-950">{{ $item->evaluation?->subject?->name ?? 'Matière' }}</td>
                                <td>{{ str_replace('_', ' ', ucfirst($item->evaluation?->type ?? 'Évaluation')) }}</td>
                                <td>{{ number_format((float) $item->value, 2) }}/20</td>
                                <td>{{ $item->evaluation?->date?->format('Y-m-d') ?? $item->created_at?->format('Y-m-d') }}</td>
                                <td>{{ $comment?->comment ?? 'Aucun commentaire' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-sm text-slate-500">Aucune note publiée pour le moment.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div class="grid gap-6 xl:grid-cols-[1.08fr_0.92fr]">
            <section class="admin-card p-6 sm:p-7">
                <h2 class="text-xl font-semibold text-slate-950">Performance par matière</h2>
                <div class="mt-6 grid gap-4 md:grid-cols-3">
                    @forelse ($subjects as $item)
                        <article class="rounded-2xl bg-slate-50 p-4">
                            <h3 class="font-semibold text-slate-950">{{ $item['name'] }}</h3>
                            <p class="mt-2 text-2xl font-semibold text-slate-950">{{ number_format((float) $item['avg'], 2) }}/20</p>
                            <p class="mt-2 text-sm text-slate-500">{{ $item['count'] }} note(s)</p>
                        </article>
                    @empty
                        <div class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-500 md:col-span-3">Aucune matière à afficher.</div>
                    @endforelse
                </div>
            </section>

            <section class="admin-card p-6 sm:p-7">
                <h2 class="text-xl font-semibold text-slate-950">Export</h2>
                <p class="mt-3 text-sm leading-7 text-slate-600">Préparation d’un export ou d’une impression synthétique des résultats du semestre.</p>
                <div class="mt-6">
                    <x-button type="button" variant="secondary" onclick="window.print()">Exporter / imprimer</x-button>
                </div>
            </section>
        </div>
    </section>
@endsection
