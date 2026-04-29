@extends('layouts.school-admin')

@section('title', 'Logs activité')

@section('content')
    @php
        $logs = [
            ['date' => '27 avr. 2026 10:28', 'user' => 'Nadia Chraibi', 'action' => 'Création classe', 'module' => 'Classes', 'ip' => '196.200.120.8', 'status' => 'Succès'],
            ['date' => '27 avr. 2026 09:54', 'user' => 'Hicham Lamrani', 'action' => 'Import élèves', 'module' => 'Imports', 'ip' => '196.200.120.10', 'status' => 'Succès'],
            ['date' => '27 avr. 2026 09:12', 'user' => 'Sara B.', 'action' => 'Connexion refusée', 'module' => 'Auth', 'ip' => '105.71.44.30', 'status' => 'Erreur'],
            ['date' => '26 avr. 2026 17:40', 'user' => 'Meryem El Fassi', 'action' => 'Modification profil', 'module' => 'Utilisateurs', 'ip' => '196.200.121.4', 'status' => 'Succès'],
        ];
    @endphp

    <section class="space-y-6 lg:space-y-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0A4FAF]">Traçabilité</p>
                <h1 class="mt-2 text-3xl font-semibold text-slate-950 sm:text-4xl">Logs activité</h1>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Suivez les actions, imports, accès et incidents récents grâce à une vue structurée pour l’audit quotidien.</p>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.12fr_0.88fr]">
            <div class="space-y-6">
                <section class="admin-card p-5 sm:p-6">
                    <div class="grid gap-4 lg:grid-cols-3">
                        <input type="search" class="admin-toolbar-input" placeholder="Utilisateur">
                        <select class="admin-toolbar-input">
                            <option>Toutes les actions</option>
                            <option>Connexion</option>
                            <option>Import</option>
                            <option>Modification</option>
                        </select>
                        <input type="text" class="admin-toolbar-input" placeholder="Date">
                    </div>
                </section>

                <section class="admin-table-wrap">
                    <div class="overflow-x-auto">
                        <table class="admin-table min-w-[920px]">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Utilisateur</th>
                                    <th>Action</th>
                                    <th>Module</th>
                                    <th>IP</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($logs as $log)
                                    <tr class="transition hover:bg-slate-50/80">
                                        <td>{{ $log['date'] }}</td>
                                        <td class="font-semibold text-slate-950">{{ $log['user'] }}</td>
                                        <td>{{ $log['action'] }}</td>
                                        <td>{{ $log['module'] }}</td>
                                        <td>{{ $log['ip'] }}</td>
                                        <td><span class="admin-pill {{ $log['status'] === 'Succès' ? 'is-success' : 'is-warning' }}">{{ $log['status'] }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <aside class="space-y-6">
                <section class="admin-card p-6 sm:p-7">
                    <h2 class="text-xl font-semibold text-slate-950">Résumé</h2>
                    <div class="mt-6 grid gap-4">
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-sm text-slate-500">Connexions today</p>
                            <p class="mt-2 text-3xl font-semibold text-slate-950">248</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-sm text-slate-500">Imports today</p>
                            <p class="mt-2 text-3xl font-semibold text-slate-950">5</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-sm text-slate-500">Errors</p>
                            <p class="mt-2 text-3xl font-semibold text-slate-950">3</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-sm text-slate-500">Actions sensibles</p>
                            <p class="mt-2 text-3xl font-semibold text-slate-950">18</p>
                        </div>
                    </div>
                </section>

                <section class="admin-card p-6 sm:p-7">
                    <h2 class="text-xl font-semibold text-slate-950">Loading skeleton</h2>
                    <div class="mt-6 space-y-4">
                        @foreach (range(1, 3) as $row)
                            <div class="flex items-center gap-4 rounded-2xl border border-slate-100 p-4">
                                <div class="h-12 w-12 animate-pulse rounded-2xl bg-slate-100"></div>
                                <div class="flex-1 space-y-3">
                                    <div class="h-3.5 w-1/3 animate-pulse rounded-full bg-slate-100"></div>
                                    <div class="h-3 w-2/3 animate-pulse rounded-full bg-slate-100"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            </aside>
        </div>
    </section>
@endsection
