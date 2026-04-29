<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Evaluation Grades</h2>
                <p class="mt-1 text-sm text-gray-500">
                    {{ $evaluation->title }} | {{ $evaluation->classroom?->name }} | {{ $evaluation->subject?->name }}
                </p>
            </div>
            <a href="{{ route('teacher.dashboard') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700">
                Back to dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            <div class="rounded-xl bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Record grades</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Type: {{ str_replace('_', ' ', ucfirst($evaluation->type)) }} | Average: {{ number_format((float) $classAverage, 2) }} | Remaining: {{ $remainingCopies }}
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        @if (! $evaluation->is_published)
                            <form method="POST" action="{{ route('teacher.evaluations.publish', $evaluation->id) }}">
                                @csrf
                                <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white">Publish</button>
                            </form>
                        @endif
                        @if (! $evaluation->is_locked)
                            <form method="POST" action="{{ route('teacher.evaluations.lock', $evaluation->id) }}">
                                @csrf
                                <button type="submit" class="rounded-lg border border-amber-300 bg-amber-50 px-4 py-2 text-sm font-medium text-amber-900">Lock</button>
                            </form>
                        @endif
                    </div>
                </div>

                <form method="POST" action="{{ route('teacher.grades.store', $evaluation->id) }}" class="mt-4 space-y-4">
                    @csrf

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Student</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Current grade</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">New grade</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse ($students as $studentProfile)
                                    @php $grade = $grades->get($studentProfile->user_id); @endphp
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $studentProfile->user?->name ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $grade?->value ?? 'Not set yet' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            <input
                                                type="number"
                                                step="0.01"
                                                min="0"
                                                max="20"
                                                name="grades[{{ $studentProfile->user_id }}]"
                                                value="{{ old('grades.' . $studentProfile->user_id, $grade?->value) }}"
                                                class="w-32 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                @disabled($evaluation->is_locked)
                                            >
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-6 text-center text-sm text-gray-500">No students found in this classroom.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <x-input-error :messages="$errors->get('grades')" class="mt-2" />
                    <x-input-error :messages="$errors->get('grades.*')" class="mt-2" />

                    <x-primary-button :disabled="$evaluation->is_locked">Save Grades</x-primary-button>
                </form>
            </div>

            <div class="rounded-xl bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900">Teacher comments</h3>

                <div class="mt-4 space-y-6">
                    @foreach ($students as $studentProfile)
                        @php $grade = $grades->get($studentProfile->user_id); @endphp
                        <div class="rounded-lg border border-gray-200 p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-500">Student</p>
                                    <p class="text-base font-semibold text-gray-900">{{ $studentProfile->user?->name ?? 'N/A' }}</p>
                                </div>
                                <div class="text-sm text-gray-600">
                                    Grade: {{ $grade?->value ?? 'Not set yet' }}
                                </div>
                            </div>

                            @if ($grade)
                                <div class="mt-4 space-y-3">
                                    @forelse ($grade->comments as $comment)
                                        <div class="rounded-md bg-gray-50 p-3 text-sm text-gray-700">
                                            <p>{{ $comment->comment }}</p>
                                            <p class="mt-2 text-xs text-gray-500">{{ $comment->teacher?->name ?? 'Teacher' }}</p>
                                        </div>
                                    @empty
                                        <p class="text-sm text-gray-500">No comments yet.</p>
                                    @endforelse
                                </div>

                                <div class="mt-4 space-y-3">
                                    <p class="text-sm font-semibold text-gray-900">Grade history</p>

                                    @forelse ($grade->histories->take(3) as $history)
                                        <div class="rounded-md border border-slate-200 bg-slate-50 p-3 text-sm text-slate-700">
                                            <p>{{ $history->old_value ?? 'Not set' }} -> {{ $history->new_value }}</p>
                                            <p class="mt-1 text-xs text-slate-500">{{ $history->actor?->name ?? 'System' }} · {{ $history->created_at?->format('Y-m-d H:i') }}</p>
                                        </div>
                                    @empty
                                        <p class="text-sm text-gray-500">No history yet.</p>
                                    @endforelse
                                </div>

                                <div class="mt-4 space-y-3">
                                    <p class="text-sm font-semibold text-gray-900">Parent review requests</p>

                                    @forelse ($grade->reviewRequests as $reviewRequest)
                                        <div class="rounded-md border border-amber-200 bg-amber-50 p-3 text-sm text-amber-900">
                                            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                                                <span>Requested for {{ $reviewRequest->student?->name ?? 'student' }}</span>
                                                <span class="rounded-full bg-white px-2 py-1 text-xs font-semibold uppercase text-amber-700">
                                                    {{ $reviewRequest->status }}
                                                </span>
                                            </div>
                                            <p class="mt-2">{{ $reviewRequest->reason }}</p>
                                        </div>
                                    @empty
                                        <p class="text-sm text-gray-500">No parent requests for this grade.</p>
                                    @endforelse
                                </div>

                                <form method="POST" action="{{ route('teacher.grades.comments.store') }}" class="mt-4 space-y-3">
                                    @csrf
                                    <input type="hidden" name="grade_id" value="{{ $grade->id }}">
                                    <div>
                                        <x-input-label :value="'Add comment for ' . ($studentProfile->user?->name ?? 'student')" />
                                        <textarea name="comment" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" @disabled($evaluation->is_locked)></textarea>
                                        <x-input-error :messages="$errors->get('comment')" class="mt-2" />
                                    </div>
                                    <x-primary-button :disabled="$evaluation->is_locked">Add Comment</x-primary-button>
                                </form>
                            @else
                                <p class="mt-4 text-sm text-gray-500">Save the grade first, then you can add comments.</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
