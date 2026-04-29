<x-guest-layout>
    @section('title', 'Mot de passe oublié')

    <section class="marketing-shell px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
        <div class="mx-auto max-w-2xl">
            <div class="marketing-card p-6 sm:p-8 lg:p-10">
                <div class="mb-8 flex items-center gap-4">
                    <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-50 p-2 ring-1 ring-slate-200">
                        <x-application-logo class="h-10 w-10" />
                    </span>
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#0A4FAF]">Recuperation</p>
                        <h1 class="mt-1 text-2xl font-semibold text-slate-950">Recevoir un lien de réinitialisation</h1>
                    </div>
                </div>

                <p class="text-sm leading-7 text-slate-600">
                    Saisissez votre adresse email. Nous vous enverrons un lien sécurisé pour définir un nouveau mot de passe.
                </p>

                @if (session('status'))
                    <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="mt-8 space-y-5">
                    @csrf

                    <x-input
                        name="email"
                        type="email"
                        :label="__('ui.auth.email')"
                        required
                        autofocus
                        placeholder="vous@etablissement.ma"
                    />

                    <x-button type="submit" size="lg" class="w-full justify-center">Envoyer le lien</x-button>
                </form>

                <div class="mt-6">
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-[#0A4FAF] transition hover:text-[#083B82]">
                        Retour à la connexion
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>
