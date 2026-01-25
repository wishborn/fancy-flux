<?php

namespace FancyFlux\Concerns;

use FancyFlux\Facades\FANCY;
use FancyFlux\Managers\D3Controller;

/**
 * Trait for Livewire components that need to interact with D3 visualizations.
 *
 * Provides a convenient way to control D3 visualizations programmatically
 * from within Livewire components without directly accessing the FANCY facade.
 *
 * Why: Follows Laravel's trait pattern for component concerns, providing
 * a cleaner API that's already familiar to Laravel developers.
 *
 * @example $this->d3('network-graph')->update($data)
 * @example $this->d3('org-chart')->zoomToFit()
 */
trait InteractsWithD3
{
    /**
     * Get a D3 controller for the specified visualization.
     *
     * @param string $name The visualization's unique name
     * @return D3Controller
     */
    public function d3(string $name): D3Controller
    {
        return FANCY::d3($name);
    }

    /**
     * Update a D3 visualization with new data.
     *
     * @param string $name The visualization's unique name
     * @param array $data The new data
     * @return D3Controller
     */
    public function updateD3(string $name, array $data): D3Controller
    {
        return $this->d3($name)->update($data);
    }

    /**
     * Refresh a D3 visualization.
     *
     * @param string $name The visualization's unique name
     * @return D3Controller
     */
    public function refreshD3(string $name): D3Controller
    {
        return $this->d3($name)->refresh();
    }
}
