<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backup Database</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&family=Space+Grotesk:wght@600;700&display=swap');

        :root {
            --bg-top: #2c0915;
            --bg-bottom: #080306;
            --card-bg: rgba(247, 239, 236, 0.98);
            --card-line: rgba(115, 38, 54, 0.2);
            --text: #2b1017;
            --muted: #77525b;
            --sidebar-bg: rgba(28, 7, 14, 0.88);
            --sidebar-line: rgba(255, 231, 235, 0.14);
            --sidebar-text: #fff2ee;
            --sidebar-muted: #dfbec3;
            --brand: #7a1129;
            --brand-strong: #540918;
            --brand-soft: rgba(122, 17, 41, 0.09);
            --success: #1e5a43;
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
            border: 1px solid rgba(255, 225, 230, 0.14);
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
            padding: 22px;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.42), rgba(255, 255, 255, 0) 24%),
                var(--card-bg);
            border-color: var(--card-line);
            box-shadow: 0 28px 58px rgba(22, 4, 9, 0.34);
        }

        .head {
            display: grid;
            gap: 12px;
        }

        .head h1 {
            margin: 0;
            font-family: "Space Grotesk", sans-serif;
            font-size: clamp(1.6rem, 2.4vw, 2.2rem);
            letter-spacing: -0.02em;
        }

        .head p {
            margin: 0;
            color: var(--muted);
            font-size: 0.95rem;
            line-height: 1.7;
            max-width: 760px;
        }

        .hero {
            margin-top: 22px;
            border-radius: 20px;
            padding: 28px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(251, 242, 240, 0.9));
            border: 1px solid rgba(115, 38, 54, 0.14);
            display: grid;
            gap: 14px;
        }

        .hero strong {
            display: inline-block;
            font-size: 0.88rem;
            color: var(--brand);
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .hero h2 {
            margin: 0;
            font-size: clamp(1.25rem, 2vw, 1.75rem);
            color: #4d1e2b;
        }

        .hero p {
            margin: 0;
            color: var(--muted);
            line-height: 1.75;
        }

        .panel-grid {
            margin-top: 24px;
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .panel {
            border-radius: 18px;
            border: 1px solid rgba(115, 38, 54, 0.14);
            background: rgba(255, 255, 255, 0.82);
            padding: 22px;
            box-shadow: 0 16px 32px rgba(116, 27, 42, 0.08);
            display: grid;
            gap: 14px;
        }

        .panel h3 {
            margin: 0;
            font-size: 1.08rem;
            color: #4e1f2f;
        }

        .panel p {
            margin: 0;
            color: var(--muted);
            line-height: 1.7;
        }

        .panel-icon {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            display: grid;
            place-items: center;
            background: rgba(186, 137, 144, 0.18);
            color: #a52042;
            font-size: 1.6rem;
        }

        .panel-actions {
            display: grid;
            gap: 10px;
        }

        .btn {
            border: 0;
            border-radius: 14px;
            padding: 14px 18px;
            font-size: 0.95rem;
            font-weight: 800;
            cursor: pointer;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #9f233f 0%, #cf4b6a 100%);
            color: #fff;
            box-shadow: 0 16px 28px rgba(159, 35, 63, 0.24);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.96);
            color: #5e2b34;
            border: 1px solid rgba(115, 38, 54, 0.18);
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .form-file {
            display: grid;
            gap: 10px;
        }

        .form-file label {
            font-size: 0.82rem;
            font-weight: 800;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: #5d3640;
        }

        .form-file input[type="file"] {
            width: 100%;
            min-height: 46px;
            padding: 12px 14px;
            border: 1px solid rgba(110, 52, 63, 0.18);
            border-radius: 12px;
            background: rgba(255, 253, 252, 0.95);
            color: var(--text);
            font-size: 0.95rem;
            cursor: pointer;
        }

        .note {
            margin-top: 18px;
            color: var(--muted);
            font-size: 0.92rem;
            line-height: 1.8;
        }

        @media (max-width: 980px) {
            .panel-grid {
                grid-template-columns: 1fr;
            }
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
                padding: 16px;
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
                <strong>{{ $authUser['username'] ?? 'user' }}</strong>
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
                @if ($canAccessSidebarMenu('backup-database'))
                <a href="{{ route('dashboard.backup-database') }}" class="sidebar-menu-item active">Backup Database</a>
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
                <a href="{{ route('dashboard.account') }}" class="sidebar-menu-item">Akun</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </aside>

        <section class="main-card">
            <div class="head">
                <h1>Backup Database</h1>
                <p>Unduh salinan database dalam format MySQL (.sql) dan simpan versi cadangan sebelum melakukan perubahan besar.</p>
            </div>

                @if (session('status'))
                <div class="hero">
                    <strong>Status</strong>
                    <p>{{ session('status') }}</p>
                </div>
            @endif

            <div class="panel-grid">
                <div class="panel">
                    <div class="panel-icon">⇩</div>
                    <h3>Export Database</h3>
                    <p>Unduh seluruh struktur dan isi database dalam format MySQL yang dapat disimpan sebagai cadangan.</p>
                    <div class="panel-actions">
                        <form method="POST" action="{{ route('dashboard.backup-database.download') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary">Export Database</button>
                        </form>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-icon">⇪</div>
                    <h3>Import Backup</h3>
                    <p>Unggah file SQL untuk memulihkan database Anda. Pastikan file backup berasal dari sumber tepercaya.</p>
                    <div class="panel-actions">
                        <form method="POST" action="{{ route('dashboard.backup-database.import') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-file">
                                <label for="backup_file">Pilih file backup</label>
                                <input type="file" id="backup_file" name="backup_file" accept=".sql,.mysql,.txt" required>
                            </div>
                            <button type="submit" class="btn btn-secondary">Upload dan Import</button>
                        </form>
                    </div>
                </div>
            </div>

            <p class="note">Cadangan SQL termasuk struktur tabel dan data. Gunakan export sebelum membuat perubahan besar, dan import hanya jika backup sudah diverifikasi.</p>
        </section>
    </main>
</body>
</html>
