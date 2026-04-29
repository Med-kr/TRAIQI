@extends('layouts.teacher')

@section('title', 'Dashboard enseignant')

@section('content')
    @php
        $kpis = [
            ['label' => 'Mes classes', 'value' => '6'],
            ['label' => 'Total élèves', 'value' => '184'],
            ['label' => 'Évaluations ce mois', 'value' => '12'],
            ['label' => 'Notes restantes', 'value' => '37'],
            ['label' => 'Moyenne générale', 'value' => '14.6/20'],
            ['label' => 'Présence moyenne', 'value' => '92%'],
        ];

        $schedule = [
            ['time' => '08:30', 'class' => '1ère Bac Sciences A', 'subject' => 'Mathématiques', 'room' => 'Salle B12'],
            ['time' => '10:15', 'class' => '2AC-B', 'subject' => 'Mathématiques', 'room' => 'Salle C04'],
            ['time' => '14:00', 'class' => 'Terminale PC', 'subject' => 'Mathématiques', 'room' => 'Salle A06'],
        ];

        $notifications = [
            ['title' => 'Résultats prêts à publier', 'copy' => 'L’évaluation Contrôle continu - 2AC-B peut être publiée.', 'time' => 'Il y a 20 min'],
            ['title' => 'Demande de révision', 'copy' => 'Deux parents ont soumis une demande sur le devoir de sciences.', 'time' => 'Aujourd’hui, 09:05'],
            ['title' => 'Nouveaux élèves importés', 'copy' => 'Les listes de 1ère Bac ont été mises à jour dans vos classes.', 'time' => 'Hier'],
        ];

        $attention = [
            ['name' => 'Imane Tazi', 'reason' => 'Absences fréquentes cette semaine'],
            ['name' => 'Yassir Ouali', 'reason' => 'Moyenne en baisse sur trois évaluations'],
            ['name' => 'Salma B.', 'reason' => 'Notes non saisies sur le dernier contrôle'],
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
                        <h2 class="text-xl font-semibold text-slate-950">Emploi du temps du jour</h2>
                        <p class="mt-2 text-sm text-slate-500">Vue rapide des séances à assurer aujourd’hui.</p>
                    </div>
                    <span class="admin-pill is-neutral">Aujourd’hui</span>
                </div>

                <div class="mt-6 space-y-4">
                    @foreach ($schedule as $item)
                        <article class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-lg font-semibold text-slate-950">{{ $item['class'] }}</p>
                                    <p class="mt-1 text-sm text-slate-600">{{ $item['subject'] }} · {{ $item['room'] }}</p>
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
                    <x-button href="#" class="w-full justify-center">Nouvelle évaluation</x-button>
                    <x-button href="#" variant="secondary" class="w-full justify-center">Saisir notes</x-button>
                    <x-button href="#" variant="dark" class="w-full justify-center">Voir classe</x-button>
                    <x-button href="#" variant="secondary" class="w-full justify-center">Export notes</x-button>
                </div>
            </section>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.05fr_0.95fr]">
            <section class="admin-card p-6 sm:p-7">
                <div class="flex items-center justify-between gap-4">
                    <h2 class="text-xl font-semibold text-slate-950">Notifications récentes</h2>
                    <span class="admin-pill is-neutral">3 nouvelles</span>
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

            <section class="admin-card p-6 sm:p-7">
                <div class="flex items-center justify-between gap-4">
                    <h2 class="text-xl font-semibold text-slate-950">Élèves à accompagner</h2>
                    <span class="admin-pill is-warning">Priorité</span>
                </div>
                <div class="mt-6 space-y-4">
                    @foreach ($attention as $item)
                        <article class="rounded-2xl border border-slate-100 p-4">
                            <h3 class="font-semibold text-slate-950">{{ $item['name'] }}</h3>
                            <p class="mt-2 text-sm leading-7 text-slate-600">{{ $item['reason'] }}</p>
                        </article>
                    @endforeach
                </div>
            </section>
        </div>
    </section>
@endsection
