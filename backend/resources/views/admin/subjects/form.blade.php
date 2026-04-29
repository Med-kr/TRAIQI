<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $subject->exists ? 'Edit Subject' : 'Create Subject' }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="rounded-xl bg-white p-6 shadow-sm">
                <form method="POST" action="{{ $subject->exists ? route('admin.subjects.update', $subject->id) : route('admin.subjects.store') }}" class="space-y-6">
                    @csrf
                    @if ($subject->exists)
                        @method('PUT')
                    @endif

                    @if (auth()->user()->hasRole('super_admin'))
                        <div>
                            <x-input-label for="school_id" value="School" />
                            <select id="school_id" name="school_id" class="mt-1 block w-full rounded-md border-gray-300">
                                <option value="">Select a school</option>
                                @foreach ($schools as $school)
                                    <option value="{{ $school->id }}" @selected(old('school_id', $subject->school_id) == $school->id)>{{ $school->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('school_id')" class="mt-2" />
                        </div>
                    @endif

                    <div>
                        <x-input-label for="name" value="Subject Name" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $subject->name)" placeholder="Mathematics" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.subjects.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700">Cancel</a>
                        <x-primary-button>{{ $subject->exists ? 'Update Subject' : 'Create Subject' }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
