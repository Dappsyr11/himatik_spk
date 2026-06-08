@extends('layouts.app')
@section('title', 'Hasil Perankingan MABAC')
@section('breadcrumb', 'Hasil Perankingan')

@section('topbar-actions')
    <a href="{{ route('admin.hasil.export') }}?periode={{ $periode }}" class="btn btn-success btn-sm">📥 Export CSV</a>
@endsection

@section('content')

{{-- Pilih Bulan --}}
<div class="card" style="margin-bottom:20px">
    <div class="card-body" style="padding:16px 20px">
        <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:10px">
            📅 Pilih Bulan — Periode HIMATIK Yugartha 2026
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:7px">
            @foreach($semuaBulan as $key => $label)
            @php $adaData = in_array($key, $bulanAdaData); @endphp
            <a href="{{ route('admin.hasil.index') }}?periode={{ $key }}"
               class="btn btn-sm {{ $key === $periode ? 'btn-primary' : 'btn-outline' }}"
               style="{{ $adaData && $key !== $periode ? 'border-color:rgba(54,211,153,.4);color:var(--green)' : '' }}">
                {{ $label }}
                @if($adaData) <span style="font-size:.55rem;margin-left:2px">●</span> @endif
            </a>
            @endforeach
        </div>
        <div style="margin-top:8px;font-size:.7rem;color:var(--muted)">
            <span style="color:var(--green)">●</span> = sudah ada data hasil perhitungan
        </div>
    </div>
</div>

{{-- Proses Perhitungan --}}
<div class="card" style="margin-bottom:20px;border-color:rgba(255,181,71,.2);background:rgba(255,181,71,.02)">
    <div class="card-body" style="padding:20px 24px">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:20px;flex-wrap:wrap">
            <div>
                <div style="font-size:1rem;font-weight:700;margin-bottom:4px">🔬 Proses Perhitungan MABAC</div>
                <div style="font-size:.82rem;color:var(--muted)">
                    Hitung perankingan untuk <strong style="color:var(--text)">{{ $semuaBulan[$periode] ?? $periode }}</strong>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.hasil.hitung') }}" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap">
                @csrf
                {{-- INPUT PERIODE WAJIB ADA --}}
                <input type="hidden" name="periode" value="{{ $periode }}">
                <div>
                    <label>Divisi (opsional)</label>
                    <select name="divisi_id" style="width:220px;padding:8px 12px">
                        <option value="">— Semua Divisi —</option>
                        @foreach($divisiList as $d)
                            <option value="{{ $d->id }}">{{ $d->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-gold">⚡ Hitung MABAC</button>
            </form>
        </div>
    </div>
</div>

{{-- Hasil Per Divisi --}}
@foreach($divisiList as $divisi)
<div class="card" style="margin-bottom:16px">
    <div class="card-header">
        <div style="display:flex;align-items:center;gap:12px">
            <span class="badge badge-blue" style="font-size:.8rem;padding:5px 12px">{{ $divisi->kode }}</span>
            <div>
                <div class="card-title">{{ $divisi->nama }}</div>
                @if($divisi->hasilMabac->isNotEmpty())
                <div style="font-size:.75rem;color:var(--muted);margin-top:2px">
                    {{ $divisi->hasilMabac->count() }} staff · {{ $semuaBulan[$periode] ?? $periode }}
                </div>
                @endif
            </div>
        </div>
        @if($divisi->hasilMabac->isNotEmpty())
            <a href="{{ route('admin.hasil.detail', $divisi) }}?periode={{ $periode }}"
               class="btn btn-outline btn-sm">📐 Detail Matriks</a>
        @endif
    </div>

    @if($divisi->hasilMabac->isEmpty())
        <div style="padding:28px;text-align:center;color:var(--dim);font-size:.875rem">
            Belum ada hasil untuk <strong>{{ $semuaBulan[$periode] ?? $periode }}</strong>
        </div>
    @else
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Peringkat</th>
                    <th>Nama Staff</th>
                    <th>NIM</th>
                    <th>Jabatan</th>
                    <th>Nilai Akhir (Si)</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($divisi->hasilMabac as $h)
                <tr style="{{ $h->terbaik ? 'background:rgba(255,181,71,.03)' : '' }}">
                    <td>
                        @if($h->peringkat == 1) <span class="rank-1">🥇 #1</span>
                        @elseif($h->peringkat == 2) <span class="rank-2">#2</span>
                        @elseif($h->peringkat == 3) <span class="rank-3">#3</span>
                        @else <span class="rank-n">#{{ $h->peringkat }}</span>
                        @endif
                    </td>
                    <td style="font-weight:{{ $h->terbaik ? '700' : '500' }}">{{ $h->staff->nama }}</td>
                    <td class="mono" style="color:var(--muted)">{{ $h->staff->nim }}</td>
                    <td style="font-size:.82rem;color:var(--muted)">{{ $h->staff->jabatan }}</td>
                    <td>
                        <span class="mono" style="font-weight:700;color:{{ $h->terbaik ? 'var(--gold)' : ($h->nilai_akhir >= 0 ? 'var(--green)' : 'var(--red)') }}">
                            {{ ($h->nilai_akhir >= 0 ? '+' : '') . number_format($h->nilai_akhir, 6) }}
                        </span>
                    </td>
                    <td>
                        @if($h->terbaik) <span class="badge badge-gold">⭐ Staff Terbaik</span>
                        @else <span class="badge badge-gray">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endforeach

@endsection