@extends('layouts.app')

@section('title', 'Buat Laporan Baru - Sistem Karhutla')

@section('content')
<style>
    .form-box {
        border: 1px solid #ccc;
        padding: 20px;
        background-color: #fff;
        max-width: 650px;
        margin: 0 auto;
    }
    .form-group {
        margin-bottom: 14px;
    }
    label {
        display: block;
        font-size: 13px;
        font-weight: bold;
        margin-bottom: 4px;
    }
    input[type="text"],
    input[type="datetime-local"],
    input[type="number"],
    select,
    textarea {
        width: 100%;
        padding: 8px;
        box-sizing: border-box;
        border: 1px solid #ccc;
        font-size: 13px;
        font-family: inherit;
    }
    .row-2 {
        display: flex;
        gap: 12px;
    }
    .row-2 .col {
        flex: 1;
    }
    .btn-submit {
        padding: 8px 16px;
        background-color: #222;
        color: #fff;
        border: 1px solid #222;
        font-size: 13px;
        cursor: pointer;
    }
    .btn-submit:hover {
        background-color: #000;
    }
    .btn-cancel {
        display: inline-block;
        padding: 8px 14px;
        border: 1px solid #ccc;
        color: #333;
        text-decoration: none;
        font-size: 13px;
        margin-left: 8px;
    }
    .btn-cancel:hover {
        background-color: #f2f2f2;
    }
    .error-text {
        color: #b00;
        font-size: 11px;
        margin-top: 2px;
    }
</style>

<div class="form-box">
    <h2 style="margin-top: 0; font-size: 18px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
        Form Pelaporan Kejadian Karhutla
    </h2>

    <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="judul">Judul Singkat Kejadian *</label>
            <input type="text" id="judul" name="judul" value="{{ old('judul') }}" placeholder="Contoh: Titik Asap Pekat di Tepi Jalan Sawit" required>
            @error('judul') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="area_id">Lokasi Area Rawan *</label>
            <select id="area_id" name="area_id" required>
                <option value="">-- Pilih Area Kerawanan --</option>
                @foreach ($areas as $area)
                    <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>
                        {{ $area->nama_area }} (Kerawanan: {{ ucfirst($area->tingkat_kerawanan) }})
                    </option>
                @endforeach
            </select>
            @error('area_id') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="waktu_kejadian">Waktu Kejadian Terlihat *</label>
            <input type="datetime-local" id="waktu_kejadian" name="waktu_kejadian" value="{{ old('waktu_kejadian', now()->format('Y-m-d\TH:i')) }}" required>
            @error('waktu_kejadian') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <div class="row-2">
            <div class="col form-group">
                <label for="latitude">Latitude (Garis Lintang) *</label>
                <input type="number" step="any" id="latitude" name="latitude" value="{{ old('latitude', '-0.1234567') }}" required>
                @error('latitude') <div class="error-text">{{ $message }}</div> @enderror
            </div>
            <div class="col form-group">
                <label for="longitude">Longitude (Garis Bujur) *</label>
                <input type="number" step="any" id="longitude" name="longitude" value="{{ old('longitude', '101.4567890') }}" required>
                @error('longitude') <div class="error-text">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="deskripsi_kondisi">Deskripsi Kondisi Lapangan *</label>
            <textarea id="deskripsi_kondisi" name="deskripsi_kondisi" rows="4" placeholder="Jelaskan perkiraan luas api, warna asap, atau arah angin..." required>{{ old('deskripsi_kondisi') }}</textarea>
            @error('deskripsi_kondisi') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="evidence">Foto Bukti Lapangan (Opsional, JPG/PNG max 2MB)</label>
            <input type="file" id="evidence" name="evidence" accept="image/*">
            @error('evidence') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <div style="margin-top: 20px; border-top: 1px solid #eee; padding-top: 14px;">
            <button type="submit" class="btn-submit">Kirim Laporan</button>
            <a href="{{ route('dashboard') }}" class="btn-cancel">Batal</a>
        </div>
    </form>
</div>
@endsection