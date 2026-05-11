<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data buku beserta kategorinya
        $buku = Buku::with('categories')->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar data buku',
            'data'    => $buku
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Cari buku berdasarkan id_buku
        $buku = Buku::with('categories')->find($id);

        if ($buku) {
            return response()->json([
                'success' => true,
                'message' => 'Detail data buku',
                'data'    => $buku
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Buku tidak ditemukan',
        ], 404);
    }
}
