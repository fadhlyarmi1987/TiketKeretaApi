@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

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
                @foreach($daftarKereta as $kereta)
                <tr>
                    <td>{{ $kereta->code }}</td>
                    <td>{{ $kereta->name }}</td>
                    <td>{{ $kereta->service_class }}</td>
                    <td>{{ $kereta->carriage_count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
