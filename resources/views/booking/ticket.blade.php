@extends('layouts.admin')

@section('title', 'E-Tiket')
@section('page_title', 'Tiket Elektronik')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/eticket.css') }}">
@endsection

@section('content')
<div class="eticket-container">

    @foreach($booking->passengers as $p)
    <div class="eticket shadow-lg mb-5">
        <!-- Header -->
        <div class="eticket-header d-flex justify-content-between">
            <h2>🎫 E-Tiket Kereta Api</h2>
            <div>
                <strong>Kode Tiket:</strong> {{ $booking->pnr }}-{{ $p->id }} <br>
                <span class="badge bg-success">{{ $booking->status }}</span>
            </div>
        </div>

        <!-- QR Code (unik per penumpang) -->
        <div class="qr-code text-center my-3">
            {!! QrCode::size(120)->generate($booking->pnr . '-' . $p->id) !!}
        </div>

        <!-- Data Penumpang -->
        <div class="eticket-section">
            <h4>👤 Data Penumpang</h4>
            <div class="eticket-info">
                <p><strong>Nama:</strong> {{ $p->name }}</p>
                <p><strong>NIK:</strong> {{ $p->nik }}</p>
                <p><strong>Jenis Kelamin:</strong> {{ $p->jenis_kelamin }}</p>
                <p><strong>Tanggal Lahir:</strong> {{ $p->tanggal_lahir }}</p>
            </div>
        </div>

        <!-- Detail Perjalanan -->
        <div class="eticket-section">
            <h4>🚆 Detail Perjalanan</h4>
            @php
                $origin = $booking->trip->stations->first();
                $destination = $booking->trip->stations->last();
            @endphp
            <div class="eticket-info">
                <p><strong>Kereta:</strong> {{ $booking->trip->train->name }}</p>
                <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($booking->departure_date)->format('d M Y') }}</p>
                <p><strong>Asal:</strong> {{ $origin?->station->name }} ({{ $origin?->departure_time }})</p>
                <p><strong>Tujuan:</strong> {{ $destination?->station->name }} ({{ $destination?->arrival_time }})</p>
            </div>
        </div>

        <!-- Kursi -->
        <div class="eticket-section">
            <h4>💺 Kursi</h4>
            <div class="eticket-info">
                @if($p->seat)
                    <p><strong>Gerbong:</strong> {{ $p->seat->carriage->order }}</p>
                    <p><strong>Kursi:</strong> {{ $p->seat->seat_number }}</p>
                @else
                    <p class="text-danger">Belum dipilih</p>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="ticket-footer text-center mt-4">
            E-Tiket ini sah digunakan tanpa tanda tangan dan stempel. <br>
            Harap hadir 30 menit sebelum jadwal keberangkatan.
        </div>

        <div class="text-center mt-3">
            <button onclick="window.print()" class="btn btn-secondary">🖨 Cetak Tiket</button>
        </div>
    </div>
    @endforeach

    <div class="text-center mt-4">
        <a href="{{ route('booking.create') }}" class="btn btn-primary">Pesan Lagi</a>
    </div>

</div>
@endsection
