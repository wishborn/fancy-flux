{{--
    Action Component: A reusable button component following Flux UI patterns for consistent
    chat/drawer actions. Supports active, warn, alert states, icons with flexible placement, 
    emojis, avatars, badges, and disabled states.

    Why: Provides a consistent, state-aware button component for action-oriented UI elements
    like chat controls, drawer actions, and toolbar buttons with full dark mode support.

    Features:
    - Shape variants: default (rounded rectangle), circle (perfect circle for icon-only buttons)
    - Color prop: standalone color theming (blue, emerald, amber, red, violet, etc.) - independent of states
    - State variants: active, checked, warn, alert (behavioral states, NOT colors when color prop is set)
    - Icon placement: left, right, top, bottom (top/bottom use absolute positioning)
    - Emoji support: leading and trailing emojis using FANCY::emoji() slugs
    - Avatar support: circular image display
    - Badge support: text badge display
    - Sort control: customize order of emoji, icon, badge (e.g., "eib", "ibe", "bei")
    - Size variants: sm, md, lg
    - Alert icon with pulsing animation
    - Full dark mode support
    - Disabled state handling
    - Livewire compatible

    Usage:
    <flux:action>Default</flux:action>
    <flux:action color="blue">Blue Button</flux:action>
    <flux:action color="emerald">Green Button</flux:action>
    <flux:action color="red" checked>Red + Checked behavior</flux:action>
    <flux:action active>Active (blue by default)</flux:action>
    <flux:action checked>Checked (emerald by default)</flux:action>
    <flux:action warn icon="exclamation-triangle">Warning</flux:action>
    <flux:action alert alert-icon="bell">Alert!</flux:action>
    <flux:action icon="pencil" icon-trailing>Edit</flux:action>
    <flux:action emoji="fire">Hot!</flux:action>
    <flux:action avatar="/img/user.jpg">John</flux:action>
    <flux:action badge="3">Messages</flux:action>
    <flux:action icon="star" emoji="fire" badge="New" sort="eib">Featured</flux:action>
    <flux:action variant="circle" icon="play">Play</flux:action>
--}}

@props([
    'variant' => 'default', // Shape: 'default' (rounded rectangle) or 'circle' (perfect circle)
    'color' => null, // Standalone color: blue, emerald, amber, red, violet, indigo, sky, rose, orange, zinc (overrides state colors)
    'active' => false, // Behavioral state (uses blue if no color set)
    'checked' => false, // Behavioral state (uses emerald if no color set)
    'warn' => false, // Behavioral state (uses amber if no color set)
    'alert' => false, // Behavioral state (triggers pulse animation, no color change)
    'icon' => null,
    'iconColor' => null,
    'iconPlace' => 'left',
    'iconTrailing' => false,
    'alertIcon' => null,
    'alertIconTrailing' => false,
    'emoji' => null,
    'emojiTrailing' => null,
    'avatar' => null,
    'avatarTrailing' => false,
    'badge' => null,
    'badgeTrailing' => false,
    'sort' => null, // Order of elements: e=emoji, i=icon, b=badge, a=avatar (e.g., "eiba", "aibe")
    'disabled' => false,
    'size' => 'md',
])

@php
    use FancyFlux\FancyFlux;

    // Normalize size and variant
    $size = $size ?? 'md';
    $variant = $variant ?? 'default';
    $isCircle = $variant === 'circle';

    // Resolve emoji slugs to characters using FANCY facade
    $fancyFlux = app(FancyFlux::class);
    $emojiChar = $emoji ? $fancyFlux->emoji($emoji) : null;
    $emojiTrailingChar = $emojiTrailing ? $fancyFlux->emoji($emojiTrailing) : null;

    // Check if icon placement is vertical (top/bottom)
    $isVerticalPlacement = in_array($iconPlace, ['top', 'over', 'bottom', 'under']);

    // Determine flex direction for horizontal layouts only
    $flexDirection = 'flex-row';
    if (!$isVerticalPlacement) {
        if ($iconPlace === 'right' || $iconTrailing) {
            $flexDirection = 'flex-row-reverse';
        }
    }

    // Base button classes using Flux::classes() pattern
    $buttonClasses = Flux::classes()
        ->add('inline-flex items-center justify-center font-medium')
        ->add($isCircle ? 'rounded-full' : 'rounded-lg')
        ->add('transition-all duration-200')
        ->add('focus:outline-none focus:ring-2 focus:ring-offset-1')
        ->add('disabled:opacity-50 disabled:cursor-not-allowed')
        ->add($flexDirection)
        // Add relative positioning for vertical icon placement
        ->add($isVerticalPlacement ? 'relative' : '')
        // Add pulse animation for alert state (pulses the entire button)
        ->add($alert ? 'animate-pulse' : '')
        // Size variants - circle uses equal width/height, default uses padding
        ->add(match (true) {
            $isCircle => match ($size) {
                'sm' => 'w-8 h-8 text-xs',
                'lg' => 'w-12 h-12 text-base',
                default => 'w-10 h-10 text-sm', // md
            },
            $isVerticalPlacement => match ($size) {
                'sm' => 'px-2 pt-5 pb-5 text-xs gap-1',
                'lg' => 'px-4 pt-8 pb-8 text-base gap-2',
                default => 'px-3 pt-6 pb-6 text-sm gap-1.5', // md
            },
            default => match ($size) {
                'sm' => 'px-2 py-1 text-xs gap-1',
                'lg' => 'px-4 py-2.5 text-base gap-2',
                default => 'px-3 py-1.5 text-sm gap-1.5', // md
            },
        })
        // Color classes - color prop takes precedence, then state-based colors, then default
        // Color prop is standalone and independent of behavioral states (active, checked, warn, alert)
        ->add(match (true) {
            // Explicit color prop - always wins
            $color === 'blue' => implode(' ', [
                'bg-blue-500 text-white border border-blue-600',
                'hover:bg-blue-600 focus:ring-blue-500',
                'dark:bg-blue-600 dark:border-blue-500 dark:hover:bg-blue-500',
            ]),
            $color === 'emerald' => implode(' ', [
                'bg-emerald-500 text-white border border-emerald-600',
                'hover:bg-emerald-600 focus:ring-emerald-500',
                'dark:bg-emerald-600 dark:border-emerald-500 dark:hover:bg-emerald-500',
            ]),
            $color === 'amber' => implode(' ', [
                'bg-amber-500 text-white border border-amber-600',
                'hover:bg-amber-600 focus:ring-amber-500',
                'dark:bg-amber-600 dark:border-amber-500 dark:hover:bg-amber-500',
            ]),
            $color === 'red' => implode(' ', [
                'bg-red-500 text-white border border-red-600',
                'hover:bg-red-600 focus:ring-red-500',
                'dark:bg-red-600 dark:border-red-500 dark:hover:bg-red-500',
            ]),
            $color === 'violet' => implode(' ', [
                'bg-violet-500 text-white border border-violet-600',
                'hover:bg-violet-600 focus:ring-violet-500',
                'dark:bg-violet-600 dark:border-violet-500 dark:hover:bg-violet-500',
            ]),
            $color === 'indigo' => implode(' ', [
                'bg-indigo-500 text-white border border-indigo-600',
                'hover:bg-indigo-600 focus:ring-indigo-500',
                'dark:bg-indigo-600 dark:border-indigo-500 dark:hover:bg-indigo-500',
            ]),
            $color === 'sky' => implode(' ', [
                'bg-sky-500 text-white border border-sky-600',
                'hover:bg-sky-600 focus:ring-sky-500',
                'dark:bg-sky-600 dark:border-sky-500 dark:hover:bg-sky-500',
            ]),
            $color === 'rose' => implode(' ', [
                'bg-rose-500 text-white border border-rose-600',
                'hover:bg-rose-600 focus:ring-rose-500',
                'dark:bg-rose-600 dark:border-rose-500 dark:hover:bg-rose-500',
            ]),
            $color === 'orange' => implode(' ', [
                'bg-orange-500 text-white border border-orange-600',
                'hover:bg-orange-600 focus:ring-orange-500',
                'dark:bg-orange-600 dark:border-orange-500 dark:hover:bg-orange-500',
            ]),
            $color === 'zinc' => implode(' ', [
                'bg-zinc-500 text-white border border-zinc-600',
                'hover:bg-zinc-600 focus:ring-zinc-500',
                'dark:bg-zinc-600 dark:border-zinc-500 dark:hover:bg-zinc-500',
            ]),
            // State-based colors (only when no color prop is set)
            $warn => implode(' ', [
                'bg-amber-50 text-amber-700 border border-amber-200',
                'hover:bg-amber-100 focus:ring-amber-500',
                'dark:bg-amber-900/30 dark:text-amber-300 dark:border-amber-700 dark:hover:bg-amber-900/50',
            ]),
            $checked => implode(' ', [
                'bg-emerald-500 text-white border border-emerald-600',
                'hover:bg-emerald-600 focus:ring-emerald-500',
                'dark:bg-emerald-600 dark:border-emerald-500 dark:hover:bg-emerald-500',
            ]),
            $active => implode(' ', [
                'bg-blue-500 text-white border border-blue-600',
                'hover:bg-blue-600 focus:ring-blue-500',
                'dark:bg-blue-600 dark:border-blue-500 dark:hover:bg-blue-500',
            ]),
            // Default neutral style
            default => implode(' ', [
                'bg-white text-zinc-700 border border-zinc-200',
                'hover:bg-zinc-50 hover:border-zinc-300 focus:ring-blue-500',
                'dark:bg-zinc-800 dark:text-zinc-300 dark:border-zinc-700 dark:hover:bg-zinc-700',
            ]),
        })
        ;

    // Icon size based on button size
    $iconSizeClass = match ($size) {
        'sm' => 'w-3 h-3',
        'lg' => 'w-5 h-5',
        default => 'w-4 h-4', // md
    };

    // Determine if button has a colored background (for icon/badge contrast)
    $hasColoredBackground = $color !== null || $active || $checked;
    $hasLightBackground = !$hasColoredBackground && !$warn;

    // Icon color - use provided iconColor, or white on colored backgrounds, or contextual colors
    $iconColorClass = $iconColor ?? match(true) {
        $hasColoredBackground => 'text-white',
        $warn => 'text-amber-600 dark:text-amber-400',
        default => 'text-zinc-500 dark:text-zinc-400',
    };

    // Icon classes for inline (horizontal) placement
    $iconClasses = Flux::classes()
        ->add($iconSizeClass)
        ->add($iconColorClass)
        ;

    // Icon classes for absolute (vertical) placement - positioned at edge, between label and container
    $absoluteIconClasses = Flux::classes()
        ->add($iconSizeClass)
        ->add($iconColorClass)
        ->add('absolute left-1/2 -translate-x-1/2')
        ->add(in_array($iconPlace, ['top', 'over']) ? 'top-1.5' : 'bottom-1.5')
        ;

    // Alert icon size (slightly smaller than main icon)
    $alertIconSize = match ($size) {
        'sm' => 'w-2.5 h-2.5',
        'lg' => 'w-4 h-4',
        default => 'w-3 h-3', // md
    };

    $alertIconClasses = Flux::classes()
        ->add($alertIconSize)
        ->add('animate-pulse text-red-500 dark:text-red-400')
        ;

    $alertIconPingClasses = Flux::classes()
        ->add($alertIconSize)
        ->add('text-red-400 dark:text-red-300')
        ;

    // Avatar size based on button size
    $avatarSizeClass = match ($size) {
        'sm' => 'w-4 h-4',
        'lg' => 'w-6 h-6',
        default => 'w-5 h-5', // md
    };

    // Badge classes - uses white/translucent on colored backgrounds
    $badgeClasses = Flux::classes()
        ->add('inline-flex items-center justify-center font-medium rounded-full')
        ->add(match ($size) {
            'sm' => 'text-[10px] px-1.5 min-w-[16px] h-4',
            'lg' => 'text-xs px-2 min-w-[22px] h-5',
            default => 'text-[11px] px-1.5 min-w-[18px] h-[18px]', // md
        })
        ->add(match (true) {
            $hasColoredBackground => 'bg-white/20 text-white',
            $warn => 'bg-amber-200 text-amber-800 dark:bg-amber-800 dark:text-amber-200',
            default => 'bg-zinc-200 text-zinc-700 dark:bg-zinc-600 dark:text-zinc-200',
        })
        ;

    // Parse sort order - default order is: emoji, icon, avatar, badge (leading), then content, then trailing versions
    // Sort string uses: e=emoji, i=icon, a=avatar, b=badge
    // Default: "eiab" (emoji, icon, avatar, badge)
    $sortOrder = [];
    $sortString = strtolower($sort ?? 'eiab');
    
    // Parse sort string character by character, silently ignore invalid characters
    foreach (str_split($sortString) as $char) {
        if (in_array($char, ['e', 'i', 'a', 'b']) && !in_array($char, $sortOrder)) {
            $sortOrder[] = $char;
        }
    }
    
    // Add any missing elements in default order
    foreach (['e', 'i', 'a', 'b'] as $defaultChar) {
        if (!in_array($defaultChar, $sortOrder)) {
            $sortOrder[] = $defaultChar;
        }
    }
@endphp

<button 
    {{ $attributes->class($buttonClasses) }}
    @if($disabled) disabled @endif
    data-flux-action
>
    {{-- Absolutely positioned icon for top/bottom placement --}}
    @if($icon && $isVerticalPlacement)
        <flux:icon name="{{ $icon }}" variant="mini" :class="$absoluteIconClasses" />
    @endif

    {{-- Leading elements in sort order --}}
    @foreach($sortOrder as $element)
        @switch($element)
            @case('e')
                {{-- Leading Emoji --}}
                @if($emojiChar)
                    <span class="emoji-leading" data-flux-action-emoji>{{ $emojiChar }}</span>
                @endif
                @break
            @case('i')
                {{-- Leading Icon (horizontal placement only) --}}
                @if($icon && !$isVerticalPlacement && !$iconTrailing)
                    <flux:icon name="{{ $icon }}" variant="mini" :class="$iconClasses" />
                @endif
                @break
            @case('a')
                {{-- Leading Avatar --}}
                @if($avatar && !$avatarTrailing)
                    <img 
                        src="{{ $avatar }}" 
                        alt=""
                        class="{{ $avatarSizeClass }} rounded-full object-cover flex-shrink-0"
                        data-flux-action-avatar
                    />
                @endif
                @break
            @case('b')
                {{-- Leading Badge --}}
                @if($badge && !$badgeTrailing)
                    <span class="{{ $badgeClasses }}" data-flux-action-badge>{{ $badge }}</span>
                @endif
                @break
        @endswitch
    @endforeach

    {{-- Alert Icon (pulsing) - leading position --}}
    @if($alertIcon && !$alertIconTrailing)
        <span class="relative" data-flux-action-alert>
            <flux:icon name="{{ $alertIcon }}" variant="mini" :class="$alertIconClasses" />
            <span class="absolute inset-0 animate-ping opacity-75">
                <flux:icon name="{{ $alertIcon }}" variant="micro" :class="$alertIconPingClasses" />
            </span>
        </span>
    @endif

    {{-- Button Content (slot) --}}
    <span class="truncate">{{ $slot }}</span>

    {{-- Trailing elements in sort order --}}
    @foreach($sortOrder as $element)
        @switch($element)
            @case('e')
                {{-- Trailing Emoji --}}
                @if($emojiTrailingChar)
                    <span class="emoji-trailing" data-flux-action-emoji>{{ $emojiTrailingChar }}</span>
                @endif
                @break
            @case('i')
                {{-- Trailing Icon (horizontal placement only) --}}
                @if($icon && !$isVerticalPlacement && $iconTrailing)
                    <flux:icon name="{{ $icon }}" variant="mini" :class="$iconClasses" />
                @endif
                @break
            @case('a')
                {{-- Trailing Avatar --}}
                @if($avatar && $avatarTrailing)
                    <img 
                        src="{{ $avatar }}" 
                        alt=""
                        class="{{ $avatarSizeClass }} rounded-full object-cover flex-shrink-0"
                        data-flux-action-avatar
                    />
                @endif
                @break
            @case('b')
                {{-- Trailing Badge --}}
                @if($badge && $badgeTrailing)
                    <span class="{{ $badgeClasses }}" data-flux-action-badge>{{ $badge }}</span>
                @endif
                @break
        @endswitch
    @endforeach

    {{-- Alert Icon (pulsing) - trailing position --}}
    @if($alertIcon && $alertIconTrailing)
        <span class="relative" data-flux-action-alert>
            <flux:icon name="{{ $alertIcon }}" variant="mini" :class="$alertIconClasses" />
            <span class="absolute inset-0 animate-ping opacity-75">
                <flux:icon name="{{ $alertIcon }}" variant="micro" :class="$alertIconPingClasses" />
            </span>
        </span>
    @endif
</button>
