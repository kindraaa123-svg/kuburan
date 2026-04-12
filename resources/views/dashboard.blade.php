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
            grid-template-columns: repeat(5, minmax(0, 1fr));
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
            justify-content: flex-end;
            gap: 8px;
            padding: 10px;
            border-bottom: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.88);
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
            <nav class="sidebar-menu">
                <a href="{{ route('dashboard') }}" class="sidebar-menu-item active">Dashboard</a>
                <a href="{{ route('dashboard.data-blok') }}" class="sidebar-menu-item">Data Blok</a>
                <a href="{{ route('dashboard.data-plot') }}" class="sidebar-menu-item">Data Plot</a>
                <a href="{{ route('dashboard.data-almarhum') }}" class="sidebar-menu-item">Data Almarhum</a>
                <a href="{{ route('dashboard.data-user') }}" class="sidebar-menu-item">Data User</a>
                <a href="{{ route('dashboard.settings') }}" class="sidebar-menu-item">Pengaturan</a>
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
                <div class="stat"><small>Reservasi</small><strong>{{ (int) ($summary->reserved ?? 0) }}</strong></div>
                <div class="stat"><small>Maintenance</small><strong>{{ (int) ($summary->maintenance ?? 0) }}</strong></div>
            </div>

            <div class="map-panel">
                <div class="map-toolbar">
                    <button class="btn" type="button" data-map-action="zoom-out">-</button>
                    <span class="zoom" id="zoomLabel">100%</span>
                    <button class="btn" type="button" data-map-action="zoom-in">+</button>
                    <button class="btn" type="button" data-map-action="reset">Reset</button>
                </div>
                <div class="map-viewport" id="mapViewport">
                    <div class="map-scene" id="mapScene" style="width: {{ $mapWidth }}px; height: {{ $mapHeight }}px;">
                        @foreach ($blockMaps as $blockMap)
                            <div
                                class="block-area"
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

    <script>
        (() => {
            const viewport = document.getElementById('mapViewport');
            const scene = document.getElementById('mapScene');
            const zoomLabel = document.getElementById('zoomLabel');
            const controls = document.querySelectorAll('[data-map-action]');

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

            function render() {
                scene.style.transform = `translate(${translateX}px, ${translateY}px) scale(${scale})`;
                zoomLabel.textContent = `${Math.round(scale * 100)}%`;
            }

            function clampScale(v) {
                return Math.min(maxScale, Math.max(minScale, v));
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

            viewport.addEventListener('pointerdown', (event) => {
                if (event.button !== 0) return;
                isDragging = true;
                startX = event.clientX;
                startY = event.clientY;
                viewport.setPointerCapture(event.pointerId);
            });

            viewport.addEventListener('pointermove', (event) => {
                if (!isDragging) return;
                translateX += event.clientX - startX;
                translateY += event.clientY - startY;
                startX = event.clientX;
                startY = event.clientY;
                render();
            });

            viewport.addEventListener('pointerup', (event) => {
                isDragging = false;
                viewport.releasePointerCapture(event.pointerId);
            });

            viewport.addEventListener('pointercancel', () => { isDragging = false; });

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

            window.addEventListener('resize', fitToViewport);
            fitToViewport();
        })();
    </script>
</body>
</html>
