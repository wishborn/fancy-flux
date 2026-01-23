# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.5] - 2026-01-23

### Added
- **FANCY Facade**: Unified API for programmatic access to FancyFlux features
  - `FANCY::emoji('slug')` - Get emoji character by slug
  - `FANCY::emoji()->list()` - List all 787+ available emoji slugs
  - `FANCY::emoji()->find('slug')` - Get full emoji data (char, name, slug, category)
  - `FANCY::emoji()->search('query')` - Search emojis by name or slug
  - `FANCY::emoji()->category('smileys')` - Get emojis by category
  - `FANCY::carousel('name')` - Programmatic carousel control
  - `FANCY::prefix()`, `FANCY::usesFluxNamespace()`, `FANCY::components()` - Configuration access

- **EmojiRepository**: Slug-based emoji lookup system
  - Kebab-case slugs derived from emoji names (e.g., 'grinning-face', 'fire', 'thumbs-up')
  - 8 categories: smileys, people, animals, food, activities, travel, symbols, flags
  - 787+ emojis with search and filtering

- **CarouselManager**: Carousel control via FANCY facade
  - `next()`, `prev()`, `goTo()`, `goToIndex()`, `refresh()`, `refreshAndGoTo()`
  - InteractsWithCarousel trait now delegates to FANCY facade (backward compatible)

- **Action Component Emoji Support**:
  - `emoji` prop - Leading emoji by slug
  - `emoji-trailing` prop - Trailing emoji by slug

### Changed
- InteractsWithCarousel trait now uses FANCY facade internally (backward compatible)
- Updated documentation with FANCY facade examples

## [1.0.4] - 2026-01-22

### Added
- **Action Component**: Reusable button component with state variants and flexible icon placement
  - State variants: default, active (blue), warn (amber), alert (pulse animation)
  - Size variants: sm, md, lg
  - Flexible icon placement: left, right, top, bottom, over, under
  - Alert icon with pulsing animation for attention-grabbing states
  - Full dark mode support
  - Livewire wire:click integration
  - Demo page and browser tests

## [1.0.3] - 2026-01-17

### Added
- Comprehensive USAGE.md documentation with tested examples for all use cases

## [1.0.2] - 2026-01-17

### Changed
- Added Tynn badge to README

## [1.0.1] - 2026-01-17

### Fixed
- Simplified carousel controls to use direct Alpine.js scope resolution
- Controls now properly call parent carousel methods via Alpine's scope chain
- Restored backward compatibility for event listeners with ID fallback

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

[1.0.5]: https://github.com/wishborn/fancy-flux/compare/v1.0.4...v1.0.5
[1.0.4]: https://github.com/wishborn/fancy-flux/compare/v1.0.3...v1.0.4
[1.0.3]: https://github.com/wishborn/fancy-flux/compare/v1.0.2...v1.0.3
[1.0.2]: https://github.com/wishborn/fancy-flux/compare/v1.0.1...v1.0.2
[1.0.1]: https://github.com/wishborn/fancy-flux/compare/v1.0.0...v1.0.1
[1.0.0]: https://github.com/wishborn/fancy-flux/releases/tag/v1.0.0
