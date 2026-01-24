<?php

namespace FancyFlux;

use FancyFlux\Repositories\EmojiRepository;
use FancyFlux\Managers\CarouselManager;
use FancyFlux\Managers\CarouselController;
use FancyFlux\Managers\TableManager;
use FancyFlux\Managers\TableController;

/**
 * Main FancyFlux service class.
 *
 * Provides a unified API for accessing FancyFlux features programmatically.
 * This class is bound to the service container and accessed via the FANCY facade.
 *
 * Why: Centralizes all FancyFlux integrations behind a clean, discoverable API
 * that follows the pattern used by Flux for modal control.
 *
 * @example FANCY::emoji()->list()
 * @example FANCY::emoji('grinning-face') // shorthand for FANCY::emoji()->get('grinning-face')
 * @example FANCY::carousel('my-carousel')->next()
 * @example FANCY::table('users')->refresh()
 */
class FancyFlux
{
    protected EmojiRepository $emojiRepository;

    protected CarouselManager $carouselManager;

    protected TableManager $tableManager;

    public function __construct()
    {
        $this->emojiRepository = new EmojiRepository();
        $this->carouselManager = new CarouselManager();
        $this->tableManager = new TableManager();
    }

    /**
     * Access the emoji repository or get an emoji by slug.
     *
     * When called without arguments, returns the EmojiRepository for chained calls.
     * When called with a slug, returns the emoji character directly.
     *
     * @param string|null $slug Optional emoji slug for direct lookup
     * @return EmojiRepository|string|null Repository instance, emoji character, or null
     *
     * @example FANCY::emoji()->list() // Get all slugs
     * @example FANCY::emoji()->find('fire') // Get emoji data
     * @example FANCY::emoji('fire') // Get emoji char directly: '🔥'
     */
    public function emoji(?string $slug = null): EmojiRepository|string|null
    {
        if ($slug === null) {
            return $this->emojiRepository;
        }

        return $this->emojiRepository->get($slug);
    }

    /**
     * Access the carousel manager or get a controller for a specific carousel.
     *
     * @param string|null $name Optional carousel name for direct access
     * @return CarouselManager|CarouselController Manager or controller instance
     *
     * @example FANCY::carousel()->get('wizard') // Get carousel controller
     * @example FANCY::carousel('wizard')->next() // Direct access and navigate
     */
    public function carousel(?string $name = null): CarouselManager|CarouselController
    {
        if ($name === null) {
            return $this->carouselManager;
        }

        return $this->carouselManager->get($name);
    }

    /**
     * Access the table manager or get a controller for a specific table.
     *
     * @param string|null $name Optional table name for direct access
     * @return TableManager|TableController Manager or controller instance
     *
     * @example FANCY::table()->get('users') // Get table controller
     * @example FANCY::table('users')->refresh() // Direct access and refresh
     * @example FANCY::table('users')->selectAll() // Select all rows
     */
    public function table(?string $name = null): TableManager|TableController
    {
        if ($name === null) {
            return $this->tableManager;
        }

        return $this->tableManager->get($name);
    }

    /**
     * Get the configured component prefix.
     *
     * @return string|null
     */
    public function prefix(): ?string
    {
        return config('fancy-flux.prefix');
    }

    /**
     * Check if components are also registered in the flux namespace.
     *
     * @return bool
     */
    public function usesFluxNamespace(): bool
    {
        return config('fancy-flux.use_flux_namespace', true);
    }

    /**
     * Get the list of available components.
     *
     * @return array<string>
     */
    public function components(): array
    {
        return config('fancy-flux.components', []);
    }
}
