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

### Component Prefix Configuration

To avoid naming conflicts with official Flux components or other custom components, you can configure a custom prefix for Fancy Flux components.

**Publish the config file:**
```bash
php artisan vendor:publish --tag=fancy-flux-config
```

**Set in your `.env` file:**
```env
FANCY_FLUX_PREFIX=fancy
FANCY_FLUX_USE_FLUX_NAMESPACE=true
```

**Usage Examples:**

- **No prefix (default):** Components available as `<flux:carousel>`
- **With prefix 'fancy':** Components available as `<fancy:carousel>` (and optionally `<flux:carousel>` if `FANCY_FLUX_USE_FLUX_NAMESPACE=true`)

**Configuration Options:**

- `FANCY_FLUX_PREFIX` - Custom prefix for components (e.g., `fancy`, `custom`, `myapp`)
- `FANCY_FLUX_USE_FLUX_NAMESPACE` - When `true`, components are also available in the `flux` namespace for backward compatibility. Set to `false` to use ONLY the prefixed namespace.

**Why use a prefix?**

- **Avoid conflicts:** If Flux releases an official `carousel` component, your prefixed version won't conflict
- **Multiple packages:** If you use multiple custom Flux component packages, prefixes prevent conflicts
- **Clear ownership:** Makes it clear which components are from Fancy Flux vs official Flux

## Documentation

- **[Usage Guide](USAGE.md)** - Comprehensive documentation for all components
- **[Component Docs](docs/)** - Detailed guides for each component:
  - [Carousel](docs/carousel.md)
  - [Color Picker](docs/color-picker.md)
  - [Emoji Select](docs/emoji-select.md)
- **[Prefix Configuration](docs/prefix-configuration.md)** - Configure custom component prefixes to avoid naming conflicts

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

## Upgrade Guide

### General Upgrade Steps

1. **Update via Composer:**
   ```bash
   # Update to latest version
   composer update wishborn/fancy-flux
   
   # Or update to a specific version
   composer require wishborn/fancy-flux:^1.0.8
   ```

2. **Clear caches:**
   ```bash
   php artisan config:clear
   php artisan view:clear
   ```

3. **Review changelog:** Check [CHANGELOG.md](CHANGELOG.md) for version-specific changes

4. **Test your application:** Verify all components work as expected

### Upgrading to 1.0.8+

**New Feature: Component Prefix Configuration**

Version 1.0.8 introduces optional component prefix configuration to avoid naming conflicts. This is **backward compatible** - existing code will continue to work without changes.

**Optional: Configure a Prefix**

If you want to use a custom prefix (recommended for new projects):

1. **Publish the config file:**
   ```bash
   php artisan vendor:publish --tag=fancy-flux-config
   ```

2. **Set prefix in `.env` (optional):**
   ```env
   FANCY_FLUX_PREFIX=fancy
   FANCY_FLUX_USE_FLUX_NAMESPACE=true
   ```

3. **Clear config cache:**
   ```bash
   php artisan config:clear
   ```

**No Action Required**

If you don't configure a prefix, components continue to work exactly as before:
- `<flux:carousel>` - Still works
- `<flux:color-picker>` - Still works
- `<flux:emoji-select>` - Still works

**Migration Path (Optional)**

If you want to migrate to a prefixed namespace:

1. Set `FANCY_FLUX_PREFIX` and keep `FANCY_FLUX_USE_FLUX_NAMESPACE=true`
2. Gradually update templates to use the prefixed version
3. Once all templates are updated, optionally set `FANCY_FLUX_USE_FLUX_NAMESPACE=false`

See [Prefix Configuration](docs/prefix-configuration.md) for detailed migration steps.

## Requirements

- PHP 8.2+
- Laravel 10+ / 11+ / 12+
- Livewire 3.7+ / 4.0+
- Flux UI 2.0+

## License

MIT
