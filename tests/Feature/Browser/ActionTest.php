<?php

/**
 * Browser tests for the flux:action component.
 *
 * These tests verify visual appearance and interactive behavior of the action button,
 * ensuring compliance with Flux design standards. Tests validate state variants,
 * icon placement, size options, and Livewire integration.
 */

describe('action component', function () {
    it('renders all state variants', function () {
        $page = visit('/fancy-flux/action');

        // State variants section should show all four states
        $page->assertSee('State Variants')
            ->assertSee('Default')
            ->assertSee('Active')
            ->assertSee('Warning')
            ->assertSee('Alert');
    });

    it('renders all size variants', function () {
        $page = visit('/fancy-flux/action');

        // Size variants section
        $page->assertSee('Size Variants')
            ->assertSee('Small')
            ->assertSee('Medium')
            ->assertSee('Large');
    });

    it('renders action buttons with icons', function () {
        $page = visit('/fancy-flux/action');

        // Icon section should show buttons with text
        $page->assertSee('With Icons')
            ->assertSee('Edit')
            ->assertSee('Delete')
            ->assertSee('Next')
            ->assertSee('Confirm');
    });

    it('renders icon placement options', function () {
        $page = visit('/fancy-flux/action');

        // Icon placement section
        $page->assertSee('Icon Placement')
            ->assertSee('Left')
            ->assertSee('Right')
            ->assertSee('Top')
            ->assertSee('Bottom');
    });

    it('renders alert icons with pulsing animation', function () {
        $page = visit('/fancy-flux/action');

        // Alert icons section
        $page->assertSee('Alert Icons')
            ->assertSee('Notifications')
            ->assertSee('3 New Messages');
    });

    it('renders disabled state buttons', function () {
        $page = visit('/fancy-flux/action');

        // Disabled section
        $page->assertSee('Disabled State')
            ->assertSee('Locked')
            ->assertSee('Disabled Active');
    });

    it('has data-flux-action attribute on buttons', function () {
        $page = visit('/fancy-flux/action');

        // Verify the data attribute is present
        $page->assertScript('document.querySelectorAll("[data-flux-action]").length > 0');
    });

    it('has no JavaScript errors on page load', function () {
        $page = visit('/fancy-flux/action');

        $page->assertNoJavascriptErrors();
    });

    it('has no console errors on page load', function () {
        $page = visit('/fancy-flux/action');

        $page->assertNoConsoleLogs();
    });
});

describe('action component in dark mode', function () {
    it('renders correctly in dark mode', function () {
        $page = visit('/fancy-flux/action')->inDarkMode();

        // Component should still be visible and functional in dark mode
        $page->assertSee('Action Component Examples')
            ->assertSee('Default')
            ->assertSee('Active')
            ->assertNoJavascriptErrors();
    });
});

describe('action component Livewire integration', function () {
    it('shows initial counter value', function () {
        $page = visit('/fancy-flux/action');

        // Counter should start at 0
        $page->assertSee('Increment (0)');
    });

    it('increments counter when button is clicked', function () {
        $page = visit('/fancy-flux/action');

        // Click increment button
        $page->click('Increment (0)')
            ->waitForText('Increment (1)')
            ->assertSee('Increment (1)');
    });

    it('decrements counter when button is clicked', function () {
        $page = visit('/fancy-flux/action');

        // First increment to 1, then decrement back to 0
        $page->click('Increment (0)')
            ->waitForText('Increment (1)')
            ->click('Decrement')
            ->waitForText('Increment (0)')
            ->assertSee('Increment (0)');
    });

    it('resets counter when reset button is clicked', function () {
        $page = visit('/fancy-flux/action');

        // Increment twice, then reset
        $page->click('Increment (0)')
            ->waitForText('Increment (1)')
            ->click('Increment (1)')
            ->waitForText('Increment (2)')
            ->click('Reset')
            ->waitForText('Increment (0)')
            ->assertSee('Increment (0)');
    });

    it('toggles toolbar formatting buttons', function () {
        $page = visit('/fancy-flux/action');

        // Bold starts as active, click to toggle off
        // The button should still be visible after clicking
        $page->assertSee('Text Formatting Toolbar')
            ->click('[data-testid="toolbar-actions"] button:first-child');

        // Wait for Livewire to update
        $page->pause(500);

        // Page should still have the toolbar
        $page->assertSee('Text Formatting Toolbar');
    });
});

describe('action component chat example', function () {
    it('renders chat action bar example', function () {
        $page = visit('/fancy-flux/action');

        // Chat actions should be visible
        $page->assertSee('Chat Action Bar')
            ->assertSee('Send')
            ->assertSee('Attach')
            ->assertSee('Emoji')
            ->assertSee('Voice');
    });
});
