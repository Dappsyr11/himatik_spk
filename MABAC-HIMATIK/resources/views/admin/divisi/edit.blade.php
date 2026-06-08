@extends('layouts.app')
@section('title', 'Edit Divisi — ' . $divisi->nama)
@section('breadcrumb', 'Divisi / Edit')
@section('content')
<div style="max-width:600px">
<div class="card">
    <div class="card-header"><div class="card-title">✏️ Edit Divisi</div></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.divisi.update', $divisi) }}">
            @csrf @method('PUT')
            <div class="form-grid">
                <div class="form-group">
                    <label>Nama Divisi <span style="color:var(--red)">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $divisi->nama) }}" required>
                    @error('nama')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label>Kode Divisi <span style="color:var(--red)">*</span></label>
                    <input type="text" name="kode" value="{{ old('kode', $divisi->kode) }}" style="text-transform:uppercase" required>
                    @error('kode')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi">{{ old('deskripsi', $divisi->deskripsi) }}</textarea>
            </div>
            <div class="form-group">
                <label style="display:flex;align-items:center;gap:8px;text-transform:none;letter-spacing:0;font-size:.875rem">
                    <input type="checkbox" name="aktif" value="1" {{ $divisi->aktif ? 'checked' : '' }} style="width:auto">
                    Divisi Aktif
                </label>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end">
                <a href="{{ route('admin.divisi.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">💾 Perbarui</button>
            </div>
        </form>
    </div>
</div>
</div>
@endsection
