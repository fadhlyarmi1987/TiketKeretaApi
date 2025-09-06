@extends('layouts.admin')

@section('title', 'Detail Penumpang')
@section('page_title', 'Isi Data Penumpang')

@section('content')
<div class="container my-4">
    <h2>👤 Isi Data Penumpang</h2>

    <form action="{{ route('booking.book', $trip->id) }}" method="POST" id="bookingForm">
        @csrf
        <input type="hidden" name="departure_date" value="{{ session('booking_data.departure_date') }}">

        {{-- Penumpang utama --}}
        @php $user = auth()->user(); @endphp
        <div class="penumpang mb-4 border p-3 rounded">
            <h5>Penumpang Utama</h5>
            <div class="mb-3">
                <label>Nama Penumpang</label>
                <input type="text" name="passengers[0][name]" class="form-control"
                       value="{{ old('name', $user->name ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label>NIK</label>
                <input type="text" name="passengers[0][nik]" class="form-control"
                       value="{{ old('nik', $user->nik ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label>Jenis Kelamin</label>
                <select name="passengers[0][jenis_kelamin]" class="form-control" required>
                    <option value="">-- Pilih --</option>
                    <option value="Laki-laki" {{ old('jenis_kelamin', $user->jenis_kelamin ?? '') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ old('jenis_kelamin', $user->jenis_kelamin ?? '') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Tanggal Lahir</label>
                <input type="date" name="passengers[0][tanggal_lahir]" class="form-control"
                       value="{{ old('tanggal_lahir', $user->tanggal_lahir ?? '') }}" required>
            </div>
        </div>

        {{-- Container untuk penumpang tambahan --}}
        <div id="extraPassengers"></div>

        <button type="button" id="addPassengerBtn" class="btn btn-secondary mb-3">➕ Tambah Penumpang</button>
        <br>
        <button type="submit" class="btn btn-success">Lanjutkan Booking</button>
    </form>
</div>

{{-- Script --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    const addBtn = document.getElementById("addPassengerBtn");
    const container = document.getElementById("extraPassengers");
    let count = 0;
    const maxPassengers = 3;

    addBtn.addEventListener("click", function () {
        if (count >= maxPassengers) {
            alert("Maksimal hanya bisa menambah 3 penumpang.");
            return;
        }
        count++;
        const index = count; // index mulai dari 1

        const wrapper = document.createElement("div");
        wrapper.classList.add("penumpang", "mb-4", "border", "p-3", "rounded");
        wrapper.innerHTML = `
            <h5>Penumpang Tambahan ${index}</h5>
            <div class="mb-3">
                <label>Nama Penumpang</label>
                <input type="text" name="passengers[${index}][name]" class="form-control" placeholder="Nama lengkap" required>
            </div>
            <div class="mb-3">
                <label>NIK</label>
                <input type="text" name="passengers[${index}][nik]" class="form-control" placeholder="Nomor Induk Kependudukan" required>
            </div>
            <div class="mb-3">
                <label>Jenis Kelamin</label>
                <select name="passengers[${index}][jenis_kelamin]" class="form-control" required>
                    <option value="">-- Pilih --</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Tanggal Lahir</label>
                <input type="date" name="passengers[${index}][tanggal_lahir]" class="form-control" required>
            </div>
            <button type="button" class="btn btn-danger btn-sm remove-passenger">❌ Hapus</button>
        `;

        container.appendChild(wrapper);

        // Tombol hapus
        wrapper.querySelector(".remove-passenger").addEventListener("click", function () {
            wrapper.remove();
            count--;
        });
    });
});
</script>
@endsection
