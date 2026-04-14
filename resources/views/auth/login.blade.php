<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Sora:wght@600;700&display=swap');

        :root {
            --bg: #ebf0ee;
            --surface: #ffffff;
            --line: #cfd9d4;
            --text: #132f35;
            --muted: #60767b;
            --brand: #1b7a63;
            --brand-2: #2c9d84;
            --alert: #c44a4a;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 16px;
            font-family: "Manrope", sans-serif;
            color: var(--text);
            background:
                radial-gradient(900px 520px at -12% -20%, #d3e7df 0%, transparent 62%),
                radial-gradient(860px 500px at 112% 120%, #d9e8e2 0%, transparent 62%),
                var(--bg);
        }

        .shell {
            width: min(980px, 96vw);
            border: 1px solid var(--line);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 24px 50px rgba(16, 47, 53, 0.14);
            background: var(--surface);
            display: grid;
            grid-template-columns: 1.05fr .95fr;
        }

        .hero {
            padding: 30px;
            background:
                linear-gradient(155deg, rgba(27, 122, 99, 0.14), rgba(255, 255, 255, 0) 58%),
                linear-gradient(30deg, rgba(19, 47, 53, 0.08), rgba(255, 255, 255, 0) 50%),
                #f7fbf9;
            border-right: 1px solid #d8e2dd;
            display: grid;
            align-content: space-between;
            gap: 18px;
        }

        .eyebrow {
            margin: 0;
            font-size: .74rem;
            letter-spacing: .16em;
            font-weight: 800;
            color: #3c666b;
        }

        .hero h1 {
            margin: 10px 0 0;
            font-family: "Sora", sans-serif;
            line-height: 1.14;
            font-size: clamp(1.7rem, 3vw, 2.3rem);
        }

        .hero p {
            margin: 10px 0 0;
            color: var(--muted);
            max-width: 420px;
            font-size: .95rem;
        }

        .feature {
            border: 1px solid #d7e1dc;
            border-radius: 14px;
            background: #fff;
            padding: 12px 13px;
        }

        .feature strong {
            display: block;
            font-size: .82rem;
            margin-bottom: 4px;
        }

        .feature span {
            font-size: .8rem;
            color: #5d767b;
        }

        .panel {
            padding: 26px 24px;
            display: grid;
            align-content: center;
        }

        .panel h2 {
            margin: 0;
            font-family: "Sora", sans-serif;
            font-size: 1.3rem;
        }

        .sub {
            margin: 8px 0 0;
            color: var(--muted);
            font-size: .87rem;
        }

        .msg {
            margin-top: 14px;
            border-radius: 10px;
            padding: 10px 11px;
            font-size: .8rem;
            font-weight: 700;
            border: 1px solid;
        }

        .msg-ok {
            background: #e6f3ee;
            border-color: #b8d4c8;
            color: #1a5547;
        }

        .msg-err {
            background: #fdeeee;
            border-color: #e4b9b9;
            color: var(--alert);
        }

        form {
            margin-top: 16px;
            display: grid;
            gap: 12px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-size: .77rem;
            font-weight: 800;
            color: #5b7479;
        }

        input {
            width: 100%;
            min-height: 46px;
            border: 1px solid #c6d6d0;
            border-radius: 11px;
            background: #fcfefd;
            padding: 0 12px;
            font-size: .95rem;
            color: var(--text);
        }

        input:focus {
            outline: none;
            border-color: #4b9683;
            box-shadow: 0 0 0 3px rgba(31, 122, 99, 0.14);
        }

        .captcha-widget {
            min-height: 78px;
            display: flex;
            align-items: center;
        }

        .btn {
            margin-top: 4px;
            min-height: 48px;
            border: 1px solid #1c6e5a;
            border-radius: 12px;
            background: linear-gradient(125deg, var(--brand-2), var(--brand));
            color: #fff;
            font-size: .92rem;
            font-weight: 800;
            cursor: pointer;
        }

        .hint {
            margin-top: 12px;
            color: #6c8388;
            font-size: .76rem;
            text-align: center;
        }

        @media (max-width: 860px) {
            .shell {
                grid-template-columns: 1fr;
            }

            .hero {
                border-right: 0;
                border-bottom: 1px solid #d8e2dd;
            }
        }
    </style>
</head>
<body>
    <main class="shell">
        <section class="hero">
            <div>
                <p class="eyebrow">CEMETERY SYSTEM</p>
                <h1>Portal Login Pengelola Denah Kuburan</h1>
                <p>Masuk dengan akun user untuk mengakses dashboard peta blok, detail almarhum, dan data keluarga.</p>
            </div>

            <div class="feature">
                <strong>Akses Cepat</strong>
                <span>Setelah login kamu langsung diarahkan ke halaman <code>/dashboard</code>.</span>
            </div>
        </section>

        <section class="panel">
            <h2>Masuk Akun</h2>
            <p class="sub">Gunakan username dan password akun yang sudah terdaftar.</p>

            @if (session('status'))
                <div class="msg msg-ok">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="msg msg-err">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf
                <input type="hidden" name="latitude" id="latitudeInput" value="{{ old('latitude') }}">
                <input type="hidden" name="longitude" id="longitudeInput" value="{{ old('longitude') }}">
                <div>
                    <label for="username">Username</label>
                    <input id="username" name="username" type="text" value="{{ old('username') }}" required autofocus>
                </div>
                <div>
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" required>
                </div>
                <div class="captcha-widget">
                    @if (config('services.recaptcha.site_key'))
                        <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                    @else
                        <div class="msg msg-err" style="margin:0;">Google reCAPTCHA belum dikonfigurasi.</div>
                    @endif
                </div>
                <button class="btn" type="submit">Login Sekarang</button>
            </form>

            <p class="hint">Jika gagal login, pastikan kembali username dan password yang dimasukkan.</p>
        </section>
    </main>
    @if (config('services.recaptcha.site_key'))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
    <script>
        (() => {
            const latitudeInput = document.getElementById('latitudeInput');
            const longitudeInput = document.getElementById('longitudeInput');

            if (!latitudeInput || !longitudeInput || !('geolocation' in navigator)) {
                return;
            }

            const hasOldValue = latitudeInput.value !== '' || longitudeInput.value !== '';
            if (hasOldValue) {
                return;
            }

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    latitudeInput.value = String(position.coords.latitude);
                    longitudeInput.value = String(position.coords.longitude);
                },
                () => {
                    latitudeInput.value = '';
                    longitudeInput.value = '';
                },
                {
                    enableHighAccuracy: false,
                    timeout: 10000,
                    maximumAge: 120000,
                }
            );
        })();
    </script>
</body>
</html>
