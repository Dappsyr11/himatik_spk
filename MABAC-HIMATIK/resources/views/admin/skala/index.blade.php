@extends('layouts.app')
@section('title', 'Skala Penilaian')
@section('breadcrumb', 'Kelola Data / Skala Penilaian')

@section('content')
<div style="display:grid;grid-template-columns:1fr 380px;gap:20px;align-items:start">

<div class="card">
    <div class="card-header">
        <div class="card-title">📊 Skala Penilaian yang Berlaku</div>
    </div>
    @if($skala->isEmpty())
        <div class="empty-state"><div class="icon">📊</div><h3>Belum ada skala penilaian</h3></div>
    @else
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Nilai Angka</th><th>Label</th><th>Deskripsi</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @foreach($skala as $s)
                <tr>
                    <td>
                        <span class="mono" style="font-size:1.4rem;font-weight:800;color:
                            @if($s->nilai==5) var(--green)
                            @elseif($s->nilai==4) var(--accent)
                            @elseif($s->nilai==3) var(--gold)
                            @elseif($s->nilai==2) #FF7A5A
                            @else var(--red) @endif
                        ">{{ $s->nilai }}</span>
                    </td>
                    <td style="font-weight:700">{{ $s->label }}</td>
                    <td style="font-size:.82rem;color:var(--muted)">{{ $s->deskripsi ?? '—' }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.skala.destroy', $s) }}" onsubmit="return confirm('Hapus skala {{ $s->label }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-xs">🗑️</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

<!-- Tambah Skala -->
<div class="card" style="position:sticky;top:80px">
    <div class="card-header"><div class="card-title">➕ Tambah Skala Baru</div></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.skala.store') }}">
            @csrf
            <div class="form-group">
                <label>Nilai Angka <span style="color:var(--red)">*</span></label>
                <input type="number" name="nilai" value="{{ old('nilai') }}" min="1" max="10" placeholder="5" required>
                <div class="input-hint">Nilai integer (1–10), harus unik</div>
                @error('nilai')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Label <span style="color:var(--red)">*</span></label>
                <input type="text" name="label" value="{{ old('label') }}" placeholder="Sangat Baik" required>
                @error('label')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" rows="3" placeholder="Penjelasan singkat...">{{ old('deskripsi') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%">➕ Tambahkan</button>
        </form>
    </div>
</div>

</div>
@endsection
