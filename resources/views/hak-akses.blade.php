<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hak Akses</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Space+Grotesk:wght@600;700&display=swap');

        :root {
            --bg: #edf3f1;
            --card: #ffffff;
            --line: #d2dfda;
            --text: #163036;
            --muted: #657c81;
            --brand: #1f7a67;
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

        .sidebar-menu { display: grid; align-content: start; gap: 8px; }
        .sidebar-bottom { display: grid; gap: 8px; }

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

        .main-card { padding: 14px; }

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
            margin-top: 10px;
            border-radius: 10px;
            border: 1px solid #c9ddd6;
            background: #eff8f4;
            padding: 9px 11px;
            color: #1a5a4d;
            font-size: .82rem;
            font-weight: 700;
        }

        .table-wrap {
            margin-top: 12px;
            border: 1px solid var(--line);
            border-radius: 12px;
            overflow: auto;
            background: #fff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 980px;
        }

        th, td {
            border-bottom: 1px solid #e3ece8;
            padding: 10px;
            text-align: left;
            font-size: .82rem;
            vertical-align: middle;
        }

        th {
            background: #f6fbf9;
            color: #27464d;
            font-weight: 700;
            white-space: nowrap;
        }

        .center {
            text-align: center;
        }

        .menu-note {
            margin-top: 10px;
            color: var(--muted);
            font-size: .78rem;
        }

        .btn-save {
            margin-top: 12px;
            border: 1px solid #1f705d;
            border-radius: 10px;
            background: linear-gradient(130deg, #2a9a82, var(--brand));
            color: #fff;
            font-weight: 700;
            font-size: .86rem;
            min-height: 40px;
            padding: 0 14px;
            cursor: pointer;
        }

        .empty {
            text-align: center;
            color: var(--muted);
            padding: 18px;
        }

        @media (max-width: 1080px) {
            .layout { grid-template-columns: 1fr; }
            .sidebar { position: static; max-height: none; }
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
                @if ($canAccessSidebarMenu('restore-data'))
                <a href="#" class="sidebar-menu-item">Restore Data</a>
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
                <h1>Hak Akses Sidebar</h1>
                <p>Pilih menu sidebar yang boleh diakses tiap level.</p>
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
                                <th>Level Name</th>
                                @foreach ($menuOptions as $menuName)
                                    <th class="center">{{ $menuName }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($levels as $level)
                                <tr>
                                    <td>{{ $level->levelname ?? ('Level #' . $level->levelid) }}</td>
                                    @foreach ($menuOptions as $menuKey => $menuName)
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
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ 1 + count($menuOptions) }}" class="empty">Data level belum tersedia.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <p class="menu-note">
                    Menu <strong>Dashboard</strong>, <strong>Akun</strong>, dan <strong>Logout</strong> selalu wajib tersedia saat login, jadi tidak dimasukkan ke tabel hak akses.
                </p>

                <button type="submit" class="btn-save">Simpan Hak Akses</button>
            </form>
        </section>
    </main>
</body>
</html>





