<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BarangApiController extends Controller
{
    /**
     * Get all barangs
     */
    public function index()
    {
        $data = Barang::all();

        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }

    /**
     * Store Barang (Create)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_pengurus'    => 'required|exists:penguruses,id_pengurus',
            'nama_barang'    => 'required|string|max:50',
            'jumlah_barang'  => 'required|integer|min:1',
            'expired_barang' => 'required|date|after:today',
        ], [
            'expired_barang.after' => 'Tanggal expired harus lebih dari hari ini.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors()
            ], 422);
        }

        $barang = Barang::create($validator->validated());

        return response()->json([
            'status' => 201,
            'message' => 'Barang berhasil ditambahkan',
            'data' => $barang
        ], 201);
    }

    /**
     * Show detail barang
     */
    public function show($id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json([
                'status' => 404,
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'data' => $barang
        ]);
    }

    /**
     * Update Barang
     */
    public function update(Request $request, $id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json([
                'status' => 404,
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'id_pengurus'    => 'required|exists:penguruses,id_pengurus',
            'nama_barang'    => 'required|string|max:50',
            'jumlah_barang'  => 'required|integer|min:1',
            'expired_barang' => 'required|date|after:today',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors()
            ], 422);
        }

        $barang->update($validator->validated());

        return response()->json([
            'status' => 200,
            'message' => 'Barang berhasil diupdate',
            'data' => $barang
        ]);
    }

    /**
     * Delete Barang
     */
    public function destroy($id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json([
                'status' => 404,
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }

        $barang->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Barang berhasil dihapus'
        ]);
    }
}
