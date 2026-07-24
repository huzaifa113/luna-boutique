<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('return_exchanges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // 'return' or 'exchange'
            $table->string('status')->default('pending'); // pending, items_received, approved, rejected
            $table->string('reason');
            $table->text('details')->nullable();
            $table->decimal('refund_amount', 10, 2)->nullable();
            $table->decimal('exchange_additional_payment', 10, 2)->nullable(); // if exchange > initial amount
            $table->string('exchange_payment_method')->nullable(); // card or cash for additional payment
            $table->string('exchange_payment_status')->nullable(); // pending, paid
            $table->foreignId('coupon_id')->nullable()->constrained()->nullOnDelete(); // auto-generated on return approval
            $table->text('admin_notes')->nullable();
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamp('admin_processed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('return_exchange_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_exchange_id')->constrained('return_exchanges')->cascadeOnDelete();
            $table->foreignId('order_item_id')->constrained('order_items')->cascadeOnDelete();
            $table->unsignedInteger('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->timestamps();
        });

        Schema::create('return_exchange_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_exchange_id')->constrained('return_exchanges')->cascadeOnDelete();
            $table->string('path');
            $table->string('original_name');
            $table->string('mime_type')->nullable();
            $table->unsignedInteger('size')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('return_exchange_attachments');
        Schema::dropIfExists('return_exchange_items');
        Schema::dropIfExists('return_exchanges');
    }
};