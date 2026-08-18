<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        Unit::firstOrCreate(['code' => 'pcs'], [
            'name' => 'Piece',
            'code' => 'pcs',
            'type' => Unit::TYPE_COUNT,
            'is_active' => true,
        ]);

        Unit::firstOrCreate(['code' => 'kg'], [
            'name' => 'Kilogram',
            'code' => 'kg',
            'type' => Unit::TYPE_WEIGHT,
            'is_active' => true,
        ]);

        Unit::firstOrCreate(['code' => 'g'], [
            'name' => 'Gram',
            'code' => 'g',
            'type' => Unit::TYPE_WEIGHT,
            'is_active' => true,
        ]);

        Unit::firstOrCreate(['code' => 'bag'], [
            'name' => 'Bag',
            'code' => 'bag',
            'type' => Unit::TYPE_COUNT,
            'is_active' => true,
        ]);

        Unit::firstOrCreate(['code' => 'carton'], [
            'name' => 'Carton',
            'code' => 'carton',
            'type' => Unit::TYPE_COUNT,
            'is_active' => true,
        ]);

        Unit::firstOrCreate(['code' => 'dozen'], [
            'name' => 'Dozen',
            'code' => 'dozen',
            'type' => Unit::TYPE_COUNT,
            'is_active' => true,
        ]);

        Unit::firstOrCreate(['code' => 'litre'], [
            'name' => 'Litre',
            'code' => 'litre',
            'type' => Unit::TYPE_VOLUME,
            'is_active' => true,
        ]);
    }
}
