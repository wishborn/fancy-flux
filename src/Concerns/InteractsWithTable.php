<?php

namespace FancyFlux\Concerns;

use FancyFlux\FancyFlux;
use FancyFlux\Managers\TableController;
use Livewire\Component;

/**
 * Trait for interacting with table components.
 *
 * Provides helper methods for programmatic table control in Livewire components.
 * This trait delegates to the FANCY facade's TableManager for consistency.
 *
 * Why: Enables convenient $this->table('name') syntax while maintaining
 * consistency with the FANCY facade API.
 *
 * @deprecated Use FANCY::table('name') instead for new code.
 * @example $this->table('users')->selectAll()
 * @example FANCY::table('users')->selectAll() // Preferred
 */
trait InteractsWithTable
{
    /**
     * Boot the table component macro.
     * Enables $this->table('name') syntax in Livewire components.
     *
     * Why: Registers a macro on all Livewire components for table control.
     * This provides backward compatibility while the FANCY facade is preferred.
     */
    public function bootTable(): void
    {
        Component::macro('table', function (string $name): TableController {
            return app(FancyFlux::class)->table($name);
        });
    }

    /**
     * Get a table controller instance.
     *
     * Usage: $this->table('table-name')->refresh()
     *
     * @param string $name The table's unique name/id
     * @return TableController
     */
    public function table(string $name): TableController
    {
        return app(FancyFlux::class)->table($name);
    }
}
