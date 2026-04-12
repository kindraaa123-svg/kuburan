<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Plot</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Space+Grotesk:wght@600;700&display=swap');

        :root {
            --bg: #edf3f1;
            --card: #ffffff;
            --line: #d2dfda;
            --text: #163036;
            --muted: #657c81;
            --occupied: #d16b3c;
            --empty: #2f8f59;
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
            border-color: #a7c8bf;
            background: #e9f4f0;
            color: #0f4b3f;
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

        .flash {
            margin-top: 10px;
            border: 1px solid #bcd8cd;
            background: #eef8f2;
            color: #0f4b3f;
            border-radius: 10px;
            padding: 10px 12px;
            font-size: .84rem;
        }

        .flash.flash-error {
            margin-top: 10px;
            border-color: #e3c1c1;
            background: #fff3f3;
            color: #7a2323;
        }

        .flash ul {
            margin: 6px 0 0 16px;
            padding: 0;
        }

        .head h1 {
            margin: 0;
            font-family: "Space Grotesk", sans-serif;
            font-size: 1.4rem;
        }

        .head p {
            margin: 6px 0 0;
            color: var(--muted);
            font-size: .84rem;
        }

        .legend {
            margin-top: 12px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            color: var(--muted);
            font-size: .8rem;
        }

        .dot { width: 11px; height: 11px; border-radius: 3px; display: inline-block; margin-right: 5px; }

        .cards {
            margin-top: 14px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
            gap: 12px;
        }

        .block-card {
            border: 1px solid var(--line);
            border-radius: 12px;
            background: #fbfdfc;
            padding: 10px;
        }

        .block-head {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            align-items: center;
            margin-bottom: 8px;
        }

        .block-head h3 {
            margin: 0;
            font-size: .95rem;
        }

        .block-head-right {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 6px;
        }

        .mini-meta {
            font-size: .75rem;
            color: var(--muted);
            font-weight: 700;
        }

        .btn-add-plot {
            border: 1px solid #b7ccc5;
            background: #ecf6f2;
            color: #13483d;
            border-radius: 8px;
            font-size: .74rem;
            font-weight: 700;
            padding: 6px 10px;
            cursor: pointer;
        }

        .btn-add-plot:hover {
            background: #e2f1eb;
        }

        .btn-add-plot:disabled {
            opacity: .55;
            cursor: not-allowed;
            background: #edf2f0;
            color: #6f8380;
        }

        .map-box {
            border: 1px solid #d7e2de;
            border-radius: 10px;
            overflow: auto;
            background: #f7fbf9;
            padding: 8px;
        }

        .map-canvas {
            position: relative;
            border-radius: 8px;
            background:
                linear-gradient(90deg, rgba(19, 42, 47, .05) 1px, transparent 1px) 0 0 / 30px 30px,
                linear-gradient(rgba(19, 42, 47, .05) 1px, transparent 1px) 0 0 / 30px 30px,
                #fff;
        }

        .plot {
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            border: 1px solid rgba(16, 45, 78, .14);
            font-size: .68rem;
            font-weight: 700;
            color: #fff;
            text-decoration: none;
        }

        .plot-occupied { background: var(--occupied); }
        .plot-empty { background: var(--empty); }

        .empty-state {
            margin-top: 12px;
            border: 1px dashed #c4d5cf;
            border-radius: 10px;
            padding: 12px;
            color: var(--muted);
            background: #f8fcfa;
            font-size: .85rem;
        }

        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(14, 31, 35, .45);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            z-index: 1200;
        }

        .modal-backdrop.show {
            display: flex;
        }

        .modal-card {
            width: min(980px, 100%);
            max-height: calc(100vh - 40px);
            overflow: auto;
            background: #fff;
            border: 1px solid #d3e0db;
            border-radius: 14px;
            box-shadow: 0 18px 28px rgba(15, 39, 44, .2);
            padding: 14px;
        }

        .modal-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .modal-head h3 {
            margin: 0;
            font-size: 1rem;
            font-family: "Space Grotesk", sans-serif;
        }

        .modal-close {
            border: 1px solid #d5e2de;
            border-radius: 8px;
            background: #f5faf8;
            color: #244e45;
            width: 34px;
            height: 34px;
            cursor: pointer;
        }

        .field-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .field-full {
            grid-column: 1 / -1;
        }

        .mini-map-wrap {
            border: 1px solid #d4e1dc;
            border-radius: 10px;
            background: #f8fcfa;
            padding: 8px;
        }

        .mini-map-shell {
            min-height: 360px;
            max-height: 56vh;
            display: flex;
            align-items: flex-start;
            justify-content: flex-start;
            overflow: auto;
            border: 1px solid #d6e2dd;
            border-radius: 8px;
            background: #fff;
            padding: 8px;
            cursor: grab;
            user-select: none;
        }

        .mini-map-shell.panning {
            cursor: grabbing;
        }

        .mini-map-stage {
            position: relative;
            border-radius: 6px;
            background:
                linear-gradient(90deg, rgba(19, 42, 47, .06) 1px, transparent 1px) 0 0 / 24px 24px,
                linear-gradient(rgba(19, 42, 47, .06) 1px, transparent 1px) 0 0 / 24px 24px,
                #fefefe;
            border: 1px solid #dde8e3;
            touch-action: none;
        }

        .mini-plot {
            position: absolute;
            border-radius: 4px;
            border: 1px solid rgba(16, 45, 78, .2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .64rem;
            font-weight: 700;
            color: #fff;
            user-select: none;
            pointer-events: none;
        }

        .mini-plot-occupied { background: var(--occupied); }
        .mini-plot-empty { background: var(--empty); }

        .mini-plot-new {
            background: #7ec8a5;
            border: 2px solid #1e7d57;
            color: #0f3f31;
            cursor: grab;
            pointer-events: auto;
        }

        .mini-plot-new.dragging { cursor: grabbing; }

        .mini-map-note {
            margin-top: 7px;
            font-size: .74rem;
            color: var(--muted);
        }

        .field label {
            display: block;
            font-size: .76rem;
            color: var(--muted);
            margin-bottom: 4px;
            font-weight: 700;
        }

        .field input {
            width: 100%;
            border: 1px solid #ccdcd7;
            border-radius: 8px;
            padding: 9px 10px;
            font-size: .85rem;
            color: var(--text);
            background: #fff;
        }

        .modal-alert {
            margin-bottom: 10px;
        }

        .modal-actions {
            margin-top: 12px;
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }

        .btn-secondary, .btn-primary {
            border-radius: 8px;
            padding: 9px 12px;
            font-size: .82rem;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-secondary {
            border: 1px solid #cadbd5;
            background: #f3f9f6;
            color: #1d4c42;
        }

        .btn-primary {
            border: 1px solid #1f6f5b;
            background: #2f8f59;
            color: #fff;
        }

        @media (max-width: 1080px) {
            .layout { grid-template-columns: 1fr; }
            .sidebar { position: static; max-height: none; }
        }

        @media (max-width: 760px) {
            .cards { grid-template-columns: 1fr; }
            .field-grid { grid-template-columns: 1fr; }
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
                <a href="{{ route('dashboard.data-plot') }}" class="sidebar-menu-item active">Data Plot</a>
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
                <h1>Data Plot per Blok</h1>
                <p>Setiap blok ditampilkan dalam card dengan denah plot masing-masing.</p>
            </div>

            @if (session('status'))
                <div class="flash">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="flash flash-error">
                    Gagal menambahkan plot:
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="legend">
                <span><i class="dot" style="background: var(--occupied);"></i>Terisi</span>
                <span><i class="dot" style="background: var(--empty);"></i>Kosong</span>
            </div>

            @if (count($plotCards) === 0)
                <div class="empty-state">Belum ada data blok.</div>
            @else
                <div class="cards">
                    @foreach ($plotCards as $card)
                        <article
                            class="block-card"
                            data-block-id="{{ $card['blockid'] }}"
                            data-total-plots="{{ $card['total_plots'] }}"
                            data-occupied-plots="{{ $card['occupied_plots'] }}"
                            data-max-plots="{{ $card['max_plots'] }}"
                        >
                            <div class="block-head">
                                <h3>{{ $card['block_name'] }}</h3>
                                <div class="block-head-right">
                                    <span class="mini-meta" data-block-meta>Total {{ $card['total_plots'] }}/{{ $card['max_plots'] }} | Terisi {{ $card['occupied_plots'] }}</span>
                                    <button
                                        type="button"
                                        class="btn-add-plot"
                                        data-block-id="{{ $card['blockid'] }}"
                                        data-block-name="{{ $card['block_name'] }}"
                                        data-total-plots="{{ $card['total_plots'] }}"
                                        data-max-plots="{{ $card['max_plots'] }}"
                                        @if ($card['total_plots'] >= $card['max_plots']) disabled title="Maksimal {{ $card['max_plots'] }} plot per blok" @endif
                                    >
                                        + Tambah Plot
                                    </button>
                                </div>
                            </div>
                            <div class="map-box">
                                <div
                                    class="map-canvas"
                                    data-block-id="{{ $card['blockid'] }}"
                                    data-canvas-width="{{ $card['canvas_width'] }}"
                                    data-canvas-height="{{ $card['canvas_height'] }}"
                                    data-map-color="{{ $card['map_color'] }}"
                                    style="width: {{ $card['canvas_width'] }}px; height: {{ $card['canvas_height'] }}px; border-top: 3px solid {{ $card['map_color'] }};"
                                >
                                    @foreach ($card['plots'] as $plot)
                                        <div
                                            class="plot plot-{{ $plot['status'] }}"
                                            title="Plot {{ $plot['number'] }} - {{ strtoupper($plot['status']) }}"
                                            data-plot-id="{{ $plot['plotid'] }}"
                                            data-plot-number="{{ $plot['number'] }}"
                                            data-row-number="{{ $plot['row_number'] ?? '' }}"
                                            data-position-x="{{ $plot['x'] }}"
                                            data-position-y="{{ $plot['y'] }}"
                                            data-width="{{ $plot['width'] }}"
                                            data-height="{{ $plot['height'] }}"
                                            data-status="{{ $plot['status'] }}"
                                            style="
                                                left: {{ $plot['x'] }}px;
                                                top: {{ $plot['y'] }}px;
                                                width: {{ $plot['width'] }}px;
                                                height: {{ $plot['height'] }}px;
                                            "
                                        >
                                            {{ $plot['number'] }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    </main>

    <div class="modal-backdrop" id="addPlotModal" aria-hidden="true">
        <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="addPlotTitle">
            <div class="modal-head">
                <h3 id="addPlotTitle">Tambah Plot</h3>
                <button type="button" class="modal-close" id="closeAddPlotModal">✕</button>
            </div>

            <form method="POST" action="{{ route('dashboard.data-plot.store') }}" id="addPlotForm">
                @csrf
                <input type="hidden" id="editPlotId" value="">
                <input type="hidden" name="block_id" id="addPlotBlockId" value="{{ old('block_id') }}">
                <input type="hidden" name="block_name" id="addPlotBlockNameInput" value="{{ old('block_name') }}">
                <div class="flash flash-error modal-alert" id="addPlotModalError" style="display: none;"></div>

                <div class="field-grid">
                    <div class="field field-full">
                        <label>Denah Mini Blok (geser plot baru)</label>
                        <div class="mini-map-wrap">
                            <div class="mini-map-shell" id="miniMapShell">
                                <div class="mini-map-stage" id="miniMapStage"></div>
                            </div>
                            <div class="mini-map-note">Plot baru otomatis berstatus kosong. Geser kotak "BARU" untuk atur posisi X/Y, lalu klik-tahan area denah untuk geser tampilan.</div>
                        </div>
                    </div>
                    <div class="field field-full">
                        <label for="plot_number">Nomor Plot</label>
                        <input id="plot_number" name="plot_number" type="text" required value="{{ old('plot_number') }}" placeholder="Contoh: A-01">
                    </div>
                    <div class="field">
                        <label for="row_number">Baris (opsional)</label>
                        <input id="row_number" name="row_number" type="text" value="{{ old('row_number') }}" placeholder="Contoh: 1">
                    </div>
                    <div class="field">
                        <label for="position_x">Posisi X</label>
                        <input id="position_x" name="position_x" type="number" step="0.01" min="0" required value="{{ old('position_x', 0) }}">
                    </div>
                    <div class="field">
                        <label for="position_y">Posisi Y</label>
                        <input id="position_y" name="position_y" type="number" step="0.01" min="0" required value="{{ old('position_y', 0) }}">
                    </div>
                    <div class="field">
                        <label for="width">Lebar</label>
                        <input id="width" name="width" type="number" step="0.01" min="1" required value="{{ old('width', 60) }}">
                    </div>
                    <div class="field">
                        <label for="height">Tinggi</label>
                        <input id="height" name="height" type="number" step="0.01" min="1" required value="{{ old('height', 40) }}">
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelAddPlotBtn">Batal</button>
                    <button type="submit" class="btn-primary" id="saveAddPlotBtn">Simpan Plot</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (() => {
            const modal = document.getElementById('addPlotModal');
            const modalTitle = document.getElementById('addPlotTitle');
            const addPlotForm = document.getElementById('addPlotForm');
            const editPlotIdInput = document.getElementById('editPlotId');
            const blockIdInput = document.getElementById('addPlotBlockId');
            const blockNameInput = document.getElementById('addPlotBlockNameInput');
            const modalError = document.getElementById('addPlotModalError');
            const plotNumberInput = document.getElementById('plot_number');
            const rowNumberInput = document.getElementById('row_number');
            const positionXInput = document.getElementById('position_x');
            const positionYInput = document.getElementById('position_y');
            const widthInput = document.getElementById('width');
            const heightInput = document.getElementById('height');
            const miniMapShell = document.getElementById('miniMapShell');
            const miniMapStage = document.getElementById('miniMapStage');
            const openButtons = document.querySelectorAll('.btn-add-plot');
            const closeBtn = document.getElementById('closeAddPlotModal');
            const cancelBtn = document.getElementById('cancelAddPlotBtn');
            const saveBtn = document.getElementById('saveAddPlotBtn');
            const FALLBACK_CANVAS_WIDTH = 480;
            const FALLBACK_CANVAS_HEIGHT = 300;
            const FALLBACK_PLOT_WIDTH = 60;
            const FALLBACK_PLOT_HEIGHT = 40;
            const DEFAULT_MAX_PLOTS_PER_BLOCK = 15;
            const baseDataPlotUrl = "{{ url('/dashboard/data-plot') }}";

            let activeBlockData = null;
            let activeBlockButton = null;
            let activePlotElement = null;
            let modalMode = 'create';
            let stageScale = 1;
            let newPlotElement = null;
            let dragState = null;
            let panState = null;

            const toNumber = (value, fallback = 0) => {
                const parsed = parseFloat(value);
                return Number.isFinite(parsed) ? parsed : fallback;
            };

            const parsePx = (value, fallback = 0) => toNumber(String(value || '').replace('px', ''), fallback);
            const round2 = (value) => Math.round(value * 100) / 100;
            const clamp = (value, min, max) => Math.min(Math.max(value, min), max);
            const readInputNumber = (input, fallback = 0) => toNumber(input.value, fallback);
            const setInputNumber = (input, value) => {
                input.value = String(round2(value));
            };

            const escapeHtml = (value) => String(value ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');

            const setModalError = (message = '', details = []) => {
                if (!modalError) {
                    return;
                }

                const detailText = (details || [])
                    .filter(Boolean)
                    .map((line) => `<li>${escapeHtml(line)}</li>`)
                    .join('');

                if (!message && detailText.length === 0) {
                    modalError.style.display = 'none';
                    modalError.innerHTML = '';
                    return;
                }

                modalError.style.display = 'block';
                modalError.innerHTML = detailText.length > 0
                    ? `${escapeHtml(message)}<ul>${detailText}</ul>`
                    : escapeHtml(message);
            };

            const setButtonStateByTotal = (button, totalPlots, maxPlots = null) => {
                if (!button) {
                    return;
                }

                const resolvedMaxPlots = Number(maxPlots ?? button.dataset.maxPlots ?? DEFAULT_MAX_PLOTS_PER_BLOCK);
                const safeMaxPlots = Number.isFinite(resolvedMaxPlots) && resolvedMaxPlots > 0
                    ? resolvedMaxPlots
                    : DEFAULT_MAX_PLOTS_PER_BLOCK;
                const reachedLimit = Number(totalPlots) >= safeMaxPlots;
                button.dataset.totalPlots = String(totalPlots);
                button.dataset.maxPlots = String(safeMaxPlots);
                button.disabled = reachedLimit;
                button.title = reachedLimit ? `Maksimal ${safeMaxPlots} plot per blok` : '';
            };

            const updateBlockHeadMeta = (blockId, totalPlots, occupiedPlots, maxPlots = null) => {
                const blockCard = document.querySelector(`.block-card[data-block-id="${blockId}"]`);
                if (!blockCard) {
                    return;
                }

                const resolvedMaxPlots = Number(maxPlots ?? blockCard.dataset.maxPlots ?? DEFAULT_MAX_PLOTS_PER_BLOCK);
                const safeMaxPlots = Number.isFinite(resolvedMaxPlots) && resolvedMaxPlots > 0
                    ? resolvedMaxPlots
                    : DEFAULT_MAX_PLOTS_PER_BLOCK;
                blockCard.dataset.totalPlots = String(totalPlots);
                blockCard.dataset.occupiedPlots = String(occupiedPlots);
                blockCard.dataset.maxPlots = String(safeMaxPlots);

                const meta = blockCard.querySelector('[data-block-meta]');
                if (meta) {
                    meta.textContent = `Total ${totalPlots}/${safeMaxPlots} | Terisi ${occupiedPlots}`;
                }
            };

            const appendPlotToCanvas = (blockId, payload) => {
                const mapCanvas = document.querySelector(`.map-canvas[data-block-id="${blockId}"]`);
                if (!mapCanvas) {
                    return null;
                }

                const plotNode = document.createElement('div');
                plotNode.className = `plot plot-${payload.status === 'occupied' ? 'occupied' : 'empty'}`;
                plotNode.title = `Plot ${payload.plot_number} - ${String(payload.status || 'empty').toUpperCase()}`;
                plotNode.style.left = `${toNumber(payload.position_x, 0)}px`;
                plotNode.style.top = `${toNumber(payload.position_y, 0)}px`;
                plotNode.style.width = `${toNumber(payload.width, FALLBACK_PLOT_WIDTH)}px`;
                plotNode.style.height = `${toNumber(payload.height, FALLBACK_PLOT_HEIGHT)}px`;
                plotNode.textContent = payload.plot_number || '';
                plotNode.dataset.plotId = String(payload.plotid || '');
                plotNode.dataset.plotNumber = payload.plot_number || '';
                plotNode.dataset.rowNumber = payload.row_number || '';
                plotNode.dataset.positionX = String(toNumber(payload.position_x, 0));
                plotNode.dataset.positionY = String(toNumber(payload.position_y, 0));
                plotNode.dataset.width = String(toNumber(payload.width, FALLBACK_PLOT_WIDTH));
                plotNode.dataset.height = String(toNumber(payload.height, FALLBACK_PLOT_HEIGHT));
                plotNode.dataset.status = payload.status === 'occupied' ? 'occupied' : 'empty';
                mapCanvas.appendChild(plotNode);
                return plotNode;
            };

            const getBlockDataFromButton = (button) => {
                const blockCard = button.closest('.block-card');
                const mapCanvas = blockCard ? blockCard.querySelector('.map-canvas') : null;

                if (!mapCanvas) {
                    return {
                        canvasWidth: FALLBACK_CANVAS_WIDTH,
                        canvasHeight: FALLBACK_CANVAS_HEIGHT,
                        mapColor: '#d8e4df',
                        plots: [],
                    };
                }

                const canvasWidth = parsePx(mapCanvas.dataset.canvasWidth || mapCanvas.style.width, FALLBACK_CANVAS_WIDTH);
                const canvasHeight = parsePx(mapCanvas.dataset.canvasHeight || mapCanvas.style.height, FALLBACK_CANVAS_HEIGHT);
                const mapColor = mapCanvas.dataset.mapColor || mapCanvas.style.borderTopColor || '#d8e4df';
                const plots = Array.from(mapCanvas.querySelectorAll('.plot')).map((plotEl) => ({
                    plotid: toNumber(plotEl.dataset.plotId, 0),
                    status: plotEl.classList.contains('plot-occupied') ? 'occupied' : 'empty',
                    x: parsePx(plotEl.style.left, 0),
                    y: parsePx(plotEl.style.top, 0),
                    width: parsePx(plotEl.style.width, FALLBACK_PLOT_WIDTH),
                    height: parsePx(plotEl.style.height, FALLBACK_PLOT_HEIGHT),
                    label: (plotEl.textContent || '').trim(),
                    rowNumber: (plotEl.dataset.rowNumber || '').trim(),
                }));

                return {
                    canvasWidth,
                    canvasHeight,
                    mapColor,
                    plots,
                };
            };

            const clampNewPlotPositionInputs = () => {
                if (!activeBlockData) {
                    return;
                }

                const maxX = Math.max(0, activeBlockData.canvasWidth - readInputNumber(widthInput, FALLBACK_PLOT_WIDTH));
                const maxY = Math.max(0, activeBlockData.canvasHeight - readInputNumber(heightInput, FALLBACK_PLOT_HEIGHT));
                setInputNumber(positionXInput, clamp(readInputNumber(positionXInput, 0), 0, maxX));
                setInputNumber(positionYInput, clamp(readInputNumber(positionYInput, 0), 0, maxY));
            };

            const syncNewPlotElementFromInputs = () => {
                if (!newPlotElement || !activeBlockData) {
                    return;
                }

                clampNewPlotPositionInputs();

                const x = readInputNumber(positionXInput, 0);
                const y = readInputNumber(positionYInput, 0);
                const width = readInputNumber(widthInput, FALLBACK_PLOT_WIDTH);
                const height = readInputNumber(heightInput, FALLBACK_PLOT_HEIGHT);

                newPlotElement.style.left = `${x * stageScale}px`;
                newPlotElement.style.top = `${y * stageScale}px`;
                newPlotElement.style.width = `${Math.max(12, width * stageScale)}px`;
                newPlotElement.style.height = `${Math.max(12, height * stageScale)}px`;
            };

            const updatePositionFromPointer = (event) => {
                if (!dragState || !activeBlockData) {
                    return;
                }

                const rect = miniMapStage.getBoundingClientRect();
                const pointerX = (event.clientX - rect.left) / stageScale;
                const pointerY = (event.clientY - rect.top) / stageScale;
                const width = readInputNumber(widthInput, FALLBACK_PLOT_WIDTH);
                const height = readInputNumber(heightInput, FALLBACK_PLOT_HEIGHT);
                const maxX = Math.max(0, activeBlockData.canvasWidth - width);
                const maxY = Math.max(0, activeBlockData.canvasHeight - height);
                const nextX = clamp(pointerX - dragState.offsetX, 0, maxX);
                const nextY = clamp(pointerY - dragState.offsetY, 0, maxY);

                setInputNumber(positionXInput, nextX);
                setInputNumber(positionYInput, nextY);
                syncNewPlotElementFromInputs();
            };

            const bindNewPlotDragEvents = () => {
                if (!newPlotElement) {
                    return;
                }

                newPlotElement.addEventListener('pointerdown', (event) => {
                    if (event.button !== 0) {
                        return;
                    }

                    const rect = miniMapStage.getBoundingClientRect();
                    const startX = (event.clientX - rect.left) / stageScale;
                    const startY = (event.clientY - rect.top) / stageScale;
                    dragState = {
                        offsetX: startX - readInputNumber(positionXInput, 0),
                        offsetY: startY - readInputNumber(positionYInput, 0),
                    };

                    newPlotElement.classList.add('dragging');
                    newPlotElement.setPointerCapture(event.pointerId);
                });

                newPlotElement.addEventListener('pointermove', (event) => {
                    if (!dragState) {
                        return;
                    }
                    updatePositionFromPointer(event);
                });

                const stopDrag = () => {
                    dragState = null;
                    if (newPlotElement) {
                        newPlotElement.classList.remove('dragging');
                    }
                };

                newPlotElement.addEventListener('pointerup', stopDrag);
                newPlotElement.addEventListener('pointercancel', stopDrag);
            };

            const bindMiniMapPanEvents = () => {
                miniMapShell.addEventListener('pointerdown', (event) => {
                    if (event.button !== 0) {
                        return;
                    }

                    if (event.target.closest('.mini-plot-new')) {
                        return;
                    }

                    panState = {
                        pointerId: event.pointerId,
                        startX: event.clientX,
                        startY: event.clientY,
                        scrollLeft: miniMapShell.scrollLeft,
                        scrollTop: miniMapShell.scrollTop,
                    };

                    miniMapShell.classList.add('panning');
                    miniMapShell.setPointerCapture(event.pointerId);
                });

                miniMapShell.addEventListener('pointermove', (event) => {
                    if (!panState || dragState) {
                        return;
                    }

                    const deltaX = event.clientX - panState.startX;
                    const deltaY = event.clientY - panState.startY;

                    miniMapShell.scrollLeft = panState.scrollLeft - deltaX;
                    miniMapShell.scrollTop = panState.scrollTop - deltaY;
                });

                const stopPan = (event) => {
                    if (!panState || event.pointerId !== panState.pointerId) {
                        return;
                    }

                    if (miniMapShell.hasPointerCapture(event.pointerId)) {
                        miniMapShell.releasePointerCapture(event.pointerId);
                    }

                    panState = null;
                    miniMapShell.classList.remove('panning');
                };

                miniMapShell.addEventListener('pointerup', stopPan);
                miniMapShell.addEventListener('pointercancel', stopPan);
            };

            const renderMiniMap = () => {
                if (!activeBlockData) {
                    return;
                }

                const shellWidth = Math.max(320, miniMapShell.clientWidth - 24);
                const shellHeight = Math.max(320, miniMapShell.clientHeight - 24);
                const scaleByWidth = shellWidth / activeBlockData.canvasWidth;
                const scaleByHeight = shellHeight / activeBlockData.canvasHeight;
                stageScale = Math.min(scaleByWidth, scaleByHeight, 1);
                if (!Number.isFinite(stageScale) || stageScale <= 0) {
                    stageScale = 1;
                }

                const stageWidth = activeBlockData.canvasWidth * stageScale;
                const stageHeight = activeBlockData.canvasHeight * stageScale;

                miniMapStage.innerHTML = '';
                miniMapStage.style.width = `${stageWidth}px`;
                miniMapStage.style.height = `${stageHeight}px`;
                miniMapStage.style.borderTop = `3px solid ${activeBlockData.mapColor}`;

                activeBlockData.plots.forEach((plot) => {
                    const plotNode = document.createElement('div');
                    plotNode.className = `mini-plot ${plot.status === 'occupied' ? 'mini-plot-occupied' : 'mini-plot-empty'}`;
                    plotNode.style.left = `${plot.x * stageScale}px`;
                    plotNode.style.top = `${plot.y * stageScale}px`;
                    plotNode.style.width = `${Math.max(10, plot.width * stageScale)}px`;
                    plotNode.style.height = `${Math.max(10, plot.height * stageScale)}px`;
                    plotNode.textContent = plot.label || '';
                    miniMapStage.appendChild(plotNode);
                });

                newPlotElement = document.createElement('div');
                newPlotElement.className = 'mini-plot mini-plot-new';
                newPlotElement.textContent = modalMode === 'edit' ? 'EDIT' : 'BARU';
                miniMapStage.appendChild(newPlotElement);

                syncNewPlotElementFromInputs();
                bindNewPlotDragEvents();
            };

            bindMiniMapPanEvents();

            const applyDefaultPlotValues = (blockData) => {
                const sizeSource = blockData.plots[0] || null;
                const defaultWidth = sizeSource ? sizeSource.width : FALLBACK_PLOT_WIDTH;
                const defaultHeight = sizeSource ? sizeSource.height : FALLBACK_PLOT_HEIGHT;
                const maxX = Math.max(0, blockData.canvasWidth - defaultWidth);
                const maxY = Math.max(0, blockData.canvasHeight - defaultHeight);

                setInputNumber(widthInput, defaultWidth);
                setInputNumber(heightInput, defaultHeight);
                setInputNumber(positionXInput, clamp(24, 0, maxX));
                setInputNumber(positionYInput, clamp(24, 0, maxY));
            };

            const applyEditPlotValues = (plotData, blockData) => {
                const width = toNumber(plotData.width, FALLBACK_PLOT_WIDTH);
                const height = toNumber(plotData.height, FALLBACK_PLOT_HEIGHT);
                const maxX = Math.max(0, blockData.canvasWidth - width);
                const maxY = Math.max(0, blockData.canvasHeight - height);

                plotNumberInput.value = plotData.plotNumber || '';
                rowNumberInput.value = plotData.rowNumber || '';
                setInputNumber(widthInput, width);
                setInputNumber(heightInput, height);
                setInputNumber(positionXInput, clamp(toNumber(plotData.positionX, 0), 0, maxX));
                setInputNumber(positionYInput, clamp(toNumber(plotData.positionY, 0), 0, maxY));
            };

            const setModalMode = (mode, blockName) => {
                modalMode = mode;
                if (mode === 'edit') {
                    modalTitle.textContent = `Edit Plot - ${blockName}`;
                    saveBtn.textContent = 'Simpan Perubahan';
                } else {
                    modalTitle.textContent = `Tambah Plot - ${blockName}`;
                    saveBtn.textContent = 'Simpan Plot';
                }
            };

            const openModal = (blockId, blockName, blockData, sourceButton = null, preserveInputValues = false, mode = 'create', plotData = null, plotElement = null) => {
                blockIdInput.value = blockId;
                blockNameInput.value = blockName;
                activeBlockData = blockData;
                activeBlockButton = sourceButton;
                activePlotElement = plotElement;
                editPlotIdInput.value = plotData?.plotId ? String(plotData.plotId) : '';
                setModalMode(mode, blockName);
                setModalError();

                if (mode === 'edit' && plotData) {
                    applyEditPlotValues(plotData, blockData);
                } else if (!preserveInputValues) {
                    applyDefaultPlotValues(blockData);
                } else {
                    clampNewPlotPositionInputs();
                }

                modal.classList.add('show');
                modal.setAttribute('aria-hidden', 'false');
                renderMiniMap();
            };

            const closeModal = () => {
                modal.classList.remove('show');
                modal.setAttribute('aria-hidden', 'true');
                setModalError();
                setModalMode('create', blockNameInput.value || 'Blok');
                activeBlockButton = null;
                activePlotElement = null;
                editPlotIdInput.value = '';
            };

            const bindPlotClick = (plotElement) => {
                plotElement.style.cursor = 'pointer';
                plotElement.addEventListener('click', () => {
                    const blockCard = plotElement.closest('.block-card');
                    const sourceButton = blockCard ? blockCard.querySelector('.btn-add-plot') : null;
                    const blockId = sourceButton?.dataset.blockId || blockCard?.dataset.blockId || '';
                    const blockName = sourceButton?.dataset.blockName || blockCard?.querySelector('.block-head h3')?.textContent?.trim() || 'Blok';
                    const blockDataRaw = sourceButton
                        ? getBlockDataFromButton(sourceButton)
                        : {
                            canvasWidth: FALLBACK_CANVAS_WIDTH,
                            canvasHeight: FALLBACK_CANVAS_HEIGHT,
                            mapColor: '#d8e4df',
                            plots: [],
                        };
                    const targetPlotId = toNumber(plotElement.dataset.plotId, 0);
                    const blockData = {
                        ...blockDataRaw,
                        plots: blockDataRaw.plots.filter((plot) => toNumber(plot.plotid, 0) !== targetPlotId),
                    };

                    openModal(
                        blockId,
                        blockName,
                        blockData,
                        sourceButton,
                        false,
                        'edit',
                        {
                            plotId: targetPlotId,
                            plotNumber: plotElement.dataset.plotNumber || (plotElement.textContent || '').trim(),
                            rowNumber: plotElement.dataset.rowNumber || '',
                            positionX: plotElement.dataset.positionX || parsePx(plotElement.style.left, 0),
                            positionY: plotElement.dataset.positionY || parsePx(plotElement.style.top, 0),
                            width: plotElement.dataset.width || parsePx(plotElement.style.width, FALLBACK_PLOT_WIDTH),
                            height: plotElement.dataset.height || parsePx(plotElement.style.height, FALLBACK_PLOT_HEIGHT),
                        },
                        plotElement,
                    );
                });
            };

            document.querySelectorAll('.map-canvas .plot').forEach((plotElement) => {
                bindPlotClick(plotElement);
            });

            openButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    if (button.disabled) {
                        return;
                    }

                    const blockId = button.dataset.blockId || '';
                    const blockName = button.dataset.blockName || 'Blok';
                    const blockData = getBlockDataFromButton(button);
                    openModal(blockId, blockName, blockData, button, false, 'create');
                });
            });

            closeBtn.addEventListener('click', closeModal);
            cancelBtn.addEventListener('click', closeModal);

            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && modal.classList.contains('show')) {
                    closeModal();
                }
            });

            positionXInput.addEventListener('input', syncNewPlotElementFromInputs);
            positionYInput.addEventListener('input', syncNewPlotElementFromInputs);
            widthInput.addEventListener('input', syncNewPlotElementFromInputs);
            heightInput.addEventListener('input', syncNewPlotElementFromInputs);

            const updatePlotElement = (plotNode, payload) => {
                if (!plotNode) {
                    return;
                }

                const statusClass = payload.status === 'occupied' ? 'occupied' : 'empty';
                plotNode.className = `plot plot-${statusClass}`;
                plotNode.title = `Plot ${payload.plot_number} - ${String(payload.status || 'empty').toUpperCase()}`;
                plotNode.style.left = `${toNumber(payload.position_x, 0)}px`;
                plotNode.style.top = `${toNumber(payload.position_y, 0)}px`;
                plotNode.style.width = `${toNumber(payload.width, FALLBACK_PLOT_WIDTH)}px`;
                plotNode.style.height = `${toNumber(payload.height, FALLBACK_PLOT_HEIGHT)}px`;
                plotNode.textContent = payload.plot_number || '';
                plotNode.dataset.plotId = String(payload.plotid || '');
                plotNode.dataset.plotNumber = payload.plot_number || '';
                plotNode.dataset.rowNumber = payload.row_number || '';
                plotNode.dataset.positionX = String(toNumber(payload.position_x, 0));
                plotNode.dataset.positionY = String(toNumber(payload.position_y, 0));
                plotNode.dataset.width = String(toNumber(payload.width, FALLBACK_PLOT_WIDTH));
                plotNode.dataset.height = String(toNumber(payload.height, FALLBACK_PLOT_HEIGHT));
                plotNode.dataset.status = statusClass;
            };

            addPlotForm.addEventListener('submit', async (event) => {
                event.preventDefault();
                setModalError();

                const formData = new FormData(addPlotForm);
                const editPlotId = (editPlotIdInput.value || '').trim();
                const isEditMode = modalMode === 'edit' && editPlotId.length > 0;
                if (isEditMode) {
                    formData.append('_method', 'PUT');
                }
                const token = formData.get('_token');
                const submitLabel = saveBtn.textContent;
                saveBtn.disabled = true;
                saveBtn.textContent = 'Menyimpan...';

                try {
                    const submitUrl = isEditMode ? `${baseDataPlotUrl}/${editPlotId}` : addPlotForm.action;
                    const response = await fetch(submitUrl, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': token,
                        },
                        body: formData,
                    });

                    const payload = await response.json().catch(() => ({}));

                    if (response.status === 401) {
                        window.location.href = '/login';
                        return;
                    }

                    if (!response.ok) {
                        const message = payload.message || (isEditMode ? 'Gagal memperbarui plot.' : 'Gagal menambahkan plot.');
                        const detailMessages = Object.values(payload.errors || {})
                            .flat()
                            .map((item) => String(item));
                        setModalError(message, detailMessages);

                        if (response.status === 422 && payload.data && payload.data.is_limit_reached) {
                            const limitedBlockId = String(payload.data.block_id || formData.get('block_id') || '');
                            const limitedMaxPlots = Number(payload.data.max_plots || DEFAULT_MAX_PLOTS_PER_BLOCK);
                            const limitedTotal = Number(payload.data.total_plots || limitedMaxPlots);
                            const buttonForBlock = activeBlockButton
                                || Array.from(openButtons).find((button) => (button.dataset.blockId || '') === limitedBlockId)
                                || null;
                            setButtonStateByTotal(buttonForBlock, limitedTotal, limitedMaxPlots);
                            updateBlockHeadMeta(
                                limitedBlockId,
                                limitedTotal,
                                Number((buttonForBlock?.closest('.block-card')?.dataset.occupiedPlots) || 0),
                                limitedMaxPlots,
                            );
                        }

                        return;
                    }

                    const plotData = payload.data || {};
                    const blockId = String(plotData.block_id || formData.get('block_id') || '');
                    if (isEditMode) {
                        updatePlotElement(activePlotElement, plotData);
                    } else {
                        const createdPlotNode = appendPlotToCanvas(blockId, plotData);
                        if (createdPlotNode) {
                            bindPlotClick(createdPlotNode);
                        }
                    }

                    updateBlockHeadMeta(
                        blockId,
                        Number(plotData.total_plots || 0),
                        Number(plotData.occupied_plots || 0),
                        Number(plotData.max_plots || DEFAULT_MAX_PLOTS_PER_BLOCK),
                    );

                    const buttonForBlock = activeBlockButton
                        || Array.from(openButtons).find((button) => (button.dataset.blockId || '') === blockId)
                        || null;
                    setButtonStateByTotal(
                        buttonForBlock,
                        Number(plotData.total_plots || 0),
                        Number(plotData.max_plots || DEFAULT_MAX_PLOTS_PER_BLOCK),
                    );

                    if (activeBlockData && !isEditMode) {
                        activeBlockData.plots.push({
                            plotid: toNumber(plotData.plotid, 0),
                            status: plotData.status === 'occupied' ? 'occupied' : 'empty',
                            x: toNumber(plotData.position_x, 0),
                            y: toNumber(plotData.position_y, 0),
                            width: toNumber(plotData.width, FALLBACK_PLOT_WIDTH),
                            height: toNumber(plotData.height, FALLBACK_PLOT_HEIGHT),
                            label: plotData.plot_number || '',
                            rowNumber: plotData.row_number || '',
                        });
                    }

                    closeModal();
                } catch (error) {
                    setModalError('Terjadi kesalahan jaringan. Coba lagi.');
                } finally {
                    saveBtn.disabled = false;
                    saveBtn.textContent = submitLabel;
                }
            });

            window.addEventListener('resize', () => {
                if (modal.classList.contains('show')) {
                    renderMiniMap();
                }
            });

            @if ($errors->any() && old('block_id'))
                {
                    const oldBlockId = @json((string) old('block_id'));
                    const fallbackName = @json(old('block_name', 'Blok #' . old('block_id')));
                    const matchedButton = Array.from(openButtons).find((button) => (button.dataset.blockId || '') === oldBlockId);

                    if (matchedButton) {
                        const blockData = getBlockDataFromButton(matchedButton);
                        const blockName = matchedButton.dataset.blockName || fallbackName;
                        openModal(oldBlockId, blockName, blockData, matchedButton, true, 'create');
                    } else {
                        openModal(oldBlockId, fallbackName, {
                            canvasWidth: FALLBACK_CANVAS_WIDTH,
                            canvasHeight: FALLBACK_CANVAS_HEIGHT,
                            mapColor: '#d8e4df',
                            plots: [],
                        }, null, true, 'create');
                    }
                }
            @endif
        })();
    </script>
</body>
</html>
