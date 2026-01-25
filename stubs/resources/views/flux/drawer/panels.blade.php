@blaze

{{--
    Drawer Panels Container: Container for drawer panel items.

    This wraps all drawer.panel components and provides the container
    for the content panels. Uses carousel.panels internally for transitions.

    @example
    <flux:drawer.panels>
        <flux:drawer.panel name="general">Content here</flux:drawer.panel>
        <flux:drawer.panel name="advanced">More content</flux:drawer.panel>
    </flux:drawer.panels>
--}}

@aware(['variant' => 'drawer', 'transitions' => 'fade', 'drawerId' => null])

@props([
    'variant' => null,
    'transitions' => null,
    'drawerId' => null,
])

@php
// Use aware values if not explicitly set
$variant = $variant ?? 'drawer';
$transitions = $transitions ?? 'fade';
@endphp

{{-- Use carousel panels for transitions --}}
<div data-flux-drawer-panels {{ $attributes }}>
    <flux:carousel.panels>
        {{ $slot }}
    </flux:carousel.panels>
</div>
