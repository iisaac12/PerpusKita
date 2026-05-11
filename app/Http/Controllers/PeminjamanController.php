<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of borrowings.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Peminjaman::with(['peminjam', 'buku']);
        
        if ($user->isAdmin()) {
            // Search (Nama Member, Judul Buku, ID)
            if ($request->filled('search')) {
                $query->where(function($q) use ($request) {
                    $q->whereHas('peminjam', function($sq) use ($request) {
                        $sq->where('nama', 'like', '%' . $request->search . '%');
                    })->orWhereHas('buku', function($sq) use ($request) {
                        $sq->where('judul', 'like', '%' . $request->search . '%');
                    })->orWhere('id_peminjaman', 'like', '%' . $request->search . '%');
                });
            }

            // Filter Status
            if ($request->filled('status') && $request->status != 'all') {
                $query->where('status', $request->status);
            }
        } else {
            $query->where('id_peminjam', $user->id_peminjam);
            // Default user view (hanya yang aktif/menunggu)
            if (!$request->has('status')) {
                $query->whereNotIn('status', ['selesai', 'dibatalkan']);
            } elseif ($request->status != 'all') {
                $query->where('status', $request->status);
            }
        }

        // Sort
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');
        $query->orderBy($sort, $order);

        $peminjaman = $query->paginate(10)->withQueryString();

        return view('peminjaman.index', compact('peminjaman'));
    }

    /**
     * Show form for new borrowing.
     */
    public function create(Request $request)
    {
        $selected_buku = null;
        if ($request->has('buku_id')) {
            $selected_buku = Buku::find($request->buku_id);
        }
        
        $buku_list = Buku::where('stok', '>', 0)->get();
        return view('peminjaman.create', compact('buku_list', 'selected_buku'));
    }

    /**
     * Store a new borrowing request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_buku' => 'required|exists:buku,id_buku',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'lama_peminjaman' => 'required|integer|min:1|max:3',
        ]);

        $buku = Buku::findOrFail($request->id_buku);
        
        if ($buku->stok <= 0) {
            return back()->with('error', 'Stok buku habis.');
        }

        DB::beginTransaction();
        try {
            $tanggal_pinjam = \Carbon\Carbon::parse($request->tanggal_pinjam);
            $tanggal_kembali = $tanggal_pinjam->copy()->addDays((int) $request->lama_peminjaman);

            $peminjaman = Peminjaman::create([
                'id_peminjam' => Auth::user()->id_peminjam,
                'tanggal_pinjam' => $tanggal_pinjam,
                'tanggal_kembali' => $tanggal_kembali,
                'lama_peminjaman' => $request->lama_peminjaman,
                'status' => 'menunggu',
            ]);

            DetailPeminjaman::create([
                'id_peminjaman' => $peminjaman->id_peminjaman,
                'id_buku' => $buku->id_buku,
                'jumlah' => 1,
            ]);

            DB::commit();
            return redirect()->route('success')->with('peminjaman_id', $peminjaman->id_peminjaman);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update status of borrowing (Admin Only).
     */
    public function updateStatus(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $old_status = $peminjaman->status;
        $new_status = $request->status;

        $request->validate([
            'status' => 'required|in:menunggu,aktif,selesai,dibatalkan',
        ]);

        DB::beginTransaction();
        try {
            // Logic for stock management
            if ($old_status != 'aktif' && $new_status == 'aktif') {
                // Deduct stock when borrowing starts
                foreach ($peminjaman->detailPeminjaman as $detail) {
                    $buku = $detail->buku;
                    if ($buku->stok < $detail->jumlah) {
                        throw new \Exception("Stok buku '{$buku->judul}' tidak mencukupi.");
                    }
                    $buku->decrement('stok', $detail->jumlah);
                }
            } elseif ($old_status == 'aktif' && ($new_status == 'selesai' || $new_status == 'dibatalkan')) {
                // Return stock when borrowing ends/cancelled
                foreach ($peminjaman->detailPeminjaman as $detail) {
                    $detail->buku->increment('stok', $detail->jumlah);
                }
            }

            $peminjaman->update(['status' => $new_status]);
            
            DB::commit();
            return back()->with('success', 'Status peminjaman berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
