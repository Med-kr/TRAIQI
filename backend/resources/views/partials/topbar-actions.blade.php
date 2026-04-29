<div class="flex items-center gap-2">
    @include('partials.language-switcher')
    @include('partials.theme-switcher')

    <button type="button" class="admin-topbar-icon relative" aria-label="{{ __('ui.nav.notifications') }}">
        <x-traiqi-icon name="bell" class="h-5 w-5" />
        <span class="absolute end-2 top-2 h-2.5 w-2.5 rounded-full bg-[#18A558]"></span>
    </button>
</div>
