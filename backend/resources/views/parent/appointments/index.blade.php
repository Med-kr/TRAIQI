@extends('layouts.parent')

@section('title', 'Rendez-vous')

@section('content')
    <section class="space-y-6 lg:space-y-8">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0A4FAF]">Coordination</p>
            <h1 class="mt-2 text-3xl font-semibold text-slate-950 sm:text-4xl">Rendez-vous</h1>
            <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Planifiez vos échanges avec l’école, consultez les rencontres à venir et gardez une trace claire des rendez-vous passés.</p>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.02fr_0.98fr]">
            <section class="admin-card p-6 sm:p-7">
                <h2 class="text-xl font-semibold text-slate-950">Demander une révision</h2>
                <p class="mt-3 text-sm leading-7 text-slate-600">Les demandes sont liées à une note précise. Utilisez la page des notes pour choisir l'évaluation concernée et envoyer votre motif.</p>
                <div class="mt-6">
                    <x-button href="{{ route('parent.grades.index') }}">Choisir une note</x-button>
                </div>
            </section>

            <div class="space-y-6">
                <section class="admin-card p-6 sm:p-7">
                    <div class="flex items-center justify-between gap-4">
                        <h2 class="text-xl font-semibold text-slate-950">À venir</h2>
                        <span class="admin-pill is-success">Planifié</span>
                    </div>
                    <div class="mt-6 space-y-4">
                        @forelse ($reviewRequests->where('status', 'pending') as $item)
                            <article class="rounded-2xl border border-slate-100 p-4">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <h3 class="font-semibold text-slate-950">{{ $item->student?->name ?? 'Élève' }} - {{ $item->grade?->evaluation?->subject?->name ?? 'Matière' }}</h3>
                                        <p class="mt-2 text-sm text-slate-600">{{ $item->created_at?->format('Y-m-d H:i') }}</p>
                                        <p class="mt-2 text-sm text-slate-500">{{ $item->reason }}</p>
                                    </div>
                                    <span class="admin-pill is-warning">{{ $item->status }}</span>
                                </div>
                            </article>
                        @empty
                            <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-5 text-sm text-slate-500">Aucune demande en attente.</div>
                        @endforelse
                    </div>
                </section>

                <section class="admin-card p-6 sm:p-7">
                    <h2 class="text-xl font-semibold text-slate-950">Historique</h2>
                    <div class="mt-6 space-y-4">
                        @forelse ($reviewRequests->where('status', '!=', 'pending') as $item)
                            <article class="rounded-2xl bg-slate-50 p-4">
                                <h3 class="font-semibold text-slate-950">{{ $item->student?->name ?? 'Élève' }} - {{ $item->grade?->evaluation?->subject?->name ?? 'Matière' }}</h3>
                                <p class="mt-2 text-sm text-slate-600">{{ $item->created_at?->format('Y-m-d H:i') }}</p>
                                <p class="mt-2 text-sm text-slate-500">{{ $item->status }}</p>
                            </article>
                        @empty
                            <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-5 text-sm text-slate-500">Aucun historique pour le moment.</div>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>
    </section>
@endsection
