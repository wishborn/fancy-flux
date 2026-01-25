# Fancy Table Component

Advanced data table with composable architecture, expandable row trays, and Carousel-powered pagination.

> **Note:** Named `<flux:fancy-table>` to avoid conflicts with the official Flux Pro table component.

## Why This Component?

The Fancy Table component provides a feature-rich data table for:
- Admin dashboards and data management
- Lists with expandable detail views
- Complex nested data with carousel-powered trays
- Sortable, searchable, paginated datasets

## Installation

The Fancy Table component is included with Fancy Flux. No additional installation required.

```blade
<flux:fancy-table :columns="$columns" :rows="$rows" />
```

## Basic Usage

### Data-Driven Mode

The simplest way to use the table - pass column definitions and row data:

```blade
@php
$columns = [
    ['name' => 'id', 'label' => 'ID', 'sortable' => true],
    ['name' => 'name', 'label' => 'Name', 'sortable' => true],
    ['name' => 'email', 'label' => 'Email'],
    ['name' => 'created_at', 'label' => 'Created', 'sortable' => true],
];

$rows = $users->map(fn($user) => [
    'id' => $user->id,
    'name' => $user->name,
    'email' => $user->email,
    'created_at' => $user->created_at->format('M d, Y'),
])->toArray();
@endphp

<flux:fancy-table :columns="$columns" :rows="$rows" />
```

### Composable Mode

For full control over rendering, use the composable slot-based approach:

```blade
<flux:fancy-table name="users">
    <flux:fancy-table.columns>
        <flux:fancy-table.column name="name" label="Name" sortable />
        <flux:fancy-table.column name="email" label="Email" />
        <flux:fancy-table.column name="status" label="Status" />
        <flux:fancy-table.column name="actions" label="" />
    </flux:fancy-table.columns>
    
    <flux:fancy-table.body :rows="$users">
        <flux:fancy-table.row :row="$row">
            <flux:fancy-table.cell>{{ $row->name }}</flux:fancy-table.cell>
            <flux:fancy-table.cell>{{ $row->email }}</flux:fancy-table.cell>
            <flux:fancy-table.cell>
                <flux:badge :color="$row->status === 'active' ? 'emerald' : 'zinc'">
                    {{ $row->status }}
                </flux:badge>
            </flux:fancy-table.cell>
            <flux:fancy-table.cell>
                <flux:action icon="pencil" size="sm" wire:click="edit({{ $row->id }})">
                    Edit
                </flux:action>
            </flux:fancy-table.cell>
        </flux:fancy-table.row>
    </flux:fancy-table.body>
</flux:fancy-table>
```

## Column Features

### Sortable Columns

```blade
<flux:fancy-table.column name="name" label="Name" sortable />
<flux:fancy-table.column name="created_at" label="Created" sortable />
```

### Resizable Columns

```blade
<flux:fancy-table.column name="description" label="Description" resizable />
```

### Reorderable Columns

```blade
<flux:fancy-table.column name="name" label="Name" reorderable />
```

### Column with Action Props

Columns support all Action component props for header styling:

```blade
<flux:fancy-table.column 
    name="priority" 
    label="Priority" 
    icon="flag"
    warn
/>
```

## Row Trays

Expandable detail areas for each row:

```blade
<flux:fancy-table name="orders">
    <flux:fancy-table.columns>
        <flux:fancy-table.column name="order_id" label="Order" />
        <flux:fancy-table.column name="total" label="Total" />
        <flux:fancy-table.column name="expand" label="" />
    </flux:fancy-table.columns>
    
    <flux:fancy-table.body :rows="$orders">
        <flux:fancy-table.row :row="$row">
            <flux:fancy-table.cell>#{{ $row->id }}</flux:fancy-table.cell>
            <flux:fancy-table.cell>${{ $row->total }}</flux:fancy-table.cell>
            <flux:fancy-table.cell>
                <flux:fancy-table.tray.trigger :row="$row" />
            </flux:fancy-table.cell>
            
            {{-- Expandable tray content --}}
            <flux:fancy-table.tray :row="$row">
                <div class="p-4 bg-zinc-50 dark:bg-zinc-800">
                    <h4 class="font-medium">Order Items</h4>
                    <ul class="mt-2 space-y-1">
                        @foreach($row->items as $item)
                            <li>{{ $item->name }} x {{ $item->quantity }}</li>
                        @endforeach
                    </ul>
                </div>
            </flux:fancy-table.tray>
        </flux:fancy-table.row>
    </flux:fancy-table.body>
</flux:fancy-table>
```

### Nested Carousels in Trays

Trays can contain carousels for complex nested content:

```blade
<flux:fancy-table.tray :row="$row">
    <flux:carousel variant="wizard" name="order-{{ $row->id }}-details">
        <flux:carousel.tabs>
            <flux:carousel.tab name="items" label="Items" />
            <flux:carousel.tab name="shipping" label="Shipping" />
            <flux:carousel.tab name="history" label="History" />
        </flux:carousel.tabs>
        <flux:carousel.panels>
            <flux:carousel.panel name="items">
                {{-- Items content --}}
            </flux:carousel.panel>
            <flux:carousel.panel name="shipping">
                {{-- Shipping content --}}
            </flux:carousel.panel>
            <flux:carousel.panel name="history">
                {{-- History content --}}
            </flux:carousel.panel>
        </flux:carousel.panels>
    </flux:carousel>
</flux:fancy-table.tray>
```

## Multi-Select

Enable row selection with checkboxes:

```blade
<flux:fancy-table name="users" wire:model="selectedUsers" selectable>
    {{-- ... --}}
</flux:fancy-table>
```

In your Livewire component:

```php
public array $selectedUsers = [];
```

## Search

Add search functionality:

```blade
<flux:fancy-table name="users" searchable>
    <flux:fancy-table.search placeholder="Search users..." />
    {{-- ... --}}
</flux:fancy-table>
```

### Deep Path Search

Search supports dot notation for nested data:

```blade
<flux:fancy-table.search 
    placeholder="Search..." 
    :paths="['name', 'email', 'profile.bio', 'address.city']" 
/>
```

## Pagination

Carousel-powered pagination:

```blade
<flux:fancy-table name="users" :per-page="10">
    {{-- columns and body --}}
    <flux:fancy-table.pagination />
</flux:fancy-table>
```

## Programmatic Control

Use the FANCY facade for programmatic table control:

```php
use FancyFlux\Facades\FANCY;

// Navigation
FANCY::table('users')->nextPage();
FANCY::table('users')->prevPage();
FANCY::table('users')->goToPage(3);

// Sorting
FANCY::table('users')->sortBy('name', 'asc');
FANCY::table('users')->sortBy('created_at', 'desc');

// Selection
FANCY::table('users')->selectAll();
FANCY::table('users')->deselectAll();
FANCY::table('users')->toggleSelection('row-5');

// Trays
FANCY::table('users')->toggleTray('row-1');
FANCY::table('users')->expandTray('row-1');
FANCY::table('users')->collapseTray('row-1');
FANCY::table('users')->collapseAllTrays();
```

### Using the Trait

Alternatively, use the `InteractsWithTable` trait:

```php
use FancyFlux\Concerns\InteractsWithTable;

class UserList extends Component
{
    use InteractsWithTable;
    
    public function refreshData(): void
    {
        $this->table('users')->goToPage(1);
        $this->table('users')->collapseAllTrays();
    }
}
```

## Props Reference

### Table Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `name` | string | auto | Unique table identifier |
| `columns` | array | `[]` | Column definitions (data-driven mode) |
| `rows` | array | `[]` | Row data (data-driven mode) |
| `selectable` | boolean | `false` | Enable row selection |
| `searchable` | boolean | `false` | Enable search |
| `per-page` | integer | `25` | Rows per page |

### Column Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `name` | string | required | Column identifier |
| `label` | string | `null` | Display label |
| `sortable` | boolean | `false` | Enable sorting |
| `resizable` | boolean | `false` | Enable resizing |
| `reorderable` | boolean | `false` | Enable reordering |
| `icon` | string | `null` | Header icon (Action prop) |
| `active` | boolean | `false` | Active state (Action prop) |
| `warn` | boolean | `false` | Warning state (Action prop) |

### Tray Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `row` | mixed | required | Row data reference |
| `terminator` | string | `null` | Content type hint: `_table`, `_carousel`, `_d3`, `_view` |

## Examples

### Admin User Table

```blade
<flux:fancy-table name="admin-users" selectable searchable :per-page="20">
    <flux:fancy-table.search placeholder="Search users..." />
    
    <flux:fancy-table.columns>
        <flux:fancy-table.column name="avatar" label="" />
        <flux:fancy-table.column name="name" label="Name" sortable />
        <flux:fancy-table.column name="email" label="Email" sortable />
        <flux:fancy-table.column name="role" label="Role" />
        <flux:fancy-table.column name="status" label="Status" />
        <flux:fancy-table.column name="actions" label="" />
    </flux:fancy-table.columns>
    
    <flux:fancy-table.body :rows="$users">
        <flux:fancy-table.row :row="$row">
            <flux:fancy-table.cell>
                <flux:avatar :src="$row->avatar" size="sm" />
            </flux:fancy-table.cell>
            <flux:fancy-table.cell>{{ $row->name }}</flux:fancy-table.cell>
            <flux:fancy-table.cell>{{ $row->email }}</flux:fancy-table.cell>
            <flux:fancy-table.cell>{{ $row->role }}</flux:fancy-table.cell>
            <flux:fancy-table.cell>
                <flux:badge :color="$row->active ? 'emerald' : 'zinc'">
                    {{ $row->active ? 'Active' : 'Inactive' }}
                </flux:badge>
            </flux:fancy-table.cell>
            <flux:fancy-table.cell class="flex gap-1">
                <flux:action icon="pencil" size="sm" wire:click="edit({{ $row->id }})" />
                <flux:action icon="trash" size="sm" warn wire:click="delete({{ $row->id }})" />
            </flux:fancy-table.cell>
        </flux:fancy-table.row>
    </flux:fancy-table.body>
    
    <flux:fancy-table.pagination />
</flux:fancy-table>
```

### Order Management with Trays

```blade
<flux:fancy-table name="orders">
    <flux:fancy-table.columns>
        <flux:fancy-table.column name="id" label="Order #" sortable />
        <flux:fancy-table.column name="customer" label="Customer" sortable />
        <flux:fancy-table.column name="total" label="Total" sortable />
        <flux:fancy-table.column name="status" label="Status" />
        <flux:fancy-table.column name="details" label="" />
    </flux:fancy-table.columns>
    
    <flux:fancy-table.body :rows="$orders">
        <flux:fancy-table.row :row="$row">
            <flux:fancy-table.cell>#{{ $row->id }}</flux:fancy-table.cell>
            <flux:fancy-table.cell>{{ $row->customer->name }}</flux:fancy-table.cell>
            <flux:fancy-table.cell>${{ number_format($row->total, 2) }}</flux:fancy-table.cell>
            <flux:fancy-table.cell>
                <flux:badge :color="match($row->status) {
                    'pending' => 'amber',
                    'processing' => 'blue',
                    'shipped' => 'violet',
                    'delivered' => 'emerald',
                    default => 'zinc'
                }">
                    {{ ucfirst($row->status) }}
                </flux:badge>
            </flux:fancy-table.cell>
            <flux:fancy-table.cell>
                <flux:fancy-table.tray.trigger :row="$row" icon="chevron-down" />
            </flux:fancy-table.cell>
            
            <flux:fancy-table.tray :row="$row">
                <div class="p-4 bg-zinc-50 dark:bg-zinc-900/50 space-y-4">
                    <div>
                        <h4 class="font-medium text-sm text-zinc-500">Items</h4>
                        <ul class="mt-2 divide-y divide-zinc-200 dark:divide-zinc-700">
                            @foreach($row->items as $item)
                                <li class="py-2 flex justify-between">
                                    <span>{{ $item->product->name }} × {{ $item->quantity }}</span>
                                    <span>${{ number_format($item->subtotal, 2) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    <div class="flex gap-2">
                        <flux:action icon="printer" size="sm">Print Invoice</flux:action>
                        <flux:action icon="truck" size="sm" color="blue">Track Shipment</flux:action>
                    </div>
                </div>
            </flux:fancy-table.tray>
        </flux:fancy-table.row>
    </flux:fancy-table.body>
    
    <flux:fancy-table.pagination />
</flux:fancy-table>
```
