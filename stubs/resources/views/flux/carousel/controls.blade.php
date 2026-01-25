@blaze

{{--
    Carousel Controls: Navigation controls for the carousel.

    Provides various styles of navigation:
    - dots (default): Simple dot indicators for current position
    - arrows: Prev/next arrow buttons (overlay or bottom)
    - buttons: Text buttons with Back/Next labels (default for wizard)
    - minimal: Just dots, no arrows
    
    Position options:
    - overlay: Controls positioned over the panels
    - bottom: Controls below the panels (default)
    - sides: Controls on left/right sides
    
    Wizard submit handling:
    - Use wire:submit="methodName" to call a Livewire method when finish is clicked
    - Dispatches 'carousel-finish' event for Alpine.js listeners
--}}

@aware(['variant' => 'directional', 'wireSubmit' => null])

@props([
    'variant' => null,
    'style' => null, // 'dots' (default), 'arrows', 'buttons', 'minimal'
    'position' => null, // Auto-determined based on variant if not set
    'showPrev' => true,
    'showNext' => true,
    'showDots' => null, // Auto-determined: true for dots/minimal, false for arrows/buttons
    'prevLabel' => null,
    'nextLabel' => null,
    'finishLabel' => 'Finish',
    'wireSubmit' => null, // Can be passed as a prop directly
    'flush' => false, // Remove default spacing/margins (for custom containers)
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

// Auto-determine style based on variant if not explicitly set
// - wizard: uses 'buttons' style by default
// - directional/thumbnail: uses 'dots' style by default
$style = $style ?? ($variant === 'wizard' ? 'buttons' : 'dots');

// Determine what to show based on style
$showArrows = in_array($style, ['arrows']);
$showButtons = in_array($style, ['buttons']);
$showDots = $showDots ?? in_array($style, ['dots', 'minimal', 'arrows']);

// Auto-determine position based on style/variant if not explicitly set
$position = $position ?? match ($style) {
    'arrows' => 'overlay',
    'buttons' => 'bottom',
    default => 'bottom',
};

// Default labels based on variant
$prevLabel = $prevLabel ?? ($variant === 'wizard' ? 'Back' : 'Previous');
$nextLabel = $nextLabel ?? ($variant === 'wizard' ? 'Next' : 'Next');

$containerClasses = Flux::classes()
    ->add(match ($position) {
        // Overlay positions arrows on left/right sides, vertically centered within the panels
        'overlay' => 'absolute inset-y-0 left-0 right-0 flex items-center justify-between px-3 pointer-events-none z-10',
        // For buttons style, use justify-end so Next button is always on right, Back button uses mr-auto
        // For dots style, center the dots
        // Only add margin/padding when not in flush mode (flush is for custom containers)
        'bottom' => $flush 
            ? ($showButtons ? 'flex items-center justify-end' : 'flex items-center justify-center')
            : ($showButtons ? 'flex items-center justify-end mt-4 px-1' : 'flex items-center justify-center mt-4'),
        'sides' => 'absolute inset-y-0 left-0 right-0 flex items-center justify-between px-3 pointer-events-none z-10',
        default => $flush ? 'flex items-center justify-center' : 'flex items-center justify-center mt-4',
    })
    ;

// Arrow button classes (for arrows style)
$arrowButtonClasses = Flux::classes()
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
    ;

// Text button classes (for buttons/wizard style)
$textButtonClasses = Flux::classes()
    ->add('px-4 py-2 rounded-lg font-medium text-sm')
    ->add('transition-colors duration-200')
    ->add('focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500')
    ->add('pointer-events-auto')
    ->add('disabled:opacity-50 disabled:cursor-not-allowed')
    ;

$prevButtonClasses = match ($style) {
    'arrows' => $arrowButtonClasses,
    'buttons' => $textButtonClasses . ' mr-auto text-zinc-600 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-white hover:bg-zinc-100 dark:hover:bg-zinc-800',
    default => $arrowButtonClasses,
};

$nextButtonClasses = match ($style) {
    'arrows' => $arrowButtonClasses,
    'buttons' => $textButtonClasses . ' bg-blue-600 text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600',
    default => $arrowButtonClasses,
};

// Dot indicator classes
$dotContainerClasses = Flux::classes()
    ->add('flex items-center gap-1.5')
    ;

$dotClasses = Flux::classes()
    ->add('size-2 rounded-full transition-all duration-200 cursor-pointer')
    ->add('hover:scale-125')
    ;

$dotActiveClasses = 'bg-blue-500 dark:bg-blue-400';
$dotInactiveClasses = 'bg-zinc-300 dark:bg-zinc-600 hover:bg-zinc-400 dark:hover:bg-zinc-500';
@endphp

{{-- 
    Simple approach: Controls live inside the carousel's x-data scope.
    We just call prev()/next() directly - Alpine resolves them from the parent carousel.
    No caching, no events, no complex state management.
--}}
<div
    {{ $attributes->except('wire:submit')->class($containerClasses) }}
    data-flux-carousel-controls
    data-style="{{ $style }}"
>
    {{-- Previous Button (arrows or buttons style only) --}}
    @if (($showArrows || $showButtons) && $showPrev)
        <button
            type="button"
            class="{{ $prevButtonClasses }}"
            x-on:click.prevent="prev()"
            :disabled="!canGoPrev()"
            x-show="canGoPrev() || loop"
            aria-label="{{ $prevLabel }}"
        >
            @if ($showButtons)
                <span>{{ $prevLabel }}</span>
            @else
                <flux:icon.chevron-left class="size-5 rtl:rotate-180" />
            @endif
        </button>
    @elseif ($showArrows || $showButtons)
        <div></div>
    @endif

    {{-- Dot Indicators (dots, minimal, or arrows style) --}}
    @if ($showDots)
        <div class="{{ $dotContainerClasses }}" role="tablist" aria-label="Slide navigation">
            <template x-for="(step, index) in totalSteps" :key="index">
                <button
                    type="button"
                    class="{{ $dotClasses }}"
                    :class="activeIndex === index ? '{{ $dotActiveClasses }}' : '{{ $dotInactiveClasses }}'"
                    x-on:click="goToIndex(index)"
                    :aria-selected="activeIndex === index"
                    :aria-label="'Go to slide ' + (index + 1)"
                    role="tab"
                ></button>
            </template>
        </div>
    @endif

    {{-- Next Button (arrows or buttons style only) --}}
    @if (($showArrows || $showButtons) && $showNext)
        <button
            type="button"
            class="{{ $nextButtonClasses }}"
            {{-- For buttons style with wizard variant on last step: --}}
            {{-- - If wire:submit is provided, call the method and dispatch finish event --}}
            {{-- - If no wire:submit but has parent carousel, advance parent on last step --}}
            {{-- - Otherwise, just navigate normally --}}
            @if ($showButtons && $variant === 'wizard')
                @if ($wireSubmit)
                    x-on:click.prevent="if (isLast()) { $wire.call('{{ $wireSubmit }}'); $dispatch('carousel-finish'); } else { next(); }"
                @else
                    x-on:click.prevent="
                        if (totalSteps > 0 && isLast() && parentCarousel && parent && typeof parent.next === 'function') {
                            parent.next();
                        } else {
                            next();
                        }
                    "
                @endif
            @else
                x-on:click.prevent="next()"
            @endif
            {{-- Visibility and disabled state logic --}}
            x-show="{{ ($showButtons && $variant === 'wizard' && !$wireSubmit) ? '!(isLast() && !parentCarousel && !loop)' : 'true' }}"
            @if ($showButtons && $variant === 'wizard' && $wireSubmit)
                :disabled="false"
            @elseif ($showButtons && $variant === 'wizard')
                :disabled="!(!isLast() || (isLast() && parentCarousel && parent && typeof parent.next === 'function') || loop)"
            @else
                :disabled="!canGoNext()"
            @endif
            aria-label="{{ $nextLabel }}"
        >
            @if ($showButtons && $variant === 'wizard' && $wireSubmit)
                <span x-text="isLast() ? '{{ $finishLabel }}' : '{{ $nextLabel }}'">{{ $nextLabel }}</span>
            @elseif ($showButtons)
                <span>{{ $nextLabel }}</span>
            @else
                <flux:icon.chevron-right class="size-5 rtl:rotate-180" />
            @endif
        </button>
    @endif
</div>
