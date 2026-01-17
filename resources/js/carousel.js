/**
 * Flux Carousel Helper Extension
 * 
 * Extends the window.Flux object to include carousel helpers.
 * Usage in Alpine: Flux.carousel('carousel-name').next()
 * Usage in JS: Flux.carousel('carousel-name').next()
 */

/**
 * Create the carousel helper function.
 * This creates a helper for controlling carousels programmatically.
 * 
 * @param {string} name - The carousel name/id
 * @returns {object} Carousel control methods
 */
function createCarouselHelper(name) {
    // Find the carousel element
    const getEl = () => document.getElementById(name) 
        || document.querySelector(`[data-flux-carousel][id="${name}"]`);

    // Get Alpine data from the carousel
    const getData = () => {
        const carouselEl = getEl();
        return carouselEl && window.Alpine ? Alpine.$data(carouselEl) : null;
    };

    return {
        // Element reference
        get el() { return getEl(); },

        // State getters
        get active() { return getData()?.active; },
        get activeIndex() { return getData()?.activeIndex; },
        get steps() { return getData()?.steps || []; },
        get totalSteps() { return getData()?.totalSteps || 0; },
        get variant() { return getData()?.variant; },
        get headless() { return getData()?.headless; },

        // Navigation methods
        next() {
            window.dispatchEvent(new CustomEvent('carousel-next', { 
                detail: { id: name } 
            }));
        },

        prev() {
            window.dispatchEvent(new CustomEvent('carousel-prev', { 
                detail: { id: name } 
            }));
        },

        goTo(stepName) {
            window.dispatchEvent(new CustomEvent('carousel-goto', { 
                detail: { id: name, name: stepName } 
            }));
        },

        goToIndex(index) {
            window.dispatchEvent(new CustomEvent('carousel-goto', { 
                detail: { id: name, index } 
            }));
        },

        refresh() {
            window.dispatchEvent(new CustomEvent('carousel-refresh', { 
                detail: { id: name } 
            }));
        },

        // Combined refresh + navigate (for dynamic content)
        refreshAndGoTo(stepName) {
            this.refresh();
            setTimeout(() => this.goTo(stepName), 50);
        },

        // State checks
        isActive(stepName) { return getData()?.isActive(stepName) ?? false; },
        isFirst() { return getData()?.isFirst() ?? true; },
        isLast() { return getData()?.isLast() ?? true; },
        canGoPrev() { return getData()?.canGoPrev() ?? false; },
        canGoNext() { return getData()?.canGoNext() ?? false; },
    };
}

// Register the helper on alpine:init which fires BEFORE Alpine evaluates components
// This ensures Flux.carousel is available when x-data is evaluated
document.addEventListener('alpine:init', () => {
    if (window.Flux && !window.Flux.carousel) {
        window.Flux.carousel = createCarouselHelper;
    }
});

// Also try to register immediately if Flux is already available (for non-Livewire pages)
if (window.Flux && !window.Flux.carousel) {
    window.Flux.carousel = createCarouselHelper;
}
