<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    protected $primaryKey = 'id_barang';
    protected $fillable = ['id_pengurus', 'nama_barang', 'jumlah', 'expired'];


    public function pengurus()
    {
        return $this->belongsTo(pengurus::class, 'id_pengurus');
    }

    public function barangMasuk()
    {
        return $this->hasMany(barang_masuk::class, 'id_barang');
    }

    public function barangKeluar()
    {
        return $this->hasMany(barang_masuk::class, 'id_barang');
    }
}
