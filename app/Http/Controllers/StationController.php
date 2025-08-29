<?php

namespace App\Http\Controllers;

use App\Models\Station;
use Illuminate\Http\Request;

class StationController extends Controller
{
    public function index(Request $request)
    {
        $stations = Station::orderBy('code', 'asc')->get();
          $stations = Station::all(); 

        return view('admin.stasiun.index', compact('stations'));
    }

    public function create()
    {
        return view('admin.stasiun.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:stations,code|max:10',
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
        ]);

        Station::create($request->all());

        return redirect()->route('stasiun.index')->with('success', 'Stasiun berhasil ditambahkan');
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

    public function edit(Station $station)
    {
        return view('admin.stasiun.edit', compact('station'));
    }

    public function update(Request $request, Station $station)
    {
        $request->validate([
            'code' => 'required|max:10|unique:stations,code,' . $station->id,
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
        ]);

        $station->update($request->all());

        return redirect()->route('stasiun.index')->with('success', 'Stasiun berhasil diperbarui');
    }
    public function search(Request $request)
    {
        $query = $request->get('q');
        $stations = Station::where('name', 'like', "%$query%")
            ->orWhere('city', 'like', "%$query%")
            ->limit(20)
            ->get();

        return response()->json($stations);
    }


    public function destroy(Station $station)
    {
        $station->delete();
        return redirect()->route('stasiun.index')->with('success', 'Stasiun berhasil dihapus');
    }
}
