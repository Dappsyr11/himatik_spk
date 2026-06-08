@extends('layouts.app')
@section('title', 'Edit Kriteria')
@section('breadcrumb', 'Kriteria / Edit')
@section('content')
<div style="max-width:600px">
<div class="card">
    <div class="card-header"><div class="card-title">✏️ Edit Kriteria</div></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.kriteria.update', $kriteria) }}">
            @csrf @method('PUT')
            <div class="form-grid">
                <div class="form-group">
                    <label>Kode Kriteria <span style="color:var(--red)">*</span></label>
                    <input type="text" name="kode" value="{{ old('kode', $kriteria->kode) }}" required>
                    @error('kode')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label>Tipe Kriteria <span style="color:var(--red)">*</span></label>
                    <select name="tipe" required>
                        <option value="benefit" {{ old('tipe',$kriteria->tipe)=='benefit'?'selected':'' }}>Benefit ↑</option>
                        <option value="cost"    {{ old('tipe',$kriteria->tipe)=='cost'?'selected':'' }}>Cost ↓</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>Nama Kriteria <span style="color:var(--red)">*</span></label>
                <input type="text" name="nama" value="{{ old('nama', $kriteria->nama) }}" required>
                @error('nama')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi">{{ old('deskripsi', $kriteria->deskripsi) }}</textarea>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end">
                <a href="{{ route('admin.kriteria.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">💾 Perbarui</button>
            </div>
        </form>
    </div>
</div>
</div>
@endsection
