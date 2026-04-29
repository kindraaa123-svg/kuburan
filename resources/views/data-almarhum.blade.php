<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Almarhum</title>
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
            font-size: .88rem;
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
            border-radius: 10px;
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
            width: min(840px, 100%);
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

        .field-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
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
        .field select,
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
        .field select:focus,
        .field textarea:focus {
            outline: none;
            border-color: rgba(122, 17, 41, 0.46);
            box-shadow: 0 0 0 4px rgba(122, 17, 41, 0.1);
        }

        .field textarea { min-height: 92px; resize: vertical; }
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
            padding: 9px 12px;
            font-size: .82rem;
            font-weight: 700;
            cursor: pointer;
        }

        .detail-link {
            text-decoration: none;
            color: #7a1129;
            border: 1px solid rgba(122, 17, 41, 0.24);
            background: rgba(122, 17, 41, 0.08);
            border-radius: 8px;
            padding: 8px 10px;
            font-size: .76rem;
            font-weight: 700;
        }

        .photo-preview-wrap {
            margin-top: 8px;
            border: 1px dashed rgba(115, 38, 54, 0.22);
            border-radius: 10px;
            padding: 8px;
            background: #fdf6f7;
        }

        .photo-preview-wrap img {
            width: 100%;
            max-height: 220px;
            object-fit: contain;
            border-radius: 8px;
            border: 1px solid rgba(115, 38, 54, 0.18);
            background: #fff;
        }

        .empty-state { margin-top: 12px; border: 1px dashed rgba(115, 38, 54, 0.22); border-radius: 10px; padding: 12px; color: var(--muted); background: #fdf6f7; font-size: .85rem; }

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
                <a href="{{ route('dashboard.data-almarhum') }}" class="sidebar-menu-item active">Data Almarhum</a>
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
                <h1>Data Almarhum per Blok</h1>
                <p>Klik plot kosong untuk tambah almarhum. Klik plot terisi untuk edit data almarhum.</p>
            </div>

            @if (session('status'))
                <div class="flash">{{ session('status') }}</div>
            @endif

            @if ($errors->almarhumForm->any())
                <div class="flash flash-error">
                    Gagal menyimpan data almarhum:
                    <ul>
                        @foreach ($errors->almarhumForm->all() as $error)
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
                                            data-full-name="{{ $plot['deceased_name'] }}"
                                            data-gender="{{ $plot['deceased_gender'] }}"
                                            data-birth-date="{{ $plot['deceased_birth_date'] }}"
                                            data-death-date="{{ $plot['deceased_death_date'] }}"
                                            data-burial-date="{{ $plot['deceased_burial_date'] }}"
                                            data-religion="{{ $plot['deceased_religion'] }}"
                                            data-identity-number="{{ $plot['deceased_identity_number'] }}"
                                            data-photo-url="{{ $plot['deceased_photo_url'] }}"
                                            style="
                                                left: {{ (float) $plot['x'] }}px;
                                                top: {{ (float) $plot['y'] }}px;
                                                width: {{ (float) $plot['width'] }}px;
                                                height: {{ (float) $plot['height'] }}px;
                                            "
                                            title="Plot {{ $plot['number'] }} - {{ $plot['deceased_id'] ? ($plot['deceased_name'] ?: 'Almarhum') : 'KOSONG' }}"
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

    <div class="modal-backdrop" id="deceasedModal" aria-hidden="true">
        <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="deceasedModalTitle">
            <div class="modal-head">
                <h3 id="deceasedModalTitle">Form Almarhum</h3>
                <button type="button" class="modal-close" id="closeDeceasedModal">x</button>
            </div>

            <form method="POST" action="{{ route('dashboard.data-almarhum.store') }}" id="deceasedForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="deceasedMethodInput" value="">
                <input type="hidden" name="_deceasedid" id="deceasedIdInput" value="">
                <input type="hidden" name="plotid" id="plotIdInput" value="">

                <div class="field-grid">
                    <div class="field">
                        <label>Blok & Plot</label>
                        <input type="text" id="plotLabelInput" value="" readonly>
                    </div>
                    <div class="field">
                        <label for="full_name">Nama Almarhum</label>
                        <input id="full_name" name="full_name" type="text" required>
                    </div>
                    <div class="field">
                        <label for="gender">Jenis Kelamin</label>
                        <select id="gender" name="gender">
                            <option value="">- Pilih -</option>
                            <option value="male">Laki-laki</option>
                            <option value="female">Perempuan</option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="birth_date">Tanggal Lahir</label>
                        <input id="birth_date" name="birth_date" type="date">
                    </div>
                    <div class="field">
                        <label for="death_date">Tanggal Wafat</label>
                        <input id="death_date" name="death_date" type="date">
                    </div>
                    <div class="field">
                        <label for="burial_date">Tanggal Pemakaman</label>
                        <input id="burial_date" name="burial_date" type="date">
                    </div>
                    <div class="field">
                        <label for="religion">Agama</label>
                        <input id="religion" name="religion" type="text">
                    </div>
                    <div class="field">
                        <label for="identity_number">NIK / Nomor Identitas</label>
                        <input id="identity_number" name="identity_number" type="text">
                    </div>
                    <div class="field">
                        <label for="photo">Foto Almarhum</label>
                        <input id="photo" name="photo" type="file" accept="image/*">
                        <div class="photo-preview-wrap" id="photoPreviewWrap" style="display:none;">
                            <img id="photoPreview" src="" alt="Preview foto almarhum">
                        </div>
                    </div>
                </div>

                <div class="modal-actions">
                    <a href="#" id="deceasedDetailLink" class="detail-link" style="display:none;">Buka Detail</a>
                    <button type="button" class="btn-danger" id="deleteDeceasedBtn" style="display:none;">Hapus</button>
                    <button type="button" class="btn-secondary" id="cancelDeceasedModal">Batal</button>
                    <button type="submit" class="btn-primary" id="submitDeceasedBtn">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (() => {
            const modal = document.getElementById('deceasedModal');
            const modalTitle = document.getElementById('deceasedModalTitle');
            const closeBtn = document.getElementById('closeDeceasedModal');
            const cancelBtn = document.getElementById('cancelDeceasedModal');
            const form = document.getElementById('deceasedForm');
            const methodInput = document.getElementById('deceasedMethodInput');
            const deceasedIdInput = document.getElementById('deceasedIdInput');
            const plotIdInput = document.getElementById('plotIdInput');
            const plotLabelInput = document.getElementById('plotLabelInput');
            const submitBtn = document.getElementById('submitDeceasedBtn');
            const detailLink = document.getElementById('deceasedDetailLink');
            const deleteBtn = document.getElementById('deleteDeceasedBtn');
            const photoInput = document.getElementById('photo');
            const photoPreviewWrap = document.getElementById('photoPreviewWrap');
            const photoPreview = document.getElementById('photoPreview');
            const slotButtons = document.querySelectorAll('[data-plot-slot]');
            const baseUrl = "{{ url('/dashboard/data-almarhum') }}";
            const csrfToken = '{{ csrf_token() }}';
            let currentSlotButton = null;
            let objectPreviewUrl = null;

            const fields = {
                full_name: document.getElementById('full_name'),
                gender: document.getElementById('gender'),
                birth_date: document.getElementById('birth_date'),
                death_date: document.getElementById('death_date'),
                burial_date: document.getElementById('burial_date'),
                religion: document.getElementById('religion'),
                identity_number: document.getElementById('identity_number'),
            };

            const setFieldValues = (data) => {
                Object.entries(fields).forEach(([key, input]) => {
                    input.value = data[key] || '';
                });
            };

            const openModal = () => {
                modal.classList.add('show');
                modal.setAttribute('aria-hidden', 'false');
            };

            const closeModal = () => {
                modal.classList.remove('show');
                modal.setAttribute('aria-hidden', 'true');
            };

            const clearObjectPreview = () => {
                if (objectPreviewUrl) {
                    URL.revokeObjectURL(objectPreviewUrl);
                    objectPreviewUrl = null;
                }
            };

            const setPreviewPhoto = (url = '') => {
                if (!photoPreviewWrap || !photoPreview) {
                    return;
                }

                if (!url) {
                    photoPreviewWrap.style.display = 'none';
                    photoPreview.src = '';
                    return;
                }

                photoPreview.src = url;
                photoPreviewWrap.style.display = 'block';
            };

            if (photoInput) {
                photoInput.addEventListener('change', () => {
                    const file = photoInput.files && photoInput.files[0] ? photoInput.files[0] : null;
                    if (!file) {
                        clearObjectPreview();
                        setPreviewPhoto('');
                        return;
                    }

                    clearObjectPreview();
                    objectPreviewUrl = URL.createObjectURL(file);
                    setPreviewPhoto(objectPreviewUrl);
                });
            }

            const applyDeceasedToSlot = (slot, data) => {
                if (!slot || !data) return;
                slot.dataset.deceasedId = String(data.deceasedid || '');
                slot.dataset.fullName = data.full_name || '';
                slot.dataset.gender = data.gender || '';
                slot.dataset.birthDate = data.birth_date || '';
                slot.dataset.deathDate = data.death_date || '';
                slot.dataset.burialDate = data.burial_date || '';
                slot.dataset.religion = data.religion || '';
                slot.dataset.identityNumber = data.identity_number || '';
                slot.dataset.photoUrl = data.photo_url || '';
                slot.classList.remove('plot-empty');
                slot.classList.add('plot-occupied');
                const plotNumber = (slot.dataset.plotNumber || '').trim();
                slot.title = `Plot ${plotNumber} - ${data.full_name || 'Almarhum'}`;
            };

            const clearDeceasedFromSlot = (slot) => {
                if (!slot) return;
                slot.dataset.deceasedId = '';
                slot.dataset.fullName = '';
                slot.dataset.gender = '';
                slot.dataset.birthDate = '';
                slot.dataset.deathDate = '';
                slot.dataset.burialDate = '';
                slot.dataset.religion = '';
                slot.dataset.identityNumber = '';
                slot.dataset.photoUrl = '';
                slot.classList.remove('plot-occupied');
                slot.classList.add('plot-empty');
                const plotNumber = (slot.dataset.plotNumber || '').trim();
                slot.title = `Plot ${plotNumber} - KOSONG`;
            };

            slotButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    currentSlotButton = button;
                    const deceasedId = (button.dataset.deceasedId || '').trim();
                    const plotId = (button.dataset.plotId || '').trim();
                    const plotNumber = (button.dataset.plotNumber || '').trim();
                    const blockName = (button.dataset.blockName || '').trim();
                    const isEdit = deceasedId.length > 0;

                    plotIdInput.value = plotId;
                    deceasedIdInput.value = deceasedId;
                    plotLabelInput.value = `${blockName} - Plot ${plotNumber}`;

                    if (isEdit) {
                        modalTitle.textContent = `Edit Almarhum - ${blockName} / Plot ${plotNumber}`;
                        submitBtn.textContent = 'Update Almarhum';
                        form.action = `${baseUrl}/${deceasedId}`;
                        methodInput.name = '_method';
                        methodInput.value = 'PUT';
                        detailLink.style.display = 'inline-block';
                        detailLink.href = `{{ url('/deceased') }}/${deceasedId}`;
                        deleteBtn.style.display = 'inline-block';
                        const currentPhotoUrl = (button.dataset.photoUrl || '').trim();
                        clearObjectPreview();
                        setPreviewPhoto(currentPhotoUrl);
                        setFieldValues({
                            full_name: button.dataset.fullName,
                            gender: button.dataset.gender,
                            birth_date: button.dataset.birthDate,
                            death_date: button.dataset.deathDate,
                            burial_date: button.dataset.burialDate,
                            religion: button.dataset.religion,
                            identity_number: button.dataset.identityNumber,
                        });
                    } else {
                        modalTitle.textContent = `Tambah Almarhum - ${blockName} / Plot ${plotNumber}`;
                        submitBtn.textContent = 'Simpan Almarhum';
                        form.action = "{{ route('dashboard.data-almarhum.store') }}";
                        methodInput.removeAttribute('name');
                        methodInput.value = '';
                        detailLink.style.display = 'none';
                        detailLink.href = '#';
                        deleteBtn.style.display = 'none';
                        clearObjectPreview();
                        setPreviewPhoto('');
                        setFieldValues({
                            full_name: '',
                            gender: '',
                            birth_date: '',
                            death_date: '',
                            burial_date: '',
                            religion: '',
                            identity_number: '',
                        });
                    }

                    if (photoInput) {
                        photoInput.value = '';
                    }

                    openModal();
                });
            });

            form.addEventListener('submit', async (event) => {
                event.preventDefault();

                const formData = new FormData(form);
                if (methodInput.value === 'PUT') {
                    formData.set('_method', 'PUT');
                }

                submitBtn.disabled = true;
                try {
                    const response = await fetch(form.action, {
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
                        const firstErrorKey = payload?.errors ? Object.keys(payload.errors)[0] : null;
                        const firstError = firstErrorKey ? (payload.errors[firstErrorKey]?.[0] || null) : null;
                        window.alert(firstError || payload.message || 'Gagal menyimpan data almarhum.');
                        return;
                    }

                    applyDeceasedToSlot(currentSlotButton, payload.data || {});
                    closeModal();
                } catch (error) {
                    window.alert('Gagal menyimpan data almarhum.');
                } finally {
                    submitBtn.disabled = false;
                }
            });

            deleteBtn.addEventListener('click', async () => {
                const deceasedId = (deceasedIdInput.value || '').trim();
                if (!deceasedId) return;

                const ok = window.confirm('Hapus data almarhum ini?');
                if (!ok) return;

                deleteBtn.disabled = true;
                try {
                    const body = new URLSearchParams();
                    body.set('_token', csrfToken);
                    body.set('_method', 'DELETE');

                    const response = await fetch(`${baseUrl}/${deceasedId}`, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                        },
                        body: body.toString(),
                    });

                    const payload = await response.json().catch(() => ({}));
                    if (!response.ok) {
                        window.alert(payload.message || 'Gagal menghapus data almarhum.');
                        return;
                    }

                    clearDeceasedFromSlot(currentSlotButton);
                    closeModal();
                } catch (error) {
                    window.alert('Gagal menghapus data almarhum.');
                } finally {
                    deleteBtn.disabled = false;
                }
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
        })();
    </script>
</body>
</html>







