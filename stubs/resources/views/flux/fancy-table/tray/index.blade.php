@blaze

{{--
    Table Tray Component: Expandable row detail area.

    Supports multiple content types (unified terminators):
    - String: Plain text/HTML
    - _view: Blade view reference
    - _table: Nested table data
    - _carousel: Nested carousel data
    - _d3: D3 visualization config

    Why: Enables rich, nested content display within table rows,
    supporting complex data hierarchies and visualizations.
--}}

@props([
    'for' => null, // Row key this tray belongs to (required)
    'data' => null, // Tray content data (for data-driven mode)
    'colspan' => null, // Number of columns to span
])

@php
// Determine content type
$contentType = 'string';
$contentData = $data;

if (is_array($data)) {
    if (isset($data['_view'])) {
        $contentType = 'view';
        $contentData = $data;
    } elseif (isset($data['_table'])) {
        $contentType = 'table';
        $contentData = $data['_table'];
    } elseif (isset($data['_carousel'])) {
        $contentType = 'carousel';
        $contentData = $data['_carousel'];
    } elseif (isset($data['_d3'])) {
        $contentType = 'd3';
        $contentData = $data['_d3'];
    }
}

$classes = Flux::classes()
    ->add('bg-zinc-50 dark:bg-zinc-800/50')
    ;
@endphp

<tr
    x-show="isTrayExpanded('{{ $for }}')"
    x-collapse
    {{ $attributes->class($classes) }}
    data-flux-table-tray="{{ $for }}"
>
    <td @if ($colspan) colspan="{{ $colspan }}" @else colspan="100%" @endif class="px-4 py-4">
        @if ($data !== null)
            {{-- Data-driven content rendering --}}
            @switch ($contentType)
                @case('view')
                    @include($contentData['_view'], collect($contentData)->except('_view')->toArray())
                    @break

                @case('table')
                    <flux:fancy-table
                        :columns="$contentData['columns'] ?? []"
                        :rows="$contentData['rows'] ?? []"
                        :paginate="$contentData['paginate'] ?? null"
                        :name="$contentData['name'] ?? null"
                    />
                    @break

                @case('carousel')
                    <flux:carousel
                        :data="$contentData['data'] ?? $contentData['items'] ?? []"
                        :variant="$contentData['variant'] ?? 'directional'"
                        :name="$contentData['name'] ?? null"
                    />
                    @break

                @case('d3')
                    <flux:d3
                        :type="$contentData['type'] ?? 'force'"
                        :data="$contentData['data'] ?? []"
                        :width="$contentData['width'] ?? null"
                        :height="$contentData['height'] ?? 300"
                        :colors="$contentData['colors'] ?? null"
                        :tooltip="$contentData['tooltip'] ?? true"
                        :zoom="$contentData['zoom'] ?? false"
                        :animate="$contentData['animate'] ?? true"
                        :name="$contentData['name'] ?? null"
                    />
                    @break

                @default
                    {{-- String content --}}
                    {!! $data !!}
            @endswitch
        @else
            {{-- Slot-based content --}}
            {{ $slot }}
        @endif
    </td>
</tr>
