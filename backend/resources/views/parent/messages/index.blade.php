@extends('layouts.parent')

@section('title', 'Messages')

@section('content')
    @php
        $conversations = [
            ['name' => 'Administration', 'excerpt' => 'Mise à jour sur la réunion de vendredi', 'unread' => 2, 'active' => true],
            ['name' => 'Prof. principal', 'excerpt' => 'Retour sur la progression d’Imane', 'unread' => 1, 'active' => false],
            ['name' => 'Support', 'excerpt' => 'Votre demande a bien été reçue', 'unread' => 0, 'active' => false],
        ];

        $thread = [
            ['from' => 'Administration', 'body' => 'Bonjour, nous vous confirmons la réunion d’orientation prévue vendredi à 16h00.', 'self' => false],
            ['from' => 'Vous', 'body' => 'Merci. Je serai présente avec le parent légal.', 'self' => true],
            ['from' => 'Administration', 'body' => 'Parfait. Un rappel vous sera envoyé la veille.', 'self' => false],
        ];
    @endphp

    <section class="space-y-6 lg:space-y-8">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0A4FAF]">Communication école-famille</p>
            <h1 class="mt-2 text-3xl font-semibold text-slate-950 sm:text-4xl">Messages</h1>
            <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Retrouvez vos échanges avec l’administration, les enseignants et le support dans une messagerie simple et rassurante.</p>
        </div>

        <div class="grid gap-6 xl:grid-cols-[0.38fr_0.62fr]">
            <section class="admin-card p-5 sm:p-6">
                <input type="search" class="admin-toolbar-input" placeholder="Rechercher un message">

                <div class="mt-5 space-y-3">
                    @foreach ($conversations as $item)
                        <article class="rounded-2xl border p-4 transition {{ $item['active'] ? 'border-[#0A4FAF]/20 bg-[#0A4FAF]/5' : 'border-slate-100 hover:bg-slate-50' }}">
                            <div class="flex items-center justify-between gap-3">
                                <h2 class="font-semibold text-slate-950">{{ $item['name'] }}</h2>
                                @if ($item['unread'] > 0)
                                    <span class="admin-pill is-success">{{ $item['unread'] }}</span>
                                @endif
                            </div>
                            <p class="mt-2 text-sm text-slate-600">{{ $item['excerpt'] }}</p>
                        </article>
                    @endforeach
                </div>
            </section>

            <section class="admin-card flex min-h-[36rem] flex-col p-5 sm:p-6">
                <div class="border-b border-slate-200 pb-4">
                    <h2 class="text-xl font-semibold text-slate-950">Administration</h2>
                    <p class="mt-1 text-sm text-slate-500">Conversation active</p>
                </div>

                <div class="flex-1 space-y-4 overflow-y-auto py-5">
                    @foreach ($thread as $message)
                        <div class="flex {{ $message['self'] ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[85%] rounded-2xl px-4 py-3 text-sm leading-7 {{ $message['self'] ? 'bg-slate-900 text-white' : 'bg-slate-50 text-slate-700' }}">
                                <p class="mb-1 text-xs font-semibold uppercase tracking-[0.18em] {{ $message['self'] ? 'text-white/70' : 'text-slate-400' }}">{{ $message['from'] }}</p>
                                <p>{{ $message['body'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-slate-200 pt-4">
                    <div class="mb-3 flex flex-wrap gap-2">
                        <button type="button" class="rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-600">Joindre un fichier</button>
                    </div>
                    <form method="POST" action="#" class="flex flex-col gap-3 sm:flex-row">
                        @csrf
                        <input type="text" class="admin-toolbar-input flex-1" placeholder="Écrire un message...">
                        <x-button type="submit" class="justify-center">Envoyer</x-button>
                    </form>
                </div>
            </section>
        </div>
    </section>
@endsection
