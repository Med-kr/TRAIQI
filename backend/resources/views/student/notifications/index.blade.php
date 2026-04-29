@extends('layouts.student')

@section('title', 'Notifications')

@section('content')
    @php
        $items = [
            ['type' => 'Notes', 'title' => 'Nouvelle note ajoutée', 'desc' => 'Mathématiques · Contrôle continu publié avec une note de 17/20.', 'time' => 'Il y a 12 min', 'read' => false],
            ['type' => 'Messages', 'title' => 'Message enseignant', 'desc' => 'Un retour a été ajouté sur ton devoir de français.', 'time' => 'Aujourd’hui, 09:10', 'read' => false],
            ['type' => 'Système', 'title' => 'Changement emploi du temps', 'desc' => 'Le cours de sciences de jeudi passe en salle Labo 2.', 'time' => 'Hier', 'read' => true],
            ['type' => 'Rendez-vous', 'title' => 'Examen à venir', 'desc' => 'Un examen d’anglais est programmé mardi prochain.', 'time' => 'Hier', 'read' => true],
        ];
    @endphp

    <section class="space-y-6 lg:space-y-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0A4FAF]">Centre d’alertes</p>
                <h1 class="mt-2 text-3xl font-semibold text-slate-950 sm:text-4xl">Notifications</h1>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Retrouve les nouvelles notes, les messages importants et les changements de planning dans un fil clair et hiérarchisé.</p>
            </div>

            <x-button href="#" variant="secondary">Tout marquer comme lu</x-button>
        </div>

        <section class="admin-card p-5 sm:p-6">
            <div class="flex flex-wrap gap-2">
                @foreach (['Toutes', 'Notes', 'Messages', 'Système', 'Rendez-vous'] as $index => $filter)
                    <button type="button" class="rounded-2xl px-4 py-2 text-sm font-semibold {{ $index === 0 ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600' }}">
                        {{ $filter }}
                    </button>
                @endforeach
            </div>
        </section>

        <div class="space-y-4">
            @foreach ($items as $item)
                <article class="admin-card p-5 sm:p-6 transition hover:-translate-y-0.5">
                    <div class="flex items-start gap-4">
                        <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl {{ $item['read'] ? 'bg-slate-100 text-slate-500' : 'bg-[#0A4FAF]/10 text-[#0A4FAF]' }}">
                            <x-traiqi-icon name="bell" class="h-5 w-5" />
                        </span>

                        <div class="min-w-0 flex-1">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-[#0A4FAF]">{{ $item['type'] }}</p>
                                    <h2 class="mt-1 text-lg font-semibold text-slate-950">{{ $item['title'] }}</h2>
                                </div>
                                <div class="flex items-center gap-3">
                                    @unless ($item['read'])
                                        <span class="admin-pill is-success">Nouveau</span>
                                    @endunless
                                    <span class="text-xs font-medium text-slate-400">{{ $item['time'] }}</span>
                                </div>
                            </div>
                            <p class="mt-3 text-sm leading-7 text-slate-600">{{ $item['desc'] }}</p>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@endsection
