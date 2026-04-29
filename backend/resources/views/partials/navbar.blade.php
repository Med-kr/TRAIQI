@php
    $guest = $guest ?? false;
    $user = auth()->user();
    $navLinks = [
        ['label' => 'Fonctionnalités', 'href' => '#features'],
        ['label' => 'Pourquoi Traiqi', 'href' => '#why-traiqi'],
        ['label' => 'Résultats', 'href' => '#stats'],
        ['label' => 'Témoignages', 'href' => '#testimonials'],
    ];
@endphp

@if ($guest)
    <header class="sticky top-0 z-40 border-b border-slate-200 bg-white/95 shadow-[0_10px_30px_rgba(15,23,42,0.04)] backdrop-blur-xl">
        <div class="mx-auto flex w-full items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8" x-data="{ open: false }">
            <a href="{{ route('home') }}" class="flex min-w-0 items-center gap-3">
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-50 shadow-sm ring-1 ring-slate-200">
                    <x-application-logo class="h-9 w-9" />
                </span>
                <span class="min-w-0">
                    <span class="block truncate text-lg font-semibold text-slate-950">Traiqi</span>
                    <span class="block truncate text-xs font-medium tracking-[0.18em] text-[#0A4FAF]">طريقي</span>
                </span>
            </a>

            <nav class="hidden items-center gap-2 lg:flex">
                @foreach ($navLinks as $link)
                    <a href="{{ $link['href'] }}" class="rounded-full px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100 hover:text-slate-950">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="hidden items-center gap-3 lg:flex">
                <x-button :href="route('login')" variant="secondary">Se connecter</x-button>
                <x-button href="#cta-banner">Commencer</x-button>
            </div>

            <button
                type="button"
                class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-900 shadow-sm lg:hidden"
                @click="open = !open"
                :aria-expanded="open.toString()"
                aria-label="Ouvrir le menu"
            >
                <svg x-show="!open" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16" />
                </svg>
                <svg x-show="open" x-cloak class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" d="M6 6l12 12M18 6L6 18" />
                </svg>
            </button>

            <div
                x-cloak
                x-show="open"
                x-transition.opacity.scale.origin.top
                class="absolute inset-x-4 top-[calc(100%+0.75rem)] rounded-[1.5rem] border border-slate-200 bg-white p-4 shadow-[0_18px_50px_rgba(15,23,42,0.12)] lg:hidden"
            >
                <div class="grid gap-2">
                @foreach ($navLinks as $link)
                    <a href="{{ $link['href'] }}" class="rounded-2xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-100 hover:text-slate-950" @click="open = false">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </div>

                <div class="mt-4 grid gap-3 border-t border-slate-200 pt-4 sm:grid-cols-2">
                    <x-button :href="route('login')" variant="secondary" class="w-full justify-center">Se connecter</x-button>
                    <x-button href="#cta-banner" class="w-full justify-center" @click="open = false">Commencer</x-button>
                </div>
            </div>
        </div>
    </header>
@else
    @php
        $initials = \Illuminate\Support\Str::of($user->name)->explode(' ')->map(fn ($part) => mb_substr($part, 0, 1))->take(2)->implode('');
    @endphp
    <header class="sticky top-0 z-30 border-b border-[color:var(--line)] bg-[color:rgba(244,247,251,0.84)] backdrop-blur-xl dark:bg-[color:rgba(7,17,32,0.84)]">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <button type="button" class="icon-button lg:hidden" @click="$store.traiqi.toggleSidebar()" aria-label="{{ __('ui.nav.open_menu') }}">
                    <x-traiqi-icon name="menu" class="h-5 w-5" />
                </button>

                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <div class="rounded-2xl bg-white/90 p-2 shadow-soft dark:bg-[color:var(--surface-strong)]">
                        <x-application-logo class="h-11 w-11" />
                    </div>
                    <div>
                        <p class="text-label">{{ __('ui.app.tagline') }}</p>
                        <p class="text-section">{{ __('ui.app.name') }}</p>
                    </div>
                </a>
            </div>

            <div class="flex items-center gap-2 sm:gap-3">
                @include('partials.language-switcher')
                @include('partials.theme-switcher')

                <a href="{{ route('notifications.index') }}" class="icon-button relative" aria-label="{{ __('ui.nav.notifications') }}">
                    <x-traiqi-icon name="bell" class="h-5 w-5" />
                </a>

                <div class="relative" x-data>
                    <button
                        type="button"
                        class="button-secondary !rounded-full !px-3 !py-2"
                        @click="$store.traiqi.profileMenuOpen = ! $store.traiqi.profileMenuOpen"
                        @click.outside="$store.traiqi.profileMenuOpen = false"
                    >
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-traiqi-blue to-traiqi-green text-sm font-semibold text-white">
                            {{ $initials }}
                        </span>
                        <span class="hidden text-start sm:block">
                            <span class="block text-sm font-semibold text-[color:var(--text)]">{{ $user->name }}</span>
                            <span class="block text-xs text-faint">{{ __('ui.roles.' . ($user->primaryRole() ?? 'student')) }}</span>
                        </span>
                    </button>

                    <div x-cloak x-show="$store.traiqi.profileMenuOpen" x-transition.opacity.scale.origin.top.right class="surface-panel absolute end-0 mt-3 w-64 p-2">
                        <a href="{{ route('profile.edit') }}" class="sidebar-link">{{ __('ui.nav.profile') }}</a>
                        <a href="{{ route($user->dashboardRoute()) }}" class="sidebar-link">{{ __('ui.nav.dashboard') }}</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="sidebar-link w-full text-start">{{ __('ui.nav.logout') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>
@endif
