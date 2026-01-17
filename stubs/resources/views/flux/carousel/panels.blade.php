@blaze

{{--
    Carousel Panels Container: Container for carousel step items.

    This wraps all carousel.step.item components and provides the sliding/fading
    container for the content panels.
    
    Includes built-in styling for a polished look out of the box:
    - Rounded corners
    - Relative positioning for overlay controls
    - Proper overflow handling
--}}

@aware(['variant' => 'directional'])

@props([
    'variant' => null,
])

@php
// Use aware variant if not explicitly set
$variant = $variant ?? 'directional';

$classes = Flux::classes()
    // Core layout - relative for absolute positioned controls
    ->add('relative w-full overflow-hidden')
    // Default rounded corners for polished look
    ->add('rounded-xl')
    // Subtle background for empty state / loading
    ->add('bg-zinc-100 dark:bg-zinc-800')
    ;
@endphp

<div
    {{ $attributes->class($classes) }}
    data-flux-carousel-panels
    role="group"
    aria-live="polite"
>
    {{ $slot }}
</div>
