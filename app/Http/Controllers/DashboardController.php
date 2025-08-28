<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // method untuk menampilkan dashboard
public function index()
{
    $totalKereta = \App\Models\Train::count();
    $totalStasiun = \App\Models\Station::count();
    $totalUser = \App\Models\User::count();
    $daftarKereta = \App\Models\Train::withCount('carriages')->get();

    return view('dashboard', compact('totalKereta', 'totalStasiun', 'totalUser', 'daftarKereta'));
}


}
