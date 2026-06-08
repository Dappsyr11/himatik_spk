<?php
namespace App\Http\Controllers;
use App\Models\SkalaPenilaian;
use Illuminate\Http\Request;

class SkalaPenilaianController extends Controller
{
    public function index() {
        $skala = SkalaPenilaian::orderByDesc('nilai')->get();
        return view('admin.skala.index', compact('skala'));
    }
    public function store(Request $request) {
        $data = $request->validate(['label'=>'required|string|max:50','nilai'=>'required|integer|min:1|max:10|unique:skala_penilaian,nilai','deskripsi'=>'nullable|string']);
        SkalaPenilaian::create($data);
        return back()->with('success', 'Skala penilaian ditambahkan!');
    }
    public function destroy(SkalaPenilaian $skalaPenilaian) {
        $skalaPenilaian->delete();
        return back()->with('success', 'Skala penilaian dihapus!');
    }
}
