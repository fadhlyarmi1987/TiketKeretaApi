@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@yield('styles')
@section('content')
<div class="dashboard-container">
    <div class="cards">
        <div class="card">
            <h3>Total Kereta</h3>
            <p>{{ $totalKereta }}</p>
        </div>
        <div class="card">
            <h3>Total Stasiun</h3>
            <p>{{ $totalStasiun }}</p>
        </div>
        <div class="card">
            <h3>Total User</h3>
            <p>{{ $totalUser }}</p>
        </div>
    </div>

    <div class="main-section">
    <h3>Daftar Kereta</h3>

    <form method="GET" action="{{ route('dashboard') }}" style="margin-bottom: 15px;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kereta..." style="padding:5px; width:200px;">
        <button type="submit" style="padding:5px 10px;">Cari</button>
        @if(request('search'))
            <a href="{{ route('dashboard') }}" style="padding:5px 10px; background:#ccc; text-decoration:none;">Reset</a>
        @endif
    </form>

    <table class="table-dashboard">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Jumlah Gerbong</th>
            </tr>
        </thead>
        <tbody>
            @forelse($daftarKereta as $kereta)
            <tr>
                <td>{{ $kereta->code }}</td>
                <td>{{ $kereta->name }}</td>
                <td>{{ $kereta->service_class }}</td>
                <td>{{ $kereta->carriages_count }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align:center;">Tidak ada kereta ditemukan</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

</div>
@endsection
