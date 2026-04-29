<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-label">{{ __('ui.grades.title') }}</p>
            <h1 class="mt-2 text-title">{{ __('ui.grades.subtitle') }}</h1>
        </div>
    </x-slot>

    <x-ui.table>
        <thead>
            <tr>
                <th>{{ __('ui.grades.subject') }}</th>
                <th>{{ __('ui.grades.classroom') }}</th>
                <th>{{ __('ui.grades.date') }}</th>
                <th>{{ __('ui.grades.value') }}</th>
                <th>Comment</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[color:var(--line)]">
            @forelse($grades as $grade)
                <tr>
                    <td>{{ $grade->evaluation?->subject?->name ?? '—' }}</td>
                    <td>{{ $grade->evaluation?->classroom?->name ?? '—' }}</td>
                    <td>{{ $grade->evaluation?->date ? \Illuminate\Support\Carbon::parse($grade->evaluation->date)->translatedFormat('d M Y') : '—' }}</td>
                    <td>
                        <x-ui.badge :tone="$grade->value >= 10 ? 'success' : 'warning'">{{ number_format((float) $grade->value, 2) }}</x-ui.badge>
                    </td>
                    <td>{{ $grade->comments->first()?->comment ?? '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">{{ __('ui.grades.empty') }}</td>
                </tr>
            @endforelse
        </tbody>
    </x-ui.table>
</x-app-layout>
