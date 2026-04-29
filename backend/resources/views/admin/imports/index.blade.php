@extends('layouts.school-admin')

@section('title', 'Imports Excel')

@section('content')
    <section class="space-y-6 lg:space-y-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0A4FAF]">Moteur d’intégration</p>
                <h1 class="mt-2 text-3xl font-semibold text-slate-950 sm:text-4xl">Imports Excel</h1>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Chargez vos fichiers, sécurisez les correspondances et suivez les traitements avec une interface pensée pour les opérations répétées.</p>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.15fr_0.85fr]">
            <section class="admin-card p-6 sm:p-7" x-data="{ fileName: '', type: 'students' }">
                <form method="POST" action="{{ route('admin.imports.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <label for="import-file" class="block cursor-pointer rounded-[1.75rem] border-2 border-dashed border-[#0A4FAF]/18 bg-slate-50 px-6 py-10 text-center transition hover:border-[#0A4FAF]/45">
                        <span class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-white text-[#0A4FAF] shadow-sm">
                            <x-traiqi-icon name="upload" class="h-7 w-7" />
                        </span>
                        <span class="mt-5 block text-2xl font-semibold text-slate-950">Choisissez votre fichier Excel</span>
                        <span class="mx-auto mt-3 block max-w-xl text-sm leading-7 text-slate-600">Formats acceptés: CSV, TXT ou XLSX. La première ligne doit contenir les colonnes attendues.</span>
                        <span class="mt-5 inline-flex rounded-full bg-[#083B82] px-5 py-3 text-sm font-semibold text-white shadow-sm">Ouvrir l’explorateur</span>
                        <span class="mt-3 block text-sm font-semibold text-[#0A4FAF]" x-text="fileName || 'Aucun fichier sélectionné'"></span>
                    </label>

                    <input id="import-file" name="file" type="file" accept=".csv,.txt,.xlsx" class="sr-only" required @change="fileName = $event.target.files[0]?.name || ''">
                    @error('file')
                        <p class="text-sm font-semibold text-red-600">{{ $message }}</p>
                    @enderror

                    <div class="grid gap-4 md:grid-cols-3">
                        <label class="block">
                            <span class="mb-2 block text-sm font-semibold text-slate-700">Type d’import</span>
                            <select name="type" class="admin-toolbar-input" x-model="type" required>
                                <option value="students">Élèves</option>
                                <option value="parents">Parents</option>
                                <option value="teachers">Enseignants</option>
                            </select>
                        </label>

                        <label class="block">
                            <span class="mb-2 block text-sm font-semibold text-slate-700">École</span>
                            <select name="school_id" class="admin-toolbar-input">
                                @foreach($schools as $school)
                                    <option value="{{ $school->id }}">{{ $school->name }}</option>
                                @endforeach
                            </select>
                        </label>

                        <label class="block">
                            <span class="mb-2 block text-sm font-semibold text-slate-700">Max élèves / classe</span>
                            <input name="max_students_per_class" type="number" min="1" max="60" value="25" class="admin-toolbar-input">
                        </label>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="font-semibold text-slate-950">Colonnes attendues</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach($expectedColumns as $type => $columns)
                                <template x-if="type === '{{ $type }}'">
                                    <div class="contents">
                                        @foreach($columns as $column)
                                            <span class="admin-pill is-neutral">{{ $column }}</span>
                                        @endforeach
                                    </div>
                                </template>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="w-full rounded-2xl bg-gradient-to-r from-[#0A4FAF] to-[#18A558] px-5 py-3 text-sm font-semibold text-white shadow-sm">
                        Lancer l’import
                    </button>
                </form>
            </section>

            <section class="admin-card p-6 sm:p-7">
                <h2 class="text-xl font-semibold text-slate-950">Moteur intelligent</h2>
                <div class="mt-6 space-y-4">
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="font-semibold text-slate-950">Détection doublons</p>
                        <p class="mt-2 text-sm leading-7 text-slate-600">Repère les emails, codes et liaisons déjà présents pour éviter les collisions silencieuses.</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="font-semibold text-slate-950">Création comptes auto</p>
                        <p class="mt-2 text-sm leading-7 text-slate-600">Prépare les comptes avec données minimales et structure propre pour accélérer l’onboarding.</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="font-semibold text-slate-950">Affectation classes auto</p>
                        <p class="mt-2 text-sm leading-7 text-slate-600">Assigne les utilisateurs aux classes et cycles selon les règles de gestion définies.</p>
                    </div>
                </div>
            </section>
        </div>

        <section class="admin-table-wrap">
            <div class="flex items-center justify-between gap-4 border-b border-slate-200 px-6 py-5">
                <div>
                    <h2 class="text-xl font-semibold text-slate-950">Historique des imports</h2>
                    <p class="mt-1 text-sm text-slate-500">Derniers traitements exécutés avec leur statut détaillé.</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="admin-table min-w-[900px]">
                    <thead>
                        <tr>
                            <th>Fichier</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th>Lignes importées</th>
                            <th>Erreurs</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($imports as $item)
                            <tr class="transition hover:bg-slate-50/80">
                                <td class="font-semibold text-slate-950">{{ $item->file_name }}</td>
                                <td>{{ $item->type }}</td>
                                <td>{{ $item->created_at?->format('d/m/Y H:i') }}</td>
                                <td><span class="admin-pill {{ $item->status === 'completed' ? 'is-success' : 'is-warning' }}">{{ $item->status }}</span></td>
                                <td>{{ $item->processed_rows }}</td>
                                <td>{{ count($item->summary['errors'] ?? []) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-sm text-slate-500">Aucun import exécuté pour le moment.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($imports->hasPages())
                <div class="border-t border-slate-100 px-4 py-4">
                    {{ $imports->links() }}
                </div>
            @endif
        </section>
    </section>
@endsection
