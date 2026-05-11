<?php

namespace App\Http\Controllers;

use App\Models\Peminjam;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Display a listing of members (Admin Only).
     */
    public function index(Request $request)
    {
        $query = Peminjam::where('role', 'peminjam');

        // Search (Nama, Email, ID)
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('id_peminjam', 'like', '%' . $request->search . '%');
            });
        }

        // Sort
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');
        
        $allowedSorts = ['nama', 'email', 'created_at'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $order);
        }

        $members = $query->paginate(10)->withQueryString();
        return view('admin.member.index', compact('members'));
    }

    /**
     * Remove the specified member from storage.
     */
    public function destroy(Peminjam $member)
    {
        $member->delete();
        return back()->with('success', 'Member berhasil dihapus.');
    }
}
