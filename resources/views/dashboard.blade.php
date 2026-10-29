@extends('layouts.app')

@section('title', 'Dashboard - Sistem Karhutla')

@section('content')
    <h2 style="margin-top: 0; font-size: 18px;">Selamat Datang di Sistem Karhutla</h2>
    <p style="font-size: 13px; color: #555;">
        Anda login sebagai: <b>{{ auth()->user()->nama_lengkap }}</b> dengan peran <b>{{ auth()->user()->role }}</b>.
    </p>
    <div style="border: 1px solid #ddd; padding: 16px; margin-top: 16px; font-size: 13px; background: #fafafa;">
        Status sesi aktif
    </div>
@endsection