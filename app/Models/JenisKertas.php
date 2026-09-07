<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisKertas extends Model
{
    use HasFactory;

    protected $table = 'jenis_kertas';
    protected $primaryKey = 'id_jenis_kertas';

    protected $fillable = [
        'nama_kertas',
        'harga',
    ];

    // Relasi: Satu jenis kertas bisa digunakan di banyak pesanan
    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'id_jenis_kertas', 'id_jenis_kertas');
    }
}
