<?php

namespace App\Http\Controllers;

use App\Helpers\PeriodeHelper;
use App\Models\Divisi;
use App\Models\HasilMabac;
use App\Models\Kriteria;
use App\Services\MabacService;
use Illuminate\Http\Request;

class HasilController extends Controller
{
    public function __construct(private MabacService $mabac) {}

    public function index(Request $request)
    {
        $semuaBulan   = PeriodeHelper::daftarBulan();
        $bulanAdaData = HasilMabac::distinct()->pluck('periode')->toArray();
        $periode      = $request->get('periode', PeriodeHelper::periodeDefault());

        if (!array_key_exists($periode, $semuaBulan)) {
            $periode = PeriodeHelper::periodeDefault();
        }

        $divisiList = Divisi::with([
            'hasilMabac' => fn($q) => $q->where('periode', $periode)->with('staff')->orderBy('peringkat')
        ])->where('aktif', true)->get();

        return view('admin.hasil.index', compact(
            'divisiList', 'semuaBulan', 'bulanAdaData', 'periode'
        ));
    }

    public function hitung(Request $request)
    {
        $request->validate([
            'periode'   => 'required|string',
            'divisi_id' => 'nullable|exists:divisi,id',
        ]);

        $periode = $request->periode;

        if (Kriteria::where('bobot', '>', 0)->count() === 0) {
            return back()->with('error', 'Bobot kriteria belum diatur!');
        }

        if ($request->divisi_id) {
            $hasil = $this->mabac->hitungPerDivisi(Divisi::findOrFail($request->divisi_id), $periode);
            if (isset($hasil['error'])) return back()->with('error', $hasil['error']);
        } else {
            $this->mabac->hitungSemuaDivisi($periode);
        }

        return redirect()->route('admin.hasil.index', ['periode' => $periode])
            ->with('success', 'Perhitungan MABAC berhasil untuk ' . PeriodeHelper::labelBulan($periode) . '!');
    }

    public function detail(Request $request, Divisi $divisi)
    {
        $semuaBulan   = PeriodeHelper::daftarBulan();
        $bulanAdaData = HasilMabac::distinct()->pluck('periode')->toArray();
        $periode      = $request->get('periode', PeriodeHelper::periodeDefault());
        $periodeList  = HasilMabac::where('divisi_id', $divisi->id)->distinct()->pluck('periode');

        $kriteria = Kriteria::orderByRaw('CAST(SUBSTRING(kode, 2) AS UNSIGNED) ASC')->get();

        $hasilList = HasilMabac::with('staff')
            ->where('divisi_id', $divisi->id)
            ->where('periode', $periode)
            ->orderBy('peringkat')
            ->get();

        if ($hasilList->isEmpty()) {
            return redirect()->route('admin.hasil.index', ['periode' => $periode])
                ->with('error', 'Belum ada hasil perhitungan untuk divisi ini.');
        }

        $matriksX = $matriksN = $matriksV = $matriksQ = [];
        $batasG = [];
        foreach ($hasilList as $h) {
            $raw = $h->detail_perhitungan ?? [];
            $d   = is_string($raw) ? json_decode($raw, true) : (array) $raw;
            $matriksX[$h->staff_id] = $d['matriks_x'] ?? [];
            $matriksN[$h->staff_id] = $d['matriks_n'] ?? [];
            $matriksV[$h->staff_id] = $d['matriks_v'] ?? [];
            $matriksQ[$h->staff_id] = $d['matriks_q'] ?? [];
            if (!empty($d['batas_g'])) $batasG = $d['batas_g'];
        }

        $bobot = $kriteria->pluck('bobot', 'id');

        return view('admin.hasil.detail', compact(
            'divisi', 'hasilList', 'kriteria', 'periode', 'semuaBulan',
            'matriksX', 'matriksN', 'matriksV', 'matriksQ', 'batasG', 'bobot',
            'bulanAdaData', 'periodeList'
        ));
    }

    public function export(Request $request)
    {
        $periode    = $request->get('periode', PeriodeHelper::periodeDefault());
        $labelBulan = PeriodeHelper::labelBulan($periode);

        $divisiList = Divisi::with([
            'hasilMabac' => fn($q) => $q->where('periode', $periode)->with('staff')->orderBy('peringkat')
        ])->where('aktif', true)->get();

        $csv  = "\xEF\xBB\xBF";
        $csv .= "Laporan Hasil SPK MABAC - HIMATIK\r\n";
        $csv .= "Bulan: {$labelBulan}\r\n";
        $csv .= "Tanggal: " . now()->format('d/m/Y H:i') . "\r\n\r\n";
        $csv .= "No,Divisi,Peringkat,Nama Staff,NIM,Jabatan,Nilai Si,Status\r\n";

        $no = 1;
        foreach ($divisiList as $div) {
            foreach ($div->hasilMabac as $h) {
                $status = $h->terbaik ? 'STAFF TERBAIK' : '';
                $csv .= "{$no},\"{$div->nama}\",{$h->peringkat},\"{$h->staff->nama}\","
                    . "{$h->staff->nim},\"{$h->staff->jabatan}\","
                    . number_format($h->nilai_akhir, 6) . ",{$status}\r\n";
                $no++;
            }
        }

        return response($csv)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', "attachment; filename=\"hasil-spk-{$periode}.csv\"");
    }
}