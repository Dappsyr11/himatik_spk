<?php
namespace App\Http\Controllers;

use App\Helpers\PeriodeHelper;
use App\Models\Divisi;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\SkalaPenilaian;
use App\Models\Staff;
use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    public function index(Request $request)
    {
        $periode    = $request->get('periode', PeriodeHelper::periodeDefault());
        $divisiId   = $request->get('divisi_id');
        $divisiList = Divisi::where('aktif', true)->get();
        $periodeList = Penilaian::distinct()->pluck('periode')->sortDesc()->values();

        $query = Staff::with([
            'divisi',
            'penilaian' => fn($q) => $q->where('periode', $periode)
        ])->where('aktif', true);

        if ($divisiId) $query->where('divisi_id', $divisiId);
        $staffList = $query->orderBy('divisi_id')->get();

        return view('admin.penilaian.index', compact('staffList', 'divisiList', 'periodeList', 'periode', 'divisiId'));
    }

    public function create(Request $request)
    {
        $periode    = $request->get('periode', PeriodeHelper::periodeDefault());
        $divisiId   = $request->get('divisi_id');
        $divisiList = Divisi::where('aktif', true)->get();
        $kriteria   = Kriteria::orderByRaw('CAST(SUBSTRING(kode, 2) AS UNSIGNED) ASC')->get();
        $skala      = SkalaPenilaian::orderByDesc('nilai')->get();
        $staffList  = Staff::where('aktif', true)
            ->when($divisiId, fn($q) => $q->where('divisi_id', $divisiId))
            ->orderBy('divisi_id')->get();

        return view('admin.penilaian.create', compact('staffList', 'divisiList', 'kriteria', 'skala', 'periode', 'divisiId'));
    }

    public function store(Request $request)
    {
        $request->validate(['periode' => 'required|string', 'penilaian' => 'required|array']);
        $skalaMap = SkalaPenilaian::pluck('label', 'nilai')->toArray();
        $periode  = $request->periode;

        foreach ($request->penilaian as $staffId => $nilaiPerKriteria) {
            foreach ($nilaiPerKriteria as $kriteriaId => $nilai) {
                Penilaian::updateOrCreate(
                    ['staff_id' => $staffId, 'kriteria_id' => $kriteriaId, 'periode' => $periode],
                    ['nilai' => $nilai, 'label_nilai' => $skalaMap[$nilai] ?? 'Cukup']
                );
            }
        }

        return redirect()->route('admin.penilaian.index', ['periode' => $periode])
            ->with('success', 'Penilaian berhasil disimpan untuk periode ' . $periode . '!');
    }

    public function edit(Staff $staff, Request $request)
    {
        $periode      = $request->get('periode', PeriodeHelper::periodeDefault());
        $kriteria     = Kriteria::orderByRaw('CAST(SUBSTRING(kode, 2) AS UNSIGNED) ASC')->get();
        $skala        = SkalaPenilaian::orderByDesc('nilai')->get();
        $penilaianMap = Penilaian::where('staff_id', $staff->id)
            ->where('periode', $periode)->pluck('nilai', 'kriteria_id');

        return view('admin.penilaian.edit', compact('staff', 'kriteria', 'skala', 'penilaianMap', 'periode'));
    }

    public function update(Request $request, Staff $staff)
    {
        $request->validate([
            'periode'     => 'required|string',
            'penilaian'   => 'required|array',
            'penilaian.*' => 'required|integer|min:1|max:5',
        ]);

        $skalaMap = SkalaPenilaian::pluck('label', 'nilai')->toArray();
        $periode  = $request->periode;

        foreach ($request->penilaian as $kriteriaId => $nilai) {
            Penilaian::updateOrCreate(
                ['staff_id' => $staff->id, 'kriteria_id' => $kriteriaId, 'periode' => $periode],
                ['nilai' => $nilai, 'label_nilai' => $skalaMap[$nilai] ?? 'Cukup']
            );
        }

        return redirect()->route('admin.penilaian.index', ['periode' => $periode])
            ->with('success', 'Penilaian ' . $staff->nama . ' berhasil diperbarui!');
    }
}