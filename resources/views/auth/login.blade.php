<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | {{ config('app.name', 'Website Kuburan') }}</title>
        <style>
        @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&family=Space+Grotesk:wght@500;700&display=swap');

        :root {
            --bg-top: #2c0915;
            --bg-bottom: #080306;
            --card: rgba(247, 239, 236, 0.98);
            --text: #2b1017;
            --muted: #77525b;
            --line: rgba(115, 38, 54, 0.28);
            --brand: #7a1129;
            --brand-strong: #5f0d21;
            --ok: #255744;
            --err: #92223b;
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
                radial-gradient(920px 520px at -8% -10%, rgba(170, 44, 73, 0.4) 0%, transparent 58%),
                radial-gradient(760px 440px at 106% 110%, rgba(124, 18, 43, 0.4) 0%, transparent 64%),
                linear-gradient(180deg, var(--bg-top) 0%, #14070d 45%, var(--bg-bottom) 100%);
        }

        .shell {
            width: min(100%, 380px);
            background:
                linear-gradient(130deg, rgba(122, 17, 41, 0.09), rgba(255, 255, 255, 0)),
                var(--card);
            border: 1px solid var(--line);
            border-radius: 18px;
            padding: 30px 24px 24px;
            box-shadow: 0 24px 48px rgba(22, 4, 9, 0.28);
        }

        h1 {
            margin: 0 0 24px;
            text-align: center;
            font-family: "Space Grotesk", sans-serif;
            font-size: 2rem;
            line-height: 1;
            font-weight: 700;
            color: var(--brand);
            letter-spacing: 0.01em;
        }

        .msg {
            margin-bottom: 12px;
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 0.88rem;
            font-weight: 700;
        }

        .msg-ok { background: rgba(37, 87, 68, 0.1); color: var(--ok); }
        .msg-err { background: rgba(146, 34, 59, 0.1); color: var(--err); }

        form { display: grid; gap: 14px; }

        .field { display: grid; gap: 8px; }

        label {
            font-size: 0.9rem;
            color: #4a3940;
            font-weight: 700;
        }

        input {
            width: 100%;
            border: 0;
            border-bottom: 2px solid rgba(115, 38, 54, 0.38);
            background: transparent;
            min-height: 40px;
            padding: 6px 0;
            font-size: 1rem;
            color: var(--text);
            transition: border-color 0.2s ease;
        }

        input:focus {
            outline: none;
            border-bottom-color: var(--brand);
        }

        input::placeholder { color: #9f8f95; }

        .sub {
            margin: -2px 0 0;
            color: var(--muted);
            font-size: 0.86rem;
            line-height: 1.5;
        }

        .captcha-widget { margin-top: 2px; }

        .captcha-offline {
            display: grid;
            gap: 8px;
        }

        .captcha-question {
            margin: 0;
            color: var(--muted);
            font-size: 0.85rem;
        }

        .btn-wrap {
            display: flex;
            justify-content: center;
            margin-top: 8px;
        }

        .btn {
            border: 1px solid #6d0f24;
            border-radius: 10px;
            min-width: 132px;
            min-height: 42px;
            padding: 0 22px;
            font-size: 1rem;
            font-weight: 700;
            color: #fff8f6;
            background: linear-gradient(130deg, #a52142 0%, var(--brand) 52%, #4a0615 100%);
            cursor: pointer;
            box-shadow: 0 10px 22px rgba(84, 9, 24, 0.24);
        }

        .btn:hover { filter: brightness(1.04); }

        .link-line {
            margin-top: 14px;
            text-align: center;
            font-size: 0.84rem;
        }

        .link-line a {
            color: var(--brand-strong);
            text-decoration: none;
            border-bottom: 1px solid rgba(122, 17, 41, 0.34);
            font-weight: 700;
        }

        .captcha-frame { transform-origin: left top; }

        @media (max-width: 380px) {
            .shell { padding: 26px 18px 20px; }
            h1 { font-size: 1.8rem; }
            .captcha-frame { transform: scale(0.9); }
        }
    </style>
</head>
<body>
    <main class="shell">
        <h1>Login</h1>

        @if (session('status'))
            <div class="msg msg-ok">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="msg msg-err">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}" id="loginMethodForm">
            @csrf
            <input type="hidden" name="latitude" id="latitudeInput" value="{{ old('latitude') }}">
            <input type="hidden" name="longitude" id="longitudeInput" value="{{ old('longitude') }}">
            <input type="hidden" name="captcha_mode" id="captchaModeInput" value="offline">

            <div class="field">
                <label for="username">Email:</label>
                <input id="username" name="username" type="text" value="{{ old('username') }}" placeholder="Masukan Email" autocomplete="username" required autofocus>
            </div>

            <div class="field">
                <label for="password">Password:</label>
                <input id="password" name="password" type="password" placeholder="Masukan Password" autocomplete="current-password" required>
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
                    <label for="offline_captcha_answer">Captcha Offline:</label>
                    <p class="captcha-question">Berapa hasil dari <strong>{{ session('offline_captcha_question', '1 + 1') }}</strong> ?</p>
                    <input id="offline_captcha_answer" name="offline_captcha_answer" type="text" inputmode="numeric" placeholder="Jawaban captcha" autocomplete="off">
                </div>
            </div>

            <div class="btn-wrap">
                <button class="btn" type="submit" id="passwordLoginBtn">Login</button>
            </div>
        </form>

        <p class="link-line">
            <a href="{{ route('login.otp') }}">Login dengan OTP Email</a>
        </p>
        <p class="link-line" style="margin-top:8px;">
            <a href="{{ route('password.forgot') }}">Lupa Password?</a>
        </p>
    </main>

    @if (config('services.recaptcha.site_key'))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
    <script>
        (() => {
            const latitudeInput = document.getElementById('latitudeInput');
            const longitudeInput = document.getElementById('longitudeInput');
            const loginMethodForm = document.getElementById('loginMethodForm');
            const passwordLoginBtn = document.getElementById('passwordLoginBtn');
            const passwordInput = document.getElementById('password');
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

            if (captchaModeInput) {
                syncCaptchaMode();
                window.addEventListener('online', syncCaptchaMode);
                window.addEventListener('offline', syncCaptchaMode);
            }

            if (loginMethodForm && passwordLoginBtn && passwordInput) {
                loginMethodForm.addEventListener('submit', (event) => {
                    const submitter = event.submitter;
                    const isPasswordLogin = submitter === passwordLoginBtn;
                    if (isPasswordLogin && passwordInput.value.trim() === '') {
                        event.preventDefault();
                        window.alert('Password wajib diisi untuk login password.');
                        passwordInput.focus();
                    }
                });
            }

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



