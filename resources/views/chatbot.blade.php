<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chatbot - {{ $setting->systemname ?: 'Denah Kuburan' }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Space+Grotesk:wght@500;700&display=swap');

        :root {
            --bg: #eef3f1;
            --card: #ffffff;
            --line: #d6e2de;
            --text: #132a2f;
            --muted: #5e7277;
            --brand: #1f7a67;
            --brand-soft: #e5f4ef;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "DM Sans", sans-serif;
            background:
                radial-gradient(900px 500px at 5% -10%, #d2e8e0 0%, transparent 60%),
                radial-gradient(800px 500px at 105% 0%, #e0ede8 0%, transparent 60%),
                var(--bg);
            color: var(--text);
        }

        .shell {
            width: min(960px, 94vw);
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
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 36px;
            padding: 0 12px;
            border-radius: 10px;
            border: 1px solid #bcd1c9;
            background: #fff;
            color: #17353c;
            text-decoration: none;
            font-weight: 700;
            font-size: .88rem;
        }

        .chat-card {
            border: 1px solid var(--line);
            border-radius: 16px;
            background: var(--card);
            box-shadow: 0 10px 24px rgba(16, 41, 48, 0.08);
            overflow: hidden;
        }

        .chat-head {
            padding: 12px 14px;
            border-bottom: 1px solid var(--line);
            background: #f8fcfa;
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
            background: linear-gradient(180deg, #f9fcfb 0%, #ffffff 100%);
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
            border-color: #c8e2da;
            justify-self: start;
        }

        .bubble.user {
            background: #f3f6ff;
            border-color: #d4def3;
            justify-self: end;
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
            border: 1px solid #c7d8d2;
            padding: 0 12px;
            font-family: inherit;
            font-size: .9rem;
        }

        .chat-send {
            min-height: 42px;
            padding: 0 14px;
            border-radius: 10px;
            border: 1px solid #0f5d4e;
            background: var(--brand);
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
