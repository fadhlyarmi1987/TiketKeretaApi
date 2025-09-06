@extends('layouts.admin')

@section('title', 'Detail Booking')
<link rel="stylesheet" href="{{ asset('css/booking-gerbong.css') }}">

@section('content')
<div class="container my-4">
    <h2>🚆 Pilih Kursi untuk Booking</h2>

    {{-- Pesan sukses/error --}}
    @if(session('success'))
    <div class="alert alert-success">
        ✅ {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        ❌ {{ session('error') }}
    </div>
    @endif

    {{-- ================= GERBONG ================= --}}
    <div class="train-carriages">
        @foreach($booking->trip->train->carriages as $carriage)
        <div class="carriage">
            <div class="carriage-header">
                Gerbong {{ $carriage->order }}
            </div>
            <div class="carriage-body">
                @php
                $rows = [];
                foreach($carriage->seats as $seat) {
                $row = preg_replace('/[^0-9]/', '', $seat->seat_number);
                $rows[$row][] = $seat;
                }
                @endphp

                @foreach($rows as $row => $seats)
                <div class="seat-row">
                    {{-- Grup kiri (A,B) --}}
                    <div class="seat-group">
                        @foreach($seats as $s)
                        @if(in_array(substr($s->seat_number, -1), ['A','B']))
                        @php
                        $isBooked = \App\Models\Passenger::where('seat_id', $s->id)
                        ->whereHas('booking', function($q) use ($booking) {
                        $q->where('status', 'CONFIRMED')
                        ->where('trip_id', $booking->trip_id)
                        ->where('departure_date', $booking->departure_date);
                        })
                        ->exists();
                        @endphp

                        <button type="button"
                            class="seat-btn {{ $isBooked ? 'booked' : '' }}"
                            data-seat-id="{{ $s->id }}"
                            data-carriage="{{ $s->carriage->order }}"
                            data-seat-number="{{ $s->seat_number }}"
                            {{ $isBooked ? 'disabled' : '' }}>
                            {{ $s->seat_number }}
                        </button>

                        @endif
                        @endforeach
                    </div>

                    <div class="aisle"></div>

                    {{-- Grup kanan (C,D,E) --}}
                    <div class="seat-group">
                        @foreach($seats as $s)
                        @if(!in_array(substr($s->seat_number, -1), ['A','B']))
                        @php
                        $isBooked = $s->bookings()
                        ->where('status', 'CONFIRMED')
                        ->where('trip_id', $booking->trip_id)
                        ->where('departure_date', $booking->departure_date)
                        ->exists();
                        @endphp

                        <button type="button"
                            class="seat-btn {{ $isBooked ? 'booked' : '' }}"
                            data-seat-id="{{ $s->id }}"
                            data-carriage="{{ $s->carriage->order }}"
                            data-seat-number="{{ $s->seat_number }}"
                            {{ $isBooked ? 'disabled' : '' }}>
                            {{ $s->seat_number }}
                        </button>
                        @endif
                        @endforeach
                    </div>
                </div>
                @endforeach

            </div>
        </div>
        @endforeach
    </div>

    {{-- ================= PILIH KURSI PER PENUMPANG ================= --}}
    <div class="mt-4">
        <form id="confirmForm" action="{{ route('booking.confirm', $booking->id) }}" method="POST">
            @csrf
            <h4>🧑‍🤝‍🧑 Pilih Kursi Penumpang</h4>

            {{-- Dropdown pilih penumpang aktif --}}
            <div class="mb-3">
                <label>Pilih Penumpang Aktif:</label>
                <select id="activePassenger" class="form-control">
                    <option value="">-- Pilih Penumpang --</option>
                    @foreach($booking->passengers as $p)
                    <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->nik }})</option>
                    @endforeach
                </select>
            </div>

            {{-- Hidden input untuk kursi masing-masing penumpang --}}
            @foreach($booking->passengers as $p)
            <input type="hidden" name="passengers[{{ $p->id }}][id]" value="{{ $p->id }}">
            <input type="hidden" name="passengers[{{ $p->id }}][seat_id]" id="seat-passenger-{{ $p->id }}">
            <p>
                {{ $p->name }} ({{ $p->nik }}) →
                <span id="seat-label-{{ $p->id }}" class="badge bg-secondary">Belum dipilih</span>
            </p>
            @endforeach

            <button type="submit" class="btn btn-success mt-3">
                ✅ Konfirmasi Booking
            </button>
        </form>
    </div>
</div>

<script>
    let activePassenger = null;

    // update penumpang aktif saat dropdown berubah
    document.getElementById("activePassenger").addEventListener("change", function() {
        activePassenger = this.value;
    });

    document.querySelectorAll(".seat-btn").forEach(btn => {
        btn.addEventListener("click", function() {
            if (!activePassenger) {
                alert("Pilih penumpang dulu sebelum memilih kursi!");
                return;
            }

            // cek apakah kursi sudah dipilih penumpang lain
            const used = Array.from(document.querySelectorAll("input[id^=seat-passenger-]"))
                .some(input => input.value == this.dataset.seatId);
            if (used) {
                alert("Kursi ini sudah dipilih penumpang lain!");
                return;
            }

            // simpan kursi untuk penumpang aktif
            document.getElementById(`seat-passenger-${activePassenger}`).value = this.dataset.seatId;
            document.getElementById(`seat-label-${activePassenger}`).textContent =
                `Gerbong ${this.dataset.carriage} - ${this.dataset.seatNumber}`;

            // tandai visual kursi
            document.querySelectorAll(".seat-btn").forEach(b => b.classList.remove("selected"));
            this.classList.add("selected");
        });
    });
</script>
@endsection