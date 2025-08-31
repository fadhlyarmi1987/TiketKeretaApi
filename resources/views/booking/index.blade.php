@extends('layouts.admin')

@section('title', 'User')
@section('page_title', 'Pemesanan')

@section('content')
<div class="container my-4">
    <h2 class="mb-4">🛤️ Pemesanan Tiket</h2>

    <form action="{{ route('booking.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="origin_id" class="form-label">Stasiun Asal</label>
            <select name="origin_id" id="origin_id" class="form-control" required>
                <option value="">-- Pilih Stasiun Asal --</option>
                @foreach($stations as $station)
                    <option value="{{ $station->id }}">{{ $station->name }} ({{ $station->code }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="destination_id" class="form-label">Stasiun Tujuan</label>
            <select name="destination_id" id="destination_id" class="form-control" required>
                <option value="">-- Pilih Stasiun Tujuan --</option>
                @foreach($stations as $station)
                    <option value="{{ $station->id }}">{{ $station->name }} ({{ $station->code }})</option>
                @endforeach
            </select>
        </div>



        <button type="submit" class="btn btn-primary">Cari Tiket</button>
    </form>
</div>
@endsection
