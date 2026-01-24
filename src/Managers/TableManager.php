<?php

namespace FancyFlux\Managers;

/**
 * Manager for programmatic table control.
 *
 * Provides a fluent API for controlling tables from Livewire components
 * via the FANCY facade. Dispatches Alpine events to the table component.
 *
 * Why: Centralizes table control logic and provides a clean facade interface
 * that's more ergonomic than directly dispatching events.
 *
 * @example FANCY::table('my-table')->refresh()
 * @example FANCY::table('users')->selectAll()
 */
class TableManager
{
    /**
     * Get a table controller instance for the given table name.
     *
     * @param string $name The table's unique name/id
     * @return TableController
     */
    public function get(string $name): TableController
    {
        return new TableController($name);
    }
}

/**
 * Controller for a specific table instance.
 *
 * Provides methods to control a table by dispatching Alpine.js events
 * that the table component listens for.
 *
 * Why: Enables programmatic table manipulation from Livewire components,
 * supporting features like pagination, selection, sorting, and tray toggling.
 */
class TableController
{
    public function __construct(
        protected string $name
    ) {}

    /**
     * Get the table name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Navigate to the next page.
     *
     * @return $this
     */
    public function nextPage(): self
    {
        $this->dispatchToLivewire('table-next-page', ['id' => $this->name]);

        return $this;
    }

    /**
     * Navigate to the previous page.
     *
     * @return $this
     */
    public function prevPage(): self
    {
        $this->dispatchToLivewire('table-prev-page', ['id' => $this->name]);

        return $this;
    }

    /**
     * Navigate to a specific page by number.
     *
     * @param int $page One-based page number
     * @return $this
     */
    public function goToPage(int $page): self
    {
        $this->dispatchToLivewire('table-goto-page', ['id' => $this->name, 'page' => $page]);

        return $this;
    }

    /**
     * Sort by a specific column.
     *
     * @param string $column Column name to sort by
     * @param string $direction Sort direction: 'asc' or 'desc'
     * @return $this
     */
    public function sortBy(string $column, string $direction = 'asc'): self
    {
        $this->dispatchToLivewire('table-sort', [
            'id' => $this->name,
            'column' => $column,
            'direction' => $direction,
        ]);

        return $this;
    }

    /**
     * Toggle sort on a column (cycles through asc, desc, none).
     *
     * @param string $column Column name to toggle sort
     * @return $this
     */
    public function toggleSort(string $column): self
    {
        $this->dispatchToLivewire('table-toggle-sort', ['id' => $this->name, 'column' => $column]);

        return $this;
    }

    /**
     * Select a row by its key.
     *
     * @param string|int $rowKey The row's unique identifier
     * @return $this
     */
    public function selectRow(string|int $rowKey): self
    {
        $this->dispatchToLivewire('table-select-row', ['id' => $this->name, 'rowKey' => $rowKey]);

        return $this;
    }

    /**
     * Deselect a row by its key.
     *
     * @param string|int $rowKey The row's unique identifier
     * @return $this
     */
    public function deselectRow(string|int $rowKey): self
    {
        $this->dispatchToLivewire('table-deselect-row', ['id' => $this->name, 'rowKey' => $rowKey]);

        return $this;
    }

    /**
     * Select all visible rows.
     *
     * @return $this
     */
    public function selectAll(): self
    {
        $this->dispatchToLivewire('table-select-all', ['id' => $this->name]);

        return $this;
    }

    /**
     * Deselect all rows.
     *
     * @return $this
     */
    public function deselectAll(): self
    {
        $this->dispatchToLivewire('table-deselect-all', ['id' => $this->name]);

        return $this;
    }

    /**
     * Toggle selection on a row.
     *
     * @param string|int $rowKey The row's unique identifier
     * @return $this
     */
    public function toggleRowSelection(string|int $rowKey): self
    {
        $this->dispatchToLivewire('table-toggle-row', ['id' => $this->name, 'rowKey' => $rowKey]);

        return $this;
    }

    /**
     * Expand a row's tray.
     *
     * @param string|int $rowKey The row's unique identifier
     * @return $this
     */
    public function expandTray(string|int $rowKey): self
    {
        $this->dispatchToLivewire('table-expand-tray', ['id' => $this->name, 'rowKey' => $rowKey]);

        return $this;
    }

    /**
     * Collapse a row's tray.
     *
     * @param string|int $rowKey The row's unique identifier
     * @return $this
     */
    public function collapseTray(string|int $rowKey): self
    {
        $this->dispatchToLivewire('table-collapse-tray', ['id' => $this->name, 'rowKey' => $rowKey]);

        return $this;
    }

    /**
     * Toggle a row's tray (expand if collapsed, collapse if expanded).
     *
     * @param string|int $rowKey The row's unique identifier
     * @return $this
     */
    public function toggleTray(string|int $rowKey): self
    {
        $this->dispatchToLivewire('table-toggle-tray', ['id' => $this->name, 'rowKey' => $rowKey]);

        return $this;
    }

    /**
     * Collapse all expanded trays.
     *
     * @return $this
     */
    public function collapseAllTrays(): self
    {
        $this->dispatchToLivewire('table-collapse-all-trays', ['id' => $this->name]);

        return $this;
    }

    /**
     * Refresh the table data (re-fetch from server or re-render).
     *
     * @return $this
     */
    public function refresh(): self
    {
        $this->dispatchToLivewire('table-refresh', ['id' => $this->name]);

        return $this;
    }

    /**
     * Set the search query.
     *
     * @param string $query The search query
     * @param array|null $columns Optional column names to search in
     * @return $this
     */
    public function search(string $query, ?array $columns = null): self
    {
        $data = ['id' => $this->name, 'query' => $query];
        if ($columns !== null) {
            $data['columns'] = $columns;
        }
        $this->dispatchToLivewire('table-search', $data);

        return $this;
    }

    /**
     * Clear the search query.
     *
     * @return $this
     */
    public function clearSearch(): self
    {
        $this->dispatchToLivewire('table-clear-search', ['id' => $this->name]);

        return $this;
    }

    /**
     * Set a filter value.
     *
     * @param string $column Column name to filter
     * @param mixed $value Filter value
     * @return $this
     */
    public function filter(string $column, mixed $value): self
    {
        $this->dispatchToLivewire('table-filter', [
            'id' => $this->name,
            'column' => $column,
            'value' => $value,
        ]);

        return $this;
    }

    /**
     * Clear a column filter.
     *
     * @param string $column Column name to clear filter for
     * @return $this
     */
    public function clearFilter(string $column): self
    {
        $this->dispatchToLivewire('table-clear-filter', ['id' => $this->name, 'column' => $column]);

        return $this;
    }

    /**
     * Clear all filters.
     *
     * @return $this
     */
    public function clearAllFilters(): self
    {
        $this->dispatchToLivewire('table-clear-all-filters', ['id' => $this->name]);

        return $this;
    }

    /**
     * Dispatch an event to the current Livewire component's JS context.
     *
     * @param string $event Event name
     * @param array $data Event data
     */
    protected function dispatchToLivewire(string $event, array $data): void
    {
        $component = $this->getCurrentLivewireComponent();
        if ($component) {
            $dataJson = json_encode($data);
            $component->js("\$dispatch('{$event}', {$dataJson})");
        }
    }

    /**
     * Get the current Livewire component instance.
     *
     * @return \Livewire\Component|null
     */
    protected function getCurrentLivewireComponent(): ?\Livewire\Component
    {
        return app('livewire')->current();
    }
}
