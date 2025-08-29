<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\TripStation; // ✅ tambahkan ini
use App\Models\Train;
use App\Models\Station;
use Illuminate\Http\Request;

class TripController extends Controller
{
    public function index()
    {
        $trips = Trip::with(['train', 'origin', 'destination'])->get();
        return view('admin.trips.index', compact('trips'));
    }

    public function searchStations(Request $request)
    {
        $search = strtolower($request->q);

        $stations = \App\Models\Station::whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
            ->orWhereRaw('LOWER(city) LIKE ?', ["%{$search}%"])
            ->orWhereRaw('LOWER(code) LIKE ?', ["%{$search}%"])
            ->limit(20)
            ->get(['id', 'name', 'city', 'code']);

        // Debug dulu
        // return response()->json($stations); 

        return response()->json(
            $stations->map(fn($s) => [
                'id' => $s->id,
                'text' => "{$s->name} - {$s->city} ({$s->code})"
            ])
        );
    }

    public function create()
    {
        $trains = Train::all();
        $stations = Station::all();
        $trains = Train::whereDoesntHave('trips')->get();
        return view('admin.trips.create', compact('trains', 'stations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'train_id' => 'required|exists:trains,id',
            'travel_date' => 'required|date',
            'stations' => 'required|array|min:2', // minimal asal & tujuan
            'stations.*.station_id' => 'required|exists:stations,id',
        ]);

        // ambil stasiun pertama & terakhir
        $firstStation = $request->stations[0];
        $lastStation = $request->stations[count($request->stations) - 1];

        // Buat trip baru
        // Buat trip baru
        $trip = Trip::create([
            'train_id'   => $request->train_id,
            'train_name' => Train::find($request->train_id)->name, // 🚀 simpan nama kereta
            'origin_station_id' => $firstStation['station_id'],
            'destination_station_id' => $lastStation['station_id'],
            'travel_date' => $request->travel_date,
            'departure_time' => $firstStation['departure_time'],
            'arrival_time' => $lastStation['arrival_time'],
        ]);


        // Simpan detail stasiun yang dilewati
        foreach ($request->stations as $order => $st) {
            TripStation::create([
                'trip_id' => $trip->id,
                'station_id' => $st['station_id'],
                'arrival_time' => $st['arrival_time'] ?? null,
                'departure_time' => $st['departure_time'] ?? null,
                'order' => $order + 1,
            ]);
        }

        return redirect()->route('trips.index')->with('success', 'Trip berhasil ditambahkan');
    }

    public function edit(Trip $trip)
    {
        $trains = Train::all();
        $stations = Station::all();
        $trip->load('tripStations.station');
        return view('admin.trips.edit', compact('trip', 'trains', 'stations'));
    }

    public function update(Request $request, Trip $trip)
    {
        $data = $request->validate([
            'train_id' => 'required|exists:trains,id',
            'travel_date' => 'required|date',
            'stations' => 'required|array|min:2',
            'stations.*.station_id' => 'required|exists:stations,id',
            'stations.*.arrival_time' => 'nullable|date_format:H:i',
            'stations.*.departure_time' => 'nullable|date_format:H:i',
        ]);

        $stations = $data['stations'];

        // ambil stasiun pertama & terakhir
        $firstStation = $stations[0];
        $lastStation  = $stations[count($stations) - 1];

        // update trip utama
        $trip->update([
            'train_id'              => $data['train_id'],
            'travel_date'           => $data['travel_date'],
            'origin_station_id'     => $firstStation['station_id'],
            'destination_station_id' => $lastStation['station_id'],
            'departure_time'        => $firstStation['departure_time'],
            'arrival_time'          => $lastStation['arrival_time'],
        ]);

        // hapus stasiun lama
        $trip->tripStations()->delete();

        // simpan ulang stasiun baru
        foreach ($stations as $i => $station) {
            $trip->tripStations()->create([
                'station_id'     => $station['station_id'],
                'arrival_time'   => $station['arrival_time'] ?? null,
                'departure_time' => $station['departure_time'] ?? null,
                'order'          => $i + 1, // mulai dari 1
            ]);
        }

        return redirect()->route('trips.index')->with('success', 'Trip berhasil diupdate.');
    }



    public function destroy(Trip $trip)
    {
        $trip->tripStations()->delete();
        $trip->delete();

        return redirect()->route('trips.index')->with('success', 'Trip berhasil dihapus');
    }
}
