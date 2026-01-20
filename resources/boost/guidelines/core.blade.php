## Fancy Flux

Custom Flux UI components for Laravel Livewire applications. Provides enhanced carousel, color picker, and emoji select components that extend the base Flux UI library.

### Features

- **Carousel Component**: Flexible carousel/slideshow with multiple variants (directional, wizard, thumbnail)
- **Color Picker Component**: Native color input with enhanced UI, swatch preview, and preset support
- **Emoji Select Component**: Composable emoji picker with category navigation, search, and customizable styling

### Installation

```bash
composer require wishborn/fancy-flux
```

### Carousel Component

The carousel component supports data-driven and slot-based usage patterns with multiple variants.

**Data-Driven (Simplest):**

@verbatim
<code-snippet name="Data-Driven Carousel" lang="blade">
@@php
$slides = [
    ['name' => 'slide1', 'label' => 'First Slide', 'src' => '/images/slide1.jpg'],
    ['name' => 'slide2', 'label' => 'Second Slide', 'src' => '/images/slide2.jpg'],
];
@@endphp

<flux:carousel :data="$slides" autoplay />
</code-snippet>
@endverbatim

**Wizard Variant (Multi-Step Forms):**

@verbatim
<code-snippet name="Wizard Carousel" lang="blade">
<flux:carousel variant="wizard" :loop="false" name="wizard-form">
    <flux:carousel.steps>
        <flux:carousel.step name="account" label="Account" />
        <flux:carousel.step name="profile" label="Profile" />
    </flux:carousel.steps>
    
    <flux:carousel.panels>
        <flux:carousel.step.item name="account">
            <!-- Form content -->
        </flux:carousel.step.item>
    </flux:carousel.panels>
    
    <flux:carousel.controls wire:submit="submitWizard" />
</flux:carousel>
</code-snippet>
@endverbatim

**Programmatic Navigation (Livewire):**

@verbatim
<code-snippet name="Programmatic Carousel Control" lang="php">
use FancyFlux\Concerns\InteractsWithCarousel;

class MyComponent extends Component
{
    use InteractsWithCarousel;
    
    public function goToStep(string $stepName): void
    {
        $this->carousel('my-carousel')->goTo($stepName);
    }
}
</code-snippet>
@endverbatim

**Nested Carousels:**

@verbatim
<code-snippet name="Nested Carousels" lang="blade">
<flux:carousel variant="wizard" :loop="false" name="parent-wizard">
    <flux:carousel.panels>
        <flux:carousel.step.item name="step1">
            <!-- Nested carousel -->
            <flux:carousel variant="wizard" name="nested-wizard" parentCarousel="parent-wizard">
                <!-- Nested content -->
            </flux:carousel>
        </flux:carousel.step.item>
    </flux:carousel.panels>
</flux:carousel>
</code-snippet>
@endverbatim

### Color Picker Component

Native color input with enhanced UI and preset support.

@verbatim
<code-snippet name="Color Picker" lang="blade">
<flux:color-picker label="Primary Color" wire:model="primaryColor" />

<!-- With custom presets -->
<flux:color-picker 
    label="Brand Colors" 
    wire:model="brandColor"
    :presets="['3b82f6', '8b5cf6', 'ec4899']"
/>
</code-snippet>
@endverbatim

### Emoji Select Component

Composable emoji picker with category navigation and search.

@verbatim
<code-snippet name="Emoji Select" lang="blade">
<flux:emoji-select wire:model.live="selectedEmoji" />

<!-- With label and custom placeholder -->
<flux:emoji-select 
    wire:model.live="reactionEmoji" 
    label="Reaction" 
    placeholder="Choose reaction..." 
/>

<!-- In form groups -->
<flux:input.group>
    <flux:emoji-select wire:model.live="reactionEmoji" />
    <flux:input placeholder="Add a comment..." />
</flux:input.group>
</code-snippet>
@endverbatim

### Key Conventions

- **Component Namespace**: All components use the flux: namespace (e.g., flux:carousel, flux:color-picker)
- **Livewire Integration**: Components work seamlessly with wire:model and wire:submit
- **Unique Names**: When using multiple carousels, always provide unique name props
- **Nested Carousels**: Use parentCarousel prop to link nested carousels to their parent
- **Programmatic Control**: Use InteractsWithCarousel trait in Livewire components for programmatic navigation

### Documentation

- Full documentation: See docs/ folder in package
- Demos: See demos/ folder for ready-to-use examples
- Usage guide: See USAGE.md in package root
