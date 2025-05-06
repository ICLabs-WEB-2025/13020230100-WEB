<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        // Tambahkan kolom yang hilang di migration orders table
    Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('customer_id')->constrained();
    $table->foreignId('service_id')->constrained(); // Ganti dari service_type
    $table->decimal('weight', 8, 2); // Tambah kolom berat
    $table->decimal('total_price', 10, 2); // Ganti dari amount
    $table->string('status')->default('pending');
    $table->date('pickup_date'); // Tambah kolom
    $table->date('delivery_date'); // Tambah kolom
    $table->text('notes')->nullable(); // Tambah kolom
    $table->timestamps();
});
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}