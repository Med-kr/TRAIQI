<x-guest-layout>
    @section('title', 'Connexion')

    <section class="auth-shell px-4 py-8 sm:px-6 sm:py-10 lg:px-8 lg:py-12">
        <div class="mx-auto grid min-h-[calc(100vh-9rem)] max-w-7xl gap-6 lg:grid-cols-[1.05fr_0.95fr]">
            <div class="auth-panel hidden overflow-hidden p-8 text-white lg:flex lg:flex-col lg:justify-between xl:p-10">
                <div class="space-y-8">
                    <div class="flex items-center gap-4">
                        <span class="flex h-20 w-20 items-center justify-center rounded-[1.75rem] bg-white/12 p-3 backdrop-blur">
                            <x-application-logo class="h-full w-full" />
                        </span>
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-white/70">Traiqi | طريقي</p>
                            <h1 class="mt-2 text-4xl font-semibold leading-tight">Reprenez la main sur votre rythme scolaire.</h1>
                        </div>
                    </div>

                    <p class="max-w-2xl text-lg leading-8 text-white/82">
                        Une interface apaisée pour les équipes éducatives, les familles et les élèves. Tout ce qui compte, sans bruit inutile.
                    </p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="marketing-glass p-5">
                        <p class="text-sm uppercase tracking-[0.18em] text-white/70">Clarté</p>
                        <p class="mt-3 text-xl font-semibold">Suivi des notes et évaluations</p>
                    </div>
                    <div class="marketing-glass p-5">
                        <p class="text-sm uppercase tracking-[0.18em] text-white/70">Confiance</p>
                        <p class="mt-3 text-xl font-semibold">Accès sécurisé par rôle</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-center">
                <div class="auth-card w-full max-w-xl p-6 sm:p-8 lg:p-10">
                    <div class="mb-8 flex items-center gap-4 lg:hidden">
                        <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-50 p-2 ring-1 ring-slate-200">
                            <x-application-logo class="h-10 w-10" />
                        </span>
                        <div>
                            <p class="text-base font-semibold text-slate-950">Traiqi</p>
                            <p class="text-sm text-[#0A4FAF]">Plateforme educative intelligente</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#0A4FAF]">Connexion</p>
                        <h2 class="text-3xl font-semibold text-slate-950">Bienvenue.</h2>
                        <p class="text-sm leading-7 text-slate-600">Connectez-vous pour accéder à votre espace scolaire et retrouver vos outils, vos échanges et vos indicateurs essentiels.</p>
                    </div>

                    @if (session('status'))
                        <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
                        @csrf

                        <x-input
                            name="email"
                            type="email"
                            :label="__('ui.auth.email')"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="vous@etablissement.ma"
                        />

                        <x-input
                            name="password"
                            type="password"
                            :label="__('ui.auth.password')"
                            required
                            autocomplete="current-password"
                            placeholder="Votre mot de passe"
                        />

                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <label class="inline-flex items-center gap-3 text-sm text-slate-600">
                                <input type="checkbox" name="remember" class="rounded border-slate-300 text-[#0A4FAF] focus:ring-[#0A4FAF]/20">
                                <span>Se souvenir de moi</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm font-semibold text-[#0A4FAF] transition hover:text-[#083B82]">
                                    Mot de passe oublié ?
                                </a>
                            @endif
                        </div>

                        <x-button type="submit" size="lg" class="w-full justify-center">Se connecter</x-button>

                        <div class="relative py-2">
                            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                <span class="w-full border-t border-slate-200"></span>
                            </div>
                            <div class="relative flex justify-center">
                                <span class="bg-white px-4 text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Sécurité</span>
                            </div>
                        </div>

                        <div class="rounded-2xl bg-slate-50 px-4 py-4 text-sm leading-7 text-slate-600">
                            Votre session est protégée. Les accès sont organisés par rôle pour préserver la confidentialité des données scolaires.
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>
