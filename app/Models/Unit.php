<?php

namespace App\Models;

use Database\Factories\UnitFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'code', 'type', 'is_active'])]
class Unit extends Model
{
    /** @use HasFactory<UnitFactory> */
    use HasFactory;

    const TYPE_WEIGHT = 'weight';

    const TYPE_VOLUME = 'volume';

    const TYPE_COUNT = 'count';

    const TYPES = [
        self::TYPE_WEIGHT,
        self::TYPE_VOLUME,
        self::TYPE_COUNT,
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
