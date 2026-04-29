@extends('layouts.teacher')

@section('title', 'Mes classes')

@section('content')
    @php
        $classes = [
            ['name' => '1ère Bac Sciences A', 'level' => 'Lycée', 'students' => 34, 'subject' => 'Mathématiques', 'next' => 'Demain 08:30', 'progress' => 74],
            ['name' => '2AC-B', 'level' => 'Collège', 'students' => 31, 'subject' => 'Mathématiques', 'next' => 'Aujourd’hui 10:15', 'progress' => 68],
            ['name' => 'Terminale PC', 'level' => 'Lycée', 'students' => 28, 'subject' => 'Mathématiques', 'next' => 'Aujourd’hui 14:00', 'progress' => 81],
            ['name' => '3AC-A', 'level' => 'Collège', 'students' => 30, 'subject' => 'Mathématiques', 'next' => 'Jeudi 09:20', 'progress' => 72],
        ];
    @endphp

    <section class="space-y-6 lg:space-y-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0A4FAF]">Organisation des groupes</p>
                <h1 class="mt-2 text-3xl font-semibold text-slate-950 sm:text-4xl">Mes classes</h1>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Une vue compacte de vos groupes, leur effectif, leur prochaine séance et la progression pédagogique du programme.</p>
            </div>
        </div>

        <section class="admin-card p-5 sm:p-6">
            <div class="grid gap-4 lg:grid-cols-[1fr_0.7fr_auto]">
                <input type="search" class="admin-toolbar-input" placeholder="Rechercher une classe">
                <select class="admin-toolbar-input">
                    <option>Tous les niveaux</option>
                    <option>Collège</option>
                    <option>Lycée</option>
                </select>
                <x-button variant="secondary" class="w-full justify-center">Filtrer</x-button>
            </div>
        </section>

        <div class="grid gap-5 md:grid-cols-2 2xl:grid-cols-4">
            @foreach ($classes as $class)
                <article class="admin-card p-6 transition duration-300 hover:-translate-y-1">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-950">{{ $class['name'] }}</h2>
                            <p class="mt-1 text-sm text-slate-500">{{ $class['level'] }} · {{ $class['subject'] }}</p>
                        </div>
                        <span class="admin-pill is-neutral">{{ $class['students'] }} élèves</span>
                    </div>

                    <p class="mt-5 text-sm text-slate-600"><span class="font-medium text-slate-800">Prochaine séance:</span> {{ $class['next'] }}</p>

                    <div class="mt-6">
                        <div class="mb-2 flex items-center justify-between text-sm">
                            <span class="font-medium text-slate-700">Progression programme</span>
                            <span class="text-slate-500">{{ $class['progress'] }}%</span>
                        </div>
                        <div class="h-3 rounded-full bg-slate-100">
                            <div class="h-3 rounded-full bg-gradient-to-r from-[#0A4FAF] to-[#18A558]" style="width: {{ $class['progress'] }}%"></div>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-wrap gap-2">
                        <x-button href="#" variant="secondary" size="sm">Voir élèves</x-button>
                        <x-button href="#" size="sm">Notes</x-button>
                        <x-button href="#" variant="ghost" size="sm">Statistiques</x-button>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@endsection
