<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ManageController;
use App\Models\Barang;
use App\Models\barang_masuk;
use Illuminate\Http\Request;

class BarangMasukController extends Controller
{
    public function index()
    {
        $data = barang_masuk::with('barang', 'pengurus')->get();

        return response()->json([
            'status' => 200,
            'message' => 'data barang masuk',
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pengurus' => 'required|exists:penguruses,id_pengurus',
            'id_barang'   => 'required|exists:barangs,id_barang',
            'jumlah'      => 'required|numeric|min:1',
            'tanggal_masuk' => 'required|date'
        ]);

        // tambah stok barang
        $stok = new ManageController();
        $stok->tambahBarang($validated['id_barang'], $validated['jumlah']);

        // catat barang masuk
        $data = barang_masuk::create($validated);

        return response()->json([
            'status' => 201,
            'message' => 'barang masuk berhasil ditambahkan',
            'data' => $data
        ]);
    }

    public function show($id)
    {
        $data = barang_masuk::with('barang', 'pengurus')
            ->where('id', $id)
            ->first();

        if (!$data) {
            return response()->json([
                'status' => 404,
                'message' => 'data barang masuk tidak ditemukan'
            ]);
        }

        return response()->json([
            'status' => 200,
            'message' => 'detail barang masuk',
            'data' => $data
        ]);
    }

    public function update(Request $request, $id)
    {
        // ambil data lama
        $masuk = barang_masuk::where('id', $id)->first();

        if (!$masuk) {
            return response()->json([
                'status' => 404,
                'message' => 'data barang masuk tidak ditemukan'
            ]);
        }

        // validasi input
        $validated = $request->validate([
            'id_pengurus' => 'required|exists:penguruses,id_pengurus',
            'id_barang'   => 'required|exists:barangs,id_barang',
            'jumlah'      => 'required|numeric|min:1',
            'tanggal_masuk' => 'required|date'
        ]);

        $stok = new ManageController();

        // hitung selisih stok
        $jumlahLama = $masuk->jumlah;
        $jumlahBaru = $validated['jumlah'];

        if ($jumlahBaru > $jumlahLama) {
            // jumlah baru lebih besar → stok ditambah
            $selisih = $jumlahBaru - $jumlahLama;
            $stok->tambahBarang($validated['id_barang'], $selisih);

        } else if ($jumlahBaru < $jumlahLama) {
            // jumlah baru lebih kecil → stok dikurangi
            $selisih = $jumlahLama - $jumlahBaru;
            $stok->kurangBarang($validated['id_barang'], $selisih);
        }

        // update database
        $masuk->update([
            'id_pengurus' => $validated['id_pengurus'],
            'id_barang'   => $validated['id_barang'],
            'jumlah'      => $validated['jumlah'],
            'tanggal_masuk' => $validated['tanggal_masuk']
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'data barang masuk berhasil diupdate',
            'data' => $masuk
        ]);
    }

    public function destroy($id)
    {
        $masuk = barang_masuk::where('id', $id)->first();

        if (!$masuk) {
            return response()->json([
                'status' => 404,
                'message' => 'data barang masuk tidak ditemukan'
            ]);
        }

        $stok = new ManageController();
        
        // kembalikan stok barang (karena data masuk dihapus → stok berkurang)
        $stok->kurangBarang($masuk->id_barang, $masuk->jumlah);

        $masuk->delete();

        return response()->json([
            'status' => 200,
            'message' => 'data barang masuk berhasil dihapus'
        ]);
    }
}
