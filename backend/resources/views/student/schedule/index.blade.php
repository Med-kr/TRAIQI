@extends('layouts.student')

@section('title', 'Emploi du temps')

@section('content')
    @php
        $days = collect(['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'])
            ->mapWithKeys(fn ($day, $index) => [
                $day => $timetableSubjects
                    ->values()
                    ->filter(fn ($subject, $subjectIndex) => $subjectIndex % 6 === $index)
                    ->map(fn ($subject) => [
                        'time' => '08:30',
                        'subject' => $subject->name,
                        'room' => $student?->classroom?->name ?? 'Classe',
                        'teacher' => 'Enseignant',
                    ])
                    ->values(),
            ]);
    @endphp

    <section class="space-y-6 lg:space-y-8" x-data="{ openDay: 'Lundi' }">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0A4FAF]">Organisation de la semaine</p>
            <h1 class="mt-2 text-3xl font-semibold text-slate-950 sm:text-4xl">Emploi du temps</h1>
            <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Consulte facilement tes cours, les salles et les enseignants, sur desktop comme sur mobile.</p>
        </div>

        <section class="admin-card hidden overflow-x-auto p-6 lg:block">
            <div class="grid min-w-[980px] grid-cols-6 gap-4">
                @foreach ($days as $day => $slots)
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <h2 class="text-lg font-semibold text-slate-950">{{ $day }}</h2>
                        <div class="mt-4 space-y-3">
                            @forelse ($slots as $slot)
                                <article class="rounded-2xl bg-white p-3 shadow-sm">
                                    <p class="text-sm font-semibold text-slate-950">{{ $slot['time'] }} · {{ $slot['subject'] }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $slot['room'] }} · {{ $slot['teacher'] }}</p>
                                </article>
                            @empty
                                <p class="text-sm text-slate-400">Aucun cours</p>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="space-y-3 lg:hidden">
            @foreach ($days as $day => $slots)
                <article class="admin-card overflow-hidden">
                    <button type="button" class="flex w-full items-center justify-between px-5 py-4 text-left" @click="openDay = openDay === '{{ $day }}' ? '' : '{{ $day }}'">
                        <span class="text-lg font-semibold text-slate-950">{{ $day }}</span>
                        <x-traiqi-icon name="chevron" class="h-5 w-5 text-slate-400" />
                    </button>
                    <div x-show="openDay === '{{ $day }}'" x-transition.opacity class="border-t border-slate-100 px-5 py-4">
                        <div class="space-y-3">
                            @forelse ($slots as $slot)
                                <article class="rounded-2xl bg-slate-50 p-3">
                                    <p class="text-sm font-semibold text-slate-950">{{ $slot['time'] }} · {{ $slot['subject'] }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $slot['room'] }} · {{ $slot['teacher'] }}</p>
                                </article>
                            @empty
                                <p class="text-sm text-slate-400">Aucun cours</p>
                            @endforelse
                        </div>
                    </div>
                </article>
            @endforeach
        </section>

        <div class="grid gap-6 xl:grid-cols-[1.08fr_0.92fr]">
            <section class="admin-card p-6 sm:p-7">
                <h2 class="text-xl font-semibold text-slate-950">Prochain cours</h2>
                <div class="mt-6 rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm text-slate-500">Aujourd’hui à 10:15</p>
                    <p class="mt-2 text-xl font-semibold text-slate-950">Français · Salle A04</p>
                    <p class="mt-2 text-sm text-slate-600">Avec M. El Fassi</p>
                </div>
            </section>

            <section class="admin-card p-6 sm:p-7">
                <h2 class="text-xl font-semibold text-slate-950">Téléchargement</h2>
                <p class="mt-3 text-sm leading-7 text-slate-600">Préparer une version imprimable ou exportable de l’emploi du temps.</p>
                <div class="mt-6">
                    <x-button type="button" variant="secondary" onclick="window.print()">Télécharger / imprimer</x-button>
                </div>
            </section>
        </div>
    </section>
@endsection
