<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $managedUser->exists ? 'Edit User' : 'Create User' }}
        </h2>
    </x-slot>

    @php
        $role = old('role', $managedUser->primaryRole());
    @endphp

    <div class="py-10">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="rounded-xl bg-white p-6 shadow-sm">
                <form method="POST" action="{{ $managedUser->exists ? route('admin.user-management.update', $managedUser->id) : route('admin.user-management.store') }}" class="grid gap-4 md:grid-cols-2">
                    @csrf
                    @if ($managedUser->exists)
                        @method('PUT')
                    @endif

                    @if (auth()->user()->hasRole('super_admin'))
                        <div class="md:col-span-2">
                            <x-input-label for="school_id" value="School" />
                            <select id="school_id" name="school_id" class="mt-1 block w-full rounded-md border-gray-300">
                                @foreach (\App\Models\School::query()->orderBy('name')->get() as $school)
                                    <option value="{{ $school->id }}" @selected(old('school_id', $managedUser->school_id ?? auth()->user()->school_id) == $school->id)>{{ $school->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('school_id')" class="mt-2" />
                        </div>
                    @endif

                    <div>
                        <x-input-label for="role" value="Role" />
                        <select id="role" name="role" class="mt-1 block w-full rounded-md border-gray-300" {{ $managedUser->exists ? 'disabled' : '' }}>
                            @foreach ($assignableRoles as $assignableRole)
                                <option value="{{ $assignableRole }}" @selected($role === $assignableRole)>{{ str_replace('_', ' ', ucfirst($assignableRole)) }}</option>
                            @endforeach
                        </select>
                        @if ($managedUser->exists)
                            <input type="hidden" name="role" value="{{ $role }}">
                        @endif
                    </div>

                    <div>
                        <x-input-label for="name" value="Name" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $managedUser->name)" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="email" value="Email" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $managedUser->email)" required :disabled="$managedUser->exists" />
                        @if ($managedUser->exists)
                            <input type="hidden" name="email" value="{{ $managedUser->email }}">
                            <p class="mt-2 text-xs text-gray-500">Email changes are disabled for now to avoid breaking imported account links.</p>
                        @endif
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="phone" value="Phone" />
                        <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $managedUser->studentProfile?->phone ?? $managedUser->parentProfile?->phone ?? $managedUser->teacherProfile?->phone ?? $managedUser->administrationProfile?->phone)" />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="level_code" value="Level code" />
                        <x-text-input id="level_code" name="level_code" type="text" class="mt-1 block w-full" :value="old('level_code', $managedUser->level?->code)" />
                        <x-input-error :messages="$errors->get('level_code')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="level_name" value="Level name" />
                        <x-text-input id="level_name" name="level_name" type="text" class="mt-1 block w-full" :value="old('level_name', $managedUser->level?->name)" />
                        <x-input-error :messages="$errors->get('level_name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="classroom_id" value="Classroom (student)" />
                        <select id="classroom_id" name="classroom_id" class="mt-1 block w-full rounded-md border-gray-300">
                            <option value="">No classroom</option>
                            @foreach ($classrooms as $classroom)
                                <option value="{{ $classroom->id }}" @selected(old('classroom_id', $managedUser->studentProfile?->classroom_id) == $classroom->id)>{{ $classroom->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label for="specialty" value="Specialty (teacher)" />
                        <x-text-input id="specialty" name="specialty" type="text" class="mt-1 block w-full" :value="old('specialty', $managedUser->teacherProfile?->specialty)" />
                        <x-input-error :messages="$errors->get('specialty')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="position" value="Position (administration)" />
                        <x-text-input id="position" name="position" type="text" class="mt-1 block w-full" :value="old('position', $managedUser->administrationProfile?->position)" />
                        <x-input-error :messages="$errors->get('position')" class="mt-2" />
                    </div>

                    <div class="md:col-span-2">
                        <label class="flex items-center gap-3 rounded-lg border border-gray-200 px-4 py-3 text-sm text-gray-700">
                            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $managedUser->exists ? $managedUser->is_active : true))>
                            <span>Account active</span>
                        </label>
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label value="Children (parent)" />
                        <div class="mt-2 grid gap-2 md:grid-cols-2">
                            @foreach ($students as $student)
                                <label class="flex items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700">
                                    <input type="checkbox" name="children[]" value="{{ $student->id }}" @checked(collect(old('children', $managedUser->children->pluck('id') ?? []))->contains($student->id))>
                                    <span>{{ $student->name }} <span class="text-gray-400">({{ $student->email }})</span></span>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('children')" class="mt-2" />
                    </div>

                    <div class="md:col-span-2 flex gap-3">
                        <x-primary-button>{{ $managedUser->exists ? 'Update user' : 'Create user' }}</x-primary-button>
                        <a href="{{ route('admin.user-management.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
