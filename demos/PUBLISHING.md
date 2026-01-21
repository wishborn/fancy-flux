# Publishing Fancy Flux Demos

The Fancy Flux demos can be published to your application as routes and views (not source code). This allows you to showcase components without copying PHP files.

## Publishing Demos

### Step 1: Publish Demo Routes

```bash
php artisan vendor:publish --tag=fancy-flux-demos-routes
```

This creates `routes/fancy-flux-demos.php` in your application. You can customize these routes as needed.

### Step 2: Publish Demo Views

```bash
php artisan vendor:publish --tag=fancy-flux-demos-views
```

This publishes the Blade view files to `resources/views/livewire/`:
- `basic-carousel-demo.blade.php`
- `wizard-form-demo.blade.php`
- `nested-carousel-demo.blade.php`
- `dynamic-carousel-demo.blade.php`
- `color-picker-examples-demo.blade.php`
- `emoji-select-examples-demo.blade.php`

### Step 3: Create Livewire Components

You'll need to create the Livewire component classes in your application. Copy the PHP files from the package `demos/` folder to `app/Livewire/`:

- `demos/basic-carousel/basic-carousel.php` → `app/Livewire/BasicCarouselDemo.php`
- `demos/wizard-form/wizard-form.php` → `app/Livewire/WizardFormDemo.php`
- `demos/nested-carousel/nested-carousel.php` → `app/Livewire/NestedCarouselDemo.php`
- `demos/dynamic-carousel/dynamic-carousel.php` → `app/Livewire/DynamicCarouselDemo.php`
- `demos/color-picker-examples/color-picker-examples.php` → `app/Livewire/ColorPickerExamplesDemo.php`
- `demos/emoji-select-examples/emoji-select-examples.php` → `app/Livewire/EmojiSelectExamplesDemo.php`

**Important:** Update the namespace in each file from `App\Livewire` to match your application's namespace, and update the `render()` method to use the published view paths:

```php
public function render()
{
    return view('livewire.basic-carousel-demo'); // or wizard-form-demo, etc.
}
```

### Step 4: Access Demos

After publishing, access demos at:
- `/fancy-flux-demos` - Demo index page
- `/fancy-flux-demos/basic-carousel`
- `/fancy-flux-demos/wizard-form`
- `/fancy-flux-demos/nested-carousel`
- `/fancy-flux-demos/dynamic-carousel`
- `/fancy-flux-demos/color-picker-examples`
- `/fancy-flux-demos/emoji-select-examples`

## Using Package Demos Directly (Test App)

For development/testing purposes, you can load demos directly from the package without publishing:

1. **Enable in config:**
   ```env
   FANCY_FLUX_LOAD_DEMO_ROUTES=true
   ```

2. **Create Livewire components** that reference package views:
   ```php
   public function render()
   {
       return view('fancy-flux-demos::basic-carousel');
   }
   ```

3. **Register routes** that use your Livewire components:
   ```php
   Route::get('/demo/carousel', BasicCarouselDemo::class);
   ```

## Notes

- **Views are publishable** - You can customize the Blade templates after publishing
- **Routes are publishable** - You can customize URLs and routing logic
- **PHP components are NOT published** - You copy these manually to maintain control over your application code
- **Package views use namespace** - `fancy-flux-demos::` namespace allows direct access from package
