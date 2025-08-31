<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TripStation;
use Illuminate\Http\Request;

class TripStationController extends Controller
{
    // GET /api/trip-stations
    public function index()
    {
        return TripStation::with(['trip.train', 'station'])->get();
    }

    // GET /api/trip-stations/{id}
    public function show($id)
    {
        return TripStation::with(['trip.train', 'station'])->findOrFail($id);
    }

    // POST /api/trip-stations
    public function store(Request $request)
    {
        $validated = $request->validate([
            'trip_id'        => 'required|exists:trips,id',
            'station_id'     => 'required|exists:stations,id',
            'arrival_time'   => 'nullable|date_format:H:i',
            'departure_time' => 'nullable|date_format:H:i',
            'station_order'  => 'required|integer',
        ]);

        $tripStation = TripStation::create($validated);

        // setelah create, reload relasi agar train_name & station_name ikut tampil
        return $tripStation->load(['trip.train', 'station']);
    }

    // PUT /api/trip-stations/{id}
    public function update(Request $request, $id)
    {
        $tripStation = TripStation::findOrFail($id);

        $validated = $request->validate([
            'trip_id'        => 'sometimes|exists:trips,id',
            'station_id'     => 'sometimes|exists:stations,id',
            'arrival_time'   => 'nullable|date_format:H:i',
            'departure_time' => 'nullable|date_format:H:i',
            'station_order'  => 'sometimes|integer',
        ]);

        $tripStation->update($validated);

        return $tripStation->load(['trip.train', 'station']);
    }

    // DELETE /api/trip-stations/{id}
    public function destroy($id)
    {
        $tripStation = TripStation::findOrFail($id);
        $tripStation->delete();

        return response()->json(['message' => 'deleted']);
    }
}
