@blaze

{{--
    Carousel Panels Container: Container for carousel panel items.

    This wraps all carousel.panel components and provides the sliding/fading
    container for the content panels.
    
    Includes built-in styling for a polished look out of the box:
    - Rounded corners
    - Relative positioning for overlay controls
    - Proper overflow handling

    @example
    <flux:carousel.panels>
        <flux:carousel.panel name="intro">Content here</flux:carousel.panel>
        <flux:carousel.panel name="details">More content</flux:carousel.panel>
    </flux:carousel.panels>
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
