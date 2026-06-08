<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BobotKriteria;
use Illuminate\Http\Request;

class ApiBobotController extends Controller
{
    public function index(Request $request)
    {
        $periode = $request->get('periode', '2024-1');
        return response()->json([
            'data' => BobotKriteria::with('kriteria')->where('periode', $periode)->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'periode' => 'required|string',
            'bobot'   => 'required|array',
        ]);

        foreach ($request->bobot as $item) {
            BobotKriteria::updateOrCreate(
                ['kriteria_id' => $item['kriteria_id'], 'periode' => $request->periode],
                ['bobot' => $item['bobot']]
            );
        }

        return response()->json(['message' => 'Bobot berhasil disimpan']);
    }
}
