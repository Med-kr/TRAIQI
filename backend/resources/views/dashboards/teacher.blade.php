<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <span class="chip" x-text="$store.traiqi.t('roles.teacher')"></span>
                <h1 class="text-title mt-4">{{ auth()->user()->name }}</h1>
                <p class="mt-3 text-soft" x-text="$store.traiqi.t('dashboard.teacher_workspace')"></p>
            </div>
            <div class="glass-card">
                <p class="text-label" x-text="$store.traiqi.t('dashboard.quick_grade_entry')"></p>
                <p class="mt-3 text-soft">{{ $remainingCopiesCount }} copies restantes à corriger</p>
            </div>
        </div>
    </x-slot>

    <div class="grid gap-6">
        <section class="stats-grid">
            <article class="metric-card">
                <p class="text-label">Assignments</p>
                <p class="stat-number">{{ $assignmentsCount }}</p>
            </article>
            <article class="metric-card">
                <p class="text-label" x-text="$store.traiqi.t('nav.evaluations')"></p>
                <p class="stat-number">{{ $evaluationsCount }}</p>
            </article>
            <article class="metric-card">
                <p class="text-label">Grades</p>
                <p class="stat-number">{{ $gradesCount }}</p>
            </article>
            <article class="metric-card">
                <p class="text-label">Class average</p>
                <p class="stat-number">{{ number_format((float) $classAverage, 2) }}</p>
            </article>
        </section>

        <section class="dashboard-grid">
            <article class="surface-panel card col-span-12 lg:col-span-5">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-section" x-text="$store.traiqi.t('dashboard.class_health')"></h2>
                    <span class="badge">{{ $trackedStudentsCount }}</span>
                </div>

                <div class="mt-6 space-y-4">
                    @forelse ($assignments as $assignment)
                        @php
                            $studentsCount = $assignment->classroom?->students?->count() ?? 0;
                            $healthPercent = $studentsCount > 0 ? max(22, min(96, 100 - (($studentsInDifficultyCount / max(1, $trackedStudentsCount)) * 100))) : 0;
                        @endphp
                        <div class="alert-card {{ $healthPercent < 55 ? 'is-warning' : '' }}">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="font-semibold">{{ $assignment->classroom?->name ?? 'N/A' }}</p>
                                    <p class="mt-1 text-soft">{{ $assignment->subject?->name ?? 'N/A' }}</p>
                                </div>
                                <span class="badge {{ $healthPercent < 55 ? 'warning' : 'success' }}">{{ $studentsCount }} élèves</span>
                            </div>
                            <div class="mt-4 progress-track"><div class="progress-bar" style="width: {{ $healthPercent }}%"></div></div>
                        </div>
                    @empty
                        <div class="empty-state" x-text="$store.traiqi.t('common.no_data')"></div>
                    @endforelse
                </div>
            </article>

            <article class="surface-panel card col-span-12 lg:col-span-7">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-section" x-text="$store.traiqi.t('dashboard.quick_grade_entry')"></h2>
                    <span class="badge success">{{ $remainingCopiesCount }}</span>
                </div>

                @if ($assignments->isEmpty())
                    <div class="empty-state" x-text="$store.traiqi.t('common.no_data')"></div>
                @else
                    <form method="POST" action="{{ route('teacher.evaluations.store') }}" class="mt-6 grid gap-4 md:grid-cols-2">
                        @csrf
                        <div class="md:col-span-2">
                            <label class="text-label">Title</label>
                            <x-text-input id="title" name="title" type="text" class="mt-2 block w-full" :value="old('title')" />
                        </div>
                        <div>
                            <label class="text-label" x-text="$store.traiqi.t('table.classroom')"></label>
                            <select id="classroom_id" name="classroom_id" class="select mt-2">
                                @foreach ($assignments as $assignment)
                                    <option value="{{ $assignment->classroom_id }}">{{ $assignment->classroom?->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-label" x-text="$store.traiqi.t('table.subject')"></label>
                            <select id="subject_id" name="subject_id" class="select mt-2">
                                @foreach ($assignments as $assignment)
                                    <option value="{{ $assignment->subject_id }}">{{ $assignment->subject?->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-label" x-text="$store.traiqi.t('table.date')"></label>
                            <x-text-input id="date" name="date" type="date" class="mt-2 block w-full" :value="old('date')" />
                        </div>
                        <div>
                            <label class="text-label">Type</label>
                            <select id="type" name="type" class="select mt-2">
                                <option value="devoir">Devoir</option>
                                <option value="examen">Examen</option>
                                <option value="controle_continu">Controle continu</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <button type="submit" class="button-primary">Create evaluation</button>
                        </div>
                    </form>
                @endif
            </article>

            <article class="surface-panel col-span-12">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-section" x-text="$store.traiqi.t('nav.evaluations')"></h2>
                    <span class="badge">{{ $evaluationsCount }}</span>
                </div>
                <div class="table-shell mt-6">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Type</th>
                                <th x-text="$store.traiqi.t('table.classroom')"></th>
                                <th x-text="$store.traiqi.t('table.subject')"></th>
                                <th x-text="$store.traiqi.t('table.date')"></th>
                                <th>Status</th>
                                <th x-text="$store.traiqi.t('table.action')"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($evaluations as $evaluation)
                                <tr>
                                    <td>{{ $evaluation->title }}</td>
                                    <td>{{ str_replace('_', ' ', ucfirst($evaluation->type)) }}</td>
                                    <td>{{ $evaluation->classroom?->name ?? 'N/A' }}</td>
                                    <td>{{ $evaluation->subject?->name ?? 'N/A' }}</td>
                                    <td>{{ $evaluation->date }}</td>
                                    <td>
                                        @if ($evaluation->is_locked)
                                            <span class="badge warning">Locked</span>
                                        @elseif ($evaluation->is_published)
                                            <span class="badge success">Published</span>
                                        @else
                                            <span class="badge">Draft</span>
                                        @endif
                                    </td>
                                    <td><a class="button-secondary" href="{{ route('teacher.grades.show', $evaluation->id) }}" x-text="$store.traiqi.t('common.open')"></a></td>
                                </tr>
                            @empty
                                <tr><td colspan="7" x-text="$store.traiqi.t('common.no_data')"></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>
        </section>
    </div>
</x-app-layout>
