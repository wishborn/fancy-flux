<?php

namespace FancyFlux\Concerns;

use Livewire\Component;

/**
 * Trait for interacting with carousel components.
 * 
 * Provides helper methods for programmatic carousel control in Livewire components.
 */
trait InteractsWithCarousel
{
    /**
     * Boot the carousel component macro.
     * Enables $this->carousel('name') syntax in Livewire components.
     */
    public function bootCarousel()
    {
        Component::macro('carousel', function ($name) {
            return new class ($name) {
                public function __construct(public $name) {}

                /**
                 * Navigate to the next slide.
                 */
                public function next()
                {
                    $component = app('livewire')->current();
                    $component->js("\$dispatch('carousel-next', { id: '{$this->name}' })");
                }

                /**
                 * Navigate to the previous slide.
                 */
                public function prev()
                {
                    $component = app('livewire')->current();
                    $component->js("\$dispatch('carousel-prev', { id: '{$this->name}' })");
                }

                /**
                 * Navigate to a specific slide by name.
                 */
                public function goTo(string $stepName)
                {
                    $component = app('livewire')->current();
                    $component->js("\$dispatch('carousel-goto', { id: '{$this->name}', name: '{$stepName}' })");
                }

                /**
                 * Navigate to a specific slide by index.
                 */
                public function goToIndex(int $index)
                {
                    $component = app('livewire')->current();
                    $component->js("\$dispatch('carousel-goto', { id: '{$this->name}', index: {$index} })");
                }

                /**
                 * Refresh the carousel (re-collect steps from DOM).
                 * Call this after dynamically adding/removing slides.
                 */
                public function refresh()
                {
                    $component = app('livewire')->current();
                    $component->js("\$dispatch('carousel-refresh', { id: '{$this->name}' })");
                }

                /**
                 * Refresh and then navigate to a specific slide.
                 * Useful when adding new slides and immediately navigating to them.
                 */
                public function refreshAndGoTo(string $stepName)
                {
                    $component = app('livewire')->current();
                    $component->js("
                        \$nextTick(() => {
                            \$dispatch('carousel-refresh', { id: '{$this->name}' });
                            setTimeout(() => {
                                \$dispatch('carousel-goto', { id: '{$this->name}', name: '{$stepName}' });
                            }, 50);
                        });
                    ");
                }
            };
        });
    }

    /**
     * Get a carousel helper instance.
     * Usage: $this->carousel('carousel-name')->next()
     */
    public function carousel($name)
    {
        return new class ($name) {
            public function __construct(public $name) {}

            public function next()
            {
                app('livewire')->current()->js("\$dispatch('carousel-next', { id: '{$this->name}' })");
            }

            public function prev()
            {
                app('livewire')->current()->js("\$dispatch('carousel-prev', { id: '{$this->name}' })");
            }

            public function goTo(string $stepName)
            {
                app('livewire')->current()->js("\$dispatch('carousel-goto', { id: '{$this->name}', name: '{$stepName}' })");
            }

            public function goToIndex(int $index)
            {
                app('livewire')->current()->js("\$dispatch('carousel-goto', { id: '{$this->name}', index: {$index} })");
            }

            public function refresh()
            {
                app('livewire')->current()->js("\$dispatch('carousel-refresh', { id: '{$this->name}' })");
            }

            public function refreshAndGoTo(string $stepName)
            {
                app('livewire')->current()->js("
                    \$nextTick(() => {
                        \$dispatch('carousel-refresh', { id: '{$this->name}' });
                        setTimeout(() => {
                            \$dispatch('carousel-goto', { id: '{$this->name}', name: '{$stepName}' });
                        }, 50);
                    });
                ");
            }
        };
    }
}
