@blaze

{{--
    Table Search Component: Search input with deep path query support.

    Supports targeting specific columns and deep path queries into
    nested tray data (e.g., table.row[*].tray._table.row[*].name).

    Why: Enables powerful search capabilities across table data including
    nested content within trays.
--}}

@props([
    'placeholder' => 'Search...', // Placeholder text
    'columns' => [], // Columns to search (empty = all)
    'deepPaths' => false, // Enable deep path searching
    'debounce' => 300, // Debounce delay in ms
])

@php
$classes = Flux::classes()
    ->add('relative mb-4')
    ;
@endphp

<div {{ $attributes->class($classes) }}>
    <div class="relative">
        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
            <flux:icon name="magnifying-glass" class="h-5 w-5 text-zinc-400" />
        </div>
        <input
            type="search"
            class="block w-full rounded-lg border border-zinc-300 bg-white py-2 pl-10 pr-10 text-sm text-zinc-900 placeholder:text-zinc-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 dark:placeholder:text-zinc-500"
            placeholder="{{ $placeholder }}"
            x-model.debounce.{{ $debounce }}ms="searchQuery"
            x-on:input.debounce.{{ $debounce }}ms="setSearch($el.value)"
        >
        <button
            type="button"
            class="absolute inset-y-0 right-0 flex items-center pr-3"
            x-show="searchQuery.length > 0"
            x-on:click="clearSearch(); $el.previousElementSibling.focus()"
            x-cloak
        >
            <flux:icon name="x-mark" class="h-5 w-5 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300" />
        </button>
    </div>

    {{-- Search info/chips --}}
    @if (!empty($columns))
        <div class="mt-2 flex flex-wrap gap-1 text-xs text-zinc-500 dark:text-zinc-400">
            <span>Searching in:</span>
            @foreach ($columns as $col)
                <span class="rounded bg-zinc-100 px-1.5 py-0.5 dark:bg-zinc-700">{{ $col }}</span>
            @endforeach
        </div>
    @endif

    @if ($deepPaths)
        <div class="mt-2 text-xs text-zinc-400 dark:text-zinc-500">
            <span>Deep path search enabled. Use syntax: </span>
            <code class="rounded bg-zinc-100 px-1 dark:bg-zinc-800">table.row[*].tray._table.row[*].field</code>
        </div>
    @endif
</div>
