<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Divisi;
use App\Models\Penilaian;
use App\Models\SkalaPenilaian;
use App\Models\Staff;
use Illuminate\Http\Request;

class ApiPenilaianController extends Controller
{
    public function index(Request $request)
    {
        $periode  = $request->get('periode', '2024-1');
        $divisiId = $request->get('divisi_id');

        $query = Staff::with([
            'divisi',
            'penilaian' => fn($q) => $q->where('periode', $periode)
        ])->where('aktif', true);

        if ($divisiId) {
            $query->where('divisi_id', $divisiId);
        }

        return response()->json([
            'data' => $query->orderBy('divisi_id')->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'periode'   => 'required|string',
            'penilaian' => 'required|array',
        ]);

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

        return response()->json(['message' => 'Penilaian berhasil disimpan']);
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

        return response()->json(['message' => 'Penilaian ' . $staff->nama . ' berhasil diperbarui']);
    }

    public function periodeList()
    {
        $list = Penilaian::distinct()->pluck('periode')->sortDesc()->values();
        if ($list->isEmpty()) {
            $list = collect(['2024-1']);
        }
        return response()->json(['data' => $list]);
    }
}
