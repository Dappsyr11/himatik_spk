@extends('layouts.app')
@section('title', 'Detail Perhitungan MABAC — ' . $divisi->nama)
@section('breadcrumb', 'Hasil / Detail Matriks')

@section('topbar-actions')
    <a href="{{ route('admin.hasil.index') }}?periode={{ $periode }}" class="btn btn-outline btn-sm">← Kembali</a>
@endsection

@push('styles')
<style>
    .step-card { background:var(--surface); border:1px solid var(--border); border-radius:var(--r-lg); margin-bottom:20px; overflow:hidden; }
    .step-header { padding:16px 22px; border-bottom:1px solid var(--border); display:flex; align-items:flex-start; gap:16px; }
    .step-num { width:32px; height:32px; border-radius:8px; background:linear-gradient(135deg,var(--accent),var(--purple)); display:flex; align-items:center; justify-content:center; font-weight:800; font-size:.85rem; flex-shrink:0; }
    .step-title { font-size:1rem; font-weight:700; margin-bottom:4px; }
    .step-formula { display:inline-block; background:var(--surface-2); border:1px solid var(--border-2); border-radius:6px; padding:3px 10px; font-family:'JetBrains Mono',monospace; font-size:.78rem; color:var(--gold); }
    .step-desc { font-size:.78rem; color:var(--muted); margin-top:4px; }

    .mat-table { width:100%; border-collapse:collapse; font-size:.82rem; }
    .mat-table th { padding:10px 12px; font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--muted); background:var(--surface-2); border-bottom:1px solid var(--border); text-align:center; white-space:nowrap; }
    .mat-table th:first-child { text-align:left; min-width:150px; }
    .mat-table td { padding:9px 12px; border-bottom:1px solid var(--border); text-align:center; font-family:'JetBrains Mono',monospace; font-size:.78rem; }
    .mat-table td:first-child { text-align:left; font-family:'Sora',sans-serif; font-size:.82rem; }
    .mat-table tbody tr:last-child td { border-bottom:none; }
    .mat-table tbody tr:hover { background:rgba(255,255,255,.02); }
    .row-terbaik { background:rgba(255,181,71,.04) !important; }
    .pos { color:#36D399; }
    .neg { color:#FF5C5C; }
    .si-bar-wrap { display:flex; align-items:center; gap:10px; }
    .si-bar { flex:1; height:8px; background:var(--border); border-radius:999px; overflow:hidden; }
    .si-fill { height:100%; border-radius:999px; }
</style>
@endpush

@section('content')

@php
    // Sort kriteria by kode properly (C1,C2,...C10 bukan alfabetikal)
    $kriteria = $kriteria->sortBy(fn($k) => (int) ltrim($k->kode, 'C'))->values();
    $kriteriaIds = $kriteria->pluck('id')->toArray();

    // Ambil Si min/max untuk bar chart
    $siValues = $hasilList->pluck('nilai_akhir')->toArray();
    $siMin    = count($siValues) ? min($siValues) : 0;
    $siMax    = count($siValues) ? max($siValues) : 1;
    $siRange  = ($siMax - $siMin) ?: 1;
@endphp

{{-- Header --}}
<div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;margin-bottom:24px;flex-wrap:wrap">
    <div>
        <div style="font-size:1.3rem;font-weight:800;margin-bottom:6px">📐 Detail Perhitungan MABAC</div>
        <div style="font-size:.875rem;color:var(--muted)">
            Divisi: <strong style="color:var(--text)">{{ $divisi->nama }}</strong>
            &nbsp;·&nbsp; Periode: <strong style="color:var(--accent)">{{ $periode }}</strong>
            &nbsp;·&nbsp; {{ $hasilList->count() }} alternatif · {{ $kriteria->count() }} kriteria
        </div>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap">
        @foreach($periodeList as $p)
            <a href="{{ route('admin.hasil.detail', $divisi) }}?periode={{ $p }}"
               class="btn btn-sm {{ $p == $periode ? 'btn-primary' : 'btn-outline' }}">{{ $p }}</a>
        @endforeach
    </div>
</div>

{{-- ── BOBOT KRITERIA ── --}}
<div class="step-card">
    <div class="step-header">
        <div class="step-num" style="background:var(--surface-2);color:var(--muted);border:1px solid var(--border)">W</div>
        <div>
            <div class="step-title">Bobot Kriteria (W)</div>
            <div class="step-desc">Total bobot semua kriteria = <strong style="color:var(--gold)">{{ number_format($bobot->sum(), 4) }}</strong></div>
        </div>
    </div>
    <div style="padding:16px 22px;display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:10px">
        @foreach($kriteria as $k)
        @php $w = (float)($bobot[$k->id] ?? 0); @endphp
        <div style="background:var(--surface-2);border:1px solid var(--border);border-radius:10px;padding:12px 14px">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px">
                <span class="badge badge-blue" style="font-size:.68rem">{{ $k->kode }}</span>
                <span class="mono" style="font-weight:800;color:var(--gold);font-size:.85rem">{{ number_format($w, 4) }}</span>
            </div>
            <div style="font-size:.78rem;font-weight:600;margin-bottom:6px">{{ $k->nama }}</div>
            <div style="background:var(--border);border-radius:999px;height:5px;overflow:hidden">
                <div style="height:100%;width:{{ $w*100 }}%;background:linear-gradient(90deg,var(--accent),var(--purple));border-radius:999px"></div>
            </div>
            <div style="font-size:.68rem;color:var(--muted);margin-top:4px;display:flex;justify-content:space-between">
                <span>{{ $k->tipe == 'benefit' ? 'Benefit ↑' : 'Cost ↓' }}</span>
                <span>{{ number_format($w*100, 1) }}%</span>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- ── LANGKAH 1: Matriks X ── --}}
<div class="step-card">
    <div class="step-header">
        <div class="step-num">1</div>
        <div>
            <div class="step-title">Matriks Keputusan (X)</div>
            <span class="step-formula">x<sub>ij</sub> = nilai penilaian staff i pada kriteria j</span>
            <div class="step-desc" style="margin-top:6px">Nilai asli hasil penilaian. Skala 1 (Sangat Kurang) – 5 (Sangat Baik).</div>
        </div>
    </div>
    <div style="overflow-x:auto">
        <table class="mat-table">
            <thead>
                <tr>
                    <th>Staff / Alternatif</th>
                    @foreach($kriteria as $k)<th>{{ $k->kode }}</th>@endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($hasilList as $h)
                @php $rowX = $matriksX[$h->staff_id] ?? []; @endphp
                <tr class="{{ $h->terbaik ? 'row-terbaik' : '' }}">
                    <td>
                        <div style="font-weight:600">{{ $h->terbaik ? '⭐ ' : '' }}{{ $h->staff->nama }}</div>
                        <div class="mono" style="font-size:.68rem;color:var(--muted)">{{ $h->staff->nim }}</div>
                    </td>
                    @foreach($kriteriaIds as $kid)
                    @php $val = $rowX[(string)$kid] ?? ($rowX[$kid] ?? null); @endphp
                    <td style="font-weight:700;color:{{ $val >= 4 ? '#36D399' : ($val == 3 ? 'var(--accent-2)' : ($val !== null ? '#FF5C5C' : 'var(--dim)')) }}">
                        {{ $val !== null ? $val : '–' }}
                    </td>
                    @endforeach
                </tr>
                @endforeach
                {{-- x_max --}}
                <tr style="background:rgba(255,255,255,.02);border-top:2px solid var(--border)">
                    <td style="color:var(--green);font-weight:700;font-size:.75rem">Nilai Tertinggi</td>
                    @foreach($kriteriaIds as $kid)
                    @php
                        $col = array_filter(array_map(fn($r) => $r[(string)$kid] ?? ($r[$kid] ?? null), $matriksX), fn($v) => $v !== null);
                        $xmax = $col ? max($col) : '–';
                    @endphp
                    <td style="color:var(--green);font-weight:700">{{ $xmax }}</td>
                    @endforeach
                </tr>
                {{-- x_min --}}
                <tr style="background:rgba(255,255,255,.01)">
                    <td style="color:#FF5C5C;font-weight:700;font-size:.75rem">Nilai Terendah</td>
                    @foreach($kriteriaIds as $kid)
                    @php
                        $col = array_filter(array_map(fn($r) => $r[(string)$kid] ?? ($r[$kid] ?? null), $matriksX), fn($v) => $v !== null);
                        $xmin = $col ? min($col) : '–';
                    @endphp
                    <td style="color:#FF5C5C;font-weight:700">{{ $xmin }}</td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
</div>

{{-- ── LANGKAH 2: Normalisasi N ── --}}
<div class="step-card">
    <div class="step-header">
        <div class="step-num" style="background:linear-gradient(135deg,var(--purple),#8B5CF6)">2</div>
        <div>
            <div class="step-title">Matriks Ternormalisasi (N)</div>
            <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:4px">
                <span class="step-formula">Benefit: n<sub>ij</sub> = (x − x_min) / (x_max − x_min)</span>
                <span class="step-formula">Cost: n<sub>ij</sub> = (x_max − x) / (x_max − x_min)</span>
            </div>
            <div class="step-desc" style="margin-top:6px">Nilai dinormalisasi ke rentang [0, 1]. Jika x_max = x_min maka nilai = 1.</div>
        </div>
    </div>
    <div style="overflow-x:auto">
        <table class="mat-table">
            <thead>
                <tr>
                    <th>Staff</th>
                    @foreach($kriteria as $k)
                    <th>{{ $k->kode }}<div style="font-size:.6rem;color:{{ $k->tipe=='benefit' ? 'var(--green)' : '#FF5C5C' }}">{{ $k->tipe=='benefit' ? '↑' : '↓' }}</div></th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($hasilList as $h)
                @php $rowN = $matriksN[$h->staff_id] ?? []; @endphp
                <tr class="{{ $h->terbaik ? 'row-terbaik' : '' }}">
                    <td><div style="font-weight:600">{{ $h->terbaik ? '⭐ ' : '' }}{{ $h->staff->nama }}</div></td>
                    @foreach($kriteriaIds as $kid)
                    @php $val = $rowN[(string)$kid] ?? ($rowN[$kid] ?? null); @endphp
                    <td style="color:var(--accent-2)">{{ $val !== null ? number_format($val, 4) : '–' }}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ── LANGKAH 3: Matriks Berbobot V ── --}}
<div class="step-card">
    <div class="step-header">
        <div class="step-num" style="background:linear-gradient(135deg,#36D399,#059669)">3</div>
        <div>
            <div class="step-title">Matriks Berbobot (V)</div>
            <span class="step-formula">v<sub>ij</sub> = w<sub>j</sub> × n<sub>ij</sub> + w<sub>j</sub></span>
            <div class="step-desc" style="margin-top:6px">Elemen normalisasi dikalikan dan ditambah bobot kriterianya.</div>
        </div>
    </div>
    <div style="overflow-x:auto">
        <table class="mat-table">
            <thead>
                <tr>
                    <th>Staff</th>
                    @foreach($kriteria as $k)<th>{{ $k->kode }}</th>@endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($hasilList as $h)
                @php $rowV = $matriksV[$h->staff_id] ?? []; @endphp
                <tr class="{{ $h->terbaik ? 'row-terbaik' : '' }}">
                    <td><div style="font-weight:600">{{ $h->terbaik ? '⭐ ' : '' }}{{ $h->staff->nama }}</div></td>
                    @foreach($kriteriaIds as $kid)
                    @php $val = $rowV[(string)$kid] ?? ($rowV[$kid] ?? null); @endphp
                    <td style="color:var(--purple)">{{ $val !== null ? number_format($val, 4) : '–' }}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ── LANGKAH 4: Batas Area G ── --}}
<div class="step-card">
    <div class="step-header">
        <div class="step-num" style="background:linear-gradient(135deg,var(--gold),#D97706)">4</div>
        <div>
            <div class="step-title">Batas Area Aproksimasi (G)</div>
            <span class="step-formula">g<sub>j</sub> = (∏ v<sub>ij</sub>)<sup>1/m</sup></span>
            <div class="step-desc" style="margin-top:6px">Rata-rata geometri dari semua elemen berbobot per kriteria. m = {{ $hasilList->count() }} staff.</div>
        </div>
    </div>
    <div style="overflow-x:auto">
        <table class="mat-table">
            <thead>
                <tr>
                    <th>Elemen</th>
                    @foreach($kriteria as $k)<th>{{ $k->kode }}</th>@endforeach
                </tr>
            </thead>
            <tbody>
                {{-- Batas G --}}
                <tr style="background:rgba(255,181,71,.06)">
                    <td style="font-weight:700;color:var(--gold)">g<sub>j</sub> (Batas Area)</td>
                    @foreach($kriteriaIds as $kid)
                    @php $g = $batasG[(string)$kid] ?? ($batasG[$kid] ?? null); @endphp
                    <td style="font-weight:700;color:var(--gold)">{{ $g !== null ? number_format($g, 4) : '–' }}</td>
                    @endforeach
                </tr>
                {{-- V per staff sebagai referensi --}}
                @foreach($hasilList as $h)
                @php $rowV = $matriksV[$h->staff_id] ?? []; @endphp
                <tr>
                    <td style="color:var(--muted);font-size:.75rem">v ({{ $h->staff->nama }})</td>
                    @foreach($kriteriaIds as $kid)
                    @php $val = $rowV[(string)$kid] ?? ($rowV[$kid] ?? null); @endphp
                    <td style="color:var(--dim);font-size:.75rem">{{ $val !== null ? number_format($val, 4) : '–' }}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ── LANGKAH 5: Jarak Q ── --}}
<div class="step-card">
    <div class="step-header">
        <div class="step-num" style="background:linear-gradient(135deg,#FF7A5A,#EF4444)">5</div>
        <div>
            <div class="step-title">Matriks Jarak dari Batas Area (Q)</div>
            <span class="step-formula">q<sub>ij</sub> = v<sub>ij</sub> − g<sub>j</sub></span>
            <div class="step-desc" style="margin-top:6px">
                <span style="color:#36D399">Positif (+)</span> = di atas batas (BAA) &nbsp;·&nbsp;
                <span style="color:#FF5C5C">Negatif (−)</span> = di bawah batas (BAA)
            </div>
        </div>
    </div>
    <div style="overflow-x:auto">
        <table class="mat-table">
            <thead>
                <tr>
                    <th>Staff</th>
                    @foreach($kriteria as $k)<th>{{ $k->kode }}</th>@endforeach
                    <th style="background:rgba(255,181,71,.08);color:var(--gold)">Si = ΣQ</th>
                </tr>
            </thead>
            <tbody>
                {{-- Batas G referensi --}}
                <tr style="background:rgba(255,181,71,.04);border-bottom:2px solid rgba(255,181,71,.3)">
                    <td style="font-size:.72rem;color:var(--gold);font-weight:700">g<sub>j</sub> (Batas Area)</td>
                    @foreach($kriteriaIds as $kid)
                    @php $g = $batasG[(string)$kid] ?? ($batasG[$kid] ?? null); @endphp
                    <td style="color:var(--gold);font-weight:700;font-size:.75rem">{{ $g !== null ? number_format($g, 4) : '–' }}</td>
                    @endforeach
                    <td></td>
                </tr>
                @foreach($hasilList as $h)
                @php $rowQ = $matriksQ[$h->staff_id] ?? []; @endphp
                <tr class="{{ $h->terbaik ? 'row-terbaik' : '' }}">
                    <td>
                        <div style="font-weight:{{ $h->terbaik ? '700' : '500' }}">
                            {{ $h->terbaik ? '⭐ ' : '' }}{{ $h->staff->nama }}
                        </div>
                        <div style="font-size:.68rem;color:var(--muted)">{{ $h->staff->jabatan }}</div>
                    </td>
                    @foreach($kriteriaIds as $kid)
                    @php $q = $rowQ[(string)$kid] ?? ($rowQ[$kid] ?? null); @endphp
                    <td class="{{ $q !== null ? ($q > 0 ? 'pos' : ($q < 0 ? 'neg' : '')) : '' }}">
                        {{ $q !== null ? (($q >= 0 ? '+' : '') . number_format($q, 4)) : '–' }}
                    </td>
                    @endforeach
                    <td style="font-weight:800;font-size:.9rem;background:rgba(255,181,71,.04);
                        color:{{ $h->terbaik ? 'var(--gold)' : ($h->nilai_akhir >= 0 ? '#36D399' : '#FF5C5C') }}">
                        {{ ($h->nilai_akhir >= 0 ? '+' : '') . number_format($h->nilai_akhir, 6) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ── LANGKAH 6: Hasil Ranking ── --}}
<div class="step-card">
    <div class="step-header">
        <div class="step-num" style="background:linear-gradient(135deg,var(--gold),#D97706)">6</div>
        <div>
            <div class="step-title">Nilai Akhir (SI) & Perankingan</div>
            <span class="step-formula">S<sub>i</sub> = Σ q<sub>ij</sub></span>
            <div class="step-desc" style="margin-top:6px">Staff dengan nilai SI terbesar adalah Staff Terbaik divisi.</div>
        </div>
    </div>
    <div style="padding:20px 22px;display:flex;flex-direction:column;gap:12px">
        @foreach($hasilList as $h)
        @php
            $pct = max(5, min(100, (($h->nilai_akhir - $siMin) / $siRange) * 100));
            $barColor = $h->terbaik
                ? 'linear-gradient(90deg,var(--gold),#F59E0B)'
                : ($h->nilai_akhir >= 0 ? 'linear-gradient(90deg,#36D399,#059669)' : 'linear-gradient(90deg,#EF4444,#FF7A5A)');
        @endphp
        <div style="background:{{ $h->terbaik ? 'rgba(255,181,71,.06)' : 'var(--surface-2)' }};border:1px solid {{ $h->terbaik ? 'rgba(255,181,71,.3)' : 'var(--border)' }};border-radius:12px;padding:16px 18px">
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:10px">
                <div style="width:36px;height:36px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.9rem;flex-shrink:0;
                    background:{{ $h->peringkat==1 ? 'rgba(255,181,71,.15)' : ($h->peringkat==2 ? 'rgba(192,192,192,.1)' : 'rgba(255,255,255,.05)') }};
                    color:{{ $h->peringkat==1 ? 'var(--gold)' : ($h->peringkat==2 ? '#C0C0C0' : 'var(--muted)') }};
                    border:1px solid {{ $h->peringkat==1 ? 'rgba(255,181,71,.3)' : 'var(--border)' }}">
                    @if($h->peringkat==1)🥇@elseif($h->peringkat==2)🥈@elseif($h->peringkat==3)🥉@else #{{ $h->peringkat }}@endif
                </div>
                <div style="flex:1">
                    <div style="font-weight:{{ $h->terbaik ? '800' : '600' }};font-size:.9rem">
                        {{ $h->staff->nama }}
                        @if($h->terbaik)<span class="badge badge-gold" style="margin-left:6px;font-size:.65rem">⭐ TERBAIK</span>@endif
                    </div>
                    <div style="font-size:.72rem;color:var(--muted);margin-top:2px">
                        <span class="mono">{{ $h->staff->nim }}</span> &nbsp;·&nbsp; {{ $h->staff->jabatan }}
                    </div>
                </div>
                <div style="text-align:right;flex-shrink:0">
                    <div style="font-size:.65rem;color:var(--muted);text-transform:uppercase;letter-spacing:.05em">Nilai Si</div>
                    <div class="mono" style="font-size:1.1rem;font-weight:800;color:{{ $h->terbaik ? 'var(--gold)' : ($h->nilai_akhir >= 0 ? '#36D399' : '#FF5C5C') }}">
                        {{ ($h->nilai_akhir >= 0 ? '+' : '') . number_format($h->nilai_akhir, 6) }}
                    </div>
                </div>
            </div>
            <div class="si-bar-wrap">
                <div style="font-size:.68rem;color:var(--muted);width:48px;text-align:right">{{ number_format($siMin, 3) }}</div>
                <div class="si-bar"><div class="si-fill" style="width:{{ $pct }}%;background:{{ $barColor }}"></div></div>
                <div style="font-size:.68rem;color:var(--muted);width:48px">{{ number_format($siMax, 3) }}</div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@endsection