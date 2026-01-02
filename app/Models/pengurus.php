<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Pengurus extends Model
{
    use  HasFactory,HasApiTokens;

    protected $table = 'penguruses';
    protected $primaryKey = 'id_pengurus';
    protected $fillable = ['nama_pengurus', 'email_pengurus', 'password_pengurus'];

    public function barang()
    {
        return $this->hasMany(barang::class, 'id_pengurus');
    }

    public function barangMasuk()
    {
        return $this->hasMany(Barang_Masuk::class, 'id_pengurus');
    }

    public function barangKeluar()
    {
        return $this->hasMany(barang_keluar::class, 'id_pengurus');
    }

}
