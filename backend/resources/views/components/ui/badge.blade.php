@props(['tone' => 'default'])

@php
    $classes = match ($tone) {
        'success' => 'status-badge is-success',
        'warning' => 'status-badge is-warning',
        'danger' => 'status-badge is-danger',
        default => 'status-badge',
    };
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
