<?php

namespace App\Services;

use App\Models\Product;

class UnitConversionService
{
    /**
     * Resolve the conversion factor for a product/unit pair.
     *
     * The caller may pass an explicit factor (e.g. from a form), but it is only
     * trusted when positive; otherwise the authoritative value is read from the
     * product_units pivot so callers can safely omit it.
     *
     * @param  float|string|null  $providedFactor  Factor supplied by the caller, if any.
     */
    public function resolveFactor(Product $product, int $unitId, float|string|null $providedFactor = null): float
    {
        if ($providedFactor !== null && (float) $providedFactor > 0) {
            return (float) $providedFactor;
        }

        $factor = $product->productUnits()->where('unit_id', $unitId)->value('factor');

        return $factor !== null && (float) $factor > 0 ? (float) $factor : 1.0;
    }

    /**
     * Convert quantity in a given unit to base units.
     *
     * @param  float  $quantity  Quantity in the given unit.
     * @param  float  $factor  How many base units per 1 of this unit.
     * @return float Base quantity, rounded to 3 decimal places.
     */
    public function toBaseQuantity(float $quantity, float $factor): float
    {
        return round($quantity * $factor, 3);
    }

    /**
     * Convert base quantity back to the given unit.
     *
     * @param  float  $baseQuantity  Quantity in base units.
     * @param  float  $factor  How many base units per 1 of this unit.
     * @return float Unit quantity, rounded to 3 decimal places.
     */
    public function toUnitQuantity(float $baseQuantity, float $factor): float
    {
        if ($factor <= 0) {
            return 0.0;
        }

        return round($baseQuantity / $factor, 3);
    }

    /**
     * Derive the per-base-unit rate from a gross amount and a gross base quantity.
     *
     * @param  float  $grossAmount  Total monetary amount for the line.
     * @param  float  $grossBaseQuantity  Total base quantity for the line.
     * @return float Base unit rate, rounded to 4 decimal places.
     */
    public function baseUnitRate(float $grossAmount, float $grossBaseQuantity): float
    {
        if ($grossBaseQuantity <= 0) {
            return 0.0;
        }

        return round($grossAmount / $grossBaseQuantity, 4);
    }

    /**
     * Format a quantity number trimming trailing zeros.
     *
     * "194.000" → "194", "396.500" → "396.5", "0.000" → "0"
     */
    public function formatQuantity(float $quantity): string
    {
        $formatted = number_format($quantity, 3, '.', '');

        // Trim trailing zeros after decimal point
        if (str_contains($formatted, '.')) {
            $formatted = rtrim($formatted, '0');
            $formatted = rtrim($formatted, '.');
        }

        return $formatted;
    }
}
