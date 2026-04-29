<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Classrooms</h2>
                <p class="mt-1 text-sm text-gray-500">Manage school classrooms and connect them to level and academic year.</p>
            </div>
            <a href="{{ route('admin.classrooms.create') }}" class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white">
                New Classroom
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-xl bg-white shadow-sm">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">School</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Academic Year</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Level</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Subjects</th>
                            <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($classrooms as $classroom)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $classroom->school?->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $classroom->academicYear?->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $classroom->level?->code ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $classroom->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $classroom->subjects->pluck('name')->join(', ') ?: 'No subjects linked' }}</td>
                                <td class="px-4 py-3 text-right text-sm">
                                    <a href="{{ route('admin.classrooms.edit', $classroom->id) }}" class="text-blue-600 hover:text-blue-500">Edit</a>
                                    <form method="POST" action="{{ route('admin.classrooms.destroy', $classroom->id) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="ml-3 text-red-600 hover:text-red-500" onclick="return confirm('Delete this classroom?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">No classrooms found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
