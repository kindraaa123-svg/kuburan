<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Akun</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&family=Space+Grotesk:wght@600;700&display=swap');

        :root {
            --bg-top: #2c0915;
            --bg-bottom: #080306;
            --shell-line: rgba(255, 225, 230, 0.14);
            --card-bg: rgba(247, 239, 236, 0.98);
            --card-line: rgba(115, 38, 54, 0.2);
            --text: #2b1017;
            --muted: #77525b;
            --sidebar-bg: rgba(28, 7, 14, 0.88);
            --sidebar-line: rgba(255, 231, 235, 0.14);
            --sidebar-text: #fff2ee;
            --sidebar-muted: #dfbec3;
            --brand: #7a1129;
            --brand-soft: rgba(122, 17, 41, 0.09);
            --danger: #92223b;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Manrope", sans-serif;
            color: var(--text);
            background:
                radial-gradient(920px 520px at -8% -10%, rgba(170, 44, 73, 0.4) 0%, transparent 58%),
                radial-gradient(760px 440px at 106% 110%, rgba(124, 18, 43, 0.4) 0%, transparent 64%),
                linear-gradient(180deg, var(--bg-top) 0%, #14070d 45%, var(--bg-bottom) 100%);
        }

        .layout {
            display: grid;
            grid-template-columns: 300px minmax(0, 1fr);
            min-height: 100vh;
            gap: 14px;
            width: min(1360px, calc(100vw - 28px));
            margin: 14px auto;
        }

        .sidebar,
        .main-card {
            border-radius: 22px;
            border: 1px solid var(--shell-line);
            overflow: hidden;
        }

        .sidebar {
            padding: 16px;
            display: grid;
            grid-template-rows: auto auto 1fr auto;
            gap: 14px;
            max-height: calc(100vh - 28px);
            position: sticky;
            top: 14px;
            background:
                linear-gradient(180deg, rgba(255, 241, 243, 0.08), rgba(255, 241, 243, 0) 22%),
                var(--sidebar-bg);
            box-shadow: 0 26px 54px rgba(22, 4, 9, 0.4);
            backdrop-filter: blur(8px);
        }

        .logo {
            margin: 0;
            font-family: "Space Grotesk", sans-serif;
            font-size: 1.02rem;
            letter-spacing: 0.03em;
            color: var(--sidebar-text);
        }

        .user-box {
            border: 1px solid var(--sidebar-line);
            border-radius: 14px;
            padding: 11px 12px;
            background: rgba(255, 241, 243, 0.08);
            color: var(--sidebar-muted);
            font-size: 0.8rem;
        }

        .user-box strong {
            display: block;
            margin-top: 3px;
            font-size: 0.92rem;
            color: var(--sidebar-text);
        }

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
            border: 1px solid var(--sidebar-line);
            border-radius: 12px;
            padding: 10px 12px;
            background: rgba(255, 241, 243, 0.05);
            color: var(--sidebar-text);
            text-decoration: none;
            font-weight: 700;
            font-size: 0.86rem;
            transition: transform 0.16s ease, background-color 0.16s ease, border-color 0.16s ease;
        }

        .sidebar-menu-item:hover {
            transform: translateX(2px);
            background: rgba(255, 241, 243, 0.12);
            border-color: rgba(255, 241, 243, 0.28);
        }

        .sidebar-menu-item.active {
            border-color: rgba(255, 188, 199, 0.7);
            background: rgba(122, 17, 41, 0.38);
            color: #fff7f4;
        }

        .logout-btn {
            width: 100%;
            min-height: 42px;
            border: 1px solid rgba(255, 241, 243, 0.24);
            border-radius: 12px;
            background: rgba(255, 241, 243, 0.08);
            color: var(--sidebar-text);
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.16s ease, border-color 0.16s ease;
        }

        .logout-btn:hover {
            background: rgba(255, 241, 243, 0.14);
            border-color: rgba(255, 241, 243, 0.38);
        }

        .main-card {
            padding: 20px;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.42), rgba(255, 255, 255, 0) 24%),
                var(--card-bg);
            border-color: var(--card-line);
            box-shadow: 0 28px 58px rgba(22, 4, 9, 0.34);
        }

        .head h1 {
            margin: 0;
            font-family: "Space Grotesk", sans-serif;
            font-size: clamp(1.35rem, 2.2vw, 1.75rem);
            letter-spacing: -0.01em;
        }

        .head p {
            margin: 8px 0 0;
            color: var(--muted);
            font-size: 0.87rem;
            line-height: 1.55;
            max-width: 680px;
        }

        .status {
            margin-top: 12px;
            border: 1px solid rgba(122, 17, 41, 0.22);
            background: var(--brand-soft);
            color: var(--brand);
            border-radius: 12px;
            padding: 10px 12px;
            font-size: 0.83rem;
            font-weight: 700;
        }

        .error {
            margin-top: 12px;
            border: 1px solid rgba(146, 34, 59, 0.25);
            background: rgba(146, 34, 59, 0.1);
            color: var(--danger);
            border-radius: 12px;
            padding: 10px 12px;
            font-size: 0.83rem;
            font-weight: 700;
        }

        .form-card {
            margin-top: 14px;
            border: 1px solid rgba(115, 38, 54, 0.18);
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.66);
            padding: 16px;
        }

        .card-title {
            margin: 0 0 10px;
            font-size: 1rem;
            font-family: "Space Grotesk", sans-serif;
            color: #4f1f2a;
        }

        .card-desc {
            margin: -2px 0 10px;
            font-size: 0.8rem;
            color: var(--muted);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .field {
            display: grid;
            gap: 6px;
        }

        .field.full {
            grid-column: 1 / -1;
        }

        label {
            font-size: 0.78rem;
            color: var(--muted);
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        input {
            width: 100%;
            min-height: 46px;
            border: 1px solid rgba(110, 52, 63, 0.18);
            border-radius: 12px;
            padding: 0 12px;
            background: rgba(255, 253, 252, 0.95);
            color: var(--text);
            font-size: 0.92rem;
            transition: border-color 0.16s ease, box-shadow 0.16s ease;
        }

        input::placeholder {
            color: #aa8a91;
        }

        input:focus {
            outline: none;
            border-color: rgba(122, 17, 41, 0.46);
            box-shadow: 0 0 0 4px rgba(122, 17, 41, 0.1);
        }

        input:disabled {
            background: #f6eef0;
            color: #8a6770;
            border-color: #e4cfd5;
        }

        .actions {
            margin-top: 14px;
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }

        .btn {
            min-height: 42px;
            border: 1px solid #6d0f24;
            border-radius: 12px;
            background: linear-gradient(130deg, #a52142 0%, var(--brand) 52%, #4a0615 100%);
            color: #fff;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            padding: 0 16px;
            cursor: pointer;
            box-shadow: 0 14px 24px rgba(84, 9, 24, 0.24);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 28px rgba(84, 9, 24, 0.3);
        }

        @media (max-width: 1120px) {
            .layout {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: static;
                max-height: none;
            }
        }

        @media (max-width: 700px) {
            .layout {
                width: calc(100vw - 16px);
                margin: 8px auto;
                gap: 10px;
            }

            .main-card,
            .sidebar {
                border-radius: 16px;
            }

            .main-card {
                padding: 14px;
            }

            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <main class="layout">
        <aside class="sidebar">
            <h2 class="logo">Dashboard Kuburan</h2>
            <div class="user-box">
                Login sebagai
                <strong>{{ (\Illuminate\Support\Facades\Schema::hasTable('employer') ? (\Illuminate\Support\Facades\DB::table('employer')->where('userid', (int) ($authUser['id'] ?? 0))->value('name') ?: ($authUser['username'] ?? 'user')) : ($authUser['username'] ?? 'user')) }}</strong>
            </div>
            @php
                $levelId = (int) ($authUser['levelid'] ?? 0);
                $allowedMenuKeys = null;
                if (\Illuminate\Support\Facades\Schema::hasTable('level_sidebar_access')) {
                    if ($levelId > 0) {
                        $allowedMenuKeys = \Illuminate\Support\Facades\DB::table('level_sidebar_access')
                            ->where('levelid', $levelId)
                            ->pluck('menu_key')
                            ->all();
                    } else {
                        $allowedMenuKeys = [];
                    }
                }
                $canAccessSidebarMenu = static function (string $menuKey) use ($allowedMenuKeys): bool {
                    if (in_array($menuKey, ['dashboard', 'account', 'logout'], true)) {
                        return true;
                    }
                    if ($allowedMenuKeys === null) {
                        return true;
                    }
                    return in_array($menuKey, $allowedMenuKeys, true);
                };
            @endphp
            <nav class="sidebar-menu">
                <a href="{{ route('dashboard') }}" class="sidebar-menu-item">Dashboard</a>
                @if ($canAccessSidebarMenu('data-blok'))
                <a href="{{ route('dashboard.data-blok') }}" class="sidebar-menu-item">Data Blok</a>
                @endif
                @if ($canAccessSidebarMenu('data-plot'))
                <a href="{{ route('dashboard.data-plot') }}" class="sidebar-menu-item">Data Plot</a>
                @endif
                @if ($canAccessSidebarMenu('data-almarhum'))
                <a href="{{ route('dashboard.data-almarhum') }}" class="sidebar-menu-item">Data Almarhum</a>
                @endif
                @if ($canAccessSidebarMenu('data-kontak-keluarga'))
                <a href="{{ route('dashboard.data-kontak-keluarga') }}" class="sidebar-menu-item">Data Kontak Keluarga</a>
                @endif
                @if ($canAccessSidebarMenu('data-user'))
                <a href="{{ route('dashboard.data-user') }}" class="sidebar-menu-item">Data User</a>
                @endif
                @if ($canAccessSidebarMenu('activity-log'))
                <a href="{{ route('dashboard.activity-log') }}" class="sidebar-menu-item">Activity Log</a>
                @endif
                @if ($canAccessSidebarMenu('restore-data'))
                <a href="{{ route('dashboard.restore-data') }}" class="sidebar-menu-item">Restore Data</a>
                @endif
                @if ($canAccessSidebarMenu('hak-akses'))
                <a href="{{ route('dashboard.hak-akses') }}" class="sidebar-menu-item">Hak Akses</a>
                @endif
                @if ($canAccessSidebarMenu('settings'))
                <a href="{{ route('dashboard.settings') }}" class="sidebar-menu-item">Pengaturan</a>
                @endif
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
                        <input id="full_name" name="full_name" type="text" value="{{ old('full_name', $accountProfile['full_name'] ?? ($userAccount->full_name ?: $userAccount->username)) }}" required placeholder="Masukkan nama lengkap">
                    </div>

                    <div class="field">
                        <label for="phone_number">Nomor Telepon</label>
                        <input id="phone_number" name="phone_number" type="text" value="{{ old('phone_number', $accountProfile['phone_number'] ?? $userAccount->phone_number) }}" placeholder="Contoh: 081234567890">
                    </div>

                    <div class="field full">
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $accountProfile['email'] ?? $userAccount->email) }}" placeholder="nama@email.com">
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








