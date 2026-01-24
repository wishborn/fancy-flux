@blaze

{{--
    Color Picker Component: A native color input with swatch preview and preset support.

    Features:
    - Native HTML color input with browser color picker
    - Swatch preview showing selected color
    - Hex value display (uppercase)
    - Support for size variants (sm, default, lg)
    - Support for style variants (outline, filled)
    - Custom preset colors via :presets prop (lowercase 6-char hex format)
    - Livewire integration via wire:model
    - Can be used with or without a label

    Usage:
    <flux:color-picker wire:model="color" />
    <flux:color-picker wire:model="color" :presets="['3b82f6', 'ef4444', '10b981']" />
--}}

@props([
    'name' => $attributes->whereStartsWith('wire:model')->first(),
    'size' => null, // 'sm', default, 'lg'
    'variant' => 'outline', // 'outline', 'filled'
    'presets' => null, // Array of hex colors (lowercase, no #)
    'label' => null,
])

@php
// Default presets if none provided
$defaultPresets = ['000000', 'ffffff', 'ef4444', 'f59e0b', '10b981', '3b82f6', '8b5cf6', 'ec4899'];
$presetColors = $presets ?? $defaultPresets;

// Get the current color value from wire:model or default
$colorValue = $attributes->whereStartsWith('wire:model')->first() 
    ? ($attributes->get('value') ?? '#3B82F6') 
    : ($attributes->get('value') ?? '#3B82F6');

// Normalize to lowercase for datalist (browser expects lowercase)
$colorValueLower = strtolower($colorValue);

$containerClasses = Flux::classes()
    ->add('flex items-center gap-3')
    ;

$inputWrapperClasses = Flux::classes()
    ->add('relative flex items-center gap-2')
    ->add(match ($size) {
        'sm' => 'h-8',
        'lg' => 'h-12',
        default => 'h-10',
    })
    ;

// The native color input is positioned absolutely over the swatch for better UX
// This hides the default browser styling while keeping accessibility
$inputClasses = Flux::classes()
    ->add('absolute inset-0 opacity-0 cursor-pointer')
    ->add('focus:outline-none')
    ;

$swatchClasses = Flux::classes()
    ->add('flex items-center justify-center rounded border')
    ->add(match ($variant) {
        'filled' => 'bg-zinc-100 dark:bg-zinc-800 border-zinc-200 dark:border-zinc-700',
        default => 'bg-white dark:bg-zinc-900 border-zinc-200 dark:border-zinc-700',
    })
    ->add(match ($size) {
        'sm' => 'w-8 h-8',
        'lg' => 'w-12 h-12',
        default => 'w-10 h-10',
    })
    ;

$hexDisplayClasses = Flux::classes()
    ->add('font-mono font-medium')
    ->add(match ($size) {
        'sm' => 'text-xs',
        'lg' => 'text-base',
        default => 'text-sm',
    })
    ->add('text-zinc-700 dark:text-zinc-300')
    ;

$datalistId = 'color-presets-' . ($name ?? uniqid());
@endphp

<div
    {{ $attributes->except(['wire:model', 'value', 'size', 'variant', 'presets', 'label'])->class($containerClasses) }}
    data-flux-color-picker
    @if($attributes->whereStartsWith('wire:model')->first())
        x-data="{ color: $wire.entangle('{{ $attributes->whereStartsWith('wire:model')->first() }}').live }"
    @else
        x-data="{ color: @js($colorValueLower) }"
    @endif
    x-on:input="color = $event.target.value.toLowerCase()"
>
    @if ($label)
        <flux:label :for="$name" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
            {{ $label }}
        </flux:label>
    @endif

    <div class="{{ $inputWrapperClasses }}">
        {{-- Swatch with hidden color input overlay --}}
        <div class="{{ $swatchClasses }} relative overflow-hidden">
            <div 
                class="w-full h-full rounded"
                :style="{ backgroundColor: color }"
            ></div>
            {{-- Native color input is invisible but clickable over the swatch --}}
            <input
                type="color"
                {{ $attributes->whereStartsWith('wire:model')->merge(['value' => $colorValueLower]) }}
                class="{{ $inputClasses }}"
                :value="color"
                list="{{ $datalistId }}"
                x-model="color"
            />
        </div>

        <span class="{{ $hexDisplayClasses }}" x-text="color ? color.toUpperCase().replace('#', '#') : '#3B82F6'"></span>
    </div>

    {{-- Datalist for preset colors (browser shows these in color picker UI) --}}
    <datalist id="{{ $datalistId }}">
        @foreach ($presetColors as $preset)
            <option value="#{{ $preset }}"></option>
        @endforeach
    </datalist>
</div>
