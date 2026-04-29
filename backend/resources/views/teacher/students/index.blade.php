@extends('layouts.teacher')

@section('title', 'Liste élèves')

@section('content')
    <section class="space-y-6 lg:space-y-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0A4FAF]">Suivi individualisé</p>
                <h1 class="mt-2 text-3xl font-semibold text-slate-950 sm:text-4xl">Liste élèves</h1>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Repérez rapidement les niveaux, la présence et les tendances de performance pour intervenir au bon moment.</p>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.12fr_0.88fr]">
            <div class="space-y-6">
                <section class="admin-table-wrap">
                    <div class="overflow-x-auto">
                        <table class="admin-table min-w-[980px]">
                            <thead>
                                <tr>
                                    <th>Photo</th>
                                    <th>Nom complet</th>
                                    <th>Classe</th>
                                    <th>Dernière note</th>
                                    <th>Moyenne</th>
                                    <th>Statut</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($students as $studentProfile)
                                    @php
                                        $student = $studentProfile->user;
                                        $studentGrades = $gradesByStudent->get($studentProfile->user_id, collect());
                                        $lastGrade = $studentGrades->first();
                                        $average = $studentGrades->avg('value');
                                        $status = $average === null ? 'À compléter' : ($average >= 16 ? 'Excellent' : ($average < 10 ? 'À suivre' : 'Stable'));
                                    @endphp
                                    <tr class="transition hover:bg-slate-50/80">
                                        <td>
                                            <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-[#0A4FAF]/10 text-sm font-semibold text-[#0A4FAF]">
                                                {{ \Illuminate\Support\Str::of($student?->name ?? 'NA')->explode(' ')->map(fn ($part) => mb_substr($part, 0, 1))->take(2)->implode('') }}
                                            </span>
                                        </td>
                                        <td class="font-semibold text-slate-950">{{ $student?->name ?? 'Élève non défini' }}</td>
                                        <td>{{ $studentProfile->classroom?->name ?? 'Classe non définie' }}</td>
                                        <td>{{ $lastGrade ? number_format((float) $lastGrade->value, 2) . '/20' : 'Non saisie' }}</td>
                                        <td>{{ $average !== null ? number_format((float) $average, 2) . '/20' : 'Non calculée' }}</td>
                                        <td>
                                            <span class="admin-pill {{ $status === 'Excellent' ? 'is-success' : ($status === 'À suivre' ? 'is-warning' : 'is-neutral') }}">
                                                {{ $status }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="flex justify-end gap-2">
                                                <x-button href="{{ route('teacher.grades.index') }}" variant="secondary" size="sm">Notes</x-button>
                                                <x-button href="{{ route('teacher.statistics.index') }}" variant="dark" size="sm">Statistiques</x-button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-8 text-center text-sm text-slate-500">Aucun élève trouvé dans vos classes.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <aside class="space-y-6">
                <section class="admin-card p-6 sm:p-7">
                    <h2 class="text-xl font-semibold text-slate-950">Repères rapides</h2>
                    <div class="mt-6 space-y-4">
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="font-semibold text-slate-950">Élèves excellents</p>
                            <p class="mt-2 text-sm text-slate-600">{{ $gradesByStudent->filter(fn ($grades) => $grades->avg('value') >= 16)->count() }} élève(s) avec une moyenne supérieure à 16/20.</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="font-semibold text-slate-950">Élèves en difficulté</p>
                            <p class="mt-2 text-sm text-slate-600">{{ $gradesByStudent->filter(fn ($grades) => $grades->avg('value') !== null && $grades->avg('value') < 10)->count() }} profil(s) demandent une remédiation ciblée.</p>
                        </div>
                    </div>
                </section>
            </aside>
        </div>
    </section>
@endsection
