<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_kertas', function (Blueprint $table) {
            $table->id('id_jenis_kertas');
            $table->string('nama_kertas');
            $table->decimal('harga', 10, 2); // Menggunakan decimal agar presisi untuk mata uang
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_kertas');
    }
};