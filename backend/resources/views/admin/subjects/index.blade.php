@extends('layouts.school-admin')

@section('title', 'Matières')

@section('content')
    @php
        $subjects = [
            ['name' => 'Mathématiques', 'teachers' => 8, 'classes' => 14, 'hours' => '6h / semaine', 'status' => 'Actif'],
            ['name' => 'Français', 'teachers' => 6, 'classes' => 12, 'hours' => '5h / semaine', 'status' => 'Actif'],
            ['name' => 'Arabe', 'teachers' => 5, 'classes' => 12, 'hours' => '5h / semaine', 'status' => 'Actif'],
            ['name' => 'Anglais', 'teachers' => 4, 'classes' => 11, 'hours' => '4h / semaine', 'status' => 'Révision'],
            ['name' => 'Sciences', 'teachers' => 7, 'classes' => 10, 'hours' => '4h / semaine', 'status' => 'Actif'],
            ['name' => 'Informatique', 'teachers' => 3, 'classes' => 8, 'hours' => '2h / semaine', 'status' => 'Actif'],
        ];
    @endphp

    <section class="space-y-6 lg:space-y-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0A4FAF]">Référentiel académique</p>
                <h1 class="mt-2 text-3xl font-semibold text-slate-950 sm:text-4xl">Matières</h1>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Visualisez rapidement les affectations, la charge horaire et la diffusion des matières sur les classes de l’établissement.</p>
            </div>

            <x-button :href="route('admin.subjects.create')">Ajouter matière</x-button>
        </div>

        <section class="admin-card p-5 sm:p-6">
            <div class="grid gap-4 lg:grid-cols-[1fr_0.8fr_auto]">
                <input type="search" class="admin-toolbar-input" placeholder="Rechercher une matière">
                <select class="admin-toolbar-input">
                    <option>Tous les cycles</option>
                    <option>Primaire</option>
                    <option>Collège</option>
                    <option>Lycée</option>
                </select>
                <x-button variant="secondary" class="w-full justify-center">Filtrer</x-button>
            </div>
        </section>

        <div class="grid gap-5 md:grid-cols-2 2xl:grid-cols-3">
            @foreach ($subjects as $subject)
                <article class="admin-card p-6 transition duration-300 hover:-translate-y-1">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h2 class="text-xl font-semibold text-slate-950">{{ $subject['name'] }}</h2>
                            <p class="mt-2 text-sm text-slate-500">{{ $subject['hours'] }}</p>
                        </div>
                        <span class="admin-pill {{ $subject['status'] === 'Actif' ? 'is-success' : 'is-warning' }}">{{ $subject['status'] }}</span>
                    </div>

                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-sm text-slate-500">Enseignants assignés</p>
                            <p class="mt-2 text-2xl font-semibold text-slate-950">{{ $subject['teachers'] }}</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-sm text-slate-500">Classes liées</p>
                            <p class="mt-2 text-2xl font-semibold text-slate-950">{{ $subject['classes'] }}</p>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-wrap gap-2">
                        <x-button href="#" variant="secondary" size="sm">Voir</x-button>
                        <x-button :href="route('admin.subjects.edit', 1)" size="sm">Modifier</x-button>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@endsection
