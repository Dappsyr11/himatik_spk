@extends('layouts.app')
@section('title', 'Tambah Staff')
@section('breadcrumb', 'Staff / Tambah')
@section('content')
<div style="max-width:680px">
<div class="card">
    <div class="card-header"><div class="card-title">👤 Tambah Staff Baru</div></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.staff.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Divisi <span style="color:var(--red)">*</span></label>
                <select name="divisi_id" required>
                    <option value="">— Pilih Divisi —</option>
                    @foreach($divisiList as $d)
                        <option value="{{ $d->id }}" {{ old('divisi_id') == $d->id ? 'selected' : '' }}>{{ $d->kode }} — {{ $d->nama }}</option>
                    @endforeach
                </select>
                @error('divisi_id')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Nama Lengkap <span style="color:var(--red)">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Ahmad Rizki Pratama" required>
                    @error('nama')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label>NIM <span style="color:var(--red)">*</span></label>
                    <input type="text" name="nim" value="{{ old('nim') }}" placeholder="2021001001" required>
                    @error('nim')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="form-group">
                <label>Jabatan <span style="color:var(--red)">*</span></label>

                <select name="jabatan" required>
                    <option value="">-- Pilih Jabatan --</option>
                    <option value="Kepala" {{ old('jabatan') == 'Kepala' ? 'selected' : '' }}>
                        Kepala
                    </option>
                    <option value="BPH" {{ old('jabatan') == 'BPH' ? 'selected' : '' }}>
                        BPH
                    </option>
                    <option value="Staff" {{ old('jabatan') == 'Staff' ? 'selected' : '' }}>
                        Staff
                    </option>
                </select>

                @error('jabatan')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Foto (opsional)</label>
                <input type="file" name="foto" accept="image/*">
                <div class="input-hint">Maks 2MB. Format: JPG, PNG, GIF.</div>
            </div>
            <div class="form-group">
                <label style="display:flex;align-items:center;gap:8px;text-transform:none;letter-spacing:0;font-size:.875rem">
                    <input type="checkbox" name="aktif" value="1" checked style="width:auto">
                    Staff Aktif
                </label>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end">
                <a href="{{ route('admin.staff.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">💾 Simpan</button>
            </div>
        </form>
    </div>
</div>
</div>
@endsection
