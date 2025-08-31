@extends('layouts.admin')

@section('title', 'Edit Trip')
@section('page_title', 'Edit Trip')

@section('content')
<div class="container my-4">
    <link href="{{ asset('css/trip-edit.css') }}" rel="stylesheet">

    {{-- Tambahkan CSS Select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-body p-4">
            <link href="{{ asset('css/trip-edit.css') }}" rel="stylesheet">
            <h2 class="mb-4 fw-bold text-primary">✏️ Edit Trip</h2>

            <form action="{{ route('trips.update', $trip->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kereta</label>
                        <select name="train_id" class="form-select">
                            @foreach($trains as $train)
                                <option value="{{ $train->id }}" {{ $train->id == $trip->train_id ? 'selected' : '' }}>
                                    {{ $train->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Status</label>
                        <input type="text" name="status" value="{{ $trip->status }}" class="form-control">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Asal</label>
                        <select name="origin_station_id" class="form-select station-select">
                            @foreach($stations as $station)
                                <option value="{{ $station->id }}" {{ $station->id == $trip->origin_station_id ? 'selected' : '' }}>
                                    {{ $station->name }} ({{ $station->code }}) | {{ $station->city }} 
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tujuan</label>
                        <select name="destination_station_id" class="form-select station-select">
                            @foreach($stations as $station)
                                <option value="{{ $station->id }}" {{ $station->id == $trip->destination_station_id ? 'selected' : '' }}>
                                    {{ $station->name }} ({{ $station->code }}) | {{ $station->city }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Jam Berangkat</label>
                        <input type="time" name="departure_time" value="{{ $trip->departure_time }}" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Jam Tiba</label>
                        <input type="time" name="arrival_time" value="{{ $trip->arrival_time }}" class="form-control">
                    </div>
                </div>

                <h4 class="mt-4 fw-bold text-secondary">📍 Jadwal Stasiun</h4>
                <div class="table-responsive mt-3">
                    <table id="stationTable" data-initial-count="{{ $trip->tripStations->count() }}" class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Stasiun</th>
                                <th>Jam Tiba</th>
                                <th>Jam Berangkat</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($trip->tripStations as $i => $ts)
                            <tr>
                                <td>
                                    <select name="stations[{{ $i }}][station_id]" class="form-select station-select">
                                        @foreach($stations as $station)
                                            <option value="{{ $station->id }}" {{ $station->id == $ts->station_id ? 'selected' : '' }}>
                                                {{ $station->name }} ({{ $station->code }}) | {{ $station->city }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="time" name="stations[{{ $i }}][arrival_time]" value="{{ $ts->arrival_time }}" class="form-control">
                                </td>
                                <td>
                                    <input type="time" name="stations[{{ $i }}][departure_time]" value="{{ $ts->departure_time }}" class="form-control">
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-outline-danger btn-sm removeRow">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mb-3">
                    <button type="button" id="addRow" class="btn btn-tambah-stasiun">
                        <i class="bi bi-plus-circle"></i> Tambah Stasiun
                    </button>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 tombol-navigasi">
                    <a href="{{ route('trips.index') }}" class="btn btn-kembali">
                        <i class="bi bi-arrow-left-circle me-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-simpan">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- JSON stasiun --}}
<script type="application/json" id="stationsData">
{!! $stations->map(fn($s) => ['id'=>$s->id,'name'=>$s->name,'code'=>$s->code,'city'=>$s->city])->values()->toJson() !!}
</script>

{{-- jQuery + Select2 --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="{{ asset('js/trip-edit.js') }}" defer></script>
@endsection
