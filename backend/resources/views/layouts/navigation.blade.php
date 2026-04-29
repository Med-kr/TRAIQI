<nav x-data="{ open: false }" class="relative z-20 px-4 pt-4 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="traiqi-panel-strong px-4 py-3 sm:px-6">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <a href="{{ route(Auth::user()->dashboardRoute()) }}" class="flex items-center gap-3">
                        <x-application-logo class="h-14 w-14 rounded-full bg-white/10 p-1.5 shadow-xl" />
                        <div class="hidden sm:block">
                            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-sky-100">TRAIQI</p>
                            <p class="font-['Outfit'] text-lg font-semibold text-white">Plateforme educative</p>
                        </div>
                    </a>

                    <div class="hidden flex-wrap items-center gap-2 xl:flex">
                        <x-nav-link :href="route(Auth::user()->dashboardRoute())" :active="request()->routeIs(Auth::user()->dashboardRoute())">
                            {{ __('Dashboard') }}
                        </x-nav-link>

                        @if (Auth::user()->hasRole('teacher'))
                            <x-nav-link :href="route('teacher.evaluations.index')" :active="request()->routeIs('teacher.evaluations.*')">
                                Evaluations
                            </x-nav-link>
                        @endif

                        @if (Auth::user()->hasRole('student'))
                            <x-nav-link :href="route('student.evaluations.index')" :active="request()->routeIs('student.evaluations.*')">
                                My Evaluations
                            </x-nav-link>
                        @endif

                        @if (Auth::user()->hasRole('parent'))
                            <x-nav-link :href="route('parent.dashboard')" :active="request()->routeIs('parent.*')">
                                Children
                            </x-nav-link>
                        @endif

                        @if (Auth::user()->isAdmin())
                            @can('manage schools')
                                <x-nav-link :href="route('admin.schools.index')" :active="request()->routeIs('admin.schools.*')">
                                    Schools
                                </x-nav-link>
                            @endcan
                            @can('manage academic years')
                                <x-nav-link :href="route('admin.academic-years.index')" :active="request()->routeIs('admin.academic-years.*')">
                                    Academic Years
                                </x-nav-link>
                            @endcan
                            @can('manage levels')
                                <x-nav-link :href="route('admin.levels.index')" :active="request()->routeIs('admin.levels.*')">
                                    Levels
                                </x-nav-link>
                            @endcan
                            @can('manage classrooms')
                                <x-nav-link :href="route('admin.classrooms.index')" :active="request()->routeIs('admin.classrooms.*')">
                                    Classrooms
                                </x-nav-link>
                            @endcan
                            @can('manage subjects')
                                <x-nav-link :href="route('admin.subjects.index')" :active="request()->routeIs('admin.subjects.*')">
                                    Subjects
                                </x-nav-link>
                            @endcan
                            @can('manage teacher assignments')
                                <x-nav-link :href="route('admin.teacher-assignments.index')" :active="request()->routeIs('admin.teacher-assignments.*')">
                                    Teacher Assignments
                                </x-nav-link>
                            @endcan
                            @can('manage imports')
                                <x-nav-link :href="route('admin.imports.index')" :active="request()->routeIs('admin.imports.*')">
                                    Imports
                                </x-nav-link>
                            @endcan
                            <x-nav-link :href="route('admin.reports.index')" :active="request()->routeIs('admin.reports.*')">
                                Reports
                            </x-nav-link>
                            <x-nav-link :href="route('admin.user-management.index')" :active="request()->routeIs('admin.user-management.*') || request()->routeIs('admin.users.*')">
                                Users
                            </x-nav-link>
                        @endif
                    </div>
                </div>

                <div class="hidden items-center gap-4 sm:flex">
                    <div class="rounded-full border border-white/10 bg-white/10 px-4 py-2 text-right backdrop-blur">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-300">Connected</p>
                        <p class="font-['Outfit'] text-sm font-semibold text-white">{{ Auth::user()->name }}</p>
                    </div>

                    <x-dropdown align="right" width="56">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white px-4 py-3 text-sm font-semibold text-slate-900 shadow-lg transition hover:bg-slate-100">
                                <span>Account</span>
                                <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="space-y-1 p-1">
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </div>
                        </x-slot>
                    </x-dropdown>
                </div>

                <div class="flex xl:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center rounded-full border border-white/10 bg-white/10 p-3 text-white transition hover:bg-white/15">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div :class="{'block': open, 'hidden': ! open}" class="hidden xl:hidden">
                <div class="mt-4 space-y-2 border-t border-white/10 pt-4">
                    <x-responsive-nav-link :href="route(Auth::user()->dashboardRoute())" :active="request()->routeIs(Auth::user()->dashboardRoute())">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>

                    @if (Auth::user()->hasRole('teacher'))
                        <x-responsive-nav-link :href="route('teacher.evaluations.index')" :active="request()->routeIs('teacher.evaluations.*')">
                            Evaluations
                        </x-responsive-nav-link>
                    @endif

                    @if (Auth::user()->hasRole('student'))
                        <x-responsive-nav-link :href="route('student.evaluations.index')" :active="request()->routeIs('student.evaluations.*')">
                            My Evaluations
                        </x-responsive-nav-link>
                    @endif

                    @if (Auth::user()->hasRole('parent'))
                        <x-responsive-nav-link :href="route('parent.dashboard')" :active="request()->routeIs('parent.*')">
                            Children
                        </x-responsive-nav-link>
                    @endif

                    @if (Auth::user()->isAdmin())
                        @can('manage schools')
                            <x-responsive-nav-link :href="route('admin.schools.index')" :active="request()->routeIs('admin.schools.*')">
                                Schools
                            </x-responsive-nav-link>
                        @endcan
                        @can('manage academic years')
                            <x-responsive-nav-link :href="route('admin.academic-years.index')" :active="request()->routeIs('admin.academic-years.*')">
                                Academic Years
                            </x-responsive-nav-link>
                        @endcan
                        @can('manage levels')
                            <x-responsive-nav-link :href="route('admin.levels.index')" :active="request()->routeIs('admin.levels.*')">
                                Levels
                            </x-responsive-nav-link>
                        @endcan
                        @can('manage classrooms')
                            <x-responsive-nav-link :href="route('admin.classrooms.index')" :active="request()->routeIs('admin.classrooms.*')">
                                Classrooms
                            </x-responsive-nav-link>
                        @endcan
                        @can('manage subjects')
                            <x-responsive-nav-link :href="route('admin.subjects.index')" :active="request()->routeIs('admin.subjects.*')">
                                Subjects
                            </x-responsive-nav-link>
                        @endcan
                        @can('manage teacher assignments')
                            <x-responsive-nav-link :href="route('admin.teacher-assignments.index')" :active="request()->routeIs('admin.teacher-assignments.*')">
                                Teacher Assignments
                            </x-responsive-nav-link>
                        @endcan
                        @can('manage imports')
                            <x-responsive-nav-link :href="route('admin.imports.index')" :active="request()->routeIs('admin.imports.*')">
                                Imports
                            </x-responsive-nav-link>
                        @endcan
                        <x-responsive-nav-link :href="route('admin.reports.index')" :active="request()->routeIs('admin.reports.*')">
                            Reports
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.user-management.index')" :active="request()->routeIs('admin.user-management.*') || request()->routeIs('admin.users.*')">
                            Users
                        </x-responsive-nav-link>
                    @endif

                    <div class="mt-4 rounded-2xl border border-white/10 bg-white/10 p-4 text-sm text-slate-200">
                        <p class="font-semibold text-white">{{ Auth::user()->name }}</p>
                        <p class="mt-1 text-slate-300">{{ Auth::user()->email }}</p>
                    </div>

                    <div class="space-y-2 pt-2">
                        <x-responsive-nav-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-responsive-nav-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-responsive-nav-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-responsive-nav-link>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
