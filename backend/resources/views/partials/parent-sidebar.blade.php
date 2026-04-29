@php
    $parentLinks = [
        ['label' => 'Dashboard', 'route' => 'parent.dashboard', 'icon' => 'dashboard'],
        ['label' => 'Mes enfants', 'route' => 'parent.children.index', 'icon' => 'users'],
        ['label' => 'Notes enfant', 'route' => 'parent.grades.index', 'icon' => 'grades'],
        ['label' => 'Messages', 'route' => 'parent.messages.index', 'icon' => 'bell'],
        ['label' => 'Rendez-vous', 'route' => 'parent.appointments.index', 'icon' => 'calendar'],
        ['label' => 'Profil', 'route' => 'profile.edit', 'icon' => 'settings'],
    ];
@endphp

<aside
    class="admin-sidebar fixed inset-y-0 start-0 z-50 flex w-[18rem] max-w-[88vw] -translate-x-full flex-col border-e border-white/10 px-4 py-5 text-white shadow-[0_28px_80px_rgba(8,59,130,0.38)] transition duration-300 lg:sticky lg:translate-x-0"
    :class="$store.traiqi.sidebarOpen ? 'translate-x-0' : ''"
>
    <div class="flex items-center justify-between gap-3 pb-6">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/12 p-2 backdrop-blur">
                <x-application-logo class="h-8 w-8" />
            </span>
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-white/70">Traiqi | طريقي</p>
                <p class="mt-1 text-lg font-semibold">Espace parent</p>
            </div>
        </a>

        <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-white/10 lg:hidden" @click="$store.traiqi.closeSidebar()">
            <x-traiqi-icon name="close" class="h-5 w-5" />
        </button>
    </div>

    <div class="rounded-[1.5rem] border border-white/12 bg-white/10 p-4 backdrop-blur">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-white/68">Suivi famille</p>
        <p class="mt-3 text-sm leading-7 text-white/86">Retrouvez les notes, les messages et les rendez-vous dans un espace clair, rassurant et simple à consulter au quotidien.</p>
    </div>

    <nav class="mt-6 space-y-1">
        @foreach ($parentLinks as $link)
            <a href="{{ \Illuminate\Support\Facades\Route::has($link['route']) ? route($link['route']) : '#' }}" class="admin-sidebar-link {{ request()->routeIs($link['route']) ? 'is-active' : '' }}">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-white/10">
                    <x-traiqi-icon :name="$link['icon']" class="h-5 w-5" />
                </span>
                <span>{{ $link['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <div class="mt-auto space-y-4 pt-6">
        <div class="rounded-[1.5rem] border border-white/12 bg-gradient-to-br from-white/12 to-white/6 p-4">
            <p class="text-sm font-semibold">Confiance & clarté</p>
            <p class="mt-2 text-sm leading-7 text-white/74">Une vue apaisée pour suivre la progression, communiquer avec l’école et préparer les prochains échanges.</p>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="admin-sidebar-link w-full justify-start bg-white/10 hover:bg-white/14">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-white/10">
                    <x-traiqi-icon name="close" class="h-5 w-5" />
                </span>
                <span>Déconnexion</span>
            </button>
        </form>
    </div>
</aside>

<div x-cloak x-show="$store.traiqi.sidebarOpen" class="fixed inset-0 z-40 bg-slate-950/50 lg:hidden" @click="$store.traiqi.closeSidebar()"></div>
