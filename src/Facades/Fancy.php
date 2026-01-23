<?php

namespace FancyFlux\Facades;

use Illuminate\Support\Facades\Facade;
use FancyFlux\FancyFlux;

/**
 * FANCY Facade for accessing FancyFlux features.
 *
 * Provides static access to the FancyFlux service for emoji lookup,
 * carousel control, and other integrations.
 *
 * @method static \FancyFlux\Repositories\EmojiRepository|string|null emoji(?string $slug = null)
 * @method static \FancyFlux\Managers\CarouselManager|\FancyFlux\Managers\CarouselController carousel(?string $name = null)
 * @method static string|null prefix()
 * @method static bool usesFluxNamespace()
 * @method static array components()
 *
 * @see \FancyFlux\FancyFlux
 */
class Fancy extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return FancyFlux::class;
    }
}
