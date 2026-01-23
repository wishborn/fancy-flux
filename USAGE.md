# Fancy Flux Usage Guide

Comprehensive usage instructions for all Fancy Flux components with tested examples.

## Components

- **[Action](docs/action.md)** - Reusable button with states, icons, and emojis
- **[Carousel](docs/carousel.md)** - Flexible carousel/slideshow with multiple variants
- **[Color Picker](docs/color-picker.md)** - Native color input with enhanced UI
- **[Emoji Select](docs/emoji-select.md)** - Composable emoji picker with category navigation

## FANCY Facade

The `FANCY` facade provides programmatic access to FancyFlux features:

- **[FANCY Facade](docs/facade.md)** - Emoji lookup, carousel control, and configuration

```php
// Emoji lookup
FANCY::emoji('fire');           // 🔥
FANCY::emoji()->list();         // All 787+ slugs
FANCY::emoji()->search('heart'); // Search emojis

// Carousel control
FANCY::carousel('wizard')->next();
FANCY::carousel('wizard')->goTo('step-3');
```

## Quick Links

### Action
- [State Variants](docs/action.md#state-variants)
- [Icon Placement](docs/action.md#icon-placement)
- [Emoji Support](docs/action.md#emoji-support)
- [Size Variants](docs/action.md#size-variants)

### Carousel
- [Data-Driven Carousel](docs/carousel.md#data-driven-carousel)
- [Wizard Variant](docs/carousel.md#wizard-variant)
- [Nested Carousels](docs/carousel.md#nested-carousels)
- [Programmatic Navigation](docs/carousel.md#programmatic-navigation)

### Color Picker
- [Basic Usage](docs/color-picker.md#basic-usage)
- [Size Variants](docs/color-picker.md#size-variants)
- [Custom Presets](docs/color-picker.md#custom-presets)

### Emoji Select
- [Basic Usage](docs/emoji-select.md#basic-usage)
- [Size Variants](docs/emoji-select.md#size-variants)
- [In Form Groups](docs/emoji-select.md#in-form-groups)

## Demos

Ready-to-use examples are available in the [demos folder](demos/):

- [Action Examples](demos/action-examples/)
- [Basic Carousel](demos/basic-carousel/)
- [Wizard Form](demos/wizard-form/)
- [Nested Carousel](demos/nested-carousel/)
- [Dynamic Carousel](demos/dynamic-carousel/)
- [Color Picker Examples](demos/color-picker-examples/)
- [Emoji Select Examples](demos/emoji-select-examples/)

## Multiple Components on One Page

When using multiple carousels on the same page, always provide unique `name` props:

```blade
<flux:carousel name="carousel-1" :data="$slides1" />
<flux:carousel name="carousel-2" :data="$slides2" />
```

If you're listening to carousel events in Livewire, filter by carousel ID:

```php
#[On('carousel-navigated')]
public function onCarouselNavigated(string $id, string $name): void
{
    // Only respond to specific carousel
    if ($id === 'my-carousel') {
        $this->currentStep = $name;
    }
}
```
