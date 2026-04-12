<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data User</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Space+Grotesk:wght@600;700&display=swap');

        :root {
            --bg: #edf3f1;
            --card: #ffffff;
            --line: #d2dfda;
            --text: #163036;
            --muted: #657c81;
            --brand-soft: #e8f3ef;
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
            padding: 16px;
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
            font-size: 1.35rem;
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

        .status {
            margin-top: 12px;
            border: 1px solid #b8d4c9;
            background: var(--brand-soft);
            color: #175247;
            border-radius: 10px;
            padding: 10px;
            font-size: .83rem;
            font-weight: 700;
        }

        .status.error {
            border-color: #e3b5b5;
            background: #fdeeee;
            color: #c44a4a;
        }

        .status ul {
            margin: 8px 0 0 16px;
            padding: 0;
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
            min-width: 640px;
        }

        th,
        td {
            padding: 10px 12px;
            border-bottom: 1px solid #e3ece8;
            font-size: .86rem;
            text-align: left;
        }

        th {
            background: #f5faf8;
            color: #2f5158;
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .02em;
        }

        tbody tr:hover {
            background: #f9fcfb;
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
            border: 1px solid #b8cbc5;
            background: #f2f8f5;
            color: #19493f;
            font-size: .76rem;
            font-weight: 700;
            padding: 0 10px;
            cursor: pointer;
            white-space: nowrap;
        }

        .btn-reset {
            border-color: #9abeb2;
            background: #eaf5f0;
            color: #155246;
        }

        .btn-delete {
            border-color: #e0b8b8;
            background: #fceeee;
            color: #9e3131;
        }

        .btn-disabled {
            border-color: #d8e4df;
            background: #f4f8f6;
            color: #7d9296;
            cursor: not-allowed;
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
            width: min(700px, 96vw);
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
            font-size: .78rem;
            color: var(--muted);
            font-weight: 700;
        }

        input,
        select {
            width: 100%;
            border: 1px solid #c7d7d1;
            border-radius: 10px;
            padding: 10px 12px;
            font-size: .9rem;
            color: var(--text);
            background: #fff;
        }

        .modal-actions {
            margin-top: 12px;
            display: flex;
            justify-content: flex-end;
            gap: 8px;
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
                <a href="{{ route('dashboard.data-plot') }}" class="sidebar-menu-item">Data Plot</a>
                <a href="{{ route('dashboard.data-almarhum') }}" class="sidebar-menu-item">Data Almarhum</a>
                <a href="{{ route('dashboard.data-user') }}" class="sidebar-menu-item active">Data User</a>
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
                    <h1>Data User</h1>
                    <p>Daftar akun user yang terdaftar pada sistem.</p>
                </div>
                <button type="button" class="add-btn" id="openAddUserModalBtn">+ Tambah User</button>
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

    <script>
        (() => {
            const modal = document.getElementById('addUserModal');
            const openBtn = document.getElementById('openAddUserModalBtn');
            const closeBtn = document.getElementById('closeAddUserModalBtn');
            const cancelBtn = document.getElementById('cancelAddUserModalBtn');

            function openModal() {
                modal.classList.add('active');
                modal.setAttribute('aria-hidden', 'false');
            }

            function closeModal() {
                modal.classList.remove('active');
                modal.setAttribute('aria-hidden', 'true');
            }

            openBtn.addEventListener('click', openModal);
            closeBtn.addEventListener('click', closeModal);
            cancelBtn.addEventListener('click', closeModal);

            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal();
                }
            });

            window.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && modal.classList.contains('active')) {
                    closeModal();
                }
            });

            @if ($errors->createUser->any())
                openModal();
            @endif
        })();
    </script>
</body>
</html>
