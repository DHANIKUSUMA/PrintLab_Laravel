<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';
    protected $primaryKey = 'id_pesanan';

    protected $fillable = [
        'id_user',
        'id_jenis_kertas',
        'jumlah_lembar',
        'total_biaya',
        'status',
        'metode_pembayaran',
        'bukti_pembayaran',
        'kode_order',
    ];

    // Relasi: Pesanan milik satu user
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Relasi: Pesanan memiliki satu jenis kertas
    public function jenisKertas()
    {
        return $this->belongsTo(JenisKertas::class, 'id_jenis_kertas', 'id_jenis_kertas');
    }
}

