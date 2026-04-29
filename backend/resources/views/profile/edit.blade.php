<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-label">{{ __('ui.profile.title') }}</p>
            <h1 class="mt-2 text-title">{{ __('ui.profile.subtitle') }}</h1>
        </div>
    </x-slot>

    <div class="grid items-start gap-6 xl:grid-cols-[0.9fr_1.1fr]">
        <x-ui.card>
            <p class="text-label">{{ __('ui.nav.profile') }}</p>
            <div class="mt-5 flex items-center gap-4">
                <span class="flex h-16 w-16 items-center justify-center rounded-3xl bg-gradient-to-br from-traiqi-blue to-traiqi-green text-xl font-semibold text-white">
                    {{ \Illuminate\Support\Str::of(auth()->user()->name)->explode(' ')->map(fn ($part) => mb_substr($part, 0, 1))->take(2)->implode('') }}
                </span>
                <div>
                    <p class="text-section">{{ auth()->user()->name }}</p>
                    <p class="text-soft">{{ auth()->user()->email }}</p>
                </div>
            </div>
        </x-ui.card>

        <div class="space-y-6">
            <x-ui.card>
                @include('profile.partials.update-profile-information-form')
            </x-ui.card>
            <x-ui.card>
                @include('profile.partials.update-password-form')
            </x-ui.card>
            <x-ui.card>
                @include('profile.partials.delete-user-form')
            </x-ui.card>
        </div>
    </div>
</x-app-layout>
