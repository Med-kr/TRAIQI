<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-label">{{ __('ui.opportunities.title') }}</p>
            <h1 class="mt-2 text-title">{{ __('ui.opportunities.subtitle') }}</h1>
        </div>
    </x-slot>

    @php
        $items = [
            ['title' => 'STEM Innovation Lab', 'copy' => 'Weekly guided projects for students building scientific and digital confidence.'],
            ['title' => 'National merit scholarship', 'copy' => 'A structured pathway for high-achieving learners preparing competitive applications.'],
            ['title' => 'Creative expression circles', 'copy' => 'Writing, debate, theatre, and multilingual communication initiatives.'],
        ];
    @endphp

    <div class="grid gap-6 lg:grid-cols-3">
        @foreach($items as $item)
            <x-ui.card>
                <span class="chip">{{ __('ui.nav.opportunities') }}</span>
                <h2 class="mt-5 text-section">{{ $item['title'] }}</h2>
                <p class="mt-3 text-soft">{{ $item['copy'] }}</p>
            </x-ui.card>
        @endforeach
    </div>
</x-app-layout>
