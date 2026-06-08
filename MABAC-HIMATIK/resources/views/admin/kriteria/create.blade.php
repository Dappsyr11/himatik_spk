@extends('layouts.app')
@section('title', 'Tambah Kriteria')
@section('breadcrumb', 'Kriteria / Tambah')
@section('content')
<div style="max-width:600px">
<div class="card">
    <div class="card-header"><div class="card-title">📋 Tambah Kriteria Baru</div></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.kriteria.store') }}">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label>Kode Kriteria <span style="color:var(--red)">*</span></label>
                    <input type="text" name="kode" value="{{ old('kode') }}" placeholder="C1" required>
                    <div class="input-hint">Contoh: C1, C2, C11, dst.</div>
                    @error('kode')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label>Tipe Kriteria <span style="color:var(--red)">*</span></label>
                    <select name="tipe" required>
                        <option value="benefit" {{ old('tipe')=='benefit'?'selected':'' }}>Benefit (semakin tinggi semakin baik ↑)</option>
                        <option value="cost" {{ old('tipe')=='cost'?'selected':'' }}>Cost (semakin rendah semakin baik ↓)</option>
                    </select>
                    @error('tipe')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="form-group">
                <label>Nama Kriteria <span style="color:var(--red)">*</span></label>
                <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Integritas, Komunikasi, dll." required>
                @error('nama')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" placeholder="Penjelasan detail tentang kriteria ini...">{{ old('deskripsi') }}</textarea>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end">
                <a href="{{ route('admin.kriteria.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">💾 Simpan</button>
            </div>
        </form>
    </div>
</div>
</div>
@endsection
