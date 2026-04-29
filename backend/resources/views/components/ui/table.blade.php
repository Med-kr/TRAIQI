<div {{ $attributes->merge(['class' => 'surface-card overflow-hidden']) }}>
    <div class="overflow-x-auto">
        <table class="data-table">
            {{ $slot }}
        </table>
    </div>
</div>
