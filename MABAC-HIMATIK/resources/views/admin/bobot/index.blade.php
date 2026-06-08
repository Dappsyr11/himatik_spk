@extends('layouts.app')
@section('title', 'Bobot Kriteria')
@section('breadcrumb', 'Kelola Data / Bobot Kriteria')

@section('content')

<!-- Periode Selector -->

<div style="display:grid;grid-template-columns:1fr 280px;gap:20px;align-items:start">

<form method="POST" action="{{ route('admin.bobot.store') }}" id="form-bobot">
    @csrf
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">⚖️ Pengaturan Bobot Kriteria</div>
                <div style="font-size:.78rem;color:var(--muted);margin-top:3px">Total bobot semua kriteria harus tepat = <strong>1.00</strong></div>
            </div>
        </div>
        <div class="card-body">

            @foreach($kriteria as $k)
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:16px;padding-bottom:16px;border-bottom:1px solid var(--border)">
                <div style="width:40px;flex-shrink:0">
                    <span class="badge badge-blue" style="width:100%;justify-content:center">{{ $k->kode }}</span>
                </div>
                <div style="flex:1">
                    <div style="font-weight:600;font-size:.875rem">{{ $k->nama }}</div>
                    <div style="display:flex;align-items:center;gap:6px;margin-top:3px">
                        @if($k->tipe=='benefit')
                            <span class="badge badge-green" style="font-size:.65rem">Benefit ↑</span>
                        @else
                            <span class="badge badge-red" style="font-size:.65rem">Cost ↓</span>
                        @endif
                        <span style="font-size:.72rem;color:var(--dim)">{{ $k->deskripsi }}</span>
                    </div>
                </div>
                <div style="width:140px;flex-shrink:0">
                    <div style="display:flex;align-items:center;gap:8px">
                        <input type="number"
                            name="bobot[{{ $k->id }}]"
                            id="bobot-{{ $k->id }}"
                            value="{{ old("bobot.{$k->id}", number_format($bobotMap[$k->id] ?? 0, 4, '.', '')) }}"
                            step="0.0001" min="0" max="1"
                            class="bobot-input"
                            style="text-align:right;font-family:'JetBrains Mono',monospace;font-weight:700"
                            oninput="updateTotal()">
                        <span style="font-size:.78rem;color:var(--muted);flex-shrink:0" id="pct-{{ $k->id }}">
                            {{ number_format(($bobotMap[$k->id] ?? 0) * 100, 1) }}%
                        </span>
                    </div>
                </div>
            </div>
            @endforeach

            <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:8px">
                <button type="button" onclick="distributeEqual()" class="btn btn-outline">⚖️ Bagi Rata</button>
                <button type="submit" class="btn btn-primary">💾 Simpan Bobot</button>
            </div>
        </div>
    </div>
</form>

<!-- Total Panel -->
<div>
    <div class="card" style="position:sticky;top:80px">
        <div class="card-header"><div class="card-title">📊 Total Bobot</div></div>
        <div class="card-body" style="text-align:center;padding:28px 20px">
            <div id="total-display" style="font-size:3rem;font-weight:800;font-family:'JetBrains Mono',monospace;line-height:1;margin-bottom:8px">
                {{ number_format($totalBobot, 4) }}
            </div>
            <div id="total-status" style="font-size:.85rem;font-weight:600;padding:6px 16px;border-radius:999px;display:inline-block">
                @if(abs($totalBobot - 1.0) < 0.001)
                    <span style="background:rgba(54,211,153,.1);color:var(--green);border:1px solid rgba(54,211,153,.25);padding:6px 16px;border-radius:999px">✅ Valid</span>
                @else
                    <span style="background:rgba(255,92,92,.1);color:var(--red);border:1px solid rgba(255,92,92,.25);padding:6px 16px;border-radius:999px">❌ Harus = 1.00</span>
                @endif
            </div>

            <div style="margin-top:24px;text-align:left">
                <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:12px">Distribusi Bobot</div>
                @foreach($kriteria as $k)
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px">
                    <div style="font-size:.7rem;color:var(--muted);width:28px">{{ $k->kode }}</div>
                    <div class="progress" style="flex:1;height:8px">
                        <div class="progress-bar" id="bar-{{ $k->id }}" style="width:{{ ($bobotMap[$k->id] ?? 0) * 100 }}%"></div>
                    </div>
                    <div class="mono" id="bar-pct-{{ $k->id }}" style="font-size:.68rem;color:var(--muted);width:34px;text-align:right">
                        {{ number_format(($bobotMap[$k->id] ?? 0) * 100, 1) }}%
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

</div>

@push('scripts')
<script>
const kriteriaIds = {!! $kriteria->pluck('id')->toJson() !!};

function updateTotal() {
    let total = 0;
    kriteriaIds.forEach(id => {
        const val = parseFloat(document.getElementById(`bobot-${id}`)?.value || 0);
        total += val;
        const pct = (val * 100).toFixed(1) + '%';
        const pctEl = document.getElementById(`pct-${id}`);
        const barEl = document.getElementById(`bar-${id}`);
        const barPctEl = document.getElementById(`bar-pct-${id}`);
        if (pctEl) pctEl.textContent = pct;
        if (barEl) barEl.style.width = Math.min(val * 100, 100) + '%';
        if (barPctEl) barPctEl.textContent = pct;
    });

    const disp = document.getElementById('total-display');
    const stat = document.getElementById('total-status');
    if (disp) disp.textContent = total.toFixed(4);

    const isValid = Math.abs(total - 1.0) < 0.001;
    if (stat) {
        stat.innerHTML = isValid
            ? '<span style="background:rgba(54,211,153,.1);color:var(--green);border:1px solid rgba(54,211,153,.25);padding:6px 16px;border-radius:999px">✅ Valid</span>'
            : '<span style="background:rgba(255,92,92,.1);color:var(--red);border:1px solid rgba(255,92,92,.25);padding:6px 16px;border-radius:999px">❌ Harus = 1.00</span>';
    }
    if (disp) disp.style.color = isValid ? 'var(--green)' : 'var(--red)';
}

function distributeEqual() {
    const n = kriteriaIds.length;
    const eq = (1 / n).toFixed(4);
    kriteriaIds.forEach(id => {
        const inp = document.getElementById(`bobot-${id}`);
        if (inp) inp.value = eq;
    });
    updateTotal();
}

updateTotal();
</script>
@endpush

@endsection
