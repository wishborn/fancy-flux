<?php

namespace FancyFlux;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use FancyFlux\Concerns\InteractsWithCarousel;
use Livewire\Component;

class FancyFluxServiceProvider extends ServiceProvider
{
    use InteractsWithCarousel;

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->bootComponentPath();
        $this->bootCarousel();
        $this->publishAssets();
    }

    /**
     * Register the component path for fancy-flux components.
     * Components will be available as <flux:carousel> and <flux:color-picker>
     * 
     * Note: Laravel checks all registered paths for a namespace, so both
     * Flux's components and fancy-flux components will be available.
     */
    protected function bootComponentPath(): void
    {
        // Register fancy-flux components in the flux namespace
        // This allows them to work alongside original Flux components
        // Laravel will check all registered paths for the 'flux' namespace
        Blade::anonymousComponentPath(
            __DIR__.'/../stubs/resources/views/flux',
            'flux'
        );
    }

    /**
     * Publish JavaScript assets.
     */
    protected function publishAssets(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../resources/js' => public_path('vendor/fancy-flux/js'),
            ], 'fancy-flux-assets');
        }
    }
}
