@extends('layouts.admin')

@section('title', 'Daftar Trip')
@section('page_title', 'Manajemen Trip')

@section('content')
<link rel="stylesheet" href="{{ asset('css/trip-index.css') }}">
<div class="trip-index">
    <div class="header">
        <h2>🚆 Daftar Trip</h2>
        <a href="{{ route('trips.create') }}" class="btn btn-secondary">➕ Tambah Trip</a>
    </div>

    @if(session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="table-wrapper">
        <table class="trip-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kereta</th>
                    <th>Asal</th>
                    <th>Tujuan</th>
                    <th>Jadwal Stasiun</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($trips as $trip)
                <tr>
                    <td>{{ $trip->id }}</td>
                    <td>{{ $trip->train->name }}</td>
                    <td>{{ $trip->origin->name }} ({{ $trip->origin->city }})</td>
                    <td>{{ $trip->destination->name }} ({{ $trip->destination->city }})</td>
                    <td>
                        <table class="station-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Stasiun</th>
                                    <th>Jam Tiba</th>
                                    <th>Jam Berangkat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $tripStations = $trip->tripStations()
                                ->orderByRaw("COALESCE(arrival_time, departure_time)")
                                ->get();
                                @endphp

                                @foreach($tripStations as $index => $ts)
                                <tr>
                                    <td>{{ $index + 1 }}</td> <!-- otomatis nomor urut sesuai jam -->
                                    <td>{{ $ts->station->name }} ({{ $ts->station->city }})</td>
                                    <td>{{ $ts->arrival_time ?? '-' }}</td>
                                    <td>{{ $ts->departure_time ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </td>
                    <td class="actions">
                        <a href="{{ route('trips.edit', $trip->id) }}" class="btn-edit">✏️ Edit</a>
                        <form action="{{ route('trips.destroy', $trip->id) }}" method="POST" class="form-delete"
                            onsubmit="return confirm('Yakin hapus trip ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete">🗑 Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="empty">Belum ada trip</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('styles')

@endpush