<?php

namespace FancyFlux\Managers;

/**
 * Manager for programmatic D3 visualization control.
 *
 * Provides a fluent API for controlling D3 visualizations from Livewire components
 * via the FANCY facade. Dispatches Alpine events to the visualization.
 *
 * Why: Centralizes D3 control logic and provides a clean facade interface
 * that's more ergonomic than directly dispatching events.
 *
 * @example FANCY::d3('network-graph')->update($newData)
 * @example FANCY::d3('org-chart')->zoomTo('node-5')
 */
class D3Manager
{
    /**
     * Get a D3 controller instance for the given visualization name.
     *
     * @param string $name The visualization's unique name/id
     * @return D3Controller
     */
    public function get(string $name): D3Controller
    {
        return new D3Controller($name);
    }
}

/**
 * Controller for a specific D3 visualization instance.
 *
 * Provides methods to update and control a D3 visualization by dispatching
 * Alpine.js events that the visualization component listens for.
 */
class D3Controller
{
    public function __construct(
        protected string $name
    ) {}

    /**
     * Get the visualization name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Update the visualization data.
     *
     * @param array $data New data for the visualization
     * @return $this
     */
    public function update(array $data): self
    {
        $this->dispatchToLivewire("d3-update", ['id' => $this->name, 'data' => $data]);

        return $this;
    }

    /**
     * Refresh the visualization (re-render with current data).
     *
     * @return $this
     */
    public function refresh(): self
    {
        $this->dispatchToLivewire("d3-refresh", ['id' => $this->name]);

        return $this;
    }

    /**
     * Reset the visualization to its initial state.
     *
     * @return $this
     */
    public function reset(): self
    {
        $this->dispatchToLivewire("d3-reset", ['id' => $this->name]);

        return $this;
    }

    /**
     * Zoom to fit the entire visualization.
     *
     * @return $this
     */
    public function zoomToFit(): self
    {
        $this->dispatchToLivewire("d3-zoom-fit", ['id' => $this->name]);

        return $this;
    }

    /**
     * Zoom to a specific node (for hierarchical/force visualizations).
     *
     * @param string $nodeId The ID of the node to zoom to
     * @return $this
     */
    public function zoomTo(string $nodeId): self
    {
        $this->dispatchToLivewire("d3-zoom-to", ['id' => $this->name, 'nodeId' => $nodeId]);

        return $this;
    }

    /**
     * Highlight a specific node or set of nodes.
     *
     * @param string|array $nodeIds Node ID(s) to highlight
     * @return $this
     */
    public function highlight(string|array $nodeIds): self
    {
        $nodeIds = is_array($nodeIds) ? $nodeIds : [$nodeIds];
        $this->dispatchToLivewire("d3-highlight", ['id' => $this->name, 'nodeIds' => $nodeIds]);

        return $this;
    }

    /**
     * Clear all highlights.
     *
     * @return $this
     */
    public function clearHighlight(): self
    {
        $this->dispatchToLivewire("d3-clear-highlight", ['id' => $this->name]);

        return $this;
    }

    /**
     * Toggle a node's expanded/collapsed state (for hierarchical visualizations).
     *
     * @param string $nodeId The ID of the node to toggle
     * @return $this
     */
    public function toggleNode(string $nodeId): self
    {
        $this->dispatchToLivewire("d3-toggle-node", ['id' => $this->name, 'nodeId' => $nodeId]);

        return $this;
    }

    /**
     * Expand all nodes (for hierarchical visualizations).
     *
     * @return $this
     */
    public function expandAll(): self
    {
        $this->dispatchToLivewire("d3-expand-all", ['id' => $this->name]);

        return $this;
    }

    /**
     * Collapse all nodes (for hierarchical visualizations).
     *
     * @return $this
     */
    public function collapseAll(): self
    {
        $this->dispatchToLivewire("d3-collapse-all", ['id' => $this->name]);

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
