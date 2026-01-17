@blaze

{{--
    Carousel Steps Container: Renders navigation indicators for the carousel.

    For directional variant: Shows dot indicators
    For wizard variant: Shows numbered step indicators with labels

    This component is optional - if omitted, only prev/next controls will be available.
    
    When headless mode is enabled on the parent carousel, this component will be hidden
    but still rendered in the DOM for accessibility purposes.
--}}

@aware(['variant' => 'directional', 'headless' => false])

@props([
    'variant' => null,
    'headless' => null, // Override parent headless setting
])

@php
// Use aware values if not explicitly set
$variant = $variant ?? 'directional';
$headless = $headless ?? false;

$classes = Flux::classes()
    ->add('flex items-center justify-center')
    ->add(match ($variant) {
        'wizard' => 'gap-2 py-4',
        'thumbnail' => 'gap-2 py-3 overflow-x-auto', // Scrollable for many thumbnails
        default => 'gap-2 pt-3', // Minimal top padding, no bottom padding for dots
    })
    ;
@endphp

{{-- In headless mode, hide visually but keep for screen readers --}}
<div
    {{ $attributes->class($classes) }}
    @if($headless) x-show="false" @endif
    data-flux-carousel-steps
    role="tablist"
    aria-label="Carousel navigation"
>
    {{ $slot }}
</div>
