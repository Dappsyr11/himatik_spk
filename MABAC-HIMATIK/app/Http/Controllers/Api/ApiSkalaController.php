<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SkalaPenilaian;
use Illuminate\Http\Request;

class ApiSkalaController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => SkalaPenilaian::orderByDesc('nilai')->get()
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'label'     => 'required|string',
            'nilai'     => 'required|integer|min:1|max:10',
            'deskripsi' => 'nullable|string',
        ]);
        return response()->json(SkalaPenilaian::create($data), 201);
    }

    public function destroy(SkalaPenilaian $skalaPenilaian)
    {
        $skalaPenilaian->delete();
        return response()->json(['message' => 'Skala berhasil dihapus']);
    }
}
