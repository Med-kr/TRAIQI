<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $academicYear->exists ? 'Edit Academic Year' : 'Create Academic Year' }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="rounded-xl bg-white p-6 shadow-sm">
                <form method="POST" action="{{ $academicYear->exists ? route('admin.academic-years.update', $academicYear->id) : route('admin.academic-years.store') }}" class="space-y-6">
                    @csrf
                    @if ($academicYear->exists)
                        @method('PUT')
                    @endif

                    @if (auth()->user()->hasRole('super_admin'))
                        <div>
                            <x-input-label for="school_id" value="School" />
                            <select id="school_id" name="school_id" class="mt-1 block w-full rounded-md border-gray-300">
                                <option value="">Select a school</option>
                                @foreach ($schools as $school)
                                    <option value="{{ $school->id }}" @selected(old('school_id', $academicYear->school_id) == $school->id)>{{ $school->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('school_id')" class="mt-2" />
                        </div>
                    @endif

                    <div>
                        <x-input-label for="name" value="Academic Year Name" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $academicYear->name)" placeholder="2026-2027" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <x-input-label for="start_date" value="Start Date" />
                            <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full" :value="old('start_date', $academicYear->start_date?->format('Y-m-d'))" required />
                            <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="end_date" value="End Date" />
                            <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block w-full" :value="old('end_date', $academicYear->end_date?->format('Y-m-d'))" required />
                            <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                        </div>
                    </div>

                    <label class="flex items-center gap-3 text-sm text-gray-700">
                        <input type="checkbox" name="is_current" value="1" class="rounded border-gray-300 text-gray-900 shadow-sm" @checked(old('is_current', $academicYear->is_current))>
                        <span>Mark as current academic year</span>
                    </label>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.academic-years.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700">Cancel</a>
                        <x-primary-button>{{ $academicYear->exists ? 'Update Academic Year' : 'Create Academic Year' }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
