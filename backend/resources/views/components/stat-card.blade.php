@props(['label', 'value', 'icon', 'color' => 'blue', 'trend' => null, 'unit' => ''])

@php
  $accentMap = [
    'blue' => 'var(--traiqi-blue)',
    'gold' => 'var(--traiqi-gold)',
    'green' => 'var(--traiqi-green)',
    'red' => 'var(--traiqi-teal)',
  ];
  $accent = $accentMap[$color] ?? 'var(--traiqi-blue)';
@endphp

<div class="traiqi-card flex items-start gap-4 p-5" style="--stat-accent: {{ $accent }};">
  <div class="flex-shrink-0 rounded-xl border border-[var(--border)] p-3" style="background: color-mix(in srgb, var(--stat-accent) 14%, transparent); color: var(--stat-accent);">
    <x-icon :name="$icon" class="h-6 w-6" />
  </div>
  <div class="min-w-0 flex-1">
    <p class="text-xs font-medium uppercase tracking-wide text-[var(--text-secondary)]" x-text="$store.i18n.t('{{ $label }}')"></p>
    <p class="mt-1 text-2xl font-bold text-[var(--text-primary)]">
      {{ $value }}<span class="ms-1 text-sm font-normal text-[var(--text-secondary)]">{{ $unit }}</span>
    </p>
    @if($trend !== null)
      <p class="mt-1 text-xs {{ $trend > 0 ? 'text-emerald-500' : 'text-red-500' }}">
        {{ $trend > 0 ? '↑' : '↓' }} {{ abs($trend) }}%
      </p>
    @endif
  </div>
</div>
