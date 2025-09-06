<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\Booking;
use App\Models\Ticket;
use App\Models\Passenger;
use App\Models\Seat;
use App\Models\Trip;

class BookingController extends Controller
{
    /**
     * Buat pesanan tiket
     */
    public function store(Request $request)
    {
        $request->validate([
            'trip_id' => 'required|exists:trips,id',
            'seats' => 'required|array|min:1',
            'passengers' => 'required|array|min:1',
            'passengers.*.name' => 'required|string',
            'passengers.*.identity_number' => 'required|string',
        ]);

        $user = Auth::user();

        return DB::transaction(function () use ($request, $user) {
            // buat booking
            $booking = Booking::create([
                'user_id' => $user->id,
                'trip_id' => $request->trip_id,
                'status' => 'pending'
            ]);

            foreach ($request->passengers as $index => $p) {
                // buat penumpang
                $passenger = Passenger::create([
                    'name' => $p['name'],
                    'identity_number' => $p['identity_number']
                ]);

                // ambil seat sesuai index penumpang
                $seatId = $request->seats[$index] ?? null;
                if (!$seatId) {
                    throw new \Exception("Seat untuk penumpang {$p['name']} tidak ada.");
                }

                $seat = Seat::findOrFail($seatId);

                // pastikan seat belum dipakai di booking confirmed
                $alreadyBooked = Ticket::where('seat_id', $seat->id)
                    ->whereHas('booking', function ($q) {
                        $q->where('status', 'confirmed');
                    })->exists();

                if ($alreadyBooked) {
                    throw new \Exception("Seat {$seat->id} sudah terisi.");
                }

                // buat tiket
                Ticket::create([
                    'booking_id' => $booking->id,
                    'seat_id' => $seat->id,
                    'passenger_id' => $passenger->id
                ]);
            }

            return response()->json([
                'booking' => $booking->load('tickets.passenger', 'tickets.seat')
            ], 201);
        });
    }

    public function search(Request $request)
    {
        $request->validate([
            'origin_id' => 'required|exists:stations,id',
            'destination_id' => 'required|exists:stations,id|different:origin_id',
            'departure_date' => 'required|date',
        ]);

        $originId = $request->origin_id;
        $destinationId = $request->destination_id;
        $date = $request->departure_date;

        $trips = Trip::with(['train', 'tripStations.station'])
            ->whereHas('tripStations', fn($q) => $q->where('station_id', $originId))
            ->whereHas('tripStations', fn($q) => $q->where('station_id', $destinationId))
            ->get()
            ->filter(function ($trip) use ($originId, $destinationId) {
                $originOrder = $trip->tripStations->firstWhere('station_id', $originId)?->station_order;
                $destOrder   = $trip->tripStations->firstWhere('station_id', $destinationId)?->station_order;
                return $originOrder !== null && $destOrder !== null && $originOrder < $destOrder;
            })
            ->values();


        return response()->json([
            'success' => true,
            'message' => 'Hasil pencarian trip',
            'data' => $trips,
        ]);
    }


    /**
     * Lihat detail booking
     */
    public function show($id)
    {
        $booking = Booking::with([
            'trip.train',
            'tickets.passenger',
            'tickets.seat.carriage'
        ])->findOrFail($id);

        // hanya pemilik booking yang bisa lihat
        if ($booking->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($booking);
    }

    /**
     * Daftar booking user login
     */
    public function myBookings()
    {
        $bookings = Booking::with('trip', 'tickets.passenger', 'tickets.seat')
            ->where('user_id', Auth::id())
            ->get();

        return response()->json($bookings);
    }
}
