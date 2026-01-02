<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;

class ManageController extends Controller
{
    public function kurangBarang($idBarang, $jumlahBarang)
    {
        $barang = Barang::findorFail($idBarang);
        $barang = $barang->jumlah_barang - $jumlahBarang;
        Barang::where('id_barang', $idBarang)->update(['jumlah_barang' => $barang]);
    }

    public function cekBarang($idBarang)
    {
        $getBarang = Barang::findorFail($idBarang);
        return $getBarang->jumlah_barang;
    }
    public function tambahBarang($idBarang, $jumlahBarang)
    {
        $barang = Barang::findorFail($idBarang);
        $barang = $barang->jumlah_barang + $jumlahBarang;
        Barang::where('id_barang', $idBarang)->update(['jumlah_barang' => $barang]);
    }

    public function menipis()
    {
        $barang = Barang::where("jumlah_barang", "<", 10)->get();
        return response()->json(
            [
                "status" => 200,
                "message" => "data barang menipis",
                "data" => $barang
            ]
        );
    }
}
