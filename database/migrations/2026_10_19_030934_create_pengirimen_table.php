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
        Schema::create('pengirimen', function (Blueprint $table) {
            $table->id();
            $table->string('metode')->default('Belum ditentukan');
            $table->string('alamat_tujuan')->nullable();
            $table->decimal('ongkir', 12, 2)->default(0);
            $table->string('status')->default('menunggu');
            $table->foreignId('transaksi_id')->constrained('transaksis')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengirimen');
    }
};
