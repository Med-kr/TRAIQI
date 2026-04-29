@include('layouts.app', [
    'layout' => 'dashboard',
    'pageTitle' => trim($__env->yieldContent('title')) ?: __('ui.app.name'),
])
