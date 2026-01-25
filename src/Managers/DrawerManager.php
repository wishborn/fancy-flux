<?php

namespace FancyFlux\Managers;

/**
 * Manager for programmatic drawer control.
 *
 * Provides a fluent API for controlling drawers from Livewire components
 * via the FANCY facade. Dispatches Alpine events to the drawer.
 *
 * Why: Centralizes drawer control logic and provides a clean facade interface
 * that's more ergonomic than directly dispatching events.
 *
 * @example FANCY::drawer('my-drawer')->open()
 * @example FANCY::drawer('settings')->goTo('advanced')
 * @example FANCY::drawer('settings')->close()
 */
class DrawerManager
{
    /**
     * Get a drawer controller instance for the given drawer name.
     *
     * @param string $name The drawer's unique name/id
     * @return DrawerController
     */
    public function get(string $name): DrawerController
    {
        return new DrawerController($name);
    }
}

/**
 * Controller for a specific drawer instance.
 *
 * Provides methods to open, close, and navigate drawers by dispatching
 * Alpine.js events that the drawer component listens for.
 */
class DrawerController
{
    public function __construct(
        protected string $name
    ) {}

    /**
     * Get the drawer name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Open the drawer.
     *
     * @return $this
     */
    public function open(): self
    {
        $this->dispatchToLivewire("drawer-open", ['id' => $this->name]);

        return $this;
    }

    /**
     * Close the drawer.
     *
     * @return $this
     */
    public function close(): self
    {
        $this->dispatchToLivewire("drawer-close", ['id' => $this->name]);

        return $this;
    }

    /**
     * Navigate to a specific panel by name.
     *
     * @param string $panelName The name of the panel to navigate to
     * @return $this
     */
    public function goTo(string $panelName): self
    {
        $this->dispatchToLivewire("drawer-goto", ['id' => $this->name, 'name' => $panelName]);

        return $this;
    }

    /**
     * Open the drawer and navigate to a specific panel.
     *
     * @param string $panelName The name of the panel to navigate to
     * @return $this
     */
    public function openTo(string $panelName): self
    {
        $component = $this->getCurrentLivewireComponent();
        if ($component) {
            $component->js("
                \$dispatch('drawer-open', { id: '{$this->name}' });
                \$nextTick(() => {
                    \$dispatch('drawer-goto', { id: '{$this->name}', name: '{$panelName}' });
                });
            ");
        }

        return $this;
    }

    /**
     * Toggle the drawer open/closed state.
     *
     * @return $this
     */
    public function toggle(): self
    {
        $this->dispatchToLivewire("drawer-toggle", ['id' => $this->name]);

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
