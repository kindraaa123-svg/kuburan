<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restore Data</title>
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
            --brand-strong: #540918;
            --ok-bg: rgba(122, 17, 41, 0.1);
            --ok-text: #7a1129;
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

        .flash {
            margin-top: 12px;
            border: 1px solid rgba(122, 17, 41, 0.22);
            background: var(--ok-bg);
            color: var(--ok-text);
            border-radius: 12px;
            padding: 10px 12px;
            font-size: 0.85rem;
            font-weight: 700;
        }

        .toolbar {
            margin-top: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .filter-label {
            font-size: 0.8rem;
            font-weight: 800;
            color: #5d3640;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .filter-select {
            min-width: 220px;
            min-height: 40px;
            border: 1px solid rgba(110, 52, 63, 0.2);
            border-radius: 10px;
            background: rgba(255, 253, 252, 0.95);
            color: var(--text);
            padding: 8px 10px;
            font-size: 0.82rem;
            font-weight: 700;
        }

        .filter-select:focus {
            outline: none;
            border-color: rgba(122, 17, 41, 0.46);
            box-shadow: 0 0 0 4px rgba(122, 17, 41, 0.1);
        }

        .table-wrap {
            margin-top: 12px;
            border: 1px solid rgba(115, 38, 54, 0.18);
            border-radius: 14px;
            overflow: auto;
            background: #fff;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 1100px;
        }

        th,
        td {
            border-bottom: 1px solid #ecdde1;
            padding: 10px 11px;
            text-align: left;
            font-size: 0.81rem;
            vertical-align: top;
            white-space: nowrap;
        }

        th {
            position: sticky;
            top: 0;
            background: #f5e8eb;
            font-weight: 800;
            color: #5a2532;
            z-index: 2;
        }

        td.actions {
            min-width: 240px;
        }

        .action-inline {
            display: inline-flex;
            gap: 8px;
            align-items: center;
        }

        .btn {
            border: 1px solid #b88895;
            border-radius: 10px;
            padding: 7px 10px;
            font-size: 0.78rem;
            font-weight: 800;
            cursor: pointer;
        }

        .btn-restore {
            background: rgba(122, 17, 41, 0.09);
            color: #7a1129;
            border-color: rgba(122, 17, 41, 0.28);
        }

        .btn-danger {
            background: rgba(146, 34, 59, 0.12);
            color: #92223b;
            border-color: rgba(146, 34, 59, 0.28);
        }

        tr:hover td {
            background: #fdf6f7;
        }

        .empty {
            text-align: center;
            color: var(--muted);
            padding: 18px;
        }

        .pagination-wrap {
            margin-top: 10px;
            display: flex;
            justify-content: flex-end;
        }

        .ajax-pagination-nav {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .page-btn {
            display: inline-block;
            border: 1px solid rgba(122, 17, 41, 0.22);
            border-radius: 8px;
            padding: 6px 10px;
            font-size: 0.78rem;
            font-weight: 700;
            color: #7a1129;
            background: rgba(122, 17, 41, 0.08);
            text-decoration: none;
        }

        .page-btn.disabled {
            color: #9e7d85;
            background: #f6eff1;
            border-color: #e2cfd4;
            cursor: not-allowed;
        }

        .page-info {
            font-size: 0.78rem;
            color: #6b4750;
            font-weight: 700;
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
                <a href="{{ route('dashboard.restore-data') }}" class="sidebar-menu-item active">Restore Data</a>
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
                <h1>Restore Data</h1>
                <p>Data yang dihapus akan masuk ke sini dan bisa dikembalikan atau dihapus permanen.</p>
            </div>

            @if (session('status'))
                <div class="flash">{{ session('status') }}</div>
            @endif

            <div class="toolbar">
                <label for="entityTypeFilter" class="filter-label">Filter Jenis Data</label>
                <select id="entityTypeFilter" class="filter-select">
                    <option value="" {{ ($entityTypeFilter ?? '') === '' ? 'selected' : '' }}>Semua Jenis</option>
                    <option value="block" {{ ($entityTypeFilter ?? '') === 'block' ? 'selected' : '' }}>Blok</option>
                    <option value="plot" {{ ($entityTypeFilter ?? '') === 'plot' ? 'selected' : '' }}>Plot</option>
                    <option value="deceased" {{ ($entityTypeFilter ?? '') === 'deceased' ? 'selected' : '' }}>Almarhum</option>
                    <option value="family" {{ ($entityTypeFilter ?? '') === 'family' ? 'selected' : '' }}>Kontak Keluarga</option>
                </select>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Username</th>
                            <th>IP Address</th>
                            <th>Longitude</th>
                            <th>Latitude</th>
                            <th>Jenis Data</th>
                            <th>Data</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="restoreDataBody">@include('partials.restore-data-rows', ['items' => $items])</tbody>
                </table>
            </div>
            <div class="pagination-wrap" id="restoreDataPagination">@include('partials.ajax-pagination', ['paginator' => $items])</div>
        </section>
    </main>
    <script>
        (function () {
            const tbody = document.getElementById('restoreDataBody');
            const pagination = document.getElementById('restoreDataPagination');
            const filter = document.getElementById('entityTypeFilter');
            if (!tbody || !pagination || !filter) {
                return;
            }

            async function loadData(url) {
                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                });
                if (!response.ok) {
                    return;
                }
                const payload = await response.json();
                if (typeof payload.tbody === 'string') {
                    tbody.innerHTML = payload.tbody;
                }
                if (typeof payload.pagination === 'string') {
                    pagination.innerHTML = payload.pagination;
                }
                window.history.replaceState({}, '', url);
            }

            function currentBaseUrl() {
                return '{{ route('dashboard.restore-data') }}';
            }

            function buildUrl(page = 1) {
                const url = new URL(currentBaseUrl(), window.location.origin);
                const entityType = filter.value.trim();
                if (entityType !== '') {
                    url.searchParams.set('entity_type', entityType);
                }
                if (page > 1) {
                    url.searchParams.set('page', String(page));
                }
                return url.toString();
            }

            pagination.addEventListener('click', (event) => {
                const link = event.target.closest('a.page-btn');
                if (!link) {
                    return;
                }
                event.preventDefault();
                loadData(link.href);
            });

            filter.addEventListener('change', () => {
                loadData(buildUrl(1));
            });
        })();
    </script>
</body>
</html>
