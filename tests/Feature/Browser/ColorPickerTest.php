<?php

/**
 * Browser tests for the flux:color-picker component.
 *
 * These tests verify visual appearance and interactive behavior of the color picker,
 * ensuring compliance with Flux design standards. Tests live in the main project repo
 * so they can be reused across different component branches in the flux package.
 */

describe('color picker component', function () {
    it('renders with swatch and hex value display', function () {
        $page = visit('/');

        // Component should render - use count check since there are multiple pickers
        $page->assertCount('[data-flux-color-picker]', 9)
            ->assertSee('#3B82F6'); // Primary color hex value (uppercase display)
    });

    it('displays label inline with color picker by default', function () {
        $page = visit('/');

        // Labels should be visible and inline with pickers
        $page->assertSee('Primary Color')
            ->assertSee('Secondary Color');
    });

    it('shows different size variants', function () {
        $page = visit('/');

        // All size labels should be visible
        $page->assertSee('Small')
            ->assertSee('Default')
            ->assertSee('Large');
    });

    it('can be used without a label', function () {
        $page = visit('/');

        // Should see the "Without Label" section
        $page->assertSee('Without Label')
            ->assertSee('Can be used standalone without a label.');
    });

    it('has no JavaScript errors on page load', function () {
        $page = visit('/');

        $page->assertNoJavascriptErrors();
    });

    it('has no console errors on page load', function () {
        $page = visit('/');

        $page->assertNoConsoleLogs();
    });
});

describe('color picker in dark mode', function () {
    it('renders correctly in dark mode', function () {
        $page = visit('/')->inDarkMode();

        // Component should still be visible and functional in dark mode
        $page->assertCount('[data-flux-color-picker]', 9)
            ->assertSee('Primary Color')
            ->assertNoJavascriptErrors();
    });
});

describe('color picker interactions', function () {
    it('has native color input available for interaction', function () {
        $page = visit('/');

        // The color input should be present - multiple inputs on page
        $page->assertCount('[data-flux-color-picker] input[type="color"]', 9);
    });

    it('updates hex display when color changes via Alpine', function () {
        $page = visit('/');

        // Verify the primary color picker shows expected value
        $page->assertSee('#3B82F6');

        // Use JavaScript to change the first color picker and verify Alpine updates the display
        $page->script("
            const picker = document.querySelector('[data-flux-color-picker]');
            picker._x_dataStack[0].color = '#ff0000';
        ");

        // Wait for Alpine to update and verify the display changed
        $page->waitForText('#FF0000')
            ->assertSee('#FF0000');
    });
});

describe('color picker with presets', function () {
    it('renders datalist elements for preset colors', function () {
        $page = visit('/');

        // Verify datalist exists in the DOM via script (datalists are hidden from accessibility tree)
        // The script method evaluates JS and returns the result
        $page->assertScript('document.querySelectorAll("datalist").length > 0');
    });
});
