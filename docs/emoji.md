# Emoji Component

Display emojis using slugs, classic emoticons (like `:)` and `:()`), or raw emoji characters.

The `flux:emoji` component works just like `flux:icon` - a simple inline display component that accepts various input formats and renders the appropriate UTF-8 emoji character.

## Basic Usage

```blade
<flux:emoji name="fire" />
<flux:emoji name="rocket" />
<flux:emoji name="thumbs-up" />
```

## Input Formats

### Slug-Based (Recommended)

Use kebab-case slugs derived from emoji names:

```blade
<flux:emoji name="fire" />           {{-- 🔥 --}}
<flux:emoji name="rocket" />         {{-- 🚀 --}}
<flux:emoji name="red-heart" />      {{-- ❤️ --}}
<flux:emoji name="thumbs-up" />      {{-- 👍 --}}
<flux:emoji name="party-popper" />   {{-- 🎉 --}}
```

### Classic Emoticons

Convert text emoticons to emoji automatically:

```blade
<flux:emoji name=":)" />    {{-- 😊 --}}
<flux:emoji name=":(" />    {{-- 😢 --}}
<flux:emoji name=":D" />    {{-- 😃 --}}
<flux:emoji name=";)" />    {{-- 😉 --}}
<flux:emoji name=":P" />    {{-- 😛 --}}
<flux:emoji name="<3" />    {{-- ❤️ --}}
<flux:emoji name="</3" />   {{-- 💔 --}}
<flux:emoji name=":O" />    {{-- 😮 --}}
<flux:emoji name="B)" />    {{-- 😎 --}}
<flux:emoji name="^_^" />   {{-- 😊 --}}
```

### Slack/Discord Style

Colon-wrapped shortcodes are also supported:

```blade
<flux:emoji name=":fire:" />       {{-- 🔥 --}}
<flux:emoji name=":thumbsup:" />   {{-- 👍 --}}
<flux:emoji name=":wave:" />       {{-- 👋 --}}
<flux:emoji name=":100:" />        {{-- 💯 --}}
```

### Raw Emoji (Passthrough)

Already have the emoji character? It passes through unchanged:

```blade
<flux:emoji name="🔥" />    {{-- 🔥 --}}
<flux:emoji name="😊" />    {{-- 😊 --}}
```

## Size Variants

Control the size with the `size` prop:

```blade
<flux:emoji name="rocket" size="sm" />     {{-- text-sm (~14px) --}}
<flux:emoji name="rocket" />               {{-- text-base (~16px, default) --}}
<flux:emoji name="rocket" size="lg" />     {{-- text-xl (~20px) --}}
<flux:emoji name="rocket" size="xl" />     {{-- text-2xl (~24px) --}}
<flux:emoji name="rocket" size="2xl" />    {{-- text-3xl (~30px) --}}
<flux:emoji name="rocket" size="3xl" />    {{-- text-4xl (~36px) --}}
```

## Accessibility

The component automatically includes ARIA attributes:

```blade
<flux:emoji name="fire" />
{{-- Renders with role="img" aria-label="fire" --}}

<flux:emoji name="thumbs-up" />
{{-- Renders with aria-label="thumbs up" --}}
```

Override the label for custom accessible text:

```blade
<flux:emoji name="fire" label="This is hot!" />
```

## Dynamic Usage

Works seamlessly with Livewire and dynamic values:

```blade
{{-- From a Livewire property --}}
<flux:emoji :name="$selectedEmoji" />

{{-- In a loop --}}
@foreach($reactions as $reaction)
    <flux:emoji :name="$reaction" size="lg" />
@endforeach
```

## Custom Styling

Pass additional classes:

```blade
<flux:emoji name="fire" class="mr-2" />
<flux:emoji name="star" class="animate-bounce" />
```

## Compared to flux:emoji-select

| Component | Purpose |
|-----------|---------|
| `flux:emoji` | **Display** - Shows a single emoji inline (like `flux:icon`) |
| `flux:emoji-select` | **Input** - Picker with categories, search, wire:model binding |

Use `flux:emoji` to display the result of an emoji-select:

```blade
<flux:emoji-select wire:model.live="selectedEmoji" />

@if($selectedEmoji)
    <p>You selected: <flux:emoji :name="$selectedEmoji" size="lg" /></p>
@endif
```

## FANCY Facade

The component uses the `FANCY` facade internally. You can also use it directly:

```php
use FancyFlux\Facades\FANCY;

// Get emoji character from slug
FANCY::emoji('fire');           // '🔥'
FANCY::emoji(':)');             // '😊'

// Access repository methods
FANCY::emoji()->list();         // All available slugs
FANCY::emoji()->search('heart'); // Search by name
FANCY::emoji()->emoticons();    // All supported emoticons
FANCY::emoji()->resolve($input); // Smart resolution (slug, emoticon, or passthrough)
```

## Supported Emoticons

The full list of supported emoticons:

| Emoticon | Emoji | Emoticon | Emoji |
|----------|-------|----------|-------|
| `:)` | 😊 | `:(` | 😢 |
| `:D` | 😃 | `;)` | 😉 |
| `:P` | 😛 | `:*` | 😘 |
| `<3` | ❤️ | `</3` | 💔 |
| `:O` | 😮 | `:/` | 😕 |
| `:|` | 😐 | `B)` | 😎 |
| `>:)` | 😈 | `>:(` | 😠 |
| `^_^` | 😊 | `-_-` | 😑 |
| `T_T` | 😭 | `XD` | 😆 |
| `o/` | 👋 | `\o/` | 🙌 |
| `:+1:` | 👍 | `:-1:` | 👎 |
| `:fire:` | 🔥 | `:100:` | 💯 |

And many more! Use `FANCY::emoji()->emoticons()` to get the complete list.

## Props Reference

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `name` | string | null | Emoji slug, emoticon, or raw character |
| `size` | string | 'md' | Size: sm, md (default), lg, xl, 2xl, 3xl |
| `label` | string | null | Custom aria-label override |
