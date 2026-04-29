@php
    $guest = $guest ?? true;
@endphp

@if ($guest)
    <footer class="border-t border-slate-200 bg-white">
        <div class="mx-auto grid w-full gap-10 px-4 py-12 sm:px-6 lg:grid-cols-[1.1fr_0.9fr] lg:px-8 lg:py-16">
            <div class="space-y-5">
                <div class="flex items-center gap-4">
                    <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-50 ring-1 ring-slate-200">
                        <x-application-logo class="h-10 w-10" />
                    </span>
                    <div>
                        <p class="text-xl font-semibold text-slate-950">Traiqi</p>
                        <p class="text-sm font-medium text-[#0A4FAF]">Plateforme educative intelligente | طريقي</p>
                    </div>
                </div>

                <p class="max-w-xl text-sm leading-7 text-slate-600">
                    Traiqi aide les établissements, les enseignants et les familles à suivre la progression scolaire dans un environnement clair, fiable et moderne.
                </p>
            </div>

            <div class="grid gap-8 sm:grid-cols-2">
                <div>
                    <h2 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Navigation</h2>
                    <div class="mt-4 grid gap-3 text-sm text-slate-600">
                        <a href="#features" class="transition hover:text-slate-950">Fonctionnalités</a>
                        <a href="#why-traiqi" class="transition hover:text-slate-950">Pourquoi Traiqi</a>
                        <a href="#testimonials" class="transition hover:text-slate-950">Témoignages</a>
                        <a href="{{ route('login') }}" class="transition hover:text-slate-950">Se connecter</a>
                    </div>
                </div>

                <div>
                    <h2 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Confiance</h2>
                    <div class="mt-4 grid gap-3 text-sm text-slate-600">
                        <p>Suivi pédagogique structuré</p>
                        <p>Communication école-famille fluide</p>
                        <p>Espaces sécurisés par rôle</p>
                        <p>Compatible français et العربية</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t border-slate-200">
            <div class="mx-auto flex w-full flex-col gap-3 px-4 py-5 text-sm text-slate-500 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
                <p>© {{ now()->year }} Traiqi. Pensé pour une éducation plus claire, plus humaine, plus ambitieuse.</p>
                <p class="font-medium text-slate-600">Moroccan EdTech spirit, built with precision.</p>
            </div>
        </div>
    </footer>
@else
    <footer class="px-4 pb-8 pt-12 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl rounded-[2rem] border border-[color:var(--line)] bg-[color:var(--surface)] px-6 py-8 shadow-soft">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-center gap-4">
                    <div class="rounded-2xl bg-[color:var(--surface-strong)] p-3 shadow-soft">
                        <x-application-logo class="h-12 w-12" />
                    </div>
                    <div>
                        <p class="text-label">{{ __('ui.app.tagline') }}</p>
                        <p class="mt-2 text-section">{{ __('ui.app.footer_heading') }}</p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3 text-sm text-soft">
                    <a href="{{ route('help-center.index') }}" class="button-ghost !px-0 !py-0">{{ __('ui.nav.help_center') }}</a>
                    <a href="{{ route('opportunities.index') }}" class="button-ghost !px-0 !py-0">{{ __('ui.nav.opportunities') }}</a>
                    <a href="{{ route('portfolio.index') }}" class="button-ghost !px-0 !py-0">{{ __('ui.nav.portfolio') }}</a>
                </div>
            </div>
        </div>
    </footer>
@endif
