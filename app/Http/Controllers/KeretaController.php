<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Train;

class KeretaController extends Controller
{
//=======INDEX=======
    public function index()
    {
        $kereta = Train::latest()->get();
        return view('admin.kereta.index', compact('kereta'));
    }

//==========CREATE==========
    public function create()
    {
        $stations = \App\Models\Station::all();

        return view('admin.kereta.create', compact('stations'));
    }
//==========SHOW==========
    public function show($id)
{
    $kereta = Train::with([
        'carriages.seats' => function ($q) {
            $q->orderByRaw("CAST(regexp_replace(seat_number, '[^0-9]', '', 'g') AS INTEGER) ASC")
              ->orderByRaw("regexp_replace(seat_number, '[^0-9]', '', 'g') ASC");
        },
        'trips.tripStations.station'
    ])->findOrFail($id);

    return view('admin.kereta.show', compact('kereta'));
}
//==========STORE==========
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:trains,code',
            'name' => 'required|string|max:255',
            'service_class' => 'required|string',
            'carriage_count' => 'required|integer|min:1|max:20',
        ]);

        // Simpan kereta
        $train = Train::create([
            'code' => $request->code,
            'name' => $request->name,
            'service_class' => $request->service_class,
            'carriage_count' => $request->carriage_count,
        ]);

        // Tentukan jumlah kursi per gerbong berdasarkan kelas
        $seatCount = 0;
        $seatLetters = [];

        if ($request->service_class === 'ECONOMY') {
            $seatCount = 161;
            $seatLetters = ['A', 'B', 'C', 'D', 'E']; // 5 seat per row
        } elseif ($request->service_class === 'BUSINESS' || $request->service_class === 'EXECUTIVE') {
            $seatCount = 100;
            $seatLetters = ['A', 'B', 'C', 'D']; // 4 seat per row
        }

        $seatsPerRow = count($seatLetters);
        $totalRows = ceil($seatCount / $seatsPerRow);

        // Tambahkan gerbong sesuai input
        for ($c = 1; $c <= $request->carriage_count; $c++) {
            $carriages = $train->carriages()->create([
                'code' => $train->code . "-K$c",
                'class' => $train->service_class,
                'seat_count' => $seatCount,
                'order' => $c,
            ]);

            // Generate kursi per baris
            $seatNumber = 0;
            for ($row = 1; $row <= $totalRows; $row++) {
                foreach ($seatLetters as $letter) {
                    $seatNumber++;
                    if ($seatNumber > $seatCount) break;

                    $carriages->seats()->create([
                        'seat_number' => $row . $letter, // contoh: 1A, 1B, ...
                        'position' => in_array($letter, ['A', 'E']) ? 'WINDOW' : 'AISLE',
                    ]);
                }
            }
        }

        return redirect()->route('kereta.index')->with('success', 'Kereta + Gerbong + Kursi berhasil ditambahkan');
    }


    public function edit($id)
    {
        $kereta = Train::findOrFail($id);
        return view('admin.kereta.edit', compact('kereta'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|string|max:10',
            'name' => 'required|string|max:100',
            'service_class' => 'required|in:ECONOMY,BUSINESS,EXECUTIVE',
            'carriage_count' => 'required|integer|min:1',
        ]);

        $kereta = Train::findOrFail($id);
        $kereta->update($request->only(['code', 'name', 'service_class', 'carriage_count']));
        return redirect()->route('kereta.index')->with('success', 'Data kereta berhasil diperbarui');
    }



    public function destroy($id)
    {
        $kereta = Train::findOrFail($id);
        $kereta->delete();

        return redirect()->route('dashboard')->with('success', 'Kereta berhasil dihapus');
    }
}
