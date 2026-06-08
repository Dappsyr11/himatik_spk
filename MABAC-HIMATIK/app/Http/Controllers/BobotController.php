<?php
namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Http\Request;

class BobotController extends Controller
{
    public function index()
    {
        $kriteria   = Kriteria::orderByRaw('CAST(SUBSTRING(kode, 2) AS UNSIGNED) ASC')->get();
        $totalBobot = $kriteria->sum('bobot');

        return view('admin.bobot.index', compact('kriteria', 'totalBobot'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bobot'   => 'required|array',
            'bobot.*' => 'required|numeric|min:0|max:1',
        ]);

        $total = array_sum($request->bobot);
        if (abs($total - 1.0) > 0.001) {
            return back()->withInput()->with('error',
                'Total bobot harus = 1.00, saat ini = ' . number_format($total, 4)
            );
        }

        foreach ($request->bobot as $kriteriaId => $bobot) {
            Kriteria::where('id', $kriteriaId)->update(['bobot' => $bobot]);
        }

        return redirect()->route('admin.bobot.index')
            ->with('success', 'Bobot kriteria berhasil disimpan!');
    }
}