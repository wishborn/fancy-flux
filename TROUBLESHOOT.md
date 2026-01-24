# Troubleshooting Guide

Common issues and solutions for Fancy Flux, organized by version.

---

## Legend

| Symbol | Meaning |
|--------|---------|
| 🔴 **BREAKING** | Breaking change - requires code updates |
| 🟡 **IMPORTANT** | Important note - may affect behavior |
| 🟢 **TIP** | Helpful tip or best practice |

---

## Upgrade Notes

### Upgrading from 1.0.3 to 1.0.11

This section covers all notable changes when upgrading from v1.0.3 to the latest version.

#### New Features Added

| Version | Feature | Description |
|---------|---------|-------------|
| 1.0.4 | **Action Component** | New button component with state variants (active, warn, alert) |
| 1.0.10 | **FANCY Facade** | Unified API for emoji lookup and carousel control |
| 1.0.10 | **Emoji Support** | 787+ emojis with slug-based lookup |
| 1.0.10 | **Action Emoji Props** | `emoji` and `emoji-trailing` props for action buttons |

#### Migration Steps

**1. Clear Caches After Upgrade**

```bash
composer update wishborn/fancy-flux
php artisan view:clear
php artisan cache:clear
```

**2. 🟢 TIP: Use the FANCY Facade (v1.0.10+)**

The new FANCY facade provides a cleaner API for carousel control:

```php
// Old way (still works)
use FancyFlux\Concerns\InteractsWithCarousel;

class MyComponent extends Component
{
    use InteractsWithCarousel;
    
    public function nextStep(): void
    {
        $this->carousel('wizard')->next();
    }
}

// New way (recommended)
use FancyFlux\Facades\FANCY;

class MyComponent extends Component
{
    public function nextStep(): void
    {
        FANCY::carousel('wizard')->next();
    }
}
```

**3. 🟢 TIP: Use Emoji Slugs in Actions (v1.0.10+)**

Action buttons now support emojis via slug:

```blade
<!-- New emoji support -->
<flux:action emoji="fire">Hot!</flux:action>
<flux:action emoji="rocket" emoji-trailing="sparkles">Launch</flux:action>
```

**4. 🟡 IMPORTANT: Boost Guidelines Format (v1.0.11)**

If you're a package maintainer extending Fancy Flux, note that Boost guidelines must use `.md` format, not `.blade.php`. See the Laravel Boost documentation for details.

#### No Breaking Changes

Upgrading from 1.0.3 to 1.0.11 has **no breaking changes**. All existing code will continue to work:

- ✅ `InteractsWithCarousel` trait still works (delegates to FANCY facade internally)
- ✅ All component props remain unchanged
- ✅ All existing templates continue to work
- ✅ Configuration options unchanged

---

### Upgrading from 1.0.0 to 1.0.3

#### Fixed in 1.0.1

**Carousel Controls** - If you experienced issues with prev/next buttons not working in v1.0.0, upgrade to v1.0.1+ which fixed the Alpine.js scope resolution.

#### Added in 1.0.3

**USAGE.md Documentation** - Comprehensive documentation with tested examples was added.

---

### Quick Upgrade Command

```bash
# Upgrade to latest
composer require wishborn/fancy-flux:^1.0.11

# Clear caches
php artisan view:clear && php artisan cache:clear

# Verify installation
php artisan tinker --execute="echo 'Fancy Flux ' . composer_show('wishborn/fancy-flux')['versions'][0];"
```

---

## Version 1.0.11

### Laravel Boost Integration

**Issue:** `boost:install` crashes with "expecting endif" error

**Fixed in v1.0.11** - Guidelines file converted from `.blade.php` to `.md` format.

---

## Version 1.0.10

### Laravel Boost Integration

**Issue:** `boost:install` crashes with "expecting endif" error

```
Illuminate\View\ViewException
syntax error, unexpected end of file, expecting "elseif" or "else" or "endif"
```

**Cause:** The guidelines file was using `.blade.php` extension with `@verbatim` blocks containing `<flux:...>` components. Livewire's Blade compiler processes these as real components even inside `@verbatim`.

**Solution:** Guidelines file was converted to `.md` format. If you're on an older version, ensure `resources/boost/guidelines/core.md` exists (not `core.blade.php`).

---

**Issue:** FANCY facade not found

```
Class "FANCY" not found
```

**Solution:** Import the facade at the top of your file:

```php
use FancyFlux\Facades\FANCY;
```

Or use the full namespace:

```php
\FancyFlux\Facades\FANCY::emoji('fire');
```

---

### Emoji Component

**Issue:** Emoji not displaying, shows slug instead

**Cause:** Invalid emoji slug passed to component or facade.

**Solution:** Use valid kebab-case slugs. Check available slugs:

```php
// List all available slugs
FANCY::emoji()->list();

// Search for emojis
FANCY::emoji()->search('heart');
```

---

## Version 1.0.4

### Action Component

**Issue:** Icon not appearing in action button

**Cause:** Invalid Heroicon name or missing icon package.

**Solution:** Ensure you're using valid Heroicon names (outline style by default):

```blade
<!-- Correct -->
<flux:action icon="pencil">Edit</flux:action>

<!-- Incorrect - don't include style prefix -->
<flux:action icon="heroicon-o-pencil">Edit</flux:action>
```

---

**Issue:** Alert pulse animation not working

**Cause:** Missing `alert` prop or Tailwind animation not compiled.

**Solution:** 
1. Ensure both `alert` and `alert-icon` props are set:
   ```blade
   <flux:action alert alert-icon="bell">Alert!</flux:action>
   ```
2. Run `npm run build` to compile Tailwind animations

---

## Version 1.0.1

### Carousel Controls

**Issue:** Carousel controls not working (prev/next buttons do nothing)

**Cause:** In v1.0.0, controls relied on event listeners that could fail to bind.

**Solution:** Upgrade to v1.0.1+ which uses direct Alpine.js scope resolution:

```bash
composer update wishborn/fancy-flux
```

---

**Issue:** Wizard carousel steps not navigating

**Cause:** Missing or mismatched `name` props between carousel and controls.

**Solution:** Ensure carousel has a `name` prop and it's consistent:

```blade
<flux:carousel variant="wizard" name="my-wizard">
    <flux:carousel.steps>
        <flux:carousel.step name="step1" label="Step 1" />
    </flux:carousel.steps>
    
    <flux:carousel.panels>
        <flux:carousel.step.item name="step1">
            Content
        </flux:carousel.step.item>
    </flux:carousel.panels>
    
    <flux:carousel.controls />
</flux:carousel>
```

---

## Version 1.0.0

### General Issues

**Issue:** Components not registered / "Component not found"

**Cause:** Service provider not auto-discovered.

**Solution:** 
1. Clear caches: `php artisan view:clear && php artisan cache:clear`
2. If still failing, manually register in `config/app.php`:
   ```php
   'providers' => [
       // ...
       FancyFlux\FancyFluxServiceProvider::class,
   ],
   ```

---

**Issue:** Component prefix conflicts with official Flux

**Cause:** Default configuration registers components under `flux:` namespace which conflicts with official Flux components.

**Solution:** Configure a custom prefix:

1. Publish config:
   ```bash
   php artisan vendor:publish --tag=fancy-flux-config
   ```

2. Set environment variables:
   ```env
   FANCY_FLUX_PREFIX=fancy
   FANCY_FLUX_USE_FLUX_NAMESPACE=false
   ```

3. Use components with prefix:
   ```blade
   <fancy:carousel>...</fancy:carousel>
   <fancy:color-picker />
   ```

---

### Color Picker

**Issue:** Color value not syncing with Livewire

**Cause:** Missing `wire:model` directive.

**Solution:** Add `wire:model` or `wire:model.live`:

```blade
<flux:color-picker wire:model.live="color" />
```

---

**Issue:** Preset colors not showing

**Cause:** Presets passed without hash symbols or as invalid format.

**Solution:** Pass presets as array of hex values (without #):

```blade
<flux:color-picker :presets="['3b82f6', '8b5cf6', 'ec4899']" />
```

---

## Common Issues (All Versions)

### Dark Mode

**Issue:** Components don't respect dark mode

**Cause:** Tailwind dark mode not configured or components not using dark: variants.

**Solution:** Ensure Tailwind config has dark mode enabled:

```js
// tailwind.config.js
module.exports = {
    darkMode: 'class', // or 'media'
    // ...
}
```

---

### Livewire Integration

**Issue:** `wire:click` not firing on action buttons

**Cause:** Button inside a form or competing event handlers.

**Solution:** 
1. Use `wire:click.prevent` if inside a form
2. Ensure no JavaScript is blocking the event
3. Check browser console for errors

---

### Alpine.js Conflicts

**Issue:** "Alpine expression error" in console

**Cause:** Multiple Alpine.js instances or version mismatch.

**Solution:** 
1. Ensure only one Alpine instance (Livewire includes it)
2. Don't manually include Alpine if using Livewire 3+
3. Check for conflicting Alpine plugins

---

## Getting Help

If your issue isn't listed here:

1. Check the [GitHub Issues](https://github.com/wishborn/fancy-flux/issues)
2. Review the component documentation in `docs/`
3. Try the demo components in `demos/`
4. Open a new issue with:
   - Fancy Flux version
   - Laravel/Livewire/Flux versions
   - Minimal reproduction code
   - Error messages and stack trace
