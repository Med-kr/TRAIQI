<button type="button"
    class="icon-button"
    @click="$store.traiqi.toggleTheme()"
    :aria-label="$store.traiqi.theme === 'dark' ? '{{ __('ui.theme.light') }}' : '{{ __('ui.theme.dark') }}'">
    <span x-show="$store.traiqi.theme === 'light'">
        <x-traiqi-icon name="moon" class="h-5 w-5" />
    </span>
    <span x-cloak x-show="$store.traiqi.theme === 'dark'">
        <x-traiqi-icon name="sun" class="h-5 w-5" />
    </span>
</button>
