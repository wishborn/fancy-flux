@blaze

{{--
    Carousel Tab: A clickable navigation tab in the carousel.

    Why: "Tab" matches the ARIA semantics (role="tab") and clearly conveys
    that this is an interactive navigation element, not just a passive indicator.

    For directional variant: Renders as a dot
    For wizard variant: Renders as a flux:action button with full prop support
    For thumbnail variant: Renders as a small preview image

    Uses "name" prop like flux:tab for consistency.
    
    Wizard variant supports all flux:action props for full customization:
    - icon, iconColor, iconPlace, iconTrailing
    - emoji, emojiTrailing  
    - warn, alert, alertIcon
    - size (defaults to 'sm')

    @example <flux:carousel.tab name="intro" label="Introduction" />
    @example <flux:carousel.tab name="step-1" icon="check" />
--}}

@aware(['variant' => 'directional', 'flush' => false])

@props([
    'name' => null, // Unique identifier matching the associated panel (required)
    'label' => null, // Display label (shown for wizard and thumbnail variants)
    'src' => null, // Thumbnail image source (for thumbnail variant)
    'alt' => null, // Alt text for thumbnail image
    'variant' => null,
    'flush' => null, // Minimal styling for seamless container integration (inherits from parent)
    // flux:action props for wizard variant
    'color' => null, // Standalone color (overrides state colors)
    'icon' => null,
    'iconColor' => null,
    'iconPlace' => 'left',
    'iconTrailing' => false,
    'emoji' => null,
    'emojiTrailing' => null,
    'avatar' => null,
    'avatarTrailing' => false,
    'badge' => null,
    'badgeTrailing' => false,
    'sort' => null, // Order of elements: e=emoji, i=icon, a=avatar, b=badge
    'checked' => false,
    'warn' => false,
    'alert' => false,
    'alertIcon' => null,
    'alertIconTrailing' => false,
    'size' => 'sm', // Default to sm for tab buttons
])

@php
// Use aware variant if not explicitly set
$variant = $variant ?? 'directional';
// Flush can be inherited from parent tabs container or set directly
$flush = $flush ?? false;

// Name is required
$tabName = $name ?? 'tab-' . uniqid();

// Default label if none provided
$displayLabel = $label ?? $tabName;
@endphp

@if ($variant === 'wizard')
    {{-- Wizard variant: Uses flux:action for consistent button styling --}}
    <div
        x-on:click="goTo('{{ $tabName }}')"
        class="cursor-pointer"
        data-flux-carousel-step
        data-name="{{ $tabName }}"
        role="tab"
        :aria-selected="isActive('{{ $tabName }}') ? 'true' : 'false'"
        :tabindex="isActive('{{ $tabName }}') ? 0 : -1"
        aria-label="{{ $displayLabel }}"
    >
        <flux:action
            :active="false"
            ::class="isActive('{{ $tabName }}') ? 'bg-blue-500! text-white! border-blue-600! hover:bg-blue-600!' : ''"
            :color="$color"
            :size="$size"
            :icon="$icon"
            :icon-color="$iconColor"
            :icon-place="$iconPlace"
            :icon-trailing="$iconTrailing"
            :emoji="$emoji"
            :emoji-trailing="$emojiTrailing"
            :avatar="$avatar"
            :avatar-trailing="$avatarTrailing"
            :badge="$badge"
            :badge-trailing="$badgeTrailing"
            :sort="$sort"
            :checked="$checked"
            :warn="$warn"
            :alert="$alert"
            :alert-icon="$alertIcon"
            :alert-icon-trailing="$alertIconTrailing"
        >{{ $displayLabel }}</flux:action>
    </div>
@elseif ($variant === 'thumbnail')
    {{-- Thumbnail variant: shows a small preview image --}}
    @php
    $thumbnailClasses = Flux::classes()
        ->add('cursor-pointer transition-all duration-200')
        ->add('focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500')
        ->add('relative overflow-hidden rounded-lg')
        ->add('w-16 h-12 sm:w-20 sm:h-14')
        ->add('border-2 border-transparent')
        ->add('hover:border-zinc-400 dark:hover:border-zinc-500')
        ->add('opacity-70 hover:opacity-100')
        ;
    $thumbnailActiveClasses = 'border-blue-500! dark:border-blue-400! opacity-100! ring-2 ring-blue-500/30';
    @endphp
    <button
        type="button"
        {{ $attributes->class($thumbnailClasses) }}
        x-on:click="goTo('{{ $tabName }}')"
        :class="isActive('{{ $tabName }}') ? '{{ $thumbnailActiveClasses }}' : ''"
        :aria-selected="isActive('{{ $tabName }}') ? 'true' : 'false'"
        :tabindex="isActive('{{ $tabName }}') ? 0 : -1"
        data-flux-carousel-step
        data-name="{{ $tabName }}"
        role="tab"
        aria-label="{{ $label ?? 'Go to ' . $tabName }}"
    >
        @if ($src)
            <img 
                src="{{ $src }}" 
                alt="{{ $alt ?? $label ?? 'Slide ' . $tabName }}"
                class="w-full h-full object-cover"
                loading="lazy"
            />
        @else
            <div class="w-full h-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center">
                <span class="text-xs text-zinc-500 dark:text-zinc-400" x-text="steps.indexOf('{{ $tabName }}') + 1"></span>
            </div>
        @endif
        @if ($label)
            <span class="absolute inset-x-0 bottom-0 bg-black/60 text-white text-[10px] px-1 py-0.5 truncate text-center">
                {{ $label }}
            </span>
        @endif
    </button>
@else
    {{-- Directional variant: Simple dot tab --}}
    @php
    $dotClasses = Flux::classes()
        ->add('cursor-pointer transition-all duration-200')
        ->add('focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500')
        ->add('size-2.5 rounded-full')
        ->add('hover:scale-125')
        ;
    $dotActiveClasses = 'bg-zinc-800! dark:bg-white!';
    $dotInactiveClasses = 'bg-zinc-300 dark:bg-zinc-600';
    @endphp
    <button
        type="button"
        {{ $attributes->class($dotClasses) }}
        x-on:click="goTo('{{ $tabName }}')"
        :class="isActive('{{ $tabName }}') ? '{{ $dotActiveClasses }}' : '{{ $dotInactiveClasses }}'"
        :aria-selected="isActive('{{ $tabName }}') ? 'true' : 'false'"
        :tabindex="isActive('{{ $tabName }}') ? 0 : -1"
        data-flux-carousel-step
        data-name="{{ $tabName }}"
        role="tab"
        aria-label="{{ $label ?? 'Go to ' . $tabName }}"
    ></button>
@endif

@if ($slot->isNotEmpty())
    {{ $slot }}
@endif
