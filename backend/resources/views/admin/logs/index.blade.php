@extends('layouts.school-admin')

@section('title', 'Logs activité')

@section('content')
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
                    <form method="GET" action="{{ route('admin.logs.index') }}" class="grid gap-4 lg:grid-cols-[1fr_1fr_1fr_auto]">
                        <input type="search" name="user" value="{{ $filters['user'] ?? '' }}" class="admin-toolbar-input" placeholder="Utilisateur">
                        <input type="search" name="action" value="{{ $filters['action'] ?? '' }}" class="admin-toolbar-input" placeholder="Action">
                        <input type="date" name="date" value="{{ $filters['date'] ?? '' }}" class="admin-toolbar-input">
                        <button type="submit" class="rounded-2xl bg-[#083B82] px-5 py-3 text-sm font-semibold text-white shadow-sm">Filtrer</button>
                    </form>
                </section>

                <section class="admin-table-wrap">
                    <div class="overflow-x-auto">
                        <table class="admin-table min-w-[920px]">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Utilisateur</th>
                                    <th>Action</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($logs as $log)
                                    <tr class="transition hover:bg-slate-50/80">
                                        <td>{{ $log->created_at?->format('d/m/Y H:i') }}</td>
                                        <td class="font-semibold text-slate-950">{{ $log->user?->name ?? 'Système' }}</td>
                                        <td><span class="admin-pill is-neutral">{{ $log->action }}</span></td>
                                        <td>{{ $log->description ?: 'Aucune description' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-8 text-center text-sm text-slate-500">Aucun log trouvé.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($logs->hasPages())
                        <div class="border-t border-slate-100 px-4 py-4">
                            {{ $logs->links() }}
                        </div>
                    @endif
                </section>
            </div>

            <aside class="space-y-6">
                <section class="admin-card p-6 sm:p-7">
                    <h2 class="text-xl font-semibold text-slate-950">Résumé</h2>
                    <div class="mt-6 grid gap-4">
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-sm text-slate-500">Actions aujourd’hui</p>
                            <p class="mt-2 text-3xl font-semibold text-slate-950">{{ $todayCount }}</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-sm text-slate-500">Imports aujourd’hui</p>
                            <p class="mt-2 text-3xl font-semibold text-slate-950">{{ $importsTodayCount }}</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-sm text-slate-500">Utilisateurs tracés</p>
                            <p class="mt-2 text-3xl font-semibold text-slate-950">{{ $usersWithLogsCount }}</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-sm text-slate-500">Actions sensibles</p>
                            <p class="mt-2 text-3xl font-semibold text-slate-950">{{ $sensitiveTodayCount }}</p>
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
