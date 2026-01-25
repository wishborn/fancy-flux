@blaze

{{--
    Carousel Component: A flexible carousel/slideshow with different control types.

    Supports two usage patterns:
    
    1. Data-driven (simple): Pass an array of slides via the data prop
       <flux:carousel :data="$slides" />
       
       Each slide in the array can have: name, label, description, src (image URL)
    
    2. Slot-based (flexible): Use sub-components for full control
       <flux:carousel>
           <flux:carousel.panels>
               <flux:carousel.panel name="intro">Content here</flux:carousel.panel>
           </flux:carousel.panels>
           <flux:carousel.controls />
           <flux:carousel.tabs>
               <flux:carousel.tab name="intro" label="Introduction" />
           </flux:carousel.tabs>
       </flux:carousel>

    Sub-components:
    - flux:carousel.panels - Container for content panels
    - flux:carousel.panel - Individual content panel
    - flux:carousel.controls - Prev/Next navigation buttons
    - flux:carousel.tabs - Container for navigation tabs (clickable)
    - flux:carousel.tab - Individual navigation tab (matches ARIA role="tab")

    Variants:
    - directional (default): Navigation with prev/next arrows, supports autoplay
    - wizard: Step-based navigation with pill indicators
    - thumbnail: Navigation with small preview images

    Features:
    - Dynamic slides via data prop or slot
    - Headless mode: Hide progress indicators for agentic/programmatic control
    - External control: Listen for carousel-next, carousel-prev, carousel-goto events
    - Livewire compatible: Works with wire:model for reactive data
    - Good-looking out of the box with sensible defaults

    Accessibility is always enabled (not optional).
--}}

@props([
    'variant' => 'directional',
    'data' => null, // Array of slides: [{name, label, description?, src?}, ...]
    'autoplay' => null,
    'interval' => 5000,
    'loop' => true,
    'name' => null, // Carousel instance name (for external control)
    'headless' => false, // Hide progress indicators (useful for agentic workflows)
    'wireSubmit' => null, // Livewire method to call on wizard finish (shorthand for wire:submit on controls)
    'parentCarousel' => null, // Parent carousel ID/name (for nested carousels in wizard variants)
])

@php
// Extract wire:submit from attributes if provided directly on carousel
// Remove it from attributes to prevent Livewire from wrapping in a form
$wireSubmit = $wireSubmit ?? $attributes->get('wire:submit');
if ($attributes->has('wire:submit')) {
    $attributes = $attributes->except('wire:submit');
}
@endphp

@php
// Autoplay is only available for directional variant
$autoplay = $variant === 'directional' ? $autoplay : false;

// Generate a unique ID for this carousel instance
$carouselId = $name ?? 'carousel-' . uniqid();

// Normalize data to array if provided
$slides = $data ? (is_array($data) ? $data : (array) $data) : null;

// If data is provided, ensure each slide has a name
if ($slides) {
    $slides = array_map(function ($slide, $index) {
        $slide = (array) $slide;
        if (!isset($slide['name'])) {
            $slide['name'] = $slide['id'] ?? 'slide-' . $index;
        }
        return $slide;
    }, $slides, array_keys($slides));
}

$classes = Flux::classes()
    ->add('relative w-full')
    ;
@endphp

<div
    {{ $attributes->merge(['wire:key' => 'carousel-' . $carouselId])->class($classes) }}
    x-data="{
        {{-- Registry of step names in order --}}
        steps: [],
        {{-- Current active step name --}}
        active: null,
        {{-- Whether autoplay is enabled --}}
        autoplay: {{ $autoplay ? 'true' : 'false' }},
        {{-- Autoplay interval in milliseconds --}}
        interval: {{ $interval }},
        {{-- Whether to loop back to start --}}
        loop: {{ $loop ? 'true' : 'false' }},
        {{-- Autoplay timer reference --}}
        timer: null,
        {{-- Variant for styling differences --}}
        variant: '{{ $variant }}',
        {{-- Headless mode for hiding indicators --}}
        headless: {{ $headless ? 'true' : 'false' }},
        {{-- Parent carousel reference (for nested carousels) --}}
        parentCarousel: {{ $parentCarousel ? "'{$parentCarousel}'" : 'null' }},
        {{-- Get parent carousel helper (for wizard variants) --}}
        get parent() {
            if (!this.parentCarousel) return null;
            return window.Flux?.carousel?.(this.parentCarousel) || null;
        },
        {{-- Initialize the carousel --}}
        init() {
            {{-- Collect steps after a short delay to ensure DOM is ready --}}
            this.$nextTick(() => {
                this.collectSteps();
                if (this.steps.length > 0) {
                    {{-- Set active step if not already set or if current active is invalid --}}
                    if (!this.active || !this.steps.includes(this.active)) {
                        this.active = this.steps[0];
                    }
                }
            });
            if (this.autoplay) {
                this.startAutoplay();
            }
        },
        {{-- Collect step names from DOM --}}
        {{-- Only collect direct children, not nested carousel steps --}}
        collectSteps() {
            {{-- Find the panels container (direct child) --}}
            const panelsContainer = this.$el.querySelector('[data-flux-carousel-panels]');
            if (!panelsContainer) {
                this.steps = [];
                return;
            }
            {{-- Only query step items that are direct children of the panels container --}}
            {{-- This prevents collecting steps from nested carousels --}}
            {{-- Use children array instead of :scope selector for better compatibility --}}
            this.steps = Array.from(panelsContainer.children)
                .filter(el => el.hasAttribute('data-flux-carousel-step-item'))
                .map(el => el.dataset.name)
                .filter(name => name);
        },
        {{-- Refresh steps (call after dynamic changes) --}}
        refresh() {
            const oldActive = this.active;
            this.collectSteps();
            if (!this.steps.includes(oldActive)) {
                this.active = this.steps[0] || null;
            }
        },
        {{-- Get current index --}}
        get activeIndex() {
            return this.steps.indexOf(this.active);
        },
        {{-- Get total steps count --}}
        get totalSteps() {
            return this.steps.length;
        },
        {{-- Navigate to a specific step by name --}}
        goTo(name) {
            if (this.steps.includes(name)) {
                this.active = name;
                this.resetAutoplay();
                this.$dispatch('carousel-navigated', { id: '{{ $carouselId }}', name: this.active, index: this.activeIndex, totalSteps: this.totalSteps });
            }
        },
        {{-- Navigate to a specific step by index --}}
        goToIndex(index) {
            if (index < 0) {
                index = this.loop ? this.totalSteps - 1 : 0;
            } else if (index >= this.totalSteps) {
                index = this.loop ? 0 : this.totalSteps - 1;
            }
            if (this.steps[index]) {
                this.goTo(this.steps[index]);
            }
        },
        {{-- Go to next step --}}
        next() {
            this.goToIndex(this.activeIndex + 1);
        },
        {{-- Go to previous step --}}
        prev() {
            this.goToIndex(this.activeIndex - 1);
        },
        {{-- Start autoplay timer --}}
        startAutoplay() {
            if (this.timer) clearInterval(this.timer);
            this.timer = setInterval(() => this.next(), this.interval);
        },
        {{-- Stop autoplay timer --}}
        stopAutoplay() {
            if (this.timer) {
                clearInterval(this.timer);
                this.timer = null;
            }
        },
        {{-- Reset autoplay timer (restart if enabled) --}}
        resetAutoplay() {
            if (this.autoplay) {
                this.stopAutoplay();
                this.startAutoplay();
            }
        },
        {{-- Check if a step is active by name --}}
        isActive(name) {
            return this.active === name;
        },
        {{-- Check if can go to previous --}}
        canGoPrev() {
            if (this.totalSteps === 0) return false;
            {{-- If no active step but steps exist, allow navigation --}}
            if (this.activeIndex === -1 && this.totalSteps > 0) return true;
            return this.loop || this.activeIndex > 0;
        },
        {{-- Check if can go to next --}}
        canGoNext() {
            if (this.totalSteps === 0) return false;
            {{-- If no active step but steps exist, allow navigation --}}
            if (this.activeIndex === -1 && this.totalSteps > 0) return true;
            return this.loop || this.activeIndex < this.totalSteps - 1;
        },
        {{-- Check if on first step --}}
        isFirst() {
            return this.activeIndex === 0;
        },
        {{-- Check if on last step --}}
        isLast() {
            return this.activeIndex === this.totalSteps - 1;
        },
    }"
    x-on:mouseenter="stopAutoplay()"
    x-on:mouseleave="if (autoplay) startAutoplay()"
    {{-- External control events for programmatic navigation --}}
    {{-- Only respond if ID matches exactly, or if no ID is provided (for backward compatibility) --}}
    x-on:carousel-next.window="if ($event.detail?.id === '{{ $carouselId }}' || !$event.detail?.id) next()"
    x-on:carousel-prev.window="if ($event.detail?.id === '{{ $carouselId }}' || !$event.detail?.id) prev()"
    x-on:carousel-goto.window="if ($event.detail?.id === '{{ $carouselId }}' || !$event.detail?.id) { $event.detail?.name ? goTo($event.detail.name) : goToIndex($event.detail?.index ?? 0) }"
    x-on:carousel-refresh.window="if ($event.detail?.id === '{{ $carouselId }}' || !$event.detail?.id) refresh()"
    data-flux-carousel
    data-flux-carousel-variant="{{ $variant }}"
    data-flux-carousel-headless="{{ $headless ? 'true' : 'false' }}"
    id="{{ $carouselId }}"
    role="region"
    aria-roledescription="carousel"
    aria-label="{{ $name ?? 'Carousel' }}"
>
    @if ($slides)
        {{-- Data-driven mode: auto-generate panels from data prop --}}
        <flux:carousel.panels>
            @foreach ($slides as $slide)
                <flux:carousel.panel 
                    :name="$slide['name']" 
                    :label="$slide['label'] ?? null"
                    :description="$slide['description'] ?? null"
                    :src="$slide['src'] ?? $slide['image'] ?? null"
                    :alt="$slide['alt'] ?? $slide['label'] ?? null"
                    wire:key="carousel-{{ $carouselId }}-panel-{{ $slide['name'] }}"
                />
            @endforeach
        </flux:carousel.panels>
        
        <flux:carousel.controls :wireSubmit="$wireSubmit" />
        
        @if (!$headless)
            <flux:carousel.tabs>
                @foreach ($slides as $slide)
                    <flux:carousel.tab 
                        :name="$slide['name']" 
                        :label="in_array($variant, ['wizard', 'thumbnail']) ? ($slide['label'] ?? null) : null"
                        :src="$variant === 'thumbnail' ? ($slide['thumbnail'] ?? $slide['src'] ?? $slide['image'] ?? null) : null"
                        :alt="$variant === 'thumbnail' ? ($slide['alt'] ?? $slide['label'] ?? null) : null"
                        wire:key="carousel-{{ $carouselId }}-tab-{{ $slide['name'] }}"
                    />
                @endforeach
            </flux:carousel.tabs>
        @endif
    @else
        {{-- Slot-based mode: use sub-components --}}
        {{ $slot }}
    @endif
</div>
