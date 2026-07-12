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
        Schema::table('donations', function (Blueprint $table) {
            $table->string('payment_gateway')->nullable()->after('status'); // 'senangpay' | 'manual'
            $table->string('gateway_transaction_id')->nullable()->after('payment_gateway');
            $table->string('gateway_status_id')->nullable()->after('gateway_transaction_id');
            $table->string('receipt_image')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn(['payment_gateway', 'gateway_transaction_id', 'gateway_status_id']);
            $table->string('receipt_image')->nullable(false)->change();
        });
    }
};
