<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\HasilMabac;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\Staff;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index(Request $request)
    {
        $periodeList    = Penilaian::distinct()->pluck('periode')->sortDesc()->values();
        $periode        = $request->get('periode', $periodeList->first() ?? '2024-1');

        $totalDivisi    = Divisi::where('aktif', true)->count();
        $totalStaff     = HasilMabac::where('periode', $periode)->count();
        $totalKriteria  = Kriteria::count();

        $kriteriaList = Kriteria::orderByRaw('CAST(SUBSTRING(kode, 2) AS UNSIGNED) ASC')->get();
        $divisiList     = Divisi::where('aktif', true)->orderBy('nama')->get();

        // Hasil per divisi (sudah diurutkan peringkat)
        $hasilPerDivisi = [];
        foreach ($divisiList as $divisi) {
            $hasilPerDivisi[$divisi->id] = HasilMabac::with('staff')
                ->where('divisi_id', $divisi->id)
                ->where('periode', $periode)
                ->orderBy('peringkat')
                ->get();
        }

        // Staff terbaik keseluruhan = Si tertinggi dari semua divisi
        $spotlightStaff = HasilMabac::with(['staff', 'divisi'])
            ->where('periode', $periode)
            ->where('terbaik', true)
            ->orderByDesc('nilai_akhir')
            ->first();

        return view('public.index', compact(
            'periodeList', 'periode',
            'totalDivisi', 'totalStaff', 'totalKriteria',
            'kriteriaList', 'divisiList',
            'hasilPerDivisi', 'spotlightStaff'
        ));
    }
}
