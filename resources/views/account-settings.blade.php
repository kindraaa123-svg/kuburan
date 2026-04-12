<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Akun</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Space+Grotesk:wght@600;700&display=swap');

        :root {
            --bg: #edf3f1;
            --card: #ffffff;
            --line: #d2dfda;
            --text: #163036;
            --muted: #657c81;
            --brand-soft: #e8f3ef;
            --danger: #c44a4a;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: "DM Sans", sans-serif;
            color: var(--text);
            background:
                radial-gradient(800px 420px at 0% -10%, #d8e8e2 0%, transparent 60%),
                radial-gradient(800px 420px at 100% 110%, #dce9e4 0%, transparent 60%),
                var(--bg);
        }

        .layout {
            display: grid;
            grid-template-columns: 290px 1fr;
            min-height: 100vh;
            gap: 12px;
            width: min(1320px, 96vw);
            margin: 12px auto;
        }

        .sidebar, .main-card {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 16px;
            box-shadow: 0 12px 24px rgba(14, 40, 44, .08);
        }

        .sidebar {
            padding: 14px;
            display: grid;
            grid-template-rows: auto auto 1fr auto;
            gap: 12px;
            max-height: calc(100vh - 24px);
            position: sticky;
            top: 12px;
        }

        .logo {
            margin: 0;
            font-family: "Space Grotesk", sans-serif;
            font-size: 1.05rem;
        }

        .user-box {
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 10px;
            background: #f8fcfa;
            font-size: .82rem;
        }

        .user-box strong { display: block; font-size: .92rem; margin-top: 3px; }

        .sidebar-menu {
            display: grid;
            align-content: start;
            gap: 8px;
        }

        .sidebar-bottom {
            display: grid;
            gap: 8px;
        }

        .sidebar-menu-item {
            display: block;
            border: 1px solid #d8e4df;
            border-radius: 10px;
            padding: 10px;
            background: #fbfdfc;
            color: var(--text);
            text-decoration: none;
            font-weight: 700;
            font-size: .88rem;
        }

        .sidebar-menu-item.active {
            border-color: #9cc3b8;
            background: #eaf4f0;
            color: #175347;
        }

        .logout-btn {
            width: 100%;
            min-height: 42px;
            border: 1px solid #bbcfca;
            border-radius: 10px;
            background: #eef6f3;
            color: #184e43;
            font-weight: 700;
            cursor: pointer;
        }

        .main-card {
            padding: 16px;
        }

        .head h1 {
            margin: 0;
            font-family: "Space Grotesk", sans-serif;
            font-size: 1.35rem;
        }

        .head p {
            margin: 6px 0 0;
            color: var(--muted);
            font-size: .84rem;
        }

        .status {
            margin-top: 12px;
            border: 1px solid #b8d4c9;
            background: var(--brand-soft);
            color: #175247;
            border-radius: 10px;
            padding: 10px;
            font-size: .83rem;
            font-weight: 700;
        }

        .error {
            margin-top: 12px;
            border: 1px solid #e3b5b5;
            background: #fdeeee;
            color: var(--danger);
            border-radius: 10px;
            padding: 10px;
            font-size: .83rem;
            font-weight: 700;
        }

        .form-card {
            margin-top: 12px;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: #fbfdfc;
            padding: 14px;
        }

        .card-title {
            margin: 0 0 10px;
            font-size: 1rem;
            font-family: "Space Grotesk", sans-serif;
        }

        .card-desc {
            margin: -2px 0 10px;
            font-size: .8rem;
            color: var(--muted);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .field {
            display: grid;
            gap: 6px;
        }

        .field.full {
            grid-column: 1 / -1;
        }

        label {
            font-size: .78rem;
            color: var(--muted);
            font-weight: 700;
        }

        input {
            width: 100%;
            min-height: 44px;
            border: 1px solid #c7d7d1;
            border-radius: 10px;
            padding: 0 12px;
            background: #fff;
            color: var(--text);
            font-size: .9rem;
        }

        input:disabled {
            background: #f2f7f5;
            color: #4a676f;
        }

        .actions {
            margin-top: 12px;
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }

        .btn {
            min-height: 42px;
            border: 1px solid #1f735f;
            border-radius: 10px;
            background: linear-gradient(120deg, #29947b, #1f7a67);
            color: #fff;
            font-weight: 700;
            padding: 0 16px;
            cursor: pointer;
        }

        @media (max-width: 1080px) {
            .layout { grid-template-columns: 1fr; }
            .sidebar { position: static; max-height: none; }
        }

        @media (max-width: 700px) {
            .grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <main class="layout">
        <aside class="sidebar">
            <h2 class="logo">Dashboard Kuburan</h2>
            <div class="user-box">
                Login sebagai
                <strong>{{ $authUser['username'] ?? 'user' }}</strong>
            </div>
            <nav class="sidebar-menu">
                <a href="{{ route('dashboard') }}" class="sidebar-menu-item">Dashboard</a>
                <a href="{{ route('dashboard.data-blok') }}" class="sidebar-menu-item">Data Blok</a>
                <a href="{{ route('dashboard.data-plot') }}" class="sidebar-menu-item">Data Plot</a>
                <a href="{{ route('dashboard.data-almarhum') }}" class="sidebar-menu-item">Data Almarhum</a>
                <a href="{{ route('dashboard.data-user') }}" class="sidebar-menu-item">Data User</a>
                <a href="{{ route('dashboard.settings') }}" class="sidebar-menu-item">Pengaturan</a>
            </nav>
            <div class="sidebar-bottom">
                <a href="{{ route('dashboard.account') }}" class="sidebar-menu-item active">Akun</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </aside>

        <section class="main-card">
            <div class="head">
                <h1>Pengaturan Akun</h1>
                <p>Kelola data akun login Anda.</p>
            </div>

            @if (session('account_status'))
                <div class="status">{{ session('account_status') }}</div>
            @endif

            @if (session('password_status'))
                <div class="status">{{ session('password_status') }}</div>
            @endif

            <form class="form-card" method="POST" action="{{ route('dashboard.account.update') }}">
                @csrf
                <h3 class="card-title">Profil Akun</h3>
                <p class="card-desc">Data ini digunakan sebagai identitas akun Anda.</p>

                @if ($errors->accountUpdate->any())
                    <div class="error">{{ $errors->accountUpdate->first() }}</div>
                @endif

                <div class="grid">
                    <div class="field">
                        <label for="username">Username</label>
                        <input id="username" type="text" value="{{ $userAccount->username }}" disabled>
                    </div>

                    <div class="field">
                        <label for="full_name">Nama</label>
                        <input id="full_name" name="full_name" type="text" value="{{ old('full_name', $userAccount->full_name ?: $userAccount->username) }}" required placeholder="Masukkan nama lengkap">
                    </div>

                    <div class="field">
                        <label for="phone_number">Nomor Telepon</label>
                        <input id="phone_number" name="phone_number" type="text" value="{{ old('phone_number', $userAccount->phone_number) }}" placeholder="Contoh: 081234567890">
                    </div>

                    <div class="field full">
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $userAccount->email) }}" placeholder="nama@email.com">
                    </div>
                </div>

                <div class="actions">
                    <button class="btn" type="submit">Simpan Profil</button>
                </div>
            </form>

            <form class="form-card" method="POST" action="{{ route('dashboard.account.password') }}">
                @csrf
                <h3 class="card-title">Reset Password</h3>
                <p class="card-desc">Gunakan password saat ini untuk membuat password baru.</p>

                @if ($errors->passwordUpdate->any())
                    <div class="error">{{ $errors->passwordUpdate->first() }}</div>
                @endif

                <div class="grid">
                    <div class="field full">
                        <label for="current_password">Password Saat Ini</label>
                        <input id="current_password" name="current_password" type="password" required>
                    </div>

                    <div class="field">
                        <label for="new_password">Password Baru</label>
                        <input id="new_password" name="new_password" type="password" required placeholder="Minimal 6 karakter">
                    </div>

                    <div class="field">
                        <label for="new_password_confirmation">Konfirmasi Password Baru</label>
                        <input id="new_password_confirmation" name="new_password_confirmation" type="password" required placeholder="Ulangi password baru">
                    </div>
                </div>

                <div class="actions">
                    <button class="btn" type="submit">Simpan Password Baru</button>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
