@php
    $childUnits = $node['children'] ?? [];
@endphp

<tr class="bg-white transition-colors hover:bg-gray-50 dark:bg-gray-900 dark:hover:bg-gray-800/40">
    <td class="whitespace-nowrap px-4 py-2.5 text-gray-500 dark:text-gray-400" style="padding-left: {{ 16 + ($level * 20) }}px">
        @if ($level > 0)
            <span class="mr-1.5 text-gray-400 dark:text-gray-500">└─</span>
        @endif
        Level {{ $level }}
    </td>
    <td class="whitespace-nowrap px-4 py-2.5 font-semibold text-gray-900 dark:text-gray-100">{{ $node['unit_name'] ?? 'N/A' }}</td>
    <td class="whitespace-nowrap px-4 py-2.5 font-mono text-xs text-gray-600 dark:text-gray-300">{{ $node['barcode'] ?? '—' }}</td>
    <td class="whitespace-nowrap px-4 py-2.5 text-gray-700 dark:text-gray-300">
        @if (!empty($node['contains_quantity']) && $node['contains_quantity'] > 0)
            {{ number_format((float) $node['contains_quantity'], 3) }}
            @if (!empty($childUnits))
                <span class="text-xs text-gray-400 dark:text-gray-500">/ {{ $childUnits[0]['unit_name'] ?? 'unit' }}</span>
            @endif
        @else
            <span class="text-gray-400 dark:text-gray-500">—</span>
        @endif
    </td>
    <td class="whitespace-nowrap px-4 py-2.5 font-medium text-gray-900 dark:text-gray-100">
        {{ config('pos.currency.symbol', 'Rs') }}{{ number_format((float) ($node['sale_rate'] ?? 0), 2) }}
    </td>
    <td class="whitespace-nowrap px-4 py-2.5 text-gray-700 dark:text-gray-300">
        {{ config('pos.currency.symbol', 'Rs') }}{{ number_format((float) ($node['purchase_rate'] ?? 0), 2) }}
    </td>
    <td class="whitespace-nowrap px-4 py-2.5 font-medium">
        <span class="text-sm {{ !empty($node['landed_cost']) && $node['landed_cost'] > 0 ? 'text-gray-900 dark:text-gray-100' : 'text-gray-400 dark:text-gray-500' }}">
            {{ config('pos.currency.symbol', 'Rs') }}{{ number_format((float) ($node['landed_cost'] ?? 0), 2) }}
        </span>
        @if (!empty($node['margin']))
            <span class="ml-1.5 text-xs {{ $node['margin'] >= 0 ? 'text-success-600' : 'text-danger-600' }}">
                ({{ $node['margin'] >= 0 ? '+' : '' }}{{ $node['margin'] }}%)
            </span>
        @endif
    </td>
    <td class="whitespace-nowrap px-4 py-2.5">
        @php
            $stock = (float) ($node['stock'] ?? 0);
        @endphp
        <span class="{{ $stock <= 0 ? 'text-danger-600' : 'text-gray-900 dark:text-gray-100' }}">
            {{ number_format($stock, 3) }}
        </span>
        <span class="text-xs text-gray-400 dark:text-gray-500">{{ $node['unit_name'] ?? '' }}</span>
    </td>
</tr>

@foreach ($childUnits as $childNode)
    @include('filament.pages.manage-inventory-unit-row', [
        'node' => $childNode,
        'level' => $level + 1,
    ])
@endforeach