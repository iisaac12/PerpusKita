<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjam;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with stats.
     */
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            // Admin Stats: Global
            $stats = [
                'total_buku' => Buku::count(),
                'total_kategori' => Kategori::count(),
                'total_peminjam' => Peminjam::where('role', 'peminjam')->count(),
                'peminjaman_aktif' => Peminjaman::where('status', 'aktif')->count(),
            ];
        } else {
            // User Stats: Personal
            $stats = [
                'buku_dipinjam' => Peminjaman::where('id_peminjam', $user->id_peminjam)->where('status', 'aktif')->count(),
                'menunggu_verifikasi' => Peminjaman::where('id_peminjam', $user->id_peminjam)->where('status', 'menunggu')->count(),
                'total_history' => Peminjaman::where('id_peminjam', $user->id_peminjam)->whereIn('status', ['selesai', 'dibatalkan'])->count(),
            ];
        }

        $recent_books = Buku::with('kategori')->latest()->take(5)->get();

        return view('dashboard', compact('stats', 'recent_books'));
    }
}
