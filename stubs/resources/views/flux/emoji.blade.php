{{--
    Emoji Component: Display emoji characters using slugs, emoticons, or raw characters.

    Why: Provides a consistent, simple way to display emojis in templates similar to
    how flux:icon works for Heroicons. Supports developer-friendly slugs, classic
    emoticons like :) and :(, and passthrough for raw emoji characters.

    Features:
    - Slug-based lookup: <flux:emoji name="fire" /> -> 🔥
    - Emoticon conversion: <flux:emoji name=":)" /> -> 😊
    - Raw passthrough: <flux:emoji name="🔥" /> -> 🔥
    - Size variants: sm, md (default), lg, xl, 2xl
    - Accessible with aria-label from emoji name
    - Inline display (like flux:icon)

    Usage:
    <flux:emoji name="fire" />
    <flux:emoji name=":)" />
    <flux:emoji name="rocket" size="lg" />
    <flux:emoji name="thumbs-up" class="mr-2" />

    Dynamic usage with Livewire:
    <flux:emoji :name="$selectedEmoji" />
--}}

@props([
    'name' => null,
    'size' => null,
    'label' => null, // Accessible label override
])

@php
    use FancyFlux\FancyFlux;

    $size = $size ?? 'md';

    // Resolve the emoji using the repository (handles slugs, emoticons, and passthrough)
    $fancyFlux = app(FancyFlux::class);
    $emojiRepo = $fancyFlux->emoji();
    
    // Try to resolve: first as emoticon/slug, then check if it's already an emoji character
    $emojiChar = $name ? $emojiRepo->resolve($name) : null;
    
    // If resolve didn't find it but name is provided, it might be a raw emoji - use as-is
    if ($emojiChar === null && $name !== null && $name !== '') {
        $emojiChar = $name;
    }

    // Get accessible label - use provided label, or try to find emoji name from slug
    $ariaLabel = $label;
    if (!$ariaLabel && $name) {
        $emojiData = $emojiRepo->find($name);
        $ariaLabel = $emojiData['name'] ?? str_replace('-', ' ', $name);
    }

    // Size classes - font size for emoji display
    $sizeClasses = match ($size) {
        'sm' => 'text-sm',           // ~14px
        'lg' => 'text-xl',           // ~20px
        'xl' => 'text-2xl',          // ~24px
        '2xl' => 'text-3xl',         // ~30px
        '3xl' => 'text-4xl',         // ~36px
        default => 'text-base',      // md ~16px
    };

    $classes = Flux::classes()
        ->add('inline-flex items-center justify-center')
        ->add($sizeClasses)
        ->add('leading-none') // Prevent extra line height
        ;
@endphp

@if($emojiChar)
<span
    {{ $attributes->class($classes) }}
    role="img"
    @if($ariaLabel) aria-label="{{ $ariaLabel }}" @endif
    data-flux-emoji
    data-emoji-name="{{ $name }}"
>{{ $emojiChar }}</span>
@endif
