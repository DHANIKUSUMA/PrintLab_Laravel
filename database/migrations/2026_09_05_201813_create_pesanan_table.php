<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id('id_pesanan');
            
            // Foreign Key ke tabel users
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
            
            // Foreign Key ke tabel jenis_kertas
            $table->unsignedBigInteger('id_jenis_kertas');
            $table->foreign('id_jenis_kertas')->references('id_jenis_kertas')->on('jenis_kertas')->onDelete('cascade');
            
            $table->integer('jumlah_lembar');
            $table->decimal('total_biaya', 12, 2);
            $table->enum('status', ['menunggu', 'ditolak', 'disetujui'])->default('menunggu');
            $table->string('metode_pembayaran')->nullable();
            $table->string('bukti_pembayaran')->nullable();
            $table->string('kode_order')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
