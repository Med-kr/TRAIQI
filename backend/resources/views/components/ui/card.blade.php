@props([
    'title' => null,
    'eyebrow' => null,
    'padding' => 'p-6',
])

<section {{ $attributes->merge(['class' => 'surface-card ' . $padding]) }}>
    @if($eyebrow || $title)
        <header class="mb-5">
            @if($eyebrow)
                <p class="text-label">{{ $eyebrow }}</p>
            @endif
            @if($title)
                <h2 class="mt-2 text-section">{{ $title }}</h2>
            @endif
        </header>
    @endif

    {{ $slot }}
</section>
