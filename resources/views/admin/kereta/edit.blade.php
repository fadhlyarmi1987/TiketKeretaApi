@extends('layouts.admin')

@section('title', 'Edit Kereta')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/edit.css') }}">
@endsection
@section('page_title', 'Edit Data Kereta')

@section('content')
<div class="form-container">
    <form method="POST" action="{{ route('kereta.update', $kereta->id) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="code">Kode Kereta</label>
            <input type="text" id="code" name="code" value="{{ old('code', $kereta->code) }}" required>
            @error('code') <small class="error">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label for="name">Nama Kereta</label>
            <input type="text" id="name" name="name" value="{{ old('name', $kereta->name) }}" required>
            @error('name') <small class="error">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label for="service_class">Kelas</label>
            <select id="service_class" name="service_class" required>
                <option value="">-- Pilih --</option>
                <option value="ECONOMY" {{ old('service_class', $kereta->service_class) == 'ECONOMY' ? 'selected' : '' }}>Ekonomi</option>
                <option value="BUSINESS" {{ old('service_class', $kereta->service_class) == 'BUSINESS' ? 'selected' : '' }}>Bisnis</option>
                <option value="EXECUTIVE" {{ old('service_class', $kereta->service_class) == 'EXECUTIVE' ? 'selected' : '' }}>Eksekutif</option>
            </select>
            @error('service_class') <small class="error">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label for="carriage_count">Jumlah Gerbong</label>
            <input type="number" id="carriage_count" name="carriage_count" value="{{ old('carriage_count', $kereta->carriage_count) }}" min="1" max="20" required>
            @error('carriage_count') <small class="error">{{ $message }}</small> @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">💾 Update</button>
            <a href="{{ route('kereta.index') }}" class="btn-secondary">⬅️ Batal</a>
        </div>
    </form>
</div>
@endsection
