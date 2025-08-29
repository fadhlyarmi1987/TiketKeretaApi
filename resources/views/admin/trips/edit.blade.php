@extends('layouts.admin')

@section('title', 'Edit Trip')
@section('page_title', 'Ubah Data Trip')

@section('content')
<form method="POST" action="{{ route('trips.update', $trip->id) }}">
    @csrf
    @method('PUT')

    <div>
        <label>Kereta</label><br>
        <select name="train_id" required>
            <option value="">-- Pilih Kereta --</option>
            @foreach($trains as $train)
            <option value="{{ $train->id }}" {{ $trip->train_id == $train->id ? 'selected' : '' }}>
                {{ $train->name }} ({{ $train->code }})
            </option>
            @endforeach
        </select>
    </div><br>

    <div>
        <label>Tanggal Perjalanan</label><br>
        <input type="date" name="travel_date" value="{{ $trip->travel_date }}" required>
    </div><br>

    <div>
        <label>Stasiun & Jadwal</label><br>

        {{-- template opsi stasiun --}}
        <template id="stationOptionsTpl">
            @foreach($stations as $station)
            <option value="{{ $station->id }}">{{ $station->name }} ({{ $station->city }})</option>
            @endforeach
        </template>

        <table border="1" cellpadding="5" id="stationTable">
            <thead>
                <tr>
                    <th>Stasiun</th>
                    <th>Jam Tiba</th>
                    <th>Jam Berangkat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($trip->tripStations->sortBy('order') as $i => $ts)
                <tr>
                    <td>
                        <select name="stations[{{ $i }}][station_id]" required>
                            <option value="">-- Pilih Stasiun --</option>
                            @foreach($stations as $station)
                            <option value="{{ $station->id }}" {{ $ts->station_id == $station->id ? 'selected' : '' }}>
                                {{ $station->name }} ({{ $station->city }})
                            </option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="time" name="stations[{{ $i }}][arrival_time]" value="{{ $ts->arrival_time }}"></td>
                    <td><input type="time" name="stations[{{ $i }}][departure_time]" value="{{ $ts->departure_time }}"></td>
                    <td><button type="button" onclick="removeRow(this)">Hapus</button></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        <button type="button" onclick="addRow()">➕ Tambah Stasiun</button>
    </div><br>

    <button type="submit">💾 Update</button>
</form>

<script>
    const stationOptionsHTML = document.getElementById('stationOptionsTpl').innerHTML;

    function reindexRows() {
        document.querySelectorAll('#stationTable tbody tr').forEach((tr, i) => {
            tr.querySelectorAll('select, input').forEach(el => {
                if (el.name.includes('station_id')) {
                    el.name = `stations[${i}][station_id]`;
                }
                if (el.name.includes('arrival_time')) {
                    el.name = `stations[${i}][arrival_time]`;
                }
                if (el.name.includes('departure_time')) {
                    el.name = `stations[${i}][departure_time]`;
                }
            });
        });
    }

    function addRow() {
        const tbody = document.querySelector('#stationTable tbody');
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>
                <select required>
                    <option value="">-- Pilih Stasiun --</option>
                    ${stationOptionsHTML}
                </select>
            </td>
            <td><input type="time"></td>
            <td><input type="time"></td>
            <td><button type="button" onclick="removeRow(this)">Hapus</button></td>
        `;
        tbody.appendChild(tr);
        reindexRows(); // 🔑 reindex setelah tambah
    }

    function removeRow(btn) {
        btn.closest('tr').remove();
        reindexRows(); // 🔑 reindex setelah hapus
    }

    // panggil sekali saat load awal
    reindexRows();
</script>

@endsection