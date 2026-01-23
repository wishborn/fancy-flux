{{--
    Action Component: A reusable button component following Flux UI patterns for consistent
    chat/drawer actions. Supports active, warn, alert states, icons with flexible placement, 
    emojis, and disabled states.

    Why: Provides a consistent, state-aware button component for action-oriented UI elements
    like chat controls, drawer actions, and toolbar buttons with full dark mode support.

    Features:
    - State variants: default, active (blue), warn (amber), alert (red with pulse)
    - Icon placement: left, right, top, bottom (top/bottom use absolute positioning)
    - Emoji support: leading and trailing emojis using FANCY::emoji() slugs
    - Size variants: sm, md, lg
    - Alert icon with pulsing animation
    - Full dark mode support
    - Disabled state handling
    - Livewire compatible

    Usage:
    <flux:action>Default</flux:action>
    <flux:action active>Active</flux:action>
    <flux:action warn icon="exclamation-triangle">Warning</flux:action>
    <flux:action alert alert-icon="bell">Alert!</flux:action>
    <flux:action icon="pencil" icon-trailing>Edit</flux:action>
    <flux:action emoji="fire">Hot!</flux:action>
    <flux:action emoji-trailing="thumbs-up">Like</flux:action>
--}}

@props([
    'active' => false,
    'warn' => false,
    'alert' => false,
    'icon' => null,
    'iconColor' => null,
    'iconPlace' => 'left',
    'iconTrailing' => false,
    'alertIcon' => null,
    'alertIconTrailing' => false,
    'emoji' => null,
    'emojiTrailing' => null,
    'disabled' => false,
    'size' => 'md',
])

@php
    use FancyFlux\FancyFlux;

    // Normalize size
    $size = $size ?? 'md';

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
        ->add('inline-flex items-center justify-center font-medium rounded-lg')
        ->add('transition-all duration-200')
        ->add('focus:outline-none focus:ring-2 focus:ring-offset-1')
        ->add('disabled:opacity-50 disabled:cursor-not-allowed')
        ->add($flexDirection)
        // Add relative positioning for vertical icon placement
        ->add($isVerticalPlacement ? 'relative' : '')
        // Add pulse animation for alert state (pulses the entire button)
        ->add($alert ? 'animate-pulse' : '')
        // Size variants - add extra vertical padding for top/bottom icons to make room for absolute icon
        ->add(match ($size) {
            'sm' => $isVerticalPlacement ? 'px-2 pt-5 pb-5 text-xs gap-1' : 'px-2 py-1 text-xs gap-1',
            'lg' => $isVerticalPlacement ? 'px-4 pt-8 pb-8 text-base gap-2' : 'px-4 py-2.5 text-base gap-2',
            default => $isVerticalPlacement ? 'px-3 pt-6 pb-6 text-sm gap-1.5' : 'px-3 py-1.5 text-sm gap-1.5', // md
        })
        // State variants (alert does NOT change color - it only triggers pulse animation)
        ->add(match (true) {
            $warn => implode(' ', [
                'bg-amber-50 text-amber-700 border border-amber-200',
                'hover:bg-amber-100 focus:ring-amber-500',
                'dark:bg-amber-900/30 dark:text-amber-300 dark:border-amber-700 dark:hover:bg-amber-900/50',
            ]),
            $active => implode(' ', [
                'bg-blue-500 text-white border border-blue-600',
                'hover:bg-blue-600 focus:ring-blue-500',
                'dark:bg-blue-600 dark:border-blue-500 dark:hover:bg-blue-500',
            ]),
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

    // Icon color - use provided color or inherit from state (alert does NOT affect icon color)
    $iconColorClass = $iconColor ?? match(true) {
        $warn => 'text-amber-600 dark:text-amber-400',
        $active => 'text-white',
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

    {{-- Leading Icon (horizontal placement only) --}}
    @if($icon && !$isVerticalPlacement && !$iconTrailing)
        <flux:icon name="{{ $icon }}" variant="mini" :class="$iconClasses" />
    @endif

    {{-- Leading Emoji --}}
    @if($emojiChar)
        <span class="emoji-leading" data-flux-action-emoji>{{ $emojiChar }}</span>
    @endif

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

    {{-- Trailing Icon (horizontal placement only) --}}
    @if($icon && !$isVerticalPlacement && $iconTrailing)
        <flux:icon name="{{ $icon }}" variant="mini" :class="$iconClasses" />
    @endif

    {{-- Trailing Emoji --}}
    @if($emojiTrailingChar)
        <span class="emoji-trailing" data-flux-action-emoji>{{ $emojiTrailingChar }}</span>
    @endif

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
