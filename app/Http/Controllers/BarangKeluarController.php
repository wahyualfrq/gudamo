<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ManageController;
use App\Models\barang_keluar;
use App\Models\Pengurus;
use Illuminate\Http\Request;

class BarangKeluarController extends Controller
{
    public function index()
    {
        $data = barang_keluar::all();

        return response()->json([
            'data' => $data,
            'status' => 200,
            'message' => 'data barang keluar'
        ]);
    }

    public function store(Request $request)
    {
        // validasi input
        $validated = $request->validate([
            'id_pengurus' => 'required|exists:penguruses,id_pengurus',
            'id_barang'   => 'required|exists:barangs,id_barang',
            'jumlah'      => 'required|numeric|min:1',
            'tanggal_keluar' => 'required|date'
        ]);

        $barang = new ManageController();

        // cek stok barang
        $stok = $barang->cekBarang($validated['id_barang']);

        if ($stok < $validated['jumlah']) {
            return response()->json([
                'status' => 400,
                'message' => 'jumlah barang tidak mencukupi'
            ]);
        }

        // create barang keluar
        $data = barang_keluar::create($validated);

        // kurangi stok
        $barang->kurangBarang($validated['id_barang'], $validated['jumlah']);

        return response()->json([
            'data' => $data,
            'status' => 201,
            'message' => 'data barang keluar berhasil ditambahkan'
        ]);
    }

    public function show(Pengurus $pengurus)
    {
        $data = barang_keluar::where('id_pengurus', $pengurus->id_pengurus)->get();

        return response()->json([
            'data' => $data,
            'status' => 200,
            'message' => 'data barang keluar'
        ]);
    }

    public function update(Request $request, $id)
    {
        // validasi
        $validated = $request->validate([
            'id_pengurus' => 'required|exists:penguruses,id_pengurus',
            'id_barang'   => 'required|exists:barangs,id_barang',
            'jumlah'      => 'required|integer|min:1',
            'tanggal_keluar' => 'required|date',
        ]);

        // get data lama
        $dataLama = barang_keluar::where('id', $id)->first();

        if (!$dataLama) {
            return response()->json([
                'status' => 404,
                'message' => 'data barang keluar tidak ditemukan'
            ]);
        }

        $jumlahLama = $dataLama->jumlah;
        $jumlahBaru = $validated['jumlah'];

        $barang = new ManageController();

        // hitung selisih
        if ($jumlahBaru > $jumlahLama) {
            // jumlah baru lebih besar -> stok dikurangi
            $selisih = $jumlahBaru - $jumlahLama;
            $barang->kurangBarang($validated['id_barang'], $selisih);
        } else {
            // jumlah baru lebih kecil -> stok dikembalikan
            $selisih = $jumlahLama - $jumlahBaru;
            $barang->tambahBarang($validated['id_barang'], $selisih);
        }

        // update ke database
        $dataLama->update($validated);

        return response()->json([
            'status' => 200,
            'message' => 'data barang keluar berhasil diubah',
            'data' => $dataLama
        ]);
    }

    public function destroy(barang_keluar $barang_keluar)
    {
        $data = barang_keluar::find($barang_keluar->id);

        if (!$data) {
            return response()->json([
                'status' => 404,
                'message' => 'data barang keluar tidak ditemukan'
            ]);
        }

        $barang = new ManageController();

        // kembalikan stok
        $barang->tambahBarang($data->id_barang, $data->jumlah);

        // delete
        $data->delete();

        return response()->json([
            'status' => 200,
            'message' => 'data barang keluar berhasil dihapus'
        ]);
    }
}
