<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display a summary of borrowings.
     */
    public function index(Request $request)
    {
        $query = Peminjaman::with(['peminjam', 'buku']);

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_pinjam', [$request->start_date, $request->end_date]);
        }

        // Filter by status
        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        $peminjaman = $query->latest()->get();

        // Statistics for the report
        $stats = [
            'total' => $peminjaman->count(),
            'aktif' => $peminjaman->where('status', 'aktif')->count(),
            'selesai' => $peminjaman->where('status', 'selesai')->count(),
            'dibatalkan' => $peminjaman->where('status', 'dibatalkan')->count(),
        ];

        return view('admin.report.index', compact('peminjaman', 'stats'));
    }
}
