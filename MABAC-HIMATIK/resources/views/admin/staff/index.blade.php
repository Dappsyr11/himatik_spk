@extends('layouts.app')
@section('title', 'Data Staff')
@section('breadcrumb', 'Kelola Data / Staff')
@section('topbar-actions')
    <a href="{{ route('admin.staff.create') }}" class="btn btn-primary btn-sm">➕ Tambah Staff</a>
@endsection
@section('content')
<div class="card" style="margin-bottom:16px">
    <div class="card-body" style="padding:14px 20px">
        <form method="GET" style="display:flex;gap:12px;align-items:flex-end">
            <div>
                <label>Filter Divisi</label>
                <select name="divisi_id" style="width:220px" onchange="this.form.submit()">
                    <option value="">— Semua Divisi —</option>
                    @foreach($divisiList as $d)
                        <option value="{{ $d->id }}" {{ request('divisi_id') == $d->id ? 'selected' : '' }}>{{ $d->nama }}</option>
                    @endforeach
                </select>
            </div>
            <a href="{{ route('admin.staff.index') }}" class="btn btn-outline btn-sm">Reset</a>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <div class="card-title">👥 Daftar Staff HIMATIK</div>
        <span class="badge badge-blue">{{ $staffList->total() }} Staff</span>
    </div>
    @if($staffList->isEmpty())
        <div class="empty-state"><div class="icon">👥</div><h3>Belum ada staff</h3><p>Tambahkan staff pertama.</p></div>
    @else
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>#</th><th>Divisi</th><th>Nama Staff</th><th>NIM</th><th>Jabatan</th><th style="text-align:center">Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @foreach($staffList as $i => $s)
                <tr>
                    <td style="color:var(--dim)">{{ $staffList->firstItem() + $i }}</td>
                    <td><span class="badge badge-blue">{{ $s->divisi->kode ?? '-' }}</span></td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,var(--accent),var(--purple));display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.78rem;flex-shrink:0">
                                {{ strtoupper(substr($s->nama, 0, 1)) }}
                            </div>
                            <div style="font-weight:600">{{ $s->nama }}</div>
                        </div>
                    </td>
                    <td class="mono" style="color:var(--muted)">{{ $s->nim }}</td>
                    <td style="font-size:.82rem;color:var(--muted)">{{ $s->jabatan }}</td>
                    <td style="text-align:center">
                        @if($s->aktif) <span class="badge badge-green">Aktif</span>
                        @else <span class="badge badge-red">Nonaktif</span> @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('admin.penilaian.edit', $s) }}" class="btn btn-outline btn-xs">📋 Nilai</a>
                            <a href="{{ route('admin.staff.edit', $s) }}" class="btn btn-outline btn-xs">✏️</a>
                            <form method="POST" action="{{ route('admin.staff.destroy', $s) }}" onsubmit="return confirm('Hapus {{ $s->nama }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="padding:16px 20px">{{ $staffList->links() }}</div>
    @endif
</div>
@endsection
