<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(MassDataSeeder::class);
        $this->call(UnitSeeder::class);
        $this->call(PosDemoSeeder::class);
    }
}
