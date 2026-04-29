@extends('layouts.parent')

@section('title', 'Notes enfant')

@section('content')
    <section class="space-y-6 lg:space-y-8">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0A4FAF]">Lecture des résultats</p>
            <h1 class="mt-2 text-3xl font-semibold text-slate-950 sm:text-4xl">Notes enfant</h1>
            <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Une vue claire des résultats, des commentaires enseignants et des matières qui progressent le mieux.</p>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @php
                $best = $grades->sortByDesc(fn ($item) => $item['grade']->value)->first();
                $lowest = $grades->sortBy(fn ($item) => $item['grade']->value)->first();
                $last = $grades->first();
                $bestSubject = data_get($best, 'grade.evaluation.subject.name', 'N/A');
                $lowestSubject = data_get($lowest, 'grade.evaluation.subject.name', 'N/A');
            @endphp
            @foreach ([['Moyenne', $average !== null ? number_format((float) $average, 2) . '/20' : 'N/A'], ['Meilleure matière', $bestSubject], ['Matière à améliorer', $lowestSubject], ['Dernière note', $last ? number_format((float) $last['grade']->value, 2) . '/20' : 'N/A']] as $item)
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
                            <th>Enfant</th>
                            <th>Type évaluation</th>
                            <th>Note</th>
                            <th>Date</th>
                            <th>Commentaire enseignant</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($grades as $item)
                            @php
                                $grade = $item['grade'];
                                $comment = $grade->comments->first();
                            @endphp
                            <tr class="transition hover:bg-slate-50/80">
                                <td class="font-semibold text-slate-950">{{ $grade->evaluation?->subject?->name ?? 'Matière' }}</td>
                                <td>{{ $item['child']->name }}</td>
                                <td>{{ str_replace('_', ' ', ucfirst($grade->evaluation?->type ?? 'evaluation')) }}</td>
                                <td>{{ number_format((float) $grade->value, 2) }}/20</td>
                                <td>{{ $grade->evaluation?->date?->format('Y-m-d') ?? $grade->created_at?->format('Y-m-d') }}</td>
                                <td>{{ $comment?->comment ?? 'Aucun commentaire' }}</td>
                                <td>
                                    <form method="POST" action="{{ route('parent.review-requests.store') }}" class="flex min-w-72 gap-2">
                                        @csrf
                                        <input type="hidden" name="student_id" value="{{ $item['child']->id }}">
                                        <input type="hidden" name="grade_id" value="{{ $grade->id }}">
                                        <input type="text" name="reason" class="admin-toolbar-input" placeholder="Motif de révision" required>
                                        <x-button type="submit" size="sm">Envoyer</x-button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-sm text-slate-500">Aucune note publiée pour vos enfants.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div class="grid gap-6 xl:grid-cols-[1.08fr_0.92fr]">
            <section class="admin-card p-6 sm:p-7">
                <h2 class="text-xl font-semibold text-slate-950">Évolution des résultats</h2>
                <div class="mt-8 flex h-64 items-end gap-3 rounded-[1.5rem] bg-slate-50 p-5">
                    @foreach ($grades->take(8) as $item)
                        @php $point = max(8, min(100, round(($item['grade']->value / 20) * 100))); @endphp
                        <div class="flex flex-1 items-end">
                            <div class="w-full rounded-t-2xl bg-gradient-to-t from-[#083B82] via-[#0A4FAF] to-[#18A558]" style="height: {{ $point }}%"></div>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="admin-card p-6 sm:p-7">
                <h2 class="text-xl font-semibold text-slate-950">Recommandations enseignant</h2>
                <div class="mt-6 space-y-4">
                    @forelse ($grades->pluck('grade')->flatMap->comments->take(3) as $comment)
                        <div class="rounded-2xl bg-slate-50 p-4 text-sm leading-7 text-slate-600">{{ $comment->comment }}</div>
                    @empty
                        <div class="rounded-2xl bg-slate-50 p-4 text-sm leading-7 text-slate-600">Aucun commentaire enseignant disponible pour le moment.</div>
                    @endforelse
                </div>
            </section>
        </div>
    </section>
@endsection
