<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Teacher Dashboard
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Overview for {{ auth()->user()->name }}.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto space-y-6 sm:px-6 lg:px-8">
            <div class="grid gap-4 md:grid-cols-3">
                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <p class="text-sm text-gray-500">Assignments</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">{{ $assignmentsCount }}</p>
                </div>

                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <p class="text-sm text-gray-500">Evaluations</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">{{ $evaluationsCount }}</p>
                </div>

                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <p class="text-sm text-gray-500">Grades recorded</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">{{ $evaluations->sum(fn ($evaluation) => $evaluation->grades->count()) }}</p>
                </div>
            </div>

            <div class="rounded-xl bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900">Your assignments</h3>

                @if ($assignments->isEmpty())
                    <p class="mt-4 text-sm text-gray-500">No class assignments yet.</p>
                @else
                    <div class="mt-4 grid gap-4 md:grid-cols-2">
                        @foreach ($assignments as $assignment)
                            <div class="rounded-lg border border-gray-200 p-4">
                                <p class="text-sm text-gray-500">Classroom</p>
                                <p class="text-base font-semibold text-gray-900">{{ $assignment->classroom?->name ?? 'N/A' }}</p>
                                <p class="mt-3 text-sm text-gray-500">Subject</p>
                                <p class="text-base font-semibold text-gray-900">{{ $assignment->subject?->name ?? 'N/A' }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="rounded-xl bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900">Recent evaluations</h3>

                @if ($evaluations->isEmpty())
                    <p class="mt-4 text-sm text-gray-500">No evaluations created yet.</p>
                @else
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Title</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Classroom</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Subject</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Grades</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach ($evaluations as $evaluation)
                                    <tr>
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $evaluation->title }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $evaluation->classroom?->name ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $evaluation->subject?->name ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $evaluation->date }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $evaluation->grades->count() }}</td>
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
