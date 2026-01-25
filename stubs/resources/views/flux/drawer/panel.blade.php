@blaze

{{--
    Drawer Panel: A content panel for a single drawer item.

    This is the main content container for each drawer panel.
    Uses carousel.panel internally for transitions.

    Why: "Panel" is a clearer, more universally understood term for drawer
    content containers. Integrates with headless carousel for transitions.

    Supports:
    - Text-based content via slot
    - Reactive updates when content changes
    - Default styling for a polished look out of the box

    @example <flux:drawer.panel name="general" label="General">Content here</flux:drawer.panel>
--}}

@aware(['variant' => 'drawer', 'transitions' => 'fade'])

@props([
    'name' => null, // Unique identifier for this panel (required)
    'label' => null, // Display label
    'description' => null, // Optional description text
    'variant' => null,
    'transitions' => null,
])

@php
// Use aware values if not explicitly set
$variant = $variant ?? 'drawer';
$transitions = $transitions ?? 'fade';

// Name is required for proper functioning
$panelName = $name ?? 'panel-' . uniqid();

// Generate a unique key for wire:key if not provided
$key = $attributes->get('wire:key') ?? 'drawer-panel-' . $panelName;

// Panel items fill the panels container with padding
$classes = Flux::classes()
    ->add('p-6')
    ->add('overflow-y-auto')
    ;
@endphp

{{-- Use carousel panel for transitions --}}
<flux:carousel.panel 
    name="{{ $panelName }}"
    :label="$label"
    :description="$description"
    wire:key="{{ $key }}"
    class="{{ $classes }}"
>
    {{ $slot }}
</flux:carousel.panel>
