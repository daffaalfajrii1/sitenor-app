@props([
    'index' => 0,
    'paginator' => null,
])

<td {{ $attributes->merge(['class' => 'sitenor-table-no text-muted text-center']) }}>
    @if ($paginator && method_exists($paginator, 'firstItem') && $paginator->firstItem() !== null)
        {{ $paginator->firstItem() + $index }}
    @else
        {{ $index + 1 }}
    @endif
</td>
