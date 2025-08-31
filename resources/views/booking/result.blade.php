@extends('layouts.admin')

@section('title', 'User')
@section('page_title', 'Result')

@section('content')
<div class="container my-4">
    <h2>🎟️ Hasil Pencarian Tiket</h2>
    <p>Tanggal: {{ $date }}</p>

    @if($trips->isEmpty())
        <div class="alert alert-warning">Tidak ada perjalanan yang ditemukan.</div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Kereta</th>
                    <th>Asal</th>
                    <th>Tujuan</th>
                    <th>Berangkat</th>
                    <th>Tiba</th>
                </tr>
            </thead>
            <tbody>
                @foreach($trips as $trip)
                    @php
                        $originStation = $trip->stations->firstWhere('station_id', $originId);
                        $destinationStation = $trip->stations->firstWhere('station_id', $destinationId);
                    @endphp
                    <tr>
                        <td>{{ $trip->train->name ?? 'N/A' }}</td>
                        <td>{{ $originStation?->station->name }}</td>
                        <td>{{ $destinationStation?->station->name }}</td>
                        <td>{{ $originStation?->departure_time }}</td>
                        <td>{{ $destinationStation?->arrival_time }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
