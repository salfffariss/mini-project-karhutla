@extends('layouts.app')

@section('title', 'Dashboard - Sistem Karhutla')

@section('content')
<style>
    .header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #ccc;
        padding-bottom: 12px;
        margin-bottom: 20px;
    }
    .header-section h2 {
        margin: 0;
        font-size: 18px;
    }
    .btn-create {
        display: inline-block;
        padding: 6px 12px;
        background-color: #222;
        color: #fff;
        text-decoration: none;
        font-size: 12px;
        border: 1px solid #222;
    }
    .btn-create:hover {
        background-color: #000;
    }
    .stats-container {
        display: flex;
        gap: 12px;
        margin-bottom: 24px;
    }
    .stat-box {
        flex: 1;
        border: 1px solid #ccc;
        padding: 10px;
        background-color: #fafafa;
    }
    .stat-box .title {
        font-size: 11px;
        color: #666;
        margin: 0;
    }
    .stat-box .count {
        font-size: 20px;
        font-weight: bold;
        margin: 6px 0 0 0;
    }
    /* Tabel Data */
    table.data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        margin-top: 10px;
    }
    table.data-table th, table.data-table td {
        border: 1px solid #ccc;
        padding: 8px 10px;
        text-align: left;
    }
    table.data-table th {
        background-color: #f2f2f2;
    }
    table.data-table tr:hover {
        background-color: #fbfbfb;
    }
</style>

<div class="header-section">
    <div>
        <h2>Dashboard Laporan</h2>
        <small style="color: #666;">
            @if (auth()->user()->role === 'pelapor')
                Daftar laporan kebakaran yang Anda kirimkan.
            @else
                Daftar seluruh laporan karhutla dari masyarakat.
            @endif
        </small>
    </div>

    @if (auth()->user()->role === 'pelapor')
        <a href="{{ route('reports.create') }}" class="btn-create">+ Buat Laporan Baru</a>
    @endif
</div>

<div class="stats-container">
    <div class="stat-box">
        <p class="title">Total Laporan</p>
        <p class="count">{{ $stats['total'] }}</p>
    </div>
    <div class="stat-box">
        <p class="title">Menunggu Tinjauan</p>
        <p class="count">{{ $stats['menunggu_tinjauan'] }}</p>
    </div>
    <div class="stat-box">
        <p class="title">Terverifikasi</p>
        <p class="count">{{ $stats['terverifikasi'] }}</p>
    </div>
    <div class="stat-box">
        <p class="title">Selesai</p>
        <p class="count">{{ $stats['selesai'] }}</p>
    </div>
</div>

<h3 style="font-size: 14px; margin-bottom: 8px;">Daftar Kejadian ({{ $reports->count() }} Data)</h3>

@if ($reports->isEmpty())
    <div style="border: 1px solid #ccc; padding: 20px; text-align: center; color: #777; font-size: 13px;">
        Belum ada data laporan yang tercatat.
    </div>
@else
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 130px;">Waktu</th>
                <th>Judul & Kondisi</th>
                <th>Lokasi Area</th>
                @if (auth()->user()->role !== 'pelapor')
                    <th>Pelapor</th>
                @endif
                <th style="width: 140px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $r)
                <tr>
                    <td style="color: #555;">
                        {{ \Carbon\Carbon::parse($r->waktu_kejadian)->format('d/m/Y H:i') }}
                    </td>
                    <td>
                        <b>{{ $r->judul }}</b>
                        <div style="font-size: 11px; color: #555; margin-top: 3px;">
                            {{ $r->deskripsi_kondisi }}
                        </div>
                    </td>
                    <td>
                        {{ $r->area->nama_area }}
                        <div style="font-size: 11px; color: #666;">
                            Kerawanan: {{ ucfirst($r->area->tingkat_kerawanan) }}
                        </div>
                    </td>
                    @if (auth()->user()->role !== 'pelapor')
                        <td>{{ $r->user->nama_lengkap }}</td>
                    @endif
                    <td>
                        <span style="border: 1px solid #888; padding: 2px 6px; font-size: 11px; text-transform: uppercase;">
                            {{ str_replace('_', ' ', $r->status) }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
@endsection