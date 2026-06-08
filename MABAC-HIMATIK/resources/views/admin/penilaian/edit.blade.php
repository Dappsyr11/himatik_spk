@extends('layouts.app')
@section('title', 'Edit Penilaian — ' . $staff->nama)
@section('breadcrumb', 'Input Penilaian / Edit')

@section('topbar-actions')
    <a href="{{ route('admin.penilaian.index') }}?periode={{ $periode }}" class="btn btn-outline btn-sm">← Kembali</a>
@endsection

@push('styles')
<style>
    /* ── Nilai Card ─────────────────────────────────────────── */
    .nilai-card {
        border-radius: 10px;
        border: 2px solid var(--border-2);
        background: var(--surface-2);
        padding: 12px 6px 10px;
        text-align: center;
        cursor: pointer;
        transition: all .15s;
        user-select: none;
        -webkit-user-select: none;
        position: relative;
    }
    .nilai-card:hover {
        border-color: var(--accent);
        transform: translateY(-2px);
    }

    /* Warna nilai: >6 hijau, =6 aksen, <6 merah */
    /* Skala 1–5: nilai 5 & 4 = hijau, 3 = aksen/biru, 2 & 1 = merah */
    .nilai-num { font-size: 1.6rem; font-weight: 800; font-family: 'JetBrains Mono', monospace; line-height: 1; margin-bottom: 6px; }
    .nilai-label { font-size: .62rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: var(--muted); }

    /* warna teks per nilai */
    .nilai-card[data-nilai="5"] .nilai-num { color: #36D399; }
    .nilai-card[data-nilai="4"] .nilai-num { color: #36D399; }
    .nilai-card[data-nilai="3"] .nilai-num { color: var(--accent); }
    .nilai-card[data-nilai="2"] .nilai-num { color: #FF5C5C; }
    .nilai-card[data-nilai="1"] .nilai-num { color: #FF5C5C; }

    /* state aktif per nilai */
    .nilai-card.active[data-nilai="5"] { border-color: #36D399; background: rgba(54,211,153,.12); box-shadow: 0 0 0 3px rgba(54,211,153,.15); }
    .nilai-card.active[data-nilai="4"] { border-color: #36D399; background: rgba(54,211,153,.12); box-shadow: 0 0 0 3px rgba(54,211,153,.15); }
    .nilai-card.active[data-nilai="3"] { border-color: var(--accent); background: rgba(91,127,255,.12); box-shadow: 0 0 0 3px var(--accent-glow); }
    .nilai-card.active[data-nilai="2"] { border-color: #FF5C5C; background: rgba(255,92,92,.12); box-shadow: 0 0 0 3px rgba(255,92,92,.15); }
    .nilai-card.active[data-nilai="1"] { border-color: #FF5C5C; background: rgba(255,92,92,.12); box-shadow: 0 0 0 3px rgba(255,92,92,.15); }

    /* centang aktif */
    .nilai-card.active::after {
        content: '✓';
        position: absolute;
        top: 4px; right: 6px;
        font-size: .65rem;
        font-weight: 900;
        color: inherit;
        opacity: .7;
    }

    .nilai-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 8px;
    }

    .kriteria-block {
        padding: 18px 0;
        border-bottom: 1px solid var(--border);
    }
    .kriteria-block:last-child { border-bottom: none; }

    .kriteria-header {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 14px;
    }
    .kriteria-info { flex: 1; }
    .kriteria-nama { font-weight: 700; font-size: .92rem; margin-bottom: 3px; }
    .kriteria-desc { font-size: .76rem; color: var(--muted); }
</style>
@endpush

@section('content')
<div style="max-width:900px">

    {{-- Staff Info Card --}}
    <div class="card" style="margin-bottom:20px">
        <div class="card-body" style="padding:20px;display:flex;align-items:center;gap:16px">
            <div style="width:52px;height:52px;border-radius:12px;background:linear-gradient(135deg,var(--accent),var(--purple));display:flex;align-items:center;justify-content:center;font-size:1.3rem;font-weight:800;flex-shrink:0">
                {{ strtoupper(substr($staff->nama, 0, 1)) }}
            </div>
            <div style="flex:1">
                <div style="font-size:1.05rem;font-weight:800">{{ $staff->nama }}</div>
                <div style="color:var(--muted);font-size:.82rem;margin-top:4px;display:flex;align-items:center;gap:8px;flex-wrap:wrap">
                    <span class="mono">{{ $staff->nim }}</span>
                    <span style="color:var(--border-2)">·</span>
                    <span>{{ $staff->jabatan }}</span>
                    <span style="color:var(--border-2)">·</span>
                    <span class="badge badge-blue">{{ $staff->divisi->kode ?? '-' }} — {{ $staff->divisi->nama ?? '-' }}</span>
                </div>
            </div>
            <div style="text-align:right;flex-shrink:0">
                <div style="font-size:.72rem;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px">Periode</div>
                <div style="font-size:1.1rem;font-weight:800;color:var(--accent)">{{ $periode }}</div>
            </div>
        </div>
    </div>

    {{-- Legend warna --}}
    <div style="display:flex;align-items:center;gap:16px;margin-bottom:16px;padding:12px 16px;background:var(--surface);border:1px solid var(--border);border-radius:var(--r);flex-wrap:wrap">
        <span style="font-size:.75rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em">Keterangan:</span>
        <span style="display:flex;align-items:center;gap:6px;font-size:.8rem">
            <span style="width:10px;height:10px;border-radius:50%;background:#36D399;display:inline-block"></span>
            <span style="color:#36D399;font-weight:700">Nilai 4–5</span> <span style="color:var(--muted)">= Baik / Sangat Baik</span>
        </span>
        <span style="display:flex;align-items:center;gap:6px;font-size:.8rem">
            <span style="width:10px;height:10px;border-radius:50%;background:var(--accent);display:inline-block"></span>
            <span style="color:var(--accent-2);font-weight:700">Nilai 3</span> <span style="color:var(--muted)">= Cukup</span>
        </span>
        <span style="display:flex;align-items:center;gap:6px;font-size:.8rem">
            <span style="width:10px;height:10px;border-radius:50%;background:#FF5C5C;display:inline-block"></span>
            <span style="color:#FF5C5C;font-weight:700">Nilai 1–2</span> <span style="color:var(--muted)">= Kurang / Sangat Kurang</span>
        </span>
    </div>

    <form method="POST" action="{{ route('admin.penilaian.update', $staff) }}" id="form-penilaian">
        @csrf @method('PUT')
        <input type="hidden" name="periode" value="{{ $periode }}">

        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">📋 Penilaian Per Kriteria</div>
                    <div style="font-size:.78rem;color:var(--muted);margin-top:2px">Klik kartu untuk memilih nilai. Semua kriteria wajib diisi.</div>
                </div>
                <div id="progress-bar-wrap" style="display:flex;align-items:center;gap:10px">
                    <span id="progress-text" style="font-size:.8rem;color:var(--muted);white-space:nowrap">0 / {{ $kriteria->count() }} diisi</span>
                    <div class="progress" style="width:120px;height:8px">
                        <div class="progress-bar" id="main-progress" style="width:0%"></div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                @foreach($kriteria as $idx => $k)
                @php $currentNilai = $penilaianMap[$k->id] ?? null; @endphp
                <div class="kriteria-block">
                    <div class="kriteria-header">
                        <span class="badge badge-blue" style="font-size:.78rem;padding:4px 10px;flex-shrink:0;margin-top:2px">{{ $k->kode }}</span>
                        <div class="kriteria-info">
                            <div class="kriteria-nama">{{ $k->nama }}</div>
                            @if($k->deskripsi)
                            <div class="kriteria-desc">{{ $k->deskripsi }}</div>
                            @endif
                        </div>
                        <span class="badge {{ $k->tipe == 'benefit' ? 'badge-green' : 'badge-red' }}" style="flex-shrink:0">
                            {{ $k->tipe == 'benefit' ? 'Benefit ↑' : 'Cost ↓' }}
                        </span>
                    </div>

                    {{-- Hidden radio inputs (nilai sebenarnya) --}}
                    @foreach($skala as $s)
                    <input type="radio"
                        name="penilaian[{{ $k->id }}]"
                        id="r-{{ $k->id }}-{{ $s->nilai }}"
                        value="{{ $s->nilai }}"
                        {{ $currentNilai == $s->nilai ? 'checked' : '' }}
                        style="position:absolute;opacity:0;pointer-events:none">
                    @endforeach

                    {{-- Kartu pilihan nilai --}}
                    <div class="nilai-grid">
                        @foreach($skala as $s)
                        <div class="nilai-card {{ $currentNilai == $s->nilai ? 'active' : '' }}"
                             data-kriteria="{{ $k->id }}"
                             data-nilai="{{ $s->nilai }}"
                             onclick="pilihNilai(this)">
                            <div class="nilai-num">{{ $s->nilai }}</div>
                            <div class="nilai-label">{{ $s->label }}</div>
                        </div>
                        @endforeach
                    </div>

                    @error("penilaian.{$k->id}")
                        <div class="error-msg" style="margin-top:8px">{{ $message }}</div>
                    @enderror
                </div>
                @endforeach
            </div>
        </div>

        <div style="display:flex;justify-content:space-between;align-items:center;margin-top:20px;gap:12px">
            <div id="warning-text" style="font-size:.82rem;color:#FF5C5C;display:none">
                ⚠️ Masih ada kriteria yang belum dinilai.
            </div>
            <div style="display:flex;gap:10px;margin-left:auto">
                <a href="{{ route('admin.penilaian.index') }}?periode={{ $periode }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary" onclick="return cekLengkap()">💾 Simpan Penilaian</button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
const totalKriteria = {{ $kriteria->count() }};

function pilihNilai(card) {
    const kriteriaId = card.dataset.kriteria;
    const nilai      = card.dataset.nilai;

    // Deaktifkan semua kartu dalam grup ini
    document.querySelectorAll(`.nilai-card[data-kriteria="${kriteriaId}"]`).forEach(c => {
        c.classList.remove('active');
    });

    // Aktifkan kartu yang dipilih
    card.classList.add('active');

    // Centang radio input yang sesuai
    const radio = document.getElementById(`r-${kriteriaId}-${nilai}`);
    if (radio) radio.checked = true;

    // Update progress
    updateProgress();
}

function updateProgress() {
    const diisi = document.querySelectorAll('.nilai-card.active').length / 5; // 5 kartu per kriteria
    // Hitung kriteria yang sudah dipilih (unique)
    const kriteriaSet = new Set(
        [...document.querySelectorAll('.nilai-card.active')].map(c => c.dataset.kriteria)
    );
    const jumlah = kriteriaSet.size;
    const pct    = Math.round((jumlah / totalKriteria) * 100);

    document.getElementById('progress-text').textContent = `${jumlah} / ${totalKriteria} diisi`;
    document.getElementById('main-progress').style.width  = pct + '%';

    if (jumlah === totalKriteria) {
        document.getElementById('warning-text').style.display = 'none';
    }
}

function cekLengkap() {
    const kriteriaSet = new Set(
        [...document.querySelectorAll('.nilai-card.active')].map(c => c.dataset.kriteria)
    );
    if (kriteriaSet.size < totalKriteria) {
        document.getElementById('warning-text').style.display = 'block';
        document.getElementById('warning-text').scrollIntoView({ behavior: 'smooth', block: 'center' });
        return false;
    }
    return true;
}

// Hitung progress awal (untuk edit yang sudah ada nilainya)
updateProgress();
</script>
@endpush
