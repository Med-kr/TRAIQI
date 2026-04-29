@extends('layouts.teacher')

@section('title', 'Mes classes')

@section('content')
    <section class="space-y-6 lg:space-y-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0A4FAF]">Organisation des groupes</p>
                <h1 class="mt-2 text-3xl font-semibold text-slate-950 sm:text-4xl">Mes classes</h1>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Une vue compacte de vos groupes, leur effectif, leur prochaine séance et la progression pédagogique du programme.</p>
            </div>
        </div>

        <div class="grid gap-5 md:grid-cols-2 2xl:grid-cols-4">
            @forelse ($assignments as $assignment)
                @php
                    $studentCount = $assignment->classroom?->students?->count() ?? 0;
                    $evaluationCount = $evaluations
                        ->where('classroom_id', $assignment->classroom_id)
                        ->where('subject_id', $assignment->subject_id)
                        ->count();
                    $progress = $studentCount > 0 ? min(100, (int) round(($evaluationCount / max(1, $studentCount)) * 100)) : 0;
                @endphp
                <article class="admin-card p-6 transition duration-300 hover:-translate-y-1">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-950">{{ $assignment->classroom?->name ?? 'Classe non définie' }}</h2>
                            <p class="mt-1 text-sm text-slate-500">{{ $assignment->classroom?->level?->name ?? 'Niveau non défini' }} · {{ $assignment->subject?->name ?? 'Matière non définie' }}</p>
                        </div>
                        <span class="admin-pill is-neutral">{{ $studentCount }} élèves</span>
                    </div>

                    <p class="mt-5 text-sm text-slate-600"><span class="font-medium text-slate-800">Évaluations:</span> {{ $evaluationCount }} créée(s)</p>

                    <div class="mt-6">
                        <div class="mb-2 flex items-center justify-between text-sm">
                            <span class="font-medium text-slate-700">Couverture notes</span>
                            <span class="text-slate-500">{{ $progress }}%</span>
                        </div>
                        <div class="h-3 rounded-full bg-slate-100">
                            <div class="h-3 rounded-full bg-gradient-to-r from-[#0A4FAF] to-[#18A558]" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-wrap gap-2">
                        <x-button href="{{ route('teacher.students.index') }}" variant="secondary" size="sm">Voir élèves</x-button>
                        <x-button href="{{ route('teacher.grades.index') }}" size="sm">Notes</x-button>
                        <x-button href="{{ route('teacher.statistics.index') }}" variant="ghost" size="sm">Statistiques</x-button>
                    </div>
                </article>
            @empty
                <div class="admin-card p-6 text-sm text-slate-500 md:col-span-2 2xl:col-span-4">
                    Aucune classe affectée à votre compte.
                </div>
            @endforelse
        </div>
    </section>
@endsection
