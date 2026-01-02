<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class barang_keluar extends Model
{

    use HasFactory;

    protected $table = 'barang_keluars';

    protected $fillable = [
        'id_pengurus',
        'id_barang',
        'jumlah_keluar',
        'tanggal_keluar',
    ];

    function pengurus()
    {
        return $this->belongsTo(pengurus::class);
    }
    //
}
