<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ (config('traiqi.locales')[app()->getLocale()]['dir'] ?? 'ltr') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Espace enseignant') | Traiqi</title>
    @include('partials.traiqi-boot')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/admin-overrides.css') . '?v=' . filemtime(public_path('css/admin-overrides.css')) }}">
</head>
<body class="admin-shell min-h-screen antialiased" x-data x-init="$store.traiqi.init()">
    <div class="relative min-h-screen lg:grid lg:grid-cols-[18rem_minmax(0,1fr)]">
        @include('partials.teacher-sidebar')

        <div class="min-w-0">
            @include('partials.teacher-topbar')

            <main class="px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
                <div class="mx-auto max-w-[1600px]">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>
</html>
