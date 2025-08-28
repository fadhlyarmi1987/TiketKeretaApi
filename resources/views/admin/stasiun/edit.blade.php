@extends('layouts.admin')

@section('title', 'Edit Stasiun')
@section('page_title', 'Edit Data Stasiun')

@section('content')
  <form method="POST" action="{{ route('stasiun.update', $station->id) }}">
      @csrf
      @method('PUT')

      <div>
          <label>Kode Stasiun</label><br>
          <input type="text" name="code" value="{{ old('code', $station->code) }}" required>
          @error('code') <small style="color:red;">{{ $message }}</small> @enderror
      </div><br>

      <div>
          <label>Nama Stasiun</label><br>
          <input type="text" name="name" value="{{ old('name', $station->name) }}" required>
          @error('name') <small style="color:red;">{{ $message }}</small> @enderror
      </div><br>

      <div>
          <label>Kota</label><br>
          <input type="text" name="city" value="{{ old('city', $station->city) }}" required>
          @error('city') <small style="color:red;">{{ $message }}</small> @enderror
      </div><br>

      <div>
          <label>Latitude</label><br>
          <input type="text" name="lat" value="{{ old('lat', $station->lat) }}">
      </div><br>

      <div>
          <label>Longitude</label><br>
          <input type="text" name="lng" value="{{ old('lng', $station->lng) }}">
      </div><br>

      <button type="submit">Update</button>
  </form>
@endsection
