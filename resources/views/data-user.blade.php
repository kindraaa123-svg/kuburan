<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data User</title>
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
            --brand-soft: rgba(122, 17, 41, 0.09);
            --danger: #92223b;
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

        .head {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 12px;
            flex-wrap: wrap;
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
        }

        .add-btn {
            min-height: 42px;
            border: 1px solid #6d0f24;
            border-radius: 12px;
            background: linear-gradient(130deg, #a52142 0%, var(--brand) 52%, #4a0615 100%);
            color: #fff;
            font-weight: 800;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            padding: 0 14px;
            cursor: pointer;
            box-shadow: 0 14px 24px rgba(84, 9, 24, 0.24);
        }
        .head-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .head-btn {
            min-height: 42px;
            border-radius: 12px;
            padding: 0 14px;
            font-weight: 800;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .head-btn-export {
            border: 1px solid rgba(122, 17, 41, 0.28);
            background: rgba(122, 17, 41, 0.08);
            color: #7a1129;
        }
        .head-btn-import {
            border: 1px solid #d8c0c7;
            background: #fff;
            color: #6f3a48;
        }

        .status {
            margin-top: 12px;
            border: 1px solid rgba(122, 17, 41, 0.22);
            background: var(--brand-soft);
            color: var(--brand);
            border-radius: 12px;
            padding: 10px 12px;
            font-size: 0.83rem;
            font-weight: 700;
        }

        .status.error {
            border-color: rgba(146, 34, 59, 0.25);
            background: rgba(146, 34, 59, 0.1);
            color: var(--danger);
        }

        .status ul {
            margin: 8px 0 0 16px;
            padding: 0;
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
            min-width: 640px;
        }

        th,
        td {
            padding: 10px 12px;
            border-bottom: 1px solid #ecdde1;
            font-size: 0.86rem;
            text-align: left;
        }

        th {
            position: sticky;
            top: 0;
            background: #f5e8eb;
            color: #5a2532;
            font-size: 0.76rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 800;
            z-index: 2;
        }

        tbody tr:hover {
            background: #fdf6f7;
        }

        .empty {
            text-align: center;
            color: var(--muted);
            font-weight: 700;
        }

        .actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .inline-form {
            margin: 0;
        }

        .btn-action {
            min-height: 34px;
            border-radius: 8px;
            border: 1px solid #b89aa3;
            background: #f8eff2;
            color: #6b3141;
            font-size: 0.76rem;
            font-weight: 700;
            padding: 0 10px;
            cursor: pointer;
            white-space: nowrap;
        }

        .btn-reset {
            border-color: rgba(122, 17, 41, 0.26);
            background: rgba(122, 17, 41, 0.1);
            color: #7a1129;
        }

        .btn-delete {
            border-color: rgba(146, 34, 59, 0.28);
            background: rgba(146, 34, 59, 0.12);
            color: #92223b;
        }

        .btn-disabled {
            border-color: #e2cfd4;
            background: #f6eff1;
            color: #9e7d85;
            cursor: not-allowed;
        }

        .modal {
            position: fixed;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(26, 6, 12, 0.62);
            z-index: 40;
            padding: 14px;
        }

        .modal.active {
            display: flex;
        }

        .modal-card {
            width: min(700px, 96vw);
            background: #fff8f6;
            border: 1px solid rgba(115, 38, 54, 0.2);
            border-radius: 14px;
            padding: 14px;
            box-shadow: 0 24px 48px rgba(22, 4, 9, 0.38);
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
            color: #4f1f2a;
        }

        .close-btn {
            border: 1px solid #d8c0c7;
            border-radius: 8px;
            background: #fff;
            color: #6f3a48;
            width: 32px;
            height: 32px;
            cursor: pointer;
            font-size: 1rem;
            line-height: 1;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
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
            font-size: 0.78rem;
            color: var(--muted);
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        input,
        select {
            width: 100%;
            border: 1px solid rgba(110, 52, 63, 0.2);
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 0.9rem;
            color: var(--text);
            background: #fff;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: rgba(122, 17, 41, 0.46);
            box-shadow: 0 0 0 4px rgba(122, 17, 41, 0.1);
        }

        .modal-actions {
            margin-top: 12px;
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }

        .btn-secondary {
            min-height: 40px;
            border: 1px solid #d8c0c7;
            border-radius: 10px;
            background: #fff;
            color: #6f3a48;
            padding: 0 12px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-primary {
            min-height: 40px;
            border: 1px solid #6d0f24;
            border-radius: 10px;
            background: linear-gradient(130deg, #a52142 0%, var(--brand) 52%, #4a0615 100%);
            color: #fff;
            padding: 0 14px;
            font-weight: 700;
            cursor: pointer;
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

            .form-grid {
                grid-template-columns: 1fr;
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
                <a href="{{ route('dashboard.data-user') }}" class="sidebar-menu-item active">Data User</a>
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
                    <h1>Data User</h1>
                    <p>Daftar akun user yang terdaftar pada sistem.</p>
                </div>
                <div class="head-actions">
                    <a href="{{ route('dashboard.data-user.export') }}" class="head-btn head-btn-export">Export .xlsx</a>
                    <button type="button" class="head-btn head-btn-import" id="openImportUserModalBtn">Import .xlsx</button>
                    <button type="button" class="add-btn" id="openAddUserModalBtn">+ Tambah User</button>
                </div>
            </div>

            @if (session('status'))
                <div class="status">{{ session('status') }}</div>
            @endif

            @if ($errors->createUser->any())
                <div class="status error">
                    Gagal menambahkan user:
                    <ul>
                        @foreach ($errors->createUser->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($errors->importUser->any())
                <div class="status error">
                    Gagal import user:
                    <ul>
                        @foreach ($errors->importUser->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phonenumber</th>
                            <th>Levelname</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $index => $user)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->full_name ?: '-' }}</td>
                                <td>{{ $user->email ?: '-' }}</td>
                                <td>{{ $user->phone_number ?: '-' }}</td>
                                <td>{{ $user->levelname ?? ('Level ' . $user->levelid) }}</td>
                                <td>
                                    <div class="actions">
                                        <form class="inline-form" method="POST" action="{{ route('dashboard.data-user.reset-password', ['user' => $user->userid]) }}" onsubmit="return confirm('Reset password user {{ $user->username }} ke 12345?')">
                                            @csrf
                                            <button type="submit" class="btn-action btn-reset">Reset Password</button>
                                        </form>
                                        @if ((int) ($authUser['id'] ?? 0) === (int) $user->userid)
                                            <button type="button" class="btn-action btn-disabled" disabled>Akun Aktif</button>
                                        @else
                                            <form class="inline-form" method="POST" action="{{ route('dashboard.data-user.destroy', ['user' => $user->userid]) }}" onsubmit="return confirm('Hapus user {{ $user->username }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action btn-delete">Hapus User</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="empty" colspan="7">Belum ada data user.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <div class="modal" id="addUserModal" aria-hidden="true">
        <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="addUserModalTitle">
            <div class="modal-head">
                <h3 id="addUserModalTitle">Tambah User</h3>
                <button type="button" class="close-btn" id="closeAddUserModalBtn">x</button>
            </div>

            <form method="POST" action="{{ route('dashboard.data-user.store') }}">
                @csrf
                <div class="form-grid">
                    <div class="field">
                        <label for="username">Username</label>
                        <input id="username" name="username" type="text" required value="{{ old('username') }}">
                    </div>
                    <div class="field">
                        <label for="levelid">Level</label>
                        @if (count($levels) > 0)
                            <select id="levelid" name="levelid" required>
                                <option value="">Pilih level</option>
                                @foreach ($levels as $level)
                                    <option value="{{ $level->levelid }}" @selected((string) old('levelid') === (string) $level->levelid)>
                                        {{ $level->levelname ?? ('Level ' . $level->levelid) }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <input id="levelid" name="levelid" type="number" min="1" required value="{{ old('levelid', 1) }}">
                        @endif
                    </div>
                    <div class="field full">
                        <label for="full_name">Nama Lengkap</label>
                        <input id="full_name" name="full_name" type="text" value="{{ old('full_name') }}">
                    </div>
                    <div class="field">
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}">
                    </div>
                    <div class="field">
                        <label for="phone_number">Nomor Telepon</label>
                        <input id="phone_number" name="phone_number" type="text" value="{{ old('phone_number') }}">
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelAddUserModalBtn">Batal</button>
                    <button type="submit" class="btn-primary">Simpan User</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal" id="importUserModal" aria-hidden="true">
        <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="importUserModalTitle">
            <div class="modal-head">
                <h3 id="importUserModalTitle">Import User (.xlsx)</h3>
                <button type="button" class="close-btn" id="closeImportUserModalBtn">x</button>
            </div>

            <form method="POST" action="{{ route('dashboard.data-user.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-grid">
                    <div class="field full">
                        <label for="user_file">File Excel (.xlsx)</label>
                        <input id="user_file" name="user_file" type="file" accept=".xlsx" required>
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelImportUserModalBtn">Batal</button>
                    <button type="submit" class="btn-primary">Import User</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (() => {
            const modal = document.getElementById('addUserModal');
            const openBtn = document.getElementById('openAddUserModalBtn');
            const closeBtn = document.getElementById('closeAddUserModalBtn');
            const cancelBtn = document.getElementById('cancelAddUserModalBtn');
            const importModal = document.getElementById('importUserModal');
            const openImportBtn = document.getElementById('openImportUserModalBtn');
            const closeImportBtn = document.getElementById('closeImportUserModalBtn');
            const cancelImportBtn = document.getElementById('cancelImportUserModalBtn');

            function openModal() {
                modal.classList.add('active');
                modal.setAttribute('aria-hidden', 'false');
            }

            function closeModal() {
                modal.classList.remove('active');
                modal.setAttribute('aria-hidden', 'true');
            }

            function openImportModal() {
                importModal.classList.add('active');
                importModal.setAttribute('aria-hidden', 'false');
            }

            function closeImportModal() {
                importModal.classList.remove('active');
                importModal.setAttribute('aria-hidden', 'true');
            }

            openBtn.addEventListener('click', openModal);
            closeBtn.addEventListener('click', closeModal);
            cancelBtn.addEventListener('click', closeModal);
            openImportBtn.addEventListener('click', openImportModal);
            closeImportBtn.addEventListener('click', closeImportModal);
            cancelImportBtn.addEventListener('click', closeImportModal);

            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal();
                }
            });
            importModal.addEventListener('click', (event) => {
                if (event.target === importModal) {
                    closeImportModal();
                }
            });

            window.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && modal.classList.contains('active')) {
                    closeModal();
                }
                if (event.key === 'Escape' && importModal.classList.contains('active')) {
                    closeImportModal();
                }
            });

            @if ($errors->createUser->any())
                openModal();
            @endif
            @if ($errors->importUser->any())
                openImportModal();
            @endif
        })();
    </script>
</body>
</html>







