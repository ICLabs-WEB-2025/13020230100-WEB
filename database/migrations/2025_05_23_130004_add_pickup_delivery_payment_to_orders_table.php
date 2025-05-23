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
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('pickup_service', ['yes', 'no'])->default('no');
            $table->enum('delivery_service', ['yes', 'no'])->default('no');
            $table->enum('payment_method', ['online', 'cash'])->default('cash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['pickup_service', 'delivery_service', 'payment_method']);
        });
    }
};
