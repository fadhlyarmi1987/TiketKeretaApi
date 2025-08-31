<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Station;
use Illuminate\Http\Request;

class StationController extends Controller
{
    // ambil semua stasiun
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Station::all()
        ]);
    }

    // ambil stasiun berdasarkan id
    public function show($id)
    {
        $station = Station::find($id);

        if (!$station) {
            return response()->json([
                'success' => false,
                'message' => 'Station not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $station
        ]);
    }
}
