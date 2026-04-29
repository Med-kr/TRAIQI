@php
    $subjectProgress = $grades
        ->groupBy(fn ($grade) => $grade->evaluation?->subject?->name ?? 'N/A')
        ->map(fn ($items) => round(($items->avg('value') / 20) * 100))
        ->take(5);

    $resourceCards = [
        ['title' => 'Maths Lab', 'copy' => 'Fiches de renforcement et quiz ciblés.'],
        ['title' => 'Langues', 'copy' => 'Compréhension orale et écrite avec défis courts.'],
        ['title' => 'Sciences', 'copy' => 'Capsules et expériences guidées.'],
    ];
@endphp
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <span class="chip" x-text="$store.traiqi.t('roles.student')"></span>
                <h1 class="text-title mt-4">
                    <span x-text="$store.traiqi.t('dashboard.hello')"></span> {{ auth()->user()->name }} 👋
                </h1>
                <p class="mt-3 text-soft">
                    <span x-text="$store.traiqi.t('dashboard.week')"></span> {{ now()->format('d/m/Y') }}
                </p>
            </div>
            <div class="glass-card max-w-md">
                <p class="text-label" x-text="$store.traiqi.t('app.slogan')"></p>
                <div class="mt-4 progress-track"><div class="progress-bar" style="width: 78%"></div></div>
                <p class="mt-3 text-soft">78%</p>
            </div>
        </div>
    </x-slot>

    <div class="grid gap-6">
        <section class="stats-grid">
            <article class="metric-card">
                <p class="text-label" x-text="$store.traiqi.t('dashboard.average')"></p>
                <p class="stat-number">{{ $averageGrade !== null ? number_format($averageGrade, 1) : '--' }}@if($averageGrade !== null)/20@endif</p>
            </article>
            <article class="metric-card">
                <p class="text-label" x-text="$store.traiqi.t('dashboard.next_exams')"></p>
                <p class="stat-number">{{ $evaluations->take(3)->count() }}</p>
            </article>
            <article class="metric-card">
                <p class="text-label" x-text="$store.traiqi.t('dashboard.month_goal')"></p>
                <div class="mt-5 progress-track"><div class="progress-bar" style="width: 78%"></div></div>
                <p class="mt-3 text-soft">78%</p>
            </article>
            <article class="metric-card">
                <p class="text-label" x-text="$store.traiqi.t('table.classroom')"></p>
                <p class="stat-number" style="font-size:2rem">{{ $student?->classroom?->name ?? 'N/A' }}</p>
            </article>
        </section>

        <section class="dashboard-grid">
            <article class="surface-panel card col-span-12 lg:col-span-7">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-section" x-text="$store.traiqi.t('dashboard.subject_progress')"></h2>
                    <span class="badge success">{{ $grades->count() }} notes</span>
                </div>
                <div class="mini-bars mt-6">
                    @forelse ($subjectProgress as $subject => $progress)
                        <div class="mini-bar-row">
                            <div class="flex items-center justify-between gap-3">
                                <span>{{ $subject }}</span>
                                <span class="text-soft">{{ $progress }}%</span>
                            </div>
                            <div class="progress-track"><div class="progress-bar" style="width: {{ $progress }}%"></div></div>
                        </div>
                    @empty
                        <div class="empty-state" x-text="$store.traiqi.t('common.no_data')"></div>
                    @endforelse
                </div>
            </article>

            <article class="surface-panel card col-span-12 lg:col-span-5">
                <h2 class="text-section" x-text="$store.traiqi.t('dashboard.recommended_resources')"></h2>
                <div class="mt-6 space-y-4">
                    @foreach ($resourceCards as $resource)
                        <div class="alert-card">
                            <p class="font-semibold">{{ $resource['title'] }}</p>
                            <p class="mt-2 text-soft">{{ $resource['copy'] }}</p>
                        </div>
                    @endforeach
                </div>
            </article>

            <article class="surface-panel col-span-12">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-section" x-text="$store.traiqi.t('dashboard.latest_grades')"></h2>
                    <span class="badge">{{ $grades->count() }}</span>
                </div>

                @if ($grades->isEmpty())
                    <div class="empty-state" x-text="$store.traiqi.t('common.no_data')"></div>
                @else
                    <div class="table-shell mt-6">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th x-text="$store.traiqi.t('nav.evaluations')"></th>
                                    <th x-text="$store.traiqi.t('table.subject')"></th>
                                    <th x-text="$store.traiqi.t('table.date')"></th>
                                    <th x-text="$store.traiqi.t('table.grade')"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($grades->take(8) as $grade)
                                    <tr>
                                        <td>{{ $grade->evaluation?->title ?? 'N/A' }}</td>
                                        <td>{{ $grade->evaluation?->subject?->name ?? 'N/A' }}</td>
                                        <td>{{ $grade->evaluation?->date ?? 'N/A' }}</td>
                                        <td><span class="badge {{ $grade->value >= 14 ? 'success' : ($grade->value >= 10 ? 'warning' : 'danger') }}">{{ $grade->value }}/20</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </article>
        </section>
    </div>
</x-app-layout>
