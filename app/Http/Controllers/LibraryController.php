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
        $query = Buku::with('categories');

        // Filter by search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', '%' . $search . '%')
                  ->orWhere('pengarang', 'like', '%' . $search . '%')
                  ->orWhere('id_buku', 'like', '%' . $search . '%');
            });
        }

        // Filter by category
        if ($request->has('category') && $request->category != 'all') {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('nama_kategori', $request->category);
            });
        }

        $buku = $query->latest()->paginate(12);
        $categories = Kategori::all();

        return view('search', compact('buku', 'categories'));
    }
}
