<?php

namespace App\Http\Controllers;

use App\Models\Peminjam;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Display a listing of members (Admin Only).
     */
    public function index()
    {
        $members = Peminjam::where('role', 'peminjam')->latest()->paginate(10);
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
