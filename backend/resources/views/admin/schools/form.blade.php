<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $school->exists ? 'Edit School' : 'Create School' }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="rounded-xl bg-white p-6 shadow-sm">
                <form method="POST" action="{{ $school->exists ? route('admin.schools.update', $school) : route('admin.schools.store') }}" class="space-y-6">
                    @csrf
                    @if ($school->exists)
                        @method('PUT')
                    @endif

                    <div>
                        <x-input-label for="name" value="School Name" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $school->name)" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.schools.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700">Cancel</a>
                        <x-primary-button>{{ $school->exists ? 'Update School' : 'Create School' }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
