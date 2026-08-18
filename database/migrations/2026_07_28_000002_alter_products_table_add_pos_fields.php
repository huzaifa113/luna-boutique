<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('stock_quantity', 12, 3)->unsigned()->default(0)->change();
            $table->foreignId('base_unit_id')->nullable()->after('sku')->constrained('units')->nullOnDelete();
            $table->boolean('track_stock')->default(true)->after('stock_quantity');
            $table->decimal('low_stock_threshold', 12, 3)->nullable()->after('track_stock');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['base_unit_id', 'track_stock', 'low_stock_threshold']);
            $table->unsignedInteger('stock_quantity')->default(0)->change();
        });
    }
};
