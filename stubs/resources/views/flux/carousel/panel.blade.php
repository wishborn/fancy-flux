@blaze

{{--
    Carousel Panel: A content panel for a single carousel item.

    This is the main content container for each carousel slide/step.
    Uses "name" prop like flux:tab.panel for consistency.

    Why: "Panel" is a clearer, more universally understood term for carousel
    content containers. Replaces the nested "step.item" naming.

    Supports:
    - Text-based content via slot
    - Resource-based content via src prop (images, files, or endpoints)
    - Reactive updates when content changes
    - Default height and styling for a polished look out of the box
    - Label overlay on images when label is provided

    @example <flux:carousel.panel name="intro">Welcome content here</flux:carousel.panel>
    @example <flux:carousel.panel name="slide-1" src="/images/hero.jpg" label="Hero Image" />
--}}

@aware(['variant' => 'directional'])

@props([
    'name' => null, // Unique identifier for this panel (required)
    'label' => null, // Display label (shown in overlay for images)
    'description' => null, // Optional description text below the label
    'src' => null,
    'alt' => null,
    'variant' => null,
])

@php
// Use aware variant if not explicitly set
$variant = $variant ?? 'directional';

// Name is required for proper functioning
$panelName = $name ?? 'panel-' . uniqid();

// Generate a unique key for wire:key if not provided
// Use the provided wire:key if available, otherwise generate one based on panel name
// Note: It's better to provide wire:key from parent that includes carousel ID
$key = $attributes->get('wire:key') ?? 'carousel-panel-' . $panelName;

// Panel items fill the panels container
// Using absolute positioning for proper fade transitions
$classes = Flux::classes()
    ->add('w-full h-full')
    ;

// Content wrapper with default height for barebones usage (only used for src-based content)
$contentClasses = Flux::classes()
    ->add('w-full')
    // Default minimum height so carousel looks good even with minimal content
    ->add('min-h-[200px]')
    ;
@endphp

<div
    {{ $attributes->except('wire:key')->class($classes) }}
    wire:key="{{ $key }}"
    x-show="isActive('{{ $panelName }}')"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200 absolute inset-0"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    data-flux-carousel-step-item
    data-name="{{ $panelName }}"
    role="tabpanel"
    :aria-hidden="!isActive('{{ $panelName }}')"
    aria-label="{{ $label ?? $panelName }}"
>
    @if ($src)
        {{-- Resource-based content (image, file, or endpoint) --}}
        <div class="{{ $contentClasses }}">
            @if (Str::startsWith($src, ['http://', 'https://']) || Str::endsWith($src, ['.jpg', '.jpeg', '.png', '.gif', '.webp', '.svg']))
                <div class="relative w-full h-full min-h-[200px]">
                    <img
                        src="{{ $src }}"
                        alt="{{ $alt ?? $label ?? 'Carousel image ' . $panelName }}"
                        class="w-full h-full object-cover rounded-xl"
                        loading="lazy"
                    />
                    {{-- Label overlay on images --}}
                    @if ($label || $description)
                        <div class="absolute inset-0 rounded-xl bg-gradient-to-t from-black/70 via-black/20 to-transparent flex items-end">
                            <div class="p-6 text-white">
                                @if ($label)
                                    <h3 class="text-xl font-semibold">{{ $label }}</h3>
                                @endif
                                @if ($description)
                                    <p class="text-white/80 mt-1 text-sm">{{ $description }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                    {{-- Slot content overlays the image if provided --}}
                    @if ($slot->isNotEmpty())
                        <div class="absolute inset-0 rounded-xl">
                            {{ $slot }}
                        </div>
                    @endif
                </div>
            @else
                {{-- For other resources, render as content with default styling --}}
                <div class="w-full h-full min-h-[200px] flex items-center justify-center p-6 text-zinc-600 dark:text-zinc-400">
                    {{ $src }}
                </div>
            @endif
        </div>
    @elseif ($slot->isNotEmpty())
        {{-- Slot content only - no wrapper div, full control to the slot --}}
        {{ $slot }}
    @else
        {{-- Default placeholder content when nothing is provided --}}
        <div class="{{ $contentClasses }}">
            <div class="w-full h-full min-h-[200px] flex items-center justify-center p-6">
                <div class="text-center text-zinc-400 dark:text-zinc-500">
                    <div class="text-4xl mb-2">{{ $label ?? $panelName }}</div>
                    <div class="text-sm">{{ $description ?? 'Panel: ' . $panelName }}</div>
                </div>
            </div>
        </div>
    @endif
</div>
