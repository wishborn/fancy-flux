## Fancy Flux

Custom Flux UI components for Laravel Livewire applications. Provides enhanced carousel, color picker, and emoji select components that extend the base Flux UI library.

### Features

- **FANCY Facade**: Unified API for programmatic access to emoji lookup, carousel control, and configuration
- **Action Component**: Reusable button with standalone colors, behavioral states (active, checked, warn, alert), shape variants (default, circle), avatars, badges, flexible icon/emoji placement, and dark mode
- **Carousel Component**: Flexible carousel/slideshow with multiple variants (directional, wizard, thumbnail)
- **Color Picker Component**: Native color input with enhanced UI, swatch preview, and preset support
- **Emoji Select Component**: Composable emoji picker with category navigation, search, and customizable styling

### Installation

```bash
composer require wishborn/fancy-flux
```

**Component Prefix Configuration:**

To avoid naming conflicts with official Flux components, you can configure a custom prefix:

```bash
php artisan vendor:publish --tag=fancy-flux-config
```

Set in `.env`:
```env
FANCY_FLUX_PREFIX=fancy
FANCY_FLUX_USE_FLUX_NAMESPACE=true
```

- **No prefix (default):** Components available as `<flux:carousel>`
- **With prefix:** Components available as `<fancy:carousel>` (and optionally `<flux:carousel>`)

### FANCY Facade

The `FANCY` facade provides unified access to FancyFlux features:

```php
// Emoji lookup
FANCY::emoji('fire');           // Returns: 🔥
FANCY::emoji()->list();         // Get all emoji slugs
FANCY::emoji()->find('rocket'); // Get emoji data
FANCY::emoji()->search('heart'); // Search emojis

// Carousel control
FANCY::carousel('wizard')->next();
FANCY::carousel('wizard')->goTo('step-3');
FANCY::carousel('dynamic')->refreshAndGoTo('new-slide');

// Configuration
FANCY::prefix();            // Custom prefix or null
FANCY::usesFluxNamespace(); // true/false
FANCY::components();        // List of components
```

### Action Component

A reusable button component with standalone colors, behavioral states, icons, emojis, avatars, badges, and flexible placement.

```blade
<!-- Default state -->
<flux:action>Default Action</flux:action>

<!-- Standalone colors (independent of states) -->
<flux:action color="blue">Blue</flux:action>
<flux:action color="emerald">Emerald</flux:action>
<flux:action color="red">Red</flux:action>
<flux:action color="violet">Violet</flux:action>

<!-- Behavioral states (use default colors when no color prop) -->
<flux:action active>Active (blue)</flux:action>
<flux:action checked>Checked (emerald)</flux:action>
<flux:action warn icon="exclamation-triangle">Warning (amber)</flux:action>
<flux:action alert alert-icon="bell">Alert (pulse)</flux:action>

<!-- Color + state (color wins, state adds behavior) -->
<flux:action color="red" alert>Red + Pulsing</flux:action>
```

**Shape Variants:**

```blade
<!-- Default (rounded rectangle) -->
<flux:action icon="pencil">Edit</flux:action>

<!-- Circle (perfect circle for icon-only) -->
<flux:action variant="circle" icon="play" />
<flux:action variant="circle" icon="pause" size="lg" />
<flux:action variant="circle" emoji="fire" color="red" />
```

**Avatar, Badge & Sort:**

```blade
<!-- Avatar support -->
<flux:action avatar="/img/user.jpg">John Doe</flux:action>
<flux:action avatar="/img/user.jpg" avatar-trailing>Profile</flux:action>

<!-- Badge support -->
<flux:action badge="3" icon="bell">Notifications</flux:action>
<flux:action badge="NEW" color="emerald">Featured</flux:action>

<!-- Sort order (e=emoji, i=icon, a=avatar, b=badge) -->
<flux:action icon="star" emoji="fire" badge="HOT" sort="bie">Custom Order</flux:action>
```

**Icon Placement Options:**

```blade
<!-- Icon on left (default) -->
<flux:action icon="pencil">Edit</flux:action>

<!-- Icon on right -->
<flux:action icon="arrow-right" icon-trailing>Next</flux:action>

<!-- Icon above text -->
<flux:action icon="cog" icon-place="top">Settings</flux:action>

<!-- Icon below text -->
<flux:action icon="info" icon-place="bottom">Info</flux:action>
```

**Emoji Support:**

```blade
<!-- Leading emoji -->
<flux:action emoji="fire">Hot!</flux:action>
<flux:action emoji="rocket" color="blue">Launch</flux:action>

<!-- Trailing emoji -->
<flux:action emoji-trailing="thumbs-up">Like</flux:action>

<!-- Combined emojis -->
<flux:action emoji="party-popper" emoji-trailing="sparkles">Celebrate</flux:action>
```

**Size Variants:**

```blade
<flux:action size="sm">Small</flux:action>
<flux:action size="md">Medium</flux:action>
<flux:action size="lg">Large</flux:action>
```

**Props Reference:**
| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `variant` | string | 'default' | Shape: 'default' (rounded rectangle) or 'circle' |
| `color` | string | null | Standalone color: blue, emerald, amber, red, violet, indigo, sky, rose, orange, zinc |
| `active` | bool | false | Active/selected state (blue if no color) |
| `checked` | bool | false | Toggle/checkbox state (emerald if no color) |
| `warn` | bool | false | Warning state (light amber if no color) |
| `alert` | bool | false | Pulse animation effect (no color change) |
| `icon` | string | null | Heroicon name for main icon |
| `icon-color` | string | null | Custom icon color class |
| `icon-place` | string | 'left' | Icon position: left, right, top, bottom, over, under |
| `icon-trailing` | bool | false | Place icon on trailing side |
| `alert-icon` | string | null | Pulsing icon for alert state |
| `alert-icon-trailing` | bool | false | Pulsing icon on trailing side |
| `emoji` | string | null | Emoji slug for leading emoji |
| `emoji-trailing` | string | null | Emoji slug for trailing emoji |
| `avatar` | string | null | Image URL for circular avatar |
| `avatar-trailing` | bool | false | Place avatar on trailing side |
| `badge` | string | null | Badge text to display |
| `badge-trailing` | bool | false | Place badge on trailing side |
| `sort` | string | 'eiab' | Element order: e=emoji, i=icon, a=avatar, b=badge |
| `disabled` | bool | false | Disabled state |
| `size` | string | 'md' | Size: sm, md, lg |

### Carousel Component

The carousel component supports data-driven and slot-based usage patterns with multiple variants.

**Data-Driven (Simplest):**

```blade
@php
$slides = [
    ['name' => 'slide1', 'label' => 'First Slide', 'src' => '/images/slide1.jpg'],
    ['name' => 'slide2', 'label' => 'Second Slide', 'src' => '/images/slide2.jpg'],
];
@endphp

<flux:carousel :data="$slides" autoplay />
```

**Wizard Variant (Multi-Step Forms):**

```blade
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
```

**Programmatic Navigation (FANCY Facade - Recommended):**

```php
class MyComponent extends Component
{
    public function goToStep(string $stepName): void
    {
        // Use FANCY facade (preferred)
        FANCY::carousel('my-carousel')->goTo($stepName);
    }
    
    public function advanceWizard(): void
    {
        FANCY::carousel('wizard')->next();
    }
}
```

**Legacy InteractsWithCarousel Trait:**

```php
use FancyFlux\Concerns\InteractsWithCarousel;

class MyComponent extends Component
{
    use InteractsWithCarousel;
    
    public function goToStep(string $stepName): void
    {
        // Trait delegates to FANCY facade internally
        $this->carousel('my-carousel')->goTo($stepName);
    }
}
```

**Nested Carousels:**

```blade
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
```

### Color Picker Component

Native color input with enhanced UI and preset support.

```blade
<flux:color-picker label="Primary Color" wire:model="primaryColor" />

<!-- With custom presets -->
<flux:color-picker 
    label="Brand Colors" 
    wire:model="brandColor"
    :presets="['3b82f6', '8b5cf6', 'ec4899']"
/>
```

### Emoji Component

Display emojis using slugs, classic emoticons, or raw characters - like `flux:icon` but for emoji.

```blade
<!-- From slugs -->
<flux:emoji name="fire" />           {{-- 🔥 --}}
<flux:emoji name="rocket" size="lg" />

<!-- From classic emoticons -->
<flux:emoji name=":)" />             {{-- 😊 --}}
<flux:emoji name=":D" />             {{-- 😃 --}}
<flux:emoji name="<3" />             {{-- ❤️ --}}

<!-- Dynamic usage -->
<flux:emoji :name="$selectedEmoji" size="xl" />
```

### Emoji Select Component

Composable emoji picker with category navigation and search.

```blade
<flux:emoji-select wire:model.live="selectedEmoji" />

<!-- Display the selected emoji -->
@if($selectedEmoji)
    <flux:emoji :name="$selectedEmoji" size="lg" />
@endif

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
```

### Key Conventions

- **FANCY Facade**: Use `FANCY::` for emoji lookup (supports slugs AND emoticons like `:)`), carousel control, and configuration access
- **Component Namespace**: Components use the `flux:` namespace by default. If `FANCY_FLUX_PREFIX` is configured, components are also available with that prefix.
- **Livewire Integration**: Components work seamlessly with wire:model and wire:submit
- **Unique Names**: When using multiple carousels, always provide unique name props
- **Nested Carousels**: Use parentCarousel prop to link nested carousels to their parent
- **Programmatic Control**: Use `FANCY::carousel('name')` (preferred) or InteractsWithCarousel trait
- **Emoji Slugs**: Use kebab-case slugs like 'fire', 'thumbs-up', 'red-heart' for emojis
- **Prefix Configuration**: Use a custom prefix to avoid conflicts with official Flux components

### Documentation

- Full documentation: See docs/ folder in package
- Demos: See demos/ folder for ready-to-use examples
- Usage guide: See USAGE.md in package root
