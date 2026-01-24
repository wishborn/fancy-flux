@blaze

{{--
    Table Component: A flexible, composable data table with advanced features.

    Supports two usage patterns:

    1. Data-driven (simple): Pass columns and rows arrays
       <flux:table :columns="$columns" :rows="$rows" />

    2. Slot-based (flexible): Use sub-components for full control
       <flux:table name="my-table">
           <flux:table.columns>...</flux:table.columns>
           <flux:table.body>...</flux:table.body>
           <flux:table.pagination />
       </flux:table>

    Features:
    - Column headers with Action component props (icon, active, sortable)
    - Expandable row trays supporting nested content (_table, _carousel, _d3, _view)
    - Multi-select with wire:model binding
    - Virtualization with prefetch for large datasets
    - Headless Carousel-powered pagination
    - Deep path search queries
    - Column resize and reorder

    Why: Provides a powerful, composable table following FancyFlux patterns,
    enabling complex data displays with nested visualizations.
--}}

@props([
    'name' => null, // Table instance name for programmatic control
    'columns' => null, // Array of column definitions for data-driven mode
    'rows' => null, // Array of row data for data-driven mode
    'paginate' => null, // Items per page (null = no pagination)
    'multiSelect' => false, // Enable multi-row selection
    'virtual' => false, // Enable virtualization for large datasets
    'virtualRowHeight' => 48, // Fixed row height for virtual scrolling
    'prefetch' => 20, // Rows to prefetch above/below viewport
    'virtualBuffer' => 5, // Extra rows rendered outside viewport
    'sortBy' => null, // Default sort column
    'sortDirection' => 'asc', // Default sort direction: 'asc' | 'desc'
    'searchable' => false, // Enable search component
    'searchColumns' => [], // Columns to include in search (empty = all)
    'searchDeepPaths' => false, // Enable deep path searching in trays
    'striped' => false, // Alternating row colors
    'hoverable' => true, // Highlight row on hover
    'bordered' => false, // Show cell borders
    'compact' => false, // Reduce cell padding
])

@php
use Illuminate\Support\Str;

// Generate unique table ID
$tableId = $name ?? 'table-' . Str::random(8);

// Determine if data-driven mode
$isDataDriven = $columns !== null && $rows !== null;

// Normalize columns to array format
$normalizedColumns = $columns ? collect($columns)->map(function ($col, $index) {
    $col = (array) $col;
    $col['name'] = $col['name'] ?? $col['key'] ?? 'col-' . $index;
    $col['label'] = $col['label'] ?? Str::title(str_replace(['_', '-'], ' ', $col['name']));
    return $col;
})->toArray() : null;

// Normalize rows with keys
$normalizedRows = $rows ? collect($rows)->map(function ($row, $index) {
    $row = (array) $row;
    $row['_key'] = $row['id'] ?? $row['_key'] ?? $index;
    return $row;
})->toArray() : null;

// Calculate pagination
$totalRows = $normalizedRows ? count($normalizedRows) : 0;
$itemsPerPage = $paginate ? (int) $paginate : $totalRows;
$totalPages = $itemsPerPage > 0 ? (int) ceil($totalRows / $itemsPerPage) : 1;

$classes = Flux::classes()
    ->add('relative w-full overflow-hidden')
    ->add('bg-white dark:bg-zinc-900')
    ->add($bordered ? 'border border-zinc-200 dark:border-zinc-700 rounded-lg' : '')
    ;
@endphp

<div
    {{ $attributes->merge(['wire:key' => 'table-' . $tableId])->class($classes) }}
    x-data="{
        {{-- Table name/id for event targeting --}}
        name: '{{ $tableId }}',

        {{-- Current page (1-based) --}}
        currentPage: 1,

        {{-- Items per page --}}
        perPage: {{ $itemsPerPage }},

        {{-- Total rows --}}
        totalRows: {{ $totalRows }},

        {{-- Total pages --}}
        totalPages: {{ $totalPages }},

        {{-- Sort state --}}
        sortColumn: {{ $sortBy ? "'{$sortBy}'" : 'null' }},
        sortDirection: '{{ $sortDirection }}',

        {{-- Multi-select state --}}
        multiSelect: {{ $multiSelect ? 'true' : 'false' }},
        selectedRows: [],

        {{-- Expanded tray keys --}}
        expandedTrays: [],

        {{-- Search state --}}
        searchQuery: '',
        searchColumns: {{ json_encode($searchColumns ?: []) }},

        {{-- Virtualization state --}}
        virtual: {{ $virtual ? 'true' : 'false' }},
        virtualRowHeight: {{ $virtualRowHeight }},
        prefetch: {{ $prefetch }},
        virtualBuffer: {{ $virtualBuffer }},
        scrollTop: 0,
        containerHeight: 0,

        {{-- Initialize --}}
        init() {
            this.$nextTick(() => {
                if (this.virtual) {
                    this.setupVirtualization();
                }
            });
        },

        {{-- Pagination methods --}}
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
                this.$dispatch('table-page-changed', { id: this.name, page: this.currentPage });
            }
        },

        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.$dispatch('table-page-changed', { id: this.name, page: this.currentPage });
            }
        },

        goToPage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
                this.$dispatch('table-page-changed', { id: this.name, page: this.currentPage });
            }
        },

        {{-- Sort methods --}}
        sort(column) {
            if (this.sortColumn === column) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortColumn = column;
                this.sortDirection = 'asc';
            }
            this.$dispatch('table-sorted', { id: this.name, column: this.sortColumn, direction: this.sortDirection });
        },

        {{-- Selection methods --}}
        isSelected(key) {
            return this.selectedRows.includes(key);
        },

        toggleRowSelection(key) {
            if (this.isSelected(key)) {
                this.selectedRows = this.selectedRows.filter(k => k !== key);
            } else {
                this.selectedRows.push(key);
            }
            this.$dispatch('table-selection-changed', { id: this.name, selected: this.selectedRows });
        },

        selectAll(keys) {
            this.selectedRows = [...new Set([...this.selectedRows, ...keys])];
            this.$dispatch('table-selection-changed', { id: this.name, selected: this.selectedRows });
        },

        deselectAll() {
            this.selectedRows = [];
            this.$dispatch('table-selection-changed', { id: this.name, selected: this.selectedRows });
        },

        {{-- Tray methods --}}
        isTrayExpanded(key) {
            return this.expandedTrays.includes(key);
        },

        toggleTray(key) {
            if (this.isTrayExpanded(key)) {
                this.expandedTrays = this.expandedTrays.filter(k => k !== key);
            } else {
                this.expandedTrays.push(key);
            }
            this.$dispatch('table-tray-toggled', { id: this.name, key: key, expanded: this.isTrayExpanded(key) });
        },

        expandTray(key) {
            if (!this.isTrayExpanded(key)) {
                this.expandedTrays.push(key);
            }
        },

        collapseTray(key) {
            this.expandedTrays = this.expandedTrays.filter(k => k !== key);
        },

        collapseAllTrays() {
            this.expandedTrays = [];
        },

        {{-- Search methods --}}
        setSearch(query) {
            this.searchQuery = query;
            this.currentPage = 1;
            this.$dispatch('table-searched', { id: this.name, query: query });
        },

        clearSearch() {
            this.searchQuery = '';
            this.$dispatch('table-searched', { id: this.name, query: '' });
        },

        {{-- Virtualization methods --}}
        setupVirtualization() {
            const container = this.$el.querySelector('[data-table-body]');
            if (container) {
                this.containerHeight = container.clientHeight;
                container.addEventListener('scroll', () => {
                    this.scrollTop = container.scrollTop;
                });
            }
        },

        get visibleStart() {
            if (!this.virtual) return 0;
            return Math.max(0, Math.floor(this.scrollTop / this.virtualRowHeight) - this.prefetch);
        },

        get visibleEnd() {
            if (!this.virtual) return this.totalRows;
            const visible = Math.ceil(this.containerHeight / this.virtualRowHeight);
            return Math.min(this.totalRows, Math.ceil(this.scrollTop / this.virtualRowHeight) + visible + this.prefetch);
        },

        get virtualPaddingTop() {
            return this.visibleStart * this.virtualRowHeight;
        },

        get virtualPaddingBottom() {
            return (this.totalRows - this.visibleEnd) * this.virtualRowHeight;
        },
    }"
    {{-- Event listeners for programmatic control --}}
    x-on:table-next-page.window="if ($event.detail?.id === name) nextPage()"
    x-on:table-prev-page.window="if ($event.detail?.id === name) prevPage()"
    x-on:table-goto-page.window="if ($event.detail?.id === name) goToPage($event.detail?.page)"
    x-on:table-sort.window="if ($event.detail?.id === name) { sortColumn = $event.detail?.column; sortDirection = $event.detail?.direction || 'asc'; }"
    x-on:table-toggle-sort.window="if ($event.detail?.id === name) sort($event.detail?.column)"
    x-on:table-select-row.window="if ($event.detail?.id === name && !isSelected($event.detail?.rowKey)) toggleRowSelection($event.detail?.rowKey)"
    x-on:table-deselect-row.window="if ($event.detail?.id === name && isSelected($event.detail?.rowKey)) toggleRowSelection($event.detail?.rowKey)"
    x-on:table-select-all.window="if ($event.detail?.id === name) selectAll([{{ $normalizedRows ? implode(',', array_map(fn($r) => "'" . addslashes($r['_key']) . "'", $normalizedRows)) : '' }}])"
    x-on:table-deselect-all.window="if ($event.detail?.id === name) deselectAll()"
    x-on:table-toggle-row.window="if ($event.detail?.id === name) toggleRowSelection($event.detail?.rowKey)"
    x-on:table-expand-tray.window="if ($event.detail?.id === name) expandTray($event.detail?.rowKey)"
    x-on:table-collapse-tray.window="if ($event.detail?.id === name) collapseTray($event.detail?.rowKey)"
    x-on:table-toggle-tray.window="if ($event.detail?.id === name) toggleTray($event.detail?.rowKey)"
    x-on:table-collapse-all-trays.window="if ($event.detail?.id === name) collapseAllTrays()"
    x-on:table-search.window="if ($event.detail?.id === name) setSearch($event.detail?.query)"
    x-on:table-clear-search.window="if ($event.detail?.id === name) clearSearch()"
    x-on:table-refresh.window="if ($event.detail?.id === name) $wire.$refresh()"
    data-flux-fancy-table
    data-flux-fancy-table-name="{{ $tableId }}"
    id="{{ $tableId }}"
    role="table"
>
    @if ($isDataDriven)
        {{-- Data-driven mode: auto-generate table structure --}}

        {{-- Search --}}
        @if ($searchable)
            <flux:fancy-table.search />
        @endif

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                {{-- Header --}}
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        @if ($multiSelect)
                            <th scope="col" class="relative w-12 px-3 py-3">
                                <input
                                    type="checkbox"
                                    class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-zinc-300 text-blue-600 focus:ring-blue-600 dark:border-zinc-600 dark:bg-zinc-700"
                                    x-on:change="$event.target.checked ? selectAll([{{ implode(',', array_map(fn($r) => "'" . addslashes($r['_key']) . "'", $normalizedRows)) }}]) : deselectAll()"
                                    x-bind:checked="selectedRows.length === {{ count($normalizedRows) }}"
                                    x-bind:indeterminate="selectedRows.length > 0 && selectedRows.length < {{ count($normalizedRows) }}"
                                >
                            </th>
                        @endif
                        @foreach ($normalizedColumns as $column)
                            <flux:fancy-table.column
                                :name="$column['name']"
                                :label="$column['label']"
                                :sortable="$column['sortable'] ?? false"
                                :icon="$column['icon'] ?? null"
                                :active="$column['active'] ?? false"
                                :warn="$column['warn'] ?? false"
                                :alert="$column['alert'] ?? false"
                                :resizable="$column['resizable'] ?? false"
                                :reorderable="$column['reorderable'] ?? false"
                            />
                        @endforeach
                    </tr>
                </thead>

                {{-- Body --}}
                <tbody
                    class="divide-y divide-zinc-200 dark:divide-zinc-700 bg-white dark:bg-zinc-900"
                    data-table-body
                >
                    @foreach ($normalizedRows as $row)
                        @php
                            $rowKey = $row['_key'];
                            $hasTray = isset($row['tray']);
                        @endphp
                        <tr
                            wire:key="table-{{ $tableId }}-row-{{ $rowKey }}"
                            class="{{ $striped ? 'even:bg-zinc-50 dark:even:bg-zinc-800/50' : '' }} {{ $hoverable ? 'hover:bg-zinc-50 dark:hover:bg-zinc-800' : '' }}"
                            x-bind:class="{ 'bg-blue-50 dark:bg-blue-900/20': isSelected('{{ $rowKey }}') }"
                        >
                            @if ($multiSelect)
                                <td class="relative w-12 px-3 py-4">
                                    <input
                                        type="checkbox"
                                        class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-zinc-300 text-blue-600 focus:ring-blue-600 dark:border-zinc-600 dark:bg-zinc-700"
                                        x-on:change="toggleRowSelection('{{ $rowKey }}')"
                                        x-bind:checked="isSelected('{{ $rowKey }}')"
                                    >
                                </td>
                            @endif
                            @foreach ($normalizedColumns as $column)
                                <td class="whitespace-nowrap px-3 {{ $compact ? 'py-2' : 'py-4' }} text-sm text-zinc-900 dark:text-zinc-100">
                                    @php $cellValue = $row[$column['name']] ?? ''; @endphp
                                    @if (is_array($cellValue) && isset($cellValue['_d3']))
                                        {{-- D3 visualization placeholder - will be implemented in s12 --}}
                                        <span class="text-xs text-zinc-400">[D3 chart]</span>
                                    @else
                                        {{ $cellValue }}
                                    @endif
                                </td>
                            @endforeach
                            @if ($hasTray)
                                <td class="relative w-10 px-2 py-4">
                                    <flux:fancy-table.tray.trigger :for="$rowKey" />
                                </td>
                            @endif
                        </tr>
                        @if ($hasTray)
                            <flux:fancy-table.tray :for="$rowKey" :data="$row['tray']" />
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($paginate && $totalPages > 1)
            <flux:fancy-table.pagination />
        @endif
    @else
        {{-- Slot-based mode --}}
        {{ $slot }}
    @endif
</div>
