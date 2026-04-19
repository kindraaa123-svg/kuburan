<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Almarhum</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Space+Grotesk:wght@500;700&display=swap');

        :root {
            --bg: #f8f3f4;
            --card: #ffffff;
            --text: #311417;
            --muted: #7c5d62;
            --line: #e8d9dc;
            --brand: #7f1d2d;
            --soft: #f8ecef;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            min-height: 100%;
            background-color: var(--bg);
        }

        body {
            margin: 0;
            font-family: "DM Sans", sans-serif;
            color: var(--text);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
            background: linear-gradient(180deg, #f6edef 0%, #f8f3f4 45%, #f8f3f4 100%);
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -1;
            pointer-events: none;
            background:
                radial-gradient(900px 420px at -6% -14%, rgba(141, 33, 54, 0.14) 0%, rgba(141, 33, 54, 0) 70%),
                radial-gradient(840px 400px at 106% -12%, rgba(196, 89, 113, 0.12) 0%, rgba(196, 89, 113, 0) 72%),
                radial-gradient(740px 360px at 50% 108%, rgba(150, 44, 66, 0.08) 0%, rgba(150, 44, 66, 0) 76%);
        }

        .wrap {
            width: min(1120px, 94vw);
            margin: 24px auto 0;
            padding-bottom: 36px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: center;
            margin-bottom: 16px;
            padding: 8px 4px;
        }

        .title {
            margin: 0;
            font-family: "Space Grotesk", sans-serif;
            font-size: clamp(1.26rem, 2.8vw, 2rem);
            letter-spacing: .01em;
        }

        .back {
            text-decoration: none;
            color: #6f1a2a;
            border: 1px solid #d9bcc2;
            background: #fff8f9;
            border-radius: 10px;
            padding: 9px 13px;
            font-size: .82rem;
            font-weight: 700;
            transition: .2s ease;
        }

        .back:hover {
            color: #fff;
            background: linear-gradient(135deg, #9e2b3f, #7f1d2d);
            border-color: #8b2335;
        }

        .layout {
            display: grid;
            grid-template-columns: 340px 1fr;
            gap: 16px;
        }

        .card {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 18px;
            box-shadow: 0 16px 28px rgba(89, 17, 34, 0.09);
        }

        .profile {
            padding: 16px;
            background:
                linear-gradient(180deg, #fffefe 0%, #fff8fa 100%);
        }

        .photo {
            width: 100%;
            aspect-ratio: 4 / 3;
            border-radius: 12px;
            border: 1px solid #dec7cc;
            object-fit: cover;
            background: #f2e8eb;
        }

        .name {
            margin: 12px 0 0;
            font-size: 1.72rem;
            font-family: "Space Grotesk", sans-serif;
            line-height: 1.1;
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
            border: 1px solid #d8bec4;
            border-radius: 999px;
            padding: 5px 9px;
            font-size: .74rem;
            font-weight: 700;
            color: #6a2130;
            background: #fff5f7;
        }

        .content {
            padding: 14px;
            display: grid;
            gap: 12px;
        }

        .section {
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 14px;
            background: #fffafb;
        }

        .section h2 {
            margin: 0;
            font-size: 1.12rem;
            font-family: "Space Grotesk", sans-serif;
            position: relative;
            padding-left: 11px;
        }

        .section h2::before {
            content: "";
            position: absolute;
            left: 0;
            top: 3px;
            bottom: 3px;
            width: 4px;
            border-radius: 999px;
            background: linear-gradient(180deg, #ab3249, #7f1d2d);
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
            border: 1px solid #eadbe0;
            border-radius: 10px;
            padding: 10px;
            background: #fff;
        }

        .item small {
            display: block;
            font-size: .68rem;
            color: #89666c;
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
            border: 1px solid #e6d6da;
            border-radius: 12px;
            padding: 10px;
            background: linear-gradient(180deg, #fff 0%, #fffcfd 100%);
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
            border: 1px solid #dabfc5;
            border-radius: 999px;
            padding: 4px 8px;
            font-size: .7rem;
            font-weight: 700;
            color: #6d2130;
            background: var(--soft);
        }

        .family-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px;
        }

        .field {
            border: 1px solid #ebdde1;
            border-radius: 10px;
            padding: 8px;
            background: #fff9fa;
        }

        .field small {
            display: block;
            margin-bottom: 3px;
            color: #8a676d;
            font-size: .67rem;
            font-weight: 700;
        }

        .field span, .field a {
            color: #3b171e;
            font-size: .83rem;
            font-weight: 700;
            text-decoration: none;
            word-break: break-word;
        }

        .field a:hover {
            color: var(--brand);
            text-decoration: underline;
        }

        .empty {
            margin-top: 10px;
            border: 1px dashed #d8bcc3;
            border-radius: 12px;
            padding: 12px;
            color: #7f5f64;
            background: #fff5f7;
            font-size: .85rem;
        }

        @media (max-width: 980px) {
            .layout {
                grid-template-columns: 1fr;
            }
            .grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
            .name {
                font-size: 1.4rem;
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
            <a class="back" href="{{ session()->has('auth_user') ? url('/dashboard') : url('/') }}">Kembali ke Denah</a>
        </div>

        <section class="layout">
            <aside class="card profile">
                <img
                    class="photo"
                    src="{{ $photoUrl ?: 'data:image/svg+xml;utf8,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22500%22 height=%22360%22%3E%3Crect width=%22100%25%22 height=%22100%25%22 fill=%22%23efe2e6%22/%3E%3Ctext x=%2250%25%22 y=%2253%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 font-family=%22Arial%22 font-size=%2248%22 fill=%22%2383565f%22%3E-%3C/text%3E%3C/svg%3E' }}"
                    alt="Foto {{ $deceased->full_name }}"
                >
                <h2 class="name">{{ $deceased->full_name }}</h2>
                <p class="meta">
                    {{ $deceased->row_number ? 'Baris ' . $deceased->row_number . ' - ' : '' }}Plot {{ $deceased->plot_number ?? '-' }} &bull; {{ $deceased->block_name ?? 'Blok tidak tersedia' }}
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
