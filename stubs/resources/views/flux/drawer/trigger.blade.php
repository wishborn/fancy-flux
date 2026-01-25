@blaze

{{--
    Drawer Trigger: A button component that opens/closes a drawer.

    This component provides a trigger button for opening drawers.
    Uses the Action component for consistent styling.
    
    Can be placed anywhere using the "for" prop to connect to a drawer by name.
    Similar to how Flux modals work with triggers.

    @example
    <flux:drawer name="settings-drawer">...</flux:drawer>
    <flux:drawer.trigger for="settings-drawer" name="general">Settings</flux:drawer.trigger>
    <flux:drawer.trigger for="settings-drawer" name="advanced" icon="cog">Advanced</flux:drawer.trigger>
--}}

@aware(['drawerId' => null, 'variant' => 'drawer'])

@props([
    'for' => null, // Drawer name to connect to (required if not inside drawer)
    'name' => null, // Panel name to navigate to when opening (optional)
    'drawerId' => null, // Drawer instance ID (from parent, fallback)
    'variant' => null,
])

@php
// Use aware values if not explicitly set
$variant = $variant ?? 'drawer';

// Get drawer ID - prefer "for" prop, then aware drawerId, then parent attribute
$targetDrawerId = $for 
    ?? $drawerId 
    ?? $attributes->get('data-flux-drawer-id') 
    ?? $attributes->get('drawerId')
    ?? null;

// Build the click handler
$panelParam = $name ? "'{$name}'" : 'null';

if ($targetDrawerId) {
    // Known drawer ID - dispatch directly as window event (like table/carousel)
    $clickHandler = "window.dispatchEvent(new CustomEvent('drawer-open', { detail: { id: '{$targetDrawerId}', panel: {$panelParam} } }))";
} else {
    // Fallback: try to find drawer ID from closest parent or by ID
    $clickHandler = "
        (function() {
            let el = \$el;
            let drawerId = null;
            // First, try to find drawer ID from parent attributes
            while (el && el !== document.body && !drawerId) {
                drawerId = el.getAttribute('data-flux-drawer-id') || el.getAttribute('data-flux-drawer');
                if (drawerId) break;
                el = el.parentElement;
            }
            // If still not found, try to find drawer by ID (if trigger has 'for' attribute)
            if (!drawerId) {
                const forAttr = \$el.getAttribute('for');
                if (forAttr) {
                    const drawerEl = document.getElementById(forAttr);
                    if (drawerEl) {
                        drawerId = drawerEl.getAttribute('data-flux-drawer') || drawerEl.getAttribute('data-flux-drawer-id') || forAttr;
                    }
                }
            }
            if (drawerId) {
                window.dispatchEvent(new CustomEvent('drawer-open', { detail: { id: drawerId, panel: {$panelParam} } }));
            } else {
                console.warn('Drawer trigger: Could not find drawer ID. Use the \"for\" prop to specify the drawer name.');
            }
        })()
    ";
}
@endphp

<flux:action
    {{ $attributes->except(['data-flux-drawer-id', 'drawerId'])->merge([
        'data-flux-drawer-trigger' => $targetDrawerId,
    ]) }}
    x-on:click="{{ $clickHandler }}"
>
    {{ $slot }}
</flux:action>
