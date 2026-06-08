<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'HIMATIK SPK') — Admin</title>
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
            --r:10px;--r-lg:16px;--sidebar-w:256px;
        }
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        html{scroll-behavior:smooth}
        body{font-family:'Sora',sans-serif;background:var(--bg);color:var(--text);min-height:100vh;display:flex}

        /* SIDEBAR */
        .sidebar{width:var(--sidebar-w);background:var(--surface);border-right:1px solid var(--border);height:100vh;position:fixed;left:0;top:0;display:flex;flex-direction:column;z-index:50;overflow-y:auto}
        .sidebar-brand{padding:22px 20px 18px;border-bottom:1px solid var(--border)}
        .brand-wrap{display:flex;align-items:center;gap:11px}
        .brand-icon{width:38px;height:38px;background:linear-gradient(135deg,var(--accent),var(--purple));border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0}
        .brand-name{font-size:.92rem;font-weight:800;line-height:1.2}
        .brand-sub{font-size:.68rem;color:var(--muted);font-weight:400;margin-top:2px}

        .sidebar-nav{padding:14px 10px;flex:1}
        .nav-lbl{font-size:.62rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--dim);padding:10px 8px 5px}
        .nav-item{display:flex;align-items:center;gap:10px;padding:8px 10px;border-radius:8px;text-decoration:none;color:var(--muted);font-size:.84rem;font-weight:500;transition:all .15s;margin-bottom:1px}
        .nav-item:hover{background:var(--surface-2);color:var(--text)}
        .nav-item.active{background:rgba(91,127,255,.12);color:var(--accent-2);border:1px solid rgba(91,127,255,.2)}
        .nav-item .ic{width:18px;text-align:center;font-size:14px;flex-shrink:0}
        .nav-item .pub-badge{margin-left:auto;font-size:.58rem;background:var(--green);color:#000;padding:2px 6px;border-radius:999px;font-weight:700}

        .sidebar-footer{padding:14px 10px;border-top:1px solid var(--border)}
        .user-card{display:flex;align-items:center;gap:10px;padding:10px;background:var(--surface-2);border-radius:var(--r)}
        .user-av{width:32px;height:32px;background:linear-gradient(135deg,var(--accent),var(--purple));border-radius:8px;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.82rem;flex-shrink:0}
        .user-name{font-size:.8rem;font-weight:600}
        .user-role{font-size:.68rem;color:var(--muted)}

        /* MAIN */
        .main-wrap{margin-left:var(--sidebar-w);flex:1;display:flex;flex-direction:column;min-height:100vh}
        .topbar{background:rgba(14,18,25,.9);backdrop-filter:blur(12px);border-bottom:1px solid var(--border);padding:0 26px;height:58px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:40}
        .page-title{font-size:.95rem;font-weight:700}
        .page-bc{font-size:.75rem;color:var(--muted);margin-top:2px}
        .topbar-actions{display:flex;align-items:center;gap:8px}
        .content{padding:26px;flex:1}

        /* CARDS */
        .card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r-lg);overflow:hidden}
        .card-header{padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:12px}
        .card-title{font-size:.92rem;font-weight:700}
        .card-body{padding:20px}

        /* STATS */
        .stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:14px;margin-bottom:24px}
        .stat-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r-lg);padding:18px;display:flex;align-items:flex-start;gap:14px}
        .stat-icon{width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0}
        .stat-value{font-size:1.7rem;font-weight:800;line-height:1;margin-bottom:3px;font-family:'JetBrains Mono',monospace}
        .stat-label{font-size:.74rem;color:var(--muted);font-weight:500}

        /* TABLE */
        .table-wrap{overflow-x:auto}
        table{width:100%;border-collapse:collapse}
        thead th{padding:10px 14px;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);border-bottom:1px solid var(--border);text-align:left;background:var(--surface-2)}
        tbody td{padding:12px 14px;font-size:.855rem;border-bottom:1px solid var(--border);vertical-align:middle}
        tbody tr:last-child td{border-bottom:none}
        tbody tr:hover{background:rgba(255,255,255,.02)}
        .mono{font-family:'JetBrains Mono',monospace;font-size:.8rem}

        /* BUTTONS */
        .btn{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:var(--r);font-family:'Sora',sans-serif;font-size:.835rem;font-weight:600;cursor:pointer;border:none;text-decoration:none;transition:all .15s;white-space:nowrap}
        .btn-primary{background:var(--accent);color:#fff}
        .btn-primary:hover{background:var(--accent-2);box-shadow:0 4px 14px var(--accent-glow)}
        .btn-outline{background:transparent;border:1px solid var(--border-2);color:var(--muted)}
        .btn-outline:hover{border-color:var(--accent);color:var(--accent)}
        .btn-success{background:rgba(54,211,153,.1);border:1px solid rgba(54,211,153,.25);color:var(--green)}
        .btn-success:hover{background:var(--green);color:#000}
        .btn-danger{background:rgba(255,92,92,.1);border:1px solid rgba(255,92,92,.25);color:var(--red)}
        .btn-danger:hover{background:var(--red);color:#fff}
        .btn-gold{background:var(--gold-glow);border:1px solid rgba(255,181,71,.3);color:var(--gold)}
        .btn-gold:hover{background:var(--gold);color:#000}
        .btn-sm{padding:5px 11px;font-size:.77rem}
        .btn-xs{padding:3px 9px;font-size:.7rem}

        /* FORMS */
        .form-group{margin-bottom:18px}
        .form-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:18px}
        label{display:block;font-size:.74rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:7px}
        input[type=text],input[type=email],input[type=number],input[type=password],select,textarea{width:100%;padding:9px 13px;background:var(--surface-2);border:1px solid var(--border);border-radius:var(--r);color:var(--text);font-family:'Sora',sans-serif;font-size:.855rem;outline:none;transition:all .15s}
        input:focus,select:focus,textarea:focus{border-color:var(--accent);box-shadow:0 0 0 3px var(--accent-glow)}
        select option{background:var(--surface)}
        textarea{resize:vertical;min-height:88px}
        .input-hint{font-size:.72rem;color:var(--muted);margin-top:4px}
        .error-msg{color:var(--red);font-size:.75rem;margin-top:4px}

        /* BADGE */
        .badge{display:inline-flex;align-items:center;padding:3px 9px;border-radius:999px;font-size:.7rem;font-weight:700}
        .badge-blue{background:rgba(91,127,255,.12);color:var(--accent-2);border:1px solid rgba(91,127,255,.2)}
        .badge-green{background:rgba(54,211,153,.1);color:var(--green);border:1px solid rgba(54,211,153,.2)}
        .badge-red{background:rgba(255,92,92,.1);color:var(--red);border:1px solid rgba(255,92,92,.2)}
        .badge-gold{background:var(--gold-glow);color:var(--gold);border:1px solid rgba(255,181,71,.25)}
        .badge-gray{background:var(--surface-2);color:var(--muted);border:1px solid var(--border)}
        .badge-purple{background:rgba(167,139,250,.1);color:var(--purple);border:1px solid rgba(167,139,250,.2)}

        /* ALERTS */
        .alert{padding:12px 16px;border-radius:var(--r);margin-bottom:18px;font-size:.855rem;font-weight:500;display:flex;align-items:flex-start;gap:10px}
        .alert-success{background:rgba(54,211,153,.08);border:1px solid rgba(54,211,153,.25);color:var(--green)}
        .alert-error{background:rgba(255,92,92,.08);border:1px solid rgba(255,92,92,.25);color:var(--red)}
        .alert-info{background:rgba(91,127,255,.08);border:1px solid rgba(91,127,255,.25);color:var(--accent-2)}
        .alert-warning{background:rgba(255,181,71,.08);border:1px solid rgba(255,181,71,.25);color:var(--gold)}

        /* RANK */
        .rank-1{background:rgba(255,181,71,.15);color:var(--gold);border:1px solid rgba(255,181,71,.3);font-weight:800;padding:4px 11px;border-radius:999px;font-size:.78rem}
        .rank-2{background:rgba(192,192,192,.1);color:#C0C0C0;border:1px solid rgba(192,192,192,.2);font-weight:700;padding:4px 11px;border-radius:999px;font-size:.78rem}
        .rank-3{background:rgba(205,127,50,.1);color:#CD7F32;border:1px solid rgba(205,127,50,.2);font-weight:700;padding:4px 11px;border-radius:999px;font-size:.78rem}
        .rank-n{background:var(--surface-2);color:var(--muted);padding:4px 11px;border-radius:999px;font-size:.78rem}

        /* PROGRESS */
        .progress{background:var(--border);border-radius:999px;overflow:hidden}
        .progress-bar{height:6px;border-radius:999px;background:linear-gradient(90deg,var(--accent),var(--purple))}

        /* EMPTY STATE */
        .empty-state{text-align:center;padding:52px 20px;color:var(--muted)}
        .empty-state .icon{font-size:44px;margin-bottom:14px}
        .empty-state h3{font-size:.95rem;font-weight:600;margin-bottom:7px;color:var(--text)}
        .empty-state p{font-size:.84rem}

        hr{border:none;border-top:1px solid var(--border);margin:18px 0}

        @keyframes fadeUp{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
        .fade-up{animation:fadeUp .35s ease both}

        @media(max-width:900px){.sidebar{transform:translateX(-100%)}.main-wrap{margin-left:0}.form-grid{grid-template-columns:1fr}}
    </style>
    @stack('styles')
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-wrap">
            <div class="brand-icon">⚡</div>
            <div>
                <div class="brand-name">HIMATIK SPK</div>
                <div class="brand-sub">Metode MABAC · Admin</div>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-lbl">Utama</div>
        <a href="{{ route('public.index') }}" class="nav-item" target="_blank">
            <span class="ic">🌐</span> Halaman Publik
            <span class="pub-badge">NEW</span>
        </a>
        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="ic">🏠</span> Dashboard
        </a>

        <div class="nav-lbl">Kelola Data</div>
        <a href="{{ route('admin.divisi.index') }}" class="nav-item {{ request()->routeIs('admin.divisi.*') ? 'active' : '' }}">
            <span class="ic">🏛️</span> Data Divisi
        </a>
        <a href="{{ route('admin.staff.index') }}" class="nav-item {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">
            <span class="ic">👥</span> Data Staff
        </a>
        <a href="{{ route('admin.kriteria.index') }}" class="nav-item {{ request()->routeIs('admin.kriteria.*') ? 'active' : '' }}">
            <span class="ic">📋</span> Data Kriteria
        </a>
        <a href="{{ route('admin.bobot.index') }}" class="nav-item {{ request()->routeIs('admin.bobot.*') ? 'active' : '' }}">
            <span class="ic">⚖️</span> Bobot Kriteria
        </a>
        <a href="{{ route('admin.skala.index') }}" class="nav-item {{ request()->routeIs('admin.skala.*') ? 'active' : '' }}">
            <span class="ic">📊</span> Skala Penilaian
        </a>

        <div class="nav-lbl">Proses SPK</div>
        <a href="{{ route('admin.penilaian.index') }}" class="nav-item {{ request()->routeIs('admin.penilaian.*') ? 'active' : '' }}">
            <span class="ic">✏️</span> Input Penilaian
        </a>
        <a href="{{ route('admin.hasil.index') }}" class="nav-item {{ request()->routeIs('admin.hasil.*') ? 'active' : '' }}">
            <span class="ic">🏆</span> Hasil Perankingan
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-av">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div style="flex:1;min-width:0">
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">{{ ucfirst(auth()->user()->role) }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="background:none;border:none;cursor:pointer;color:var(--muted);font-size:16px" title="Keluar">↩</button>
            </form>
        </div>
    </div>
</aside>

<div class="main-wrap">
    <header class="topbar">
        <div>
            <div class="page-title">@yield('title', 'Dashboard')</div>
            <div class="page-bc">Admin / @yield('breadcrumb', 'Dashboard')</div>
        </div>
        <div class="topbar-actions">@yield('topbar-actions')</div>
    </header>

    <main class="content">
        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">⚠️ {{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">
                <span>⚠️</span>
                <ul style="list-style:none;padding:0">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </main>
</div>

@stack('scripts')
</body>
</html>
