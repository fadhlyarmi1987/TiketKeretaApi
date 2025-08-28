@extends('layouts.admin')

@section('title', 'Tambah Kereta')
@section('page_title', 'Tambah Data Kereta')

@section('content')
  <form method="POST" action="{{ route('kereta.store') }}">
      @csrf
      <div>
          <label>Kode Kereta</label><br>
          <input type="text" name="code" value="{{ old('code') }}" required>
          @error('code') <small style="color:red;">{{ $message }}</small> @enderror
      </div><br>

      <div>
          <label>Nama Kereta</label><br>
          <input type="text" name="name" value="{{ old('name') }}" required>
          @error('name') <small style="color:red;">{{ $message }}</small> @enderror
      </div><br>

      <div>
          <label>Kelas</label><br>
          <select name="service_class" required>
              <option value="">-- Pilih --</option>
              <option value="ECONOMY">Ekonomi</option>
              <option value="BUSINESS">Bisnis</option>
              <option value="EXECUTIVE">Eksekutif</option>
          </select>
          @error('service_class') <small style="color:red;">{{ $message }}</small> @enderror
      </div><br>

      <div>
          <label>Jumlah Gerbong</label><br>
          <input type="number" name="carriage_count" value="{{ old('carriage_count') }}" min="1" max="20" required>
          @error('carriage_count') <small style="color:red;">{{ $message }}</small> @enderror
      </div><br>

      <div>
    <label>Stasiun Tujuan</label><br>
    <select name="destination_station_id" required>
        <option value="">-- Pilih Stasiun --</option>
        @foreach($stations as $station)
            <option value="{{ $station->id }}">
                {{ $station->name }} ({{ $station->city }})
            </option>
        @endforeach
    </select>
    @error('destination_station_id') <small style="color:red;">{{ $message }}</small> @enderror
</div><br>


      <button type="submit">Simpan</button>
  </form>
@endsection
