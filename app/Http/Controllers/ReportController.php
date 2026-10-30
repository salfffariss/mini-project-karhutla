<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Area;

class ReportController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Ambil data laporan beserta relasi user dan areanya (eager loading)
        $query = Report::with(['user', 'area'])->latest();

        // Aturan Hak Akses:
        // Jika Pelapor: hanya tampilkan laporan miliknya sendiri.
        // Jika Relawan / Koordinator: tampilkan semua laporan.
        if ($user->role === 'pelapor') {
            $query->where('user_id', $user->id);
        }

        $reports = $query->get();

        // Ringkasan angka statistik sederhana
        $stats = [
            'total'             => $reports->count(),
            'menunggu_tinjauan' => $reports->where('status', 'menunggu_tinjauan')->count(),
            'terverifikasi'     => $reports->where('status', 'terverifikasi')->count(),
            'selesai'           => $reports->where('status', 'selesai')->count(),
        ];

        return view('dashboard', compact('reports', 'stats'));
    }
}