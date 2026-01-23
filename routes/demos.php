<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Fancy Flux Demo Routes
|--------------------------------------------------------------------------
|
| These routes showcase Fancy Flux components. Publish these routes
| to your application to access the demo pages.
|
| After publishing, access demos at:
| - /fancy-flux-demos
| - /fancy-flux-demos/basic-carousel
| - /fancy-flux-demos/wizard-form
| - /fancy-flux-demos/nested-carousel
| - /fancy-flux-demos/dynamic-carousel
| - /fancy-flux-demos/action-examples
| - /fancy-flux-demos/color-picker-examples
| - /fancy-flux-demos/emoji-select-examples
|
| Note: You'll need to create the Livewire components in your app
| by copying the PHP files from the package demos folder.
|
*/

Route::prefix('fancy-flux-demos')->group(function () {
    Route::get('/', function () {
        return view('fancy-flux-demos::index');
    })->name('fancy-flux-demos.index');

    Route::get('/action-examples', \App\Livewire\ActionDemo::class)
        ->name('fancy-flux-demos.action-examples');

    Route::get('/basic-carousel', \App\Livewire\BasicCarouselDemo::class)
        ->name('fancy-flux-demos.basic-carousel');

    Route::get('/wizard-form', \App\Livewire\WizardDemo::class)
        ->name('fancy-flux-demos.wizard-form');

    Route::get('/nested-carousel', \App\Livewire\NestedCarouselDemo::class)
        ->name('fancy-flux-demos.nested-carousel');

    Route::get('/dynamic-carousel', \App\Livewire\DynamicCarouselDemo::class)
        ->name('fancy-flux-demos.dynamic-carousel');

    Route::get('/color-picker-examples', \App\Livewire\ColorPickerDemo::class)
        ->name('fancy-flux-demos.color-picker-examples');

    Route::get('/emoji-select-examples', \App\Livewire\EmojiSelectDemo::class)
        ->name('fancy-flux-demos.emoji-select-examples');
});
