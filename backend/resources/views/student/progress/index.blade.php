@extends('layouts.student')

@section('title', 'Ma progression')

@section('content')
    <section class="space-y-6 lg:space-y-8">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0A4FAF]">Vision long terme</p>
            <h1 class="mt-2 text-3xl font-semibold text-slate-950 sm:text-4xl">Ma progression</h1>
            <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Observe ton évolution dans le temps, repère tes forces et identifie les axes de progression à travailler.</p>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @php
                $recentAverage = $grades->take(3)->avg('value');
                $olderAverage = $grades->slice(3, 3)->avg('value');
                $evolution = $recentAverage !== null && $olderAverage !== null ? round($recentAverage - $olderAverage, 2) : null;
            @endphp
            @foreach ([['Moyenne actuelle', $averageGrade !== null ? number_format((float) $averageGrade, 2) . '/20' : 'N/A'], ['Taux réussite', $passRate . '%'], ['Notes publiées', $grades->count()], ['Évolution moyenne', $evolution !== null ? ($evolution >= 0 ? '+' : '') . $evolution : 'N/A']] as $item)
                <article class="admin-stat-card">
                    <p class="text-sm font-medium text-slate-500">{{ $item[0] }}</p>
                    <p class="mt-4 text-2xl font-semibold text-slate-950">{{ $item[1] }}</p>
                </article>
            @endforeach
        </div>

        <div class="grid gap-6 xl:grid-cols-3">
            <section class="admin-card p-6 sm:p-7">
                <h2 class="text-xl font-semibold text-slate-950">Progression mensuelle</h2>
                <div class="mt-8 flex h-64 items-end gap-3 rounded-[1.5rem] bg-slate-50 p-5">
                    @forelse ($grades->take(8) as $grade)
                        @php $point = max(8, min(100, round(($grade->value / 20) * 100))); @endphp
                        <div class="flex flex-1 items-end">
                            <div class="w-full rounded-t-2xl bg-gradient-to-t from-[#083B82] via-[#0A4FAF] to-[#18A558]" style="height: {{ $point }}%"></div>
                        </div>
                    @empty
                        <div class="flex flex-1 items-end"><div class="w-full rounded-t-2xl bg-slate-200" style="height: 8%"></div></div>
                    @endforelse
                </div>
            </section>

            <section class="admin-card p-6 sm:p-7">
                <h2 class="text-xl font-semibold text-slate-950">Notes par matière</h2>
                <div class="mt-6 space-y-5">
                    @forelse ($subjects as $item)
                        @php $percent = max(0, min(100, round(($item['avg'] / 20) * 100))); @endphp
                        <div>
                            <div class="mb-2 flex items-center justify-between text-sm">
                                <span class="font-medium text-slate-700">{{ $item['name'] }}</span>
                                <span class="text-slate-500">{{ $percent }}%</span>
                            </div>
                            <div class="h-3 rounded-full bg-slate-100">
                                <div class="h-3 rounded-full bg-gradient-to-r from-[#0A4FAF] to-[#18A558]" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-500">Aucune note disponible.</div>
                    @endforelse
                </div>
            </section>

            <section class="admin-card p-6 sm:p-7">
                <h2 class="text-xl font-semibold text-slate-950">Présence trend</h2>
                <div class="mt-8 grid grid-cols-2 gap-4">
                    <div class="rounded-2xl bg-slate-50 p-4 text-center">
                        <p class="text-sm text-slate-500">Réussite</p>
                        <p class="mt-2 text-2xl font-semibold text-slate-950">{{ $passRate }}%</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4 text-center">
                        <p class="text-sm text-slate-500">Notes</p>
                        <p class="mt-2 text-2xl font-semibold text-slate-950">{{ $grades->count() }}</p>
                    </div>
                </div>
            </section>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.08fr_0.92fr]">
            <section class="admin-card p-6 sm:p-7">
                <h2 class="text-xl font-semibold text-slate-950">Compétences</h2>
                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    @foreach ([['Communication', 'Bonne'], ['Discipline', 'Très bonne'], ['Participation', 'En progrès'], ['Travail personnel', 'Solide']] as $item)
                        <article class="rounded-2xl bg-slate-50 p-4">
                            <h3 class="font-semibold text-slate-950">{{ $item[0] }}</h3>
                            <p class="mt-2 text-sm text-slate-500">{{ $item[1] }}</p>
                        </article>
                    @endforeach
                </div>
            </section>

            <section class="admin-card p-6 sm:p-7">
                <h2 class="text-xl font-semibold text-slate-950">Conseils pour progresser</h2>
                <div class="mt-6 space-y-4">
                    <div class="rounded-2xl bg-slate-50 p-4 text-sm leading-7 text-slate-600">Continue à consolider tes acquis en mathématiques avec des exercices réguliers et courts.</div>
                    <div class="rounded-2xl bg-slate-50 p-4 text-sm leading-7 text-slate-600">Travaille l’expression écrite en anglais deux fois par semaine pour gagner en confiance.</div>
                </div>
            </section>
        </div>
    </section>
@endsection
