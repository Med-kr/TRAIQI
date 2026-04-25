<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Student Dashboard
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Welcome back, {{ auth()->user()->name }}.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto space-y-6 sm:px-6 lg:px-8">
            <div class="grid gap-4 md:grid-cols-3">
                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <p class="text-sm text-gray-500">Classroom</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">
                        {{ $student?->classroom?->name ?? 'Not assigned yet' }}
                    </p>
                </div>

                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <p class="text-sm text-gray-500">Evaluations</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">
                        {{ $evaluations->count() }}
                    </p>
                </div>

                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <p class="text-sm text-gray-500">Average grade</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">
                        {{ $averageGrade !== null ? number_format($averageGrade, 2) . '/20' : 'No grades yet' }}
                    </p>
                </div>
            </div>

            <div class="rounded-xl bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900">Recent grades</h3>

                @if ($grades->isEmpty())
                    <p class="mt-4 text-sm text-gray-500">No grades available yet.</p>
                @else
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Evaluation</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Subject</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Grade</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Comments</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach ($grades as $grade)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $grade->evaluation?->title ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $grade->evaluation?->subject?->name ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $grade->evaluation?->date ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ $grade->value }}/20</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">
                                            {{ $grade->comments->pluck('comment')->implode(' | ') ?: 'No comment' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
