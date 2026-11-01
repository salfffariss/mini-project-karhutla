<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Area;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Report::with(['user', 'area'])->latest();

        if ($user->role === 'pelapor') {
            $query->where('user_id', $user->id);
        }

        $allowedStatuses = ['menunggu_tinjauan', 'terverifikasi', 'ditolak', 'diteruskan', 'selesai'];
        if ($request->filled('status') && in_array($request->status, $allowedStatuses)) {
            $query->where('status', $request->status);
        }

        if ($request->filled('area_id') && is_numeric($request->area_id)) {
            $query->where('area_id', $request->area_id);
        }

        $reports = $query->get();

        $statsQuery = Report::query();
        if ($user->role === 'pelapor') {
            $statsQuery->where('user_id', $user->id);
        }
        $allUserReports = $statsQuery->get();

        $stats = [
            'total'             => $allUserReports->count(),
            'menunggu_tinjauan' => $allUserReports->where('status', 'menunggu_tinjauan')->count(),
            'terverifikasi'     => $allUserReports->where('status', 'terverifikasi')->count(),
            'selesai'           => $allUserReports->where('status', 'selesai')->count(),
        ];

        $areas = Area::all();

        return view('dashboard', compact('reports', 'stats', 'areas'));
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
            'evidence'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
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

    public function show($id)
    {
        $report = Report::with(['user', 'area'])->findOrFail($id);

        if (auth()->user()->role === 'pelapor' && $report->user_id !== auth()->id()) {
            abort(403);
        }

        return view('reports.show', compact('report'));
    }

    public function updateStatus(Request $request, $id)
    {
        if (auth()->user()->role === 'pelapor') {
            abort(403);
        }

        $report = Report::findOrFail($id);

        if (in_array($report->status, ['selesai', 'ditolak'])) {
            return back()->withErrors(['status' => 'Laporan berstatus final (' . $report->status . ') terkunci dan tidak dapat diubah lagi.']);
        }

        $validated = $request->validate([
            'status'          => 'required|in:menunggu_tinjauan,terverifikasi,ditolak,diteruskan,selesai',
            'catatan_relawan' => 'nullable|string',
        ]);

        $report->update([
            'status'          => $validated['status'],
            'catatan_relawan' => $validated['catatan_relawan'],
        ]);

        return redirect()->route('reports.show', $report->id)->with('success', 'Status dan catatan laporan berhasil diperbarui.');
    }

    public function edit($id)
    {
        $report = Report::findOrFail($id);

        if (auth()->user()->role !== 'pelapor' || $report->user_id !== auth()->id()) {
            abort(403);
        }

        if ($report->status !== 'menunggu_tinjauan') {
            return redirect()->route('reports.show', $report->id)->withErrors(['status' => 'Laporan yang telah ditinjau tidak dapat diubah lagi.']);
        }

        $areas = Area::all();
        return view('reports.edit', compact('report', 'areas'));
    }

    public function update(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        if (auth()->user()->role !== 'pelapor' || $report->user_id !== auth()->id()) {
            abort(403);
        }

        if ($report->status !== 'menunggu_tinjauan') {
            return redirect()->route('reports.show', $report->id)->withErrors(['status' => 'Laporan yang telah ditinjau tidak dapat diubah lagi.']);
        }

        $validated = $request->validate([
            'area_id'           => 'required|exists:areas,id',
            'judul'             => 'required|string|max:255',
            'deskripsi_kondisi' => 'required|string',
            'waktu_kejadian'    => 'required|date',
            'latitude'          => 'required|numeric|between:-90,90',
            'longitude'         => 'required|numeric|between:-180,180',
            'evidence'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('evidence')) {
            $validated['evidence_path'] = $request->file('evidence')->store('evidence', 'public');
        }

        $report->update($validated);

        return redirect()->route('reports.show', $report->id)->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $report = Report::findOrFail($id);

        if (auth()->user()->role !== 'pelapor' || $report->user_id !== auth()->id()) {
            abort(403);
        }

        if ($report->status !== 'menunggu_tinjauan') {
            return redirect()->route('reports.show', $report->id)->withErrors(['status' => 'Laporan yang telah ditinjau tidak dapat dihapus.']);
        }

        $report->delete();

        return redirect()->route('dashboard')->with('success', 'Laporan berhasil dihapus.');
    }
}