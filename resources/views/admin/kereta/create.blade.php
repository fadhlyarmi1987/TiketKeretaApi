@extends('layouts.admin')

@section('title', 'Tambah Kereta')
@section('page_title', 'Tambah Data Kereta')

{{-- Panggil CSS khusus untuk form --}}
@section('styles')
<link rel="stylesheet" href="{{ asset('css/forms.css') }}">
@endsection


@section('content')
<div class="form-container">
    <form method="POST" action="{{ route('kereta.store') }}">
        @csrf

        <div class="form-group">
            <label>Kode Kereta</label>
            <input type="text" name="code" value="{{ old('code') }}" required>
            @error('code') <small class="error">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label>Nama Kereta</label>
            <input type="text" name="name" value="{{ old('name') }}" required>
            @error('name') <small class="error">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label>Kelas</label>
            <select name="service_class" required>
                <option value="">-- Pilih --</option>
                <option value="ECONOMY">Ekonomi</option>
                <option value="BUSINESS">Bisnis</option>
                <option value="EXECUTIVE">Eksekutif</option>
            </select>
            @error('service_class') <small class="error">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label>Jumlah Gerbong</label>
            <input type="number" name="carriage_count" value="{{ old('carriage_count') }}" min="1" max="20" required>
            @error('carriage_count') <small class="error">{{ $message }}</small> @enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('kereta.index') }}" class="btn btn-secondary">⬅️ Kembali</a>
            <button type="submit" class="btn btn-submit">💾 Simpan Trip</button>
        </div>

    </form>
</div>
@endsection