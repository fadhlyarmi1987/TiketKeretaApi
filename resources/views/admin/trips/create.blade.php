@extends('layouts.admin')

@section('title', 'Tambah Trip')
@section('page_title', 'Tambah Data Trip')

@section('content')
<div class="trip-form max-w-5xl mx-auto bg-white shadow-lg rounded-2xl p-6 border border-gray-100">

    <link rel="stylesheet" href="{{ asset('css/trip-form.css') }}">

    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        🚄 Tambah Data Trip
    </h2>
    

    <form method="POST" action="{{ route('trips.store') }}">
        @csrf

        {{-- Pilih Kereta --}}
        <div class="mb-4">
            <label for="train_id" class="form-label fw-semibold">🚆 Pilih Kereta</label>
            <select name="train_id" id="train_id" class="input-field w-100" required>
                <option value="">-- Pilih Kereta --</option>
                @foreach($trains as $train)
                <option value="{{ $train->id }}">{{ $train->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Tanggal --}}
        <div class="mb-4">
            <label for="travel_date" class="form-label fw-semibold">📅 Tanggal Perjalanan</label>
            <input type="date" id="travel_date" name="travel_date" class="input-field w-100" required>
        </div>

        {{-- Stasiun & Jadwal --}}
        <div class="mb-4">
            <label class="form-label fw-semibold">🛤️ Rute Perjalanan (Stasiun & Jadwal)</label>
            <div class="table-responsive">
                <table id="stationTable" class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Stasiun</th>
                            <th>Jam Tiba</th>
                            <th>Jam Berangkat</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="stations[0][station_id]" class="station-select input-field w-100" required></select>
                            </td>
                            <td>
                                <input type="time" name="stations[0][arrival_time]" class="input-field w-100">
                            </td>
                            <td>
                                <input type="time" name="stations[0][departure_time]" class="input-field w-100">
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn-danger">Hapus</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- tombol tambah stasiun --}}
            <div class="action-row">
                <button type="button" class="btn-add" id="btnAddStation">Tambah Stasiun</button>
            </div>
        </div>

        {{-- Tombol Simpan & Kembali --}}
        <div class="form-actions">
            <a href="{{ route('trips.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn-submit">Simpan Trip</button>
        </div>

    </form>
</div>

{{-- jQuery & Select2 --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof $ === 'undefined') {
            console.error("jQuery tidak ter-load!");
            return;
        }
        initSelect2('.station-select');
        document.getElementById("btnAddStation").addEventListener("click", function() {
            addRow();
        });
    });

    function initSelect2(selector) {
        $(selector).select2({
            placeholder: "-- Cari Stasiun --",
            width: '100%',
            allowClear: true,
            ajax: {
                url: "{{ route('stations.search') }}",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term || ''
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            minimumInputLength: 1
        });
    }

    let index = 1;

    function addRow() {
        const tbody = document.querySelector('#stationTable tbody');
        const tr = document.createElement('tr');
        tr.innerHTML = `
        <td><select name="stations[${index}][station_id]" class="station-select input-field w-100" required></select></td>
        <td><input type="time" name="stations[${index}][arrival_time]" class="input-field w-100"></td>
        <td><input type="time" name="stations[${index}][departure_time]" class="input-field w-100"></td>
        <td class="text-center">
            <button type="button" class="btn-danger" onclick="removeRow(this)">🗑️</button>
        </td>
    `;
        tbody.appendChild(tr);
        initSelect2($(tr).find('.station-select'));
        index++;
    }

    function removeRow(button) {
        button.closest('tr').remove();
    }
</script>
@endsection