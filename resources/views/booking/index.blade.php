@extends('layouts.admin')

@section('title', 'User')
@section('page_title', 'Pemesanan')
@section('styles')
<link rel="stylesheet" href="{{ asset('css/booking-index.css') }}">
{{-- CSS Select2 --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="container my-4">
    <h2 class="mb-4">🛤️ Pemesanan Tiket</h2>

    <form action="{{ route('booking.store') }}" method="POST" class="form-pemesanan">
        @csrf

        <div class="row">
            <!-- Stasiun Asal -->
            <div class="col-md-6 mb-3">
                <label for="origin_id" class="form-label">🚉 Stasiun Asal</label>
                <select name="origin_id" id="origin_id" class="form-control station-select" required>
                    <option value="">-- Pilih Stasiun Asal --</option>
                    @foreach($stations as $station)
                        <option value="{{ $station->id }}">{{ $station->name }} ({{ $station->code }}) | {{ $station->city }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Stasiun Tujuan -->
            <div class="col-md-6 mb-3">
                <label for="destination_id" class="form-label">🎯 Stasiun Tujuan</label>
                <select name="destination_id" id="destination_id" class="form-control station-select" required>
                    <option value="">-- Pilih Stasiun Tujuan --</option>
                    @foreach($stations as $station)
                        <option value="{{ $station->id }}">{{ $station->name }} ({{ $station->code }}) | {{ $station->city }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Tanggal Berangkat -->
        <div class="row">
            <div class="col-md-12 mb-4">
                <label for="departure_date" class="form-label">📅 Tanggal Berangkat</label>
                <input type="date" name="departure_date" id="departure_date" class="form-control" required>
            </div>
        </div>

        <!-- Tombol -->
        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-cari-tiket">🔍 Cari Tiket</button>
        </div>
    </form>
</div>

{{-- jQuery + Select2 --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // aktifkan select2
    $(".station-select").select2({
        placeholder: "Ketik nama/kode stasiun...",
        allowClear: true,
        width: "100%"
    });

    // aturan 1: stasiun asal tidak boleh dipilih lagi di tujuan
    $("#origin_id").on("change", function () {
        let originVal = $(this).val();

        // reset tujuan dulu
        $("#destination_id option").prop("disabled", false);

        if (originVal) {
            // disable option tujuan yang sama dengan asal
            $("#destination_id option[value='" + originVal + "']").prop("disabled", true);
        }

        // refresh select2 tujuan biar update
        $("#destination_id").val(null).trigger("change");
    });

    // aturan 2: tanggal hanya bisa hari ini sampai H+7
    let today = new Date();
    let dd = String(today.getDate()).padStart(2, '0');
    let mm = String(today.getMonth() + 1).padStart(2, '0'); // Januari = 0
    let yyyy = today.getFullYear();

    let minDate = yyyy + '-' + mm + '-' + dd;

    let maxDateObj = new Date();
    maxDateObj.setDate(today.getDate() + 7);
    let ddMax = String(maxDateObj.getDate()).padStart(2, '0');
    let mmMax = String(maxDateObj.getMonth() + 1).padStart(2, '0');
    let yyyyMax = maxDateObj.getFullYear();
    let maxDate = yyyyMax + '-' + mmMax + '-' + ddMax;

    document.getElementById("departure_date").setAttribute("min", minDate);
    document.getElementById("departure_date").setAttribute("max", maxDate);
});
</script>
@endsection
