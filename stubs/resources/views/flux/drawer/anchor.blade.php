@blaze

{{--
    Drawer Anchor: The anchor element that the drawer attaches to.

    This component wraps the anchor element (input, button, etc.) and provides
    visual continuity with the drawer when expanded. It automatically adjusts
    border-radius and borders based on drawer state.

    @example
    <flux:drawer name="chat-actions" variant="attached">
        <flux:drawer.panels>
            <flux:drawer.panel name="actions">...</flux:drawer.panel>
        </flux:drawer.panels>
        
        <flux:drawer.anchor>
            <div class="flex items-center gap-2 p-3 border">
                <flux:drawer.trigger for="chat-actions">Toggle</flux:drawer.trigger>
                <input type="text" />
            </div>
        </flux:drawer.anchor>
    </flux:drawer>
--}}

@aware(['drawerId' => null, 'variant' => 'attached', 'anchorPosition' => 'top'])

@props([])

@php
// Get drawer ID from parent
$parentDrawerId = $drawerId ?? $attributes->get('data-flux-drawer-id') ?? null;
@endphp

@php
$anchorPos = $anchorPosition ?? 'top';
@endphp

<div 
    class="relative z-50 transition-all rounded-2xl"
    x-data="{
        isOpen: false,
        init() {
            const checkState = () => {
                const drawerEl = \$el.closest('[data-flux-drawer]');
                if (drawerEl && window.Alpine) {
                    const data = window.Alpine.\$data(drawerEl);
                    if (data) {
                        this.isOpen = data.isOpen || false;
                    }
                }
            };
            checkState();
            setInterval(checkState, 50);
        }
    }"
    x-bind:class="{
        'rounded-b-2xl border-t-0': isOpen && '{{ $anchorPos }}' === 'top',
        'rounded-t-2xl border-b-0': isOpen && '{{ $anchorPos }}' === 'bottom',
        'rounded-2xl': !isOpen
    }"
    data-flux-drawer-anchor="{{ $parentDrawerId }}"
>
    {{ $slot }}
</div>
