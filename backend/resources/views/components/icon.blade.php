@props(['name', 'class' => ''])

@switch($name)
    @case('grid-2x2')
        <svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h7v7H4V4Zm9 0h7v7h-7V4ZM4 13h7v7H4v-7Zm9 0h7v7h-7v-7Z"/>
        </svg>
        @break
    @case('star')
        <svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="m12 3.5 2.7 5.48 6.04.88-4.37 4.26 1.03 6.02L12 17.25l-5.4 2.89 1.03-6.02-4.37-4.26 6.04-.88L12 3.5Z"/>
        </svg>
        @break
    @case('book-open')
        <svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.5 6.5A2.5 2.5 0 0 1 5 4h6.5a3 3 0 0 1 3 3v13a3 3 0 0 0-3-3H5a2.5 2.5 0 0 0-2.5 2.5v-13Zm19 0A2.5 2.5 0 0 0 19 4h-6.5a3 3 0 0 0-3 3v13a3 3 0 0 1 3-3H19a2.5 2.5 0 0 1 2.5 2.5v-13Z"/>
        </svg>
        @break
    @case('compass')
        <svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8']) }}>
            <circle cx="12" cy="12" r="9"></circle>
            <path stroke-linecap="round" stroke-linejoin="round" d="m14.7 9.3-4 1.3-1.4 4 4-1.3 1.4-4Z"/>
        </svg>
        @break
    @case('heart')
        <svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 5.5a5 5 0 0 0-7.07 0L12 5.93l-.43-.43a5 5 0 1 0-7.07 7.07L12 20.07l7.5-7.5a5 5 0 0 0 0-7.07Z"/>
        </svg>
        @break
    @case('briefcase')
        <svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 6V4.5A1.5 1.5 0 0 1 9.5 3h5A1.5 1.5 0 0 1 16 4.5V6m4 0H4a1 1 0 0 0-1 1v9.5A2.5 2.5 0 0 0 5.5 19h13a2.5 2.5 0 0 0 2.5-2.5V7a1 1 0 0 0-1-1Z"/>
        </svg>
        @break
    @case('folder')
        <svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5A2.5 2.5 0 0 1 5.5 5H10l2 2h6.5A2.5 2.5 0 0 1 21 9.5v7A2.5 2.5 0 0 1 18.5 19h-13A2.5 2.5 0 0 1 3 16.5v-9Z"/>
        </svg>
        @break
    @case('settings')
        <svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8.5a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Zm8 3.5-1.88-.63.1-1.95-1.78-1.03-1.43 1.3-1.82-.75-.42-1.88h-2.06l-.42 1.88-1.82.75-1.43-1.3-1.78 1.03.1 1.95L4 12l.63 1.88-1.3 1.43 1.03 1.78 1.95-.1.75 1.82 1.88.42h2.06l.42-1.88 1.82-.75 1.43 1.3 1.78-1.03-.1-1.95 1.88-.63V12Z"/>
        </svg>
        @break
    @case('bell')
        <svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.41-1.41A1.97 1.97 0 0 1 18 14.17V11a6 6 0 1 0-12 0v3.17c0 .53-.21 1.04-.59 1.42L4 17h5m6 0a3 3 0 1 1-6 0m6 0H9"/>
        </svg>
        @break
    @case('trending-up')
        <svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="m22 7-7.5 7.5-4-4L2 19m20-12h-6"/>
        </svg>
        @break
    @case('calendar')
        <svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="M7 2v4m10-4v4M3 9h18M5.5 4h13A1.5 1.5 0 0 1 20 5.5v13a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 18.5v-13A1.5 1.5 0 0 1 5.5 4Z"/>
        </svg>
        @break
    @default
        <svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8']) }}>
            <circle cx="12" cy="12" r="9"></circle>
        </svg>
@endswitch
