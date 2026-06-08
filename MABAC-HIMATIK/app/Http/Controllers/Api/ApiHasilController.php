<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BobotKriteria;
use App\Models\Divisi;
use App\Models\HasilMabac;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Services\MabacService;
use Illuminate\Http\Request;

class ApiHasilController extends Controller
{
    public function __construct(private MabacService $mabac) {}

    public function index(Request $request)
    {
        $periodeList    = Penilaian::distinct()->pluck('periode')->sortDesc()->values();
        $periode        = $request->get('periode', $periodeList->first() ?? '2024-1');

        $divisiList = Divisi::with([
            'hasilMabac' => fn($q) => $q->where('periode', $periode)
                ->with('staff')->orderBy('peringkat')
        ])->where('aktif', true)->get();

        return response()->json([
            'periode'      => $periode,
            'periode_list' => $periodeList,
            'divisi_list'  => $divisiList,
        ]);
    }

    public function hitung(Request $request)
    {
        $request->validate([
            'periode'   => 'required|string',
            'divisi_id' => 'nullable|exists:divisi,id',
        ]);

        $periode = $request->periode;

        if (BobotKriteria::where('periode', $periode)->count() === 0) {
            return response()->json([
                'message' => "Bobot kriteria periode {$periode} belum diatur!"
            ], 422);
        }

        if ($request->divisi_id) {
            $hasil = $this->mabac->hitungPerDivisi(
                Divisi::findOrFail($request->divisi_id), $periode
            );
            if (isset($hasil['error'])) {
                return response()->json(['message' => $hasil['error']], 422);
            }
        } else {
            $this->mabac->hitungSemuaDivisi($periode);
        }

        return response()->json(['message' => 'Perhitungan MABAC berhasil untuk periode ' . $periode]);
    }

    public function detail(Request $request, Divisi $divisi)
    {
        $periodeList = Penilaian::distinct()->pluck('periode')->sortDesc()->values();
        $periode     = $request->get('periode', $periodeList->first() ?? '2024-1');
        $kriteria    = Kriteria::orderByRaw('CAST(SUBSTRING(kode, 2) AS UNSIGNED) ASC')->get();

        $hasilList = HasilMabac::with('staff')
            ->where('divisi_id', $divisi->id)
            ->where('periode', $periode)
            ->orderBy('peringkat')
            ->get()
            ->map(function ($h) {
                $raw = $h->detail_perhitungan ?? [];
                $d   = is_string($raw) ? json_decode($raw, true) : (array) $raw;
                return [
                    'id'          => $h->id,
                    'staff_id'    => $h->staff_id,
                    'divisi_id'   => $h->divisi_id,
                    'periode'     => $h->periode,
                    'nilai_akhir' => $h->nilai_akhir,
                    'peringkat'   => $h->peringkat,
                    'terbaik'     => $h->terbaik,
                    'staff'       => $h->staff,
                    'detail_perhitungan' => $d,
                ];
            });

        return response()->json([
            'divisi'     => $divisi,
            'periode'    => $periode,
            'kriteria'   => $kriteria,
            'hasil_list' => $hasilList,
        ]);
    }
}
