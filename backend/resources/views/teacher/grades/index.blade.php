@extends('layouts.teacher')

@section('title', 'Saisie des notes')

@section('content')
    <section class="space-y-6 lg:space-y-8" x-data="{ saved: true }">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0A4FAF]">Correction & publication</p>
            <h1 class="mt-2 text-3xl font-semibold text-slate-950 sm:text-4xl">Saisie des notes</h1>
            <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Saisissez, ajustez et publiez les résultats avec une table lisible et des repères visuels rapides.</p>
        </div>

        <section class="admin-table-wrap">
            <div class="flex items-center justify-between gap-4 border-b border-slate-200 px-6 py-5">
                <div>
                    <h2 class="text-xl font-semibold text-slate-950">Évaluations à noter</h2>
                    <p class="mt-1 text-sm text-slate-500">Ouvrez une évaluation pour saisir ou corriger les notes.</p>
                </div>
                <span class="admin-pill is-success">{{ $evaluations->count() }} évaluation(s)</span>
            </div>

            <div class="overflow-x-auto">
                <table class="admin-table min-w-[980px]">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Classe</th>
                            <th>Matière</th>
                            <th>Date</th>
                            <th>Notes saisies</th>
                            <th>Statut</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($evaluations as $evaluation)
                            @php
                                $studentCount = $evaluation->classroom?->students?->count() ?? 0;
                                $gradeCount = $evaluation->grades?->count() ?? 0;
                            @endphp
                            <tr class="transition hover:bg-slate-50/80">
                                <td class="font-semibold text-slate-950">{{ $evaluation->title }}</td>
                                <td>{{ $evaluation->classroom?->name ?? 'Classe' }}</td>
                                <td>{{ $evaluation->subject?->name ?? 'Matière' }}</td>
                                <td>{{ $evaluation->date?->format('Y-m-d') }}</td>
                                <td>{{ $gradeCount }} / {{ $studentCount }}</td>
                                <td>
                                    <span class="admin-pill {{ $evaluation->is_locked ? 'is-warning' : ($evaluation->is_published ? 'is-success' : 'is-neutral') }}">
                                        {{ $evaluation->is_locked ? 'Verrouillée' : ($evaluation->is_published ? 'Publiée' : 'Brouillon') }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <x-button href="{{ route('teacher.grades.show', $evaluation->id) }}" size="sm">Ouvrir</x-button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-sm text-slate-500">Aucune évaluation disponible pour la saisie des notes.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div class="flex flex-wrap gap-3">
            <x-button href="{{ route('teacher.evaluations.create') }}">Créer une évaluation</x-button>
            <x-button href="{{ route('teacher.dashboard') }}" variant="secondary">Retour dashboard</x-button>
        </div>
    </section>
@endsection
