<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Data Blok</title>
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
            --danger: #92223b;
        }

        * { box-sizing: border-box; }

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
            grid-template-columns: 300px 1fr;
            min-height: 100vh;
            gap: 14px;
            width: min(1360px, calc(100vw - 28px));
            margin: 14px auto;
        }

        .sidebar, .main-card {
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
            font-size: 1.05rem;
            letter-spacing: 0.03em;
            color: var(--sidebar-text);
        }

        .user-box {
            border: 1px solid var(--sidebar-line);
            border-radius: 14px;
            padding: 10px;
            background: rgba(255, 241, 243, 0.08);
            font-size: .82rem;
            color: var(--sidebar-muted);
        }

        .user-box strong { display: block; font-size: .92rem; margin-top: 3px; color: var(--sidebar-text); }

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
            padding: 10px;
            background: rgba(255, 241, 243, 0.05);
            color: var(--sidebar-text);
            text-decoration: none;
            font-weight: 700;
            font-size: .88rem;
            transition: transform .16s ease, background-color .16s ease, border-color .16s ease;
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
            border-radius: 10px;
            background: rgba(255, 241, 243, 0.08);
            color: var(--sidebar-text);
            font-weight: 700;
            cursor: pointer;
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
            align-items: flex-end;
            justify-content: space-between;
            gap: 10px;
        }

        .head h1 {
            margin: 0;
            font-family: "Space Grotesk", sans-serif;
            font-size: clamp(1.35rem, 2.2vw, 1.75rem);
            letter-spacing: -0.01em;
        }

        .head p {
            margin: 6px 0 0;
            color: var(--muted);
            font-size: .84rem;
        }

        .add-btn {
            min-height: 40px;
            border: 1px solid #6d0f24;
            border-radius: 10px;
            background: linear-gradient(130deg, #a52142 0%, var(--brand) 52%, #4a0615 100%);
            color: #fff;
            font-weight: 700;
            padding: 0 14px;
            cursor: pointer;
            box-shadow: 0 14px 24px rgba(84, 9, 24, 0.24);
        }

        .message {
            margin-top: 12px;
            border-radius: 10px;
            padding: 10px;
            font-size: .83rem;
            font-weight: 700;
            display: none;
        }

        .message.success {
            display: block;
            border: 1px solid rgba(122, 17, 41, 0.22);
            background: rgba(122, 17, 41, 0.09);
            color: var(--brand);
        }

        .message.error {
            display: block;
            border: 1px solid rgba(146, 34, 59, 0.26);
            background: rgba(146, 34, 59, 0.1);
            color: var(--danger);
        }

        .table-wrap {
            margin-top: 14px;
            border: 1px solid rgba(115, 38, 54, 0.18);
            border-radius: 12px;
            overflow: auto;
            background: rgba(255, 255, 255, 0.9);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 980px;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #ecdde1;
            text-align: left;
            font-size: .84rem;
            white-space: nowrap;
        }

        th {
            background: #f5e8eb;
            color: #5a2532;
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        tr:last-child td { border-bottom: none; }

        .badge {
            display: inline-block;
            min-width: 34px;
            text-align: center;
            border-radius: 999px;
            background: rgba(122, 17, 41, 0.1);
            color: #7a1129;
            font-weight: 700;
            padding: 4px 8px;
        }

        .color-cell {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .color-swatch {
            width: 16px;
            height: 16px;
            border-radius: 4px;
            border: 1px solid #d8c0c7;
            background: #ccc;
        }

        .action-btn {
            min-height: 32px;
            border: 1px solid rgba(122, 17, 41, 0.26);
            border-radius: 8px;
            background: rgba(122, 17, 41, 0.1);
            color: #7a1129;
            padding: 0 10px;
            font-size: .78rem;
            font-weight: 700;
            cursor: pointer;
        }

        .modal {
            position: fixed;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(26, 6, 12, .62);
            z-index: 40;
            padding: 14px;
        }

        .modal.active {
            display: flex;
        }

        .modal-card {
            width: min(720px, 96vw);
            background: #fff8f6;
            border: 1px solid rgba(115, 38, 54, 0.2);
            border-radius: 16px;
            padding: 16px;
            box-shadow: 0 24px 48px rgba(22, 4, 9, 0.38);
        }

        .modal-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 8px;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid rgba(115, 38, 54, 0.18);
        }

        .modal-title-wrap {
            display: grid;
            gap: 4px;
        }

        .modal-head h3 {
            margin: 0;
            font-family: "Space Grotesk", sans-serif;
            font-size: 1.12rem;
            color: #4f1f2a;
        }

        .modal-head p {
            margin: 0;
            font-size: .78rem;
            color: var(--muted);
            line-height: 1.4;
        }

        .close-btn {
            border: 1px solid #d8c0c7;
            border-radius: 8px;
            background: #fff;
            width: 34px;
            height: 34px;
            cursor: pointer;
            font-size: 1.1rem;
            line-height: 1;
            color: #6f3a48;
        }

        .form-grid {
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

        .section-label {
            margin: 6px 0 -2px;
            font-size: .76rem;
            color: #77525b;
            font-weight: 700;
            letter-spacing: .02em;
        }

        label {
            font-size: .78rem;
            color: var(--muted);
            font-weight: 700;
        }

        input[type="text"], input[type="number"], textarea {
            width: 100%;
            border: 1px solid rgba(110, 52, 63, 0.2);
            border-radius: 10px;
            padding: 10px 12px;
            font-size: .9rem;
            color: var(--text);
            background: #fff;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        textarea:focus,
        input[type="color"]:focus {
            outline: none;
            border-color: rgba(122, 17, 41, 0.46);
            box-shadow: 0 0 0 4px rgba(122, 17, 41, 0.1);
        }

        textarea {
            min-height: 92px;
            resize: vertical;
        }

        input[type="color"] {
            width: 100%;
            min-height: 44px;
            border: 1px solid rgba(110, 52, 63, 0.2);
            border-radius: 10px;
            background: #fff;
            cursor: pointer;
            padding: 4px;
        }

        .form-error {
            margin-top: 8px;
            border: 1px solid rgba(146, 34, 59, 0.26);
            background: rgba(146, 34, 59, 0.1);
            color: var(--danger);
            border-radius: 8px;
            padding: 8px 10px;
            font-size: .79rem;
            font-weight: 700;
            display: none;
        }

        .modal-actions {
            margin-top: 12px;
            display: flex;
            justify-content: space-between;
            gap: 8px;
            border-top: 1px solid rgba(115, 38, 54, 0.18);
            padding-top: 12px;
        }

        .btn-danger {
            min-height: 40px;
            border: 1px solid rgba(146, 34, 59, 0.28);
            border-radius: 10px;
            background: rgba(146, 34, 59, 0.12);
            color: #92223b;
            padding: 0 14px;
            font-weight: 700;
            cursor: pointer;
            display: none;
        }

        .btn-group {
            display: flex;
            gap: 8px;
            margin-left: auto;
        }

        .btn-secondary {
            min-height: 40px;
            border: 1px solid #d8c0c7;
            border-radius: 10px;
            background: #fff;
            color: #6f3a48;
            padding: 0 16px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-primary {
            min-height: 40px;
            border: 1px solid #6d0f24;
            border-radius: 10px;
            background: linear-gradient(130deg, #a52142 0%, var(--brand) 52%, #4a0615 100%);
            color: #fff;
            padding: 0 18px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-danger:disabled,
        .btn-secondary:disabled,
        .btn-primary:disabled {
            opacity: .6;
            cursor: not-allowed;
        }

        @media (max-width: 1080px) {
            .layout { grid-template-columns: 1fr; }
            .sidebar { position: static; max-height: none; }
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

            .form-grid { grid-template-columns: 1fr; }
            .modal-actions { flex-direction: column; }
            .btn-group { width: 100%; margin-left: 0; }
            .btn-group .btn-secondary,
            .btn-group .btn-primary,
            .btn-danger {
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
                <a href="{{ route('dashboard.data-blok') }}" class="sidebar-menu-item active">Data Blok</a>
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
                    <h1>Data Blok</h1>
                    <p id="lokasi-blok">Kelola tambah, edit, hapus, dan posisi blok pada denah.</p>
                </div>
                <button type="button" class="add-btn" id="openModalBtn">+ Tambah Blok</button>
            </div>

            <div id="messageBox" class="message"></div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Blok</th>
                            <th>Warna</th>
                            <th>Maks Plot</th>
                            <th>Terisi</th>
                            <th>Kosong</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="blockTableBody">
                        @forelse ($blocks as $index => $block)
                            <tr id="row-block-{{ $block->blockid }}">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $block->block_name }}</td>
                                <td>
                                    <div class="color-cell">
                                        <i class="color-swatch" style="background: {{ $block->map_color ?: '#D8E4DF' }};"></i>
                                        <span>{{ $block->map_color ?: '-' }}</span>
                                    </div>
                                </td>
                                <td><span class="badge">{{ (int) ($block->max_plots ?? 15) }}</span></td>
                                <td>{{ (int) $block->occupied_plots }}</td>
                                <td>{{ (int) $block->empty_plots }}</td>
                                <td>{{ $block->description ?: '-' }}</td>
                                <td>
                                    <button
                                        type="button"
                                        class="action-btn edit-btn"
                                        data-block-id="{{ $block->blockid }}"
                                        data-block-name="{{ e($block->block_name) }}"
                                        data-block-color="{{ $block->map_color ?: '#D8E4DF' }}"
                                        data-block-description="{{ e($block->description ?? '') }}"
                                        data-block-max-plots="{{ (int) ($block->max_plots ?? 15) }}"
                                        data-block-map-x="{{ isset($block->map_x) ? (int) $block->map_x : '' }}"
                                        data-block-map-y="{{ isset($block->map_y) ? (int) $block->map_y : '' }}"
                                    >
                                        Edit
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr id="emptyRow">
                                <td colspan="8">Belum ada data blok.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <div class="modal" id="blockModal" aria-hidden="true">
        <div class="modal-card">
            <div class="modal-head">
                <div class="modal-title-wrap">
                    <h3 id="modalTitle">Tambah Blok</h3>
                    <p>Lengkapi data blok dan atur posisi tampil di denah.</p>
                </div>
                <button type="button" class="close-btn" id="closeModalBtn">x</button>
            </div>

            <form id="blockForm">
                <input type="hidden" id="blockIdField">
                <div class="form-grid">
                    <div class="section-label full">Informasi Blok</div>

                    <div class="field full">
                        <label for="blockNameField">Nama Blok</label>
                        <input id="blockNameField" type="text" placeholder="Contoh: Blok A" required>
                    </div>

                    <div class="field">
                        <label for="blockColorField">Warna Blok</label>
                        <input id="blockColorField" type="color" value="#1F7A67" required>
                    </div>

                    <div class="field">
                        <label for="blockMaxPlotsField">Maksimal Plot</label>
                        <input id="blockMaxPlotsField" type="number" min="1" max="500" value="15" required>
                    </div>

                    <div class="field full">
                        <label for="blockDescriptionField">Deskripsi</label>
                        <textarea id="blockDescriptionField" placeholder="Keterangan singkat blok"></textarea>
                    </div>
                </div>

                <div class="form-error" id="formErrorBox"></div>

                <div class="modal-actions">
                    <button type="button" class="btn-danger" id="deleteBlockBtn">Hapus</button>
                    <div class="btn-group">
                        <button type="button" class="btn-secondary" id="cancelModalBtn">Batal</button>
                        <button type="submit" class="btn-primary" id="saveModalBtn">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        (() => {
            const tableBody = document.getElementById('blockTableBody');
            const messageBox = document.getElementById('messageBox');
            const modal = document.getElementById('blockModal');
            const modalTitle = document.getElementById('modalTitle');
            const openModalBtn = document.getElementById('openModalBtn');
            const closeModalBtn = document.getElementById('closeModalBtn');
            const cancelModalBtn = document.getElementById('cancelModalBtn');
            const blockForm = document.getElementById('blockForm');
            const blockIdField = document.getElementById('blockIdField');
            const blockNameField = document.getElementById('blockNameField');
            const blockColorField = document.getElementById('blockColorField');
            const blockMaxPlotsField = document.getElementById('blockMaxPlotsField');
            const blockDescriptionField = document.getElementById('blockDescriptionField');
            const saveModalBtn = document.getElementById('saveModalBtn');
            const deleteBlockBtn = document.getElementById('deleteBlockBtn');
            const formErrorBox = document.getElementById('formErrorBox');
            const csrfToken = '{{ csrf_token() }}';
            const baseUrl = "{{ url('/dashboard/data-blok') }}";
            let mode = 'create';

            function showMessage(type, text) {
                messageBox.className = `message ${type}`;
                messageBox.textContent = text;
            }

            function clearFormError() {
                formErrorBox.style.display = 'none';
                formErrorBox.textContent = '';
            }

            function showFormError(text) {
                formErrorBox.style.display = 'block';
                formErrorBox.textContent = text;
            }

            function openModalCreate() {
                mode = 'create';
                modalTitle.textContent = 'Tambah Blok';
                saveModalBtn.textContent = 'Simpan';
                deleteBlockBtn.style.display = 'none';
                blockIdField.value = '';
                blockNameField.value = '';
                blockColorField.value = '#1F7A67';
                blockMaxPlotsField.value = '15';
                blockDescriptionField.value = '';
                clearFormError();
                modal.classList.add('active');
                modal.setAttribute('aria-hidden', 'false');
            }

            function openModalEdit(data) {
                mode = 'edit';
                modalTitle.textContent = 'Edit Blok';
                saveModalBtn.textContent = 'Simpan Perubahan';
                deleteBlockBtn.style.display = 'inline-block';
                blockIdField.value = data.id;
                blockNameField.value = data.name;
                blockColorField.value = data.color || '#1F7A67';
                blockMaxPlotsField.value = data.maxPlots || 15;
                blockDescriptionField.value = data.description || '';
                clearFormError();
                modal.classList.add('active');
                modal.setAttribute('aria-hidden', 'false');
            }

            function closeModal() {
                modal.classList.remove('active');
                modal.setAttribute('aria-hidden', 'true');
            }

            function renderRow(block, index) {
                const description = block.description ? block.description : '-';
                return `
                    <tr id="row-block-${block.blockid}">
                        <td>${index}</td>
                        <td>${block.block_name}</td>
                        <td>
                            <div class="color-cell">
                                <i class="color-swatch" style="background: ${block.map_color || '#D8E4DF'};"></i>
                                <span>${block.map_color || '-'}</span>
                            </div>
                        </td>
                        <td><span class="badge">${block.max_plots || 15}</span></td>
                        <td>${block.occupied_plots}</td>
                        <td>${block.empty_plots}</td>
                        <td>${description}</td>
                        <td>
                            <button
                                type="button"
                                class="action-btn edit-btn"
                                data-block-id="${block.blockid}"
                                data-block-name="${block.block_name.replace(/\"/g, '&quot;')}"
                                data-block-color="${block.map_color || '#D8E4DF'}"
                                data-block-description="${(block.description || '').replace(/\"/g, '&quot;')}"
                                data-block-max-plots="${block.max_plots || 15}"
                                data-block-map-x="${Number.isFinite(block.map_x) ? block.map_x : ''}"
                                data-block-map-y="${Number.isFinite(block.map_y) ? block.map_y : ''}"
                            >
                                Edit
                            </button>
                        </td>
                    </tr>
                `;
            }

            function refreshRowNumbers() {
                const rows = tableBody.querySelectorAll('tr[id^=\"row-block-\"]');
                rows.forEach((row, idx) => {
                    const firstCell = row.querySelector('td');
                    if (firstCell) {
                        firstCell.textContent = String(idx + 1);
                    }
                });
            }

            async function ajaxRequest(url, method, payload = null) {
                const response = await fetch(url, {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: payload ? JSON.stringify(payload) : null,
                });

                const data = await response.json().catch(() => ({}));
                if (!response.ok) {
                    const errorText = data?.message || 'Terjadi kesalahan.';
                    const validationError = data?.errors ? Object.values(data.errors).flat()[0] : null;
                    throw new Error(validationError || errorText);
                }
                return data;
            }

            openModalBtn.addEventListener('click', openModalCreate);
            closeModalBtn.addEventListener('click', closeModal);
            cancelModalBtn.addEventListener('click', closeModal);

            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal();
                }
            });

            window.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeModal();
                }
            });

            tableBody.addEventListener('click', (event) => {
                const btn = event.target.closest('.edit-btn');
                if (!btn) {
                    return;
                }

                openModalEdit({
                    id: btn.dataset.blockId,
                    name: btn.dataset.blockName,
                    color: btn.dataset.blockColor,
                    description: btn.dataset.blockDescription,
                    maxPlots: btn.dataset.blockMaxPlots,
                });
            });

            blockForm.addEventListener('submit', async (event) => {
                event.preventDefault();
                clearFormError();

                const payload = {
                    block_name: blockNameField.value.trim(),
                    map_color: blockColorField.value,
                    max_plots: parseInt(blockMaxPlotsField.value, 10),
                    description: blockDescriptionField.value.trim(),
                };

                if (!payload.block_name) {
                    showFormError('Nama blok wajib diisi.');
                    return;
                }
                if (!Number.isFinite(payload.max_plots) || payload.max_plots < 1) {
                    showFormError('Maksimal plot harus angka minimal 1.');
                    return;
                }

                saveModalBtn.disabled = true;
                try {
                    if (mode === 'create') {
                        const result = await ajaxRequest(baseUrl, 'POST', payload);
                        const emptyRow = document.getElementById('emptyRow');
                        if (emptyRow) {
                            emptyRow.remove();
                        }
                        tableBody.insertAdjacentHTML('beforeend', renderRow(result.data, tableBody.querySelectorAll('tr[id^=\"row-block-\"]').length + 1));
                        refreshRowNumbers();
                        showMessage('success', result.message || 'Data blok berhasil ditambahkan.');
                    } else {
                        const blockId = blockIdField.value;
                        const result = await ajaxRequest(`${baseUrl}/${blockId}`, 'PUT', payload);
                        const row = document.getElementById(`row-block-${blockId}`);
                        if (row) {
                            const rowIndex = row.querySelector('td')?.textContent || '1';
                            row.outerHTML = renderRow(result.data, rowIndex);
                        }
                        refreshRowNumbers();
                        showMessage('success', result.message || 'Data blok berhasil diperbarui.');
                    }
                    closeModal();
                } catch (error) {
                    showFormError(error.message);
                } finally {
                    saveModalBtn.disabled = false;
                }
            });

            deleteBlockBtn.addEventListener('click', async () => {
                const blockId = blockIdField.value;
                if (!blockId) {
                    return;
                }

                clearFormError();
                if (!window.confirm('Yakin ingin menghapus blok ini?')) {
                    return;
                }

                deleteBlockBtn.disabled = true;
                try {
                    const result = await ajaxRequest(`${baseUrl}/${blockId}`, 'DELETE');
                    const row = document.getElementById(`row-block-${blockId}`);
                    if (row) {
                        row.remove();
                    }

                    const remainingRows = tableBody.querySelectorAll('tr[id^=\"row-block-\"]');
                    if (remainingRows.length === 0) {
                        tableBody.innerHTML = '<tr id="emptyRow"><td colspan="8">Belum ada data blok.</td></tr>';
                    } else {
                        refreshRowNumbers();
                    }

                    showMessage('success', result.message || 'Data blok berhasil dihapus.');
                    closeModal();
                } catch (error) {
                    showFormError(error.message);
                } finally {
                    deleteBlockBtn.disabled = false;
                }
            });
        })();
    </script>
</body>
</html>



