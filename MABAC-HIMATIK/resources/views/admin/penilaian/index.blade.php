@extends('layouts.app')
@section('title', 'Data Penilaian Staff')
@section('breadcrumb', 'Input Penilaian')

@section('topbar-actions')
    <a href="{{ route('admin.penilaian.create') }}" class="btn btn-primary btn-sm">✏️ Input Penilaian Baru</a>
@endsection

@section('content')

<!-- Filter -->
<div class="card" style="margin-bottom:20px">
    <div class="card-body" style="padding:16px 20px">
        <form method="GET" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap">
            <div>
                <label>Periode</label>
                <input type="text" name="periode" value="{{ $periode }}" placeholder="{{ now()->format('Y-m') }}" style="width:130px">
            </div>
            <div>
                <label>Divisi</label>
                <select name="divisi_id" style="width:220px">
                    <option value="">— Semua Divisi —</option>
                    @foreach($divisiList as $d)
                        <option value="{{ $d->id }}" {{ $divisiId == $d->id ? 'selected' : '' }}>{{ $d->nama }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">🔍 Filter</button>
            <a href="{{ route('admin.penilaian.index') }}" class="btn btn-outline">Reset</a>
        </form>
    </div>
</div>

<!-- Daftar Staff & Status Penilaian -->
<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">📋 Status Penilaian Staff</div>
            <div style="font-size:.78rem;color:var(--muted);margin-top:3px">
                Periode: <strong style="color:var(--accent)">{{ $periode }}</strong> — {{ $staffList->count() }} staff
            </div>
        </div>
        @if($periodeList->isNotEmpty())
        <div style="display:flex;gap:8px">
            @foreach($periodeList as $p)
                <a href="{{ route('admin.penilaian.index') }}?periode={{ $p }}&divisi_id={{ $divisiId }}"
                    class="btn btn-sm {{ $p == $periode ? 'btn-primary' : 'btn-outline' }}">{{ $p }}</a>
            @endforeach
        </div>
        @endif
    </div>

    @if($staffList->isEmpty())
        <div class="empty-state">
            <div class="icon">👥</div>
            <h3>Tidak ada staff ditemukan</h3>
            <p>Tambahkan data staff atau ubah filter pencarian.</p>
        </div>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Divisi</th>
                        <th>Nama Staff</th>
                        <th>NIM</th>
                        <th>Jabatan</th>
                        <th style="text-align:center">Status Penilaian</th>
                        <th style="text-align:center">Kriteria Dinilai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($staffList as $i => $staff)
                    @php
                        $jumlahDinilai = $staff->penilaian->count();
                        $totalKriteria = 10;
                        $sudahLengkap = $jumlahDinilai >= $totalKriteria;
                    @endphp
                    <tr>
                        <td style="color:var(--dim)">{{ $i+1 }}</td>
                        <td>
                            <span class="badge badge-blue">{{ $staff->divisi->kode ?? '-' }}</span>
                        </td>
                        <td>
                            <div style="font-weight:600">{{ $staff->nama }}</div>
                        </td>
                        <td class="mono" style="color:var(--muted)">{{ $staff->nim }}</td>
                        <td style="font-size:.82rem;color:var(--muted)">{{ $staff->jabatan }}</td>
                        <td style="text-align:center">
                            @if($sudahLengkap)
                                <span class="badge badge-green">✅ Lengkap</span>
                            @elseif($jumlahDinilai > 0)
                                <span class="badge badge-gold">⚠️ Sebagian</span>
                            @else
                                <span class="badge badge-red">❌ Belum dinilai</span>
                            @endif
                        </td>
                        <td style="text-align:center">
                            <span class="mono" style="font-size:.82rem;color:{{ $sudahLengkap ? 'var(--green)' : 'var(--muted)' }}">
                                {{ $jumlahDinilai }}/{{ $totalKriteria }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.penilaian.edit', $staff) }}?periode={{ $periode }}"
                                class="btn btn-outline btn-xs">
                                {{ $jumlahDinilai > 0 ? '✏️ Edit' : '➕ Nilai' }}
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection
