# Fancy Flux

Custom Flux UI components for Laravel Livewire applications.

## Components

### Carousel

A flexible carousel/slideshow component with multiple variants:

- **Directional**: Navigation with prev/next arrows, supports autoplay
- **Wizard**: Step-based navigation with numbered indicators
- **Thumbnail**: Image-based navigation with preview thumbnails

### Color Picker

A native color input component with swatch preview and preset support.

## Installation

```bash
composer require wishborn/fancy-flux
```

## Usage

### Carousel

```blade
<flux:carousel :data="$slides" autoplay />
```

See the demo app for more examples.

### Color Picker

```blade
<flux:color-picker wire:model="color" />
```

## Requirements

- PHP 8.2+
- Laravel 10+ / 11+ / 12+
- Livewire 3.7+ / 4.0+
- Flux UI 3.0+
