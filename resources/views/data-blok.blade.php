<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Data Blok</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Space+Grotesk:wght@600;700&display=swap');

        :root {
            --bg: #edf3f1;
            --card: #ffffff;
            --line: #d2dfda;
            --text: #163036;
            --muted: #657c81;
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

        .main-card { padding: 16px; }

        .head {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 10px;
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

        .add-btn {
            min-height: 40px;
            border: 1px solid #1f735f;
            border-radius: 10px;
            background: linear-gradient(120deg, #29947b, #1f7a67);
            color: #fff;
            font-weight: 700;
            padding: 0 14px;
            cursor: pointer;
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
            border: 1px solid #b8d4c9;
            background: #e8f3ef;
            color: #175247;
        }

        .message.error {
            display: block;
            border: 1px solid #e3b5b5;
            background: #fdeeee;
            color: #c44a4a;
        }

        .table-wrap {
            margin-top: 14px;
            border: 1px solid var(--line);
            border-radius: 12px;
            overflow: auto;
            background: #fbfdfc;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 980px;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid var(--line);
            text-align: left;
            font-size: .84rem;
            white-space: nowrap;
        }

        th {
            background: #f2f8f5;
            color: #36585f;
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
            background: #e8f3ef;
            color: #1a5a4d;
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
            border: 1px solid #c3d2cc;
            background: #ccc;
        }

        .action-btn {
            min-height: 32px;
            border: 1px solid #1f735f;
            border-radius: 8px;
            background: #eaf4f0;
            color: #0f4b3f;
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
            background: rgba(16, 38, 44, .45);
            z-index: 40;
            padding: 14px;
        }

        .modal.active {
            display: flex;
        }

        .modal-card {
            width: min(560px, 96vw);
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 14px;
            box-shadow: 0 18px 40px rgba(14, 40, 44, .22);
        }

        .modal-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
        }

        .modal-head h3 {
            margin: 0;
            font-family: "Space Grotesk", sans-serif;
            font-size: 1.06rem;
        }

        .close-btn {
            border: 1px solid #c4d3cd;
            border-radius: 8px;
            background: #fff;
            width: 32px;
            height: 32px;
            cursor: pointer;
            font-size: 1rem;
            line-height: 1;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 170px 150px;
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

        input[type="text"], input[type="number"], textarea {
            width: 100%;
            border: 1px solid #c7d7d1;
            border-radius: 10px;
            padding: 10px 12px;
            font-size: .9rem;
            color: var(--text);
            background: #fff;
        }

        textarea {
            min-height: 92px;
            resize: vertical;
        }

        input[type="color"] {
            width: 100%;
            min-height: 44px;
            border: 1px solid #c7d7d1;
            border-radius: 10px;
            background: #fff;
            cursor: pointer;
            padding: 4px;
        }

        .form-error {
            margin-top: 8px;
            border: 1px solid #e3b5b5;
            background: #fdeeee;
            color: #c44a4a;
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
        }

        .btn-danger {
            min-height: 40px;
            border: 1px solid #b64d4d;
            border-radius: 10px;
            background: #fff1f1;
            color: #9b2525;
            padding: 0 12px;
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
            border: 1px solid #c4d3cd;
            border-radius: 10px;
            background: #fff;
            color: #35555c;
            padding: 0 12px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-primary {
            min-height: 40px;
            border: 1px solid #1f735f;
            border-radius: 10px;
            background: linear-gradient(120deg, #29947b, #1f7a67);
            color: #fff;
            padding: 0 14px;
            font-weight: 700;
            cursor: pointer;
        }

        @media (max-width: 1080px) {
            .layout { grid-template-columns: 1fr; }
            .sidebar { position: static; max-height: none; }
        }

        @media (max-width: 700px) {
            .form-grid { grid-template-columns: 1fr; }
            .modal-actions { flex-direction: column; }
            .btn-group { width: 100%; margin-left: 0; }
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
                <a href="{{ route('dashboard.data-blok') }}" class="sidebar-menu-item active">Data Blok</a>
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
                    <h1>Data Blok</h1>
                    <p>Kelola tambah, edit, dan hapus data blok.</p>
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
                            <th>Total Petak</th>
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
                                <td><span class="badge">{{ (int) $block->total_plots }}</span></td>
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
                                    >
                                        Edit
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr id="emptyRow">
                                <td colspan="9">Belum ada data blok.</td>
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
                <h3 id="modalTitle">Tambah Blok</h3>
                <button type="button" class="close-btn" id="closeModalBtn">x</button>
            </div>

            <form id="blockForm">
                <input type="hidden" id="blockIdField">
                <div class="form-grid">
                    <div class="field">
                        <label for="blockNameField">Nama Blok</label>
                        <input id="blockNameField" type="text" required>
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
            const csrfToken = document.querySelector('meta[name=\"csrf-token\"]')?.getAttribute('content') || '';
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
                        <td><span class="badge">${block.total_plots}</span></td>
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
                        tableBody.innerHTML = '<tr id="emptyRow"><td colspan="9">Belum ada data blok.</td></tr>';
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
