@if(session('status'))
    <div class="surface-panel flex items-start gap-3 px-5 py-4 text-sm text-soft">
        <span class="status-badge is-success">{{ __('ui.actions.done') }}</span>
        <p>{{ session('status') }}</p>
    </div>
@endif

@if($errors->any())
    <div class="surface-panel px-5 py-4">
        <p class="text-sm font-semibold text-red-600">{{ __('ui.form.fix_errors') }}</p>
        <ul class="mt-3 space-y-2 text-sm text-soft">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
