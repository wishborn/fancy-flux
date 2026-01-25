<?php

namespace FancyFlux;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Foundation\AliasLoader;
use FancyFlux\Concerns\InteractsWithCarousel;
use FancyFlux\Facades\Fancy;
use Livewire\Component;

class FancyFluxServiceProvider extends ServiceProvider
{
    use InteractsWithCarousel;

    /**
     * Register any application services.
     *
     * Binds the FancyFlux service class and registers the FANCY facade alias.
     */
    public function register(): void
    {
        // Bind the FancyFlux service as a singleton
        $this->app->singleton(FancyFlux::class, function ($app) {
            return new FancyFlux();
        });

        // Register the FANCY facade alias for global access
        $loader = AliasLoader::getInstance();
        $loader->alias('FANCY', Fancy::class);

        // Load base PHP config
        $this->mergeConfigFrom(
            __DIR__.'/../config/fancy-flux.php',
            'fancy-flux'
        );

        // ENV variables override PHP config (highest priority)
        if (env('FANCY_FLUX_PREFIX') !== null) {
            config(['fancy-flux.prefix' => env('FANCY_FLUX_PREFIX')]);
        }
        if (env('FANCY_FLUX_USE_FLUX_NAMESPACE') !== null) {
            config(['fancy-flux.use_flux_namespace' => filter_var(env('FANCY_FLUX_USE_FLUX_NAMESPACE'), FILTER_VALIDATE_BOOLEAN)]);
        }
        if (env('FANCY_FLUX_ENABLE_DEMO_ROUTES') !== null) {
            config(['fancy-flux.enable_demo_routes' => filter_var(env('FANCY_FLUX_ENABLE_DEMO_ROUTES'), FILTER_VALIDATE_BOOLEAN)]);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->bootComponentPath();
        $this->bootCarousel();
        $this->publishAssets();
        $this->publishConfig();
        $this->publishDemos();
    }

    /**
     * Register the component path for fancy-flux components.
     * 
     * Components can be registered with a custom prefix to avoid conflicts
     * with official Flux components or other custom components.
     * 
     * Configuration:
     *   - FANCY_FLUX_PREFIX: Custom prefix (e.g., 'fancy' for <fancy:carousel>)
     *   - FANCY_FLUX_USE_FLUX_NAMESPACE: Also register in 'flux' namespace (default: true)
     * 
     * Examples:
     *   - No prefix: <flux:carousel> (default)
     *   - Prefix 'fancy': <fancy:carousel> (and optionally <flux:carousel>)
     */
    protected function bootComponentPath(): void
    {
        $prefix = config('fancy-flux.prefix');
        $useFluxNamespace = config('fancy-flux.use_flux_namespace', true);
        $componentPath = __DIR__.'/../stubs/resources/views/flux';

        // Register components with custom prefix if set
        if ($prefix) {
            Blade::anonymousComponentPath($componentPath, $prefix);
        }

        // Register components in 'flux' namespace (for backward compatibility or default)
        if ($useFluxNamespace || !$prefix) {
            Blade::anonymousComponentPath($componentPath, 'flux');
        }
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

    /**
     * Publish configuration file.
     */
    protected function publishConfig(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/fancy-flux.php' => config_path('fancy-flux.php'),
            ], 'fancy-flux-config');
        }
    }

    /**
     * Publish demo routes and views.
     * 
     * Demos can be accessed directly from the package (for test app) or published to the app.
     * After publishing, users can access demos at /fancy-flux-demos
     * 
     * To publish:
     *   php artisan vendor:publish --tag=fancy-flux-demos-routes
     *   php artisan vendor:publish --tag=fancy-flux-demos-views
     */
    protected function publishDemos(): void
    {
        if ($this->app->runningInConsole()) {
            // Publish demo routes (users can customize these)
            $this->publishes([
                __DIR__.'/../routes/demos.php' => base_path('routes/fancy-flux-demos.php'),
            ], 'fancy-flux-demos-routes');

            // Publish demo views only (not PHP source files)
            $this->publishes([
                __DIR__.'/../demos/action-examples/action-examples.blade.php' => resource_path('views/livewire/action-examples-demo.blade.php'),
                __DIR__.'/../demos/basic-carousel/basic-carousel.blade.php' => resource_path('views/livewire/basic-carousel-demo.blade.php'),
                __DIR__.'/../demos/wizard-form/wizard-form.blade.php' => resource_path('views/livewire/wizard-form-demo.blade.php'),
                __DIR__.'/../demos/nested-carousel/nested-carousel.blade.php' => resource_path('views/livewire/nested-carousel-demo.blade.php'),
                __DIR__.'/../demos/dynamic-carousel/dynamic-carousel.blade.php' => resource_path('views/livewire/dynamic-carousel-demo.blade.php'),
                __DIR__.'/../demos/color-picker-examples/color-picker-examples.blade.php' => resource_path('views/livewire/color-picker-examples-demo.blade.php'),
                __DIR__.'/../demos/emoji-select-examples/emoji-select-examples.blade.php' => resource_path('views/livewire/emoji-select-examples-demo.blade.php'),
                __DIR__.'/../demos/drawer-examples/drawer-examples.blade.php' => resource_path('views/livewire/drawer-examples.blade.php'),
            ], 'fancy-flux-demos-views');
        }

        // Register demo views namespace for direct access from package
        // Views in subdirectories are accessed using dot notation:
        // fancy-flux-demos::emoji-select-examples.emoji-select-examples
        $this->loadViewsFrom(__DIR__.'/../demos', 'fancy-flux-demos');

        // Load demo routes from package if enabled via config
        // Users can publish routes to customize them instead
        if (config('fancy-flux.enable_demo_routes', false)) {
            $this->loadRoutesFrom(__DIR__.'/../routes/demos.php');
        }
    }
}
