@extends('layouts.parent')

@section('title', 'Messages')

@section('content')
    <section class="space-y-6 lg:space-y-8">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0A4FAF]">Communication école-famille</p>
            <h1 class="mt-2 text-3xl font-semibold text-slate-950 sm:text-4xl">Messages</h1>
            <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Retrouvez vos échanges avec l’administration, les enseignants et le support dans une messagerie simple et rassurante.</p>
        </div>

        <div class="grid gap-6 xl:grid-cols-[0.38fr_0.62fr]">
            <section class="admin-card p-5 sm:p-6">
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm text-slate-500">Messages non lus</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-950">{{ $unreadNotificationsCount }}</p>
                </div>

                <div class="mt-5 space-y-3">
                    @forelse ($notifications as $item)
                        <article class="rounded-2xl border p-4 transition {{ ! $item->read ? 'border-[#0A4FAF]/20 bg-[#0A4FAF]/5' : 'border-slate-100 hover:bg-slate-50' }}">
                            <div class="flex items-center justify-between gap-3">
                                <h2 class="font-semibold text-slate-950">{{ $item->title }}</h2>
                                @if (! $item->read)
                                    <span class="admin-pill is-success">Nouveau</span>
                                @endif
                            </div>
                            <p class="mt-2 text-sm text-slate-600">{{ \Illuminate\Support\Str::limit($item->body, 90) }}</p>
                        </article>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-5 text-sm text-slate-500">Aucun message reçu.</div>
                    @endforelse
                </div>
            </section>

            <section class="admin-card flex min-h-[36rem] flex-col p-5 sm:p-6">
                <div class="border-b border-slate-200 pb-4">
                    <h2 class="text-xl font-semibold text-slate-950">Fil de notifications</h2>
                    <p class="mt-1 text-sm text-slate-500">Messages envoyés par l'école et les enseignants.</p>
                </div>

                <div class="flex-1 space-y-4 overflow-y-auto py-5">
                    @forelse ($notifications as $message)
                        <div class="flex justify-start">
                            <div class="max-w-[85%] rounded-2xl px-4 py-3 text-sm leading-7 {{ ! $message->read ? 'bg-[#0A4FAF]/8 text-slate-800' : 'bg-slate-50 text-slate-700' }}">
                                <p class="mb-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">{{ $message->created_at?->format('Y-m-d H:i') }}</p>
                                <p class="font-semibold text-slate-950">{{ $message->title }}</p>
                                <p class="mt-1">{{ $message->body }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-5 text-sm text-slate-500">Aucun message à afficher.</div>
                    @endforelse
                </div>

                <div class="border-t border-slate-200 pt-4">
                    <x-button href="{{ route('notifications.index') }}" variant="secondary">Ouvrir toutes les notifications</x-button>
                </div>
            </section>
        </div>
    </section>
@endsection
