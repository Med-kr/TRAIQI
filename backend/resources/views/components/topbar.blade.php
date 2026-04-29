<header class="flex h-16 flex-shrink-0 items-center justify-between border-b border-[var(--border)] bg-[var(--bg-surface)] px-4 shadow-[var(--shadow-sm)] md:px-6">
  <button @click="$store.ui.toggleSidebar()"
          class="rounded-lg p-2 transition-colors hover:bg-[var(--bg-base)] md:hidden"
          :aria-label="$store.i18n.t('nav.dashboard')">
    <svg class="h-6 w-6 text-[var(--text-primary)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
    </svg>
  </button>

  <div class="mx-6 hidden max-w-md flex-1 md:flex">
    <div class="relative w-full">
      <input type="text"
             :placeholder="$store.i18n.t('common.search')"
             class="surface-input w-full rounded-xl px-4 py-2 ps-10 text-sm placeholder:text-[var(--text-secondary)]">
      <svg class="absolute start-3 top-1/2 h-4 w-4 -translate-y-1/2 text-[var(--text-secondary)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 1 1-14 0 7 7 0 0 1 14 0z"/>
      </svg>
    </div>
  </div>

  <div class="flex items-center gap-2">
    @include('components.lang-switcher')
    @include('components.theme-toggle')

    <button class="relative rounded-xl p-2 transition-colors hover:bg-[var(--bg-base)]"
            :aria-label="$store.i18n.t('dashboard.alerts')">
      <x-icon name="bell" class="h-5 w-5 text-[var(--text-secondary)]" />
      <span class="absolute end-1 top-1 h-2 w-2 rounded-full bg-red-500"></span>
    </button>
  </div>
</header>
