# Fancy Flux Demos

Ready-to-use examples for Fancy Flux components. Copy these files into your Laravel application to get started quickly.

## Available Demos

### [Basic Carousel](basic-carousel/)
Simplest usage with data-driven slides. Perfect for image galleries or simple content carousels.

### [Wizard Form](wizard-form/)
Multi-step form wizard with validation and submission. Includes step tracking and success modal.

### [Nested Carousel](nested-carousel/)
Demonstrates nesting carousels inside carousel step items with parent advancement.

### [Dynamic Carousel](dynamic-carousel/)
Add or remove slides dynamically without resetting carousel position.

### [Color Picker Examples](color-picker-examples/)
Comprehensive examples showing all color picker variants, sizes, and features.

## Usage

### Option 1: Publish Routes and Views (Recommended)

Publish demo routes and views to your application:

```bash
# Publish routes
php artisan vendor:publish --tag=fancy-flux-demos-routes

# Publish views
php artisan vendor:publish --tag=fancy-flux-demos-views
```

Then copy the PHP component files from the `demos/` folder to `app/Livewire/` and update the view paths. See [PUBLISHING.md](PUBLISHING.md) for detailed instructions.

### Option 2: Copy Files Manually

1. Copy the demo files to your Laravel application:
   - PHP files go in `app/Livewire/`
   - Blade views go in `resources/views/livewire/`

2. Register routes in `routes/web.php` (optional):
   ```php
   Route::get('/demo/{component}', [YourController::class, 'show']);
   ```

3. Access the demos at `/demo/{component-name}` or use directly as Livewire components

## Requirements

- Laravel 10+ / 11+ / 12+
- Livewire 3.7+ / 4.0+
- Flux UI 2.0+
- Fancy Flux package installed

## Note

These demos are provided as reference examples. They don't need to be functional when the package is installed - they're here for developers to copy and adapt for their own use cases.
