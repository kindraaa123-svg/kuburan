<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Website Kuburan') }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Manrope:wght@400;500;700;800&display=swap');

        :root {
            --bg-top: #2c0915;
            --bg-bottom: #080306;
            --line: rgba(255, 225, 230, 0.14);
            --text: #fff2ee;
            --muted: #dfbec3;
            --panel-bg: rgba(24, 7, 13, 0.54);
            --panel-border: rgba(255, 231, 235, 0.14);
            --card-bg: rgba(255, 241, 243, 0.08);
            --card-border: rgba(255, 231, 235, 0.16);
            --brand: #7a1129;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Manrope", sans-serif;
            color: var(--text);
            background:
                radial-gradient(920px 520px at -8% -10%, rgba(170, 44, 73, 0.4) 0%, transparent 58%),
                radial-gradient(760px 440px at 106% 110%, rgba(124, 18, 43, 0.4) 0%, transparent 64%),
                linear-gradient(180deg, var(--bg-top) 0%, #14070d 45%, var(--bg-bottom) 100%);
        }

        .shell {
            width: min(1160px, calc(100vw - 28px));
            margin: 14px auto;
            min-height: calc(100vh - 28px);
            border-radius: 28px;
            border: 1px solid var(--line);
            background: var(--panel-bg);
            box-shadow: 0 34px 80px rgba(2, 0, 1, 0.45);
            backdrop-filter: blur(10px);
            display: grid;
            grid-template-rows: auto 1fr;
            overflow: hidden;
        }

        .topbar {
            padding: 14px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            border-bottom: 1px solid var(--panel-border);
            background: linear-gradient(180deg, rgba(255, 241, 243, 0.08), rgba(255, 241, 243, 0));
        }

        .brand {
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            font-size: 0.76rem;
            color: #f6c5ca;
        }

        .nav {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .nav a {
            text-decoration: none;
            font-size: 0.78rem;
            font-weight: 700;
            color: #fff2ee;
            border: 1px solid var(--panel-border);
            border-radius: 10px;
            padding: 8px 11px;
            background: rgba(255, 241, 243, 0.06);
        }

        .nav a.primary {
            border-color: #6d0f24;
            background: linear-gradient(130deg, #a52142 0%, var(--brand) 52%, #4a0615 100%);
        }

        .hero {
            padding: 26px 22px;
            display: grid;
            grid-template-columns: minmax(0, 1.08fr) minmax(300px, 0.92fr);
            gap: 16px;
            align-items: center;
        }

        .hero-copy h1 {
            margin: 0;
            font-family: "Cormorant Garamond", serif;
            font-size: clamp(2.5rem, 5vw, 4.4rem);
            font-weight: 600;
            line-height: 0.95;
            letter-spacing: -0.03em;
        }

        .hero-copy p {
            margin: 14px 0 0;
            color: var(--muted);
            line-height: 1.75;
            max-width: 620px;
            font-size: 0.95rem;
        }

        .quick {
            margin-top: 18px;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
        }

        .quick article {
            border: 1px solid var(--card-border);
            border-radius: 14px;
            background: var(--card-bg);
            padding: 11px;
        }

        .quick strong {
            display: block;
            margin-bottom: 4px;
            font-size: 0.78rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: #ffd7d8;
        }

        .quick span {
            color: var(--muted);
            font-size: 0.78rem;
            line-height: 1.55;
        }

        .visual {
            border: 1px solid var(--card-border);
            border-radius: 20px;
            min-height: 330px;
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(circle at 72% 18%, rgba(255, 238, 231, 0.95) 0 7%, rgba(255, 220, 211, 0.26) 8%, transparent 19%),
                radial-gradient(circle at 18% 20%, rgba(240, 117, 132, 0.15) 0%, transparent 28%),
                linear-gradient(180deg, #5b1324 0%, #300a15 56%, #13060b 100%);
        }

        .visual::before {
            content: "";
            position: absolute;
            inset: auto -4% 0 -4%;
            height: 48%;
            background:
                radial-gradient(circle at 10% 44%, rgba(255, 255, 255, 0.05) 0 8%, transparent 9%),
                radial-gradient(circle at 68% 38%, rgba(255, 255, 255, 0.05) 0 7%, transparent 8%),
                linear-gradient(180deg, rgba(14, 5, 8, 0) 0%, rgba(14, 5, 8, 0.86) 34%, rgba(9, 3, 5, 1) 100%);
        }

        .visual .badge {
            position: absolute;
            top: 12px;
            left: 12px;
            border: 1px solid rgba(255, 229, 233, 0.22);
            background: rgba(255, 247, 248, 0.12);
            padding: 7px 10px;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #ffe9ea;
        }

        @media (max-width: 900px) {
            .hero {
                grid-template-columns: 1fr;
            }

            .quick {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <main class="shell">
        <header class="topbar">
            <div class="brand">{{ config('app.name', 'Website Kuburan') }}</div>
            <nav class="nav">
                @if (Route::has('login'))
                    @auth
                        <a class="primary" href="{{ url('/dashboard') }}">Dashboard</a>
                    @else
                        <a class="primary" href="{{ route('login') }}">Login</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                @endif
            </nav>
        </header>

        <section class="hero">
            <div class="hero-copy">
                <h1>Sistem Informasi Denah Makam</h1>
                <p>Platform pengelolaan data blok, plot, almarhum, dan kontak keluarga dalam satu dashboard bernuansa maroon yang tegas, terstruktur, dan fokus operasional.</p>
                <div class="quick">
                    <article>
                        <strong>Denah Blok</strong>
                        <span>Kelola struktur area pemakaman per blok.</span>
                    </article>
                    <article>
                        <strong>Data Almarhum</strong>
                        <span>Atur data penghuni plot secara cepat dan rapi.</span>
                    </article>
                    <article>
                        <strong>Kontak Keluarga</strong>
                        <span>Simpan relasi keluarga untuk kebutuhan layanan.</span>
                    </article>
                </div>
            </div>
            <aside class="visual" aria-hidden="true">
                <span class="badge">Cemetery Portal</span>
            </aside>
        </section>
    </main>
</body>
</html>
