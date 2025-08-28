@extends('layouts.admin')

@section('title', 'Stasiun')
@section('page_title', 'Manajemen Stasiun')

@section('content')
  <div>
      <a href="{{ route('stasiun.create') }}">+ Tambah Stasiun</a>
  </div>
  <br>

  @if(session('success'))
      <div style="color: green">{{ session('success') }}</div>
  @endif

  <table border="1" cellpadding="8">
      <thead>
          <tr>
              <th>Kode</th>
              <th>Nama</th>
              <th>Kota</th>
              <th>Latitude</th>
              <th>Longitude</th>
              <th>Aksi</th>
          </tr>
      </thead>
      <tbody>
          @foreach($stations as $station)
              <tr>
                  <td>{{ $station->code }}</td>
                  <td>{{ $station->name }}</td>
                  <td>{{ $station->city }}</td>
                  <td>{{ $station->lat }}</td>
                  <td>{{ $station->lng }}</td>
                  <td>
                      <a href="{{ route('stasiun.edit', $station->id) }}">Edit</a> |
                      <form action="{{ route('stasiun.destroy', $station->id) }}" method="POST" style="display:inline;">
                          @csrf
                          @method('DELETE')
                          <button type="submit" onclick="return confirm('Yakin hapus stasiun ini?')">Hapus</button>
                      </form>
                  </td>
              </tr>
          @endforeach
      </tbody>
  </table>
@endsection
