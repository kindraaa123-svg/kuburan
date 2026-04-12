<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Almarhum</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Space+Grotesk:wght@500;700&display=swap');

        :root {
            --bg: #edf3f1;
            --card: #ffffff;
            --text: #163036;
            --muted: #667c81;
            --line: #d3e1dc;
            --brand: #1f7a67;
            --soft: #e7f4ee;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "DM Sans", sans-serif;
            color: var(--text);
            background:
                radial-gradient(900px 430px at 0% -10%, #d8eae3 0%, transparent 62%),
                radial-gradient(820px 430px at 100% -6%, #ddebe6 0%, transparent 62%),
                var(--bg);
        }

        .wrap {
            width: min(1120px, 94vw);
            margin: 22px auto 34px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: center;
            margin-bottom: 14px;
        }

        .title {
            margin: 0;
            font-family: "Space Grotesk", sans-serif;
            font-size: clamp(1.2rem, 2.5vw, 1.8rem);
        }

        .back {
            text-decoration: none;
            color: #11493e;
            border: 1px solid #b7cfc6;
            background: #f7fcf9;
            border-radius: 10px;
            padding: 9px 13px;
            font-size: .82rem;
            font-weight: 700;
        }

        .layout {
            display: grid;
            grid-template-columns: 340px 1fr;
            gap: 14px;
        }

        .card {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 16px;
            box-shadow: 0 12px 26px rgba(17, 48, 54, 0.08);
        }

        .profile {
            padding: 16px;
        }

        .photo {
            width: 100%;
            aspect-ratio: 4 / 3;
            border-radius: 12px;
            border: 1px solid #c9d8d3;
            object-fit: cover;
            background: #e8f1ee;
        }

        .name {
            margin: 12px 0 0;
            font-size: 1.25rem;
            font-family: "Space Grotesk", sans-serif;
        }

        .meta {
            margin: 8px 0 0;
            color: var(--muted);
            font-size: .88rem;
        }

        .tags {
            margin-top: 10px;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .tag {
            border: 1px solid #c6d9d2;
            border-radius: 999px;
            padding: 5px 9px;
            font-size: .74rem;
            font-weight: 700;
            color: #2e5751;
            background: #f8fcfa;
        }

        .content {
            padding: 14px;
            display: grid;
            gap: 12px;
        }

        .section {
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 12px;
            background: #fcfefd;
        }

        .section h2 {
            margin: 0;
            font-size: 1rem;
            font-family: "Space Grotesk", sans-serif;
        }

        .section p {
            margin: 6px 0 0;
            color: var(--muted);
            font-size: .82rem;
        }

        .grid {
            margin-top: 10px;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 9px;
        }

        .item {
            border: 1px solid #d8e5e0;
            border-radius: 10px;
            padding: 9px;
            background: #fff;
        }

        .item small {
            display: block;
            font-size: .68rem;
            color: #70858a;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .item strong {
            font-size: .86rem;
            line-height: 1.35;
        }

        .family-list {
            margin-top: 10px;
            display: grid;
            gap: 9px;
        }

        .family {
            border: 1px solid #d4e3dd;
            border-radius: 12px;
            padding: 10px;
            background: #fff;
        }

        .family-head {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            align-items: center;
            margin-bottom: 8px;
        }

        .family-head h3 {
            margin: 0;
            font-size: .92rem;
        }

        .pill {
            border: 1px solid #b7d2c9;
            border-radius: 999px;
            padding: 4px 8px;
            font-size: .7rem;
            font-weight: 700;
            color: #2f5e54;
            background: var(--soft);
        }

        .family-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px;
        }

        .field {
            border: 1px solid #dce7e2;
            border-radius: 10px;
            padding: 8px;
            background: #fbfdfc;
        }

        .field small {
            display: block;
            margin-bottom: 3px;
            color: #71878b;
            font-size: .67rem;
            font-weight: 700;
        }

        .field span, .field a {
            color: #18343b;
            font-size: .83rem;
            font-weight: 700;
            text-decoration: none;
            word-break: break-word;
        }

        .empty {
            margin-top: 10px;
            border: 1px dashed #c7d8d2;
            border-radius: 12px;
            padding: 12px;
            color: #6d8186;
            background: #f8fcfa;
            font-size: .85rem;
        }

        @media (max-width: 980px) {
            .layout {
                grid-template-columns: 1fr;
            }
            .grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 660px) {
            .grid,
            .family-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <main class="wrap">
        <div class="topbar">
            <h1 class="title">Detail Data Almarhum</h1>
            <a class="back" href="{{ url('/dashboard') }}">Kembali ke Denah</a>
        </div>

        <section class="layout">
            <aside class="card profile">
                <img
                    class="photo"
                    src="{{ $photoUrl ?: 'data:image/svg+xml;utf8,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22500%22 height=%22360%22%3E%3Crect width=%22100%25%22 height=%22100%25%22 fill=%22%23dde9e5%22/%3E%3Ctext x=%2250%25%22 y=%2253%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 font-family=%22Arial%22 font-size=%2248%22 fill=%22%232f545d%22%3E-%3C/text%3E%3C/svg%3E' }}"
                    alt="Foto {{ $deceased->full_name }}"
                >
                <h2 class="name">{{ $deceased->full_name }}</h2>
                <p class="meta">
                    {{ $deceased->row_number ? 'Baris ' . $deceased->row_number . ' - ' : '' }}Plot {{ $deceased->plot_number ?? '-' }} • {{ $deceased->block_name ?? 'Blok tidak tersedia' }}
                </p>
                <div class="tags">
                    <span class="tag">{{ $deceased->gender ?? '-' }}</span>
                    <span class="tag">{{ $deceased->religion ?? '-' }}</span>
                    <span class="tag">{{ $ageAtDeath !== null ? $ageAtDeath . ' tahun' : '-' }}</span>
                </div>
            </aside>

            <div class="card content">
                <div class="section">
                    <h2>Informasi Utama</h2>
                    <p>Ringkasan identitas dan waktu pemakaman.</p>
                    <div class="grid">
                        <div class="item"><small>Umur Wafat</small><strong>{{ $ageAtDeath !== null ? $ageAtDeath . ' tahun' : '-' }}</strong></div>
                        <div class="item"><small>Tanggal Wafat</small><strong>{{ $deceased->death_date ?? '-' }}</strong></div>
                        <div class="item"><small>Tanggal Lahir</small><strong>{{ $deceased->birth_date ?? '-' }}</strong></div>
                        <div class="item"><small>Tanggal Pemakaman</small><strong>{{ $deceased->burial_date ?? '-' }}</strong></div>
                        <div class="item"><small>NIK / Identitas</small><strong>{{ $deceased->identity_number ?? '-' }}</strong></div>
                        <div class="item"><small>Alamat Almarhum</small><strong>{{ $deceased->address ?? '-' }}</strong></div>
                    </div>
                </div>

                <div class="section">
                    <h2>Kontak Keluarga</h2>
                    <p>Daftar pihak keluarga yang dapat dihubungi terkait data ini.</p>

                    @if ($familyContacts->isEmpty())
                        <div class="empty">Belum ada data kontak keluarga untuk almarhum ini.</div>
                    @else
                        <div class="family-list">
                            @foreach ($familyContacts as $family)
                                <article class="family">
                                    <div class="family-head">
                                        <h3>{{ $family->family_name ?: '-' }}</h3>
                                        <span class="pill">{{ $family->relationship_status ?: '-' }}</span>
                                    </div>
                                    <div class="family-grid">
                                        <div class="field">
                                            <small>No. Telepon</small>
                                            <span>{{ $family->phone_number ?: '-' }}</span>
                                        </div>
                                        <div class="field">
                                            <small>Email</small>
                                            @if ($family->email)
                                                <a href="mailto:{{ $family->email }}">{{ $family->email }}</a>
                                            @else
                                                <span>-</span>
                                            @endif
                                        </div>
                                        <div class="field">
                                            <small>Alamat Keluarga</small>
                                            <span>{{ $family->address ?: '-' }}</span>
                                        </div>
                                        <div class="field">
                                            <small>Catatan</small>
                                            <span>{{ $family->notes ?: '-' }}</span>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </main>
</body>
</html>
