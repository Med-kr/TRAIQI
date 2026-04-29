<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <span class="chip" x-text="$store.traiqi.t('roles.parent')"></span>
                <h1 class="text-title mt-4">{{ auth()->user()->name }}</h1>
                <p class="mt-3 text-soft" x-text="$store.traiqi.t('dashboard.parent_followup')"></p>
            </div>
            <a href="#requests" class="button-primary" x-text="$store.traiqi.t('dashboard.take_appointment')"></a>
        </div>
    </x-slot>

    <div class="grid gap-6">
        <section class="stats-grid">
            <article class="metric-card">
                <p class="text-label" x-text="$store.traiqi.t('dashboard.linked_children')"></p>
                <p class="stat-number">{{ $childrenCount }}</p>
            </article>
            <article class="metric-card">
                <p class="text-label" x-text="$store.traiqi.t('dashboard.alerts')"></p>
                <p class="stat-number">{{ $childrenWithGrades->sum(fn ($entry) => $entry['grades']->where('value', '<', 10)->count()) }}</p>
            </article>
            <article class="metric-card">
                <p class="text-label" x-text="$store.traiqi.t('dashboard.weekly_progress')"></p>
                <div class="mt-5 progress-track"><div class="progress-bar" style="width: 73%"></div></div>
                <p class="mt-3 text-soft">73%</p>
            </article>
            <article class="metric-card">
                <p class="text-label" x-text="$store.traiqi.t('common.status')"></p>
                <span class="badge success" x-text="$store.traiqi.t('status.good')"></span>
            </article>
        </section>

        @forelse ($childrenWithGrades as $entry)
            @php
                $criticalCount = $entry['grades']->where('value', '<', 10)->count();
                $statusClass = $criticalCount > 2 ? 'danger' : ($criticalCount > 0 ? 'warning' : 'success');
                $statusKey = $criticalCount > 2 ? 'status.danger' : ($criticalCount > 0 ? 'status.warning' : 'status.good');
                $weeklyAverage = round((($entry['average'] ?? 0) / 20) * 100);
            @endphp
            <section class="dashboard-grid">
                <article class="surface-panel card col-span-12 lg:col-span-4">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-label" x-text="$store.traiqi.t('roles.student')"></p>
                            <h2 class="text-section mt-2">{{ $entry['child']->name }}</h2>
                            <p class="mt-2 text-soft">{{ $entry['child']->studentProfile?->classroom?->name ?? 'N/A' }}</p>
                        </div>
                        <span class="badge {{ $statusClass }}" x-text="$store.traiqi.t('{{ $statusKey }}')"></span>
                    </div>

                    <div class="mt-6 space-y-4">
                        <div class="alert-card {{ $criticalCount > 2 ? 'is-danger' : ($criticalCount > 0 ? 'is-warning' : '') }}">
                            <p class="font-semibold" x-text="$store.traiqi.t('dashboard.average')"></p>
                            <p class="mt-2">{{ $entry['average'] !== null ? number_format($entry['average'], 1) . '/20' : '--' }}</p>
                        </div>
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="text-soft" x-text="$store.traiqi.t('dashboard.weekly_progress')"></span>
                                <span>{{ $weeklyAverage }}%</span>
                            </div>
                            <div class="mt-3 progress-track"><div class="progress-bar" style="width: {{ $weeklyAverage }}%"></div></div>
                        </div>
                    </div>
                </article>

                <article class="surface-panel card col-span-12 lg:col-span-8" id="requests">
                    <div class="flex items-center justify-between gap-3">
                        <h2 class="text-section" x-text="$store.traiqi.t('dashboard.latest_grades')"></h2>
                        <a href="#appointment-{{ $entry['child']->id }}" class="button-secondary" x-text="$store.traiqi.t('dashboard.take_appointment')"></a>
                    </div>

                    <div class="table-shell mt-6">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th x-text="$store.traiqi.t('nav.evaluations')"></th>
                                    <th x-text="$store.traiqi.t('table.subject')"></th>
                                    <th x-text="$store.traiqi.t('table.grade')"></th>
                                    <th x-text="$store.traiqi.t('dashboard.alerts')"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($entry['grades']->take(6) as $grade)
                                    @php $pendingRequest = $grade->reviewRequests->firstWhere('status', 'pending'); @endphp
                                    <tr>
                                        <td>{{ $grade->evaluation?->title ?? 'N/A' }}</td>
                                        <td>{{ $grade->evaluation?->subject?->name ?? 'N/A' }}</td>
                                        <td><span class="badge {{ $grade->value >= 14 ? 'success' : ($grade->value >= 10 ? 'warning' : 'danger') }}">{{ $grade->value }}/20</span></td>
                                        <td>
                                            @if ($pendingRequest)
                                                <span class="badge warning">Pending</span>
                                            @else
                                                <span class="badge {{ $grade->value < 10 ? 'danger' : 'success' }}" x-text="$store.traiqi.t('{{ $grade->value < 10 ? 'status.warning' : 'status.good' }}')"></span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" x-text="$store.traiqi.t('common.no_data')"></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($entry['grades']->isNotEmpty())
                        <form id="appointment-{{ $entry['child']->id }}" method="POST" action="{{ route('parent.review-requests.store') }}" class="mt-6 grid gap-4">
                            @csrf
                            <input type="hidden" name="student_id" value="{{ $entry['child']->id }}">
                            <input type="hidden" name="grade_id" value="{{ $entry['grades']->first()->id }}">
                            <label class="text-label" x-text="$store.traiqi.t('dashboard.take_appointment')"></label>
                            <textarea name="reason" class="textarea" required>{{ old('student_id') == $entry['child']->id ? old('reason') : '' }}</textarea>
                            <div><button type="submit" class="button-primary" x-text="$store.traiqi.t('dashboard.take_appointment')"></button></div>
                        </form>
                    @endif
                </article>
            </section>
        @empty
            <section class="surface-panel empty-state" x-text="$store.traiqi.t('common.no_data')"></section>
        @endforelse
    </div>
</x-app-layout>
