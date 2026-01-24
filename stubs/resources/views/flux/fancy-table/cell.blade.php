@blaze

{{--
    Table Cell Component: Individual cell in composable mode.

    Why: Provides cell-level styling and alignment control.
--}}

@props([
    'align' => 'left', // Text alignment: left, center, right
    'compact' => false, // Reduce padding
    'nowrap' => true, // Prevent text wrapping
])

@php
$alignClass = match($align) {
    'center' => 'text-center',
    'right' => 'text-right',
    default => 'text-left',
};

$classes = Flux::classes()
    ->add('px-3 text-sm text-zinc-900 dark:text-zinc-100')
    ->add($compact ? 'py-2' : 'py-4')
    ->add($nowrap ? 'whitespace-nowrap' : '')
    ->add($alignClass)
    ;
@endphp

<td {{ $attributes->class($classes) }}>
    {{ $slot }}
</td>
