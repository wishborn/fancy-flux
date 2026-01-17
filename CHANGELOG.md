# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2024-12-19

### Added
- **Carousel Component**: Flexible carousel/slideshow component with multiple variants
  - Directional variant with prev/next navigation and autoplay support
  - Wizard variant with step-based navigation and numbered indicators
  - Thumbnail variant with image-based navigation and preview thumbnails
  - Data-driven and slot-based usage patterns
  - Programmatic navigation helpers via `$this->carousel('name')` in Livewire components
  - JavaScript helper via `Flux.carousel('name')` for Alpine.js integration

- **Color Picker Component**: Native color input with enhanced UI
  - Swatch preview showing selected color
  - Hex value display (uppercase)
  - Size variants (sm, default, lg)
  - Style variants (outline, filled)
  - Custom preset colors support
  - Full Livewire wire:model integration
  - Works with or without labels

- **Service Provider**: Automatic component registration
- **JavaScript Helpers**: Carousel control helpers for Alpine.js integration
- **Browser Tests**: Comprehensive test coverage for both components

### Requirements
- PHP 8.2+
- Laravel 10+ / 11+ / 12+
- Livewire 3.7+ / 4.0+
- Flux UI 2.0+

[1.0.0]: https://github.com/wishborn/fancy-flux/releases/tag/v1.0.0
