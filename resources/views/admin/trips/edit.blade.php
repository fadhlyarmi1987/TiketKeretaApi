@extends('layouts.admin')

@section('title', 'Edit Trip')
@section('page_title', 'Edit Trip')

@section('content')
<div class="trip-edit">
    <h2>✏️ Edit Trip</h2>

    <form action="{{ route('trips.update', $trip->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label>Kereta</label>
            <select name="train_id">
                @foreach($trains as $train)
                <option value="{{ $train->id }}" {{ $train->id == $trip->train_id ? 'selected' : '' }}>
                    {{ $train->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Asal</label>
            <select name="origin_station_id">
                @foreach($stations as $station)
                <option value="{{ $station->id }}" {{ $station->id == $trip->origin_station_id ? 'selected' : '' }}>
                    {{ $station->name }} ({{ $station->city }})
                </option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Tujuan</label>
            <select name="destination_station_id">
                @foreach($stations as $station)
                <option value="{{ $station->id }}" {{ $station->id == $trip->destination_station_id ? 'selected' : '' }}>
                    {{ $station->name }} ({{ $station->city }})
                </option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Tanggal</label>
            <input type="date" name="travel_date" value="{{ $trip->travel_date }}">
        </div>

        <div>
            <label>Jam Berangkat</label>
            <input type="time" name="departure_time" value="{{ $trip->departure_time }}">
        </div>

        <div>
            <label>Jam Tiba</label>
            <input type="time" name="arrival_time" value="{{ $trip->arrival_time }}">
        </div>

        <div>
            <label>Status</label>
            <input type="text" name="status" value="{{ $trip->status }}">
        </div>

        <h3>Jadwal Stasiun</h3>
        <table id="stationTable">
            <thead>
                <tr>
                    <th>Stasiun</th>
                    <th>Jam Tiba</th>
                    <th>Jam Berangkat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($trip->tripStations as $i => $ts)
                <tr>
                    <td>
                        <select name="stations[{{ $i }}][station_id]">
                            @foreach($stations as $station)
                            <option value="{{ $station->id }}" {{ $station->id == $ts->station_id ? 'selected' : '' }}>
                                {{ $station->name }} ({{ $station->city }})
                            </option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="time" name="stations[{{ $i }}][arrival_time]" value="{{ $ts->arrival_time }}"></td>
                    <td><input type="time" name="stations[{{ $i }}][departure_time]" value="{{ $ts->departure_time }}"></td>
                    <td><button type="button" class="removeRow">❌</button></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <button type="button" id="addRow">➕ Tambah Stasiun</button>

        <br><br>
        <button type="submit">💾 Simpan Perubahan</button>
    </form>
</div>

{{-- JS Tambah/Hapus Row --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    let rowIndex = {{ count($trip->tripStations) }};

    document.getElementById("addRow").addEventListener("click", function () {
        let tableBody = document.querySelector("#stationTable tbody");

        let newRow = document.createElement("tr");
        newRow.innerHTML = `
            <td>
                <select name="stations[${rowIndex}][station_id]">
                    @foreach($stations as $station)
                        <option value="{{ $station->id }}">{{ $station->name }} ({{ $station->city }})</option>
                    @endforeach
                </select>
            </td>
            <td><input type="time" name="stations[${rowIndex}][arrival_time]"></td>
            <td><input type="time" name="stations[${rowIndex}][departure_time]"></td>
            <td><button type="button" class="removeRow">❌</button></td>
        `;
        tableBody.appendChild(newRow);
        rowIndex++;
    });

    document.querySelector("#stationTable").addEventListener("click", function (e) {
        if (e.target.classList.contains("removeRow")) {
            e.target.closest("tr").remove();
        }
    });
});
</script>
@endsection
