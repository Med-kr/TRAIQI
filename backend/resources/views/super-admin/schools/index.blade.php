@extends('layouts.admin')

@section('title', 'Gestion des ecoles')

@section('content')
    @php
        $schools = [
            ['logo' => 'A', 'name' => 'Lycée Atlas Excellence', 'city' => 'Rabat', 'email' => 'contact@atlas.ma', 'phone' => '+212 5 37 00 11 22', 'students' => '1 280', 'status' => 'Actif'],
            ['logo' => 'Q', 'name' => 'Ecole Al Qods', 'city' => 'Marrakech', 'email' => 'contact@alqods.ma', 'phone' => '+212 5 24 14 10 30', 'students' => '940', 'status' => 'Audit'],
            ['logo' => 'A', 'name' => 'Groupe Scolaire Al Amal', 'city' => 'Casablanca', 'email' => 'direction@alamal.ma', 'phone' => '+212 5 22 55 13 07', 'students' => '2 340', 'status' => 'Actif'],
            ['logo' => 'N', 'name' => 'Institut Nour', 'city' => 'Tanger', 'email' => 'info@nour.ma', 'phone' => '+212 5 39 22 08 19', 'students' => '0', 'status' => 'En attente'],
        ];
    @endphp

    <section class="space-y-6 lg:space-y-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0A4FAF]">Réseau établissements</p>
                <h1 class="mt-2 text-3xl font-semibold text-slate-950 sm:text-4xl">Gestion des écoles</h1>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Un espace de pilotage clair pour surveiller l’activation, la capacité et la qualité des établissements connectés à Traiqi.</p>
            </div>

            <x-button :href="route('admin.schools.create')">Ajouter une ecole</x-button>
        </div>

        <section class="admin-card p-5 sm:p-6">
            <div class="grid gap-4 lg:grid-cols-[1.2fr_0.7fr_0.7fr_auto]">
                <input type="search" class="admin-toolbar-input" placeholder="Rechercher une ecole">
                <select class="admin-toolbar-input">
                    <option>Toutes les villes</option>
                    <option>Rabat</option>
                    <option>Casablanca</option>
                    <option>Marrakech</option>
                </select>
                <select class="admin-toolbar-input">
                    <option>Tous les types</option>
                    <option>Public</option>
                    <option>Privé</option>
                    <option>Partenaire</option>
                </select>
                <div class="flex gap-3">
                    <x-button variant="secondary" class="w-full justify-center">Filtrer</x-button>
                </div>
            </div>
        </section>

        <section class="admin-table-wrap">
            <div class="overflow-x-auto">
                <table class="admin-table min-w-[980px]">
                    <thead>
                        <tr>
                            <th>Logo</th>
                            <th>Nom</th>
                            <th>Ville</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Nombre élèves</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($schools as $school)
                            <tr class="transition hover:bg-slate-50/80">
                                <td>
                                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-[#0A4FAF] to-[#18A558] text-sm font-semibold text-white">
                                        {{ $school['logo'] }}
                                    </span>
                                </td>
                                <td class="font-semibold text-slate-950">{{ $school['name'] }}</td>
                                <td>{{ $school['city'] }}</td>
                                <td>{{ $school['email'] }}</td>
                                <td>{{ $school['phone'] }}</td>
                                <td>{{ $school['students'] }}</td>
                                <td>
                                    <span class="admin-pill {{ $school['status'] === 'Actif' ? 'is-success' : ($school['status'] === 'Audit' ? 'is-warning' : 'is-neutral') }}">
                                        {{ $school['status'] }}
                                    </span>
                                </td>
                                <td>
                                    <div class="flex justify-end gap-2">
                                        <x-button href="#" variant="ghost" size="sm">Voir</x-button>
                                        <x-button :href="route('admin.schools.edit', 1)" variant="secondary" size="sm">Modifier</x-button>
                                        <x-button href="#" variant="dark" size="sm">Suspendre</x-button>
                                        <form method="POST" action="{{ route('admin.schools.destroy', 1) }}">
                                            @csrf
                                            @method('DELETE')
                                            <x-button type="submit" variant="ghost" size="sm" class="text-red-600 hover:bg-red-50 hover:text-red-700">Supprimer</x-button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <div class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
            <section class="admin-card p-6">
                <div class="flex items-center justify-between gap-4">
                    <h2 class="text-lg font-semibold text-slate-950">Pagination</h2>
                    <p class="text-sm text-slate-500">Affichage 1 à 4 sur 128 établissements</p>
                </div>

                <div class="mt-6 flex flex-wrap items-center gap-3">
                    <x-button href="#" variant="secondary" size="sm">Précédent</x-button>
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-900 text-sm font-semibold text-white">1</span>
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-200 bg-white text-sm font-semibold text-slate-700">2</span>
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-200 bg-white text-sm font-semibold text-slate-700">3</span>
                    <x-button href="#" variant="secondary" size="sm">Suivant</x-button>
                </div>
            </section>

            <section class="admin-card p-6">
                <div class="flex h-full flex-col items-start justify-center rounded-[1.5rem] border border-dashed border-slate-200 bg-slate-50 px-6 py-8 text-left">
                    <span class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-[#0A4FAF] shadow-sm">
                        <x-traiqi-icon name="book" class="h-6 w-6" />
                    </span>
                    <h2 class="mt-5 text-xl font-semibold text-slate-950">État vide élégant</h2>
                    <p class="mt-3 max-w-md text-sm leading-7 text-slate-600">Quand aucun résultat ne correspond aux filtres, affichez ici un message utile avec une action claire pour réinitialiser la recherche ou créer une nouvelle école.</p>
                    <div class="mt-5">
                        <x-button :href="route('admin.schools.create')" size="sm">Créer une école</x-button>
                    </div>
                </div>
            </section>
        </div>
    </section>
@endsection
