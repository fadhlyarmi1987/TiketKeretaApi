<?php

namespace App\Http\Controllers;

use App\Models\Station;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    // tampilkan halaman form booking
    public function index()
    {
        $stations = Station::all();
        return view('booking.index', compact('stations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'origin_id' => 'required|exists:stations,id',
            'destination_id' => 'required|exists:stations,id|different:origin_id',
        ]);

        $originId = $request->origin_id;
        $destinationId = $request->destination_id;
        $date = $request->departure_date;
        $time = $request->departure_time;

        // Cari trip yang melewati stasiun asal & tujuan
        $trips = \App\Models\Trip::whereHas('stations', function ($q) use ($originId) {
            $q->where('station_id', $originId);
        })
            ->whereHas('stations', function ($q) use ($destinationId) {
                $q->where('station_id', $destinationId);
            })

            ->with(['train', 'stations.station'])
            ->get();

        return view('booking.result', compact('trips', 'originId', 'destinationId', 'date'));
    }
}
