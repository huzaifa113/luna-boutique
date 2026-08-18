<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('product_units', function (Blueprint $table) {
            $table->string('barcode')->nullable()->after('sale_rate');
            $table->foreignId('parent_product_unit_id')->nullable()->after('barcode')->constrained('product_units')->nullOnDelete();
            $table->decimal('contains_quantity', 12, 4)->nullable()->after('parent_product_unit_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_units', function (Blueprint $table) {
            $table->dropConstrainedForeignId('parent_product_unit_id');
            $table->dropColumn(['barcode', 'contains_quantity']);
        });
    }
};