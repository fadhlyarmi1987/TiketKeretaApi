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
                    <th>Tanggal</th>
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
                    <td>{{ \Carbon\Carbon::parse($trip->travel_date)->format('d M Y') }}</td>
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
                                @foreach($trip->tripStations()->orderBy('order')->get() as $ts)
                                <tr>
                                    <td>{{ $ts->order }}</td>
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
