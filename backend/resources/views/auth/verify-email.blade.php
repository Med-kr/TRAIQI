<x-guest-layout>
    <section class="px-4 py-10 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl">
            <x-ui.card :title="__('ui.auth.sign_in')" :eyebrow="__('ui.nav.notifications')" class="p-6 sm:p-8">
                <p class="text-soft">{{ __('ui.auth.access_copy') }}</p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <x-ui.button type="submit">{{ __('ui.actions.send') }}</x-ui.button>
                    </form>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-ui.button type="submit" variant="secondary">{{ __('ui.nav.logout') }}</x-ui.button>
                    </form>
                </div>
            </x-ui.card>
        </div>
    </section>
</x-guest-layout>
