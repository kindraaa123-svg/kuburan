<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 | Halaman Tidak Ditemukan</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Sora:wght@600;700&display=swap');

        :root {
            --bg: #ecf2ef;
            --surface: #ffffff;
            --line: #c9d8d2;
            --text: #173238;
            --muted: #60767b;
            --accent: #1f7a67;
            --info: #3e6ba3;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 18px;
            font-family: "Manrope", sans-serif;
            color: var(--text);
            background:
                radial-gradient(760px 440px at -8% -20%, #d4e8df 0%, transparent 64%),
                radial-gradient(760px 440px at 108% 120%, #deebe5 0%, transparent 64%),
                var(--bg);
        }

        .card {
            width: min(760px, 96vw);
            border-radius: 22px;
            border: 1px solid var(--line);
            background: var(--surface);
            box-shadow: 0 24px 48px rgba(15, 47, 51, 0.12);
            overflow: hidden;
        }

        .head {
            padding: 26px 28px 18px;
            border-bottom: 1px solid #d8e2de;
            background: linear-gradient(150deg, rgba(62, 107, 163, 0.15), rgba(255, 255, 255, 0));
        }

        .code {
            margin: 0;
            font-family: "Sora", sans-serif;
            font-size: clamp(2.2rem, 7vw, 4rem);
            line-height: 1;
        }

        .title {
            margin: 10px 0 0;
            font-size: clamp(1.2rem, 3vw, 1.7rem);
            font-family: "Sora", sans-serif;
        }

        .body {
            padding: 18px 28px 26px;
        }

        .body p {
            margin: 0;
            color: var(--muted);
            line-height: 1.6;
            font-size: .95rem;
            max-width: 590px;
        }

        .actions {
            margin-top: 18px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            min-height: 42px;
            border-radius: 10px;
            border: 1px solid;
            padding: 0 14px;
            font-weight: 800;
            font-size: .82rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-primary {
            border-color: #1b6f5d;
            color: #fff;
            background: linear-gradient(130deg, #2a9a82, var(--accent));
        }

        .btn-secondary {
            border-color: #bfd0ca;
            color: #234149;
            background: #f7fbf9;
        }
    </style>
</head>
<body>
    <main class="card">
        <section class="head">
            <h1 class="code">404</h1>
            <h2 class="title">Halaman Tidak Ditemukan</h2>
        </section>
        <section class="body">
            <p>
                Halaman yang kamu cari tidak tersedia atau URL-nya salah.
                Periksa kembali alamat halaman, lalu coba lagi dari menu utama.
            </p>
            <div class="actions">
                <a class="btn btn-primary" href="{{ url('/') }}">Kembali ke Beranda</a>
                <a class="btn btn-secondary" href="{{ url()->previous() }}">Halaman Sebelumnya</a>
            </div>
        </section>
    </main>
</body>
</html>
