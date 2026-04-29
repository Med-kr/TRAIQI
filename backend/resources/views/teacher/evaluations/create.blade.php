@extends('layouts.teacher')

@section('title', 'Créer évaluation')

@section('content')
    <section class="space-y-6 lg:space-y-8">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0A4FAF]">Conception d’évaluation</p>
            <h1 class="mt-2 text-3xl font-semibold text-slate-950 sm:text-4xl">Créer évaluation</h1>
            <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Préparez une évaluation claire, cohérente et prête à publier avec les paramètres utiles au suivi pédagogique.</p>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
            <section class="admin-card p-6 sm:p-7">
                <form method="POST" action="{{ Route::has('teacher.evaluations.store') ? route('teacher.evaluations.store') : '#' }}" class="grid gap-5">
                    @csrf

                    <x-input name="title" label="Titre évaluation" placeholder="Devoir surveillé - Chapitre 4" />

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-900">Type</label>
                            <select name="type" class="admin-toolbar-input">
                                <option>devoir</option>
                                <option>examen</option>
                                <option>contrôle continu</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-900">Classe</label>
                            <select name="classroom_id" class="admin-toolbar-input">
                                <option>1ère Bac Sciences A</option>
                                <option>2AC-B</option>
                                <option>Terminale PC</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-900">Matière</label>
                            <select name="subject_id" class="admin-toolbar-input">
                                <option>Mathématiques</option>
                                <option>Sciences</option>
                                <option>Informatique</option>
                            </select>
                        </div>
                        <x-input name="date" type="date" label="Date" />
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <x-input name="max_score" type="number" label="Note maximale (/20)" placeholder="20" />
                        <x-input name="coefficient" type="number" label="Coefficient" placeholder="2" />
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-900">Description</label>
                        <textarea name="description" rows="6" class="admin-toolbar-input" placeholder="Objectifs, consignes, périmètre du devoir..."></textarea>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <x-button type="submit">Publier</x-button>
                        <x-button type="submit" variant="secondary">Brouillon</x-button>
                        <x-button href="{{ route('teacher.dashboard') }}" variant="ghost">Annuler</x-button>
                    </div>
                </form>
            </section>

            <aside class="space-y-6">
                <section class="admin-card p-6 sm:p-7">
                    <h2 class="text-xl font-semibold text-slate-950">Résumé évaluation</h2>
                    <div class="mt-6 space-y-4 text-sm text-slate-600">
                        <p><span class="font-medium text-slate-800">Classe cible:</span> 1ère Bac Sciences A</p>
                        <p><span class="font-medium text-slate-800">Barème:</span> /20</p>
                        <p><span class="font-medium text-slate-800">Publication:</span> immédiate ou brouillon</p>
                    </div>
                </section>

                <section class="admin-card p-6 sm:p-7">
                    <h2 class="text-xl font-semibold text-slate-950">Conseils rapides</h2>
                    <div class="mt-6 space-y-4">
                        <div class="rounded-2xl bg-slate-50 p-4 text-sm leading-7 text-slate-600">Définissez un titre clair et une portée précise pour faciliter l’analyse ultérieure.</div>
                        <div class="rounded-2xl bg-slate-50 p-4 text-sm leading-7 text-slate-600">Conservez un coefficient cohérent avec le poids pédagogique de l’activité.</div>
                        <div class="rounded-2xl bg-slate-50 p-4 text-sm leading-7 text-slate-600">Préférez le brouillon si les critères de notation ne sont pas encore finalisés.</div>
                    </div>
                </section>
            </aside>
        </div>
    </section>
@endsection
