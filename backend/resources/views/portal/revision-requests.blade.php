<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-label">{{ __('ui.revision_requests.title') }}</p>
            <h1 class="mt-2 text-title">{{ __('ui.revision_requests.subtitle') }}</h1>
        </div>
    </x-slot>

    <div class="grid gap-4">
        @forelse($reviewRequests as $request)
            <x-ui.card>
                <div class="flex flex-col gap-4 lg:flex-row lg:justify-between">
                    <div>
                        <h2 class="text-section">{{ $request->grade?->evaluation?->subject?->name ?? '—' }}</h2>
                        <p class="mt-2 text-soft">{{ $request->reason }}</p>
                        <p class="mt-2 text-sm text-faint">{{ $request->student?->name }}</p>
                    </div>
                    <x-ui.badge :tone="$request->status === 'approved' ? 'success' : ($request->status === 'rejected' ? 'danger' : 'warning')">
                        {{ ucfirst($request->status) }}
                    </x-ui.badge>
                </div>
            </x-ui.card>
        @empty
            <x-ui.card>
                <p class="text-soft">{{ __('ui.revision_requests.empty') }}</p>
            </x-ui.card>
        @endforelse
    </div>
</x-app-layout>
