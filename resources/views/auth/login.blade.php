<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | {{ config('app.name', 'Website Kuburan') }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Manrope:wght@400;500;700;800&display=swap');

        :root {
            --bg-top: #2c0915;
            --bg-bottom: #080306;
            --shell-line: rgba(255, 225, 230, 0.12);
            --hero-text: #fff2ee;
            --hero-muted: #dfbec3;
            --hero-card: rgba(255, 240, 242, 0.08);
            --panel-bg: #f7efec;
            --panel-line: rgba(115, 38, 54, 0.18);
            --panel-text: #2b1017;
            --panel-muted: #77525b;
            --input-bg: rgba(255, 253, 252, 0.88);
            --brand: #7a1129;
            --brand-strong: #540918;
            --brand-soft: #d89aa5;
            --success: #255744;
            --danger: #92223b;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 22px;
            font-family: "Manrope", sans-serif;
            color: var(--panel-text);
            background:
                radial-gradient(900px 520px at -12% -18%, rgba(170, 44, 73, 0.44) 0%, transparent 60%),
                radial-gradient(760px 440px at 110% 0%, rgba(124, 18, 43, 0.5) 0%, transparent 62%),
                linear-gradient(180deg, var(--bg-top) 0%, #14070d 48%, var(--bg-bottom) 100%);
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background:
                linear-gradient(rgba(255, 255, 255, 0.028) 1px, transparent 1px) 0 0 / 100% 76px,
                linear-gradient(90deg, rgba(255, 255, 255, 0.022) 1px, transparent 1px) 0 0 / 76px 100%;
            opacity: 0.35;
            pointer-events: none;
        }

        body::after {
            content: "";
            position: fixed;
            inset: auto -10% -120px -10%;
            height: 240px;
            background: radial-gradient(circle at center, rgba(0, 0, 0, 0.45) 0%, rgba(0, 0, 0, 0) 70%);
            pointer-events: none;
        }

        .shell {
            width: min(520px, 100%);
            display: grid;
            grid-template-columns: 1fr;
            border-radius: 30px;
            overflow: hidden;
            border: 1px solid var(--shell-line);
            background: rgba(24, 7, 13, 0.5);
            box-shadow: 0 34px 80px rgba(2, 0, 1, 0.45);
            backdrop-filter: blur(10px);
            position: relative;
            z-index: 1;
        }

        .hero,
        .panel {
            min-width: 0;
        }

        .hero {
            padding: 34px 34px 30px;
            color: var(--hero-text);
            background:
                radial-gradient(circle at 80% 12%, rgba(255, 217, 208, 0.18) 0%, transparent 24%),
                linear-gradient(180deg, rgba(115, 17, 41, 0.95) 0%, rgba(50, 11, 22, 0.98) 56%, rgba(15, 5, 10, 1) 100%);
            border-right: 1px solid rgba(255, 231, 235, 0.12);
            display: grid;
            align-content: space-between;
            gap: 26px;
        }

        .hero-top {
            display: grid;
            gap: 16px;
        }

        .brand-chip {
            width: fit-content;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 999px;
            border: 1px solid rgba(255, 229, 233, 0.18);
            background: rgba(255, 247, 248, 0.08);
            font-size: 0.76rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .brand-mark {
            width: 18px;
            height: 18px;
            border-radius: 999px;
            border: 1px solid rgba(255, 236, 239, 0.42);
            position: relative;
            background: radial-gradient(circle at 32% 32%, #ffd4cc 0%, #e88f92 42%, #761527 100%);
            box-shadow: 0 0 20px rgba(255, 194, 182, 0.18);
        }

        .brand-mark::before,
        .brand-mark::after {
            content: "";
            position: absolute;
            left: 50%;
            top: 50%;
            background: rgba(255, 247, 244, 0.92);
            transform: translate(-50%, -50%);
            border-radius: 999px;
        }

        .brand-mark::before {
            width: 2px;
            height: 10px;
        }

        .brand-mark::after {
            width: 10px;
            height: 2px;
        }

        .eyebrow {
            margin: 0;
            font-size: 0.76rem;
            font-weight: 800;
            letter-spacing: 0.24em;
            color: #f6c5ca;
            text-transform: uppercase;
        }

        .hero h1 {
            margin: 0;
            max-width: 560px;
            font-family: "Cormorant Garamond", serif;
            font-size: clamp(3rem, 5vw, 4.7rem);
            font-weight: 600;
            line-height: 0.95;
            letter-spacing: -0.03em;
        }

        .hero-lead {
            margin: 0;
            max-width: 540px;
            color: var(--hero-muted);
            font-size: 1rem;
            line-height: 1.8;
        }

        .cemetery {
            position: relative;
            min-height: 290px;
            overflow: hidden;
            border-radius: 28px;
            border: 1px solid rgba(255, 236, 238, 0.12);
            background:
                radial-gradient(circle at 72% 18%, rgba(255, 238, 231, 0.95) 0 7%, rgba(255, 220, 211, 0.26) 8%, transparent 19%),
                radial-gradient(circle at 18% 20%, rgba(240, 117, 132, 0.15) 0%, transparent 28%),
                linear-gradient(180deg, #5b1324 0%, #300a15 56%, #13060b 100%);
            box-shadow:
                inset 0 1px 0 rgba(255, 255, 255, 0.12),
                0 24px 38px rgba(0, 0, 0, 0.22);
        }

        .cemetery::before {
            content: "";
            position: absolute;
            inset: auto -4% 0 -4%;
            height: 48%;
            background:
                radial-gradient(circle at 10% 44%, rgba(255, 255, 255, 0.05) 0 8%, transparent 9%),
                radial-gradient(circle at 68% 38%, rgba(255, 255, 255, 0.05) 0 7%, transparent 8%),
                linear-gradient(180deg, rgba(14, 5, 8, 0) 0%, rgba(14, 5, 8, 0.86) 34%, rgba(9, 3, 5, 1) 100%);
        }

        .cemetery::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.04), rgba(255, 255, 255, 0));
            pointer-events: none;
        }

        .mist {
            position: absolute;
            border-radius: 999px;
            background: rgba(255, 232, 230, 0.09);
            filter: blur(10px);
        }

        .mist-a {
            width: 180px;
            height: 40px;
            left: 24px;
            bottom: 92px;
        }

        .mist-b {
            width: 220px;
            height: 48px;
            right: 34px;
            bottom: 74px;
        }

        .mist-c {
            width: 140px;
            height: 30px;
            left: 46%;
            bottom: 120px;
        }

        .grave {
            position: absolute;
            bottom: 48px;
            border-radius: 24px 24px 10px 10px;
            border: 1px solid rgba(255, 244, 245, 0.12);
            background: linear-gradient(180deg, #8f7d84 0%, #3e2b33 100%);
            box-shadow: 0 14px 22px rgba(0, 0, 0, 0.26);
        }

        .grave::before {
            content: "";
            position: absolute;
            left: 18px;
            right: 18px;
            top: 18px;
            height: 2px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.24);
        }

        .grave-a {
            left: 34px;
            width: 62px;
            height: 84px;
        }

        .grave-b {
            left: 108px;
            width: 52px;
            height: 70px;
        }

        .grave-c {
            left: 54%;
            width: 70px;
            height: 96px;
            bottom: 56px;
        }

        .grave-d {
            right: 84px;
            width: 58px;
            height: 78px;
            bottom: 52px;
        }

        .grave-e {
            right: 28px;
            width: 44px;
            height: 62px;
        }

        .grave-cross::after {
            content: "";
            position: absolute;
            left: 50%;
            top: 10px;
            width: 2px;
            height: 20px;
            transform: translateX(-50%);
            background: #f2d4cb;
            box-shadow: 0 0 0 1px rgba(242, 212, 203, 0.1);
        }

        .grave-cross i {
            position: absolute;
            left: 50%;
            top: 17px;
            width: 14px;
            height: 2px;
            transform: translateX(-50%);
            border-radius: 999px;
            background: #f2d4cb;
            display: block;
            font-style: normal;
        }

        .mausoleum {
            position: absolute;
            left: 34%;
            bottom: 54px;
            width: 110px;
            height: 84px;
            border-radius: 12px 12px 8px 8px;
            border: 1px solid rgba(255, 242, 244, 0.12);
            background:
                linear-gradient(180deg, rgba(240, 222, 220, 0.18), rgba(255, 255, 255, 0) 22%),
                linear-gradient(180deg, #4f3942 0%, #24171d 100%);
            box-shadow: 0 18px 24px rgba(0, 0, 0, 0.26);
        }

        .mausoleum::before {
            content: "";
            position: absolute;
            left: 12px;
            right: 12px;
            top: -22px;
            height: 24px;
            background: linear-gradient(180deg, #66505a 0%, #2f2027 100%);
            clip-path: polygon(50% 0%, 100% 100%, 0% 100%);
        }

        .mausoleum::after {
            content: "";
            position: absolute;
            left: 38px;
            right: 38px;
            bottom: 0;
            height: 46px;
            border-radius: 22px 22px 0 0;
            background: linear-gradient(180deg, #1f1117 0%, #090406 100%);
            border: 1px solid rgba(255, 244, 245, 0.08);
        }

        .pathway {
            position: absolute;
            left: 50%;
            bottom: -20px;
            width: 140px;
            height: 180px;
            transform: translateX(-50%);
            background: linear-gradient(180deg, rgba(255, 233, 224, 0.16), rgba(255, 233, 224, 0.04) 30%, rgba(255, 233, 224, 0));
            clip-path: polygon(48% 0%, 76% 0%, 100% 100%, 0% 100%);
            opacity: 0.6;
        }

        .hero-cards {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }

        .hero-card {
            padding: 14px 14px 15px;
            border-radius: 18px;
            border: 1px solid rgba(255, 233, 237, 0.12);
            background: var(--hero-card);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.06);
        }

        .hero-card strong {
            display: block;
            margin-bottom: 6px;
            font-size: 0.82rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #ffd7d8;
        }

        .hero-card span {
            font-size: 0.82rem;
            line-height: 1.6;
            color: var(--hero-muted);
        }

        .panel {
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.24), rgba(255, 255, 255, 0) 18%),
                var(--panel-bg);
            padding: 28px;
            display: grid;
            align-items: center;
        }

        .panel-shell {
            width: min(100%, 430px);
            margin: 0 auto;
            display: grid;
            gap: 16px;
        }

        .panel-head {
            display: grid;
            gap: 8px;
        }

        .access-tag {
            width: fit-content;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            border: 1px solid rgba(122, 17, 41, 0.16);
            background: rgba(122, 17, 41, 0.08);
            font-size: 0.76rem;
            font-weight: 800;
            color: var(--brand);
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .access-tag::before {
            content: "";
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: linear-gradient(180deg, #c4405f, #6f0f26);
            box-shadow: 0 0 0 4px rgba(122, 17, 41, 0.12);
        }

        .panel h2 {
            margin: 0;
            font-family: "Cormorant Garamond", serif;
            font-size: clamp(2.2rem, 5vw, 3rem);
            line-height: 0.95;
            font-weight: 600;
            letter-spacing: -0.02em;
        }

        .sub {
            margin: 0;
            color: var(--panel-muted);
            font-size: 0.92rem;
            line-height: 1.7;
        }

        .msg {
            border-radius: 16px;
            padding: 13px 14px;
            font-size: 0.84rem;
            font-weight: 700;
            line-height: 1.55;
            border: 1px solid transparent;
        }

        .msg-ok {
            border-color: rgba(37, 87, 68, 0.18);
            background: rgba(37, 87, 68, 0.08);
            color: var(--success);
        }

        .msg-err {
            border-color: rgba(146, 34, 59, 0.18);
            background: rgba(146, 34, 59, 0.08);
            color: var(--danger);
        }

        form {
            display: grid;
            gap: 14px;
        }

        .field {
            display: grid;
            gap: 7px;
        }

        label {
            font-size: 0.77rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #6c4650;
        }

        input {
            width: 100%;
            min-height: 52px;
            border-radius: 16px;
            border: 1px solid rgba(110, 52, 63, 0.18);
            background: var(--input-bg);
            padding: 0 15px;
            font-size: 0.96rem;
            color: var(--panel-text);
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }

        input::placeholder {
            color: #aa8a91;
        }

        input:focus {
            outline: none;
            border-color: rgba(122, 17, 41, 0.48);
            box-shadow: 0 0 0 4px rgba(122, 17, 41, 0.12);
            transform: translateY(-1px);
        }

        .captcha-widget {
            min-height: 86px;
            display: flex;
            align-items: flex-start;
        }

        .captcha-frame {
            transform-origin: left top;
        }

        .captcha-offline {
            width: 100%;
            display: grid;
            gap: 8px;
        }

        .captcha-offline label {
            margin: 0;
        }

        .captcha-question {
            margin: 0;
            color: var(--panel-muted);
            font-size: 0.84rem;
            font-weight: 700;
            line-height: 1.4;
        }

        .btn {
            min-height: 56px;
            border: 0;
            border-radius: 18px;
            background:
                linear-gradient(135deg, #a52142 0%, #7a1129 48%, #4a0615 100%);
            color: #fff8f6;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            padding: 0 18px;
            font-size: 0.94rem;
            font-weight: 800;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            cursor: pointer;
            box-shadow: 0 18px 28px rgba(84, 9, 24, 0.24);
            transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 22px 30px rgba(84, 9, 24, 0.28);
            filter: saturate(1.05);
        }

        .btn:focus-visible {
            outline: 3px solid rgba(122, 17, 41, 0.18);
            outline-offset: 3px;
        }

        .btn-arrow {
            width: 34px;
            height: 34px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.14);
            font-size: 1rem;
            line-height: 1;
        }

        .panel-note {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 14px 15px;
            border-radius: 16px;
            border: 1px solid var(--panel-line);
            background: rgba(255, 255, 255, 0.36);
            color: var(--panel-muted);
            font-size: 0.82rem;
            line-height: 1.65;
        }

        .panel-note::before {
            content: "";
            width: 10px;
            height: 10px;
            margin-top: 0.28rem;
            border-radius: 999px;
            flex: 0 0 auto;
            background: linear-gradient(180deg, #c4405f, #6f0f26);
        }

        .hint {
            margin: 0;
            text-align: center;
            color: var(--panel-muted);
            font-size: 0.78rem;
            line-height: 1.7;
        }

        code {
            padding: 2px 6px;
            border-radius: 999px;
            background: rgba(122, 17, 41, 0.08);
            color: var(--brand-strong);
            font-size: 0.78rem;
        }

        @media (max-width: 980px) {
            .shell {
                grid-template-columns: 1fr;
            }

            .hero {
                border-right: 0;
                border-bottom: 1px solid rgba(255, 231, 235, 0.12);
            }
        }

        @media (max-width: 720px) {
            body {
                padding: 14px;
            }

            .hero,
            .panel {
                padding: 22px;
            }

            .hero-cards {
                grid-template-columns: 1fr;
            }

            .cemetery {
                min-height: 250px;
            }
        }

        @media (max-width: 420px) {
            .panel {
                padding: 20px 16px;
            }

            .hero {
                padding: 22px 18px;
            }

            .captcha-frame {
                transform: scale(0.92);
            }

            .captcha-widget {
                min-height: 78px;
            }
        }

        @media (max-width: 360px) {
            .captcha-frame {
                transform: scale(0.84);
            }

            .captcha-widget {
                min-height: 72px;
            }

            .btn {
                padding: 0 14px;
            }
        }
    </style>
</head>
<body>
    <main class="shell">
        <section class="panel">
            <div class="panel-shell">
                <div class="panel-head">
                    <span class="access-tag">Staff Access</span>
                    <h2>Masuk Akun</h2>
                    <p class="sub">Gunakan username dan password akun yang sudah terdaftar untuk membuka dashboard pengelolaan kuburan.</p>
                </div>

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
                    <input type="hidden" name="captcha_mode" id="captchaModeInput" value="offline">

                    <div class="field">
                        <label for="username">Username</label>
                        <input id="username" name="username" type="text" value="{{ old('username') }}" placeholder="Masukkan username" autocomplete="username" required autofocus>
                    </div>

                    <div class="field">
                        <label for="password">Password</label>
                        <input id="password" name="password" type="password" placeholder="Masukkan password" autocomplete="current-password" required>
                    </div>

                    <div class="captcha-widget">
                        @if (config('services.recaptcha.site_key') && config('services.recaptcha.secret_key'))
                            <div class="captcha-frame" id="onlineCaptchaWrap" style="display: none;">
                                <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                            </div>
                        @else
                            <div id="onlineCaptchaWrap" style="display: none;"></div>
                        @endif

                        <div class="captcha-offline" id="offlineCaptchaWrap">
                            <label for="offline_captcha_answer">Captcha Offline</label>
                            <p class="captcha-question">Berapa hasil dari <strong>{{ session('offline_captcha_question', '1 + 1') }}</strong> ?</p>
                            <input id="offline_captcha_answer" name="offline_captcha_answer" type="text" inputmode="numeric" placeholder="Jawaban captcha aritmatika" autocomplete="off">
                        </div>
                    </div>

                    <button class="btn" type="submit">
                        <span>Login</span>
                        <span class="btn-arrow" aria-hidden="true">-></span>
                    </button>
                </form>
            </div>
        </section>
    </main>

    @if (config('services.recaptcha.site_key'))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
    <script>
        (() => {
            const latitudeInput = document.getElementById('latitudeInput');
            const longitudeInput = document.getElementById('longitudeInput');
            const captchaModeInput = document.getElementById('captchaModeInput');
            const onlineCaptchaWrap = document.getElementById('onlineCaptchaWrap');
            const offlineCaptchaWrap = document.getElementById('offlineCaptchaWrap');
            const offlineCaptchaInput = document.getElementById('offline_captcha_answer');
            const hasGoogleCaptcha = Boolean(onlineCaptchaWrap && onlineCaptchaWrap.querySelector('.g-recaptcha'));

            const syncCaptchaMode = () => {
                const useOnlineCaptcha = hasGoogleCaptcha && navigator.onLine;

                if (onlineCaptchaWrap) {
                    onlineCaptchaWrap.style.display = useOnlineCaptcha ? '' : 'none';
                }

                if (offlineCaptchaWrap) {
                    offlineCaptchaWrap.style.display = useOnlineCaptcha ? 'none' : '';
                }

                if (offlineCaptchaInput) {
                    offlineCaptchaInput.required = !useOnlineCaptcha;
                    if (useOnlineCaptcha) {
                        offlineCaptchaInput.value = '';
                    }
                }

                if (captchaModeInput) {
                    captchaModeInput.value = useOnlineCaptcha ? 'online' : 'offline';
                }
            };

            syncCaptchaMode();
            window.addEventListener('online', syncCaptchaMode);
            window.addEventListener('offline', syncCaptchaMode);

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
