@extends('layouts.app')
@section('title', 'Data Kriteria')
@section('breadcrumb', 'Kelola Data / Kriteria')
@section('topbar-actions')
    <a href="{{ route('admin.kriteria.create') }}" class="btn btn-primary btn-sm">➕ Tambah Kriteria</a>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">📋 Kriteria Penilaian ({{ $kriteriaList->count() }} Kriteria)</div>
        <span class="badge badge-gray">Total bobot ideal = 1.00</span>
    </div>
    @if($kriteriaList->isEmpty())
        <div class="empty-state"><div class="icon">📋</div><h3>Belum ada kriteria</h3></div>
    @else
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Kode</th><th>Nama Kriteria</th><th>Tipe</th><th>Deskripsi</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @foreach($kriteriaList as $k)
                <tr>
                    <td><span class="badge badge-blue">{{ $k->kode }}</span></td>
                    <td style="font-weight:600">{{ $k->nama }}</td>
                    <td>
                        @if($k->tipe=='benefit') <span class="badge badge-green">Benefit ↑</span>
                        @else <span class="badge badge-red">Cost ↓</span> @endif
                    </td>
                    <td style="font-size:.82rem;color:var(--muted);max-width:300px">{{ $k->deskripsi ?? '—' }}</td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('admin.kriteria.edit', $k) }}" class="btn btn-outline btn-xs">✏️ Edit</a>
                            <form method="POST" action="{{ route('admin.kriteria.destroy', $k) }}" onsubmit="return confirm('Hapus kriteria {{ $k->nama }}?')">
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
