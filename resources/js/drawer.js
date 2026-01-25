/**
 * Flux Drawer Helper Extension
 * 
 * Extends the window.Flux object to include drawer helpers.
 * Usage in Alpine: Flux.drawer('drawer-name').open()
 * Usage in JS: Flux.drawer('drawer-name').open()
 */

/**
 * Create the drawer helper function.
 * This creates a helper for controlling drawers programmatically.
 * 
 * @param {string} name - The drawer name/id
 * @returns {object} Drawer control methods
 */
function createDrawerHelper(name) {
    // Find the drawer element
    const getEl = () => document.querySelector(`[data-flux-drawer="${name}"]`);

    // Get Alpine data from the drawer
    const getData = () => {
        const drawerEl = getEl();
        return drawerEl && window.Alpine ? Alpine.$data(drawerEl) : null;
    };

    return {
        // Element reference
        get el() { return getEl(); },

        // State getters
        get isOpen() { return getData()?.isOpen ?? false; },
        get activePanel() { return getData()?.activePanel; },
        get activeIndex() { return getData()?.activeIndex ?? -1; },
        get panels() { return getData()?.panels || []; },
        get variant() { return getData()?.variant; },
        get position() { return getData()?.position; },

        // Control methods
        open(panelName = null) {
            window.dispatchEvent(new CustomEvent('drawer-open', { 
                detail: { id: name, panel: panelName } 
            }));
        },

        close() {
            window.dispatchEvent(new CustomEvent('drawer-close', { 
                detail: { id: name } 
            }));
        },

        toggle() {
            window.dispatchEvent(new CustomEvent('drawer-toggle', { 
                detail: { id: name } 
            }));
        },

        goTo(panelName) {
            window.dispatchEvent(new CustomEvent('drawer-goto', { 
                detail: { id: name, name: panelName } 
            }));
        },

        // Combined open + navigate
        openTo(panelName) {
            this.open(panelName);
        },

        // Navigation methods (for multi-panel drawers)
        next() {
            const data = getData();
            if (data && data.panels && data.activeIndex < data.panels.length - 1) {
                this.goTo(data.panels[data.activeIndex + 1]);
            }
        },

        prev() {
            const data = getData();
            if (data && data.activeIndex > 0) {
                this.goTo(data.panels[data.activeIndex - 1]);
            }
        },

        // State checks
        isActive(panelName) { return getData()?.isActive(panelName) ?? false; },
    };
}

// Register the helper on alpine:init which fires BEFORE Alpine evaluates components
// This ensures Flux.drawer is available when x-data is evaluated
document.addEventListener('alpine:init', () => {
    if (window.Flux && !window.Flux.drawer) {
        window.Flux.drawer = createDrawerHelper;
    }
});

// Also try to register immediately if Flux is already available (for non-Livewire pages)
if (window.Flux && !window.Flux.drawer) {
    window.Flux.drawer = createDrawerHelper;
}
