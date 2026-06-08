@extends('layouts.app')
@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="stats-grid fade-up">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(91,127,255,.12)">🏛️</div>
        <div>
            <div class="stat-value" style="color:var(--accent)">{{ $totalDivisi }}</div>
            <div class="stat-label">Total Divisi</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(54,211,153,.1)">👥</div>
        <div>
            <div class="stat-value" style="color:var(--green)">{{ $totalStaff }}</div>
            <div class="stat-label">Total Staff Aktif</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(167,139,250,.1)">📋</div>
        <div>
            <div class="stat-value" style="color:var(--purple)">{{ $totalKriteria }}</div>
            <div class="stat-label">Kriteria Penilaian</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(255,181,71,.1)">✏️</div>
        <div>
            <div class="stat-value" style="color:var(--gold)">{{ $totalPenilaian }}</div>
            <div class="stat-label">Data Penilaian</div>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 340px;gap:20px" class="fade-up">
    <!-- Staff Terbaik -->
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">🏆 Staff Terbaik Per Divisi</div>
                <div style="font-size:.78rem;color:var(--muted);margin-top:3px">
                    Periode: <strong style="color:var(--accent)">{{ $periodeTerbaru }}</strong>
                    — Berdasarkan perhitungan MABAC
                </div>
            </div>
            <div style="display:flex;gap:8px;align-items:center">
                @if($periodeList->isNotEmpty())
                <form method="GET" style="display:flex;gap:8px;align-items:center">
                    <select name="periode" onchange="this.form.submit()" style="padding:6px 10px;font-size:.78rem;width:auto">
                        @foreach($periodeList as $p)
                            <option value="{{ $p }}" {{ $p == $periodeTerbaru ? 'selected' : '' }}>{{ $p }}</option>
                        @endforeach
                    </select>
                </form>
                @endif
                <a href="{{ route('admin.hasil.index') }}" class="btn btn-outline btn-sm">Lihat Semua →</a>
            </div>
        </div>
        <div class="card-body" style="padding:0">
            @if($staffTerbaik->isEmpty())
                <div class="empty-state">
                    <div class="icon">🔬</div>
                    <h3>Belum ada hasil perhitungan</h3>
                    <p>Input penilaian staff dan jalankan perhitungan MABAC terlebih dahulu.</p>
                    <div style="margin-top:16px;display:flex;gap:10px;justify-content:center">
                        <a href="{{ route('admin.penilaian.create') }}" class="btn btn-primary btn-sm">Input Penilaian</a>
                        <a href="{{ route('admin.hasil.index') }}" class="btn btn-outline btn-sm">Proses MABAC</a>
                    </div>
                </div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Divisi</th>
                                <th>Staff Terbaik</th>
                                <th>Jabatan</th>
                                <th>Nilai Si</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($staffTerbaik as $h)
                            <tr>
                                <td><span class="rank-1">🥇 #1</span></td>
                                <td>
                                    <span class="badge badge-blue">{{ $h->staff->divisi->kode ?? '-' }}</span>
                                    <div style="font-size:.75rem;color:var(--muted);margin-top:3px">{{ $h->staff->divisi->nama ?? '-' }}</div>
                                </td>
                                <td>
                                    <div style="font-weight:600">{{ $h->staff->nama }}</div>
                                    <div class="mono" style="color:var(--muted);font-size:.72rem">{{ $h->staff->nim }}</div>
                                </td>
                                <td style="color:var(--muted);font-size:.82rem">{{ $h->staff->jabatan }}</td>
                                <td><span class="mono" style="color:var(--gold);font-weight:700">{{ number_format($h->nilai_akhir, 4) }}</span></td>
                                <td>
                                    <a href="{{ route('admin.hasil.detail', $h->divisi_id) }}?periode={{ $periodeTerbaru }}"
                                       class="btn btn-xs btn-outline">Detail</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Sidebar kanan -->
    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="card">
            <div class="card-header" style="padding:16px 20px">
                <div class="card-title">⚡ Aksi Cepat</div>
            </div>
            <div class="card-body" style="padding:14px;display:flex;flex-direction:column;gap:8px">
                <a href="{{ route('admin.penilaian.create') }}" class="btn btn-primary" style="width:100%;justify-content:center">
                    ✏️ Input Penilaian Baru
                </a>
                <a href="{{ route('admin.hasil.index') }}" class="btn btn-gold" style="width:100%;justify-content:center">
                    🔬 Proses Perhitungan MABAC
                </a>
                <a href="{{ route('admin.hasil.export') }}" class="btn btn-success" style="width:100%;justify-content:center">
                    📥 Export Hasil CSV
                </a>
                <a href="{{ route('public.index') }}" target="_blank" class="btn btn-outline" style="width:100%;justify-content:center">
                    🌐 Lihat Halaman Publik
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header" style="padding:16px 20px">
                <div class="card-title">📐 Alur Metode MABAC</div>
            </div>
            <div class="card-body" style="padding:16px 20px">
                @php
                $steps = [
                    ['1','Matriks Keputusan (X)'],
                    ['2','Normalisasi Matriks (N)'],
                    ['3','Matriks Berbobot (V)'],
                    ['4','Batas Area (G)'],
                    ['5','Jarak dari Batas (Q)'],
                    ['6','Nilai Akhir (Si)'],
                    ['7','Perankingan Staff'],
                ];
                $colors = ['var(--accent)','var(--purple)','var(--green)','var(--gold)','#FF7A5A','var(--red)','var(--gold)'];
                @endphp
                @foreach($steps as $i => $s)
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:{{ $i < count($steps)-1 ? '2px' : '0' }}">
                    <div style="width:22px;height:22px;border-radius:6px;background:{{ $colors[$i] }};display:flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:800;color:#000;opacity:.85;flex-shrink:0">{{ $s[0] }}</div>
                    <div style="font-size:.78rem;color:var(--muted)">{{ $s[1] }}</div>
                </div>
                @if($i < count($steps)-1)
                <div style="width:1px;height:10px;background:var(--border);margin-left:11px;margin-bottom:2px"></div>
                @endif
                @endforeach
            </div>
        </div>

        <div class="card" style="border-color:rgba(91,127,255,.2);background:rgba(91,127,255,.03)">
            <div class="card-body" style="padding:16px 20px">
                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--accent-2);margin-bottom:8px">Status Perhitungan</div>
                <div style="font-size:.84rem;color:var(--muted)">
                    Divisi sudah dihitung:
                    <strong style="color:var(--text)">{{ $divisiSudahDihitungCount }}</strong>
                    dari <strong style="color:var(--text)">{{ $totalDivisi }}</strong>
                </div>
                <div class="progress" style="margin-top:10px">
                    <div class="progress-bar" style="width:{{ $totalDivisi > 0 ? ($divisiSudahDihitungCount / $totalDivisi * 100) : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
