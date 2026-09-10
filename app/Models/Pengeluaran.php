<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $table = 'pengeluarans';
    protected $primaryKey = 'id_pengeluaran'; // Sesuaikan jika pakai $table->id('id_pengeluaran')

    protected $fillable = [
        // 'id_user',
        'deskripsi',
        'kategori',
        'jumlah',
        'tanggal',
    ];

    // Opsional: Casting tanggal & jumlah
    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
    ];
}
