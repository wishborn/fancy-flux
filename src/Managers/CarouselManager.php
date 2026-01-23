<?php

namespace FancyFlux\Managers;

/**
 * Manager for programmatic carousel control.
 *
 * Provides a fluent API for controlling carousels from Livewire components
 * via the FANCY facade. Dispatches Alpine events to the carousel.
 *
 * Why: Centralizes carousel control logic and provides a clean facade interface
 * that's more ergonomic than directly dispatching events.
 *
 * @example FANCY::carousel('my-carousel')->next()
 * @example FANCY::carousel('wizard')->goTo('step-3')
 */
class CarouselManager
{
    /**
     * Get a carousel controller instance for the given carousel name.
     *
     * @param string $name The carousel's unique name/id
     * @return CarouselController
     */
    public function get(string $name): CarouselController
    {
        return new CarouselController($name);
    }
}

/**
 * Controller for a specific carousel instance.
 *
 * Provides methods to navigate and control a carousel by dispatching
 * Alpine.js events that the carousel component listens for.
 */
class CarouselController
{
    public function __construct(
        protected string $name
    ) {}

    /**
     * Get the carousel name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Navigate to the next slide.
     *
     * @return $this
     */
    public function next(): self
    {
        $this->dispatchToLivewire("carousel-next", ['id' => $this->name]);

        return $this;
    }

    /**
     * Navigate to the previous slide.
     *
     * @return $this
     */
    public function prev(): self
    {
        $this->dispatchToLivewire("carousel-prev", ['id' => $this->name]);

        return $this;
    }

    /**
     * Navigate to a specific slide by name.
     *
     * @param string $stepName The name of the step/slide to navigate to
     * @return $this
     */
    public function goTo(string $stepName): self
    {
        $this->dispatchToLivewire("carousel-goto", ['id' => $this->name, 'name' => $stepName]);

        return $this;
    }

    /**
     * Navigate to a specific slide by index.
     *
     * @param int $index Zero-based slide index
     * @return $this
     */
    public function goToIndex(int $index): self
    {
        $this->dispatchToLivewire("carousel-goto", ['id' => $this->name, 'index' => $index]);

        return $this;
    }

    /**
     * Refresh the carousel (re-collect steps from DOM).
     * Call this after dynamically adding/removing slides.
     *
     * @return $this
     */
    public function refresh(): self
    {
        $this->dispatchToLivewire("carousel-refresh", ['id' => $this->name]);

        return $this;
    }

    /**
     * Refresh and then navigate to a specific slide.
     * Useful when adding new slides and immediately navigating to them.
     *
     * @param string $stepName The name of the step to navigate to after refresh
     * @return $this
     */
    public function refreshAndGoTo(string $stepName): self
    {
        $component = $this->getCurrentLivewireComponent();
        if ($component) {
            $component->js("
                \$nextTick(() => {
                    \$dispatch('carousel-refresh', { id: '{$this->name}' });
                    setTimeout(() => {
                        \$dispatch('carousel-goto', { id: '{$this->name}', name: '{$stepName}' });
                    }, 50);
                });
            ");
        }

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
