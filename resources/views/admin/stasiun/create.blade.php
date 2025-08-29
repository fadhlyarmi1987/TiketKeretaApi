@extends('layouts.admin')

@section('title', 'Tambah Stasiun')
@section('page_title', 'Tambah Data Stasiun')

@section('content')

<link href="{{ asset('css/create-station.css') }}" rel="stylesheet">
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card station-card shadow-sm border-0">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-building"></i> Tambah Data Stasiun
                    </h5>
                </div>
                <div class="card-body station-form">
                    <form method="POST" action="{{ route('stasiun.store') }}">
                        @csrf
                        
                        {{-- Kode Stasiun --}}
                        <div class="mb-3">
                            <label class="form-label">Kode Stasiun</label>
                            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" 
                                   value="{{ old('code') }}" placeholder="Contoh: SBY" required>
                            @error('code') 
                                <div class="invalid-feedback">{{ $message }}</div> 
                            @enderror
                        </div>

                        {{-- Nama Stasiun --}}
                        <div class="mb-3">
                            <label class="form-label">Nama Stasiun</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}" placeholder="Contoh: Stasiun Surabaya Gubeng" required>
                            @error('name') 
                                <div class="invalid-feedback">{{ $message }}</div> 
                            @enderror
                        </div>

                        {{-- Kota --}}
                        <div class="mb-3">
                            <label class="form-label">Kota</label>
                            <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" 
                                   value="{{ old('city') }}" placeholder="Contoh: Surabaya" required>
                            @error('city') 
                                <div class="invalid-feedback">{{ $message }}</div> 
                            @enderror
                        </div>

                        {{-- Latitude & Longitude --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Latitude</label>
                                <input type="text" name="lat" class="form-control" value="{{ old('lat') }}" placeholder="-7.2575">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Longitude</label>
                                <input type="text" name="lng" class="form-control" value="{{ old('lng') }}" placeholder="112.7521">
                            </div>
                        </div>

                        {{-- Tombol --}}
                        <div class="form-actions">
                            <a href="{{ route('stasiun.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection