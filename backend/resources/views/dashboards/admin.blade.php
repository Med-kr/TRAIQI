<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Administration Dashboard
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Global overview of the platform.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto space-y-6 sm:px-6 lg:px-8">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <p class="text-sm text-gray-500">Users</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">{{ $usersCount }}</p>
                </div>
                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <p class="text-sm text-gray-500">Evaluations</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">{{ $evaluationsCount }}</p>
                </div>
                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <p class="text-sm text-gray-500">Grades</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">{{ $gradesCount }}</p>
                </div>
                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <p class="text-sm text-gray-500">Notifications</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">{{ $notificationsCount }}</p>
                </div>
                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <p class="text-sm text-gray-500">Review requests</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">{{ $reviewRequestsCount }}</p>
                </div>
            </div>

            <div class="rounded-xl bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Latest users</h3>
                    <a href="{{ route('administration.users.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                        View all
                    </a>
                </div>

                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Email</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Role</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach ($users as $user)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $user->name }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $user->email }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $user->primaryRole() ?? 'No role' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
