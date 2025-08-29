<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // method untuk menampilkan dashboard
    public function index(Request $request)
    {
        $totalKereta = \App\Models\Train::count();
        $totalStasiun = \App\Models\Station::count();
        $totalUser = \App\Models\User::count();

        $query = \App\Models\Train::withCount('carriages');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $daftarKereta = $query->get();
        
        return view('dashboard', compact('totalKereta', 'totalStasiun', 'totalUser', 'daftarKereta'));
    }
}
