@extends('layouts.parent')

@section('title', 'Mes enfants')

@section('content')
    @php
        $children = [
            ['name' => 'Imane Tazi', 'class' => '1ère Bac Sciences A', 'age' => '16 ans', 'avg' => '16.2', 'attendance' => '97%', 'last_grade' => '17/20', 'progress' => 'En hausse'],
            ['name' => 'Youssef Tazi', 'class' => '2AC-B', 'age' => '13 ans', 'avg' => '13.1', 'attendance' => '91%', 'last_grade' => '12/20', 'progress' => 'Stable'],
            ['name' => 'Salma Tazi', 'class' => '6ème Primaire A', 'age' => '11 ans', 'avg' => '15.4', 'attendance' => '98%', 'last_grade' => '16/20', 'progress' => 'Excellent'],
        ];
    @endphp

    <section class="space-y-6 lg:space-y-8">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0A4FAF]">Suivi individuel</p>
            <h1 class="mt-2 text-3xl font-semibold text-slate-950 sm:text-4xl">Mes enfants</h1>
            <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Retrouvez pour chaque enfant l’essentiel: classe, moyenne actuelle, présence, dernière note et évolution récente.</p>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-2">
                @foreach ($children as $child)
                    <article class="admin-card p-6">
                        <div class="flex items-start gap-4">
                            <span class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-[#0A4FAF]/10 text-lg font-semibold text-[#0A4FAF]">
                                {{ \Illuminate\Support\Str::of($child['name'])->explode(' ')->map(fn ($part) => mb_substr($part, 0, 1))->take(2)->implode('') }}
                            </span>
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center justify-between gap-3">
                                    <h2 class="text-lg font-semibold text-slate-950">{{ $child['name'] }}</h2>
                                    <span class="admin-pill {{ $child['progress'] === 'Excellent' ? 'is-success' : ($child['progress'] === 'En hausse' ? 'is-neutral' : 'is-warning') }}">{{ $child['progress'] }}</span>
                                </div>
                                <p class="mt-1 text-sm text-slate-500">{{ $child['class'] }} · {{ $child['age'] }}</p>
                            </div>
                        </div>

                        <div class="mt-6 grid gap-4 sm:grid-cols-3">
                            <div class="rounded-2xl bg-slate-50 p-4">
                                <p class="text-sm text-slate-500">Moyenne</p>
                                <p class="mt-2 text-xl font-semibold text-slate-950">{{ $child['avg'] }}/20</p>
                            </div>
                            <div class="rounded-2xl bg-slate-50 p-4">
                                <p class="text-sm text-slate-500">Présence</p>
                                <p class="mt-2 text-xl font-semibold text-slate-950">{{ $child['attendance'] }}</p>
                            </div>
                            <div class="rounded-2xl bg-slate-50 p-4">
                                <p class="text-sm text-slate-500">Dernière note</p>
                                <p class="mt-2 text-xl font-semibold text-slate-950">{{ $child['last_grade'] }}</p>
                            </div>
                        </div>

                        <div class="mt-6 flex flex-wrap gap-2">
                            <x-button href="#" size="sm">Voir notes</x-button>
                            <x-button href="#" variant="secondary" size="sm">Contacter enseignant</x-button>
                            <x-button href="#" variant="ghost" size="sm">Historique</x-button>
                        </div>
                    </article>
                @endforeach
            </div>

            <aside class="space-y-6">
                <section class="admin-card p-6 sm:p-7">
                    <h2 class="text-xl font-semibold text-slate-950">Résumé famille</h2>
                    <div class="mt-6 space-y-4">
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-sm text-slate-500">Total enfants</p>
                            <p class="mt-2 text-3xl font-semibold text-slate-950">3</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-sm text-slate-500">Performance globale famille</p>
                            <p class="mt-2 text-3xl font-semibold text-slate-950">14.9/20</p>
                        </div>
                    </div>
                </section>
            </aside>
        </div>
    </section>
@endsection
