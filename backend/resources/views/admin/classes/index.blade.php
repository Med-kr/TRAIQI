@extends('layouts.school-admin')

@section('title', 'Classes')

@section('content')
    @php
        $classes = [
            ['name' => '1ère Bac Sciences A', 'level' => 'Lycée', 'students' => 34, 'teacher' => 'Nadia Chraibi', 'room' => 'Salle B12', 'progress' => 76],
            ['name' => '2AC-B', 'level' => 'Collège', 'students' => 31, 'teacher' => 'Karim Alaoui', 'room' => 'Salle C04', 'progress' => 69],
            ['name' => 'Terminale Lettres', 'level' => 'Lycée', 'students' => 27, 'teacher' => 'Sara El Mokri', 'room' => 'Salle A07', 'progress' => 82],
            ['name' => '6ème Primaire A', 'level' => 'Primaire', 'students' => 29, 'teacher' => 'Meryem El Fassi', 'room' => 'Salle P03', 'progress' => 73],
        ];
    @endphp

    <section class="space-y-6 lg:space-y-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0A4FAF]">Organisation pédagogique</p>
                <h1 class="mt-2 text-3xl font-semibold text-slate-950 sm:text-4xl">Classes</h1>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Suivez les groupes, la capacité, les professeurs principaux et la progression annuelle depuis une lecture rapide et exploitable.</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <x-button :href="route('admin.classrooms.index')" variant="secondary">Ajouter classe</x-button>
                <x-button href="#" variant="dark">Générer automatiquement</x-button>
            </div>
        </div>

        <section class="admin-card p-5 sm:p-6">
            <input type="search" class="admin-toolbar-input" placeholder="Rechercher une classe">
        </section>

        <div class="grid gap-5 md:grid-cols-2 2xl:grid-cols-4">
            @foreach ($classes as $class)
                <article class="admin-card p-6 transition duration-300 hover:-translate-y-1">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-950">{{ $class['name'] }}</h2>
                            <p class="mt-1 text-sm text-slate-500">{{ $class['level'] }}</p>
                        </div>
                        <span class="admin-pill is-neutral">{{ $class['students'] }} élèves</span>
                    </div>

                    <div class="mt-6 space-y-3 text-sm text-slate-600">
                        <p><span class="font-medium text-slate-800">Prof principal:</span> {{ $class['teacher'] }}</p>
                        <p><span class="font-medium text-slate-800">Salle:</span> {{ $class['room'] }}</p>
                    </div>

                    <div class="mt-6">
                        <div class="mb-2 flex items-center justify-between text-sm">
                            <span class="font-medium text-slate-700">Progression année</span>
                            <span class="text-slate-500">{{ $class['progress'] }}%</span>
                        </div>
                        <div class="h-3 rounded-full bg-slate-100">
                            <div class="h-3 rounded-full bg-gradient-to-r from-[#0A4FAF] to-[#18A558]" style="width: {{ $class['progress'] }}%"></div>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-wrap gap-2">
                        <x-button href="#" variant="secondary" size="sm">Planning</x-button>
                        <x-button href="#" variant="ghost" size="sm">Liste élèves</x-button>
                        <x-button href="#" size="sm">Modifier</x-button>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@endsection
