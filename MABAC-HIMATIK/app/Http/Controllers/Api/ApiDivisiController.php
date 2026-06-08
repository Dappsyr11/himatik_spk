<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Divisi;
use Illuminate\Http\Request;

class ApiDivisiController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => Divisi::withCount('staff')->orderBy('nama')->get()
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'      => 'required|string|max:100',
            'kode'      => 'required|string|max:20|unique:divisi',
            'deskripsi' => 'nullable|string',
            'aktif'     => 'boolean',
        ]);
        return response()->json(Divisi::create($data), 201);
    }

    public function update(Request $request, Divisi $divisi)
    {
        $data = $request->validate([
            'nama'      => 'required|string|max:100',
            'kode'      => 'required|string|max:20|unique:divisi,kode,' . $divisi->id,
            'deskripsi' => 'nullable|string',
            'aktif'     => 'boolean',
        ]);
        $divisi->update($data);
        return response()->json($divisi);
    }

    public function destroy(Divisi $divisi)
    {
        $divisi->delete();
        return response()->json(['message' => 'Divisi berhasil dihapus']);
    }
}
