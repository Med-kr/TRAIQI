<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Parent Dashboard
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Follow your children and their latest grades.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto space-y-6 sm:px-6 lg:px-8">
            <div class="grid gap-4 md:grid-cols-2">
                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <p class="text-sm text-gray-500">Linked children</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">{{ $childrenCount }}</p>
                </div>
                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <p class="text-sm text-gray-500">Parent account</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                </div>
            </div>

            @if ($childrenWithGrades->isEmpty())
                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <p class="text-sm text-gray-500">No linked children yet.</p>
                </div>
            @else
                @foreach ($childrenWithGrades as $entry)
                    <div class="rounded-xl bg-white p-6 shadow-sm">
                        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $entry['child']->name }}</h3>
                                <p class="text-sm text-gray-500">
                                    Classroom: {{ $entry['child']->studentProfile?->classroom?->name ?? 'Not assigned yet' }}
                                </p>
                            </div>
                            <div class="text-sm text-gray-600">
                                Average:
                                <span class="font-semibold text-gray-900">
                                    {{ $entry['average'] !== null ? number_format($entry['average'], 2) . '/20' : 'No grades yet' }}
                                </span>
                            </div>
                        </div>

                        @if ($entry['grades']->isEmpty())
                            <p class="mt-4 text-sm text-gray-500">No grades available for this child yet.</p>
                        @else
                            <div class="mt-4 overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Evaluation</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Subject</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Date</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Grade</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        @foreach ($entry['grades'] as $grade)
                                            <tr>
                                                <td class="px-4 py-3 text-sm text-gray-900">{{ $grade->evaluation?->title ?? 'N/A' }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-600">{{ $grade->evaluation?->subject?->name ?? 'N/A' }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-600">{{ $grade->evaluation?->date ?? 'N/A' }}</td>
                                                <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ $grade->value }}/20</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</x-app-layout>
