@extends('layouts.parent')

@section('title', 'Dashboard parent')

@section('content')
    @php
        $kpis = [
            ['label' => "Nombre d'enfants liés", 'value' => $childrenCount],
            ['label' => 'Moyenne générale', 'value' => $average !== null ? number_format((float) $average, 2) . '/20' : 'N/A'],
            ['label' => 'Notes publiées', 'value' => $gradesCount],
            ['label' => 'Messages non lus', 'value' => $unreadNotificationsCount],
            ['label' => 'Demandes de révision', 'value' => $reviewRequests->count()],
        ];
    @endphp

    <section class="space-y-6 lg:space-y-8">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0A4FAF]">Portail famille</p>
            <h1 class="mt-2 text-3xl font-semibold text-slate-950 sm:text-4xl">Bienvenue</h1>
            <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Suivez sereinement la progression scolaire de vos enfants, les messages de l’établissement et les rendez-vous importants depuis un espace clair et rassurant.</p>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
            @foreach ($kpis as $item)
                <article class="admin-stat-card">
                    <p class="text-sm font-medium text-slate-500">{{ $item['label'] }}</p>
                    <p class="mt-4 text-3xl font-semibold text-slate-950">{{ $item['value'] }}</p>
                </article>
            @endforeach
        </div>

        <section class="space-y-4">
            <div class="flex items-center justify-between gap-4">
                <h2 class="text-xl font-semibold text-slate-950">Vue rapide de mes enfants</h2>
                <x-button href="{{ route('parent.children.index') }}" variant="secondary" size="sm">Voir tout</x-button>
            </div>

            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($childrenWithGrades as $item)
                    @php
                        $child = $item['child'];
                        $averageValue = $item['average'];
                    @endphp
                    <article class="admin-card p-6">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-4">
                                <span class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-[#0A4FAF]/10 text-base font-semibold text-[#0A4FAF]">
                                    {{ \Illuminate\Support\Str::of($child->name)->explode(' ')->map(fn ($part) => mb_substr($part, 0, 1))->take(2)->implode('') }}
                                </span>
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-950">{{ $child->name }}</h3>
                                    <p class="mt-1 text-sm text-slate-500">{{ $child->studentProfile?->classroom?->name ?? 'Classe non définie' }}</p>
                                </div>
                            </div>
                            <span class="admin-pill is-success">Actif</span>
                        </div>

                        <div class="mt-6 grid gap-4 sm:grid-cols-2">
                            <div class="rounded-2xl bg-slate-50 p-4">
                                <p class="text-sm text-slate-500">Moyenne</p>
                                <p class="mt-2 text-2xl font-semibold text-slate-950">{{ $averageValue !== null ? number_format((float) $averageValue, 2) . '/20' : 'N/A' }}</p>
                            </div>
                            <div class="rounded-2xl bg-slate-50 p-4">
                                <p class="text-sm text-slate-500">Notes</p>
                                <p class="mt-2 text-2xl font-semibold text-slate-950">{{ $item['grades']->count() }}</p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <x-button href="{{ route('parent.grades.index') }}" class="w-full justify-center">Voir détail</x-button>
                        </div>
                    </article>
                @empty
                    <div class="admin-card p-6 text-sm text-slate-500 md:col-span-2 xl:col-span-3">
                        Aucun enfant n'est lié à votre compte pour le moment.
                    </div>
                @endforelse
            </div>
        </section>

        <div class="grid gap-6 xl:grid-cols-[1.05fr_0.95fr]">
            <section class="admin-card p-6 sm:p-7">
                <div class="flex items-center justify-between gap-4">
                    <h2 class="text-xl font-semibold text-slate-950">Notifications récentes</h2>
                    <span class="admin-pill is-neutral">À suivre</span>
                </div>
                <div class="mt-6 space-y-4">
                    @forelse ($notifications as $item)
                        <article class="rounded-2xl bg-slate-50 p-4">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h3 class="font-semibold text-slate-950">{{ $item->title }}</h3>
                                    <p class="mt-2 text-sm leading-7 text-slate-600">{{ $item->body }}</p>
                                </div>
                                <span class="text-xs font-medium text-slate-400">{{ $item->created_at?->diffForHumans() }}</span>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-5 text-sm text-slate-500">Aucune notification récente.</div>
                    @endforelse
                </div>
            </section>

            <div class="space-y-6">
                <section class="admin-card p-6 sm:p-7">
                    <h2 class="text-xl font-semibold text-slate-950">Actions rapides</h2>
                    <div class="mt-6 grid gap-3">
                        <x-button href="{{ route('parent.grades.index') }}" class="w-full justify-center">Voir notes</x-button>
                        <x-button href="{{ route('parent.messages.index') }}" variant="secondary" class="w-full justify-center">Voir messages</x-button>
                        <x-button href="{{ route('parent.appointments.index') }}" variant="dark" class="w-full justify-center">Demandes de révision</x-button>
                    </div>
                </section>

                <section class="admin-card p-6 sm:p-7">
                    <div class="flex items-center justify-between gap-4">
                        <h2 class="text-xl font-semibold text-slate-950">Événements à venir</h2>
                        <span class="admin-pill is-warning">Agenda</span>
                    </div>
                    <div class="mt-6 space-y-4">
                        @forelse ($reviewRequests as $event)
                            <article class="rounded-2xl border border-slate-100 p-4">
                                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-[#0A4FAF]">{{ $event->status }}</p>
                                <h3 class="mt-2 font-semibold text-slate-950">{{ $event->grade?->evaluation?->subject?->name ?? 'Demande de révision' }}</h3>
                                <p class="mt-2 text-sm text-slate-500">{{ $event->created_at?->format('Y-m-d H:i') }}</p>
                            </article>
                        @empty
                            <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-5 text-sm text-slate-500">Aucune demande de révision en cours.</div>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>
    </section>
@endsection
