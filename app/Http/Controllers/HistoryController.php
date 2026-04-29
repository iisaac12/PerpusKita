<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    /**
     * Display a listing of completed and cancelled borrowings.
     */
    public function index()
    {
        $user = Auth::user();
        
        $query = Peminjaman::with(['peminjam', 'buku'])
            ->whereIn('status', ['selesai', 'dibatalkan'])
            ->latest();

        if (!$user->isAdmin()) {
            $query->where('id_peminjam', $user->id_peminjam);
        }

        $history = $query->paginate(10);

        return view('history.index', compact('history'));
    }
}
