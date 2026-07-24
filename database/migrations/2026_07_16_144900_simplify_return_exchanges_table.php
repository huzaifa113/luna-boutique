<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('return_exchanges', function (Blueprint $table) {
            $table->dropColumn(['type', 'exchange_additional_payment', 'exchange_payment_method', 'exchange_payment_status']);
        });
    }

    public function down(): void
    {
        Schema::table('return_exchanges', function (Blueprint $table) {
            $table->string('type')->default('return')->after('order_id');
            $table->decimal('exchange_additional_payment', 10, 2)->nullable()->after('refund_amount');
            $table->string('exchange_payment_method')->nullable()->after('exchange_additional_payment');
            $table->string('exchange_payment_status')->nullable()->after('exchange_payment_method');
        });
    }
};