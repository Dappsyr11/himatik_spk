<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Divisi;
use App\Models\HasilMabac;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\Staff;
use Illuminate\Http\Request;

class ApiDashboardController extends Controller
{
    public function index(Request $request)
    {
        $periodeList    = Penilaian::distinct()->pluck('periode')->sortDesc()->values();
        $periodeTerbaru = $periodeList->first() ?? null;

        $topStaff = $periodeTerbaru
            ? HasilMabac::with(['staff', 'staff.divisi'])
                ->where('periode', $periodeTerbaru)
                ->orderBy('peringkat')
                ->take(5)
                ->get()
                ->map(fn($h) => [
                    'nama'        => $h->staff->nama,
                    'nim'         => $h->staff->nim,
                    'jabatan'     => $h->staff->jabatan,
                    'divisi'      => $h->staff->divisi->nama ?? '-',
                    'peringkat'   => $h->peringkat,
                    'nilai_akhir' => $h->nilai_akhir,
                    'terbaik'     => $h->terbaik,
                ])
            : [];

        return response()->json([
            'user'            => $request->user(),
            'total_staff'     => Staff::where('aktif', true)->count(),
            'total_divisi'    => Divisi::where('aktif', true)->count(),
            'total_kriteria'  => Kriteria::count(),
            'total_penilaian' => Penilaian::count(),
            'periode_terbaru' => $periodeTerbaru,
            'top_staff'       => $topStaff,
        ]);
    }
}
