<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — HIMATIK SPK</title>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #07090F;
            --surface: #0E1219;
            --border: #1C2333;
            --text: #E8EAF0;
            --muted: #8B95A8;
            --accent: #5B7FFF;
            --accent-2: #7C9FFF;
            --accent-glow: rgba(91,127,255,.2);
            --red: #FF5C5C;
            --green: #36D399;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Sora', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: radial-gradient(ellipse 80% 60% at 50% -10%, rgba(91,127,255,.12), transparent),
                        radial-gradient(ellipse 60% 40% at 90% 80%, rgba(167,139,250,.08), transparent);
            pointer-events: none;
        }
        .login-wrap {
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 1;
        }
        .login-header { text-align: center; margin-bottom: 40px; }
        .logo {
            width: 60px; height: 60px;
            background: linear-gradient(135deg, #5B7FFF, #A78BFA);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin: 0 auto 18px;
            box-shadow: 0 0 40px rgba(91,127,255,.3);
        }
        .login-title { font-size: 1.6rem; font-weight: 800; letter-spacing: -.02em; }
        .login-sub { font-size: .875rem; color: var(--muted); margin-top: 6px; }
        .login-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 36px;
        }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-size: .75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: var(--muted); margin-bottom: 8px; }
        input {
            width: 100%;
            padding: 12px 16px;
            background: rgba(255,255,255,.03);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text);
            font-family: 'Sora', sans-serif;
            font-size: .9rem;
            outline: none;
            transition: all .15s;
        }
        input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-glow); }
        .error { color: var(--red); font-size: .78rem; margin-top: 6px; }
        .btn-login {
            width: 100%;
            padding: 13px;
            background: var(--accent);
            color: white;
            border: none;
            border-radius: 10px;
            font-family: 'Sora', sans-serif;
            font-size: .9rem;
            font-weight: 700;
            cursor: pointer;
            margin-top: 8px;
            transition: all .15s;
        }
        .btn-login:hover { background: var(--accent-2); box-shadow: 0 8px 24px var(--accent-glow); }
        .remember-row { display: flex; align-items: center; gap: 8px; margin-bottom: 20px; }
        .remember-row input[type=checkbox] { width: auto; }
        .remember-row label { margin: 0; font-size: .82rem; font-weight: 500; text-transform: none; letter-spacing: 0; }
        .info-box {
            background: rgba(91,127,255,.06);
            border: 1px solid rgba(91,127,255,.2);
            border-radius: 10px;
            padding: 14px 16px;
            margin-top: 24px;
        }
        .info-box-title { font-size: .75rem; font-weight: 700; color: var(--accent-2); margin-bottom: 8px; text-transform: uppercase; letter-spacing: .06em; }
        .info-box-row { display: flex; justify-content: space-between; font-size: .78rem; color: var(--muted); margin-bottom: 4px; }
        .info-box-row code { font-family: 'JetBrains Mono', monospace; color: var(--text); font-size: .75rem; }
        .footer-note { text-align: center; font-size: .75rem; color: var(--muted); margin-top: 24px; }
    </style>
</head>
<body>
<div class="login-wrap">
    <div class="login-header">
        <div class="logo-icon">
            <img src="{{ asset('images/Logo Himatik.png') }}" alt="HIMATIK" style="width:120px;height:120px;object-fit:contain">
        </div>
        <h1 class="login-title">HIMATIK SPK</h1>
        <p class="login-sub">Sistem Pendukung Keputusan — Metode MABAC</p>
    </div>

    <div class="login-card">
        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="form-group">
                <label for="username">Username / Email</label>
                <input type="text" id="username" name="username" value="{{ old('username') }}" placeholder="admin" autofocus required>
                @error('username') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>
            <div class="remember-row">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember" style="cursor:pointer">Ingat saya</label>
            </div>
            <button type="submit" class="btn-login">Masuk ke Sistem →</button>
        </form>

        <div class="info-box">
            <div class="info-box-title">🔑 Akun Default</div>
            <div class="info-box-row"><span>Admin</span> <code>admin / password</code></div>
            <div class="info-box-row"><span>Pimpinan</span> <code>ketua / password</code></div>
        </div>
    </div>

    <div class="footer-note">
        Himpunan Mahasiswa Teknik Informatika dan Komputer &copy; {{ date('Y') }}
    </div>
</div>
</body>
</html>
