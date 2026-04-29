@php
    $locales = config('traiqi.locales');
    $currentLocale = app()->getLocale();
    $localeConfig = $locales[$currentLocale] ?? $locales[config('app.fallback_locale', 'en')];
    $pageTitle = $pageTitle ?? __('ui.app.name');
@endphp
<!DOCTYPE html>
<html lang="{{ $currentLocale }}" dir="{{ $localeConfig['dir'] }}" data-theme="light" data-font="{{ $localeConfig['font'] }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }} | {{ __('ui.app.name') }}</title>

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
            document.documentElement.dir = locales[activeLocale].dir;
            document.documentElement.dataset.font = locales[activeLocale].font;
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700;800&family=Noto+Sans+Tifinagh:wght@400;500;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">

    <script>
        window.traiqiConfig = {
            locale: @json($currentLocale),
            csrfToken: @json(csrf_token()),
            routes: {
                locale: @json(route('locale.switch')),
            },
            locales: @json($locales),
        };
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="page-shell antialiased" x-data x-init="$store.traiqi.init()">
    @if(($layout ?? 'dashboard') === 'dashboard')
        <div class="shell-grid">
            @include('partials.sidebar')

            <div class="shell-main">
                @include('partials.navbar')

                <main class="px-4 pb-8 pt-6 sm:px-6 lg:px-8 lg:pt-8">
                    <div class="mx-auto max-w-7xl space-y-6">
                        @if(! empty($header))
                            <header class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
                                {{ $header }}
                            </header>
                        @endif

                        @include('partials.alerts')

                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    @else
        <div class="relative">
            @include('partials.navbar', ['guest' => true])

            <main>
                @include('partials.alerts')
                {{ $slot }}
            </main>

            @include('partials.footer')
        </div>
    @endif
</body>
</html>
