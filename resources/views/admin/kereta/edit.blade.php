@extends('layouts.admin')

@section('title', 'Edit Kereta')
@section('page_title', 'Edit Data Kereta')

@section('content')
  <form method="POST" action="{{ route('kereta.update', $kereta->id) }}">
      @csrf
      @method('PUT')

      <div>
          <label>Kode Kereta</label><br>
          <input type="text" name="code" value="{{ old('code', $kereta->code) }}" required>
          @error('code') <small style="color:red;">{{ $message }}</small> @enderror
      </div><br>

      <div>
          <label>Nama Kereta</label><br>
          <input type="text" name="name" value="{{ old('name', $kereta->name) }}" required>
          @error('name') <small style="color:red;">{{ $message }}</small> @enderror
      </div><br>

      <div>
          <label>Kelas</label><br>
          <select name="service_class" required>
              <option value="">-- Pilih --</option>
              <option value="ECONOMY" {{ old('service_class', $kereta->service_class) == 'ECONOMY' ? 'selected' : '' }}>Ekonomi</option>
              <option value="BUSINESS" {{ old('service_class', $kereta->service_class) == 'BUSINESS' ? 'selected' : '' }}>Bisnis</option>
              <option value="EXECUTIVE" {{ old('service_class', $kereta->service_class) == 'EXECUTIVE' ? 'selected' : '' }}>Eksekutif</option>
          </select>
          @error('service_class') <small style="color:red;">{{ $message }}</small> @enderror
      </div><br>

      <div>
          <label>Jumlah Gerbong</label><br>
          <input type="number" name="carriage_count" value="{{ old('carriage_count', $kereta->carriage_count) }}" min="1" max="20" required>
          @error('carriage_count') <small style="color:red;">{{ $message }}</small> @enderror
      </div><br>

      <button type="submit">Update</button>
  </form>
@endsection
