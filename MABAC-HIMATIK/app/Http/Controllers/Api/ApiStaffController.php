<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;

class ApiStaffController extends Controller
{
    public function index(Request $request)
    {
        $query = Staff::with('divisi')->where('aktif', true);
        if ($request->divisi_id) {
            $query->where('divisi_id', $request->divisi_id);
        }
        return response()->json(['data' => $query->orderBy('nama')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'divisi_id' => 'required|exists:divisi,id',
            'nama'      => 'required|string|max:100',
            'nim'       => 'required|string|unique:staff',
            'jabatan'   => 'required|string|max:100',
            'aktif'     => 'boolean',
        ]);
        return response()->json(Staff::create($data)->load('divisi'), 201);
    }

    public function update(Request $request, Staff $staff)
    {
        $data = $request->validate([
            'divisi_id' => 'required|exists:divisi,id',
            'nama'      => 'required|string|max:100',
            'nim'       => 'required|string|unique:staff,nim,' . $staff->id,
            'jabatan'   => 'required|string|max:100',
            'aktif'     => 'boolean',
        ]);
        $staff->update($data);
        return response()->json($staff->load('divisi'));
    }

    public function destroy(Staff $staff)
    {
        $staff->delete();
        return response()->json(['message' => 'Staff berhasil dihapus']);
    }
}
