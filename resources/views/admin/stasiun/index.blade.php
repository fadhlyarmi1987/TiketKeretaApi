@extends('layouts.admin')

@section('title', 'Stasiun')
@section('styles')
<link rel="stylesheet" href="{{ asset('css/stasiun.css') }}">
@endsection
@section('page_title', 'Manajemen Stasiun')

@section('content')
<div class="main-section">
    <div class="section-header">
        <a href="{{ route('stasiun.create') }}" class="btn-primary">➕ Tambah Stasiun</a>

        <!-- ganti form jadi input biasa -->
        <input type="text" id="station-search" placeholder="Cari stasiun..."
            class="search-input"
            style="margin-left:10px; padding:6px; border:1px solid #ccc; border-radius:6px; min-width:250px;">
    </div>

    @if(session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="table-container">
    <table class="table-dashboard">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Kota</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th style="width: 140px;">Aksi</th>
            </tr>
        </thead>
        <tbody id="stations-body">
            @foreach($stations as $station)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $station->code }}</td>
                <td>{{ $station->name }}</td>
                <td>{{ $station->city }}</td>
                <td>{{ $station->lat }}</td>
                <td>{{ $station->lng }}</td>
                <td class="action-buttons">
                    <a href="{{ route('stasiun.edit', $station->id) }}" class="btn-edit" title="Edit">✏️</a>
                    <form action="{{ route('stasiun.destroy', $station->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete" onclick="return confirm('Yakin hapus stasiun ini?')" title="Hapus">🗑️</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Tombol navigasi -->
<div class="table-navigation">
    <button id="prev-btn">⬅️</button>
    <button id="next-btn">➡️</button>
</div>

</div>
@endsection
@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const allRows = document.querySelectorAll("#stations-body tr");
    const rowsPerPage = 10;
    let currentPage = 0;
    let filteredRows = Array.from(allRows); // default semua data

    function showPage(page) {
        // sembunyikan semua
        allRows.forEach(row => row.style.display = "none");

        // tampilkan hanya yang terfilter
        filteredRows.forEach((row, index) => {
            if (index >= page * rowsPerPage && index < (page + 1) * rowsPerPage) {
                row.style.display = "";
            }
        });

        // atur tombol navigasi
        document.getElementById("prev-btn").disabled = page === 0;
        document.getElementById("next-btn").disabled = (page + 1) * rowsPerPage >= filteredRows.length;
    }

    // tombol navigasi
    document.getElementById("prev-btn").addEventListener("click", function () {
        if (currentPage > 0) {
            currentPage--;
            showPage(currentPage);
        }
    });

    document.getElementById("next-btn").addEventListener("click", function () {
        if ((currentPage + 1) * rowsPerPage < filteredRows.length) {
            currentPage++;
            showPage(currentPage);
        }
    });

    // fitur search
    document.getElementById("station-search").addEventListener("keyup", function () {
        let q = this.value.toLowerCase().trim();
        filteredRows = Array.from(allRows).filter(row => {
            let rowText = row.innerText.toLowerCase();
            return rowText.includes(q);
        });
        currentPage = 0; // reset ke halaman pertama setiap search
        showPage(currentPage);
    });

    // tampilkan halaman pertama
    showPage(currentPage);
});

</script>
@endpush
