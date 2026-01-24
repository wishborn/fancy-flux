# Action Component

A reusable button component following Flux UI patterns for consistent chat/drawer actions. Supports standalone colors, behavioral states (active, checked, warn, alert), shape variants, avatars, badges, icons with flexible placement, emojis, and disabled states.

## Why This Component?

The Action component provides a consistent, state-aware button for action-oriented UI elements like:
- Chat controls and message actions
- Drawer toolbars
- Card actions
- Toolbar buttons
- Quick action menus
- Toggle/checkbox buttons
- User profile buttons

## Installation

The Action component is included with Fancy Flux. No additional installation required.

```blade
<flux:action>Click me</flux:action>
```

## Basic Usage

### Default State

```blade
<flux:action>Default Action</flux:action>
```

### With Icons

```blade
<!-- Icon on left (default) -->
<flux:action icon="pencil">Edit</flux:action>

<!-- Icon on right -->
<flux:action icon="arrow-right" icon-trailing>Next</flux:action>
```

## Standalone Colors

The `color` prop provides standalone color theming **independent of behavioral states**. When set, it always takes precedence over state-based colors.

```blade
<!-- Available colors -->
<flux:action color="blue">Blue</flux:action>
<flux:action color="emerald">Emerald</flux:action>
<flux:action color="amber">Amber</flux:action>
<flux:action color="red">Red</flux:action>
<flux:action color="violet">Violet</flux:action>
<flux:action color="indigo">Indigo</flux:action>
<flux:action color="sky">Sky</flux:action>
<flux:action color="rose">Rose</flux:action>
<flux:action color="orange">Orange</flux:action>
<flux:action color="zinc">Zinc</flux:action>

<!-- Color + behavioral state (color wins for styling, state adds behavior) -->
<flux:action color="red" alert>Red + Pulsing</flux:action>
<flux:action color="violet" checked>Violet + Checked behavior</flux:action>
```

## Behavioral States

States control behavior and styling (when no `color` prop is set):

### Active State

Blue styling for selected/active items:

```blade
<flux:action active>Selected</flux:action>
<flux:action active icon="check">Active Item</flux:action>
```

### Checked State

Emerald styling for toggle/checkbox states:

```blade
<flux:action checked>Selected</flux:action>
<flux:action :checked="$isEnabled" wire:click="toggle">Toggle</flux:action>
```

### Warning State

Light amber styling for cautionary actions:

```blade
<flux:action warn>Caution</flux:action>
<flux:action warn icon="exclamation-triangle">Proceed with Care</flux:action>
```

### Alert State

Pulsing effect for urgent attention (no color change - use with `color` prop):

```blade
<flux:action alert>Critical</flux:action>
<flux:action alert alert-icon="bell">New Notifications</flux:action>
<flux:action color="red" alert>Red + Pulsing</flux:action>
```

## Shape Variants

### Default (Rounded Rectangle)

```blade
<flux:action icon="pencil">Edit</flux:action>
```

### Circle (Perfect Circle)

Ideal for icon-only buttons:

```blade
<flux:action variant="circle" icon="play" />
<flux:action variant="circle" icon="pause" size="lg" />
<flux:action variant="circle" emoji="fire" color="red" />
<flux:action variant="circle" icon="plus" color="emerald" />
```

## Avatar Support

Display circular avatars in action buttons:

```blade
<!-- Leading avatar (default) -->
<flux:action avatar="/img/user.jpg">John Doe</flux:action>

<!-- Trailing avatar -->
<flux:action avatar="/img/user.jpg" avatar-trailing>Profile</flux:action>

<!-- Avatar with other elements -->
<flux:action avatar="/img/user.jpg" badge="Admin">John</flux:action>
```

## Badge Support

Display badges (notification counts, labels, etc.):

```blade
<!-- Leading badge (default) -->
<flux:action badge="3" icon="bell">Notifications</flux:action>

<!-- Trailing badge -->
<flux:action badge="NEW" badge-trailing>Updates</flux:action>

<!-- Badge with colors -->
<flux:action badge="99+" color="red">Alerts</flux:action>
<flux:action badge="PRO" color="violet">Upgrade</flux:action>
```

## Element Sort Order

Control the display order of emoji, icon, avatar, and badge using the `sort` prop:

- `e` = emoji
- `i` = icon
- `a` = avatar
- `b` = badge

```blade
<!-- Default order: emoji, icon, avatar, badge -->
<flux:action emoji="fire" icon="star" badge="HOT">Featured</flux:action>

<!-- Custom order: badge first, then icon, then emoji -->
<flux:action emoji="fire" icon="star" badge="HOT" sort="bie">Featured</flux:action>

<!-- Avatar first -->
<flux:action avatar="/img/user.jpg" badge="Admin" sort="ab">John</flux:action>
```

Invalid characters in the sort string are silently ignored, and missing elements are added in default order.

## Icon Placement

The component supports flexible icon positioning:

| Position | Description |
|----------|-------------|
| `left` | Icon before text (default) |
| `right` | Icon after text |
| `top` / `over` | Icon above text (vertical layout) |
| `bottom` / `under` | Icon below text (vertical layout) |

```blade
<!-- Horizontal layouts -->
<flux:action icon="home" icon-place="left">Home</flux:action>
<flux:action icon="arrow-right" icon-place="right">Next</flux:action>

<!-- Vertical layouts -->
<flux:action icon="cog" icon-place="top">Settings</flux:action>
<flux:action icon="info" icon-place="bottom">Info</flux:action>

<!-- Alternative: use icon-trailing for right placement -->
<flux:action icon="chevron-right" icon-trailing>Continue</flux:action>
```

## Size Variants

Three size options are available:

```blade
<flux:action size="sm">Small</flux:action>
<flux:action size="md">Medium (default)</flux:action>
<flux:action size="lg">Large</flux:action>
```

| Size | Padding | Text | Icon Size |
|------|---------|------|-----------|
| `sm` | px-2 py-1 | text-xs | 12px (w-3 h-3) |
| `md` | px-3 py-1.5 | text-sm | 16px (w-4 h-4) |
| `lg` | px-4 py-2.5 | text-base | 20px (w-5 h-5) |

## Alert Icons

For attention-grabbing states, use a pulsing alert icon:

```blade
<!-- Alert icon on leading side (default) -->
<flux:action alert alert-icon="bell">Notifications</flux:action>

<!-- Alert icon on trailing side -->
<flux:action alert alert-icon="exclamation-circle" alert-icon-trailing>
    3 New Messages
</flux:action>
```

## Custom Icon Colors

Override the default state-based icon colors:

```blade
<flux:action icon="star" icon-color="text-yellow-500">Favorite</flux:action>
<flux:action icon="heart" icon-color="text-pink-500 dark:text-pink-400">Like</flux:action>
```

## Emoji Support

Add emojis to your action buttons using slugs from the FANCY facade. The emoji repository includes 787+ emojis organized by category.

### Leading Emoji

```blade
<flux:action emoji="fire">Hot!</flux:action>
<flux:action emoji="rocket" active>Launch</flux:action>
<flux:action emoji="red-heart" warn>Love</flux:action>
```

### Trailing Emoji

```blade
<flux:action emoji-trailing="thumbs-up">Like</flux:action>
<flux:action emoji-trailing="sparkles">Celebrate</flux:action>
```

### Combined Emojis

```blade
<flux:action emoji="party-popper" emoji-trailing="confetti-ball">
    Party Time
</flux:action>
```

### Finding Emoji Slugs

Use the FANCY facade to explore available emojis:

```php
// List all emoji slugs
FANCY::emoji()->list();

// Get emoji character by slug
FANCY::emoji('fire'); // Returns: 🔥

// Search emojis
FANCY::emoji()->search('heart'); // Returns matching emojis

// Find emoji data
FANCY::emoji()->find('rocket');
// Returns: ['char' => '🚀', 'name' => 'rocket', 'slug' => 'rocket', 'category' => 'travel']

// List categories
FANCY::emoji()->categories();
// Returns: ['smileys', 'people', 'animals', 'food', 'activities', 'travel', 'symbols', 'flags']
```

## Disabled State

```blade
<flux:action disabled>Unavailable</flux:action>
<flux:action disabled icon="lock">Locked</flux:action>
```

## Livewire Integration

The component works seamlessly with Livewire:

```blade
<flux:action wire:click="save" icon="check">Save</flux:action>
<flux:action wire:click="delete" warn icon="trash">Delete</flux:action>

<!-- With loading state -->
<flux:action wire:click="process" wire:loading.attr="disabled">
    <span wire:loading.remove>Process</span>
    <span wire:loading>Processing...</span>
</flux:action>
```

## Dark Mode

The component includes full dark mode support out of the box. All colors and states automatically adjust for dark mode:

| Color/State | Light Mode | Dark Mode |
|-------------|------------|-----------|
| Default | White bg, zinc text | Zinc-800 bg, zinc-300 text |
| `color="blue"` / `active` | Blue-500 bg, white text | Blue-600 bg, white text |
| `color="emerald"` / `checked` | Emerald-500 bg, white text | Emerald-600 bg, white text |
| `warn` | Amber-50 bg, amber-700 text | Amber-900/30 bg, amber-300 text |
| `color="red"` | Red-500 bg, white text | Red-600 bg, white text |
| `color="violet"` | Violet-500 bg, white text | Violet-600 bg, white text |

## Props Reference

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `variant` | string | `'default'` | Shape variant: 'default' (rounded rectangle) or 'circle' |
| `color` | string | `null` | Standalone color (overrides state colors): blue, emerald, amber, red, violet, indigo, sky, rose, orange, zinc |
| `active` | boolean | `false` | Active/selected state (blue if no color set) |
| `checked` | boolean | `false` | Toggle/checkbox state (emerald if no color set) |
| `warn` | boolean | `false` | Warning state (light amber if no color set) |
| `alert` | boolean | `false` | Pulse animation effect (no color change) |
| `icon` | string | `null` | Heroicon name for main icon |
| `icon-color` | string | `null` | Custom Tailwind color class for icon |
| `icon-place` | string | `'left'` | Icon position: left, right, top, bottom, over, under |
| `icon-trailing` | boolean | `false` | Place icon on trailing side (shorthand for right) |
| `alert-icon` | string | `null` | Heroicon name for pulsing alert icon |
| `alert-icon-trailing` | boolean | `false` | Place alert icon on trailing side |
| `emoji` | string | `null` | Emoji slug for leading emoji (e.g., 'fire', 'rocket') |
| `emoji-trailing` | string | `null` | Emoji slug for trailing emoji |
| `avatar` | string | `null` | Image URL for circular avatar |
| `avatar-trailing` | boolean | `false` | Place avatar on trailing side |
| `badge` | string | `null` | Badge text to display |
| `badge-trailing` | boolean | `false` | Place badge on trailing side |
| `sort` | string | `'eiab'` | Element order: e=emoji, i=icon, a=avatar, b=badge |
| `disabled` | boolean | `false` | Disable the button |
| `size` | string | `'md'` | Button size: sm, md, lg |

## Examples

### Chat Action Bar

```blade
<div class="flex gap-2">
    <flux:action icon="paper-airplane" active>Send</flux:action>
    <flux:action icon="photo">Attach</flux:action>
    <flux:action icon="face-smile">Emoji</flux:action>
    <flux:action icon="microphone">Voice</flux:action>
</div>
```

### Toolbar with States

```blade
<div class="flex gap-1">
    <flux:action size="sm" icon="bold" active>B</flux:action>
    <flux:action size="sm" icon="italic">I</flux:action>
    <flux:action size="sm" icon="underline">U</flux:action>
</div>
```

### Card Actions

```blade
<div class="flex justify-end gap-2 mt-4">
    <flux:action icon="x-mark">Cancel</flux:action>
    <flux:action active icon="check">Confirm</flux:action>
</div>
```

### Alert Notification

```blade
<flux:action 
    alert 
    alert-icon="bell" 
    icon="envelope" 
    size="lg"
>
    5 Unread Messages
</flux:action>
```
