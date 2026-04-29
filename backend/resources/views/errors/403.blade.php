<x-guest-layout>
    <section class="px-4 py-20 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-3xl">
            <div class="hero-panel text-center">
                <div class="relative z-10 space-y-5">
                    <p class="text-label !text-white/80">403</p>
                    <h1 class="text-display">{{ __('ui.errors.403_title') }}</h1>
                    <p class="mx-auto max-w-2xl text-lg text-white/88">{{ __('ui.errors.copy') }}</p>
                    <x-ui.button :href="route('home')">{{ __('ui.actions.back_home') }}</x-ui.button>
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>
