@blaze

{{--
    Table Row Component: Individual row in composable mode.

    Why: Provides row-level control including selection state binding
    and tray expansion.
--}}

@props([
    'key' => null, // Unique row identifier for selection/tray
    'striped' => false, // Apply striped styling
    'hoverable' => true, // Highlight on hover
])

@php
$classes = Flux::classes()
    ->add($striped ? 'even:bg-zinc-50 dark:even:bg-zinc-800/50' : '')
    ->add($hoverable ? 'hover:bg-zinc-50 dark:hover:bg-zinc-800' : '')
    ;
@endphp

<tr
    {{ $attributes->class($classes) }}
    @if ($key)
        x-bind:class="{ 'bg-blue-50 dark:bg-blue-900/20': isSelected('{{ $key }}') }"
        data-row-key="{{ $key }}"
    @endif
>
    {{ $slot }}
</tr>
