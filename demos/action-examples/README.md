# Action Component Demo

This demo showcases the `flux:action` component with all its variants and features.

## Features Demonstrated

- **State Variants**: Default, active, warn, and alert states
- **Size Variants**: Small (sm), medium (md), and large (lg)
- **Icon Support**: Leading and trailing icon placement
- **Icon Placement**: Left, right, top, bottom positioning
- **Alert Icons**: Pulsing alert indicators
- **Disabled State**: Visual feedback for unavailable actions
- **Livewire Integration**: Interactive counter example
- **Real-world Examples**: Chat action bar and text formatting toolbar

## Installation

1. Copy `action-examples.php` to `app/Livewire/ActionDemo.php`
2. Copy `action-examples.blade.php` to `resources/views/livewire/action-examples.blade.php`
3. Add a route in your `routes/web.php`:

```php
use App\Livewire\ActionDemo;

Route::get('/action-demo', ActionDemo::class);
```

## Usage

Visit `/action-demo` to see the component in action.
