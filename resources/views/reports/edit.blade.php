@extends('layouts.app')

@section('title', 'Edit Laporan - Sistem Karhutla')

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
        Edit Laporan Kejadian Karhutla
    </h2>

    <form action="{{ route('reports.update', $report->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="judul">Judul Singkat Kejadian *</label>
            <input type="text" id="judul" name="judul" value="{{ old('judul', $report->judul) }}" required>
            @error('judul') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="area_id">Lokasi Area Rawan *</label>
            <select id="area_id" name="area_id" required>
                <option value="">-- Pilih Area Kerawanan --</option>
                @foreach ($areas as $area)
                    <option value="{{ $area->id }}" {{ old('area_id', $report->area_id) == $area->id ? 'selected' : '' }}>
                        {{ $area->nama_area }} (Kerawanan: {{ ucfirst($area->tingkat_kerawanan) }})
                    </option>
                @endforeach
            </select>
            @error('area_id') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="waktu_kejadian">Waktu Kejadian Terlihat *</label>
            <input type="datetime-local" id="waktu_kejadian" name="waktu_kejadian" value="{{ old('waktu_kejadian', \Carbon\Carbon::parse($report->waktu_kejadian)->format('Y-m-d\TH:i')) }}" required>
            @error('waktu_kejadian') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <div class="row-2">
            <div class="col form-group">
                <label for="latitude">Latitude (Garis Lintang) *</label>
                <input type="number" step="any" id="latitude" name="latitude" value="{{ old('latitude', $report->latitude) }}" required>
                @error('latitude') <div class="error-text">{{ $message }}</div> @enderror
            </div>
            <div class="col form-group">
                <label for="longitude">Longitude (Garis Bujur) *</label>
                <input type="number" step="any" id="longitude" name="longitude" value="{{ old('longitude', $report->longitude) }}" required>
                @error('longitude') <div class="error-text">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="deskripsi_kondisi">Deskripsi Kondisi Lapangan *</label>
            <textarea id="deskripsi_kondisi" name="deskripsi_kondisi" rows="4" required>{{ old('deskripsi_kondisi', $report->deskripsi_kondisi) }}</textarea>
            @error('deskripsi_kondisi') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="evidence">Ganti Foto Bukti (Opsional, JPG/PNG max 2MB)</label>
            @if ($report->evidence_path)
                <div style="font-size: 11px; color: #555; margin-bottom: 4px;">
                    Foto saat ini: <a href="{{ asset('storage/' . $report->evidence_path) }}" target="_blank">[Lihat Foto]</a>
                </div>
            @endif
            <input type="file" id="evidence" name="evidence" accept="image/*">
            @error('evidence') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <div style="margin-top: 20px; border-top: 1px solid #eee; padding-top: 14px;">
            <button type="submit" class="btn-submit">Simpan Perubahan</button>
            <a href="{{ route('reports.show', $report->id) }}" class="btn-cancel">Batal</a>
        </div>
    </form>
</div>
@endsection
