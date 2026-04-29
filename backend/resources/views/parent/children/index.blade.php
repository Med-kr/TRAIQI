@extends('layouts.parent')

@section('title', 'Mes enfants')

@section('content')
    <section class="space-y-6 lg:space-y-8">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0A4FAF]">Suivi individuel</p>
            <h1 class="mt-2 text-3xl font-semibold text-slate-950 sm:text-4xl">Mes enfants</h1>
            <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Retrouvez pour chaque enfant l’essentiel: classe, moyenne actuelle, présence, dernière note et évolution récente.</p>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-2">
                @forelse ($childrenWithGrades as $item)
                    @php
                        $child = $item['child'];
                        $progress = $item['progress'];
                        $progressLabel = $progress === null ? 'À compléter' : ($progress > 0 ? 'En hausse' : ($progress < 0 ? 'À suivre' : 'Stable'));
                    @endphp
                    <article class="admin-card p-6">
                        <div class="flex items-start gap-4">
                            <span class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-[#0A4FAF]/10 text-lg font-semibold text-[#0A4FAF]">
                                {{ \Illuminate\Support\Str::of($child->name)->explode(' ')->map(fn ($part) => mb_substr($part, 0, 1))->take(2)->implode('') }}
                            </span>
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center justify-between gap-3">
                                    <h2 class="text-lg font-semibold text-slate-950">{{ $child->name }}</h2>
                                    <span class="admin-pill {{ $progressLabel === 'En hausse' ? 'is-success' : ($progressLabel === 'À suivre' ? 'is-warning' : 'is-neutral') }}">{{ $progressLabel }}</span>
                                </div>
                                <p class="mt-1 text-sm text-slate-500">{{ $child->studentProfile?->classroom?->name ?? 'Classe non définie' }}</p>
                            </div>
                        </div>

                        <div class="mt-6 grid gap-4 sm:grid-cols-3">
                            <div class="rounded-2xl bg-slate-50 p-4">
                                <p class="text-sm text-slate-500">Moyenne</p>
                                <p class="mt-2 text-xl font-semibold text-slate-950">{{ $item['average'] !== null ? number_format((float) $item['average'], 2) . '/20' : 'N/A' }}</p>
                            </div>
                            <div class="rounded-2xl bg-slate-50 p-4">
                                <p class="text-sm text-slate-500">Notes</p>
                                <p class="mt-2 text-xl font-semibold text-slate-950">{{ $item['grades']->count() }}</p>
                            </div>
                            <div class="rounded-2xl bg-slate-50 p-4">
                                <p class="text-sm text-slate-500">Dernière note</p>
                                <p class="mt-2 text-xl font-semibold text-slate-950">{{ $item['lastGrade'] ? number_format((float) $item['lastGrade']->value, 2) . '/20' : 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="mt-6 flex flex-wrap gap-2">
                            <x-button href="{{ route('parent.grades.index') }}" size="sm">Voir notes</x-button>
                            <x-button href="{{ route('parent.messages.index') }}" variant="secondary" size="sm">Messages</x-button>
                            <x-button href="{{ route('parent.appointments.index') }}" variant="ghost" size="sm">Suivi</x-button>
                        </div>
                    </article>
                @empty
                    <div class="admin-card p-6 text-sm text-slate-500 md:col-span-2">
                        Aucun enfant n'est lié à votre compte pour le moment.
                    </div>
                @endforelse
            </div>

            <aside class="space-y-6">
                <section class="admin-card p-6 sm:p-7">
                    <h2 class="text-xl font-semibold text-slate-950">Résumé famille</h2>
                    <div class="mt-6 space-y-4">
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-sm text-slate-500">Total enfants</p>
                            <p class="mt-2 text-3xl font-semibold text-slate-950">{{ $childrenCount }}</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-sm text-slate-500">Performance globale famille</p>
                            <p class="mt-2 text-3xl font-semibold text-slate-950">{{ $average !== null ? number_format((float) $average, 2) . '/20' : 'N/A' }}</p>
                        </div>
                    </div>
                </section>
            </aside>
        </div>
    </section>
@endsection
