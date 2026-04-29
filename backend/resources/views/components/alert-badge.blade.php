@props(['type' => 'success', 'text'])

@php
  $classes = [
    'success' => 'badge-success',
    'warning' => 'badge-warning',
    'danger' => 'badge-danger',
  ][$type] ?? 'badge-success';
@endphp

<span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $classes }}">
  {{ $text }}
</span>
