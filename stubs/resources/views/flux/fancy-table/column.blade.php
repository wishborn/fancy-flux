@blaze

{{--
    Table Column Component: Header cell with Action button props support.

    Integrates with the Action component for consistent styling while
    providing table-specific features like sorting and filtering.

    Why: Column headers need the visual richness of Action buttons
    (icons, states) while also supporting table-specific behaviors.
--}}

@props([
    'name' => null, // Column identifier (required for sorting/filtering)
    'label' => null, // Display label
    'sortable' => false, // Enable sorting
    'filterable' => false, // Enable filtering
    'resizable' => false, // Enable column resize
    'reorderable' => false, // Enable drag reorder
    'icon' => null, // Icon from Heroicons (left of label)
    'iconTrailing' => false, // Icon on right side
    'emoji' => null, // Emoji slug
    'emojiTrailing' => null, // Trailing emoji slug
    'active' => false, // Active/selected state
    'warn' => false, // Warning state
    'alert' => false, // Alert state
    'align' => 'left', // Text alignment: left, center, right
    'width' => null, // Fixed width
    'minWidth' => null, // Minimum width
    'maxWidth' => null, // Maximum width
])

@php
use FancyFlux\Facades\FANCY;

$emojiChar = $emoji ? FANCY::emoji($emoji) : null;
$emojiTrailingChar = $emojiTrailing ? FANCY::emoji($emojiTrailing) : null;

$alignClass = match($align) {
    'center' => 'text-center',
    'right' => 'text-right',
    default => 'text-left',
};

$classes = Flux::classes()
    ->add('px-3 py-3 text-sm font-medium text-zinc-700 dark:text-zinc-300')
    ->add($alignClass)
    ->add($active ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300' : '')
    ->add($warn ? 'bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-300' : '')
    ->add($alert ? 'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300' : '')
    ->add($resizable ? 'relative' : '')
    ;

$styleAttr = collect([
    $width ? "width: {$width}" : null,
    $minWidth ? "min-width: {$minWidth}" : null,
    $maxWidth ? "max-width: {$maxWidth}" : null,
])->filter()->implode('; ');
@endphp

<th
    scope="col"
    {{ $attributes->class($classes) }}
    @if ($styleAttr) style="{{ $styleAttr }}" @endif
    @if ($name) data-column="{{ $name }}" @endif
    @if ($reorderable) draggable="true" @endif
>
    @if ($sortable)
        <button
            type="button"
            class="group inline-flex items-center gap-1 hover:text-zinc-900 dark:hover:text-zinc-100"
            x-on:click="sort('{{ $name }}')"
        >
            @if ($icon && !$iconTrailing)
                <flux:icon :name="$icon" class="h-4 w-4" />
            @endif

            @if ($emojiChar)
                <span class="text-base">{{ $emojiChar }}</span>
            @endif

            <span>{{ $label ?? $slot }}</span>

            @if ($emojiTrailingChar)
                <span class="text-base">{{ $emojiTrailingChar }}</span>
            @endif

            @if ($icon && $iconTrailing)
                <flux:icon :name="$icon" class="h-4 w-4" />
            @endif

            {{-- Sort indicator --}}
            <span
                class="ml-1 flex-none rounded text-zinc-400 group-hover:visible group-focus:visible"
                x-show="sortColumn === '{{ $name }}'"
            >
                <template x-if="sortDirection === 'asc'">
                    <flux:icon name="chevron-up" class="h-4 w-4" />
                </template>
                <template x-if="sortDirection === 'desc'">
                    <flux:icon name="chevron-down" class="h-4 w-4" />
                </template>
            </span>
            <span
                class="ml-1 flex-none rounded text-zinc-300 dark:text-zinc-600 invisible group-hover:visible"
                x-show="sortColumn !== '{{ $name }}'"
            >
                <flux:icon name="chevron-up-down" class="h-4 w-4" />
            </span>
        </button>
    @else
        <span class="inline-flex items-center gap-1">
            @if ($icon && !$iconTrailing)
                <flux:icon :name="$icon" class="h-4 w-4" />
            @endif

            @if ($emojiChar)
                <span class="text-base">{{ $emojiChar }}</span>
            @endif

            <span>{{ $label ?? $slot }}</span>

            @if ($emojiTrailingChar)
                <span class="text-base">{{ $emojiTrailingChar }}</span>
            @endif

            @if ($icon && $iconTrailing)
                <flux:icon :name="$icon" class="h-4 w-4" />
            @endif
        </span>
    @endif

    @if ($resizable)
        {{-- Resize handle --}}
        <div
            class="absolute right-0 top-0 bottom-0 w-1 cursor-col-resize bg-transparent hover:bg-blue-400 dark:hover:bg-blue-500"
            x-on:mousedown.prevent="$dispatch('column-resize-start', { column: '{{ $name }}' })"
        ></div>
    @endif
</th>
