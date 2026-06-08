@extends('layouts.app')
@section('title', 'Tambah Divisi')
@section('breadcrumb', 'Divisi / Tambah')
@section('content')
<div style="max-width:600px">
<div class="card">
    <div class="card-header"><div class="card-title">🏛️ Tambah Divisi Baru</div></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.divisi.store') }}">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label>Nama Divisi <span style="color:var(--red)">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Divisi Akademik" required>
                    @error('nama')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label>Kode Divisi <span style="color:var(--red)">*</span></label>
                    <input type="text" name="kode" value="{{ old('kode') }}" placeholder="AKD" style="text-transform:uppercase" required>
                    <div class="input-hint">Kode unik, maks 10 karakter (otomatis kapital)</div>
                    @error('kode')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" placeholder="Deskripsi singkat divisi...">{{ old('deskripsi') }}</textarea>
            </div>
            <div class="form-group">
                <label style="display:flex;align-items:center;gap:8px;text-transform:none;letter-spacing:0;font-size:.875rem">
                    <input type="checkbox" name="aktif" value="1" checked style="width:auto">
                    Divisi Aktif
                </label>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end">
                <a href="{{ route('admin.divisi.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">💾 Simpan</button>
            </div>
        </form>
    </div>
</div>
</div>
@endsection
