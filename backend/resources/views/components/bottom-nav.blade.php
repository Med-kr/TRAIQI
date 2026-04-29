@php
  $role = auth()->user()?->primaryRole();
  $items = match ($role) {
    'student' => [
      ['icon'=>'grid-2x2', 'key'=>'nav.dashboard', 'route'=>'student.dashboard'],
      ['icon'=>'star', 'key'=>'nav.grades', 'route'=>'student.evaluations.index'],
      ['icon'=>'settings', 'key'=>'nav.settings', 'route'=>'profile.edit'],
    ],
    'parent' => [
      ['icon'=>'grid-2x2', 'key'=>'nav.dashboard', 'route'=>'parent.dashboard'],
      ['icon'=>'star', 'key'=>'nav.grades', 'route'=>'parent.dashboard'],
      ['icon'=>'settings', 'key'=>'nav.settings', 'route'=>'profile.edit'],
    ],
    'teacher' => [
      ['icon'=>'grid-2x2', 'key'=>'nav.dashboard', 'route'=>'teacher.dashboard'],
      ['icon'=>'star', 'key'=>'nav.grades', 'route'=>'teacher.evaluations.index'],
      ['icon'=>'settings', 'key'=>'nav.settings', 'route'=>'profile.edit'],
    ],
    default => [
      ['icon'=>'grid-2x2', 'key'=>'nav.dashboard', 'route'=>'admin.dashboard'],
      ['icon'=>'star', 'key'=>'nav.grades', 'route'=>'admin.reports.index'],
      ['icon'=>'book-open', 'key'=>'nav.resources', 'route'=>'admin.imports.index'],
      ['icon'=>'settings', 'key'=>'nav.settings', 'route'=>'profile.edit'],
    ],
  };
@endphp

<nav class="bottom-nav-safe fixed inset-x-0 bottom-0 z-30 flex justify-around border-t border-[var(--border)] bg-[var(--bg-surface)] md:hidden">
  @foreach($items as $item)
    @php
      $href = route($item['route']);
      $active = request()->routeIs($item['route']);
    @endphp
    <a href="{{ $href }}"
       class="flex flex-col items-center gap-1 px-3 py-2 text-xs transition-colors {{ $active ? 'text-[var(--gold)]' : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)]' }}">
      <x-icon :name="$item['icon']" class="h-5 w-5" />
      <span x-text="$store.i18n.t('{{ $item['key'] }}')"></span>
    </a>
  @endforeach
</nav>
