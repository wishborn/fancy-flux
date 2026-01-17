@blaze

{{--
    Carousel Step Indicator: A single step indicator in the carousel navigation.

    For directional variant: Renders as a dot
    For wizard variant: Renders as a numbered step with optional label
    For thumbnail variant: Renders as a small preview image

    Uses "name" prop like flux:tab for consistency.
--}}

@aware(['variant' => 'directional'])

@props([
    'name' => null, // Unique identifier matching the associated step item (required)
    'label' => null, // Display label (shown for wizard and thumbnail variants)
    'icon' => null,
    'src' => null, // Thumbnail image source (for thumbnail variant)
    'alt' => null, // Alt text for thumbnail image
    'variant' => null,
])

@php
// Use aware variant if not explicitly set
$variant = $variant ?? 'directional';

// Name is required
$stepName = $name ?? 'step-' . uniqid();

// Base classes for all variants
$baseClasses = Flux::classes()
    ->add('cursor-pointer transition-all duration-200')
    ->add('focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500')
    ;

// Variant-specific classes (no background for directional - handled by Alpine)
$variantClasses = match ($variant) {
    'wizard' => Flux::classes()
        ->add('flex flex-col items-center gap-1')
        ->add('px-3 py-2 rounded-lg')
        ->add('text-zinc-500 dark:text-zinc-400')
        ->add('hover:text-zinc-800 dark:hover:text-white')
        ->add('hover:bg-zinc-100 dark:hover:bg-zinc-800')
        ,
    'thumbnail' => Flux::classes()
        ->add('relative overflow-hidden rounded-lg')
        ->add('w-16 h-12 sm:w-20 sm:h-14') // Responsive thumbnail size
        ->add('border-2 border-transparent')
        ->add('hover:border-zinc-400 dark:hover:border-zinc-500')
        ->add('opacity-70 hover:opacity-100')
        ,
    default => Flux::classes()
        ->add('size-2.5 rounded-full')
        ->add('hover:scale-125')
        ,
};

// Active classes use !important to override base styles
$activeClasses = match ($variant) {
    'wizard' => 'text-blue-600! dark:text-blue-400! bg-blue-50! dark:bg-blue-900/30!',
    'thumbnail' => 'border-blue-500! dark:border-blue-400! opacity-100! ring-2 ring-blue-500/30',
    default => 'bg-zinc-800! dark:bg-white!',
};

// Inactive classes for directional variant
$inactiveClasses = match ($variant) {
    'wizard' => '',
    'thumbnail' => '',
    default => 'bg-zinc-300 dark:bg-zinc-600',
};
@endphp

<button
    type="button"
    {{ $attributes->class([$baseClasses, $variantClasses]) }}
    x-on:click="goTo('{{ $stepName }}')"
    :class="isActive('{{ $stepName }}') ? '{{ $activeClasses }}' : '{{ $inactiveClasses }}'"
    :aria-selected="isActive('{{ $stepName }}')"
    :tabindex="isActive('{{ $stepName }}') ? 0 : -1"
    data-flux-carousel-step
    data-name="{{ $stepName }}"
    role="tab"
    aria-label="{{ $label ?? 'Go to ' . $stepName }}"
>
    @if ($variant === 'thumbnail')
        {{-- Thumbnail variant: shows a small preview image --}}
        @if ($src)
            <img 
                src="{{ $src }}" 
                alt="{{ $alt ?? $label ?? 'Slide ' . $stepName }}"
                class="w-full h-full object-cover"
                loading="lazy"
            />
        @else
            {{-- Fallback when no src provided --}}
            <div class="w-full h-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center">
                <span class="text-xs text-zinc-500 dark:text-zinc-400" x-text="steps.indexOf('{{ $stepName }}') + 1"></span>
            </div>
        @endif
        @if ($label)
            <span class="absolute inset-x-0 bottom-0 bg-black/60 text-white text-[10px] px-1 py-0.5 truncate text-center">
                {{ $label }}
            </span>
        @endif
    @elseif ($variant === 'wizard')
        <span class="flex items-center justify-center size-8 rounded-full border-2 transition-colors"
              :class="isActive('{{ $stepName }}') ? 'border-blue-500 bg-blue-500 text-white' : 'border-zinc-300 dark:border-zinc-600'">
            @if ($icon)
                <flux:icon :$icon class="size-4" />
            @else
                <span class="text-sm font-medium" x-text="steps.indexOf('{{ $stepName }}') + 1"></span>
            @endif
        </span>
        @if ($label)
            <span class="text-xs font-medium">{{ $label }}</span>
        @endif
    @endif
    @if ($slot->isNotEmpty())
        {{ $slot }}
    @endif
</button>
