@blaze

{{--
    Table Tray Trigger Component: Button to expand/collapse a row's tray.

    Why: Provides a customizable trigger for tray expansion that can be
    placed anywhere in a row while following the Carousel controls pattern.
--}}

@props([
    'for' => null, // Row key to toggle (required)
    'icon' => 'chevron-down', // Icon when collapsed
    'iconExpanded' => 'chevron-up', // Icon when expanded
    'label' => null, // Optional text label
    'labelExpanded' => null, // Optional label when expanded
    'size' => 'sm', // Size: xs, sm, md
])

@php
$sizeClasses = match($size) {
    'xs' => 'p-1',
    'md' => 'p-3',
    default => 'p-2',
};

$iconSizeClasses = match($size) {
    'xs' => 'h-3 w-3',
    'md' => 'h-5 w-5',
    default => 'h-4 w-4',
};

$classes = Flux::classes()
    ->add('inline-flex items-center justify-center rounded')
    ->add('text-zinc-400 hover:text-zinc-600 dark:text-zinc-500 dark:hover:text-zinc-300')
    ->add('hover:bg-zinc-100 dark:hover:bg-zinc-700')
    ->add('transition-colors duration-150')
    ->add($sizeClasses)
    ;
@endphp

<button
    type="button"
    {{ $attributes->class($classes) }}
    x-on:click="toggleTray('{{ $for }}')"
    x-bind:aria-expanded="isTrayExpanded('{{ $for }}')"
    aria-controls="tray-{{ $for }}"
    data-flux-table-tray-trigger="{{ $for }}"
>
    @if ($label || $labelExpanded)
        <span x-show="!isTrayExpanded('{{ $for }}')">{{ $label ?? 'Show details' }}</span>
        <span x-show="isTrayExpanded('{{ $for }}')" x-cloak>{{ $labelExpanded ?? $label ?? 'Hide details' }}</span>
    @endif

    <flux:icon
        :name="$icon"
        :class="$iconSizeClasses . ' transition-transform duration-200'"
        x-show="!isTrayExpanded('{{ $for }}')"
    />
    <flux:icon
        :name="$iconExpanded"
        :class="$iconSizeClasses . ' transition-transform duration-200'"
        x-show="isTrayExpanded('{{ $for }}')"
        x-cloak
    />
</button>
