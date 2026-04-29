<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-label">{{ __('ui.portfolio.title') }}</p>
            <h1 class="mt-2 text-title">{{ __('ui.portfolio.subtitle') }}</h1>
        </div>
    </x-slot>

    <div class="grid gap-6 xl:grid-cols-[0.95fr_1.05fr]">
        <x-ui.card>
            <p class="text-label">{{ __('ui.nav.profile') }}</p>
            <h2 class="mt-3 text-section">{{ auth()->user()->name }}</h2>
            <p class="mt-2 text-soft">{{ auth()->user()->email }}</p>
        </x-ui.card>

        <x-ui.card :title="__('ui.nav.portfolio')">
            <div class="space-y-4">
                <div class="rounded-[1.2rem] border border-[color:var(--line)] p-4">
                    <p class="font-semibold">Academic strengths</p>
                    <p class="mt-2 text-soft">Languages, mathematics, digital literacy, and project-based collaboration.</p>
                </div>
                <div class="rounded-[1.2rem] border border-[color:var(--line)] p-4">
                    <p class="font-semibold">Experience highlights</p>
                    <p class="mt-2 text-soft">Class participation, mentoring, clubs, and portfolio-ready achievements.</p>
                </div>
            </div>
        </x-ui.card>
    </div>
</x-app-layout>
