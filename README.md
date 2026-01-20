[![Guided by Tynn](https://img.shields.io/endpoint?url=https%3A%2F%2Ftynn.ai%2Fu%2Fwishborn%2Fflux-dev%2Fbadge.json)](https://tynn.ai/u/wishborn/flux-dev)
[![Latest Version](https://img.shields.io/github/v/release/wishborn/fancy-flux?style=flat-square)](https://github.com/wishborn/fancy-flux/releases)
[![License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

# Fancy Flux

Custom Flux UI components for Laravel Livewire applications.

![Fancy Flux Components](docs/screenshot.png)

## Components

### 🎠 Carousel

A flexible carousel/slideshow component with multiple variants:

- **Directional** - Navigation with prev/next arrows, supports autoplay
- **Wizard** - Step-based navigation with numbered indicators, perfect for multi-step forms
- **Thumbnail** - Image-based navigation with preview thumbnails

**Quick Example:**
```blade
<flux:carousel :data="$slides" autoplay />
```

[📖 Full Documentation](docs/carousel.md) | [💡 Examples](demos/basic-carousel/)

---

### 🎨 Color Picker

A native color input component with enhanced UI, swatch preview, and preset support.

**Quick Example:**
```blade
<flux:color-picker label="Primary Color" wire:model="primaryColor" />
```

[📖 Full Documentation](docs/color-picker.md) | [💡 Examples](demos/color-picker-examples/)

---

### 😀 Emoji Select

A composable emoji picker component with category navigation, search, and customizable styling.

**Quick Example:**
```blade
<flux:emoji-select wire:model.live="selectedEmoji" />
```

[📖 Full Documentation](docs/emoji-select.md) | [💡 Examples](demos/emoji-select-examples/)

---

## Installation

```bash
composer require wishborn/fancy-flux
```

## Documentation

- **[Usage Guide](USAGE.md)** - Comprehensive documentation for all components
- **[Component Docs](docs/)** - Detailed guides for each component:
  - [Carousel](docs/carousel.md)
  - [Color Picker](docs/color-picker.md)
  - [Emoji Select](docs/emoji-select.md)

## Demos

Ready-to-use examples are available in the `demos/` folder. Copy the demo files into your Laravel application to get started quickly:

- **Basic Carousel** - Simple data-driven carousel
- **Wizard Form** - Multi-step form with validation
- **Nested Carousel** - Nested carousels with parent advancement
- **Dynamic Carousel** - Add/remove slides dynamically
- **Color Picker Examples** - All color picker variants
- **Emoji Select Examples** - All emoji select variants

See the [demos README](demos/README.md) for details.

## Laravel Boost Integration

Fancy Flux includes AI guidelines for [Laravel Boost](https://github.com/laravel/boost). When you install this package and run `php artisan boost:install`, Boost will automatically load the guidelines to help AI assistants generate correct code for Fancy Flux components.

### Custom AI Guidelines

You can also add custom AI guidelines for Fancy Flux by creating a `.ai/guidelines/fancy-flux.md` file in your application. This allows you to customize how AI assistants understand and use Fancy Flux components in your specific project context.

## Requirements

- PHP 8.2+
- Laravel 10+ / 11+ / 12+
- Livewire 3.7+ / 4.0+
- Flux UI 2.0+

## License

MIT
