# Carousel Component

A flexible carousel/slideshow component with multiple variants and usage patterns.

## Quick Start

```blade
@php
$slides = [
    ['name' => 'slide1', 'label' => 'First Slide', 'src' => '/images/slide1.jpg'],
    ['name' => 'slide2', 'label' => 'Second Slide', 'src' => '/images/slide2.jpg'],
];
@endphp

<flux:carousel :data="$slides" autoplay />
```

## Variants

### Directional (Default)

Navigation with prev/next arrows, supports autoplay:

```blade
<flux:carousel :data="$slides" autoplay />
```

### Wizard

Step-based navigation with numbered indicators, perfect for multi-step forms:

```blade
<flux:carousel :data="$steps" variant="wizard" :loop="false" />
```

### Thumbnail

Image-based navigation with preview thumbnails:

```blade
<flux:carousel :data="$slides" variant="thumbnail" />
```

## Usage Patterns

### Data-Driven Carousel

The simplest way to create a carousel - pass an array of slides via the `data` prop:

```blade
@php
$slides = [
    [
        'name' => 'mountains',
        'label' => 'Explore Nature',
        'description' => 'Discover breathtaking mountain views.',
        'src' => '/images/mountains.jpg'
    ],
    [
        'name' => 'city',
        'label' => 'Urban Adventure',
        'description' => 'Experience the vibrant city life.',
        'src' => '/images/city.jpg'
    ],
];
@endphp

{{-- One line! All panels, controls, and steps auto-generated --}}
<flux:carousel :data="$slides" autoplay />
```

**Slide Data Structure:**

| Key | Required | Description |
|-----|----------|-------------|
| `name` | Yes | Unique identifier for the slide |
| `label` | No | Display title for the slide |
| `description` | No | Subtitle or description text |
| `src` | No | Image URL for image-based slides |
| `thumbnail` | No | Separate thumbnail URL (defaults to `src`) |

### Slot-Based Carousel

For full control over content, use the slot-based approach:

```blade
<flux:carousel>
    <flux:carousel.panels>
        <flux:carousel.step.item name="first" label="First Slide" />
        <flux:carousel.step.item name="second" label="Second Slide" />
        <flux:carousel.step.item name="third" label="Third Slide" />
    </flux:carousel.panels>
    
    <flux:carousel.controls />
    
    <flux:carousel.steps>
        <flux:carousel.step name="first" />
        <flux:carousel.step name="second" />
        <flux:carousel.step name="third" />
    </flux:carousel.steps>
</flux:carousel>
```

## Wizard Variant

Create step-by-step wizards with numbered indicators:

```blade
@php
$steps = [
    ['name' => 'account', 'label' => 'Account'],
    ['name' => 'profile', 'label' => 'Profile'],
    ['name' => 'review', 'label' => 'Review'],
];
@endphp

<flux:carousel :data="$steps" variant="wizard" :loop="false" />
```

### Wizard with Form Submission

Use `wire:submit` to handle form submission when the user clicks "Finish":

**Shorthand Syntax:**

```blade
<flux:carousel 
    :data="$steps" 
    variant="wizard" 
    :loop="false" 
    wire:submit="submitWizard" 
/>
```

**Slot-Based Syntax with Custom Content:**

```blade
<flux:carousel variant="wizard" :loop="false" name="wizard-demo">
    <flux:carousel.steps>
        <flux:carousel.step name="account" label="Account" />
        <flux:carousel.step name="profile" label="Profile" />
        <flux:carousel.step name="review" label="Review" />
    </flux:carousel.steps>
    
    <flux:carousel.panels>
        <flux:carousel.step.item name="account">
            <div class="p-6">
                <flux:heading size="md">Create Your Account</flux:heading>
                <flux:text class="mt-2 mb-4">Enter your email and password.</flux:text>
                <div class="space-y-4 max-w-sm">
                    <flux:input label="Email" type="email" wire:model.blur="email" />
                    <flux:input label="Password" type="password" wire:model.blur="password" />
                </div>
            </div>
        </flux:carousel.step.item>
        
        <flux:carousel.step.item name="profile">
            <div class="p-6">
                <flux:heading size="md">Complete Your Profile</flux:heading>
                <div class="space-y-4 max-w-sm">
                    <flux:input label="Full Name" wire:model.blur="fullName" />
                    <flux:textarea label="Bio" wire:model.blur="bio" />
                </div>
            </div>
        </flux:carousel.step.item>
        
        <flux:carousel.step.item name="review">
            <div class="p-6">
                <flux:heading size="md">Review & Confirm</flux:heading>
                {{-- Display collected data --}}
            </div>
        </flux:carousel.step.item>
    </flux:carousel.panels>
    
    {{-- wire:submit calls submitWizard() when Complete is clicked --}}
    <flux:carousel.controls finishLabel="Complete" wire:submit="submitWizard" />
</flux:carousel>
```

**Livewire Component:**

```php
class WizardDemo extends Component
{
    public string $email = '';
    public string $password = '';
    public string $fullName = '';
    public string $bio = '';
    public bool $showSuccessModal = false;
    
    public function submitWizard(): void
    {
        $this->validate();
        // Save data...
        $this->showSuccessModal = true;
    }
}
```

## Custom Content

Create slides with custom HTML content:

```blade
<flux:carousel :autoplay="false">
    <flux:carousel.panels>
        <flux:carousel.step.item name="welcome">
            <div class="flex items-center justify-center h-64 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl text-white">
                <div class="text-center">
                    <flux:heading size="lg" class="text-white!">Welcome</flux:heading>
                    <flux:text class="text-blue-100 mt-2">This is the first slide.</flux:text>
                </div>
            </div>
        </flux:carousel.step.item>
        
        <flux:carousel.step.item name="features">
            <div class="flex items-center justify-center h-64 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl text-white">
                <div class="text-center">
                    <flux:heading size="lg" class="text-white!">Features</flux:heading>
                    <flux:text class="text-purple-100 mt-2">Discover amazing features.</flux:text>
                </div>
            </div>
        </flux:carousel.step.item>
    </flux:carousel.panels>
    
    <flux:carousel.controls />
    
    <flux:carousel.steps>
        <flux:carousel.step name="welcome" />
        <flux:carousel.step name="features" />
    </flux:carousel.steps>
</flux:carousel>
```

## Dynamic Slides

Add or remove slides dynamically without resetting carousel position:

**Livewire Component:**

```php
use FancyFlux\Concerns\InteractsWithCarousel;

class DynamicCarouselDemo extends Component
{
    use InteractsWithCarousel;
    
    public array $slides = [
        ['name' => 'slide-1', 'label' => 'Welcome', 'description' => 'First slide', 'color' => 'blue'],
        ['name' => 'slide-2', 'label' => 'Features', 'description' => 'Second slide', 'color' => 'purple'],
    ];
    
    public function appendSlide(): void
    {
        $count = count($this->slides) + 1;
        $this->slides[] = [
            'name' => 'slide-' . $count,
            'label' => 'Slide ' . $count,
            'description' => 'Dynamically added slide',
            'color' => 'green',
        ];
        
        // Refresh the carousel to recognize new slides
        $this->carousel('dynamic-carousel')->refresh();
    }
    
    public function removeLastSlide(): void
    {
        if (count($this->slides) > 1) {
            array_pop($this->slides);
            $this->carousel('dynamic-carousel')->refresh();
        }
    }
}
```

**Blade Template:**

```blade
<flux:carousel name="dynamic-carousel" :autoplay="false" :loop="true">
    <flux:carousel.panels>
        @foreach($slides as $slide)
            <flux:carousel.step.item 
                :name="$slide['name']" 
                :label="$slide['label']" 
                wire:key="slide-{{ $slide['name'] }}"
            >
                {{-- Custom slide content --}}
            </flux:carousel.step.item>
        @endforeach
    </flux:carousel.panels>
    
    <flux:carousel.controls position="overlay" />
    
    <flux:carousel.steps>
        @foreach($slides as $slide)
            <flux:carousel.step :name="$slide['name']" wire:key="step-{{ $slide['name'] }}" />
        @endforeach
    </flux:carousel.steps>
</flux:carousel>

<flux:button wire:click="appendSlide">Add Slide</flux:button>
<flux:button wire:click="removeLastSlide">Remove Last</flux:button>
```

## Programmatic Navigation

### From Livewire

Use the `InteractsWithCarousel` trait:

```php
use FancyFlux\Concerns\InteractsWithCarousel;

class MyComponent extends Component
{
    use InteractsWithCarousel;
    
    public function navigateToStep(string $stepName): void
    {
        $this->carousel('my-carousel')->goTo($stepName);
    }
    
    public function refreshAndNavigate(string $stepName): void
    {
        $this->carousel('my-carousel')->refreshAndGoTo($stepName);
    }
}
```

### From Alpine.js

Use `Flux.carousel('name')` helper for programmatic control:

```blade
<div x-data="{ 
    get carousel() { return Flux.carousel('my-carousel'); }
}">
    <flux:button x-on:click="carousel.prev()" x-bind:disabled="!carousel.canGoPrev()">
        Previous
    </flux:button>
    
    <flux:button x-on:click="carousel.next()" x-bind:disabled="!carousel.canGoNext()">
        Next
    </flux:button>
    
    <flux:button x-on:click="carousel.goTo('specific-slide')">
        Go to Specific Slide
    </flux:button>
</div>
```

### Via JavaScript Events

Dispatch events to control carousels:

```javascript
// Navigate to next slide
window.dispatchEvent(new CustomEvent('carousel-next', { 
    detail: { id: 'my-carousel' } 
}));

// Navigate to previous slide
window.dispatchEvent(new CustomEvent('carousel-prev', { 
    detail: { id: 'my-carousel' } 
}));

// Go to specific slide by name
window.dispatchEvent(new CustomEvent('carousel-goto', { 
    detail: { id: 'my-carousel', name: 'slide-name' } 
}));

// Refresh carousel (after dynamic changes)
window.dispatchEvent(new CustomEvent('carousel-refresh', { 
    detail: { id: 'my-carousel' } 
}));
```

## Nested Carousels

Nested carousels are fully supported and operate independently. Each carousel maintains its own state and controls.

### Basic Nested Carousel

```blade
<flux:carousel variant="wizard" :loop="false" name="parent-wizard">
    <flux:carousel.steps>
        <flux:carousel.step name="step1" label="Step 1" />
        <flux:carousel.step name="step2" label="Step 2" />
    </flux:carousel.steps>
    
    <flux:carousel.panels>
        <flux:carousel.step.item name="step1">
            <div class="p-6">Parent step 1 content</div>
        </flux:carousel.step.item>
        
        <flux:carousel.step.item name="step2">
            {{-- Nested wizard inside step 2 --}}
            <flux:carousel variant="wizard" :loop="false" name="nested-wizard" parentCarousel="parent-wizard">
                <flux:carousel.steps>
                    <flux:carousel.step name="nested1" label="Nested 1" />
                    <flux:carousel.step name="nested2" label="Nested 2" />
                </flux:carousel.steps>
                
                <flux:carousel.panels>
                    <flux:carousel.step.item name="nested1">
                        <div class="p-4">First nested step content.</div>
                    </flux:carousel.step.item>
                    
                    <flux:carousel.step.item name="nested2">
                        <div class="p-4">Second nested step content.</div>
                    </flux:carousel.step.item>
                </flux:carousel.panels>
                
                <flux:carousel.controls />
            </flux:carousel>
        </flux:carousel.step.item>
    </flux:carousel.panels>
    
    <flux:carousel.controls />
</flux:carousel>
```

### Key Behaviors

- **Independence:** Nested carousels do NOT inherit properties from parent carousels
- **Isolation:** Controls only affect the carousel they belong to
- **Parent Advancement:** On the final step of a nested wizard, the Next button can advance the parent wizard using the `parentCarousel` prop

### Nested Wizard with Parent Advancement

```blade
{{-- Nested wizard with parentCarousel prop --}}
<flux:carousel 
    variant="wizard" 
    :loop="false" 
    name="nested-wizard" 
    parentCarousel="parent-wizard"
>
    {{-- ... nested steps ... --}}
    
    {{-- On final step, Next button will advance parent wizard --}}
    <flux:carousel.controls />
</flux:carousel>
```

**Using wire:submit with Parent Advancement:**

```php
use FancyFlux\Concerns\InteractsWithCarousel;

class WizardDemo extends Component
{
    use InteractsWithCarousel;
    
    public function completeNestedWizard(): void
    {
        // Perform validation, save data, etc.
        $this->validate();
        
        // Advance the parent wizard
        $this->carousel('parent-wizard')->next();
    }
}
```

## Control Styles

The `flux:carousel.controls` component supports multiple display styles:

### Dots (Default)

Simple dot indicators showing current position. Clicking a dot navigates to that slide:

```blade
<flux:carousel.controls />
{{-- or explicitly: --}}
<flux:carousel.controls style="dots" />
```

### Arrows

Prev/next arrow buttons with dot indicators:

```blade
<flux:carousel.controls style="arrows" />
<flux:carousel.controls style="arrows" position="overlay" />
```

### Buttons

Text-based Back/Next buttons (default for wizard variant):

```blade
<flux:carousel.controls style="buttons" />
<flux:carousel.controls style="buttons" prevLabel="Go Back" nextLabel="Continue" />
```

### Minimal

Just dots, no navigation buttons:

```blade
<flux:carousel.controls style="minimal" />
```

### Control Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `style` | string | auto | `'dots'`, `'arrows'`, `'buttons'`, `'minimal'` |
| `position` | string | auto | `'bottom'`, `'overlay'`, `'sides'` |
| `showDots` | bool | auto | Show/hide dot indicators |
| `showPrev` | bool | `true` | Show previous button |
| `showNext` | bool | `true` | Show next button |
| `prevLabel` | string | 'Back' | Previous button text (buttons style) |
| `nextLabel` | string | 'Next' | Next button text (buttons style) |
| `finishLabel` | string | 'Finish' | Final step button text (wizard with wire:submit) |
| `flush` | bool | `false` | Remove default margins (for custom containers) |

## Props Reference

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `data` | array | `[]` | Array of slide data for data-driven mode |
| `variant` | string | `'directional'` | `'directional'`, `'wizard'`, or `'thumbnail'` |
| `name` | string | auto | Unique identifier for programmatic control |
| `autoplay` | bool | `false` | Enable auto-advancing slides |
| `interval` | int | `5000` | Milliseconds between slides |
| `loop` | bool | `true` | Loop back to start after last slide |
| `headless` | bool | `false` | Hide step indicators (wizard variant) |
| `wire:submit` | string | `null` | Livewire method to call on wizard finish |
| `parentCarousel` | string | `null` | Parent carousel ID/name (for nested carousels) |

## Examples

See the [demos folder](../demos/) for complete working examples:
- [Basic Carousel](../demos/basic-carousel/)
- [Wizard Form](../demos/wizard-form/)
- [Nested Carousel](../demos/nested-carousel/)
- [Dynamic Carousel](../demos/dynamic-carousel/)
