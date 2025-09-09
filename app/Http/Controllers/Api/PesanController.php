<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PesanController extends Controller
{
    public function store(Request $request)
    {
        // Validasi data permintaan
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'trip_id' => 'required|integer|exists:trips,id',
            'departure_date' => 'required|date|after_or_equal:today',
            'seat_id' => 'required|integer|exists:seats,id',
            'passengers' => 'required|array|min:1',
            'passengers.*.name' => 'required|string|max:255',
            'passengers.*.nik' => 'required|string|max:20',
            'passengers.*.jenis_kelamin' => 'required|string|max:10',
            'passengers.*.tanggal_lahir' => 'required|date',
            'passengers.*.seat_id' => 'required|integer|exists:seats,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 400);
        }

        try {
            // Generate kode PNR unik
            $pnr = strtoupper(Str::random(6));

            // Buat booking baru dengan menyertakan seat_id
            $booking = Booking::create([
                'pnr' => $pnr,
                'user_id' => $request->user_id,
                'trip_id' => $request->trip_id,
                'departure_date' => $request->departure_date,
                'status' => 'PENDING',
                'seat_id' => $request->seat_id,  // Pastikan seat_id disertakan di booking
            ]);

            // Simpan semua penumpang melalui relasi booking
            foreach ($request->passengers as $p) {
                $booking->passengers()->create($p);
            }

            return response()->json([
                'message' => 'Pemesanan berhasil dibuat!',
                'booking' => $booking->load('passengers'), // sertakan data penumpang
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan saat membuat pemesanan. ' . $e->getMessage(),
            ], 500);
        }
    }

    public function updateStatusByUser(Request $request, $userId)
{
    $validator = Validator::make($request->all(), [
        'status' => 'required|in:PENDING,CONFIRMED,CANCELLED',
        'trip_id' => 'required|integer|exists:trips,id',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'error' => $validator->errors(),
        ], 400);
    }

    try {
        // update semua booking untuk user + trip tersebut
        $affected = Booking::where('user_id', $userId)
            ->where('trip_id', $request->trip_id)
            ->update(['status' => $request->status]);

        if ($affected === 0) {
            return response()->json([
                'error' => 'Tidak ada booking yang ditemukan',
            ], 404);
        }

        return response()->json([
            'message' => "Status $affected booking diperbarui!",
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Gagal memperbarui status booking. ' . $e->getMessage(),
        ], 500);
    }
}

    public function confirm($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->status = 'confirmed';
        $booking->save();

        return response()->json(['message' => 'Booking confirmed']);
    }
}
