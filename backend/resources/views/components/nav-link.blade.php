@props(['active'])

@php
    $classes = ($active ?? false)
                ? 'traiqi-nav-link traiqi-nav-link-active'
                : 'traiqi-nav-link';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
