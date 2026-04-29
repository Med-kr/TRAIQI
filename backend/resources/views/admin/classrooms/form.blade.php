<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $classroom->exists ? 'Edit Classroom' : 'Create Classroom' }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="rounded-xl bg-white p-6 shadow-sm">
                <form method="POST" action="{{ $classroom->exists ? route('admin.classrooms.update', $classroom->id) : route('admin.classrooms.store') }}" class="space-y-6">
                    @csrf
                    @if ($classroom->exists)
                        @method('PUT')
                    @endif

                    @if (auth()->user()->hasRole('super_admin'))
                        <div>
                            <x-input-label for="school_id" value="School" />
                            <select id="school_id" name="school_id" class="mt-1 block w-full rounded-md border-gray-300">
                                <option value="">Select a school</option>
                                @foreach ($schools as $school)
                                    <option value="{{ $school->id }}" @selected(old('school_id', $classroom->school_id) == $school->id)>{{ $school->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('school_id')" class="mt-2" />
                        </div>
                    @endif

                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <x-input-label for="academic_year_id" value="Academic Year" />
                            <select id="academic_year_id" name="academic_year_id" class="mt-1 block w-full rounded-md border-gray-300">
                                <option value="">Select an academic year</option>
                                @foreach ($academicYears as $academicYear)
                                    <option value="{{ $academicYear->id }}" @selected(old('academic_year_id', $classroom->academic_year_id) == $academicYear->id)>{{ $academicYear->name }} - {{ $academicYear->school?->name ?? 'School' }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('academic_year_id')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="level_id" value="Level" />
                            <select id="level_id" name="level_id" class="mt-1 block w-full rounded-md border-gray-300">
                                <option value="">Select a level</option>
                                @foreach ($levels as $level)
                                    <option value="{{ $level->id }}" @selected(old('level_id', $classroom->level_id) == $level->id)>{{ $level->code }} - {{ $level->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('level_id')" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="name" value="Classroom Name" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $classroom->name)" placeholder="3AC-1" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="subject_ids" value="Subjects Linked To This Classroom" />
                        <select id="subject_ids" name="subject_ids[]" multiple class="mt-1 block w-full rounded-md border-gray-300">
                            @php
                                $selectedSubjects = old('subject_ids', $classroom->exists ? $classroom->subjects->pluck('id')->all() : []);
                            @endphp
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}" @selected(in_array($subject->id, $selectedSubjects))>
                                    {{ $subject->name }}{{ $subject->school ? ' - ' . $subject->school->name : '' }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-2 text-xs text-gray-500">Hold Ctrl or Cmd to select more than one subject.</p>
                        <x-input-error :messages="$errors->get('subject_ids')" class="mt-2" />
                        <x-input-error :messages="$errors->get('subject_ids.*')" class="mt-2" />
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.classrooms.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700">Cancel</a>
                        <x-primary-button>{{ $classroom->exists ? 'Update Classroom' : 'Create Classroom' }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
