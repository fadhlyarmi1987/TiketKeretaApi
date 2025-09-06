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
            'type' => 'required|string',
            'carriage_count' => 'required|integer|min:1|max:20',
        ]);

        $train = Train::create([
            'code' => $request->code,
            'name' => $request->name,
            'service_class' => $request->service_class,
            'type' => $request->type,
            'carriage_count' => $request->carriage_count,
        ]);

        // Tentukan kursi per kelas
        if ($request->service_class === 'ECONOMY') {
            $seatCount = 106;
        } elseif ($request->service_class === 'BUSINESS' || $request->service_class === 'EXECUTIVE') {
            $seatCount = 48;
        } else {
            $seatCount = 0;
        }

        // Tambahkan gerbong sesuai input
        for ($c = 1; $c <= $request->carriage_count; $c++) {
            $carriage = $train->carriages()->create([
                'code' => $train->code . "-K$c",
                'class' => $train->service_class,
                'seat_count' => $seatCount,
                'order' => $c,
            ]);

            // Generate kursi
            $seatNumber = 0;
            $totalRows = 30; // cukup banyak untuk economy, bisa disesuaikan

            for ($row = 1; $row <= $totalRows; $row++) {
                // tentukan huruf kursi per baris
                if ($request->service_class === 'ECONOMY') {
                    if ($row == 1 || $row == 2) {
                        $seatLetters = ['A', 'B'];
                    } elseif ($row == 3 || $row == 22) {
                        $seatLetters = ['A', 'B', 'D', 'E'];
                    } elseif ($row == 23 || $row == 24) {
                        $seatLetters = ['D', 'E'];
                    } else {
                        $seatLetters = ['A', 'B', 'C', 'D', 'E'];
                    }
                } else {
                    // BUSINESS / EXECUTIVE
                    $seatLetters = ['A', 'B', 'C', 'D'];
                }

                foreach ($seatLetters as $letter) {
                    $seatNumber++;
                    if ($seatNumber > $seatCount) break 2; // stop jika sudah penuh

                    $carriage->seats()->create([
                        'seat_number' => $row . $letter,
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
        $oldCarriageCount = $kereta->carriage_count;
        $kereta->update($request->only(['code', 'name', 'service_class', 'carriage_count']));

        // Jika jumlah gerbong berubah, regenerasi gerbong & kursi
        if ($request->carriage_count != $oldCarriageCount) {
            // Hapus gerbong lama beserta kursinya
            $kereta->carriages()->delete();

            // Tentukan jumlah kursi
            if ($request->service_class === 'ECONOMY') {
                $seatCount = 106; // sesuai kebutuhan
            } elseif (in_array($request->service_class, ['BUSINESS', 'EXECUTIVE'])) {
                $seatCount = 48;
            } else {
                $seatCount = 0;
            }

            // Tambahkan gerbong baru
            for ($c = 1; $c <= $request->carriage_count; $c++) {
                $carriage = $kereta->carriages()->create([
                    'code' => $kereta->code . "-K$c",
                    'class' => $kereta->service_class,
                    'seat_count' => $seatCount,
                    'order' => $c,
                ]);

                // Generate kursi per row
                $seatNumber = 0;
                $totalRows = 30; // asumsi cukup untuk ECONOMY, sesuaikan jika perlu

                for ($row = 1; $row <= $totalRows; $row++) {
                    if ($request->service_class === 'ECONOMY') {
                        // aturan khusus economy
                        if ($row == 1 || $row == 2) {
                            $seatLetters = ['A', 'B'];
                        } elseif ($row == 3 || $row == 22) {
                            $seatLetters = ['A', 'B', 'D', 'E'];
                        } elseif ($row == 23 || $row == 24) {
                            $seatLetters = ['D', 'E'];
                        } else {
                            $seatLetters = ['A', 'B', 'C', 'D', 'E'];
                        }
                    } else {
                        // business / executive
                        $seatLetters = ['A', 'B', 'C', 'D'];
                    }

                    foreach ($seatLetters as $letter) {
                        $seatNumber++;
                        if ($seatNumber > $seatCount) break 2; // stop jika sudah penuh

                        $carriage->seats()->create([
                            'seat_number' => $row . $letter,
                            'position' => in_array($letter, ['A', 'E']) ? 'WINDOW' : 'AISLE',
                        ]);
                    }
                }
            }
        }

        return redirect()->route('kereta.index')->with('success', 'Data kereta berhasil diperbarui');
    }
    public function destroy($id)
    {
        $kereta = Train::findOrFail($id);
        $kereta->delete();

        return redirect()->route('kereta.index')->with('success', 'Kereta berhasil dihapus');
    }
}
