@extends('layouts.school-admin')

@section('title', 'Rapports')

@section('content')
    @php
        $reportCards = [
            'Rapport élèves',
            'Rapport notes',
            'Rapport présence',
            'Rapport enseignants',
            'Rapport financier',
        ];
    @endphp

    <section class="space-y-6 lg:space-y-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0A4FAF]">Décision & analyse</p>
                <h1 class="mt-2 text-3xl font-semibold text-slate-950 sm:text-4xl">Rapports</h1>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Produisez rapidement des synthèses fiables sur les effectifs, les résultats, la présence et la dynamique pédagogique de l’établissement.</p>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
            @foreach ($reportCards as $card)
                <article class="admin-card p-5 transition duration-300 hover:-translate-y-1">
                    <h2 class="text-base font-semibold text-slate-950">{{ $card }}</h2>
                    <p class="mt-2 text-sm leading-7 text-slate-600">Vue synthétique exportable pour comité, direction ou suivi opérationnel.</p>
                </article>
            @endforeach
        </div>

        <section class="admin-card p-5 sm:p-6">
            <div class="grid gap-4 xl:grid-cols-[1fr_1fr_1fr_auto_auto_auto]">
                <input type="text" class="admin-toolbar-input" placeholder="Plage de dates">
                <select class="admin-toolbar-input">
                    <option>Toutes les classes</option>
                    <option>1ère Bac Sciences A</option>
                    <option>2AC-B</option>
                </select>
                <select class="admin-toolbar-input">
                    <option>Tous les niveaux</option>
                    <option>Primaire</option>
                    <option>Collège</option>
                    <option>Lycée</option>
                </select>
                <x-button variant="secondary" class="w-full justify-center">PDF</x-button>
                <x-button variant="dark" class="w-full justify-center">Excel</x-button>
                <x-button class="w-full justify-center">CSV</x-button>
            </div>
        </section>

        <div class="grid gap-6 xl:grid-cols-3">
            <section class="admin-card p-6 sm:p-7 xl:col-span-2">
                <h2 class="text-xl font-semibold text-slate-950">Vue comparative</h2>
                <div class="mt-8 flex h-72 items-end gap-3 rounded-[1.5rem] bg-slate-50 p-5">
                    @foreach ([62, 74, 58, 81, 79, 88, 92, 84] as $point)
                        <div class="flex flex-1 items-end">
                            <div class="w-full rounded-t-2xl bg-gradient-to-t from-[#083B82] via-[#0A4FAF] to-[#18A558]" style="height: {{ $point }}%"></div>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="admin-card p-6 sm:p-7">
                <h2 class="text-xl font-semibold text-slate-950">Indicateurs clés</h2>
                <div class="mt-6 space-y-4">
                    @foreach ([['label' => 'Moyenne générale', 'value' => '14.8/20'], ['label' => 'Présence', 'value' => '93%'], ['label' => 'Décrochage à surveiller', 'value' => '17 cas']] as $item)
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-sm text-slate-500">{{ $item['label'] }}</p>
                            <p class="mt-2 text-2xl font-semibold text-slate-950">{{ $item['value'] }}</p>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    </section>
@endsection
