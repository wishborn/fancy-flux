@blaze

{{--
    Drawer Controls: Navigation controls for multi-panel drawers.

    Provides prev/next navigation buttons for navigating between
    drawer panels. Uses the carousel controls pattern.

    @example
    <flux:drawer.controls />
--}}

@aware(['drawerId' => null, 'variant' => 'drawer'])

@props([
    'drawerId' => null,
    'variant' => null,
    'showPrev' => true,
    'showNext' => true,
    'prevLabel' => 'Back',
    'nextLabel' => 'Next',
])

@php
// Use aware values if not explicitly set
$drawerId = $drawerId ?? 'drawer-' . uniqid();
$variant = $variant ?? 'drawer';

$containerClasses = Flux::classes()
    ->add('flex items-center justify-between')
    ->add('px-6 py-4')
    ->add('border-t border-zinc-200 dark:border-zinc-700')
    ->add('bg-zinc-50 dark:bg-zinc-900/50')
    ;

$buttonClasses = Flux::classes()
    ->add('px-4 py-2 rounded-lg font-medium text-sm')
    ->add('transition-colors duration-200')
    ->add('focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500')
    ->add('disabled:opacity-50 disabled:cursor-not-allowed')
    ;

$prevButtonClasses = $buttonClasses . ' text-zinc-600 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-white hover:bg-zinc-100 dark:hover:bg-zinc-800';
$nextButtonClasses = $buttonClasses . ' bg-blue-600 text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600';
@endphp

<div
    {{ $attributes->class($containerClasses) }}
    data-flux-drawer-controls
>
    {{-- Previous Button --}}
    @if ($showPrev)
        <button
            type="button"
            class="{{ $prevButtonClasses }}"
            x-on:click.prevent="prev()"
            :disabled="activeIndex <= 0"
            x-show="activeIndex > 0"
            aria-label="{{ $prevLabel }}"
        >
            {{ $prevLabel }}
        </button>
    @else
        <div></div>
    @endif

    {{-- Next Button --}}
    @if ($showNext)
        <button
            type="button"
            class="{{ $nextButtonClasses }}"
            x-on:click.prevent="next()"
            :disabled="activeIndex >= panels.length - 1"
            x-show="activeIndex < panels.length - 1"
            aria-label="{{ $nextLabel }}"
        >
            {{ $nextLabel }}
        </button>
    @else
        <div></div>
    @endif
</div>
