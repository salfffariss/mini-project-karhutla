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

    public function create()
    {
        if (auth()->user()->role !== 'pelapor') {
            abort(403, 'Hanya warga/pelapor yang dapat membuat laporan baru.');
        }
        $areas = Area::all();
        return view('reports.create', compact('areas'));
    }
     
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'pelapor') {
            abort(403);
        }
        $validated = $request->validate([
            'area_id'           => 'required|exists:areas,id',
            'judul'             => 'required|string|max:255',
            'deskripsi_kondisi' => 'required|string',
            'waktu_kejadian'    => 'required|date',
            'latitude'          => 'required|numeric|between:-90,90',
            'longitude'         => 'required|numeric|between:-180,180',
            'evidence'          => 'nullable|image|max:2048', // foto bukti opsional max 2MB
        ]);
        $evidencePath = null;
        if ($request->hasFile('evidence')) {
            $evidencePath = $request->file('evidence')->store('evidence', 'public');
        }
        Report::create([
            'user_id'           => auth()->id(),
            'area_id'           => $validated['area_id'],
            'judul'             => $validated['judul'],
            'deskripsi_kondisi' => $validated['deskripsi_kondisi'],
            'waktu_kejadian'    => $validated['waktu_kejadian'],
            'latitude'          => $validated['latitude'],
            'longitude'         => $validated['longitude'],
            'evidence_path'     => $evidencePath,
            'status'            => 'menunggu_tinjauan',
        ]);
        return redirect()->route('dashboard')->with('success', 'Laporan kejadian karhutla berhasil dikirim dan menunggu tinjauan relawan.');
    }
}