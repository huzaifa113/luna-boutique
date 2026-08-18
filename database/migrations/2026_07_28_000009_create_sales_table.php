<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('sales')) {
            return;
        }

        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('walk_in_name')->nullable();
            $table->string('walk_in_phone')->nullable();
            $table->string('invoice_number')->unique();
            $table->date('sale_date')->index();
            $table->string('status')->default('draft'); // draft | completed | cancelled
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('shortage_adjustment', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('delivery_charges', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->decimal('shortage_cost', 12, 2)->default(0);
            $table->string('payment_status')->default('unpaid'); // unpaid | partial | paid
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
