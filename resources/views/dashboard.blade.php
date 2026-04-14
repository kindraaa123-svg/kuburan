<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Denah Kuburan</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Space+Grotesk:wght@600;700&display=swap');

        :root {
            --bg: #edf3f1;
            --card: #ffffff;
            --line: #d2dfda;
            --text: #163036;
            --muted: #657c81;
            --brand: #1f7a67;
            --danger: #d16b3c;
            --ok: #2f8f59;
            --info: #3f73c9;
            --gray: #7f8f95;
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
            padding: 14px;
        }

        .head {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: flex-end;
            margin-bottom: 12px;
        }

        .head h1 {
            margin: 0;
            font-family: "Space Grotesk", sans-serif;
            font-size: 1.4rem;
        }

        .head p {
            margin: 5px 0 0;
            color: var(--muted);
            font-size: .84rem;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 8px;
            margin-bottom: 12px;
        }

        .stat {
            border: 1px solid var(--line);
            border-radius: 10px;
            background: #fbfdfc;
            padding: 9px;
        }

        .stat small { color: var(--muted); font-size: .7rem; font-weight: 700; }
        .stat strong { display: block; margin-top: 4px; font-size: 1.1rem; }

        .map-panel {
            border: 1px solid var(--line);
            border-radius: 13px;
            overflow: hidden;
            background: #f5f9f7;
        }

        .map-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            padding: 10px;
            border-bottom: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.88);
        }

        .map-edit-tools {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .map-zoom-tools {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: auto;
        }

        .map-edit-note {
            font-size: .74rem;
            color: #5c7278;
            font-weight: 700;
            display: none;
        }

        .map-edit-note.active {
            display: inline;
        }

        .btn {
            min-width: 34px;
            height: 34px;
            border: 1px solid #bcd0c9;
            border-radius: 8px;
            background: #fff;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-edit {
            min-width: 0;
            padding: 0 12px;
            background: #fff;
            color: #15493f;
        }

        .btn-save {
            min-width: 0;
            padding: 0 12px;
            background: #1f7a67;
            border-color: #1f7a67;
            color: #fff;
            display: none;
        }

        .btn-cancel {
            min-width: 0;
            padding: 0 12px;
            display: none;
        }

        .btn-save.active,
        .btn-cancel.active {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .zoom {
            min-width: 48px;
            text-align: center;
            color: var(--muted);
            font-size: .82rem;
            font-weight: 700;
            line-height: 34px;
        }

        .map-viewport {
            position: relative;
            height: min(72vh, 680px);
            overflow: hidden;
            cursor: move;
            user-select: none;
            touch-action: none;
        }

        .map-scene {
            position: absolute;
            left: 0;
            top: 0;
            transform-origin: 0 0;
            border-radius: 10px;
            background:
                linear-gradient(90deg, rgba(19, 42, 47, .05) 1px, transparent 1px) 0 0 / 40px 40px,
                linear-gradient(rgba(19, 42, 47, .05) 1px, transparent 1px) 0 0 / 40px 40px,
                #f8fbfa;
        }

        .block-area {
            position: absolute;
            border: 2px solid rgba(19, 42, 47, .18);
            border-radius: 14px;
            background: color-mix(in srgb, var(--block-color) 18%, #fff);
            cursor: default;
        }

        .map-scene.editing .block-area {
            cursor: grab;
            box-shadow: 0 4px 12px rgba(12, 35, 40, 0.16);
        }

        .map-scene.editing .block-area.is-dragging {
            cursor: grabbing;
            box-shadow: 0 8px 18px rgba(12, 35, 40, 0.22);
        }

        .map-scene.editing .plot {
            pointer-events: none;
        }

        .block-badge {
            position: absolute;
            left: 10px;
            top: 8px;
            font-size: .73rem;
            color: #fff;
            background: var(--block-color);
            border-radius: 999px;
            padding: 4px 9px;
            font-weight: 700;
        }

        .plot {
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 7px;
            border: 1px solid rgba(16, 45, 78, .14);
            font-size: .7rem;
            font-weight: 700;
            color: #fff;
            cursor: default;
        }

        .plot-occupied { background: var(--danger); }
        .plot-empty { background: var(--ok); }
        .plot-reserved { background: var(--info); }
        .plot-maintenance { background: var(--gray); }

        .hover-card {
            position: fixed;
            z-index: 60;
            width: 228px;
            background: #ffffff;
            border: 1px solid #c9d8d3;
            border-radius: 12px;
            box-shadow: 0 14px 26px rgba(16, 41, 48, 0.18);
            padding: 8px;
            display: none;
            pointer-events: auto;
        }

        .hover-card.active {
            display: block;
        }

        .hover-head {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .hover-photo {
            width: 42px;
            height: 42px;
            border-radius: 8px;
            border: 1px solid #cbd8d5;
            object-fit: cover;
            background: #eef4f2;
        }

        .hover-title {
            margin: 0;
            font-size: .82rem;
            font-weight: 700;
            color: #19363e;
            line-height: 1.25;
        }

        .hover-sub {
            margin: 2px 0 0;
            font-size: .68rem;
            color: #5f7479;
            font-weight: 600;
        }

        .hover-grid {
            margin-top: 8px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px;
        }

        .hover-cell {
            border: 1px solid #d8e4df;
            border-radius: 8px;
            padding: 5px 6px;
            background: #fbfdfc;
        }

        .hover-cell small {
            display: block;
            font-size: .62rem;
            color: #6f8387;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .hover-cell span {
            font-size: .73rem;
            font-weight: 700;
            color: #18353c;
        }

        .hover-action {
            margin-top: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            text-decoration: none;
            border-radius: 8px;
            border: 1px solid #a9c7bc;
            color: #12463d;
            background: #e9f4ef;
            font-size: .7rem;
            font-weight: 700;
            min-height: 30px;
            pointer-events: auto;
        }

        .legend {
            margin-top: 10px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            color: var(--muted);
            font-size: .8rem;
        }

        .dot { width: 11px; height: 11px; border-radius: 3px; display: inline-block; margin-right: 5px; }

        .settings-panel {
            margin-top: 12px;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: #fbfdfc;
            padding: 12px;
        }

        .settings-panel h3 {
            margin: 0;
            font-size: .98rem;
            font-family: "Space Grotesk", sans-serif;
        }

        .settings-grid {
            margin-top: 10px;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 8px;
        }

        .settings-item {
            border: 1px solid #d9e4df;
            border-radius: 10px;
            padding: 10px;
            background: #fff;
        }

        .settings-item strong {
            display: block;
            font-size: .84rem;
            margin-bottom: 4px;
        }

        .settings-item span {
            font-size: .75rem;
            color: var(--muted);
        }

        @media (max-width: 1080px) {
            .layout { grid-template-columns: 1fr; }
            .sidebar { position: static; max-height: none; }
            .stats { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        }

        @media (max-width: 700px) {
            .stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .settings-grid { grid-template-columns: 1fr; }
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
                <a href="{{ route('dashboard') }}" class="sidebar-menu-item active">Dashboard</a>
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
                <div>
                    <h1>Denah Kuburan</h1>
                    <p>Drag untuk geser peta, scroll/tombol untuk zoom.</p>
                </div>
            </div>

            <div class="stats">
                <div class="stat"><small>Total Petak</small><strong>{{ (int) ($summary->total ?? 0) }}</strong></div>
                <div class="stat"><small>Terisi</small><strong>{{ (int) ($summary->occupied ?? 0) }}</strong></div>
                <div class="stat"><small>Kosong</small><strong>{{ (int) ($summary->empty ?? 0) }}</strong></div>
            </div>

            <div class="map-panel">
                <div class="map-toolbar">
                    <div class="map-edit-tools">
                        @if ($canAccessSidebarMenu('data-blok'))
                            <button class="btn btn-edit" type="button" id="mapEditBtn">Edit Posisi</button>
                            <button class="btn btn-save" type="button" id="mapSaveBtn">Simpan Posisi</button>
                            <button class="btn btn-cancel" type="button" id="mapCancelBtn">Batal</button>
                        @endif
                        <span class="map-edit-note" id="mapEditNote">Mode edit aktif: geser blok untuk atur lokasi.</span>
                    </div>
                    <div class="map-zoom-tools">
                        <button class="btn" type="button" data-map-action="zoom-out">-</button>
                        <span class="zoom" id="zoomLabel">100%</span>
                        <button class="btn" type="button" data-map-action="zoom-in">+</button>
                        <button class="btn" type="button" data-map-action="reset">Reset</button>
                    </div>
                </div>
                <div class="map-viewport" id="mapViewport">
                    <div class="map-scene" id="mapScene" style="width: {{ $mapWidth }}px; height: {{ $mapHeight }}px;">
                        @foreach ($blockMaps as $blockMap)
                            <div
                                class="block-area"
                                data-block-id="{{ (int) $blockMap['id'] }}"
                                style="
                                    --block-color: {{ $blockMap['color'] }};
                                    left: {{ $blockMap['x'] }}px;
                                    top: {{ $blockMap['y'] }}px;
                                    width: {{ $blockMap['width'] }}px;
                                    height: {{ $blockMap['height'] }}px;
                                "
                            >
                                <span class="block-badge">{{ $blockMap['name'] }}</span>
                            </div>
                        @endforeach

                        @foreach ($mapPlots as $plot)
                            <a
                                href="{{ $plot['deceased_id'] ? route('deceased.detail', ['id' => $plot['deceased_id']]) : '#' }}"
                                class="plot plot-{{ $plot['status'] }}"
                                data-status="{{ $plot['status'] }}"
                                data-name="{{ $plot['deceased_name'] ?? '' }}"
                                data-age="{{ $plot['deceased_age'] ?? '' }}"
                                data-death-date="{{ $plot['deceased_death_date'] ?? '' }}"
                                data-photo="{{ $plot['deceased_photo_url'] ?? '' }}"
                                data-plot-label="{{ $plot['plot_label'] }}"
                                data-detail-url="{{ $plot['deceased_id'] ? route('deceased.detail', ['id' => $plot['deceased_id']]) : '' }}"
                                data-block-id="{{ (int) ($plot['block_id'] ?? 0) }}"
                                style="
                                    left: {{ (float) $plot['x'] }}px;
                                    top: {{ (float) $plot['y'] }}px;
                                    width: {{ (float) $plot['width'] }}px;
                                    height: {{ (float) $plot['height'] }}px;
                                    text-decoration: none;
                                "
                            >
                                {{ $plot['plot_number'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="legend">
                <span><i class="dot" style="background: var(--danger);"></i>Terisi</span>
                <span><i class="dot" style="background: var(--ok);"></i>Kosong</span>
                <span><i class="dot" style="background: var(--info);"></i>Reservasi</span>
                <span><i class="dot" style="background: var(--gray);"></i>Maintenance</span>
            </div>

            <section id="pengaturan" class="settings-panel">
                <h3>Pengaturan</h3>
                <div class="settings-grid">
                    <article class="settings-item">
                        <strong>Nama Website</strong>
                        <span>{{ $setting->systemname ?: '-' }}</span>
                    </article>
                    <article class="settings-item">
                        <strong>Kontak</strong>
                        <span>{{ $setting->systemcontact ?: '-' }}</span>
                    </article>
                    <article class="settings-item">
                        <strong>Manager</strong>
                        <span>{{ $setting->systemmanager ?: '-' }}</span>
                    </article>
                </div>
                <div style="margin-top:8px;font-size:.8rem;color:var(--muted);">
                    Alamat: {{ $setting->systemaddress ?: '-' }}
                </div>
                <div style="margin-top:10px;">
                    <a href="{{ route('dashboard.settings') }}" style="display:inline-block;padding:8px 12px;border:1px solid #bcd0c9;border-radius:8px;background:#fff;text-decoration:none;color:#184e43;font-weight:700;font-size:.82rem;">Buka Halaman Pengaturan</a>
                </div>
            </section>
        </section>
    </main>

    <div class="hover-card" id="plotHoverCard">
        <div class="hover-head">
            <img class="hover-photo" id="hoverPhoto" src="" alt="Foto almarhum">
            <div>
                <h4 class="hover-title" id="hoverName">-</h4>
                <p class="hover-sub" id="hoverPlot">-</p>
            </div>
        </div>
        <div class="hover-grid">
            <div class="hover-cell">
                <small>Umur Wafat</small>
                <span id="hoverAge">-</span>
            </div>
            <div class="hover-cell">
                <small>Tanggal Wafat</small>
                <span id="hoverDeathDate">-</span>
            </div>
        </div>
        <a class="hover-action" id="hoverDetailBtn" href="#">Lihat Lebih Detail</a>
    </div>

    <script>
        (() => {
            const viewport = document.getElementById('mapViewport');
            const scene = document.getElementById('mapScene');
            const zoomLabel = document.getElementById('zoomLabel');
            const controls = document.querySelectorAll('[data-map-action]');
            const hoverCard = document.getElementById('plotHoverCard');
            const hoverPhoto = document.getElementById('hoverPhoto');
            const hoverName = document.getElementById('hoverName');
            const hoverPlot = document.getElementById('hoverPlot');
            const hoverAge = document.getElementById('hoverAge');
            const hoverDeathDate = document.getElementById('hoverDeathDate');
            const hoverDetailBtn = document.getElementById('hoverDetailBtn');
            const mapEditBtn = document.getElementById('mapEditBtn');
            const mapSaveBtn = document.getElementById('mapSaveBtn');
            const mapCancelBtn = document.getElementById('mapCancelBtn');
            const mapEditNote = document.getElementById('mapEditNote');
            const plotElements = document.querySelectorAll('.plot');
            const blockElements = document.querySelectorAll('.block-area');
            const savePositionsUrl = @json(route('dashboard.data-blok.positions'));
            const csrfToken = @json(csrf_token());

            if (!viewport || !scene) {
                return;
            }

            const minScale = 0.35;
            const maxScale = 2.8;
            let scale = 1;
            let translateX = 0;
            let translateY = 0;
            let isDragging = false;
            let startX = 0;
            let startY = 0;
            let hideTimer = null;
            let isHoverCardActive = false;
            let isEditMode = false;
            let blockDragState = null;
            let isSavingPositions = false;
            const initialBlockPositions = new Map();
            const plotByBlockId = new Map();

            function render() {
                scene.style.transform = `translate(${translateX}px, ${translateY}px) scale(${scale})`;
                zoomLabel.textContent = `${Math.round(scale * 100)}%`;
            }

            function setBlockPosition(blockElement, x, y) {
                blockElement.style.left = `${x}px`;
                blockElement.style.top = `${y}px`;
            }

            function getBlockPosition(blockElement) {
                const left = parseFloat(blockElement.style.left || '0');
                const top = parseFloat(blockElement.style.top || '0');
                return {
                    x: Number.isFinite(left) ? left : 0,
                    y: Number.isFinite(top) ? top : 0,
                };
            }

            function toggleEditMode(nextState) {
                isEditMode = nextState;
                scene.classList.toggle('editing', isEditMode);

                if (mapEditNote) {
                    mapEditNote.classList.toggle('active', isEditMode);
                }
                if (mapSaveBtn) {
                    mapSaveBtn.classList.toggle('active', isEditMode);
                }
                if (mapCancelBtn) {
                    mapCancelBtn.classList.toggle('active', isEditMode);
                }
                if (mapEditBtn) {
                    mapEditBtn.style.display = isEditMode ? 'none' : 'inline-flex';
                }

                if (isEditMode) {
                    hideHoverCard();
                    initialBlockPositions.clear();
                    blockElements.forEach((blockElement) => {
                        const blockId = parseInt(blockElement.dataset.blockId || '0', 10);
                        if (!Number.isFinite(blockId) || blockId <= 0) {
                            return;
                        }
                        initialBlockPositions.set(blockId, getBlockPosition(blockElement));
                    });
                }
            }

            function clampScale(v) {
                return Math.min(maxScale, Math.max(minScale, v));
            }

            function initialsFromName(name) {
                return (name || 'A')
                    .split(' ')
                    .filter(Boolean)
                    .slice(0, 2)
                    .map((part) => part.charAt(0).toUpperCase())
                    .join('');
            }

            function placeholderPhoto(name) {
                const initials = initialsFromName(name);
                const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="120" height="120"><rect width="100%" height="100%" fill="#dde9e5"/><text x="50%" y="54%" dominant-baseline="middle" text-anchor="middle" font-family="Arial, sans-serif" font-size="38" fill="#2f545d">${initials}</text></svg>`;
                return `data:image/svg+xml;utf8,${encodeURIComponent(svg)}`;
            }

            function placeHoverCard(clientX, clientY) {
                if (!hoverCard) {
                    return;
                }

                const margin = 16;
                const cardWidth = hoverCard.offsetWidth || 228;
                const cardHeight = hoverCard.offsetHeight || 188;
                let left = clientX + 18;
                let top = clientY + 18;

                if (left + cardWidth > window.innerWidth - margin) {
                    left = clientX - cardWidth - 18;
                }

                if (top + cardHeight > window.innerHeight - margin) {
                    top = window.innerHeight - cardHeight - margin;
                }

                if (top < margin) {
                    top = margin;
                }

                hoverCard.style.left = `${left}px`;
                hoverCard.style.top = `${top}px`;
            }

            function showHoverCard(plot, event) {
                if (isEditMode) {
                    return;
                }
                if (!hoverCard || !hoverPhoto || !hoverName || !hoverPlot || !hoverAge || !hoverDeathDate || !hoverDetailBtn) {
                    return;
                }

                if (hideTimer) {
                    window.clearTimeout(hideTimer);
                }

                const name = plot.dataset.name || '-';
                const plotLabel = plot.dataset.plotLabel || '-';
                const age = plot.dataset.age ? `${plot.dataset.age} tahun` : '-';
                const deathDate = plot.dataset.deathDate || '-';
                const photo = plot.dataset.photo || placeholderPhoto(name);
                const statusRaw = (plot.dataset.status || '').toLowerCase();
                const status = statusRaw === 'occupied' ? 'Terisi' : 'Kosong';
                const detailUrl = plot.dataset.detailUrl || '#';
                const isEmptyPlot = statusRaw !== 'occupied';
                const displayName = isEmptyPlot ? 'Kosong' : name;

                hoverPhoto.src = isEmptyPlot ? placeholderPhoto('Kosong') : photo;
                hoverPhoto.alt = `Foto ${displayName}`;
                hoverName.textContent = displayName;
                hoverPlot.textContent = isEmptyPlot ? plotLabel : `${plotLabel} - ${status}`;
                hoverAge.textContent = isEmptyPlot ? '-' : age;
                hoverDeathDate.textContent = isEmptyPlot ? '-' : deathDate;
                hoverDetailBtn.href = detailUrl;
                hoverDetailBtn.style.display = isEmptyPlot || detailUrl === '#' ? 'none' : 'inline-flex';

                hoverCard.classList.add('active');
                placeHoverCard(event.clientX, event.clientY);
            }

            function hideHoverCard() {
                if (!hoverCard) {
                    return;
                }

                hoverCard.classList.remove('active');
            }

            function queueHideHoverCard() {
                if (hideTimer) {
                    window.clearTimeout(hideTimer);
                }

                hideTimer = window.setTimeout(() => {
                    if (!isHoverCardActive) {
                        hideHoverCard();
                    }
                }, 140);
            }

            function zoomAt(clientX, clientY, factor) {
                const rect = viewport.getBoundingClientRect();
                const nextScale = clampScale(scale * factor);
                const vx = clientX - rect.left;
                const vy = clientY - rect.top;
                const worldX = (vx - translateX) / scale;
                const worldY = (vy - translateY) / scale;

                scale = nextScale;
                translateX = vx - (worldX * scale);
                translateY = vy - (worldY * scale);
                render();
            }

            function fitToViewport() {
                const padding = 30;
                const viewWidth = viewport.clientWidth - (padding * 2);
                const viewHeight = viewport.clientHeight - (padding * 2);
                const fitScale = clampScale(Math.min(viewWidth / scene.offsetWidth, viewHeight / scene.offsetHeight, 1));
                scale = fitScale;
                translateX = (viewport.clientWidth - (scene.offsetWidth * scale)) / 2;
                translateY = (viewport.clientHeight - (scene.offsetHeight * scale)) / 2;
                render();
            }

            function applyBlockDeltaToPlots(blockId, dx, dy) {
                const relatedPlots = plotByBlockId.get(blockId) || [];
                relatedPlots.forEach((plotElement) => {
                    const left = parseFloat(plotElement.style.left || '0');
                    const top = parseFloat(plotElement.style.top || '0');
                    const nextLeft = (Number.isFinite(left) ? left : 0) + dx;
                    const nextTop = (Number.isFinite(top) ? top : 0) + dy;
                    plotElement.style.left = `${nextLeft}px`;
                    plotElement.style.top = `${nextTop}px`;
                });
            }

            function restoreInitialPositions() {
                blockElements.forEach((blockElement) => {
                    const blockId = parseInt(blockElement.dataset.blockId || '0', 10);
                    if (!Number.isFinite(blockId) || blockId <= 0) {
                        return;
                    }

                    const previous = initialBlockPositions.get(blockId);
                    if (!previous) {
                        return;
                    }

                    const current = getBlockPosition(blockElement);
                    const dx = previous.x - current.x;
                    const dy = previous.y - current.y;
                    setBlockPosition(blockElement, previous.x, previous.y);
                    applyBlockDeltaToPlots(blockId, dx, dy);
                });
            }

            async function saveBlockPositions() {
                if (isSavingPositions) {
                    return;
                }

                const blocks = [];
                blockElements.forEach((blockElement) => {
                    const blockId = parseInt(blockElement.dataset.blockId || '0', 10);
                    if (!Number.isFinite(blockId) || blockId <= 0) {
                        return;
                    }

                    const current = getBlockPosition(blockElement);
                    blocks.push({
                        id: blockId,
                        x: Math.round(current.x),
                        y: Math.round(current.y),
                    });
                });

                if (blocks.length === 0) {
                    toggleEditMode(false);
                    return;
                }

                isSavingPositions = true;
                if (mapSaveBtn) {
                    mapSaveBtn.disabled = true;
                }

                try {
                    const response = await fetch(savePositionsUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({ blocks }),
                    });

                    const payload = await response.json().catch(() => ({}));
                    if (!response.ok) {
                        throw new Error(payload?.message || 'Gagal menyimpan posisi denah.');
                    }

                    toggleEditMode(false);
                } catch (error) {
                    alert(error.message || 'Gagal menyimpan posisi denah.');
                } finally {
                    isSavingPositions = false;
                    if (mapSaveBtn) {
                        mapSaveBtn.disabled = false;
                    }
                }
            }

            viewport.addEventListener('pointerdown', (event) => {
                if (event.button !== 0) return;
                if (isEditMode) return;
                hideHoverCard();
                isDragging = true;
                startX = event.clientX;
                startY = event.clientY;
                viewport.setPointerCapture(event.pointerId);
            });

            viewport.addEventListener('pointermove', (event) => {
                if (blockDragState) return;
                if (!isDragging) return;
                translateX += event.clientX - startX;
                translateY += event.clientY - startY;
                startX = event.clientX;
                startY = event.clientY;
                render();
            });

            viewport.addEventListener('pointerup', (event) => {
                isDragging = false;
                if (viewport.hasPointerCapture(event.pointerId)) {
                    viewport.releasePointerCapture(event.pointerId);
                }
            });

            viewport.addEventListener('pointercancel', () => {
                isDragging = false;
            });

            viewport.addEventListener('wheel', (event) => {
                event.preventDefault();
                zoomAt(event.clientX, event.clientY, event.deltaY < 0 ? 1.1 : 0.9);
            }, { passive: false });

            controls.forEach((button) => {
                button.addEventListener('click', () => {
                    const action = button.getAttribute('data-map-action');
                    const rect = viewport.getBoundingClientRect();
                    const cx = rect.left + (rect.width / 2);
                    const cy = rect.top + (rect.height / 2);

                    if (action === 'zoom-in') zoomAt(cx, cy, 1.15);
                    if (action === 'zoom-out') zoomAt(cx, cy, 0.87);
                    if (action === 'reset') fitToViewport();
                });
            });

            plotElements.forEach((plotElement) => {
                const blockId = parseInt(plotElement.dataset.blockId || '0', 10);
                if (!Number.isFinite(blockId) || blockId <= 0) {
                    return;
                }

                if (!plotByBlockId.has(blockId)) {
                    plotByBlockId.set(blockId, []);
                }
                plotByBlockId.get(blockId).push(plotElement);
            });

            blockElements.forEach((blockElement) => {
                blockElement.addEventListener('pointerdown', (event) => {
                    if (!isEditMode || event.button !== 0) {
                        return;
                    }

                    const blockId = parseInt(blockElement.dataset.blockId || '0', 10);
                    if (!Number.isFinite(blockId) || blockId <= 0) {
                        return;
                    }

                    event.preventDefault();
                    event.stopPropagation();
                    hideHoverCard();

                    const position = getBlockPosition(blockElement);
                    blockElement.classList.add('is-dragging');
                    blockElement.setPointerCapture(event.pointerId);

                    blockDragState = {
                        blockId,
                        pointerId: event.pointerId,
                        startClientX: event.clientX,
                        startClientY: event.clientY,
                        startX: position.x,
                        startY: position.y,
                        element: blockElement,
                    };
                });

                blockElement.addEventListener('pointermove', (event) => {
                    if (!blockDragState || blockDragState.pointerId !== event.pointerId) {
                        return;
                    }

                    const dx = (event.clientX - blockDragState.startClientX) / scale;
                    const dy = (event.clientY - blockDragState.startClientY) / scale;
                    const nextX = blockDragState.startX + dx;
                    const nextY = blockDragState.startY + dy;
                    const current = getBlockPosition(blockDragState.element);
                    const moveX = nextX - current.x;
                    const moveY = nextY - current.y;

                    if (moveX === 0 && moveY === 0) {
                        return;
                    }

                    setBlockPosition(blockDragState.element, nextX, nextY);
                    applyBlockDeltaToPlots(blockDragState.blockId, moveX, moveY);
                });

                const endBlockDrag = (event) => {
                    if (!blockDragState || blockDragState.pointerId !== event.pointerId) {
                        return;
                    }

                    blockDragState.element.classList.remove('is-dragging');
                    if (blockDragState.element.hasPointerCapture(event.pointerId)) {
                        blockDragState.element.releasePointerCapture(event.pointerId);
                    }
                    blockDragState = null;
                };

                blockElement.addEventListener('pointerup', endBlockDrag);
                blockElement.addEventListener('pointercancel', endBlockDrag);
            });

            if (mapEditBtn) {
                mapEditBtn.addEventListener('click', () => {
                    toggleEditMode(true);
                });
            }

            if (mapCancelBtn) {
                mapCancelBtn.addEventListener('click', () => {
                    restoreInitialPositions();
                    toggleEditMode(false);
                });
            }

            if (mapSaveBtn) {
                mapSaveBtn.addEventListener('click', saveBlockPositions);
            }

            window.addEventListener('resize', fitToViewport);
            fitToViewport();

            plotElements.forEach((plot) => {
                plot.addEventListener('mouseenter', (event) => showHoverCard(plot, event));
                plot.addEventListener('mouseleave', queueHideHoverCard);
            });

            if (hoverCard) {
                hoverCard.addEventListener('mouseenter', () => {
                    isHoverCardActive = true;
                    if (hideTimer) {
                        window.clearTimeout(hideTimer);
                    }
                });

                hoverCard.addEventListener('mouseleave', () => {
                    isHoverCardActive = false;
                    queueHideHoverCard();
                });
            }
        })();
    </script>
</body>
</html>








