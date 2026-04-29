@extends('layouts.student')

@section('title', 'Dashboard élève')

@section('content')
    @php
        $kpis = [
            ['label' => 'Moyenne générale', 'value' => '15.4/20'],
            ['label' => 'Présence', 'value' => '96%'],
            ['label' => 'Devoirs à venir', 'value' => '3'],
            ['label' => 'Notifications nouvelles', 'value' => '5'],
            ['label' => 'Rang classe', 'value' => '4e'],
            ['label' => 'Matières validées', 'value' => '8/10'],
        ];

        $schedule = [
            ['time' => '08:30', 'subject' => 'Mathématiques', 'room' => 'Salle B12', 'teacher' => 'Mme Chraibi'],
            ['time' => '10:15', 'subject' => 'Français', 'room' => 'Salle A04', 'teacher' => 'M. El Fassi'],
            ['time' => '14:00', 'subject' => 'Sciences', 'room' => 'Labo 2', 'teacher' => 'Mme Amrani'],
        ];

        $grades = [
            ['subject' => 'Mathématiques', 'grade' => '17/20', 'type' => 'Contrôle continu'],
            ['subject' => 'Français', 'grade' => '14/20', 'type' => 'Devoir'],
            ['subject' => 'Anglais', 'grade' => '13/20', 'type' => 'Examen'],
        ];
    @endphp

    <section class="space-y-6 lg:space-y-8">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0A4FAF]">Espace élève</p>
            <h1 class="mt-2 text-3xl font-semibold text-slate-950 sm:text-4xl">Bienvenue</h1>
            <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Un espace moderne pour suivre tes résultats, ton emploi du temps et ta progression avec clarté et motivation.</p>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-6">
            @foreach ($kpis as $item)
                <article class="admin-stat-card">
                    <p class="text-sm font-medium text-slate-500">{{ $item['label'] }}</p>
                    <p class="mt-4 text-3xl font-semibold text-slate-950">{{ $item['value'] }}</p>
                </article>
            @endforeach
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.05fr_0.95fr]">
            <section class="admin-card p-6 sm:p-7">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-semibold text-slate-950">Programme du jour</h2>
                        <p class="mt-2 text-sm text-slate-500">Tes prochains cours en un coup d’œil.</p>
                    </div>
                    <span class="admin-pill is-neutral">Aujourd’hui</span>
                </div>

                <div class="mt-6 space-y-4">
                    @foreach ($schedule as $item)
                        <article class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <h3 class="font-semibold text-slate-950">{{ $item['subject'] }}</h3>
                                    <p class="mt-1 text-sm text-slate-600">{{ $item['room'] }} · {{ $item['teacher'] }}</p>
                                </div>
                                <span class="admin-pill is-success">{{ $item['time'] }}</span>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>

            <section class="admin-card p-6 sm:p-7">
                <h2 class="text-xl font-semibold text-slate-950">Actions rapides</h2>
                <div class="mt-6 grid gap-3 sm:grid-cols-2">
                    <x-button href="#" class="w-full justify-center">Voir notes</x-button>
                    <x-button href="#" variant="secondary" class="w-full justify-center">Voir emploi du temps</x-button>
                    <x-button href="#" variant="dark" class="w-full justify-center">Consulter progression</x-button>
                </div>
            </section>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.05fr_0.95fr]">
            <section class="admin-card p-6 sm:p-7">
                <div class="flex items-center justify-between gap-4">
                    <h2 class="text-xl font-semibold text-slate-950">Dernières notes</h2>
                    <span class="admin-pill is-neutral">Récent</span>
                </div>
                <div class="mt-6 space-y-4">
                    @foreach ($grades as $item)
                        <article class="rounded-2xl bg-slate-50 p-4">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <h3 class="font-semibold text-slate-950">{{ $item['subject'] }}</h3>
                                    <p class="mt-1 text-sm text-slate-600">{{ $item['type'] }}</p>
                                </div>
                                <span class="text-lg font-semibold text-slate-950">{{ $item['grade'] }}</span>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>

            <section class="admin-card p-6 sm:p-7">
                <div class="flex items-center justify-between gap-4">
                    <h2 class="text-xl font-semibold text-slate-950">Motivation</h2>
                    <span class="admin-pill is-success">Progression</span>
                </div>
                <div class="mt-6 space-y-4">
                    <blockquote class="rounded-2xl bg-slate-50 p-4 text-sm leading-7 text-slate-700">
                        “Chaque effort régulier construit une vraie avance. Continue sur ta lancée.”
                    </blockquote>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-sm text-slate-500">Badge de progression</p>
                        <p class="mt-2 text-2xl font-semibold text-slate-950">Semaine positive</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-sm text-slate-500">Objectif hebdomadaire</p>
                        <p class="mt-2 text-base font-semibold text-slate-950">Finaliser les révisions de mathématiques avant jeudi.</p>
                    </div>
                </div>
            </section>
        </div>
    </section>
@endsection
