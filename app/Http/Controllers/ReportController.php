<?php

namespace App\Http\Controllers;

use App\Models\Supermarket;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    // Menampilkan semua laporan untuk supermarket tertentu
    public function index($supermarketId)
    {
        $supermarket = Supermarket::findOrFail($supermarketId);
        $reports = $supermarket->reports()->latest()->get();

        return view('reports.index', compact('supermarket', 'reports'));
    }

    // Membuat laporan baru
    public function store(Request $request, $supermarketId)
    {
        $this->validate($request, [
            'message' => 'required|string|max:500'
        ]);

        $supermarket = Supermarket::findOrFail($supermarketId);

        $report = Report::create([
            'user_id' => Auth::id(),
            'supermarket_id' => $supermarket->id,
            'message' => $request->message
        ]);

        return redirect()->back()->with('success', 'Laporan berhasil dibuat');
    }

    // Menghapus laporan (hanya oleh pemilik laporan)
    public function destroy($id)
    {
        $report = Report::findOrFail($id);

        // Pastikan hanya pemilik laporan yang bisa menghapus
        if ($report->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin');
        }

        $report->delete();

        return redirect()->back()->with('success', 'Laporan berhasil dihapus');
    }
}
