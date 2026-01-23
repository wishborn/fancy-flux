# FANCY Facade

The `FANCY` facade provides a unified API for accessing FancyFlux features programmatically. It follows the pattern used by Flux for modal control and provides a consistent interface for all integrations.

## Overview

The facade is automatically registered when Fancy Flux is installed. You can access it globally using:

```php
FANCY::emoji('fire'); // Returns: 🔥
FANCY::carousel('wizard')->next();
```

## Emoji Repository

The emoji repository provides slug-based access to 787+ emojis organized by category.

### Quick Access

```php
// Get emoji character by slug
FANCY::emoji('fire');        // 🔥
FANCY::emoji('rocket');      // 🚀
FANCY::emoji('thumbs-up');   // 👍
FANCY::emoji('red-heart');   // ❤️
```

### Repository Methods

Access the full repository for advanced operations:

```php
$repo = FANCY::emoji();

// List all slugs
$slugs = $repo->list(); // ['grinning-face', 'fire', 'rocket', ...]

// Find emoji data
$emoji = $repo->find('fire');
// Returns: ['char' => '🔥', 'name' => 'fire', 'slug' => 'fire', 'category' => 'symbols']

// Check if slug exists
$repo->has('fire'); // true

// Search by name or slug
$results = $repo->search('heart', 10);

// Get emojis by category
$smileys = $repo->category('smileys');

// List all categories
$repo->categories();
// ['smileys', 'people', 'animals', 'food', 'activities', 'travel', 'symbols', 'flags']
```

### Slug Format

Emoji slugs use kebab-case derived from their names:

| Emoji | Name | Slug |
|-------|------|------|
| 😀 | grinning face | `grinning-face` |
| 🔥 | fire | `fire` |
| 🚀 | rocket | `rocket` |
| ❤️ | red heart | `red-heart` |
| 👍 | thumbs up | `thumbs-up` |
| ✨ | sparkles | `sparkles` |

### Categories

Emojis are organized into 8 categories:

- `smileys` - Faces and expressions
- `people` - Hands, gestures, and people
- `animals` - Animals and nature
- `food` - Food and drink
- `activities` - Sports and hobbies
- `travel` - Places and transportation
- `symbols` - Hearts, shapes, and symbols
- `flags` - Country and other flags

## Carousel Control

Programmatically control carousels from Livewire components.

### Basic Navigation

```php
// In a Livewire component
FANCY::carousel('my-carousel')->next();
FANCY::carousel('my-carousel')->prev();
FANCY::carousel('wizard')->goTo('step-3');
FANCY::carousel('wizard')->goToIndex(2);
```

### Dynamic Content

```php
// Refresh after adding/removing slides
FANCY::carousel('dynamic-carousel')->refresh();

// Refresh and navigate
FANCY::carousel('dynamic-carousel')->refreshAndGoTo('new-slide');
```

### Method Chaining

```php
FANCY::carousel('wizard')
    ->refresh()
    ->goTo('confirmation');
```

## Configuration Access

```php
// Get configured prefix (null by default)
FANCY::prefix();

// Check if flux namespace is enabled
FANCY::usesFluxNamespace(); // true

// List available components
FANCY::components();
// ['action', 'carousel', 'color-picker', 'emoji-select']
```

## Using in Blade Components

The FANCY facade is available in Blade views:

```blade
@php
    $emoji = FANCY::emoji('fire');
@endphp

<span>{{ $emoji }}</span>
```

Or in Blade components:

```blade
{{-- In a component --}}
@props(['emojiSlug' => null])

@if($emojiSlug)
    <span>{{ FANCY::emoji($emojiSlug) }}</span>
@endif
```

## Service Resolution

The facade resolves to the `FancyFlux\FancyFlux` service class:

```php
// These are equivalent
FANCY::emoji('fire');
app(FancyFlux\FancyFlux::class)->emoji('fire');
```

## Backward Compatibility

### InteractsWithCarousel Trait

The `InteractsWithCarousel` trait still works and now delegates to the FANCY facade:

```php
use FancyFlux\Concerns\InteractsWithCarousel;

class MyComponent extends Component
{
    use InteractsWithCarousel;
    
    public function goToNext()
    {
        // Both work:
        $this->carousel('wizard')->next();     // Trait method
        FANCY::carousel('wizard')->next();     // Facade (preferred)
    }
}
```

### Prefix Configuration

Components support custom prefixes for avoiding conflicts:

```php
// In config/fancy-flux.php or .env
'prefix' => 'fancy',
'use_flux_namespace' => true,

// Components available as:
// <fancy:action>    (custom prefix)
// <flux:action>     (if use_flux_namespace is true)
```
