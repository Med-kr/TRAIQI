<x-app-layout>
    <x-slot name="header">
        <div>
            <span class="chip" x-text="$store.traiqi.t('nav.users')"></span>
            <h1 class="text-title mt-4" x-text="$store.traiqi.t('nav.users')"></h1>
        </div>
    </x-slot>

    <section class="surface-panel">
        <div class="table-shell">
            <table class="table">
                <thead>
                    <tr>
                        <th x-text="$store.traiqi.t('table.name')"></th>
                        <th x-text="$store.traiqi.t('table.email')"></th>
                        <th x-text="$store.traiqi.t('table.role')"></th>
                        <th x-text="$store.traiqi.t('table.date')"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->primaryRole() ?? 'No role' }}</td>
                            <td>{{ $user->created_at?->format('Y-m-d H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</x-app-layout>
