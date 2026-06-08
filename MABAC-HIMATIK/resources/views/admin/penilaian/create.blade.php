@extends('layouts.app')
@section('title', 'Input Penilaian Staff')
@section('breadcrumb', 'Input Penilaian / Tambah')

@section('topbar-actions')
    <a href="{{ route('admin.penilaian.index') }}" class="btn btn-outline btn-sm">← Kembali</a>
@endsection

@push('styles')
<style>
    /* ── Nilai Card ─────────────────────────────────────────── */
    .nilai-card {
        border-radius: 8px;
        border: 2px solid var(--border-2);
        background: var(--surface-2);
        padding: 8px 4px 7px;
        text-align: center;
        cursor: pointer;
        transition: all .15s;
        user-select: none;
        -webkit-user-select: none;
        position: relative;
    }
    .nilai-card:hover { border-color: var(--accent); transform: translateY(-1px); }

    .nilai-num-sm  { font-size: 1.2rem; font-weight: 800; font-family: 'JetBrains Mono', monospace; line-height: 1; margin-bottom: 3px; }
    .nilai-lbl-sm  { font-size: .55rem; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; color: var(--muted); }

    /* warna teks per nilai */
    .nilai-card[data-nilai="5"] .nilai-num-sm,
    .nilai-card[data-nilai="4"] .nilai-num-sm { color: #36D399; }
    .nilai-card[data-nilai="3"] .nilai-num-sm { color: var(--accent-2); }
    .nilai-card[data-nilai="2"] .nilai-num-sm,
    .nilai-card[data-nilai="1"] .nilai-num-sm { color: #FF5C5C; }

    /* state aktif */
    .nilai-card.active[data-nilai="5"],
    .nilai-card.active[data-nilai="4"] { border-color: #36D399; background: rgba(54,211,153,.12); box-shadow: 0 0 0 3px rgba(54,211,153,.12); }
    .nilai-card.active[data-nilai="3"] { border-color: var(--accent); background: rgba(91,127,255,.12); box-shadow: 0 0 0 3px var(--accent-glow); }
    .nilai-card.active[data-nilai="2"],
    .nilai-card.active[data-nilai="1"] { border-color: #FF5C5C; background: rgba(255,92,92,.12); box-shadow: 0 0 0 3px rgba(255,92,92,.12); }

    .nilai-card.active::after {
        content: '✓';
        position: absolute;
        top: 2px; right: 4px;
        font-size: .55rem; font-weight: 900; opacity: .8;
    }

    /* ── Staff Block ─────────────────────────────────────────── */
    .staff-block {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 14px;
        margin-bottom: 14px;
        overflow: hidden;
        transition: border-color .2s;
    }
    .staff-block:hover { border-color: var(--border-2); }
    .staff-block.complete { border-color: rgba(54,211,153,.3); }

    .staff-head {
        padding: 12px 18px;
        background: var(--surface-2);
        display: flex;
        align-items: center;
        gap: 12px;
        border-bottom: 1px solid var(--border);
        cursor: pointer;
    }
    .staff-avatar {
        width: 36px; height: 36px;
        border-radius: 9px;
        background: linear-gradient(135deg,var(--accent),var(--purple));
        display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: .88rem; flex-shrink: 0;
    }
    .staff-nama   { font-weight: 700; font-size: .88rem; }
    .staff-meta   { font-size: .72rem; color: var(--muted); margin-top:2px; }

    .staff-body   { padding: 14px 18px; }

    .k-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
    }
    .k-kode { width: 34px; flex-shrink: 0; }
    .k-nama { flex: 1; font-size: .8rem; color: var(--muted); }
    .k-grid {
        display: grid;
        grid-template-columns: repeat(5, 44px);
        gap: 5px;
    }
</style>
@endpush

@section('content')

{{-- Top Config Bar --}}
<div class="card" style="margin-bottom:18px">
    <div class="card-body" style="padding:16px 20px">
        <div style="display:flex;gap:20px;align-items:flex-end;flex-wrap:wrap">
            <div class="form-group" style="margin:0;flex:0 0 160px">
                <label>Periode</label>
                <input type="text" id="inp-periode" value="{{ request('periode', now()->format('Y-m')) }}" placeholder="{{ now()->format('Y-m') }}">
            </div>
            <div class="form-group" style="margin:0;flex:1;min-width:200px">
                <label>Filter Divisi</label>
                <select id="sel-divisi" onchange="filterDivisi(this.value)">
                    <option value="">— Semua Divisi —</option>
                    @foreach($divisiList as $d)
                        <option value="{{ $d->id }}" {{ request('divisi_id') == $d->id ? 'selected' : '' }}>
                            {{ $d->kode }} — {{ $d->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div style="flex-shrink:0">
                <div style="font-size:.72rem;color:var(--muted);margin-bottom:6px;text-transform:uppercase;letter-spacing:.05em">Keterangan nilai:</div>
                <div style="display:flex;gap:10px;align-items:center">
                    <span style="color:#36D399;font-weight:700;font-family:'JetBrains Mono',monospace;font-size:.82rem">4–5</span>
                    <span style="font-size:.75rem;color:var(--muted)">Baik/Sangat Baik</span>
                    <span style="color:var(--accent-2);font-weight:700;font-family:'JetBrains Mono',monospace;font-size:.82rem">3</span>
                    <span style="font-size:.75rem;color:var(--muted)">Cukup</span>
                    <span style="color:#FF5C5C;font-weight:700;font-family:'JetBrains Mono',monospace;font-size:.82rem">1–2</span>
                    <span style="font-size:.75rem;color:var(--muted)">Kurang</span>
                </div>
            </div>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('admin.penilaian.store') }}" id="form-penilaian">
    @csrf
    <input type="hidden" name="periode" id="hid-periode" value="{{ request('periode', now()->format('Y-m')) }}">

    @foreach($staffList as $staff)
    <div class="staff-block" data-divisi="{{ $staff->divisi_id }}" id="sb-{{ $staff->id }}">

        {{-- Staff Header --}}
        <div class="staff-head" onclick="toggleStaff({{ $staff->id }})">
            <div class="staff-avatar">{{ strtoupper(substr($staff->nama, 0, 1)) }}</div>
            <div style="flex:1">
                <div class="staff-nama">{{ $staff->nama }}</div>
                <div class="staff-meta">
                    <span class="mono">{{ $staff->nim }}</span> &nbsp;·&nbsp;
                    {{ $staff->jabatan }} &nbsp;·&nbsp;
                    <span class="badge badge-blue" style="font-size:.62rem">{{ $staff->divisi->kode }}</span>
                </div>
            </div>
            <div id="prog-{{ $staff->id }}" style="font-size:.75rem;color:var(--muted);flex-shrink:0">
                0/{{ $kriteria->count() }}
            </div>
            <span id="chevron-{{ $staff->id }}" style="color:var(--muted);margin-left:8px">▾</span>
        </div>

        {{-- Staff Body --}}
        <div class="staff-body" id="body-{{ $staff->id }}">
            {{-- Hidden radios --}}
            @foreach($kriteria as $k)
                @foreach($skala as $s)
                <input type="radio"
                    name="penilaian[{{ $staff->id }}][{{ $k->id }}]"
                    id="r-{{ $staff->id }}-{{ $k->id }}-{{ $s->nilai }}"
                    value="{{ $s->nilai }}"
                    style="position:absolute;opacity:0;pointer-events:none">
                @endforeach
            @endforeach

            {{-- Kriteria rows --}}
            @foreach($kriteria as $k)
            <div class="k-row">
                <span class="badge badge-blue k-kode" style="font-size:.65rem;justify-content:center">{{ $k->kode }}</span>
                <span class="k-nama">{{ $k->nama }}</span>
                <div class="k-grid">
                    @foreach($skala as $s)
                    <div class="nilai-card"
                         data-staff="{{ $staff->id }}"
                         data-kriteria="{{ $k->id }}"
                         data-nilai="{{ $s->nilai }}"
                         onclick="pilihNilai(this)"
                         title="{{ $s->label }}">
                        <div class="nilai-num-sm">{{ $s->nilai }}</div>
                        <div class="nilai-lbl-sm">{{ Str::limit($s->label, 6, '') }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach

    {{-- Submit --}}
    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:8px;gap:12px;padding:16px;background:var(--surface);border:1px solid var(--border);border-radius:var(--r-lg)">
        <div id="warn-msg" style="font-size:.82rem;color:#FF5C5C;display:none">
            ⚠️ Ada staff yang belum dinilai lengkap. Pastikan semua kriteria terisi.
        </div>
        <div style="margin-left:auto;display:flex;gap:10px">
            <a href="{{ route('admin.penilaian.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary" onclick="return cekSemua()">
                💾 Simpan Semua Penilaian
            </button>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
const TOTAL_K = {{ $kriteria->count() }};

// ── Pilih Nilai ──────────────────────────────────────────────
function pilihNilai(card) {
    const staffId    = card.dataset.staff;
    const kriteriaId = card.dataset.kriteria;
    const nilai      = card.dataset.nilai;

    // Hapus active semua kartu dalam grup yang sama
    document.querySelectorAll(
        `.nilai-card[data-staff="${staffId}"][data-kriteria="${kriteriaId}"]`
    ).forEach(c => c.classList.remove('active'));

    // Aktifkan kartu ini
    card.classList.add('active');

    // Centang radio
    const radio = document.getElementById(`r-${staffId}-${kriteriaId}-${nilai}`);
    if (radio) radio.checked = true;

    updateProgress(staffId);
}

// ── Update Progress per Staff ───────────────────────────────
function updateProgress(staffId) {
    const aktif = new Set(
        [...document.querySelectorAll(`.nilai-card.active[data-staff="${staffId}"]`)]
            .map(c => c.dataset.kriteria)
    );
    const jumlah   = aktif.size;
    const progEl   = document.getElementById(`prog-${staffId}`);
    const blockEl  = document.getElementById(`sb-${staffId}`);

    if (progEl) {
        progEl.textContent = `${jumlah}/${TOTAL_K}`;
        progEl.style.color = jumlah === TOTAL_K ? '#36D399' : 'var(--muted)';
    }
    if (blockEl) {
        if (jumlah === TOTAL_K) blockEl.classList.add('complete');
        else blockEl.classList.remove('complete');
    }
}

// ── Toggle Collapse Staff ──────────────────────────────────
function toggleStaff(staffId) {
    const body    = document.getElementById(`body-${staffId}`);
    const chevron = document.getElementById(`chevron-${staffId}`);
    if (!body) return;
    if (body.style.display === 'none') {
        body.style.display = '';
        if (chevron) chevron.textContent = '▾';
    } else {
        body.style.display = 'none';
        if (chevron) chevron.textContent = '▸';
    }
}

// ── Filter Divisi ──────────────────────────────────────────
function filterDivisi(divisiId) {
    document.querySelectorAll('.staff-block').forEach(el => {
        el.style.display = (!divisiId || el.dataset.divisi == divisiId) ? '' : 'none';
    });
}

// ── Sinkronkan periode ─────────────────────────────────────
document.getElementById('inp-periode').addEventListener('change', function() {
    document.getElementById('hid-periode').value = this.value;
});

// ── Validasi sebelum submit ────────────────────────────────
function cekSemua() {
    document.getElementById('hid-periode').value =
        document.getElementById('inp-periode').value;

    // Cek hanya staff yang terlihat
    const visible = [...document.querySelectorAll('.staff-block')]
        .filter(el => el.style.display !== 'none');

    let belumLengkap = false;
    visible.forEach(el => {
        const staffId = el.id.replace('sb-', '');
        const aktif   = new Set(
            [...document.querySelectorAll(`.nilai-card.active[data-staff="${staffId}"]`)]
                .map(c => c.dataset.kriteria)
        );
        if (aktif.size < TOTAL_K) {
            belumLengkap = true;
            el.style.borderColor = 'rgba(255,92,92,.5)';
        } else {
            el.style.borderColor = '';
        }
    });

    if (belumLengkap) {
        document.getElementById('warn-msg').style.display = 'block';
        window.scrollTo({ top: 0, behavior: 'smooth' });
        return false;
    }
    return true;
}

// ── Apply filter awal ──────────────────────────────────────
filterDivisi(document.getElementById('sel-divisi').value);
</script>
@endpush
