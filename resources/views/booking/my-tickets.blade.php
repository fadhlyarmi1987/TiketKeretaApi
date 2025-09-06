@extends('layouts.admin')

@section('title', 'Tiket Saya')
@section('page_title', 'Daftar Tiket Saya')

@section('content')
<div class="container my-4">
    <h2>🎫 Daftar Tiket Saya</h2>

    @if($bookings->isEmpty())
        <div class="alert alert-info mt-3">
            Kamu belum memiliki tiket yang dikonfirmasi.
        </div>
    @else
        <table class="table-dashboard mt-4">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Tiket</th>
                    <th>Kereta</th>
                    <th>Rute</th>
                    <th>Tanggal</th>
                    <th>Penumpang</th>
                    <th>Gerbong / Kursi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $i => $booking)
                    @foreach($booking->passengers as $p)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $booking->pnr }}-{{ $p->id }}</td>
                        <td>{{ $booking->trip->train->name }}</td>
                        <td>
                            {{ $booking->trip->stations->first()->station->name }}
                            →
                            {{ $booking->trip->stations->last()->station->name }}
                        </td>
                        <td>{{ \Carbon\Carbon::parse($booking->departure_date)->format('d M Y') }}</td>
                        <td>{{ $p->name }}</td>
                        <td>
                            @if($p->seat)
                                Gerbong {{ $p->seat->carriage->order }},
                                Kursi {{ $p->seat->seat_number }}
                            @else
                                <span class="text-danger">Belum dipilih</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $booking->status == 'CONFIRMED' ? 'bg-success' : 'bg-warning' }}">
                                {{ $booking->status }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('booking.ticket', $booking->id) }}" class="btn btn-sm btn-primary">
                                Lihat
                            </a>
                        </td>
                    </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
