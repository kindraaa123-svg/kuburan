<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hak Akses</title>
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
            --accent: #7a1129;
            --accent-strong: #540918;
            --accent-soft: rgba(122, 17, 41, 0.14);
            --ok: #7a1129;
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
            box-shadow: 0 26px 54px rgba(4, 13, 18, 0.4);
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

        .head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
            padding-bottom: 12px;
            border-bottom: 1px solid rgba(115, 38, 54, 0.18);
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
            max-width: 640px;
        }

        .badge {
            border: 1px solid rgba(122, 17, 41, 0.22);
            background: var(--accent-soft);
            color: var(--accent-strong);
            border-radius: 999px;
            font-size: 0.76rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 8px 12px;
            white-space: nowrap;
        }

        .status {
            margin-top: 12px;
            border-radius: 12px;
            border: 1px solid rgba(122, 17, 41, 0.22);
            background: rgba(122, 17, 41, 0.09);
            padding: 10px 12px;
            color: var(--ok);
            font-size: 0.84rem;
            font-weight: 700;
        }

        .table-wrap {
            margin-top: 14px;
            border: 1px solid rgba(115, 38, 54, 0.18);
            border-radius: 14px;
            overflow: auto;
            background: #fff;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 980px;
        }

        th,
        td {
            border-bottom: 1px solid #ecdde1;
            padding: 10px 12px;
            text-align: left;
            font-size: 0.82rem;
            vertical-align: middle;
        }

        thead th {
            position: sticky;
            top: 0;
            z-index: 2;
            background: #f5e8eb;
            color: #5a2532;
            font-weight: 800;
            white-space: nowrap;
        }

        thead th:first-child,
        tbody td:first-child {
            position: sticky;
            left: 0;
            z-index: 1;
            background: #ffffff;
        }

        thead th:first-child {
            z-index: 3;
            background: #f5e8eb;
        }

        tbody tr:hover td {
            background: #fdf6f7;
        }

        .center {
            text-align: center;
        }

        .level-name {
            min-width: 190px;
            font-weight: 700;
            color: #5a2532;
        }

        input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--accent);
            cursor: pointer;
        }

        .form-foot {
            margin-top: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .menu-note {
            margin: 0;
            color: var(--muted);
            font-size: 0.79rem;
            line-height: 1.6;
            max-width: 760px;
        }

        .btn-save {
            border: 1px solid #6d0f24;
            border-radius: 12px;
            background: linear-gradient(130deg, #a52142 0%, var(--accent) 52%, #4a0615 100%);
            color: #fff;
            font-weight: 800;
            font-size: 0.85rem;
            min-height: 42px;
            padding: 0 16px;
            cursor: pointer;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
            box-shadow: 0 14px 24px rgba(84, 9, 24, 0.24);
        }

        .btn-save:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 28px rgba(84, 9, 24, 0.3);
        }

        .empty {
            text-align: center;
            color: var(--muted);
            padding: 18px;
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

        @media (max-width: 680px) {
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

            .head {
                flex-direction: column;
                align-items: flex-start;
            }

            .form-foot {
                align-items: stretch;
            }

            .btn-save {
                width: 100%;
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
                <a href="{{ route('dashboard.backup-database') }}" class="sidebar-menu-item">Backup Database</a>
                @endif
                @if ($canAccessSidebarMenu('restore-data'))
                <a href="{{ route('dashboard.restore-data') }}" class="sidebar-menu-item">Restore Data</a>
                @endif
                @if ($canAccessSidebarMenu('hak-akses'))
                <a href="{{ route('dashboard.hak-akses') }}" class="sidebar-menu-item active">Hak Akses</a>
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
                <div>
                    <h1>Hak Akses Sidebar</h1>
                    <p>Pilih menu sidebar yang boleh diakses tiap level user untuk menjaga dashboard tetap bersih dan sesuai peran.</p>
                </div>
                <span class="badge">Role Matrix</span>
            </div>

            @if (session('status'))
                <div class="status">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('dashboard.hak-akses.update') }}">
                @csrf
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Hak Akses</th>
                                @foreach ($levels as $level)
                                    <th class="center">{{ $level->levelname ?? ('Level #' . $level->levelid) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($menuOptions as $menuKey => $menuName)
                                <tr>
                                    <td class="level-name">{{ $menuName }}</td>
                                    @forelse ($levels as $level)
                                        @php
                                            $isChecked = (bool) ($matrix[$level->levelid][$menuKey] ?? false);
                                        @endphp
                                        <td class="center">
                                            <input
                                                type="checkbox"
                                                name="access[{{ $level->levelid }}][]"
                                                value="{{ $menuKey }}"
                                                {{ $isChecked ? 'checked' : '' }}
                                            >
                                        </td>
                                    @empty
                                        <td class="empty">-</td>
                                    @endforelse
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ 1 + count($levels) }}" class="empty">Data menu hak akses belum tersedia.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="form-foot">
                    <p class="menu-note">
                        Menu <strong>Dashboard</strong>, <strong>Akun</strong>, dan <strong>Logout</strong> selalu wajib tersedia saat login, jadi tidak dimasukkan ke tabel hak akses.
                    </p>
                    <button type="submit" class="btn-save">Simpan Hak Akses</button>
                </div>
            </form>
        </section>
    </main>
</body>
</html>





