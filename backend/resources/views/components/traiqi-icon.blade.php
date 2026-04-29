@props(['name', 'class' => ''])

@switch($name)
    @case('dashboard')
        <svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 13.5h6V4H4v9.5Zm0 6.5h6v-3.5H4V20Zm10 0h6V10h-6v10Zm0-13.5h6V4h-6v2.5Z"/>
        </svg>
        @break
    @case('grades')
        <svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 19h16M7 16V8m5 8V5m5 11v-6"/>
        </svg>
        @break
    @case('users')
        <svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2m18 0v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75M9.5 11A4 4 0 1 0 9.5 3a4 4 0 0 0 0 8Z"/>
        </svg>
        @break
    @case('book')
        <svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20M6.5 17A2.5 2.5 0 0 0 4 19.5V5a2 2 0 0 1 2-2h14v14M6.5 17H20"/>
        </svg>
        @break
    @case('bell')
        <svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 8a6 6 0 1 1 12 0c0 7 3 9 3 9H3s3-2 3-9m6 13a2.5 2.5 0 0 0 2.45-2"/>
        </svg>
        @break
    @case('settings')
        <svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="m12 15.5 2.3 1.32.62-2.58 2.01-1.72-2.65-.22L12 10l-1.28 2.3-2.65.22 2.01 1.72.62 2.58L12 15.5ZM4 12c0 1.46.3 2.84.85 4.1l-1.5 2.6 2.95 2.95 2.6-1.5A9.96 9.96 0 0 0 12 21c1.46 0 2.84-.3 4.1-.85l2.6 1.5 2.95-2.95-1.5-2.6A9.96 9.96 0 0 0 21 12c0-1.46-.3-2.84-.85-4.1l1.5-2.6-2.95-2.95-2.6 1.5A9.96 9.96 0 0 0 12 3c-1.46 0-2.84.3-4.1.85l-2.6-1.5L2.35 5.3l1.5 2.6C3.3 9.16 3 10.54 3 12"/>
        </svg>
        @break
    @case('search')
        <svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8']) }}>
            <circle cx="11" cy="11" r="7"></circle>
            <path stroke-linecap="round" stroke-linejoin="round" d="m20 20-3.5-3.5"/>
        </svg>
        @break
    @case('globe')
        <svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8']) }}>
            <circle cx="12" cy="12" r="9"></circle>
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.6 9h16.8M3.6 15h16.8M12 3c2.3 2.5 3.5 5.7 3.5 9S14.3 18.5 12 21c-2.3-2.5-3.5-5.7-3.5-9S9.7 5.5 12 3Z"/>
        </svg>
        @break
    @case('sun')
        <svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8']) }}>
            <circle cx="12" cy="12" r="4"></circle>
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 2v2.2M12 19.8V22M4.93 4.93l1.56 1.56M17.5 17.5l1.57 1.57M2 12h2.2M19.8 12H22M4.93 19.07l1.56-1.56M17.5 6.5l1.57-1.57"/>
        </svg>
        @break
    @case('moon')
        <svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.8A9 9 0 1 1 11.2 3a7.2 7.2 0 0 0 9.8 9.8Z"/>
        </svg>
        @break
    @case('menu')
        <svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16"/>
        </svg>
        @break
    @case('upload')
        <svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 16V5m0 0-4 4m4-4 4 4M4 17.5V19a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-1.5"/>
        </svg>
        @break
    @case('calendar')
        <svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="M7 2v4M17 2v4M3 9h18M5 5h14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"/>
        </svg>
        @break
    @case('target')
        <svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8']) }}>
            <circle cx="12" cy="12" r="8"></circle>
            <circle cx="12" cy="12" r="4"></circle>
            <circle cx="12" cy="12" r="1"></circle>
        </svg>
        @break
    @case('star')
        <svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="m12 3.5 2.6 5.28 5.82.85-4.2 4.1.99 5.8L12 16.75 6.79 19.53l.99-5.8-4.2-4.1 5.82-.85L12 3.5Z"/>
        </svg>
        @break
    @case('shield')
        <svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 22s8-3 8-10V5l-8-3-8 3v7c0 7 8 10 8 10Z"/>
        </svg>
        @break
    @case('clipboard')
        <svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 4h6m-7 3h8a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2Zm1-3h4a1 1 0 0 1 1 1v2H9V5a1 1 0 0 1 1-1Z"/>
        </svg>
        @break
    @case('sparkles')
        <svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="m12 3 1.7 4.3L18 9l-4.3 1.7L12 15l-1.7-4.3L6 9l4.3-1.7L12 3Zm6 11 1 2.5L21.5 18 19 19l-1 2.5L17 19l-2.5-1.5L17 16l1-2Zm-12 1 1.25 3L10 19.25 7.25 20.5 6 23l-1.25-2.5L2 19.25 4.75 18 6 15Z"/>
        </svg>
        @break
    @case('briefcase')
        <svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 7V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2m-9 3h12m-14 0h16v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-8Z"/>
        </svg>
        @break
    @case('chart')
        <svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 20h16M7 16v-5m5 5V6m5 10v-8"/>
        </svg>
        @break
    @case('life-buoy')
        <svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8']) }}>
            <circle cx="12" cy="12" r="9"></circle>
            <circle cx="12" cy="12" r="3"></circle>
            <path stroke-linecap="round" stroke-linejoin="round" d="m18.4 5.6-4.2 4.2m-4.4 4.4-4.2 4.2m0-12.8 4.2 4.2m4.4 4.4 4.2 4.2"/>
        </svg>
        @break
    @case('panel')
        <svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 5h6v14H4V5Zm10 0h6v6h-6V5Zm0 10h6v4h-6v-4Z"/>
        </svg>
        @break
    @case('close')
        <svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="m6 6 12 12M18 6 6 18"/>
        </svg>
        @break
    @case('chevron')
        <svg {{ $attributes->merge(['class' => trim('chevron '.$class), 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="m9 6 6 6-6 6"/>
        </svg>
        @break
    @default
        <svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8']) }}>
            <circle cx="12" cy="12" r="8"></circle>
        </svg>
@endswitch
