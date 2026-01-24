@blaze

{{--
    Table Columns Container: Wrapper for column definitions in composable mode.

    Why: Provides a semantic container for column definitions that can be
    rendered as a thead row.
--}}

@props([])

<thead class="bg-zinc-50 dark:bg-zinc-800" {{ $attributes }}>
    <tr>
        {{ $slot }}
    </tr>
</thead>
