<?php

namespace FancyFlux\Concerns;

use FancyFlux\FancyFlux;
use FancyFlux\Managers\CarouselController;
use Livewire\Component;

/**
 * Trait for interacting with carousel components.
 *
 * Provides helper methods for programmatic carousel control in Livewire components.
 * This trait delegates to the FANCY facade's CarouselManager for consistency.
 *
 * @deprecated Use FANCY::carousel('name') instead for new code.
 * @example $this->carousel('wizard')->goTo('step-2')
 * @example FANCY::carousel('wizard')->goTo('step-2') // Preferred
 */
trait InteractsWithCarousel
{
    /**
     * Boot the carousel component macro.
     * Enables $this->carousel('name') syntax in Livewire components.
     *
     * Why: Registers a macro on all Livewire components for carousel control.
     * This provides backward compatibility while the FANCY facade is preferred.
     */
    public function bootCarousel(): void
    {
        Component::macro('carousel', function (string $name): CarouselController {
            return app(FancyFlux::class)->carousel($name);
        });
    }

    /**
     * Get a carousel controller instance.
     *
     * Usage: $this->carousel('carousel-name')->next()
     *
     * @param string $name The carousel's unique name/id
     * @return CarouselController
     */
    public function carousel(string $name): CarouselController
    {
        return app(FancyFlux::class)->carousel($name);
    }
}
