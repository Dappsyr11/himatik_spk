<?php
namespace App\Http\Controllers;
use App\Models\Divisi;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    public function index(Request $request) {
        $query = Staff::with('divisi');
        if ($request->divisi_id) $query->where('divisi_id', $request->divisi_id);
        $staffList  = $query->orderBy('divisi_id')->paginate(20)->withQueryString();
        $divisiList = Divisi::all();
        return view('admin.staff.index', compact('staffList', 'divisiList'));
    }
    public function create() {
        $divisiList = Divisi::where('aktif', true)->orderBy('nama')->get();
        return view('admin.staff.create', compact('divisiList'));
    }
    public function store(Request $request) {
        $data = $request->validate(['divisi_id'=>'required|exists:divisi,id','nama'=>'required|string|max:100','nim'=>'required|string|unique:staff,nim','jabatan'=>'required|string|max:100','foto'=>'nullable|image|max:2048']);
        if ($request->hasFile('foto')) $data['foto'] = $request->file('foto')->store('staff-foto', 'public');
        $data['aktif'] = $request->boolean('aktif', true);
        Staff::create($data);
        return redirect()->route('admin.staff.index')->with('success', 'Staff berhasil ditambahkan!');
    }
    public function edit(Staff $staff) {
        $divisiList = Divisi::where('aktif', true)->orderBy('nama')->get();
        return view('admin.staff.edit', compact('staff', 'divisiList'));
    }
    public function update(Request $request, Staff $staff) {
        $data = $request->validate(['divisi_id'=>'required|exists:divisi,id','nama'=>'required|string|max:100','nim'=>'required|string|unique:staff,nim,'.$staff->id,'jabatan'=>'required|string|max:100','foto'=>'nullable|image|max:2048']);
        if ($request->hasFile('foto')) {
            if ($staff->foto) Storage::disk('public')->delete($staff->foto);
            $data['foto'] = $request->file('foto')->store('staff-foto', 'public');
        }
        $data['aktif'] = $request->boolean('aktif', true);
        $staff->update($data);
        return redirect()->route('admin.staff.index')->with('success', 'Staff berhasil diperbarui!');
    }
    public function destroy(Staff $staff) {
        if ($staff->foto) Storage::disk('public')->delete($staff->foto);
        $staff->delete();
        return back()->with('success', 'Staff berhasil dihapus!');
    }
}
