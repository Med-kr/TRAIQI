<x-app-layout>
    <x-slot name="header">
        <div>
            <span class="chip" x-text="$store.traiqi.t('nav.dashboard')"></span>
            <h1 class="text-title mt-4" x-text="$store.traiqi.t('nav.dashboard')"></h1>
        </div>
    </x-slot>

    <section class="surface-panel empty-state">
        <p x-text="$store.traiqi.t('common.no_data')"></p>
    </section>
</x-app-layout>
