@php
    $locales = config('traiqi.locales');
    $currentLocale = app()->getLocale();
    $localeConfig = $locales[$currentLocale] ?? $locales[config('app.fallback_locale', 'en')];
    $pageTitle = $pageTitle ?? __('ui.app.name');
    $userRole = auth()->user()?->primaryRole();
    $useSchoolAdminShell = in_array($userRole, ['school_admin', 'super_admin'], true);
@endphp
<!DOCTYPE html>
<html lang="{{ $currentLocale }}" dir="{{ $localeConfig['dir'] }}" data-theme="light" data-font="{{ $localeConfig['font'] }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }} | {{ __('ui.app.name') }}</title>

    @include('partials.traiqi-boot')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700;800&family=Noto+Sans+Tifinagh:wght@400;500;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/admin-overrides.css') . '?v=' . filemtime(public_path('css/admin-overrides.css')) }}">
</head>
<body class="{{ $useSchoolAdminShell ? 'admin-shell' : 'page-shell' }} antialiased" x-data x-init="$store.traiqi.init()">
    @if(($layout ?? 'dashboard') === 'dashboard')
        @if($useSchoolAdminShell)
            <div class="relative min-h-screen lg:grid lg:grid-cols-[18rem_minmax(0,1fr)]">
                @include('partials.school-sidebar')

                <div class="min-w-0">
                    @include('partials.school-topbar')

                    <main class="px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
                        <div class="mx-auto max-w-[1600px] space-y-6">
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
        @endif
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
