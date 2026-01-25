@blaze

{{--
    Carousel Tabs Container: Renders clickable navigation tabs for the carousel.

    Why: "Tabs" matches the ARIA semantics (role="tablist") and clearly conveys
    that these are interactive navigation elements, not passive indicators.

    For directional variant: Shows dot tabs
    For wizard variant: Shows pill/button tabs with labels
    For thumbnail variant: Shows small preview image tabs

    This component is optional - if omitted, only prev/next controls will be available.
    
    When headless mode is enabled on the parent carousel, this component will be hidden
    but still rendered in the DOM for accessibility purposes.

    @example
    <flux:carousel.tabs>
        <flux:carousel.tab name="intro" label="Introduction" />
        <flux:carousel.tab name="details" label="Details" />
    </flux:carousel.tabs>
--}}

@aware(['variant' => 'directional', 'headless' => false])

@props([
    'variant' => null,
    'headless' => null, // Override parent headless setting
    'flush' => false, // Remove default spacing/padding (for custom containers)
])

@php
// Use aware values if not explicitly set
$variant = $variant ?? 'directional';
$headless = $headless ?? false;

$classes = Flux::classes()
    ->add('flex items-center justify-center')
    ->add(match ($variant) {
        // In flush mode, only add gap - no padding (for custom containers)
        'wizard' => $flush ? 'gap-2' : 'gap-2 py-4',
        'thumbnail' => $flush ? 'gap-2 overflow-x-auto' : 'gap-2 py-3 overflow-x-auto',
        default => $flush ? 'gap-2' : 'gap-2 pt-3',
    })
    ;
@endphp

{{-- In headless mode, hide visually but keep for screen readers --}}
{{-- Provide flush to child tabs via x-data so they can inherit the setting --}}
<div
    {{ $attributes->class($classes) }}
    @if($headless) x-show="false" @endif
    data-flux-carousel-steps
    data-flush="{{ $flush ? 'true' : 'false' }}"
    role="tablist"
    aria-label="Carousel navigation"
>
    {{ $slot }}
</div>
