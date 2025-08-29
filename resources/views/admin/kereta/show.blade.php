@extends('layouts.admin')

@section('title', 'Detail Kereta')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/gerbong.css') }}">
@endsection
@section('page_title', 'Detail Kereta: ' . $kereta->name)

@section('content')
<div class="train-detail">
    <h2>🚆 {{ $kereta->name }} ({{ $kereta->code }})</h2>
    <p>Kelas: <strong>{{ $kereta->service_class }}</strong></p>
    <p>Jumlah Gerbong: <strong>{{ $kereta->carriage_count }}</strong></p>

    <div class="train-carriages">
        @foreach($kereta->carriages as $carriage)
            <div class="carriage">
                <div class="carriage-header">
                    Gerbong {{ $carriage->order }}
                </div>
                <div class="carriage-body">
                    @php
                        // kelompokkan kursi per row
                        $rows = [];
                        foreach($carriage->seats as $seat) {
                            $row = preg_replace('/[^0-9]/', '', $seat->seat_number); // ambil angka row
                            $rows[$row][] = $seat;
                        }
                    @endphp

                    @foreach($rows as $row => $seats)
                        <div class="seat-row">
                            <div class="seat-group">
                                @foreach($seats as $s)
                                    @if(in_array(substr($s->seat_number, -1), ['A','B'])) 
                                        <div class="seat {{ $s->status == 'BOOKED' ? 'booked' : 'available' }}">
                                            {{ $s->seat_number }}
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="aisle"></div>
                            <div class="seat-group">
                                @foreach($seats as $s)
                                    @if(!in_array(substr($s->seat_number, -1), ['A','B'])) 
                                        <div class="seat {{ $s->status == 'BOOKED' ? 'booked' : 'available' }}">
                                            {{ $s->seat_number }}
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <a href="{{ route('kereta.index') }}" class="btn-primary back-btn">⬅️ Kembali</a>
</div>
@endsection
