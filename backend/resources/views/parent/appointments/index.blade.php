@extends('layouts.parent')

@section('title', 'Rendez-vous')

@section('content')
    @php
        $upcoming = [
            ['date' => '02 mai 2026', 'time' => '16:00', 'with' => 'Prof. principal - Imane Tazi', 'status' => 'Confirmé'],
            ['date' => '08 mai 2026', 'time' => '10:30', 'with' => 'Administration', 'status' => 'En attente'],
        ];

        $past = [
            ['date' => '12 avr. 2026', 'time' => '15:30', 'with' => 'Prof. mathématiques', 'status' => 'Terminé'],
            ['date' => '22 mars 2026', 'time' => '11:00', 'with' => 'Conseiller pédagogique', 'status' => 'Terminé'],
        ];
    @endphp

    <section class="space-y-6 lg:space-y-8">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0A4FAF]">Coordination</p>
            <h1 class="mt-2 text-3xl font-semibold text-slate-950 sm:text-4xl">Rendez-vous</h1>
            <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Planifiez vos échanges avec l’école, consultez les rencontres à venir et gardez une trace claire des rendez-vous passés.</p>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.02fr_0.98fr]">
            <section class="admin-card p-6 sm:p-7">
                <h2 class="text-xl font-semibold text-slate-950">Demander un rendez-vous</h2>
                <form method="POST" action="#" class="mt-6 grid gap-5">
                    @csrf

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-900">Enfant</label>
                            <select class="admin-toolbar-input">
                                <option>Imane Tazi</option>
                                <option>Youssef Tazi</option>
                                <option>Salma Tazi</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-900">Professeur / administration</label>
                            <select class="admin-toolbar-input">
                                <option>Prof. principal</option>
                                <option>Administration</option>
                                <option>Prof. mathématiques</option>
                            </select>
                        </div>
                    </div>

                    <x-input name="subject" label="Sujet" placeholder="Point sur la progression du semestre" />
                    <x-input name="preferred_date" type="date" label="Date souhaitée" />

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-900">Message</label>
                        <textarea rows="5" class="admin-toolbar-input" placeholder="Précisez le contexte ou les points que vous souhaitez aborder."></textarea>
                    </div>

                    <div>
                        <x-button type="submit">Envoyer la demande</x-button>
                    </div>
                </form>
            </section>

            <div class="space-y-6">
                <section class="admin-card p-6 sm:p-7">
                    <div class="flex items-center justify-between gap-4">
                        <h2 class="text-xl font-semibold text-slate-950">À venir</h2>
                        <span class="admin-pill is-success">Planifié</span>
                    </div>
                    <div class="mt-6 space-y-4">
                        @foreach ($upcoming as $item)
                            <article class="rounded-2xl border border-slate-100 p-4">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <h3 class="font-semibold text-slate-950">{{ $item['with'] }}</h3>
                                        <p class="mt-2 text-sm text-slate-600">{{ $item['date'] }} · {{ $item['time'] }}</p>
                                    </div>
                                    <span class="admin-pill {{ $item['status'] === 'Confirmé' ? 'is-success' : 'is-warning' }}">{{ $item['status'] }}</span>
                                </div>
                                <div class="mt-4 flex flex-wrap gap-2">
                                    <x-button href="#" variant="secondary" size="sm">Replanifier</x-button>
                                    <x-button href="#" variant="ghost" size="sm">Annuler</x-button>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>

                <section class="admin-card p-6 sm:p-7">
                    <h2 class="text-xl font-semibold text-slate-950">Historique</h2>
                    <div class="mt-6 space-y-4">
                        @foreach ($past as $item)
                            <article class="rounded-2xl bg-slate-50 p-4">
                                <h3 class="font-semibold text-slate-950">{{ $item['with'] }}</h3>
                                <p class="mt-2 text-sm text-slate-600">{{ $item['date'] }} · {{ $item['time'] }}</p>
                                <p class="mt-2 text-sm text-slate-500">{{ $item['status'] }}</p>
                            </article>
                        @endforeach
                    </div>
                </section>
            </div>
        </div>
    </section>
@endsection
