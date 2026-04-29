@include('layouts.app', [
    'layout' => 'guest',
    'pageTitle' => trim($__env->yieldContent('title')) ?: __('ui.app.name'),
])
