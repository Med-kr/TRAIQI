<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-label">{{ __('ui.notifications.title') }}</p>
            <h1 class="mt-2 text-title">{{ __('ui.notifications.subtitle') }}</h1>
        </div>
    </x-slot>

    <div class="grid gap-4">
        @forelse($notifications as $notification)
            <x-ui.card>
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <h2 class="text-section">{{ $notification->title }}</h2>
                        <p class="mt-3 text-soft">{{ $notification->body }}</p>
                    </div>
                    @if(! $notification->read)
                        <x-ui.button variant="secondary"
                            @click="$store.traiqi.markNotificationAsRead({{ $notification->id }}, '{{ route('notifications.read', $notification->id) }}')">
                            {{ __('ui.notifications.mark_read') }}
                        </x-ui.button>
                    @endif
                </div>
            </x-ui.card>
        @empty
            <x-ui.card>
                <p class="text-soft">{{ __('ui.notifications.empty') }}</p>
            </x-ui.card>
        @endforelse
    </div>
</x-app-layout>
