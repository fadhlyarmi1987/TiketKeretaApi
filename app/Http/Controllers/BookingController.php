<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Models\Trip;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
            'departure_date' => 'required|date|after_or_equal:today',
        ]);

        $originId = $request->origin_id;
        $destinationId = $request->destination_id;
        $date = $request->departure_date;

        $trips = Trip::whereHas('stations', fn($q) => $q->where('station_id', $originId))
            ->whereHas('stations', fn($q) => $q->where('station_id', $destinationId))
            ->with(['train', 'stations.station'])
            ->get()
            ->filter(function ($trip) use ($originId, $destinationId) {
                $originOrder = $trip->stations->firstWhere('station_id', $originId)?->station_order;
                $destOrder   = $trip->stations->firstWhere('station_id', $destinationId)?->station_order;
                return $originOrder !== null && $destOrder !== null && $originOrder < $destOrder;
            });

        return view('booking.result', compact('trips', 'originId', 'destinationId', 'date'));
    }


    public function book(Request $request, Trip $trip)
    {
        $request->validate([
            'departure_date' => 'required|date|after_or_equal:today',
            'passengers' => 'required|array|min:1',
            'passengers.*.name' => 'required|string',
            'passengers.*.nik' => 'required|string',
            'passengers.*.jenis_kelamin' => 'required|string',
            'passengers.*.tanggal_lahir' => 'required|date',
        ]);

        $pnr = strtoupper(Str::random(6));

        $booking = Booking::create([
            'pnr' => $pnr,
            'user_id' => optional(auth()->user())->id,
            'trip_id' => $trip->id,
            'departure_date' => $request->departure_date,
            'status' => 'PENDING',
        ]);

        // Simpan semua penumpang
        foreach ($request->passengers as $p) {
            $booking->passengers()->create($p);
        }

        return redirect()->route('booking.show', $booking->id)
            ->with('success', 'Booking berhasil dibuat! Silakan pilih kursi.');
    }


    public function show($id)
    {
        $booking = \App\Models\Booking::with([
            'trip.train.carriages.seats',
            'trip.stations.station',
            'user'
        ])->findOrFail($id);

        return view('booking.show', compact('booking'));
    }

    public function selectSeat(Request $request, $bookingId, $seatId)
    {
        $booking = Booking::findOrFail($bookingId);
        $seat = Seat::with('carriage')->findOrFail($seatId);

        // cek apakah sudah booked oleh orang lain
        $isBooked = $seat->bookings()
            ->where('status', 'CONFIRMED')
            ->exists();

        if ($isBooked) {
            return back()->with('error', "Kursi {$seat->seat_number} di Gerbong {$seat->carriage->order} sudah dibooking!");
        }

        // simpan kursi pilihan di session, bukan DB
        session([
            'selected_seat_id' => $seat->id,
            'selected_carriage' => $seat->carriage->order,
            'selected_seat_number' => $seat->seat_number,
        ]);

        return back()->with('success', "Berhasil memilih Kursi {$seat->seat_number} di Gerbong {$seat->carriage->order}. Klik Konfirmasi untuk menyelesaikan pesanan.");
    }


    public function confirm(Request $request, Booking $booking)
    {
        if (!$request->passengers) {
            return back()->with('error', 'Silakan pilih kursi untuk semua penumpang!');
        }

        foreach ($request->passengers as $data) {
            $passenger = $booking->passengers()->find($data['id']);
            if ($passenger) {
                $passenger->seat_id = $data['seat_id'];
                $passenger->save();
            }
        }

        $booking->status = 'CONFIRMED';
        $booking->user_id = Auth::id();
        $booking->save();

        return redirect()->route('booking.ticket', $booking->id)
            ->with('success', "Pesanan berhasil dikonfirmasi untuk semua penumpang.");
    }


    public function ticket(Booking $booking)
    {
        $booking->load([
            'trip.train',
            'trip.stations.station',
            'seat.carriage',
            'user'
        ]);

        return view('booking.ticket', compact('booking'));
    }

    public function myTickets()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->where('status', 'CONFIRMED')
            ->with([
                'trip.train',
                'trip.stations.station',
                'seat.carriage',
            ])
            ->latest()
            ->get();

        return view('booking.my-tickets', compact('bookings'));
    }

    public function passengerDetail(Request $request, Trip $trip)
    {
        // simpan info sementara di session
        $request->session()->put('booking_data', [
            'trip_id' => $trip->id,
            'departure_date' => $request->departure_date,
        ]);

        return view('booking.passenger-detail', compact('trip'));
    }
}
