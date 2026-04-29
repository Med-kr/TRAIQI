<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-label">{{ __('ui.help.title') }}</p>
            <h1 class="mt-2 text-title">{{ __('ui.help.subtitle') }}</h1>
        </div>
    </x-slot>

    @php
        $faqs = [
            ['q' => 'How do I switch my role dashboard?', 'a' => 'Use the main navigation and your account role routing will bring you to the correct workspace.'],
            ['q' => 'How can I follow student progress?', 'a' => 'Use the grades, notifications, and revision request pages for a complete view.'],
            ['q' => 'How is language stored?', 'a' => 'Your preference is saved for the next visit and applied automatically before the page finishes loading.'],
        ];
    @endphp

    <div class="grid gap-4">
        @foreach($faqs as $faq)
            <x-ui.card>
                <h2 class="text-section">{{ $faq['q'] }}</h2>
                <p class="mt-3 text-soft">{{ $faq['a'] }}</p>
            </x-ui.card>
        @endforeach
    </div>
</x-app-layout>
