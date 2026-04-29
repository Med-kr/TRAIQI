@extends('layouts.parent')

@section('title', 'Dashboard parent')

@section('content')
    @php
        $kpis = [
            ['label' => "Nombre d'enfants liés", 'value' => '3'],
            ['label' => 'Moyenne générale', 'value' => '14.9/20'],
            ['label' => 'Nouvelles notes', 'value' => '6'],
            ['label' => 'Messages non lus', 'value' => '4'],
            ['label' => 'Rendez-vous à venir', 'value' => '2'],
        ];

        $children = [
            ['name' => 'Imane Tazi', 'class' => '1ère Bac Sciences A', 'avg' => '16.2', 'attendance' => '97%'],
            ['name' => 'Youssef Tazi', 'class' => '2AC-B', 'avg' => '13.1', 'attendance' => '91%'],
            ['name' => 'Salma Tazi', 'class' => '6ème Primaire A', 'avg' => '15.4', 'attendance' => '98%'],
        ];

        $notifications = [
            ['title' => 'Nouvelle note publiée', 'copy' => 'Mathématiques - Imane Tazi: 17/20.', 'time' => 'Il y a 18 min'],
            ['title' => 'Message enseignant', 'copy' => 'Le professeur principal souhaite partager un point de suivi.', 'time' => 'Aujourd’hui, 08:42'],
            ['title' => 'Réunion parents', 'copy' => 'Une réunion d’orientation est prévue vendredi prochain.', 'time' => 'Hier'],
        ];

        $events = [
            ['type' => 'Examen', 'label' => 'Contrôle continu - Mathématiques', 'date' => '29 avr. 2026'],
            ['type' => 'Réunion', 'label' => 'Entretien avec le professeur principal', 'date' => '02 mai 2026'],
            ['type' => 'Activité école', 'label' => 'Journée projets scientifiques', 'date' => '06 mai 2026'],
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
                <x-button href="#" variant="secondary" size="sm">Voir tout</x-button>
            </div>

            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($children as $child)
                    <article class="admin-card p-6">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-4">
                                <span class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-[#0A4FAF]/10 text-base font-semibold text-[#0A4FAF]">
                                    {{ \Illuminate\Support\Str::of($child['name'])->explode(' ')->map(fn ($part) => mb_substr($part, 0, 1))->take(2)->implode('') }}
                                </span>
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-950">{{ $child['name'] }}</h3>
                                    <p class="mt-1 text-sm text-slate-500">{{ $child['class'] }}</p>
                                </div>
                            </div>
                            <span class="admin-pill is-success">Actif</span>
                        </div>

                        <div class="mt-6 grid gap-4 sm:grid-cols-2">
                            <div class="rounded-2xl bg-slate-50 p-4">
                                <p class="text-sm text-slate-500">Moyenne</p>
                                <p class="mt-2 text-2xl font-semibold text-slate-950">{{ $child['avg'] }}/20</p>
                            </div>
                            <div class="rounded-2xl bg-slate-50 p-4">
                                <p class="text-sm text-slate-500">Présence</p>
                                <p class="mt-2 text-2xl font-semibold text-slate-950">{{ $child['attendance'] }}</p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <x-button href="#" class="w-full justify-center">Voir détail</x-button>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>

        <div class="grid gap-6 xl:grid-cols-[1.05fr_0.95fr]">
            <section class="admin-card p-6 sm:p-7">
                <div class="flex items-center justify-between gap-4">
                    <h2 class="text-xl font-semibold text-slate-950">Notifications récentes</h2>
                    <span class="admin-pill is-neutral">À suivre</span>
                </div>
                <div class="mt-6 space-y-4">
                    @foreach ($notifications as $item)
                        <article class="rounded-2xl bg-slate-50 p-4">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h3 class="font-semibold text-slate-950">{{ $item['title'] }}</h3>
                                    <p class="mt-2 text-sm leading-7 text-slate-600">{{ $item['copy'] }}</p>
                                </div>
                                <span class="text-xs font-medium text-slate-400">{{ $item['time'] }}</span>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>

            <div class="space-y-6">
                <section class="admin-card p-6 sm:p-7">
                    <h2 class="text-xl font-semibold text-slate-950">Actions rapides</h2>
                    <div class="mt-6 grid gap-3">
                        <x-button href="#" class="w-full justify-center">Voir notes</x-button>
                        <x-button href="#" variant="secondary" class="w-full justify-center">Envoyer message</x-button>
                        <x-button href="#" variant="dark" class="w-full justify-center">Demander rendez-vous</x-button>
                    </div>
                </section>

                <section class="admin-card p-6 sm:p-7">
                    <div class="flex items-center justify-between gap-4">
                        <h2 class="text-xl font-semibold text-slate-950">Événements à venir</h2>
                        <span class="admin-pill is-warning">Agenda</span>
                    </div>
                    <div class="mt-6 space-y-4">
                        @foreach ($events as $event)
                            <article class="rounded-2xl border border-slate-100 p-4">
                                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-[#0A4FAF]">{{ $event['type'] }}</p>
                                <h3 class="mt-2 font-semibold text-slate-950">{{ $event['label'] }}</h3>
                                <p class="mt-2 text-sm text-slate-500">{{ $event['date'] }}</p>
                            </article>
                        @endforeach
                    </div>
                </section>
            </div>
        </div>
    </section>
@endsection
