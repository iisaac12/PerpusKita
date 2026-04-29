<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class LibraryController extends Controller
{
    /**
     * Display the library catalog.
     */
    public function index(Request $request)
    {
        $query = Buku::with('kategori');

        // Filter by search
        if ($request->has('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('pengarang', 'like', '%' . $request->search . '%')
                  ->orWhere('id_buku', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->has('category') && $request->category != 'all') {
            $query->whereHas('kategori', function($q) use ($request) {
                $q->where('nama_kategori', $request->category);
            });
        }

        $buku = $query->latest()->paginate(12);
        $categories = Kategori::all();

        return view('search', compact('buku', 'categories'));
    }
}
