@props(['active'])

@php
    $classes = ($active ?? false)
                ? 'block w-full rounded-2xl bg-white px-4 py-3 text-start text-sm font-semibold text-slate-900 shadow-sm transition'
                : 'block w-full rounded-2xl px-4 py-3 text-start text-sm font-medium text-slate-200 transition hover:bg-white/10 hover:text-white';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
