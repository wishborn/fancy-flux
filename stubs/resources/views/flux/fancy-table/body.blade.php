@blaze

{{--
    Table Body Container: Wrapper for rows in composable mode.

    Why: Provides a semantic container for table rows that can include
    virtualization support.
--}}

@props([])

<tbody
    {{ $attributes->class(['divide-y divide-zinc-200 dark:divide-zinc-700 bg-white dark:bg-zinc-900']) }}
    data-table-body
>
    {{ $slot }}
</tbody>
