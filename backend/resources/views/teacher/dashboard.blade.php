@extends('layouts.teacher')

@section('title', 'Dashboard enseignant')

@section('content')
    @php
        $kpis = [
            ['label' => 'Mes classes', 'value' => $assignmentsCount],
            ['label' => 'Total élèves', 'value' => $trackedStudentsCount],
            ['label' => 'Évaluations', 'value' => $evaluationsCount],
            ['label' => 'Notes saisies', 'value' => $gradesCount],
            ['label' => 'Notes restantes', 'value' => $remainingCopiesCount],
            ['label' => 'Moyenne générale', 'value' => number_format((float) $classAverage, 2) . '/20'],
        ];
    @endphp

    <section class="space-y-6 lg:space-y-8">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0A4FAF]">Espace pédagogique</p>
            <h1 class="mt-2 text-3xl font-semibold text-slate-950 sm:text-4xl">Bienvenue Professeur</h1>
            <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Un espace de travail clair pour enseigner, évaluer, suivre les progrès et intervenir rapidement là où il faut.</p>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-6">
            @foreach ($kpis as $item)
                <article class="admin-stat-card">
                    <p class="text-sm font-medium text-slate-500">{{ $item['label'] }}</p>
                    <p class="mt-4 text-3xl font-semibold text-slate-950">{{ $item['value'] }}</p>
                </article>
            @endforeach
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
            <section class="admin-card p-6 sm:p-7">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-semibold text-slate-950">Mes affectations</h2>
                        <p class="mt-2 text-sm text-slate-500">Classes et matières attribuées à votre compte.</p>
                    </div>
                    <span class="admin-pill is-neutral">{{ $assignmentsCount }} affectation(s)</span>
                </div>

                <div class="mt-6 space-y-4">
                    @forelse ($assignments->take(4) as $assignment)
                        <article class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-lg font-semibold text-slate-950">{{ $assignment->classroom?->name ?? 'Classe non définie' }}</p>
                                    <p class="mt-1 text-sm text-slate-600">{{ $assignment->subject?->name ?? 'Matière non définie' }} · {{ $assignment->classroom?->level?->name ?? 'Niveau non défini' }}</p>
                                </div>
                                <span class="admin-pill is-success">{{ $assignment->classroom?->students?->count() ?? 0 }} élèves</span>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-5 text-sm text-slate-500">
                            Aucune affectation trouvée. L'administration doit d'abord vous lier à une classe et une matière.
                        </div>
                    @endforelse
                </div>
            </section>

            <section class="admin-card p-6 sm:p-7">
                <h2 class="text-xl font-semibold text-slate-950">Actions rapides</h2>
                <div class="mt-6 grid gap-3 sm:grid-cols-2">
                    <x-button href="{{ route('teacher.evaluations.create') }}" class="w-full justify-center">Nouvelle évaluation</x-button>
                    <x-button href="{{ route('teacher.grades.index') }}" variant="secondary" class="w-full justify-center">Saisir notes</x-button>
                    <x-button href="{{ route('teacher.classes.index') }}" variant="dark" class="w-full justify-center">Voir classes</x-button>
                    <x-button href="{{ route('teacher.statistics.index') }}" variant="secondary" class="w-full justify-center">Statistiques</x-button>
                </div>
            </section>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.05fr_0.95fr]">
            <section class="admin-card p-6 sm:p-7">
                <div class="flex items-center justify-between gap-4">
                    <h2 class="text-xl font-semibold text-slate-950">Évaluations récentes</h2>
                    <span class="admin-pill is-neutral">{{ $evaluationsCount }}</span>
                </div>
                <div class="mt-6 space-y-4">
                    @forelse ($evaluations->take(5) as $evaluation)
                        <article class="rounded-2xl bg-slate-50 p-4">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h3 class="font-semibold text-slate-950">{{ $evaluation->title }}</h3>
                                    <p class="mt-2 text-sm leading-7 text-slate-600">{{ $evaluation->classroom?->name ?? 'Classe' }} · {{ $evaluation->subject?->name ?? 'Matière' }}</p>
                                </div>
                                <a class="admin-pill is-neutral" href="{{ route('teacher.grades.show', $evaluation->id) }}">Ouvrir</a>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-5 text-sm text-slate-500">Aucune évaluation créée.</div>
                    @endforelse
                </div>
            </section>

            <section class="admin-card p-6 sm:p-7">
                <div class="flex items-center justify-between gap-4">
                    <h2 class="text-xl font-semibold text-slate-950">Élèves à accompagner</h2>
                    <span class="admin-pill is-warning">{{ $studentsInDifficultyCount }} profil(s)</span>
                </div>
                <div class="mt-6 space-y-4">
                    <article class="rounded-2xl border border-slate-100 p-4">
                        <h3 class="font-semibold text-slate-950">Notes inférieures à 10/20</h3>
                        <p class="mt-2 text-sm leading-7 text-slate-600">{{ $studentsInDifficultyCount }} élève(s) ont au moins une note en difficulté dans vos évaluations.</p>
                    </article>
                    <article class="rounded-2xl border border-slate-100 p-4">
                        <h3 class="font-semibold text-slate-950">Copies restantes</h3>
                        <p class="mt-2 text-sm leading-7 text-slate-600">{{ $remainingCopiesCount }} note(s) restent à saisir pour compléter vos évaluations.</p>
                    </article>
                </div>
            </section>
        </div>
    </section>
@endsection
