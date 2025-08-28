@extends('layouts.admin')

@section('title', 'Tambah Stasiun')
@section('page_title', 'Tambah Data Stasiun')

@section('content')
  <form method="POST" action="{{ route('stasiun.store') }}">
      @csrf
      <div>
          <label>Kode Stasiun</label><br>
          <input type="text" name="code" value="{{ old('code') }}" required>
          @error('code') <small style="color:red;">{{ $message }}</small> @enderror
      </div><br>

      <div>
          <label>Nama Stasiun</label><br>
          <input type="text" name="name" value="{{ old('name') }}" required>
          @error('name') <small style="color:red;">{{ $message }}</small> @enderror
      </div><br>

      <div>
          <label>Kota</label><br>
          <input type="text" name="city" value="{{ old('city') }}" required>
          @error('city') <small style="color:red;">{{ $message }}</small> @enderror
      </div><br>

      <div>
          <label>Latitude</label><br>
          <input type="text" name="lat" value="{{ old('lat') }}">
      </div><br>

      <div>
          <label>Longitude</label><br>
          <input type="text" name="lng" value="{{ old('lng') }}">
      </div><br>

      <button type="submit">Simpan</button>
  </form>
@endsection
