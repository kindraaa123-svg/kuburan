<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kontak Keluarga</title>
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
            --occupied: #a83c52;
            --empty: #7a1129;
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
            grid-template-columns: 300px minmax(0, 1fr);
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

        .logo { margin: 0; font-family: "Space Grotesk", sans-serif; font-size: 1.02rem; letter-spacing: 0.03em; color: var(--sidebar-text); }
        .user-box { border: 1px solid var(--sidebar-line); border-radius: 14px; padding: 11px 12px; background: rgba(255, 241, 243, 0.08); font-size: .82rem; color: var(--sidebar-muted); }
        .user-box strong { display: block; font-size: .92rem; margin-top: 3px; color: var(--sidebar-text); }
        .sidebar-menu { display: grid; align-content: start; gap: 8px; }
        .sidebar-bottom { display: grid; gap: 8px; }

        .sidebar-menu-item {
            display: block;
            border: 1px solid var(--sidebar-line);
            border-radius: 12px;
            padding: 10px 12px;
            background: rgba(255, 241, 243, 0.05);
            color: var(--sidebar-text);
            text-decoration: none;
            font-weight: 700;
            font-size: .86rem;
            transition: transform .16s ease, background-color .16s ease, border-color .16s ease;
        }

        .sidebar-menu-item:hover {
            transform: translateX(2px);
            background: rgba(255, 241, 243, 0.12);
            border-color: rgba(255, 241, 243, 0.28);
        }

        .sidebar-menu-item.active { border-color: rgba(255, 188, 199, 0.7); background: rgba(122, 17, 41, 0.38); color: #fff7f4; }
        .logout-btn {
            width: 100%;
            min-height: 42px;
            border: 1px solid rgba(255, 241, 243, 0.24);
            border-radius: 12px;
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
        .head h1 { margin: 0; font-family: "Space Grotesk", sans-serif; font-size: clamp(1.35rem, 2.2vw, 1.75rem); letter-spacing: -0.01em; }
        .head p { margin: 8px 0 0; color: var(--muted); font-size: .87rem; }

        .legend {
            margin-top: 12px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            color: var(--muted);
            font-size: .8rem;
        }

        .dot { width: 11px; height: 11px; border-radius: 3px; display: inline-block; margin-right: 5px; }
        .cards { margin-top: 14px; display: grid; grid-template-columns: repeat(auto-fill, minmax(360px, 1fr)); gap: 12px; }
        .block-card { border: 1px solid rgba(115, 38, 54, 0.18); border-radius: 14px; background: rgba(255, 255, 255, 0.66); padding: 10px; }
        .block-head { display: flex; justify-content: space-between; gap: 10px; align-items: center; margin-bottom: 8px; }
        .block-head h3 { margin: 0; font-size: .95rem; color: #4f1f2a; }
        .mini-meta { font-size: .75rem; color: var(--muted); font-weight: 700; }

        .map-box {
            border: 1px solid rgba(115, 38, 54, 0.18);
            border-radius: 10px;
            overflow: auto;
            background: #fff7f8;
            padding: 8px;
        }

        .map-canvas {
            position: relative;
            border-radius: 8px;
            background:
                linear-gradient(90deg, rgba(122, 17, 41, .06) 1px, transparent 1px) 0 0 / 30px 30px,
                linear-gradient(rgba(122, 17, 41, .06) 1px, transparent 1px) 0 0 / 30px 30px,
                #fff;
        }

        .plot {
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            border: 1px solid rgba(84, 9, 24, .24);
            font-size: .68rem;
            font-weight: 700;
            color: #fff;
            text-decoration: none;
            cursor: pointer;
        }

        .plot-slot {
            appearance: none;
            -webkit-appearance: none;
        }

        .plot-occupied { background: var(--occupied); }
        .plot-empty { background: var(--empty); }

        .flash {
            margin-top: 10px;
            border: 1px solid rgba(122, 17, 41, 0.22);
            background: rgba(122, 17, 41, 0.09);
            color: var(--brand);
            border-radius: 12px;
            padding: 10px 12px;
            font-size: .84rem;
        }

        .flash.flash-error {
            border-color: rgba(146, 34, 59, 0.26);
            background: rgba(146, 34, 59, 0.1);
            color: var(--danger);
        }

        .flash ul {
            margin: 6px 0 0 16px;
            padding: 0;
        }

        .empty-state { margin-top: 12px; border: 1px dashed rgba(115, 38, 54, 0.22); border-radius: 10px; padding: 12px; color: var(--muted); background: #fdf6f7; font-size: .85rem; }

        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(26, 6, 12, .62);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            z-index: 1400;
        }

        .modal-backdrop.show { display: flex; }

        .modal-card {
            width: min(920px, 100%);
            max-height: calc(100vh - 40px);
            overflow: auto;
            background: #fff8f6;
            border: 1px solid rgba(115, 38, 54, 0.2);
            border-radius: 14px;
            box-shadow: 0 24px 48px rgba(22, 4, 9, 0.38);
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
            color: #4f1f2a;
        }

        .modal-close {
            border: 1px solid #d8c0c7;
            border-radius: 8px;
            background: #fff;
            color: #6f3a48;
            width: 34px;
            height: 34px;
            cursor: pointer;
        }

        .plot-info {
            border: 1px solid rgba(115, 38, 54, 0.18);
            background: #fdf6f7;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 10px;
            font-size: .83rem;
            color: #6a4851;
        }

        .section-title {
            margin: 0 0 8px;
            font-size: .9rem;
            font-weight: 700;
            color: #4f1f2a;
        }

        .family-list {
            display: grid;
            gap: 8px;
        }

        .family-item {
            border: 1px solid rgba(115, 38, 54, 0.18);
            border-radius: 10px;
            padding: 9px;
            background: #fff;
        }

        .family-head {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            align-items: center;
            margin-bottom: 6px;
        }

        .family-head strong {
            font-size: .84rem;
        }

        .family-head-actions {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .pill {
            font-size: .69rem;
            border: 1px solid rgba(122, 17, 41, 0.24);
            background: rgba(122, 17, 41, 0.08);
            color: #7a1129;
            border-radius: 999px;
            padding: 3px 8px;
            font-weight: 700;
        }

        .family-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 6px 10px;
            font-size: .77rem;
        }

        .family-grid small {
            display: block;
            color: #8a6770;
            font-weight: 700;
        }

        .family-grid span, .family-grid a {
            color: #4f1f2a;
            word-break: break-word;
        }

        .modal-actions {
            margin-top: 10px;
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }

        .btn-secondary, .btn-primary {
            border-radius: 10px;
            padding: 9px 12px;
            font-size: .82rem;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-secondary {
            border: 1px solid #d8c0c7;
            background: #fff;
            color: #6f3a48;
        }

        .btn-primary {
            border: 1px solid #6d0f24;
            background: linear-gradient(130deg, #a52142 0%, var(--brand) 52%, #4a0615 100%);
            color: #fff;
        }

        .btn-danger {
            border: 1px solid rgba(146, 34, 59, 0.28);
            background: rgba(146, 34, 59, 0.12);
            color: #92223b;
            border-radius: 8px;
            padding: 5px 9px;
            font-size: .74rem;
            font-weight: 700;
            cursor: pointer;
        }

        .form-wrap {
            margin-top: 10px;
            border: 1px solid rgba(115, 38, 54, 0.18);
            border-radius: 10px;
            padding: 10px;
            background: #fdf6f7;
            display: none;
        }

        .form-wrap.show { display: block; }

        .field-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px 10px;
        }

        .field-full { grid-column: 1 / -1; }
        .field label {
            display: block;
            font-size: .76rem;
            color: var(--muted);
            margin-bottom: 4px;
            font-weight: 700;
        }

        .field input,
        .field textarea {
            width: 100%;
            border: 1px solid rgba(110, 52, 63, 0.2);
            border-radius: 8px;
            padding: 9px 10px;
            font-size: .85rem;
            color: var(--text);
            background: #fff;
            font-family: inherit;
        }

        .field input:focus,
        .field textarea:focus {
            outline: none;
            border-color: rgba(122, 17, 41, 0.46);
            box-shadow: 0 0 0 4px rgba(122, 17, 41, 0.1);
        }

        .field textarea { min-height: 86px; resize: vertical; }

        @media (max-width: 1120px) {
            .layout { grid-template-columns: 1fr; }
            .sidebar { position: static; max-height: none; }
        }

        @media (max-width: 760px) {
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

            .cards { grid-template-columns: 1fr; }
            .field-grid, .family-grid { grid-template-columns: 1fr; }
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
                <a href="{{ route('dashboard.data-kontak-keluarga') }}" class="sidebar-menu-item active">Data Kontak Keluarga</a>
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
                <a href="{{ route('dashboard.account') }}" class="sidebar-menu-item">Akun</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </aside>

        <section class="main-card">
            <div class="head">
                <h1>Data Kontak Keluarga</h1>
                <p>Klik plot untuk melihat data keluarga. Jika belum ada, tambahkan dari tombol <strong>+</strong> di modal.</p>
            </div>

            @if (session('status'))
                <div class="flash">{{ session('status') }}</div>
            @endif

            @if ($errors->familyContactForm->any())
                <div class="flash flash-error">
                    Gagal menyimpan kontak keluarga:
                    <ul>
                        @foreach ($errors->familyContactForm->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="legend">
                <span><i class="dot" style="background: var(--occupied);"></i>Terisi (bisa kontak keluarga)</span>
                <span><i class="dot" style="background: var(--empty);"></i>Kosong (belum ada almarhum)</span>
            </div>

            @if (count($plotCards) === 0)
                <div class="empty-state">Belum ada data blok.</div>
            @else
                <div class="cards">
                    @foreach ($plotCards as $card)
                        <article class="block-card">
                            <div class="block-head">
                                <h3>{{ $card['block_name'] }}</h3>
                                <span class="mini-meta">Total {{ $card['total_plots'] }}/{{ $card['max_plots'] }} | Terisi {{ $card['occupied_plots'] }}</span>
                            </div>
                            <div class="map-box">
                                <div
                                    class="map-canvas"
                                    style="width: {{ $card['canvas_width'] }}px; height: {{ $card['canvas_height'] }}px; border-top: 3px solid {{ $card['map_color'] }};"
                                >
                                    @foreach ($card['plots'] as $plot)
                                        <button
                                            type="button"
                                            class="plot plot-slot plot-{{ $plot['status'] }}"
                                            data-plot-slot="true"
                                            data-block-name="{{ $card['block_name'] }}"
                                            data-plot-id="{{ $plot['plotid'] }}"
                                            data-plot-number="{{ $plot['number'] }}"
                                            data-deceased-id="{{ $plot['deceased_id'] }}"
                                            data-deceased-name="{{ $plot['deceased_name'] }}"
                                            data-family-contacts='{{ json_encode($plot['family_contacts'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) }}'
                                            style="
                                                left: {{ (float) $plot['x'] }}px;
                                                top: {{ (float) $plot['y'] }}px;
                                                width: {{ (float) $plot['width'] }}px;
                                                height: {{ (float) $plot['height'] }}px;
                                            "
                                            title="Plot {{ $plot['number'] }}"
                                        >
                                            {{ $plot['number'] }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    </main>

    <div class="modal-backdrop" id="familyModal" aria-hidden="true">
        <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="familyModalTitle">
            <div class="modal-head">
                <h3 id="familyModalTitle">Kontak Keluarga</h3>
                <button type="button" class="modal-close" id="closeFamilyModal">x</button>
            </div>

            <div class="plot-info" id="plotInfoText">-</div>

            <h4 class="section-title">Daftar Kontak Keluarga</h4>
            <div id="familyList" class="family-list"></div>

            <div class="modal-actions">
                <button type="button" class="btn-secondary" id="openFamilyFormBtn">+ Tambah Kontak Keluarga</button>
                <button type="button" class="btn-secondary" id="cancelFamilyModal">Tutup</button>
            </div>

            <form method="POST" action="{{ route('dashboard.data-kontak-keluarga.store') }}" id="familyForm" class="form-wrap">
                @csrf
                <input type="hidden" name="deceased_id" id="familyDeceasedId" value="">
                <div class="field-grid">
                    <div class="field">
                        <label for="family_name">Nama Keluarga</label>
                        <input id="family_name" name="family_name" type="text" required>
                    </div>
                    <div class="field">
                        <label for="relationship_status">Hubungan</label>
                        <input id="relationship_status" name="relationship_status" type="text" placeholder="Contoh: Anak, Istri, Kakak">
                    </div>
                    <div class="field">
                        <label for="phone_number">No. Telepon</label>
                        <input id="phone_number" name="phone_number" type="text">
                    </div>
                    <div class="field">
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email">
                    </div>
                    <div class="field field-full">
                        <label for="address">Alamat</label>
                        <textarea id="address" name="address"></textarea>
                    </div>
                    <div class="field field-full">
                        <label for="notes">Catatan</label>
                        <textarea id="notes" name="notes"></textarea>
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelFamilyFormBtn">Batal</button>
                    <button type="submit" class="btn-primary">Simpan Kontak</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (() => {
            const modal = document.getElementById('familyModal');
            const closeModalBtn = document.getElementById('closeFamilyModal');
            const cancelModalBtn = document.getElementById('cancelFamilyModal');
            const plotInfoText = document.getElementById('plotInfoText');
            const familyList = document.getElementById('familyList');
            const openFormBtn = document.getElementById('openFamilyFormBtn');
            const familyForm = document.getElementById('familyForm');
            const cancelFamilyFormBtn = document.getElementById('cancelFamilyFormBtn');
            const familyDeceasedId = document.getElementById('familyDeceasedId');
            const plotButtons = document.querySelectorAll('[data-plot-slot]');
            const csrfToken = '{{ csrf_token() }}';
            const deleteUrlTemplate = '{{ route('dashboard.data-kontak-keluarga.destroy', ['familyid' => '__ID__']) }}';
            const createUrl = '{{ route('dashboard.data-kontak-keluarga.store') }}';
            let currentContacts = [];
            let currentPlotButton = null;

            const openModal = () => {
                modal.classList.add('show');
                modal.setAttribute('aria-hidden', 'false');
            };

            const closeModal = () => {
                modal.classList.remove('show');
                modal.setAttribute('aria-hidden', 'true');
                familyForm.classList.remove('show');
            };

            const clearForm = () => {
                familyForm.reset();
                familyDeceasedId.value = '';
            };

            const syncCurrentPlotContacts = () => {
                if (!currentPlotButton) return;
                currentPlotButton.dataset.familyContacts = JSON.stringify(currentContacts || []);
            };

            const showAjaxError = (message) => {
                window.alert(message || 'Terjadi kesalahan. Silakan coba lagi.');
            };

            const renderFamilyList = (items) => {
                if (!familyList) return;

                if (!Array.isArray(items) || items.length === 0) {
                    familyList.innerHTML = '<div class="empty-state" style="margin-top:0;">Belum ada data kontak keluarga pada plot ini.</div>';
                    return;
                }

                familyList.innerHTML = items.map((item) => `
                    <article class="family-item">
                        <div class="family-head">
                            <strong>${item.family_name || '-'}</strong>
                            <div class="family-head-actions">
                                <span class="pill">${item.relationship_status || '-'}</span>
                                <button type="button" class="btn-danger" data-delete-family-id="${item.familyid || ''}" data-family-name="${item.family_name || '-'}">Hapus</button>
                            </div>
                        </div>
                        <div class="family-grid">
                            <div><small>Telepon</small><span>${item.phone_number || '-'}</span></div>
                            <div><small>Email</small><span>${item.email || '-'}</span></div>
                            <div><small>Alamat</small><span>${item.address || '-'}</span></div>
                            <div><small>Catatan</small><span>${item.notes || '-'}</span></div>
                        </div>
                    </article>
                `).join('');
            };

            familyList.addEventListener('click', async (event) => {
                const button = event.target.closest('[data-delete-family-id]');
                if (!button) return;

                const familyId = String(button.getAttribute('data-delete-family-id') || '').trim();
                const familyName = String(button.getAttribute('data-family-name') || '-').trim();
                if (!familyId) return;

                const ok = window.confirm(`Hapus kontak keluarga "${familyName}"?`);
                if (!ok) return;

                try {
                    button.disabled = true;
                    const response = await fetch(deleteUrlTemplate.replace('__ID__', familyId), {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                        },
                        body: '_method=DELETE&_token=' + encodeURIComponent(csrfToken),
                    });

                    const payload = await response.json().catch(() => ({}));
                    if (!response.ok) {
                        showAjaxError(payload.message || 'Gagal menghapus kontak keluarga.');
                        return;
                    }

                    currentContacts = currentContacts.filter((item) => String(item.familyid) !== familyId);
                    syncCurrentPlotContacts();
                    renderFamilyList(currentContacts);
                } catch (error) {
                    showAjaxError('Gagal menghapus kontak keluarga.');
                } finally {
                    button.disabled = false;
                }
            });

            familyForm.addEventListener('submit', async (event) => {
                event.preventDefault();

                if (!familyDeceasedId.value) {
                    showAjaxError('Pilih plot dengan data almarhum terlebih dahulu.');
                    return;
                }

                const submitBtn = familyForm.querySelector('button[type="submit"]');
                const formData = new FormData(familyForm);
                formData.set('_token', csrfToken);

                try {
                    if (submitBtn) submitBtn.disabled = true;
                    const response = await fetch(createUrl, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: formData,
                    });

                    const payload = await response.json().catch(() => ({}));
                    if (!response.ok) {
                        if (payload && payload.errors) {
                            const firstErrorKey = Object.keys(payload.errors)[0];
                            const firstMessage = firstErrorKey ? (payload.errors[firstErrorKey]?.[0] || 'Validasi gagal.') : 'Validasi gagal.';
                            showAjaxError(firstMessage);
                        } else {
                            showAjaxError(payload.message || 'Gagal menambah kontak keluarga.');
                        }
                        return;
                    }

                    const newItem = payload.data || null;
                    if (newItem) {
                        currentContacts.push(newItem);
                        syncCurrentPlotContacts();
                        renderFamilyList(currentContacts);
                    }

                    familyForm.classList.remove('show');
                    familyForm.reset();
                    familyDeceasedId.value = currentPlotButton?.dataset.deceasedId || '';
                } catch (error) {
                    showAjaxError('Gagal menambah kontak keluarga.');
                } finally {
                    if (submitBtn) submitBtn.disabled = false;
                }
            });

            plotButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    clearForm();
                    currentPlotButton = button;

                    const blockName = (button.dataset.blockName || '').trim();
                    const plotNumber = (button.dataset.plotNumber || '').trim();
                    const deceasedId = (button.dataset.deceasedId || '').trim();
                    const deceasedName = (button.dataset.deceasedName || '').trim();
                    const contactsRaw = button.dataset.familyContacts || '[]';
                    let contacts = [];

                    try {
                        contacts = JSON.parse(contactsRaw);
                    } catch (error) {
                        contacts = [];
                    }
                    currentContacts = Array.isArray(contacts) ? contacts : [];

                    if (!deceasedId) {
                        plotInfoText.innerHTML = `Blok <strong>${blockName}</strong> - Plot <strong>${plotNumber}</strong><br>Plot ini belum memiliki data almarhum, sehingga kontak keluarga belum dapat ditambahkan.`;
                        renderFamilyList([]);
                        openFormBtn.style.display = 'none';
                        openModal();
                        return;
                    }

                    plotInfoText.innerHTML = `Blok <strong>${blockName}</strong> - Plot <strong>${plotNumber}</strong><br>Almarhum: <strong>${deceasedName || '-'}</strong>`;
                    renderFamilyList(currentContacts);
                    familyDeceasedId.value = deceasedId;
                    openFormBtn.style.display = 'inline-block';
                    openModal();
                });
            });

            openFormBtn.addEventListener('click', () => {
                familyForm.classList.add('show');
            });

            cancelFamilyFormBtn.addEventListener('click', () => {
                familyForm.classList.remove('show');
            });

            closeModalBtn.addEventListener('click', closeModal);
            cancelModalBtn.addEventListener('click', closeModal);
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
        })();
    </script>
</body>
</html>





