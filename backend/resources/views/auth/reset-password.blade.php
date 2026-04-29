<x-guest-layout>
    @section('title', 'Réinitialiser le mot de passe')

    <section class="marketing-shell px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
        <div class="mx-auto max-w-2xl">
            <div class="marketing-card p-6 sm:p-8 lg:p-10">
                <div class="mb-8">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#0A4FAF]">Nouveau mot de passe</p>
                    <h1 class="mt-2 text-2xl font-semibold text-slate-950">Définissez un accès sécurisé</h1>
                    <p class="mt-3 text-sm leading-7 text-slate-600">Choisissez un mot de passe fort pour retrouver votre espace Traiqi en toute sécurité.</p>
                </div>

                <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <x-input
                        name="email"
                        type="email"
                        :value="$request->email"
                        :label="__('ui.auth.email')"
                        required
                        autocomplete="username"
                    />

                    <x-input
                        name="password"
                        type="password"
                        :label="__('ui.auth.password')"
                        required
                        autocomplete="new-password"
                        placeholder="Nouveau mot de passe"
                    />

                    <x-input
                        name="password_confirmation"
                        type="password"
                        :label="__('ui.auth.confirm_password')"
                        required
                        autocomplete="new-password"
                        placeholder="Confirmer le mot de passe"
                    />

                    <x-button type="submit" size="lg" class="w-full justify-center">Réinitialiser</x-button>
                </form>
            </div>
        </div>
    </section>
</x-guest-layout>
