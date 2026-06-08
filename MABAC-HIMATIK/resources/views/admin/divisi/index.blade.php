@extends('layouts.app')
@section('title', 'Data Divisi')
@section('breadcrumb', 'Kelola Data / Divisi')

@section('topbar-actions')
    <a href="{{ route('admin.divisi.create') }}" class="btn btn-primary btn-sm">➕ Tambah Divisi</a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">🏛️ Daftar Divisi HIMATIK</div>
        <span class="badge badge-blue">{{ $divisiList->count() }} Divisi</span>
    </div>
    @if($divisiList->isEmpty())
        <div class="empty-state"><div class="icon">🏛️</div><h3>Belum ada divisi</h3><p>Tambahkan divisi pertama.</p></div>
    @else
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th><th>Kode</th><th>Nama Divisi</th><th>Deskripsi</th>
                    <th style="text-align:center">Staff</th><th style="text-align:center">Status</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($divisiList as $i => $d)
                <tr>
                    <td style="color:var(--dim)">{{ $i+1 }}</td>
                    <td><span class="badge badge-blue">{{ $d->kode }}</span></td>
                    <td style="font-weight:600">{{ $d->nama }}</td>
                    <td style="font-size:.82rem;color:var(--muted);max-width:260px">{{ $d->deskripsi ?? '—' }}</td>
                    <td style="text-align:center"><span class="badge badge-gray">{{ $d->staff_count }}</span></td>
                    <td style="text-align:center">
                        @if($d->aktif) <span class="badge badge-green">Aktif</span>
                        @else <span class="badge badge-red">Nonaktif</span> @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('admin.divisi.edit', $d) }}" class="btn btn-outline btn-xs">✏️ Edit</a>
                            <form method="POST" action="{{ route('admin.divisi.destroy', $d) }}" onsubmit="return confirm('Hapus divisi {{ $d->nama }}?')">
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
    @endif
</div>
@endsection
