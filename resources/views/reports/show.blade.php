@extends('layouts.app')

@section('title', 'Detail Laporan - Sistem Karhutla')

@section('content')
<style>
    .detail-card {
        border: 1px solid #ccc;
        padding: 20px;
        background: #fff;
        margin-bottom: 20px;
    }
    .detail-row {
        display: flex;
        border-bottom: 1px solid #eee;
        padding: 8px 0;
        font-size: 13px;
    }
    .detail-label {
        width: 180px;
        font-weight: bold;
        color: #555;
    }
    .detail-value {
        flex: 1;
        color: #111;
    }
    .form-box {
        border: 1px solid #999;
        padding: 16px;
        background: #fafafa;
        margin-top: 20px;
    }
    select, textarea {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        font-size: 13px;
        box-sizing: border-box;
        margin-top: 4px;
        font-family: inherit;
    }
    .btn-action {
        padding: 6px 14px;
        background: #222;
        color: #fff;
        border: 1px solid #222;
        font-size: 12px;
        cursor: pointer;
    }
    .btn-action:hover {
        background: #000;
    }
    .btn-back {
        display: inline-block;
        padding: 6px 12px;
        border: 1px solid #ccc;
        color: #333;
        text-decoration: none;
        font-size: 12px;
        margin-bottom: 16px;
    }
    .btn-back:hover {
        background: #f0f0f0;
    }
</style>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
    <a href="{{ route('dashboard') }}" class="btn-back" style="margin-bottom: 0;">&larr; Kembali ke Dashboard</a>
    @if (auth()->user()->role === 'pelapor' && $report->user_id === auth()->id() && $report->status === 'menunggu_tinjauan')
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('reports.edit', $report->id) }}" style="padding: 6px 12px; border: 1px solid #333; background: #fff; color: #333; text-decoration: none; font-size: 12px;">Edit Laporan</a>
            <form action="{{ route('reports.destroy', $report->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan/menghapus laporan ini?');" style="margin: 0;">
                @csrf
                @method('DELETE')
                <button type="submit" style="padding: 6px 12px; border: 1px solid #900; background: #fff; color: #900; font-size: 12px; cursor: pointer;">Hapus</button>
            </form>
        </div>
    @endif
</div>

@if ($errors->any())
    <div style="border: 1px solid #999; background: #fff0f0; color: #900; padding: 10px; font-size: 13px; margin-bottom: 16px;">
        {{ $errors->first() }}
    </div>
@endif

<div class="detail-card">
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ccc; padding-bottom: 10px; margin-bottom: 12px;">
        <h2 style="margin: 0; font-size: 18px;">{{ $report->judul }}</h2>
        <span style="border: 1px solid #666; padding: 3px 8px; font-size: 11px; text-transform: uppercase;">
            {{ str_replace('_', ' ', $report->status) }}
        </span>
    </div>

    <div class="detail-row">
        <div class="detail-label">Pelapor</div>
        <div class="detail-value">{{ $report->user->nama_lengkap }} ({{ $report->user->username }})</div>
    </div>

    <div class="detail-row">
        <div class="detail-label">Waktu Kejadian</div>
        <div class="detail-value">{{ \Carbon\Carbon::parse($report->waktu_kejadian)->format('d F Y - H:i') }} WIB</div>
    </div>

    <div class="detail-row">
        <div class="detail-label">Area / Lokasi</div>
        <div class="detail-value">
            {{ $report->area->nama_area }} 
            (Kerawanan: <b>{{ ucfirst($report->area->tingkat_kerawanan) }}</b>)
            <div style="font-size: 11px; color: #666; margin-top: 2px;">{{ $report->area->deskripsi }}</div>
        </div>
    </div>

    <div class="detail-row">
        <div class="detail-label">Titik Koordinat</div>
        <div class="detail-value">
            {{ $report->latitude }}, {{ $report->longitude }}
            <a href="https://www.google.com/maps?q={{ $report->latitude }},{{ $report->longitude }}" target="_blank" style="margin-left: 8px; font-size: 11px; color: #0066cc; text-decoration: underline;">[Buka di Peta]</a>
        </div>
    </div>

    <div class="detail-row">
        <div class="detail-label">Kondisi Lapangan</div>
        <div class="detail-value" style="white-space: pre-line;">{{ $report->deskripsi_kondisi }}</div>
    </div>

    <div class="detail-row">
        <div class="detail-label">Foto Bukti</div>
        <div class="detail-value">
            @if ($report->evidence_path)
                <div>
                    <img src="{{ asset('storage/' . $report->evidence_path) }}" alt="Foto Bukti" style="max-width: 350px; border: 1px solid #ccc; display: block; margin-bottom: 6px;">
                    <a href="{{ asset('storage/' . $report->evidence_path) }}" target="_blank" style="font-size: 11px; color: #0066cc;">[Buka Ukuran Penuh]</a>
                </div>
            @else
                <span style="color: #888;">Tidak ada lampiran foto.</span>
            @endif
        </div>
    </div>

    <div class="detail-row">
        <div class="detail-label">Catatan Relawan</div>
        <div class="detail-value">
            @if ($report->catatan_relawan)
                <div style="background: #f7f7f7; border: 1px solid #ddd; padding: 8px; font-size: 12px;">
                    {{ $report->catatan_relawan }}
                </div>
            @else
                <span style="color: #888;">Belum ada catatan penanganan dari petugas/relawan.</span>
            @endif
        </div>
    </div>
</div>

@if (auth()->user()->role !== 'pelapor')
    @if (in_array($report->status, ['selesai', 'ditolak']))
        <div style="border: 1px solid #ccc; background: #f9f9f9; padding: 14px; font-size: 13px; color: #555; margin-top: 20px;">
            Status laporan ini bersifat final (<b>{{ str_replace('_', ' ', $report->status) }}</b>) dan terkunci dari perubahan lebih lanjut.
        </div>
    @else
        <div class="form-box">
            <h3 style="margin-top: 0; font-size: 14px;">Tindakan Petugas: Pembaruan Status & Catatan</h3>

            <form action="{{ route('reports.status.update', $report->id) }}" method="POST">
                @csrf
                @method('PATCH')

                <div style="margin-bottom: 12px;">
                    <label style="font-size: 12px; font-weight: bold;">Status Laporan</label>
                    <select name="status" required>
                        <option value="menunggu_tinjauan" {{ $report->status === 'menunggu_tinjauan' ? 'selected' : '' }}>Menunggu Tinjauan</option>
                        <option value="terverifikasi" {{ $report->status === 'terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
                        <option value="ditolak" {{ $report->status === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        <option value="diteruskan" {{ $report->status === 'diteruskan' ? 'selected' : '' }}>Diteruskan</option>
                        <option value="selesai" {{ $report->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>

                <div style="margin-bottom: 12px;">
                    <label style="font-size: 12px; font-weight: bold;">Catatan Lapangan / Keterangan Penanganan</label>
                    <textarea name="catatan_relawan" rows="3" placeholder="Masukkan keterangan pengecekan darat atau penanganan tim...">{{ old('catatan_relawan', $report->catatan_relawan) }}</textarea>
                </div>

                <button type="submit" class="btn-action">Simpan Perubahan Status</button>
            </form>
        </div>
    @endif
@endif
@endsection
