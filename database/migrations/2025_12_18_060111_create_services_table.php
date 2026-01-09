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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_code')->unique();
            $table->integer('queue_number');
            $table->string('customer_name');
            $table->string('phone');
            $table->string('device');
            $table->text('complaint');
            $table->text('damage_note')->nullable();
            $table->enum('status', ['MENUNGGU', 'DIPERIKSA', 'DIPERBAIKI', 'SELESAI', 'DIAMBIL'])->default('MENUNGGU');
            $table->enum('payment_method', ['CASH', 'QRIS'])->nullable();
            $table->enum('payment_status', ['BELUM_LUNAS', 'LUNAS'])->default('BELUM_LUNAS');
            $table->integer('total_price')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
