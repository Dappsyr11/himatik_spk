<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Terbaik HIMATIK — Periode {{ $periode }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:#07090F;--surface:#0E1219;--surface-2:#141924;
            --border:#1C2333;--border-2:#263045;--text:#E8EAF0;
            --muted:#8B95A8;--dim:#4A5568;
            --accent:#5B7FFF;--accent-2:#7C9FFF;--accent-glow:rgba(91,127,255,.2);
            --gold:#FFB547;--gold-glow:rgba(255,181,71,.15);
            --green:#36D399;--red:#FF5C5C;--purple:#A78BFA;
        }
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Sora',sans-serif;background:var(--bg);color:var(--text);min-height:100vh}
        body::before{content:'';position:fixed;inset:0;
            background:radial-gradient(ellipse 80% 50% at 50% -5%,rgba(91,127,255,.1),transparent),
                        radial-gradient(ellipse 50% 40% at 100% 60%,rgba(167,139,250,.07),transparent),
                        linear-gradient(rgba(91,127,255,.02) 1px,transparent 1px),
                        linear-gradient(90deg,rgba(91,127,255,.02) 1px,transparent 1px);
            background-size:auto,auto,48px 48px,48px 48px;
            pointer-events:none;z-index:0}
        .wrap{max-width:1200px;margin:0 auto;padding:0 24px;position:relative;z-index:1}

        /* NAV */
        .navbar{position:sticky;top:0;z-index:50;background:rgba(7,9,15,.85);backdrop-filter:blur(20px);border-bottom:1px solid var(--border);padding:0 24px}
        .navbar-inner{max-width:1200px;margin:0 auto;height:60px;display:flex;align-items:center;justify-content:space-between}
        .brand{display:flex;align-items:center;gap:10px;text-decoration:none}
        .brand-icon{width:36px;height:36px;background:linear-gradient(135deg,var(--accent),var(--purple));border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:16px}
        .brand-name{font-size:.95rem;font-weight:800;color:var(--text)}
        .brand-name span{color:var(--accent)}
        .nav-links{display:flex;align-items:center;gap:6px;flex-wrap:wrap}
        .nav-link{color:var(--muted);text-decoration:none;font-size:.8rem;font-weight:500;padding:5px 11px;border-radius:7px;transition:all .15s}
        .nav-link:hover{color:var(--text);background:var(--surface)}
        .nav-link.active{color:var(--accent-2);background:rgba(91,127,255,.1)}
        .nav-btn{background:rgba(91,127,255,.1);border:1px solid rgba(91,127,255,.2);color:var(--accent-2);padding:5px 12px;border-radius:7px;text-decoration:none;font-size:.78rem;font-weight:600}

        /* HERO */
        .hero{padding:64px 0 48px;text-align:center}
        .eyebrow{display:inline-flex;align-items:center;gap:8px;background:rgba(91,127,255,.08);border:1px solid rgba(91,127,255,.2);border-radius:999px;padding:5px 14px;font-size:.72rem;font-weight:700;color:var(--accent-2);letter-spacing:.05em;text-transform:uppercase;margin-bottom:18px}
        .hero-title{font-size:clamp(1.8rem,4.5vw,3rem);font-weight:800;letter-spacing:-.03em;line-height:1.15;margin-bottom:14px}
        .grad{background:linear-gradient(135deg,var(--gold),#FF9A3C);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
        .hero-sub{font-size:.95rem;color:var(--muted);max-width:500px;margin:0 auto 28px;line-height:1.7}
        .hero-meta{display:flex;align-items:center;justify-content:center;gap:20px;flex-wrap:wrap;font-size:.8rem;color:var(--dim)}
        .hero-meta strong{color:var(--muted)}

        /* STATS */
        .stats-strip{display:grid;grid-template-columns:repeat(auto-fit,minmax(130px,1fr));gap:12px;margin-bottom:48px}
        .stat-box{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:16px;text-align:center}
        .stat-val{font-family:'JetBrains Mono',monospace;font-size:1.6rem;font-weight:800;line-height:1;margin-bottom:5px}
        .stat-lbl{font-size:.68rem;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.05em}

        /* SPOTLIGHT */
        .spotlight{background:linear-gradient(135deg,rgba(255,181,71,.08),rgba(255,122,50,.04));border:1px solid rgba(255,181,71,.25);border-radius:20px;padding:28px 32px;margin-bottom:40px;display:flex;align-items:center;gap:24px;flex-wrap:wrap;position:relative;overflow:hidden}
        .spotlight::before{content:'🏆';position:absolute;right:20px;top:12px;font-size:5rem;opacity:.07;pointer-events:none}
        .sp-avatar{width:68px;height:68px;border-radius:16px;background:linear-gradient(135deg,var(--gold),#FF9A3C);display:flex;align-items:center;justify-content:center;font-size:1.7rem;font-weight:800;flex-shrink:0;box-shadow:0 0 28px var(--gold-glow)}
        .sp-divisi{font-size:.7rem;color:var(--gold);font-weight:700;text-transform:uppercase;letter-spacing:.08em;margin-bottom:5px}
        .sp-nama{font-size:1.35rem;font-weight:800;margin-bottom:3px;letter-spacing:-.02em}
        .sp-meta{font-size:.8rem;color:var(--muted)}
        .sp-si{text-align:right;flex-shrink:0;margin-left:auto}
        .sp-si-lbl{font-size:.65rem;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:3px}
        .sp-si-val{font-family:'JetBrains Mono',monospace;font-size:1.5rem;font-weight:800;color:var(--gold)}

        /* SECTION */
        .section-title{font-size:1.15rem;font-weight:800;margin-bottom:5px}
        .section-sub{font-size:.8rem;color:var(--muted);margin-bottom:22px}

        /* DIVISI GRID */
        .divisi-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(340px,1fr));gap:14px;margin-bottom:56px}
        .divisi-card{background:var(--surface);border:1px solid var(--border);border-radius:16px;overflow:hidden;transition:all .2s}
        .divisi-card:hover{border-color:var(--border-2);transform:translateY(-2px);box-shadow:0 8px 28px rgba(0,0,0,.3)}
        .dc-head{padding:14px 18px 12px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:11px}
        .dc-icon{width:34px;height:34px;border-radius:8px;background:rgba(91,127,255,.1);border:1px solid rgba(91,127,255,.2);display:flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:800;color:var(--accent-2);flex-shrink:0}
        .dc-nama{font-size:.86rem;font-weight:700}
        .dc-total{font-size:.7rem;color:var(--muted);margin-top:1px}
        .dc-badge{margin-left:auto;font-size:.62rem;background:rgba(255,181,71,.1);color:var(--gold);border:1px solid rgba(255,181,71,.2);padding:3px 8px;border-radius:999px;font-weight:700;white-space:nowrap;max-width:120px;overflow:hidden;text-overflow:ellipsis}

        .staff-list{padding:10px 18px}
        .staff-row{display:flex;align-items:center;gap:10px;padding:9px 0;border-bottom:1px solid var(--border)}
        .staff-row:last-child{border-bottom:none}
        .rnk{width:26px;height:26px;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:.68rem;font-weight:800;flex-shrink:0}
        .rnk-1{background:rgba(255,181,71,.15);color:var(--gold);border:1px solid rgba(255,181,71,.3)}
        .rnk-2{background:rgba(192,192,192,.1);color:#C0C0C0;border:1px solid rgba(192,192,192,.2)}
        .rnk-3{background:rgba(205,127,50,.1);color:#CD7F32;border:1px solid rgba(205,127,50,.2)}
        .rnk-n{background:var(--surface-2);color:var(--muted);border:1px solid var(--border)}
        .s-info{flex:1;min-width:0}
        .s-nama{font-size:.82rem;font-weight:600;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
        .s-jabatan{font-size:.68rem;color:var(--muted);margin-top:1px}
        .s-bar{width:44px;height:4px;background:var(--border);border-radius:999px;overflow:hidden;flex-shrink:0}
        .s-fill{height:100%;border-radius:999px}
        .s-si{font-family:'JetBrains Mono',monospace;font-size:.75rem;font-weight:700;flex-shrink:0;width:64px;text-align:right}

        /* METODE */
        .metode-wrap{background:var(--surface);border:1px solid var(--border);border-radius:20px;padding:32px;margin-bottom:56px}
        .steps-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:12px;margin-top:22px}
        .step-box{background:var(--surface-2);border:1px solid var(--border);border-radius:12px;padding:14px;display:flex;gap:10px}
        .step-n{width:26px;height:26px;border-radius:7px;background:linear-gradient(135deg,var(--accent),var(--purple));display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:800;flex-shrink:0}
        .step-lbl{font-size:.8rem;font-weight:700;margin-bottom:2px}
        .step-formula{font-family:'JetBrains Mono',monospace;font-size:.68rem;color:var(--gold);margin-bottom:4px}
        .step-desc{font-size:.7rem;color:var(--muted)}

        /* KRITERIA LIST */
        .kriteria-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:8px;margin-top:16px}
        .k-box{background:var(--surface-2);border:1px solid var(--border);border-radius:10px;padding:10px 12px;display:flex;align-items:center;gap:8px}
        .k-kode{font-size:.65rem;font-weight:800;color:var(--accent-2);width:28px;flex-shrink:0}
        .k-nama{font-size:.75rem;flex:1}
        .k-tipe{font-size:.6rem;color:var(--green)}

        /* FOOTER */
        .footer{border-top:1px solid var(--border);padding:28px 24px;text-align:center;color:var(--dim);font-size:.75rem}
        .footer strong{color:var(--muted)}
        .footer a{color:var(--accent);text-decoration:none}

        @media(max-width:640px){
            .hero{padding:40px 0 32px}
            .spotlight{flex-direction:column;text-align:center}
            .sp-si{text-align:center;margin-left:0}
            .divisi-grid{grid-template-columns:1fr}
        }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="navbar-inner">
        <a href="{{ route('public.index') }}" class="brand">
            <img src="{{ asset('images/Logo Himatik.png') }}" alt="HIMATIK" style="width:48px;height:48px;object-fit:contain">
            <span class="brand-name">HIMATIK<span>SPK</span></span>
        </a>
        <div class="nav-links">
            @foreach($periodeList as $p)
                <a href="{{ route('public.index') }}?periode={{ $p }}"
                   class="nav-link {{ $p == $periode ? 'active' : '' }}">{{ $p }}</a>
            @endforeach
            <a href="{{ route('login') }}" class="nav-btn">⚙️ Admin</a>
        </div>
    </div>
</nav>

<div class="wrap">

    {{-- HERO --}}
    <div class="hero">
        <div class="eyebrow">⭐ Hasil Resmi · Metode MABAC</div>
        <h1 class="hero-title">Staff Terbaik <span class="grad">HIMATIK</span></h1>
        <p class="hero-sub">
            Pemilihan berbasis <strong style="color:var(--text)">Sistem Pendukung Keputusan</strong>
            metode MABAC dengan {{ $totalKriteria }} kriteria penilaian terbobot.
        </p>
        <div class="hero-meta">
            <span>📅 Periode: <strong>{{ $periode }}</strong></span>
            <span>🏛️ <strong>{{ $totalDivisi }}</strong> Divisi</span>
            <span>👥 <strong>{{ $totalStaff }}</strong> Staff</span>
            <span>📋 <strong>{{ $totalKriteria }}</strong> Kriteria</span>
        </div>
    </div>

    {{-- STATS --}}
    <div class="stats-strip">
        <div class="stat-box"><div class="stat-val" style="color:var(--accent)">{{ $totalDivisi }}</div><div class="stat-lbl">Divisi</div></div>
        <div class="stat-box"><div class="stat-val" style="color:var(--green)">{{ $totalStaff }}</div><div class="stat-lbl">Staff Dinilai</div></div>
        <div class="stat-box"><div class="stat-val" style="color:var(--purple)">{{ $totalKriteria }}</div><div class="stat-lbl">Kriteria</div></div>
        <div class="stat-box"><div class="stat-val" style="color:var(--gold)">{{ $periodeList->count() }}</div><div class="stat-lbl">Periode Data</div></div>
    </div>

    {{-- SPOTLIGHT --}}
    @if($spotlightStaff)
    <div class="section-title">🌟 Staff Terbaik Keseluruhan</div>
    <p class="section-sub">Nilai Si MABAC tertinggi dari seluruh divisi pada periode {{ $periode }}.</p>
    <div class="spotlight">
        <div class="sp-avatar">{{ strtoupper(substr($spotlightStaff->staff->nama, 0, 1)) }}</div>
        <div style="flex:1">
            <div class="sp-divisi">🏛️ {{ $spotlightStaff->divisi->nama }}</div>
            <div class="sp-nama">{{ $spotlightStaff->staff->nama }}</div>
            <div class="sp-meta">
                <span style="font-family:'JetBrains Mono',monospace">{{ $spotlightStaff->staff->nim }}</span>
                &nbsp;·&nbsp; {{ $spotlightStaff->staff->jabatan }}
            </div>
        </div>
        <div class="sp-si">
            <div class="sp-si-lbl">Nilai Si MABAC</div>
            <div class="sp-si-val">+{{ number_format($spotlightStaff->nilai_akhir, 6) }}</div>
            <div style="margin-top:8px">
                <span style="background:rgba(255,181,71,.12);border:1px solid rgba(255,181,71,.3);color:var(--gold);padding:4px 12px;border-radius:999px;font-size:.7rem;font-weight:700">
                    🥇 #1 dari {{ $totalStaff }} Staff
                </span>
            </div>
        </div>
    </div>
    @endif

    {{-- HASIL PER DIVISI --}}
    <div class="section-title">🏛️ Perankingan Per Divisi</div>
    <p class="section-sub">Staff terbaik tiap divisi berdasarkan metode MABAC periode {{ $periode }}.</p>

    <div class="divisi-grid">
        @foreach($divisiList as $divisi)
        @php $hasilDivisi = $hasilPerDivisi[$divisi->id] ?? collect(); @endphp
        <div class="divisi-card">
            <div class="dc-head">
                <div class="dc-icon">{{ $divisi->kode }}</div>
                <div style="flex:1;min-width:0">
                    <div class="dc-nama">{{ $divisi->nama }}</div>
                    <div class="dc-total">{{ $hasilDivisi->count() }} staff</div>
                </div>
                @if($hasilDivisi->isNotEmpty())
                    <div class="dc-badge">⭐ {{ $hasilDivisi->first()->staff->nama }}</div>
                @endif
            </div>
            <div class="staff-list">
                @if($hasilDivisi->isEmpty())
                    <div style="text-align:center;padding:20px;color:var(--dim);font-size:.8rem">
                        Belum ada hasil perhitungan
                    </div>
                @else
                @php
                    $sMax = $hasilDivisi->max('nilai_akhir');
                    $sMin = $hasilDivisi->min('nilai_akhir');
                    $sRange = ($sMax - $sMin) ?: 1;
                @endphp
                @foreach($hasilDivisi as $h)
                @php
                    $pct = max(5, min(100, (($h->nilai_akhir - $sMin) / $sRange) * 100));
                    $barColor = $h->terbaik
                        ? 'linear-gradient(90deg,var(--gold),#F59E0B)'
                        : ($h->nilai_akhir >= 0 ? 'linear-gradient(90deg,var(--green),#059669)' : 'linear-gradient(90deg,#FF5C5C,#DC2626)');
                    $siColor = $h->terbaik ? 'var(--gold)' : ($h->nilai_akhir >= 0 ? 'var(--green)' : 'var(--red)');
                @endphp
                <div class="staff-row">
                    <div class="rnk {{ $h->peringkat<=3 ? 'rnk-'.$h->peringkat : 'rnk-n' }}">
                        @if($h->peringkat==1)🥇@elseif($h->peringkat==2)🥈@elseif($h->peringkat==3)🥉@else #{{ $h->peringkat }}@endif
                    </div>
                    <div class="s-info">
                        <div class="s-nama" style="{{ $h->terbaik ? 'color:var(--gold);font-weight:700' : '' }}">{{ $h->staff->nama }}</div>
                        <div class="s-jabatan">{{ $h->staff->jabatan }}</div>
                    </div>
                    <div class="s-bar"><div class="s-fill" style="width:{{ $pct }}%;background:{{ $barColor }}"></div></div>
                    <div class="s-si" style="color:{{ $siColor }}">
                        {{ ($h->nilai_akhir >= 0 ? '+' : '').number_format($h->nilai_akhir, 4) }}
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>
        @endforeach
    </div>

    {{-- TENTANG METODE --}}
    <div class="metode-wrap">
        <div style="display:grid;grid-template-columns:1fr auto;gap:32px;align-items:start;flex-wrap:wrap">
            <div>
                <div class="section-title" style="margin-bottom:8px">📐 Tentang Metode MABAC</div>
                <p style="font-size:.82rem;color:var(--muted);line-height:1.7;max-width:520px">
                    MABAC membandingkan setiap alternatif (staff) terhadap batas area aproksimasi (BAA).
                    Alternatif yang berada di atas BAA mendapat nilai positif, di bawah BAA negatif.
                    Staff dengan nilai Si terbesar adalah yang terbaik.
                </p>
                <div class="steps-grid">
                    @foreach([
                        ['1','Matriks X','x_ij','Nilai penilaian asli (1–5)'],
                        ['2','Normalisasi N','(x-min)/(max-min)','Skala 0–1 per kriteria'],
                        ['3','Berbobot V','w·n + w','Dikalikan bobot kriteria'],
                        ['4','Batas G','(∏v_ij)^(1/m)','Rata-rata geometri'],
                        ['5','Jarak Q','v_ij − g_j','Jarak dari batas area'],
                        ['6','Nilai Si','Σ q_ij','Jumlah semua jarak'],
                        ['7','Ranking','Si terbesar = #1','Staff terbaik divisi'],
                    ] as $s)
                    <div class="step-box">
                        <div class="step-n">{{ $s[0] }}</div>
                        <div>
                            <div class="step-lbl">{{ $s[1] }}</div>
                            <div class="step-formula">{{ $s[2] }}</div>
                            <div class="step-desc">{{ $s[3] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div style="min-width:200px">
                <div style="font-size:.7rem;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:10px">{{ $totalKriteria }} Kriteria Penilaian</div>
                @foreach($kriteriaList as $k)
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px">
                    <span style="font-size:.65rem;font-weight:700;color:var(--accent-2);width:26px;flex-shrink:0">{{ $k->kode }}</span>
                    <span style="font-size:.75rem;color:var(--muted);flex:1">{{ $k->nama }}</span>
                    <span style="font-size:.6rem;color:{{ $k->tipe=='benefit' ? 'var(--green)' : 'var(--red)' }};font-weight:700">
                        {{ $k->tipe=='benefit' ? '↑' : '↓' }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

<footer class="footer">
    <img src="{{ asset('images/Logo Himatik.png') }}" alt="HIMATIK" style="width:48px;height:48px;object-fit:contain">
    <p> <strong>HIMATIK SPK</strong> — Sistem Pendukung Keputusan Pemilihan Staff Terbaik</p>
    <p style="margin-top:5px">
        Himpunan Mahasiswa Teknik Informatika dan Komputer &copy; {{ date('Y') }}
        &nbsp;·&nbsp;
        <a href="{{ route('login') }}">Admin Login</a>
    </p>
</footer>

</body>
</html>
