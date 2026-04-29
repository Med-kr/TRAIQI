@php
  $role = auth()->user()?->primaryRole();
  $navItems = match ($role) {
    'student' => [
      ['icon'=>'grid-2x2', 'key'=>'nav.dashboard', 'route'=>'student.dashboard'],
      ['icon'=>'star', 'key'=>'nav.grades', 'route'=>'student.evaluations.index'],
      ['icon'=>'settings', 'key'=>'nav.settings', 'route'=>'profile.edit'],
    ],
    'parent' => [
      ['icon'=>'grid-2x2', 'key'=>'nav.dashboard', 'route'=>'parent.dashboard'],
      ['icon'=>'star', 'key'=>'nav.grades', 'route'=>'parent.dashboard'],
      ['icon'=>'settings', 'key'=>'nav.settings', 'route'=>'profile.edit'],
    ],
    'teacher' => [
      ['icon'=>'grid-2x2', 'key'=>'nav.dashboard', 'route'=>'teacher.dashboard'],
      ['icon'=>'star', 'key'=>'nav.grades', 'route'=>'teacher.evaluations.index'],
      ['icon'=>'settings', 'key'=>'nav.settings', 'route'=>'profile.edit'],
    ],
    default => [
      ['icon'=>'grid-2x2', 'key'=>'nav.dashboard', 'route'=>'admin.dashboard'],
      ['icon'=>'star', 'key'=>'nav.grades', 'route'=>'admin.reports.index'],
      ['icon'=>'book-open', 'key'=>'nav.resources', 'route'=>'admin.imports.index'],
      ['icon'=>'compass', 'key'=>'nav.orientation', 'route'=>'admin.user-management.index'],
      ['icon'=>'settings', 'key'=>'nav.settings', 'route'=>'profile.edit'],
    ],
  };
@endphp

<div x-data="{ open: true }">
  <div x-show="$store.ui.mobileSidebarOpen"
       x-transition.opacity
       class="sidebar-backdrop fixed inset-0 z-30 md:hidden"
       @click="$store.ui.closeSidebar()"></div>

  <aside x-show="window.innerWidth >= 768 || $store.ui.mobileSidebarOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-x-4"
         x-transition:enter-end="opacity-100 translate-x-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-x-0"
         x-transition:leave-end="opacity-0 -translate-x-4"
         :class="open ? 'w-64' : 'w-16'"
         class="sidebar fixed inset-y-0 z-40 flex min-h-screen flex-col bg-[var(--bg-sidebar)] transition-all duration-300 ease-in-out"
         style="transform:none;">
    <div class="flex items-center gap-3 border-b border-white/10 p-5">
      <img src="{{ asset('traiqi-logo.png') }}"
           alt="Traiqi Logo"
           class="logo-star h-10 w-10 flex-shrink-0"
           style="transform:none !important;">
      <span x-show="open"
            class="text-xl font-bold text-[var(--star)]"
            style="font-family:'Cairo',sans-serif;"
            x-text="$store.i18n.t('app.name')"></span>
    </div>

    <nav class="flex-1 overflow-y-auto py-4">
      @foreach($navItems as $item)
        @php
          $href = route($item['route']);
          $active = request()->routeIs($item['route']);
        @endphp
        <a href="{{ $href }}"
           class="mx-2 flex cursor-pointer items-center gap-3 rounded-xl px-4 py-3 text-[var(--text-sidebar)] transition-all duration-150 hover:bg-[var(--bg-sidebar-hover)] hover:text-[var(--text-sidebar-active)] {{ $active ? 'sidebar-item-active' : '' }}">
          <x-icon :name="$item['icon']" class="h-5 w-5 flex-shrink-0" />
          <span x-show="open"
                class="truncate text-sm font-medium"
                x-text="$store.i18n.t('{{ $item['key'] }}')"></span>
        </a>
      @endforeach
    </nav>

    <div class="border-t border-white/10 p-4">
      <div class="flex items-center gap-3">
        <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-[var(--traiqi-blue)] text-sm font-bold text-white" style="background: linear-gradient(135deg, var(--traiqi-blue), var(--traiqi-gold));">
          {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
        </div>
        <div x-show="open" class="min-w-0">
          <p class="truncate text-sm font-semibold text-white">{{ auth()->user()->name ?? 'Utilisateur' }}</p>
          <p class="truncate text-xs text-[var(--text-sidebar)]">{{ auth()->user()->primaryRole() ?? '' }}</p>
        </div>
      </div>
    </div>

    <button @click="open = !open"
            class="absolute top-20 -end-3 z-30 flex h-6 w-6 items-center justify-center rounded-full bg-[var(--gold)] shadow-lg"
            :aria-label="$store.i18n.t('nav.settings')">
      <svg x-bind:style="open && document.dir === 'ltr' ? '' : 'transform:rotate(180deg)'"
           class="h-3 w-3 text-white transition-transform duration-300"
           fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
      </svg>
    </button>
  </aside>
</div>
