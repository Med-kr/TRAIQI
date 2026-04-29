@php
    $locales = config('traiqi.locales');
    $currentLocale = app()->getLocale();
    $localeConfig = $locales[$currentLocale] ?? $locales[config('app.fallback_locale', 'fr')] ?? ['dir' => 'ltr', 'font' => 'latin'];
@endphp

<script>
    (function () {
        var theme = localStorage.getItem('traiqi.theme');
        var locales = @json($locales);
        var locale = localStorage.getItem('traiqi.locale') || @json($currentLocale);
        var activeLocale = locales[locale] ? locale : @json($currentLocale);
        var activeTheme = theme || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');

        document.documentElement.dataset.theme = activeTheme;
        document.documentElement.classList.toggle('dark', activeTheme === 'dark');
        document.documentElement.lang = activeLocale;
        document.documentElement.dir = locales[activeLocale]?.dir || @json($localeConfig['dir']);
        document.documentElement.dataset.font = locales[activeLocale]?.font || @json($localeConfig['font']);
    })();

    window.traiqiConfig = {
        locale: @json($currentLocale),
        csrfToken: @json(csrf_token()),
        routes: {
            locale: @json(route('locale.switch')),
        },
        locales: @json($locales),
    };
</script>
