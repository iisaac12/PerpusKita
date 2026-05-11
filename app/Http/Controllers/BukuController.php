<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Buku::with('categories');

        // Search (Judul, Pengarang, ID)
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('pengarang', 'like', '%' . $request->search . '%')
                  ->orWhere('id_buku', 'like', '%' . $request->search . '%');
            });
        }

        // Filter Category
        if ($request->filled('category') && $request->category != 'all') {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('kategori.id_kategori', $request->category);
            });
        }

        // Sort
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');
        
        $allowedSorts = ['judul', 'pengarang', 'stok', 'created_at'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $order);
        }

        $buku = $query->paginate(10)->withQueryString();
        $categories = Kategori::all();
        
        return view('admin.buku.index', compact('buku', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategori = Kategori::all();
        return view('admin.buku.create', compact('kategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'categories' => 'required|array|min:1', 
            'categories.*' => 'exists:kategori,id_kategori',
            'judul' => 'required|string|max:255',
            'pengarang' => 'required|string|max:150',
            'penerbit' => 'required|string|max:150',
            'deskripsi' => 'nullable|string',
            'stok' => 'required|integer|min:0',
            'cover_buku' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except('categories');
        // Set default id_kategori for old schema compatibility if needed, 
        // or just leave it empty if column is nullable/removed
        $data['id_kategori'] = $request->categories[0]; 

        if ($request->hasFile('cover_buku')) {
            $data['cover_buku'] = $request->file('cover_buku')->store('covers', 'public');
        }

        $buku = Buku::create($data);
        $buku->categories()->sync($request->categories);

        return redirect()->route('buku.index')
            ->with('success', 'Buku berhasil ditambahkan dengan ' . count($request->categories) . ' kategori.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Buku $buku)
    {
        $kategori = Kategori::all();
        return view('admin.buku.edit', compact('buku', 'kategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Buku $buku)
    {
        $request->validate([
            'categories' => 'required|array|min:1', 
            'categories.*' => 'exists:kategori,id_kategori',
            'judul' => 'required|string|max:255',
            'pengarang' => 'required|string|max:150',
            'penerbit' => 'required|string|max:150',
            'deskripsi' => 'nullable|string',
            'stok' => 'required|integer|min:0',
            'cover_buku' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except('categories');
        $data['id_kategori'] = $request->categories[0];

        if ($request->hasFile('cover_buku')) {
            // Delete old cover if exists
            if ($buku->cover_buku) {
                Storage::disk('public')->delete($buku->cover_buku);
            }
            $data['cover_buku'] = $request->file('cover_buku')->store('covers', 'public');
        }

        $buku->update($data);
        $buku->categories()->sync($request->categories);

        return redirect()->route('buku.index')
            ->with('success', 'Buku berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Buku $buku)
    {
        $buku->delete(); // Soft delete

        return redirect()->route('buku.index')
            ->with('success', 'Buku berhasil dihapus (soft delete).');
    }
}
