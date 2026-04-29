<x-guest-layout>
    <section class="px-4 py-10 sm:px-6 lg:px-8">
        <div class="mx-auto grid max-w-6xl gap-6 lg:grid-cols-[0.92fr_1.08fr]">
            <x-ui.card class="p-6 sm:p-8">
                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="field-label">{{ __('ui.auth.full_name') }}</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus class="field-input">
                    </div>

                    <div>
                        <label for="email" class="field-label">{{ __('ui.auth.email') }}</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required class="field-input">
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label for="password" class="field-label">{{ __('ui.auth.password') }}</label>
                            <input id="password" name="password" type="password" required class="field-input">
                        </div>
                        <div>
                            <label for="password_confirmation" class="field-label">{{ __('ui.auth.confirm_password') }}</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required class="field-input">
                        </div>
                    </div>

                    <x-ui.button type="submit" class="w-full justify-center">{{ __('ui.auth.create_account') }}</x-ui.button>
                </form>
            </x-ui.card>

            <div class="hero-panel">
                <div class="relative z-10 space-y-6">
                    <span class="chip">{{ __('ui.auth.create_account') }}</span>
                    <h1 class="text-display">{{ __('ui.auth.register_copy') }}</h1>
                    <p class="max-w-2xl text-lg leading-8 text-slate-100/88">{{ __('ui.app.sidebar_message') }}</p>
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>
