<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\TripStation;
use App\Models\Train;
use App\Models\Station;
use Illuminate\Http\Request;

class TripStationController extends Controller
{
    public function index()
    {
        $trips = Trip::with(['train', 'origin', 'destination', 'tripStations.station'])->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar trip berhasil diambil',
            'data' => $trips
        ]);
    }

    public function show($id)
    {
        $trip = Trip::with(['train', 'origin', 'destination', 'tripStations.station'])->find($id);

        if (!$trip) {
            return response()->json([
                'success' => false,
                'message' => 'Trip tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail trip berhasil diambil',
            'data' => $trip
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'train_id' => 'required|exists:trains,id',
            'travel_date' => 'required|date',
            'stations' => 'required|array|min:2',
            'stations.*.station_id' => 'required|exists:stations,id',
        ]);

        $firstStation = $request->stations[0];
        $lastStation = $request->stations[count($request->stations) - 1];

        $trip = Trip::create([
            'train_id'   => $request->train_id,
            'train_name' => Train::find($request->train_id)->name,
            'origin_station_id' => $firstStation['station_id'],
            'destination_station_id' => $lastStation['station_id'],
            'travel_date' => $request->travel_date,
            'departure_time' => $firstStation['departure_time'],
            'arrival_time' => $lastStation['arrival_time'],
        ]);

        foreach ($request->stations as $order => $st) {
            TripStation::create([
                'trip_id' => $trip->id,
                'station_id' => $st['station_id'],
                'arrival_time' => $st['arrival_time'] ?? null,
                'departure_time' => $st['departure_time'] ?? null,
                'station_order' => $order + 1,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Trip berhasil dibuat',
            'data' => $trip->load(['tripStations.station', 'train', 'origin', 'destination'])
        ]);
    }

    public function update(Request $request, $id)
    {
        $trip = Trip::findOrFail($id);

        $request->validate([
            'train_id' => 'required|exists:trains,id',
            'origin_station_id' => 'required|exists:stations,id',
            'destination_station_id' => 'required|exists:stations,id',
            'travel_date' => 'date',
            'stations' => 'required|array|min:2',
            'stations.*.station_id' => 'required|exists:stations,id',
        ]);

        $trip->update([
            'train_id' => $request->train_id,
            'origin_station_id' => $request->origin_station_id,
            'destination_station_id' => $request->destination_station_id,
            'travel_date' => $request->travel_date,
            'departure_time' => $request->departure_time,
            'arrival_time' => $request->arrival_time,
            'status' => $request->status,
            'train_name' => $trip->train->name,
        ]);

        $trip->tripStations()->delete();

        foreach ($request->stations as $i => $stationData) {
            $station = Station::find($stationData['station_id']);
            $trip->tripStations()->create([
                'station_id' => $station->id,
                'station_name' => $station->name,
                'arrival_time' => $stationData['arrival_time'] ?? null,
                'departure_time' => $stationData['departure_time'] ?? null,
                'station_order' => $i + 1,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Trip berhasil diupdate',
            'data' => $trip->load(['tripStations.station', 'train', 'origin', 'destination'])
        ]);
    }

    public function destroy($id)
    {
        $trip = Trip::findOrFail($id);
        $trip->tripStations()->delete();
        $trip->delete();

        return response()->json([
            'success' => true,
            'message' => 'Trip berhasil dihapus'
        ]);
    }
}
