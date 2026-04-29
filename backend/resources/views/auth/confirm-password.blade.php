<x-guest-layout>
    <section class="px-4 py-10 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl">
            <x-ui.card :title="__('ui.auth.confirm_password')" class="p-6 sm:p-8">
                <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label for="password" class="field-label">{{ __('ui.auth.password') }}</label>
                        <input id="password" name="password" type="password" required class="field-input">
                    </div>
                    <x-ui.button type="submit">{{ __('ui.actions.done') }}</x-ui.button>
                </form>
            </x-ui.card>
        </div>
    </section>
</x-guest-layout>
