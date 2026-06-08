<?php
namespace App\Http\Controllers;
use App\Models\Kriteria;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    public function index() {
        $kriteriaList = Kriteria::orderByRaw('CAST(SUBSTRING(kode, 2) AS UNSIGNED) ASC')->get();
        return view('admin.kriteria.index', compact('kriteriaList'));
    }
    public function create() { return view('admin.kriteria.create'); }
    public function store(Request $request) {
        $data = $request->validate(['kode'=>'required|string|max:10|unique:kriteria,kode','nama'=>'required|string|max:100','tipe'=>'required|in:benefit,cost','deskripsi'=>'nullable|string']);
        Kriteria::create($data);
        return redirect()->route('admin.kriteria.index')->with('success', 'Kriteria berhasil ditambahkan!');
    }
    public function edit(Kriteria $kriteria) { return view('admin.kriteria.edit', compact('kriteria')); }
    public function update(Request $request, Kriteria $kriteria) {
        $data = $request->validate(['kode'=>'required|string|max:10|unique:kriteria,kode,'.$kriteria->id,'nama'=>'required|string|max:100','tipe'=>'required|in:benefit,cost','deskripsi'=>'nullable|string']);
        $kriteria->update($data);
        return redirect()->route('admin.kriteria.index')->with('success', 'Kriteria berhasil diperbarui!');
    }
    public function destroy(Kriteria $kriteria) {
        $kriteria->delete();
        return back()->with('success', 'Kriteria berhasil dihapus!');
    }
}
