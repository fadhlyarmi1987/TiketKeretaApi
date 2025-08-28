@extends('layouts.admin')

@section('title', 'Daftar Kereta')
@section('page_title', 'Daftar Kereta')

@section('content')
<div class="main-section">
    <div class="section-header">
        <h3>Daftar Kereta</h3>
        <a href="{{ route('kereta.create') }}" class="btn-primary">➕ Tambah Kereta</a>
    </div>

    <div class="table-container">
        <table class="table-dashboard">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Jumlah Gerbong</th>
                    <th style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kereta as $k)
                <tr>
                    <td>{{ $k->code }}</td>
                    <td>{{ $k->name }}</td>
                    <td>{{ $k->service_class }}</td>
                    <td>{{ $k->carriage_count }}</td>
                    <td class="action-buttons">
                        <a href="{{ route('kereta.edit', $k->id) }}" class="btn-edit" title="Edit">✏️</a>
                        <form action="{{ route('kereta.destroy', $k->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete" title="Delete">🗑️</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
