<?php
// ── ApiKriteriaController.php ────────────────────────────────────
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use Illuminate\Http\Request;

class ApiKriteriaController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => Kriteria::orderByRaw('CAST(SUBSTRING(kode, 2) AS UNSIGNED) ASC')->get()
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode'      => 'required|string|unique:kriteria',
            'nama'      => 'required|string',
            'tipe'      => 'required|in:benefit,cost',
            'deskripsi' => 'nullable|string',
        ]);
        return response()->json(Kriteria::create($data), 201);
    }

    public function update(Request $request, Kriteria $kriteria)
    {
        $data = $request->validate([
            'kode'      => 'required|string|unique:kriteria,kode,' . $kriteria->id,
            'nama'      => 'required|string',
            'tipe'      => 'required|in:benefit,cost',
            'deskripsi' => 'nullable|string',
        ]);
        $kriteria->update($data);
        return response()->json($kriteria);
    }

    public function destroy(Kriteria $kriteria)
    {
        $kriteria->delete();
        return response()->json(['message' => 'Kriteria berhasil dihapus']);
    }
}
