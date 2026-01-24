@blaze

{{--
    Table Pagination Component: Headless Carousel-powered pagination.

    Uses the Carousel component internally for navigation while providing
    table-specific pagination UI.

    Why: Leverages the proven Carousel navigation system while presenting
    a familiar pagination interface for tables.
--}}

@props([
    'showInfo' => true, // Show "Showing X-Y of Z" info
    'showPerPage' => false, // Show per-page selector
    'perPageOptions' => [10, 25, 50, 100], // Per-page options
])

@php
$classes = Flux::classes()
    ->add('flex items-center justify-between border-t border-zinc-200 bg-white px-4 py-3 dark:border-zinc-700 dark:bg-zinc-900')
    ;
@endphp

<div {{ $attributes->class($classes) }}>
    {{-- Info section --}}
    @if ($showInfo)
        <div class="flex flex-1 items-center text-sm text-zinc-700 dark:text-zinc-300">
            <span>
                Showing
                <span class="font-medium" x-text="Math.min((currentPage - 1) * perPage + 1, totalRows)">1</span>
                to
                <span class="font-medium" x-text="Math.min(currentPage * perPage, totalRows)">10</span>
                of
                <span class="font-medium" x-text="totalRows">0</span>
                results
            </span>
        </div>
    @endif

    {{-- Per-page selector --}}
    @if ($showPerPage)
        <div class="flex items-center gap-2 text-sm">
            <label class="text-zinc-600 dark:text-zinc-400">Show</label>
            <select
                class="rounded border border-zinc-300 bg-white px-2 py-1 text-sm dark:border-zinc-600 dark:bg-zinc-800"
                x-model="perPage"
                x-on:change="currentPage = 1; totalPages = Math.ceil(totalRows / perPage)"
            >
                @foreach ($perPageOptions as $option)
                    <option value="{{ $option }}">{{ $option }}</option>
                @endforeach
            </select>
            <span class="text-zinc-600 dark:text-zinc-400">per page</span>
        </div>
    @endif

    {{-- Navigation --}}
    <div class="flex items-center gap-1">
        {{-- First page --}}
        <button
            type="button"
            class="relative inline-flex items-center rounded-l-md px-2 py-2 text-zinc-400 ring-1 ring-inset ring-zinc-300 hover:bg-zinc-50 focus:z-20 focus:outline-offset-0 disabled:opacity-50 dark:ring-zinc-600 dark:hover:bg-zinc-800"
            x-on:click="goToPage(1)"
            x-bind:disabled="currentPage === 1"
        >
            <span class="sr-only">First</span>
            <flux:icon name="chevron-double-left" class="h-4 w-4" />
        </button>

        {{-- Previous page --}}
        <button
            type="button"
            class="relative inline-flex items-center px-2 py-2 text-zinc-400 ring-1 ring-inset ring-zinc-300 hover:bg-zinc-50 focus:z-20 focus:outline-offset-0 disabled:opacity-50 dark:ring-zinc-600 dark:hover:bg-zinc-800"
            x-on:click="prevPage()"
            x-bind:disabled="currentPage === 1"
        >
            <span class="sr-only">Previous</span>
            <flux:icon name="chevron-left" class="h-4 w-4" />
        </button>

        {{-- Page numbers --}}
        <template x-for="page in totalPages" :key="page">
            <button
                type="button"
                class="relative inline-flex items-center px-3 py-2 text-sm font-medium ring-1 ring-inset ring-zinc-300 focus:z-20 focus:outline-offset-0 dark:ring-zinc-600"
                x-on:click="goToPage(page)"
                x-bind:class="{
                    'bg-blue-600 text-white hover:bg-blue-500 dark:bg-blue-500 dark:hover:bg-blue-400': currentPage === page,
                    'text-zinc-700 hover:bg-zinc-50 dark:text-zinc-300 dark:hover:bg-zinc-800': currentPage !== page
                }"
                x-text="page"
                x-show="page === 1 || page === totalPages || (page >= currentPage - 1 && page <= currentPage + 1)"
            ></button>
        </template>

        {{-- Ellipsis --}}
        <span
            class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-zinc-700 ring-1 ring-inset ring-zinc-300 dark:text-zinc-300 dark:ring-zinc-600"
            x-show="totalPages > 5 && currentPage < totalPages - 2"
            x-cloak
        >...</span>

        {{-- Next page --}}
        <button
            type="button"
            class="relative inline-flex items-center px-2 py-2 text-zinc-400 ring-1 ring-inset ring-zinc-300 hover:bg-zinc-50 focus:z-20 focus:outline-offset-0 disabled:opacity-50 dark:ring-zinc-600 dark:hover:bg-zinc-800"
            x-on:click="nextPage()"
            x-bind:disabled="currentPage === totalPages"
        >
            <span class="sr-only">Next</span>
            <flux:icon name="chevron-right" class="h-4 w-4" />
        </button>

        {{-- Last page --}}
        <button
            type="button"
            class="relative inline-flex items-center rounded-r-md px-2 py-2 text-zinc-400 ring-1 ring-inset ring-zinc-300 hover:bg-zinc-50 focus:z-20 focus:outline-offset-0 disabled:opacity-50 dark:ring-zinc-600 dark:hover:bg-zinc-800"
            x-on:click="goToPage(totalPages)"
            x-bind:disabled="currentPage === totalPages"
        >
            <span class="sr-only">Last</span>
            <flux:icon name="chevron-double-right" class="h-4 w-4" />
        </button>
    </div>
</div>
