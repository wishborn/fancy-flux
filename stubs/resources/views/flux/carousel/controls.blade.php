@blaze

{{--
    Carousel Controls: Navigation controls for the carousel.

    Provides prev/next buttons for navigating between slides.
    For wizard variant, also includes "Back" and "Next"/"Finish" text buttons.
    
    Position options:
    - overlay: Arrows positioned over the panels (default for directional)
    - bottom: Controls below the panels (default for wizard)
    - sides: Same as overlay, but without centering
    
    Wizard submit handling:
    - Use wire:submit="methodName" to call a Livewire method when finish is clicked
    - Dispatches 'carousel-finish' event for Alpine.js listeners
--}}

@aware(['variant' => 'directional', 'wireSubmit' => null])

@props([
    'variant' => null,
    'position' => null, // Auto-determined based on variant if not set
    'showPrev' => true,
    'showNext' => true,
    'prevLabel' => null,
    'nextLabel' => null,
    'finishLabel' => 'Finish',
    'wireSubmit' => null, // Can be passed as a prop directly
])

@php
// Extract wire:submit if present for wizard finish action
// Priority: 1. Direct attribute on controls (wire:submit="method"), 2. Prop (:wireSubmit), 3. Aware from parent carousel
$wireSubmitFromAttr = $attributes->get('wire:submit');
$wireSubmit = $wireSubmitFromAttr ?? $wireSubmit;
@endphp

@php
// Use aware variant if not explicitly set
$variant = $variant ?? 'directional';

// Auto-determine position based on variant if not explicitly set
$position = $position ?? ($variant === 'wizard' ? 'bottom' : 'overlay');

// Default labels based on variant
$prevLabel = $prevLabel ?? ($variant === 'wizard' ? 'Back' : 'Previous');
$nextLabel = $nextLabel ?? ($variant === 'wizard' ? 'Next' : 'Next');

$containerClasses = Flux::classes()
    ->add(match ($position) {
        // Overlay positions arrows on left/right sides, vertically centered within the panels
        'overlay' => 'absolute inset-y-0 left-0 right-0 flex items-center justify-between px-3 pointer-events-none z-10',
        // For wizard, use justify-end so Next button is always on right, Back button uses mr-auto
        'bottom' => 'flex items-center justify-end mt-4 px-1',
        'sides' => 'absolute inset-y-0 left-0 right-0 flex items-center justify-between px-3 pointer-events-none z-10',
        default => 'flex items-center justify-between mt-4',
    })
    ;

$buttonClasses = match ($variant) {
    'wizard' => Flux::classes()
        ->add('px-4 py-2 rounded-lg font-medium text-sm')
        ->add('transition-colors duration-200')
        ->add('focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500')
        ->add('pointer-events-auto')
        ->add('disabled:opacity-50 disabled:cursor-not-allowed')
        ,
    default => Flux::classes()
        ->add('size-10 rounded-full flex items-center justify-center')
        ->add('bg-white/90 dark:bg-zinc-800/90 backdrop-blur-sm')
        ->add('border border-zinc-200 dark:border-zinc-700')
        ->add('text-zinc-700 dark:text-zinc-300')
        ->add('hover:bg-white dark:hover:bg-zinc-800')
        ->add('hover:text-zinc-900 dark:hover:text-white')
        ->add('shadow-sm hover:shadow')
        ->add('transition-all duration-200')
        ->add('focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500')
        ->add('disabled:opacity-50 disabled:cursor-not-allowed')
        ->add('pointer-events-auto')
        ,
};

$prevButtonClasses = match ($variant) {
    // mr-auto pushes the Back button to the left, keeping Next on the right
    'wizard' => $buttonClasses . ' mr-auto text-zinc-600 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-white hover:bg-zinc-100 dark:hover:bg-zinc-800',
    default => $buttonClasses,
};

$nextButtonClasses = match ($variant) {
    'wizard' => $buttonClasses . ' bg-blue-600 text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600',
    default => $buttonClasses,
};
@endphp

{{-- 
    Simple approach: Controls live inside the carousel's x-data scope.
    We just call prev()/next() directly - Alpine resolves them from the parent carousel.
    No caching, no events, no complex state management.
--}}
<div
    {{ $attributes->except('wire:submit')->class($containerClasses) }}
    data-flux-carousel-controls
>
    @if ($showPrev)
        <button
            type="button"
            class="{{ $prevButtonClasses }}"
            x-on:click.prevent="prev()"
            :disabled="!canGoPrev()"
            x-show="canGoPrev() || loop"
            aria-label="{{ $prevLabel }}"
        >
            @if ($variant === 'wizard')
                <span>{{ $prevLabel }}</span>
            @else
                <flux:icon.chevron-left class="size-5 rtl:rotate-180" />
            @endif
        </button>
    @else
        <div></div>
    @endif

    @if ($showNext)
        <button
            type="button"
            class="{{ $nextButtonClasses }}"
            {{-- For wizard variant on last step: --}}
            {{-- - If wire:submit is provided, call the method and dispatch finish event --}}
            {{-- - If no wire:submit, just navigate (no finish action) --}}
            @if ($variant === 'wizard')
                @if ($wireSubmit)
                    x-on:click.prevent="if (isLast()) { $wire.call('{{ $wireSubmit }}'); $dispatch('carousel-finish'); } else { next(); }"
                @else
                    x-on:click.prevent="next()"
                @endif
            @else
                x-on:click.prevent="next()"
            @endif
            {{-- For wizard variant with wire:submit, don't disable on last step (allow "Complete" action) --}}
            {{-- For wizard without wire:submit, disable on last step like normal --}}
            :disabled="{{ ($variant === 'wizard' && $wireSubmit) ? 'false' : '!canGoNext()' }}"
            aria-label="{{ $nextLabel }}"
        >
            @if ($variant === 'wizard' && $wireSubmit)
                {{-- Only show Finish label when wire:submit is provided --}}
                <span x-text="isLast() ? '{{ $finishLabel }}' : '{{ $nextLabel }}'">{{ $nextLabel }}</span>
            @elseif ($variant === 'wizard')
                {{-- No wire:submit, always show Next label --}}
                <span>{{ $nextLabel }}</span>
            @else
                <flux:icon.chevron-right class="size-5 rtl:rotate-180" />
            @endif
        </button>
    @endif
</div>
