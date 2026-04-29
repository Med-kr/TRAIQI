<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $level->exists ? 'Edit Level' : 'Create Level' }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="rounded-xl bg-white p-6 shadow-sm">
                <form method="POST" action="{{ $level->exists ? route('admin.levels.update', $level->id) : route('admin.levels.store') }}" class="space-y-6">
                    @csrf
                    @if ($level->exists)
                        @method('PUT')
                    @endif

                    @if (auth()->user()->hasRole('super_admin'))
                        <div>
                            <x-input-label for="school_id" value="School" />
                            <select id="school_id" name="school_id" class="mt-1 block w-full rounded-md border-gray-300">
                                <option value="">Select a school</option>
                                @foreach ($schools as $school)
                                    <option value="{{ $school->id }}" @selected(old('school_id', $level->school_id) == $school->id)>{{ $school->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('school_id')" class="mt-2" />
                        </div>
                    @endif

                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <x-input-label for="code" value="Code" />
                            <x-text-input id="code" name="code" type="text" class="mt-1 block w-full" :value="old('code', $level->code)" placeholder="1AP" required />
                            <x-input-error :messages="$errors->get('code')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="order" value="Display Order" />
                            <x-text-input id="order" name="order" type="number" min="1" class="mt-1 block w-full" :value="old('order', $level->order)" required />
                            <x-input-error :messages="$errors->get('order')" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="name" value="Name" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $level->name)" placeholder="First Year Primary" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.levels.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700">Cancel</a>
                        <x-primary-button>{{ $level->exists ? 'Update Level' : 'Create Level' }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
