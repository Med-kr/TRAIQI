<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Schools</h2>
                <p class="mt-1 text-sm text-gray-500">Manage the list of schools in the platform.</p>
            </div>
            <a href="{{ route('admin.schools.create') }}" class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white">
                New School
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-xl bg-white shadow-sm">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Users</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Years</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Classrooms</th>
                            <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($schools as $school)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $school->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $school->users_count }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $school->academic_years_count }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $school->classrooms_count }}</td>
                                <td class="px-4 py-3 text-right text-sm">
                                    <a href="{{ route('admin.schools.edit', $school) }}" class="text-blue-600 hover:text-blue-500">Edit</a>
                                    <form method="POST" action="{{ route('admin.schools.destroy', $school) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="ml-3 text-red-600 hover:text-red-500" onclick="return confirm('Delete this school?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">No schools found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
