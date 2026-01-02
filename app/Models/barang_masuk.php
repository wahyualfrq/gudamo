<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class barang_masuk extends Model
{
    use HAsFactory ;

    protected $table = 'barang_masuk';

    protected $fillable = ['id_barang', 'jumlah_barang', 'tanggal_masuk'];

    //
}
