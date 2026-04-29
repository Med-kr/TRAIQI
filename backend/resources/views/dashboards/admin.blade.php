<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <span class="chip" x-text="$store.traiqi.t('roles.admin')"></span>
                <h1 class="text-title mt-4" x-text="$store.traiqi.t('dashboard.admin_hub')"></h1>
                <p class="mt-3 text-soft" x-text="$store.traiqi.t('dashboard.system_state')"></p>
            </div>
            <div class="glass-card">
                <p class="text-label" x-text="$store.traiqi.t('dashboard.active_accounts')"></p>
                <p class="stat-number">{{ $usersCount }}</p>
            </div>
        </div>
    </x-slot>

    <div class="grid gap-6">
        <section class="stats-grid">
            <article class="metric-card">
                <p class="text-label" x-text="$store.traiqi.t('dashboard.active_accounts')"></p>
                <p class="stat-number">{{ $usersCount }}</p>
            </article>
            <article class="metric-card">
                <p class="text-label" x-text="$store.traiqi.t('dashboard.generated_classes')"></p>
                <p class="stat-number">{{ $activeClassesCount }}</p>
            </article>
            <article class="metric-card">
                <p class="text-label">Imports</p>
                <p class="stat-number">{{ $importsCount }}</p>
            </article>
            <article class="metric-card">
                <p class="text-label">Schools</p>
                <p class="stat-number">{{ $schoolsCount }}</p>
            </article>
        </section>

        <section class="dashboard-grid">
            <article class="surface-panel card col-span-12 lg:col-span-7">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-section" x-text="$store.traiqi.t('dashboard.import_center')"></h2>
                    <a href="{{ route('admin.imports.index') }}" class="button-secondary" x-text="$store.traiqi.t('common.manage')"></a>
                </div>

                <form method="GET" action="{{ route('admin.imports.index') }}" class="mt-6">
                    <label class="dropzone">
                        <input type="file" aria-hidden="true" tabindex="-1">
                        <span class="icon-badge mx-auto"><x-traiqi-icon name="upload" class="h-5 w-5" /></span>
                        <p class="mt-4 text-section" x-text="$store.traiqi.t('dashboard.dropzone_title')"></p>
                        <p class="mt-3 text-soft" x-text="$store.traiqi.t('dashboard.dropzone_copy')"></p>
                    </label>
                </form>

                <div class="overview-grid mt-6">
                    <div class="data-card">
                        <p class="text-label">Students</p>
                        <p class="stat-number">{{ $studentsCount }}</p>
                    </div>
                    <div class="data-card">
                        <p class="text-label">Teachers</p>
                        <p class="stat-number">{{ $teachersCount }}</p>
                    </div>
                    <div class="data-card">
                        <p class="text-label">Parents</p>
                        <p class="stat-number">{{ $parentsCount }}</p>
                    </div>
                </div>
            </article>

            <article class="surface-panel card col-span-12 lg:col-span-5">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-section" x-text="$store.traiqi.t('dashboard.system_state')"></h2>
                    <span class="badge success" x-text="$store.traiqi.t('status.good')"></span>
                </div>

                <div class="mt-6 space-y-4">
                    <div class="alert-card">
                        <div class="flex items-center justify-between gap-3">
                            <span x-text="$store.traiqi.t('dashboard.generated_classes')"></span>
                            <strong>{{ $activeClassesCount }}</strong>
                        </div>
                    </div>
                    <div class="alert-card is-warning">
                        <div class="flex items-center justify-between gap-3">
                            <span>Review requests</span>
                            <strong>{{ $reviewRequestsCount }}</strong>
                        </div>
                    </div>
                    <div class="alert-card">
                        <div class="flex items-center justify-between gap-3">
                            <span>Notifications</span>
                            <strong>{{ $notificationsCount }}</strong>
                        </div>
                    </div>
                    <div class="alert-card">
                        <div class="flex items-center justify-between gap-3">
                            <span>Evaluations</span>
                            <strong>{{ $evaluationsCount }}</strong>
                        </div>
                    </div>
                </div>
            </article>

            <article class="surface-panel col-span-12 lg:col-span-7">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-section">Latest users</h2>
                    <a href="{{ route('admin.user-management.index') }}" class="button-secondary" x-text="$store.traiqi.t('common.view_all')"></a>
                </div>

                <div class="table-shell mt-6">
                    <table class="table">
                        <thead>
                            <tr>
                                <th x-text="$store.traiqi.t('table.name')"></th>
                                <th x-text="$store.traiqi.t('table.email')"></th>
                                <th x-text="$store.traiqi.t('table.role')"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->primaryRole() ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </article>

            <article class="surface-panel col-span-12 lg:col-span-5">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-section" x-text="$store.traiqi.t('dashboard.latest_imports')"></h2>
                    <a href="{{ route('admin.imports.index') }}" class="button-secondary" x-text="$store.traiqi.t('common.open')"></a>
                </div>

                <div class="mt-6 space-y-4">
                    @forelse ($recentImports as $import)
                        <div class="alert-card {{ $import->status === 'completed' ? '' : 'is-warning' }}">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="font-semibold">{{ ucfirst($import->type) }}</p>
                                    <p class="mt-1 text-soft">{{ $import->file_name }}</p>
                                </div>
                                <span class="badge {{ $import->status === 'completed' ? 'success' : 'warning' }}">{{ $import->status }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state" x-text="$store.traiqi.t('common.no_data')"></div>
                    @endforelse
                </div>
            </article>
        </section>
    </div>
</x-app-layout>
