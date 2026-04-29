@props([
    'href' => null,
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
])

@php
    $base = 'inline-flex items-center justify-center gap-2 rounded-2xl font-semibold transition duration-200 focus:outline-none focus:ring-2 focus:ring-traiqi-blue/30 focus:ring-offset-2';
    $variants = [
        'primary' => 'bg-gradient-to-r from-[#0A4FAF] via-[#0A4FAF] to-[#18A558] text-white shadow-[0_18px_40px_rgba(10,79,175,0.28)] hover:-translate-y-0.5 hover:shadow-[0_22px_48px_rgba(10,79,175,0.32)]',
        'secondary' => 'border border-slate-200 bg-white text-slate-900 shadow-sm hover:border-[#0A4FAF]/20 hover:bg-slate-50',
        'ghost' => 'text-[#083B82] hover:bg-[#0A4FAF]/6',
        'dark' => 'bg-[#083B82] text-white shadow-[0_16px_34px_rgba(8,59,130,0.25)] hover:-translate-y-0.5',
    ];
    $sizes = [
        'sm' => 'px-4 py-2.5 text-sm',
        'md' => 'px-5 py-3 text-sm',
        'lg' => 'px-6 py-3.5 text-base',
    ];
    $classes = trim($base.' '.($variants[$variant] ?? $variants['primary']).' '.($sizes[$size] ?? $sizes['md']));
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
