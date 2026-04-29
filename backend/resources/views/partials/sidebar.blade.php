@if (!empty($adminShell))
    @php
        $adminLinks = [
            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'dashboard'],
            ['label' => 'Ecoles', 'route' => 'admin.schools.index', 'icon' => 'book'],
            ['label' => 'Admins ecoles', 'route' => 'admin.user-management.index', 'icon' => 'users'],
            ['label' => 'Rapports', 'route' => 'admin.reports.index', 'icon' => 'chart'],
            ['label' => 'Parametres', 'route' => 'profile.edit', 'icon' => 'settings'],
        ];
    @endphp

    <aside
        class="admin-sidebar fixed inset-y-0 start-0 z-50 flex w-[18rem] max-w-[88vw] -translate-x-full flex-col border-e border-white/10 px-4 py-5 text-white shadow-[0_28px_80px_rgba(8,59,130,0.38)] transition duration-300 lg:sticky lg:translate-x-0"
        :class="$store.traiqi.sidebarOpen ? 'translate-x-0' : ''"
    >
        <div class="flex items-center justify-between gap-3 pb-6">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white p-1.5 shadow-lg">
                    <x-application-logo class="h-8 w-8" />
                </span>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-white/70">Traiqi | طريقي</p>
                    <p class="mt-1 text-lg font-semibold">Super Admin</p>
                </div>
            </a>

            <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-white/10 lg:hidden" @click="$store.traiqi.closeSidebar()">
                <x-traiqi-icon name="close" class="h-5 w-5" />
            </button>
        </div>

        <div class="rounded-[1.5rem] border border-white/12 bg-white/10 p-4 backdrop-blur">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-white/68">Plateforme nationale</p>
            <p class="mt-3 text-sm leading-7 text-white/86">Pilotage des ecoles, des administrateurs et des activites critiques depuis un seul espace de controle.</p>
        </div>

        <nav class="mt-6 space-y-1">
            @foreach ($adminLinks as $link)
                <a href="{{ route($link['route']) }}" class="admin-sidebar-link {{ request()->routeIs($link['route']) ? 'is-active' : '' }}">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-white/10">
                        <x-traiqi-icon :name="$link['icon']" class="h-5 w-5" />
                    </span>
                    <span>{{ $link['label'] }}</span>
                </a>
            @endforeach
        </nav>

        <div class="mt-auto space-y-4 pt-6">
            <div class="rounded-[1.5rem] border border-white/12 bg-gradient-to-br from-white/12 to-white/6 p-4">
                <p class="text-sm font-semibold">Cadence de supervision</p>
                <p class="mt-2 text-sm leading-7 text-white/74">Suivi des inscriptions, gestion des ecoles, activite des comptes, et operations sensibles.</p>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="admin-sidebar-link w-full justify-start bg-white/10 hover:bg-white/14">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-white/10">
                        <x-traiqi-icon name="close" class="h-5 w-5" />
                    </span>
                    <span>Deconnexion</span>
                </button>
            </form>
        </div>
    </aside>

    <div x-cloak x-show="$store.traiqi.sidebarOpen" class="fixed inset-0 z-40 bg-slate-950/50 lg:hidden" @click="$store.traiqi.closeSidebar()"></div>
@else
    @php
        $user = auth()->user();
        $role = $user?->primaryRole();
        $rolePanel = match ($role) {
            'student' => [
                'title' => __('ui.roles.student'),
                'message' => 'Goals, grades, and opportunities are organized here to keep momentum high.',
            ],
            'parent' => [
                'title' => __('ui.roles.parent'),
                'message' => 'Follow your children, review alerts quickly, and keep communication simple.',
            ],
            'teacher' => [
                'title' => __('ui.roles.teacher'),
                'message' => 'Evaluations, grading flow, and classroom tracking stay accessible in one space.',
            ],
            'school_admin', 'super_admin' => [
                'title' => __('ui.roles.admin'),
                'message' => 'Manage operations, users, and reporting with a structured institutional overview.',
            ],
            default => [
                'title' => __('ui.app.name'),
                'message' => __('ui.app.sidebar_message'),
            ],
        };
        $links = collect([
            ['route' => 'student.dashboard', 'label' => __('ui.nav.student_space'), 'icon' => 'target', 'roles' => ['student']],
            ['route' => 'parent.dashboard', 'label' => __('ui.nav.parent_space'), 'icon' => 'users', 'roles' => ['parent']],
            ['route' => 'teacher.dashboard', 'label' => __('ui.nav.teacher_space'), 'icon' => 'book', 'roles' => ['teacher']],
            ['route' => 'admin.dashboard', 'label' => __('ui.nav.admin_space'), 'icon' => 'shield', 'roles' => ['school_admin', 'super_admin']],
            ['route' => 'grades.index', 'label' => __('ui.nav.grades'), 'icon' => 'grades', 'roles' => ['student', 'parent', 'teacher', 'school_admin', 'super_admin']],
            ['route' => 'revision-requests.index', 'label' => __('ui.nav.revision_requests'), 'icon' => 'clipboard', 'roles' => ['student', 'parent', 'teacher', 'school_admin', 'super_admin']],
            ['route' => 'notifications.index', 'label' => __('ui.nav.notifications'), 'icon' => 'bell', 'roles' => ['student', 'parent', 'teacher', 'school_admin', 'super_admin']],
            ['route' => 'opportunities.index', 'label' => __('ui.nav.opportunities'), 'icon' => 'sparkles', 'roles' => ['student', 'parent', 'teacher', 'school_admin', 'super_admin']],
            ['route' => 'portfolio.index', 'label' => __('ui.nav.portfolio'), 'icon' => 'briefcase', 'roles' => ['student', 'teacher']],
            ['route' => 'admin.reports.index', 'label' => __('ui.nav.reports'), 'icon' => 'chart', 'roles' => ['school_admin', 'super_admin']],
            ['route' => 'help-center.index', 'label' => __('ui.nav.help_center'), 'icon' => 'life-buoy', 'roles' => ['student', 'parent', 'teacher', 'school_admin', 'super_admin']],
        ])->filter(fn ($link) => in_array($role, $link['roles'], true) && \Illuminate\Support\Facades\Route::has($link['route']));
    @endphp
    <aside class="fixed inset-y-0 start-0 z-40 w-[18rem] max-w-[88vw] -translate-x-full border-e border-[color:var(--line)] bg-[color:var(--surface)] px-4 py-5 shadow-soft transition duration-300 lg:sticky lg:translate-x-0"
        :class="[$store.traiqi.sidebarOpen ? 'translate-x-0' : '', $store.traiqi.sidebarCollapsed ? 'lg:w-[6.25rem]' : '']">
        <div class="flex h-full flex-col">
            <div class="flex items-center justify-between gap-3 pb-6">
                <a href="{{ route('home') }}" class="flex items-center gap-3 overflow-hidden">
                    <div class="rounded-2xl bg-white/90 p-2 shadow-soft dark:bg-[color:var(--surface-strong)]">
                        <x-application-logo class="h-11 w-11 shrink-0" />
                    </div>
                    <div x-show="! $store.traiqi.sidebarCollapsed" class="min-w-0">
                        <p class="text-label">{{ __('ui.app.tagline') }}</p>
                        <p class="truncate text-section">{{ __('ui.app.name') }}</p>
                    </div>
                </a>

                <div class="flex items-center gap-2">
                    <button type="button" class="icon-button hidden lg:inline-flex" @click="$store.traiqi.toggleSidebarMode()">
                        <x-traiqi-icon name="panel" class="h-5 w-5" />
                    </button>
                    <button type="button" class="icon-button lg:hidden" @click="$store.traiqi.closeSidebar()">
                        <x-traiqi-icon name="close" class="h-5 w-5" />
                    </button>
                </div>
            </div>

            <div class="surface-card mb-5 overflow-hidden p-4" x-show="! $store.traiqi.sidebarCollapsed">
                <p class="text-label">{{ $rolePanel['title'] }}</p>
                <p class="mt-3 text-sm leading-7 text-soft">{{ $rolePanel['message'] }}</p>
            </div>

            <nav class="space-y-1 overflow-y-auto">
                @foreach($links as $link)
                    <a href="{{ route($link['route']) }}"
                        class="sidebar-link {{ request()->routeIs($link['route']) ? 'is-active' : '' }}">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[color:rgba(11,78,162,0.08)]">
                            <x-traiqi-icon :name="$link['icon']" class="h-5 w-5" />
                        </span>
                        <span x-show="! $store.traiqi.sidebarCollapsed">{{ $link['label'] }}</span>
                    </a>
                @endforeach
            </nav>

            <div class="mt-auto pt-5">
                <a href="{{ route('help-center.index') }}" class="surface-card flex items-center gap-3 p-4">
                    <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-traiqi-blue to-traiqi-green text-white">
                        <x-traiqi-icon name="life-buoy" class="h-5 w-5" />
                    </span>
                    <div x-show="! $store.traiqi.sidebarCollapsed">
                        <p class="font-semibold">{{ __('ui.help.need_support') }}</p>
                        <p class="text-sm text-soft">{{ __('ui.help.support_copy') }}</p>
                    </div>
                </a>
            </div>
        </div>
    </aside>

    <div x-cloak x-show="$store.traiqi.sidebarOpen" class="fixed inset-0 z-30 bg-slate-950/40 lg:hidden" @click="$store.traiqi.closeSidebar()"></div>
@endif
