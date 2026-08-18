<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('sale_items')) {
            return;
        }

        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('product_name');
            $table->string('product_sku')->nullable();
            $table->foreignId('unit_id')->nullable()->constrained()->nullOnDelete();
            $table->string('unit_name');
            $table->decimal('factor', 12, 4);
            $table->decimal('quantity', 12, 3);
            $table->decimal('gross_base_quantity', 12, 3);
            $table->decimal('shortage_quantity', 12, 3)->default(0);
            $table->decimal('billed_base_quantity', 12, 3);
            $table->decimal('rate', 12, 2);
            $table->decimal('base_unit_rate', 12, 4);
            $table->decimal('base_unit_cost', 12, 4);
            $table->decimal('gross_amount', 12, 2);
            $table->decimal('shortage_amount', 12, 2);
            $table->decimal('net_amount', 12, 2);
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
