@php
    $currentLocale = app()->getLocale();
    $user = auth()->user();
    $schoolName = $user?->school?->name ?? 'Établissement';
@endphp

<header class="sticky top-0 z-30 border-b border-slate-200/80 bg-white/88 backdrop-blur-xl">
    <div class="flex flex-col gap-4 px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
        <div class="flex items-center gap-3">
            <button type="button" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-900 shadow-sm lg:hidden" @click="$store.traiqi.toggleSidebar()">
                <x-traiqi-icon name="menu" class="h-5 w-5" />
            </button>

            <div class="relative w-full max-w-xl">
                <span class="pointer-events-none absolute inset-y-0 start-4 flex items-center text-slate-400">
                    <x-traiqi-icon name="search" class="h-5 w-5" />
                </span>
                <input type="search" placeholder="Rechercher une note, une matière ou une notification..." class="admin-toolbar-input ps-11">
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3 sm:justify-end">
            <span class="admin-pill is-neutral">{{ $schoolName }}</span>

            <button type="button" class="relative inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:text-slate-950">
                <x-traiqi-icon name="bell" class="h-5 w-5" />
                <span class="absolute end-2 top-2 h-2.5 w-2.5 rounded-full bg-[#18A558]"></span>
            </button>

            <div class="flex items-center gap-2 rounded-2xl border border-slate-200 bg-white p-1 shadow-sm">
                <button type="button" class="rounded-xl px-3 py-2 text-sm font-semibold {{ $currentLocale === 'fr' ? 'bg-slate-900 text-white' : 'text-slate-600' }}">FR</button>
                <button type="button" class="rounded-xl px-3 py-2 text-sm font-semibold {{ $currentLocale === 'ar' ? 'bg-slate-900 text-white' : 'text-slate-600' }}">AR</button>
            </div>

            <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-3 py-2 shadow-sm">
                <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-br from-[#0A4FAF] to-[#18A558] text-sm font-semibold text-white">
                    {{ \Illuminate\Support\Str::of($user?->name ?? 'EL')->explode(' ')->map(fn ($part) => mb_substr($part, 0, 1))->take(2)->implode('') }}
                </span>
                <div class="hidden sm:block">
                    <p class="text-sm font-semibold text-slate-950">{{ $user?->name ?? 'Élève' }}</p>
                    <p class="text-xs text-slate-500">Espace personnel</p>
                </div>
            </div>
        </div>
    </div>
</header>
