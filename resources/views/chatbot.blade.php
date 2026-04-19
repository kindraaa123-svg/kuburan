<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chatbot - {{ $setting->systemname ?: 'Denah Kuburan' }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&family=Space+Grotesk:wght@500;700&display=swap');

        :root {
            --bg-top: #2c0915;
            --bg-bottom: #080306;
            --card: rgba(247, 239, 236, 0.98);
            --line: rgba(115, 38, 54, 0.2);
            --text: #2b1017;
            --muted: #77525b;
            --brand: #7a1129;
            --brand-soft: rgba(122, 17, 41, 0.1);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Manrope", sans-serif;
            background:
                radial-gradient(920px 520px at -8% -10%, rgba(170, 44, 73, 0.4) 0%, transparent 58%),
                radial-gradient(760px 440px at 106% 110%, rgba(124, 18, 43, 0.4) 0%, transparent 64%),
                linear-gradient(180deg, var(--bg-top) 0%, #14070d 45%, var(--bg-bottom) 100%);
            color: var(--text);
        }

        .shell {
            width: min(960px, calc(100vw - 28px));
            margin: 24px auto;
        }

        .top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 12px;
        }

        .top h1 {
            margin: 0;
            font-family: "Space Grotesk", sans-serif;
            font-size: clamp(1.3rem, 2.8vw, 1.9rem);
            color: #fff2ee;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 36px;
            padding: 0 12px;
            border-radius: 10px;
            border: 1px solid rgba(255, 231, 235, 0.24);
            background: rgba(255, 241, 243, 0.08);
            color: #fff2ee;
            text-decoration: none;
            font-weight: 700;
            font-size: .88rem;
        }

        .chat-card {
            border: 1px solid var(--line);
            border-radius: 16px;
            background: var(--card);
            box-shadow: 0 24px 48px rgba(22, 4, 9, 0.24);
            overflow: hidden;
        }

        .chat-head {
            padding: 12px 14px;
            border-bottom: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.7);
        }

        .chat-head p {
            margin: 0;
            color: var(--muted);
            font-size: .86rem;
        }

        .chat-body {
            height: min(58vh, 520px);
            padding: 14px;
            overflow: auto;
            display: grid;
            gap: 10px;
            align-content: start;
            background: linear-gradient(180deg, #fdf6f7 0%, #ffffff 100%);
        }

        .bubble {
            max-width: min(78%, 640px);
            border-radius: 12px;
            padding: 10px 12px;
            font-size: .9rem;
            line-height: 1.35;
            border: 1px solid var(--line);
        }

        .bubble.bot {
            background: var(--brand-soft);
            border-color: rgba(122, 17, 41, 0.26);
            justify-self: start;
        }

        .bubble.user {
            background: rgba(122, 17, 41, 0.08);
            border-color: rgba(122, 17, 41, 0.22);
            justify-self: end;
            color: #5a2532;
        }

        .chat-form {
            display: flex;
            gap: 8px;
            padding: 12px;
            border-top: 1px solid var(--line);
            background: #fff;
        }

        .chat-input {
            flex: 1;
            min-height: 42px;
            border-radius: 10px;
            border: 1px solid rgba(110, 52, 63, 0.2);
            padding: 0 12px;
            font-family: inherit;
            font-size: .9rem;
            color: var(--text);
        }

        .chat-input:focus {
            outline: none;
            border-color: rgba(122, 17, 41, 0.46);
            box-shadow: 0 0 0 4px rgba(122, 17, 41, 0.1);
        }

        .chat-send {
            min-height: 42px;
            padding: 0 14px;
            border-radius: 10px;
            border: 1px solid #6d0f24;
            background: linear-gradient(130deg, #a52142 0%, var(--brand) 52%, #4a0615 100%);
            color: #fff;
            font-weight: 700;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <main class="shell">
        <div class="top">
            <h1>Chatbot {{ $setting->systemname ?: 'Denah Kuburan' }}</h1>
            <a class="back-link" href="{{ url('/') }}">Kembali ke Beranda</a>
        </div>

        <section class="chat-card">
            <header class="chat-head">
                <p>Tanyakan lokasi kuburan, info blok, atau plot kosong. Contoh: "Kuburan Bapak Ahmad di mana?"</p>
            </header>

            <div class="chat-body" id="chatBody">
                <div class="bubble bot">Halo, ada yang bisa saya bantu terkait data pemakaman?</div>
            </div>

            <form class="chat-form" id="chatForm">
                <input class="chat-input" id="chatInput" type="text" placeholder="Tulis pesan..." autocomplete="off">
                <button class="chat-send" type="submit">Kirim</button>
            </form>
        </section>
    </main>

    <script>
        (() => {
            const form = document.getElementById('chatForm');
            const input = document.getElementById('chatInput');
            const body = document.getElementById('chatBody');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const askUrl = @json(route('chatbot.ask'));

            if (!form || !input || !body) {
                return;
            }

            function appendBubble(role, text) {
                const bubble = document.createElement('div');
                bubble.className = `bubble ${role}`;
                bubble.textContent = text;
                body.appendChild(bubble);
                body.scrollTop = body.scrollHeight;
                return bubble;
            }

            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                const message = input.value.trim();
                if (!message) {
                    return;
                }

                appendBubble('user', message);
                input.value = '';
                input.disabled = true;

                const loadingBubble = appendBubble('bot', 'Sedang mencari data...');

                try {
                    const response = await fetch(askUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({ message }),
                    });

                    const payload = await response.json().catch(() => ({}));
                    const answer = response.ok
                        ? (payload?.answer || 'Maaf, jawaban tidak tersedia.')
                        : (payload?.message || 'Terjadi kesalahan saat memproses pertanyaan.');
                    loadingBubble.textContent = answer;
                } catch (error) {
                    loadingBubble.textContent = 'Gagal terhubung ke server chatbot.';
                } finally {
                    input.disabled = false;
                    input.focus();
                }
            });
        })();
    </script>
</body>
</html>
