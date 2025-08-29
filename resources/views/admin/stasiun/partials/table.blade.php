@extends('layouts.admin')

@section('title', 'Stasiun')
@section('styles')
<link rel="stylesheet" href="{{ asset('css/stasiun.css') }}">
@endsection
@section('page_title', 'Manajemen Stasiun')

@section('content')
<div class="table-container">
    <table class="table-dashboard">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Kota</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th style="width: 140px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stations as $station)
            <tr>
                <td>{{ $station->code }}</td>
                <td>{{ $station->name }}</td>
                <td>{{ $station->city }}</td>
                <td>{{ $station->lat }}</td>
                <td>{{ $station->lng }}</td>
                <td class="action-buttons">
                    <a href="{{ route('stasiun.edit', $station->id) }}" class="btn-edit" title="Edit">✏️</a>
                    <form action="{{ route('stasiun.destroy', $station->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete" onclick="return confirm('Yakin hapus stasiun ini?')" title="Hapus">🗑️</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination-container" style="margin-top: 15px;">
        {{ $stations->links() }}
    </div>
</div>
