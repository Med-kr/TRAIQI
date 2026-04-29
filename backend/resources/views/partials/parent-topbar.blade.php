@php
    $user = auth()->user();
    $schoolName = $user?->school?->name ?? 'Établissement';
@endphp

<header class="admin-topbar">
    <div class="flex flex-col gap-4 px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
        <div class="flex items-center gap-3">
            <button type="button" class="admin-topbar-icon lg:hidden" @click="$store.traiqi.toggleSidebar()">
                <x-traiqi-icon name="menu" class="h-5 w-5" />
            </button>

            <div class="relative w-full max-w-xl">
                <span class="pointer-events-none absolute inset-y-0 start-4 flex items-center text-slate-400">
                    <x-traiqi-icon name="search" class="h-5 w-5" />
                </span>
                <input type="search" placeholder="Rechercher une note, un message ou un rendez-vous..." class="admin-toolbar-input ps-11">
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3 sm:justify-end">
            <span class="admin-pill is-neutral">{{ $schoolName }}</span>

            @include('partials.topbar-actions')

            <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-3 py-2 shadow-sm">
                <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-br from-[#0A4FAF] to-[#18A558] text-sm font-semibold text-white">
                    {{ \Illuminate\Support\Str::of($user?->name ?? 'PA')->explode(' ')->map(fn ($part) => mb_substr($part, 0, 1))->take(2)->implode('') }}
                </span>
                <div class="hidden sm:block">
                    <p class="text-sm font-semibold text-slate-950">{{ $user?->name ?? 'Parent' }}</p>
                    <p class="text-xs text-slate-500">Espace famille</p>
                </div>
            </div>
        </div>
    </div>
</header>
