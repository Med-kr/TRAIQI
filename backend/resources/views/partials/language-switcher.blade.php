<div class="relative" x-data>
    <button type="button"
        class="button-secondary !rounded-full !px-4 !py-2.5"
        @click="$store.traiqi.languageOpen = ! $store.traiqi.languageOpen"
        @click.outside="$store.traiqi.languageOpen = false"
        aria-haspopup="true"
        :aria-expanded="$store.traiqi.languageOpen">
        <x-traiqi-icon name="globe" class="h-4 w-4" />
        <span>{{ config('traiqi.locales')[app()->getLocale()]['native'] ?? 'Language' }}</span>
    </button>

    <div x-cloak
        x-show="$store.traiqi.languageOpen"
        x-transition.opacity.scale.origin.top.right
        class="surface-panel absolute end-0 z-20 mt-3 w-60 overflow-hidden p-2">
        @foreach(config('traiqi.locales') as $code => $locale)
            <button type="button"
                class="sidebar-link w-full justify-between text-start {{ app()->getLocale() === $code ? 'is-active' : '' }}"
                @click="$store.traiqi.switchLanguage('{{ $code }}')">
                <span>
                    <span class="block font-semibold text-[color:var(--text)]">{{ $locale['native'] }}</span>
                    <span class="text-xs text-faint">{{ strtoupper($code) }} · {{ strtoupper($locale['dir']) }}</span>
                </span>
                @if(app()->getLocale() === $code)
                    <span class="status-badge">{{ __('ui.language.active') }}</span>
                @endif
            </button>
        @endforeach
    </div>
</div>
