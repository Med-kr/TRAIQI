@props([
    'label',
    'value',
    'hint' => null,
])

<article {{ $attributes->merge(['class' => 'metric-card']) }}>
    <p class="text-label">{{ $label }}</p>
    <p class="metric-value">{{ $value }}</p>
    @if($hint)
        <p class="mt-3 text-sm text-soft">{{ $hint }}</p>
    @endif
</article>
