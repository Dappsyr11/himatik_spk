<?php
namespace App\Http\Controllers;
use App\Models\Divisi;
use Illuminate\Http\Request;

class DivisiController extends Controller
{
    public function index() {
        $divisiList = Divisi::withCount('staff')->orderBy('nama')->get();
        return view('admin.divisi.index', compact('divisiList'));
    }
    public function create() { return view('admin.divisi.create'); }
    public function store(Request $request) {
        $data = $request->validate(['nama'=>'required|string|max:100','kode'=>'required|string|max:10|unique:divisi,kode','deskripsi'=>'nullable|string']);
        $data['kode']  = strtoupper($data['kode']);
        $data['aktif'] = $request->boolean('aktif', true);
        Divisi::create($data);
        return redirect()->route('admin.divisi.index')->with('success', 'Divisi berhasil ditambahkan!');
    }
    public function edit(Divisi $divisi) { return view('admin.divisi.edit', compact('divisi')); }
    public function update(Request $request, Divisi $divisi) {
        $data = $request->validate(['nama'=>'required|string|max:100','kode'=>'required|string|max:10|unique:divisi,kode,'.$divisi->id,'deskripsi'=>'nullable|string']);
        $data['kode']  = strtoupper($data['kode']);
        $data['aktif'] = $request->boolean('aktif', true);
        $divisi->update($data);
        return redirect()->route('admin.divisi.index')->with('success', 'Divisi berhasil diperbarui!');
    }
    public function destroy(Divisi $divisi) {
        $divisi->delete();
        return back()->with('success', 'Divisi berhasil dihapus!');
    }
}
