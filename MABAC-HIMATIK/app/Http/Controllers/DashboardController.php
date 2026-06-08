<?php
namespace App\Http\Controllers;
use App\Models\Divisi;
use App\Models\HasilMabac;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\Staff;

class DashboardController extends Controller
{
    public function index()
    {
        $totalDivisi    = Divisi::where('aktif', true)->count();
        $totalStaff     = Staff::where('aktif', true)->count();
        $totalKriteria  = Kriteria::count();
        $totalPenilaian = Penilaian::count();

        $periodeList    = Penilaian::distinct()->pluck('periode')->sortDesc()->values();
        $periodeTerbaru = $periodeList->first() ?? '2024-1';

        $staffTerbaik = HasilMabac::with(['staff', 'staff.divisi'])
            ->where('periode', $periodeTerbaru)
            ->where('terbaik', true)
            ->orderByDesc('nilai_akhir')
            ->get();

        $divisiSudahDihitungCount = HasilMabac::where('periode', $periodeTerbaru)
            ->distinct('divisi_id')->count('divisi_id');

        return view('admin.dashboard', compact(
            'totalDivisi', 'totalStaff', 'totalKriteria', 'totalPenilaian',
            'staffTerbaik', 'periodeList', 'periodeTerbaru', 'divisiSudahDihitungCount'
        ));
    }
}
