<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $assignment->exists ? 'Edit Teacher Assignment' : 'Create Teacher Assignment' }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="rounded-xl bg-white p-6 shadow-sm">
                <form method="POST" action="{{ $assignment->exists ? route('admin.teacher-assignments.update', $assignment->id) : route('admin.teacher-assignments.store') }}" class="space-y-6">
                    @csrf
                    @if ($assignment->exists)
                        @method('PUT')
                    @endif

                    @if (auth()->user()->hasRole('super_admin'))
                        <div>
                            <x-input-label for="school_id" value="School" />
                            <select id="school_id" name="school_id" class="mt-1 block w-full rounded-md border-gray-300">
                                <option value="">Select a school</option>
                                @foreach ($schools as $school)
                                    <option value="{{ $school->id }}" @selected(old('school_id', $assignment->school_id) == $school->id)>{{ $school->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('school_id')" class="mt-2" />
                        </div>
                    @endif

                    <div>
                        <x-input-label for="teacher_id" value="Teacher" />
                        <select id="teacher_id" name="teacher_id" class="mt-1 block w-full rounded-md border-gray-300">
                            <option value="">Select a teacher</option>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}" @selected(old('teacher_id', $assignment->teacher_id) == $teacher->id)>{{ $teacher->name }} - {{ $teacher->email }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('teacher_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="classroom_id" value="Classroom" />
                        <select id="classroom_id" name="classroom_id" class="mt-1 block w-full rounded-md border-gray-300">
                            <option value="">Select a classroom</option>
                            @foreach ($classrooms as $classroom)
                                <option value="{{ $classroom->id }}" @selected(old('classroom_id', $assignment->classroom_id) == $classroom->id)>{{ $classroom->name }} - {{ $classroom->subjects->pluck('name')->join(', ') }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('classroom_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="subject_id" value="Subject" />
                        <select id="subject_id" name="subject_id" class="mt-1 block w-full rounded-md border-gray-300">
                            <option value="">Select a subject</option>
                            @foreach ($classrooms as $classroom)
                                @foreach ($classroom->subjects as $subject)
                                    <option value="{{ $subject->id }}" @selected(old('subject_id', $assignment->subject_id) == $subject->id)>{{ $subject->name }} - {{ $classroom->name }}</option>
                                @endforeach
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('subject_id')" class="mt-2" />
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.teacher-assignments.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700">Cancel</a>
                        <x-primary-button>{{ $assignment->exists ? 'Update Assignment' : 'Create Assignment' }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
