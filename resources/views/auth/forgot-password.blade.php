<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password | {{ config('app.name', 'Website Kuburan') }}</title>
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
            width: min(100%, 420px);
            background: linear-gradient(130deg, rgba(122, 17, 41, 0.09), rgba(255, 255, 255, 0)), var(--card);
            border: 1px solid var(--line);
            border-radius: 18px;
            padding: 30px 24px 24px;
            box-shadow: 0 24px 48px rgba(22, 4, 9, 0.28);
        }

        h1 {
            margin: 0 0 10px;
            text-align: center;
            font-family: "Space Grotesk", sans-serif;
            font-size: 1.9rem;
            line-height: 1;
            font-weight: 700;
            color: var(--brand);
        }

        .sub {
            margin: 0 0 18px;
            text-align: center;
            color: var(--muted);
            font-size: 0.9rem;
            line-height: 1.5;
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

        label { font-size: 0.9rem; color: #4a3940; font-weight: 700; }

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

        .btn-wrap {
            display: flex;
            justify-content: center;
            margin-top: 8px;
        }

        .btn {
            border: 1px solid #6d0f24;
            border-radius: 10px;
            min-width: 170px;
            min-height: 42px;
            padding: 0 22px;
            font-size: 1rem;
            font-weight: 700;
            color: #fff8f6;
            background: linear-gradient(130deg, #a52142 0%, var(--brand) 52%, #4a0615 100%);
            cursor: pointer;
            box-shadow: 0 10px 22px rgba(84, 9, 24, 0.24);
        }

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
    </style>
</head>
<body>
    <main class="shell">
        <h1>Lupa Password</h1>
        <p class="sub">Masukkan email terdaftar. Kami akan kirim link untuk reset password Anda.</p>

        @if (session('status'))
            <div class="msg msg-ok">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="msg msg-err">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="field">
                <label for="email">Email:</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="Masukan Email" autocomplete="email" required autofocus>
            </div>

            <div class="btn-wrap">
                <button class="btn" type="submit">Kirim Link Reset</button>
            </div>
        </form>

        <p class="link-line">
            <a href="{{ route('login') }}">Kembali ke Login</a>
        </p>
    </main>
</body>
</html>
