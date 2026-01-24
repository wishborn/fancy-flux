# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.13] - 2026-01-24

### Added
- **Action Component - New Props**:
  - `variant` prop: Shape variants - 'default' (rounded rectangle) or 'circle' (perfect circle for icon-only buttons)
  - `color` prop: Standalone color theming independent of states - blue, emerald, amber, red, violet, indigo, sky, rose, orange, zinc
  - `checked` prop: Toggle/checkbox behavioral state (emerald by default)
  - `avatar` prop: Display circular avatar images
  - `avatar-trailing` prop: Place avatar on trailing side
  - `badge` prop: Display text badges (notification counts, labels)
  - `badge-trailing` prop: Place badge on trailing side
  - `sort` prop: Control element display order (e=emoji, i=icon, a=avatar, b=badge)

- **Kitchen Sink Demo - RAMPAGE! Button**:
  - Interactive demo showing all Action component features
  - Circle play button that transforms into chaotic cycling button
  - Demonstrates dynamic color, emoji, icon, badge, and sort changes

### Changed
- **Action Component - Color/State Separation**:
  - `color` prop now takes precedence over all state-based colors
  - States (`active`, `checked`, `warn`, `alert`) are now purely behavioral when `color` is set
  - `alert` state only triggers pulse animation, never changes color

### Notes
- **No breaking changes**: All existing Action component usage continues to work
- States without `color` prop use their default colors (active=blue, checked=emerald, warn=amber)

## [0.5.0] - 2026-01-24 (+GlowUp1)

### Added
- **s13: Carousel Compatibility Audit**: Verified Carousel component's nesting capabilities
  - ✅ 3-level deep nesting works correctly (Carousel in Carousel in Carousel)
  - ✅ Event isolation: nested carousel controls don't affect parent carousels
  - ✅ State management: carousels maintain independent state in dynamic Livewire containers
  - ✅ Performance: 10+ carousels on one page with no JavaScript errors
  - ✅ Collapsible containers: carousels work correctly inside `<details>` elements
  - Added comprehensive browser tests in `tests/Feature/Browser/CarouselNestingTest.php`
  - Added test demo page at `/fancy-flux/carousel-nesting-test`

- **s10: Fancy Table Component**: Advanced data table with composable architecture
  - **Data-driven mode**: Pass `:columns` and `:rows` arrays for quick table generation
  - **Composable mode**: Full slot-based control with subcomponents
  - **Column headers**: Action component props support (icon, active, warn, alert, sortable)
  - **Column features**: Resizable (`resizable`) and reorderable (`reorderable`) props
  - **Row trays**: Expandable detail areas with unified terminators (_table, _carousel, _d3, _view, string)
  - **Multi-select**: Checkbox selection with `wire:model` binding
  - **Search**: Deep path query support for nested data
  - **Pagination**: Carousel-powered page navigation
  - **Virtualization**: Performance optimization for large datasets with prefetch
  - Added `TableManager` and `InteractsWithTable` trait for programmatic control
  - Added `FANCY::table('name')` facade method
  - Added comprehensive browser tests in `tests/Feature/Browser/TableTest.php`
  - Added demo page at `/fancy-flux/table`

### Notes
- **Component name**: Use `<flux:fancy-table>` to avoid conflict with official Flux table
- **D3 placeholder**: D3 visualization terminators show placeholders until s12 is complete

## [1.0.12] - 2026-01-24

### Added
- **Upgrade Notes**: Added comprehensive upgrade guide in TROUBLESHOOT.md covering v1.0.3 to v1.0.11
  - Migration steps and tips
  - New features summary table
  - Quick upgrade command reference

## [1.0.11] - 2026-01-24

### Fixed
- **Laravel Boost Integration**: Fixed `boost:install` crash caused by Blade processing `<flux:...>` components inside `@verbatim` blocks
  - Converted guidelines from `.blade.php` to `.md` format
  - Code snippets now use standard markdown code blocks instead of `<code-snippet>` tags

### Added
- **TROUBLESHOOT.md**: New troubleshooting guide with common issues and solutions organized by version
  - Version-specific known issues with resolution steps
  - Breaking change indicators (🔴 BREAKING)
  - Common issues across all versions

### Changed
- Updated README.md to reference new troubleshooting documentation

## [1.0.10] - 2026-01-23

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

[1.0.12]: https://github.com/wishborn/fancy-flux/compare/v1.0.11...v1.0.12
[1.0.11]: https://github.com/wishborn/fancy-flux/compare/v1.0.10...v1.0.11
[1.0.10]: https://github.com/wishborn/fancy-flux/compare/v1.0.9...v1.0.10
[1.0.4]: https://github.com/wishborn/fancy-flux/compare/v1.0.3...v1.0.4
[1.0.3]: https://github.com/wishborn/fancy-flux/compare/v1.0.2...v1.0.3
[1.0.2]: https://github.com/wishborn/fancy-flux/compare/v1.0.1...v1.0.2
[1.0.1]: https://github.com/wishborn/fancy-flux/compare/v1.0.0...v1.0.1
[1.0.0]: https://github.com/wishborn/fancy-flux/releases/tag/v1.0.0
