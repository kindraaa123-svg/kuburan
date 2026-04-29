<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $setting->systemname ?: 'Denah Kuburan' }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&family=Space+Grotesk:wght@500;700&display=swap');

        :root {
            --bg-top: #2c0915;
            --bg-bottom: #080306;
            --card: rgba(247, 239, 236, 0.98);
            --text: #2b1017;
            --muted: #77525b;
            --line: rgba(115, 38, 54, 0.2);
            --brand: #7a1129;
            --brand-soft: rgba(122, 17, 41, 0.09);
            --danger: #a83c52;
            --ok: #7a1129;
            --info: #8f3d53;
            --gray: #8a6770;
            --gate-icon: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 -8 120 100' fill='none' stroke='%231f1f1f' stroke-width='4' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M8 82h104'/%3E%3Cpath d='M10 20h28'/%3E%3Cpath d='M82 20h28'/%3E%3Cpath d='M38 20a22 22 0 0 1 44 0'/%3E%3Cpath d='M10 20v62'/%3E%3Cpath d='M110 20v62'/%3E%3Cpath d='M22 20v62'/%3E%3Cpath d='M98 20v62'/%3E%3Cpath d='M60 20v62'/%3E%3Cpath d='M16 56h88'/%3E%3Cpath d='M30 20v36'/%3E%3Cpath d='M44 20v36'/%3E%3Cpath d='M76 20v36'/%3E%3Cpath d='M90 20v36'/%3E%3Cpath d='M26 56v26'/%3E%3Cpath d='M36 56v26'/%3E%3Cpath d='M46 56v26'/%3E%3Cpath d='M56 56v26'/%3E%3Cpath d='M66 56v26'/%3E%3Cpath d='M76 56v26'/%3E%3Cpath d='M86 56v26'/%3E%3Cpath d='M94 56v26'/%3E%3C/svg%3E");
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Manrope", sans-serif;
            color: var(--text);
            background:
                radial-gradient(920px 520px at -8% -10%, rgba(170, 44, 73, 0.4) 0%, transparent 58%),
                radial-gradient(760px 440px at 106% 110%, rgba(124, 18, 43, 0.4) 0%, transparent 64%),
                linear-gradient(180deg, var(--bg-top) 0%, #14070d 45%, var(--bg-bottom) 100%);
        }

        .shell {
            width: min(1240px, calc(100vw - 28px));
            margin: 0 auto;
            padding-bottom: 28px;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 20;
            border-bottom: 1px solid var(--line);
            background: rgba(24, 7, 13, 0.82);
            backdrop-filter: blur(8px);
        }

        .topbar-inner {
            width: min(1240px, calc(100vw - 28px));
            margin: 0 auto;
            min-height: 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .brand {
            margin: 0;
            font-family: "Space Grotesk", sans-serif;
            font-size: 1.08rem;
            letter-spacing: .04em;
            color: #fff2ee;
        }

        .brand-wrap {
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .brand-logo {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid rgba(255, 231, 235, 0.18);
            background: rgba(255, 241, 243, 0.1);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border: 1px solid rgba(255, 231, 235, 0.2);
            border-radius: 999px;
            background: rgba(255, 241, 243, 0.1);
            color: #fff2ee;
            font-size: .78rem;
            font-weight: 700;
        }

        .badge::before {
            content: "";
            width: 9px;
            height: 9px;
            border-radius: 99px;
            background: var(--brand);
        }

        .hero {
            margin-top: 26px;
            display: grid;
            grid-template-columns: 1.35fr .65fr;
            gap: 16px;
        }

        .guest-hero {
            margin-top: 24px;
            padding: 24px;
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 18px;
            box-shadow: 0 24px 48px rgba(22, 4, 9, 0.24);
            background:
                linear-gradient(130deg, rgba(122, 17, 41, .09), rgba(255, 255, 255, 0)),
                var(--card);
        }

        .guest-hero h1 {
            margin: 0;
            font-family: "Space Grotesk", sans-serif;
            line-height: 1.15;
            font-size: clamp(1.5rem, 2.8vw, 2.1rem);
        }

        .guest-hero p {
            margin: 10px 0 0;
            color: var(--muted);
            max-width: 840px;
        }

        .hero-main, .hero-side, .panel {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 18px;
            box-shadow: 0 24px 48px rgba(22, 4, 9, 0.24);
        }

        .hero-main {
            padding: 24px;
            background:
                linear-gradient(130deg, rgba(122, 17, 41, .09), rgba(255, 255, 255, 0)),
                var(--card);
        }

        .hero-main h1 {
            margin: 0;
            font-family: "Space Grotesk", sans-serif;
            line-height: 1.15;
            font-size: clamp(1.6rem, 3vw, 2.25rem);
        }

        .hero-main p {
            margin: 12px 0 0;
            color: var(--muted);
            max-width: 780px;
        }

        .hero-side {
            padding: 18px;
            display: grid;
            gap: 10px;
            align-content: start;
        }

        .mini {
            border: 1px solid rgba(115, 38, 54, 0.18);
            border-radius: 12px;
            padding: 10px 12px;
            background: rgba(255, 255, 255, 0.66);
        }

        .mini strong {
            display: block;
            font-size: .8rem;
            color: var(--muted);
            font-weight: 700;
            margin-bottom: 6px;
        }

        .mini span {
            font-size: 1.15rem;
            font-weight: 700;
        }

        .stats {
            margin-top: 16px;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }

        .stat-card {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 14px 15px;
            box-shadow: 0 16px 30px rgba(22, 4, 9, 0.18);
        }

        .stat-card p {
            margin: 0;
            font-size: .8rem;
            color: var(--muted);
            font-weight: 700;
        }

        .stat-card h3 {
            margin: 8px 0 0;
            font-size: 1.45rem;
            font-family: "Space Grotesk", sans-serif;
        }

        .panel {
            margin-top: 16px;
            padding: 14px;
        }

        .panel-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 14px;
            margin-bottom: 12px;
        }

        .panel-head h2 {
            margin: 0;
            font-size: 1.1rem;
            font-family: "Space Grotesk", sans-serif;
        }

        .panel-head p {
            margin: 4px 0 0;
            color: var(--muted);
            font-size: .84rem;
        }

        .chips {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: flex-end;
            max-width: 58%;
        }

        .chip {
            border: 1px solid var(--line);
            border-radius: 999px;
            background: #f7fbf9;
            padding: 6px 10px;
            font-size: .75rem;
            color: #35535a;
            font-weight: 700;
            white-space: nowrap;
        }

        .map-panel {
            border: 1px solid rgba(115, 38, 54, 0.18);
            border-radius: 13px;
            overflow: hidden;
            background: #fdf6f7;
        }

        .map-toolbar {
            display: flex;
            align-items: center;
            gap: 8px;
            justify-content: flex-end;
            padding: 10px;
            border-bottom: 1px solid rgba(115, 38, 54, 0.18);
            background: rgba(255, 255, 255, 0.88);
        }

        .btn {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            border: 1px solid rgba(115, 38, 54, 0.2);
            background: #fff;
            color: #6f3a48;
            font-weight: 700;
            cursor: pointer;
            font-size: 1rem;
        }

        .btn-reset {
            width: auto;
            padding: 0 12px;
            font-size: .8rem;
            font-weight: 700;
        }

        .zoom {
            min-width: 48px;
            text-align: center;
            font-size: .8rem;
            font-weight: 700;
            color: var(--muted);
        }

        .map-viewport {
            position: relative;
            height: min(72vh, 680px);
            overflow: hidden;
            cursor: move;
            touch-action: none;
            user-select: none;
        }

        .map-viewport.dragging {
            cursor: move;
        }

        .map-scene {
            position: absolute;
            left: 0;
            top: 0;
            transform-origin: 0 0;
            border-radius: 10px;
            background:
                linear-gradient(90deg, rgba(122, 17, 41, .05) 1px, transparent 1px) 0 0 / 40px 40px,
                linear-gradient(rgba(122, 17, 41, .05) 1px, transparent 1px) 0 0 / 40px 40px,
                #fdf6f7;
        }

        .block-area {
            position: absolute;
            border: 2px solid rgba(22, 48, 50, 0.28);
            border-radius: 18px;
            background: var(--block-color);
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.28), 0 10px 18px rgba(20, 55, 63, 0.16);
            cursor: default;
            z-index: 3;
        }

        .block-badge {
            position: absolute;
            left: 10px;
            top: -13px;
            font-size: .75rem;
            color: #4f1f2a;
            background: rgba(252, 255, 255, 0.95);
            border: 1px solid rgba(115, 38, 54, 0.28);
            border-radius: 999px;
            padding: 4px 9px;
            font-weight: 800;
            letter-spacing: .02em;
            cursor: default;
        }

        .plot {
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
            border: 1px solid rgba(10, 31, 35, 0.34);
            font-size: .72rem;
            font-weight: 800;
            color: #11292d;
            text-shadow: 0 1px 0 rgba(255, 255, 255, .3);
            cursor: default;
            z-index: 4;
        }

        .plot-empty {
            background: color-mix(in srgb, var(--plot-color) 88%, #ffffff 12%);
        }

        .plot-occupied {
            background: color-mix(in srgb, var(--plot-color) 64%, #35657a 36%);
            color: #f5fbff;
            text-shadow: none;
        }

        .road-lane {
            position: absolute;
            background: #cfd5d8;
            border: 1px solid #a7b0b4;
            border-radius: 28px;
            z-index: 1;
            overflow: hidden;
            box-shadow: inset 0 0 0 2px rgba(255, 255, 255, .15);
        }

        .road-lane::after {
            content: "";
            position: absolute;
            opacity: 0.9;
            background: repeating-linear-gradient(90deg, #ffffff 0, #ffffff 9px, transparent 9px, transparent 17px);
        }

        .road-lane-vertical::after {
            top: 0;
            bottom: 0;
            left: 50%;
            width: 3px;
            transform: translateX(-50%);
            background: repeating-linear-gradient(180deg, #ffffff 0, #ffffff 9px, transparent 9px, transparent 17px);
        }

        .road-lane-horizontal::after {
            left: 0;
            right: 0;
            top: 50%;
            height: 3px;
            transform: translateY(-50%);
        }

        .road-joint {
            position: absolute;
            z-index: 1;
            pointer-events: none;
            overflow: hidden;
            background: transparent;
        }

        .road-joint-mask {
            background:
                linear-gradient(90deg, rgba(19, 42, 47, .05) 1px, transparent 1px) var(--joint-bg-x, 0px) var(--joint-bg-y, 0px) / 40px 40px,
                linear-gradient(rgba(19, 42, 47, .05) 1px, transparent 1px) var(--joint-bg-x, 0px) var(--joint-bg-y, 0px) / 40px 40px,
                #f8fbfa;
        }

        .road-joint-bridge {
            background: #cfd5d8;
            border: 1px solid #a7b0b4;
            box-shadow: inset 0 0 0 2px rgba(255, 255, 255, .15);
        }

        .road-joint-bridge-marked::after {
            content: "";
            position: absolute;
            opacity: 0.9;
        }

        .road-joint-bridge-marked.road-joint-bridge-vertical::after {
            top: 0;
            bottom: 0;
            left: 50%;
            width: 3px;
            transform: translateX(-50%);
            background: repeating-linear-gradient(180deg, #ffffff 0, #ffffff 9px, transparent 9px, transparent 17px);
        }

        .road-joint-bridge-marked.road-joint-bridge-horizontal::after {
            left: 0;
            right: 0;
            top: 50%;
            height: 3px;
            transform: translateY(-50%);
            background: repeating-linear-gradient(90deg, #ffffff 0, #ffffff 9px, transparent 9px, transparent 17px);
        }

        .tree-dot {
            position: absolute;
            width: 16px;
            height: 16px;
            border-radius: 999px;
            border: 1px solid rgba(34, 90, 33, 0.55);
            background: radial-gradient(circle at 34% 34%, #c7f19d 0 20%, #4ca740 21% 72%, #2f7b2a 73% 100%);
            z-index: 2;
        }

        .landmark {
            position: absolute;
            border-radius: 12px;
            border: 2px solid #3c8f4f;
            background: #43b45d;
            z-index: 2;
            box-shadow: inset 0 0 0 2px rgba(255, 255, 255, 0.2);
        }

        .entrance-gate {
            position: absolute;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 14px;
            border-radius: 999px;
            border: 1px solid #9cb7af;
            background: #e5f2ed;
            color: #19453f;
            font-size: .72rem;
            font-weight: 800;
            letter-spacing: .02em;
            z-index: 8;
            box-shadow: 0 6px 16px rgba(18, 54, 61, 0.12);
            cursor: default;
        }

        .entrance-gate::before {
            content: "";
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: #1f7a67;
        }

        .facility-item {
            position: absolute;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            border: 1px solid rgba(18, 51, 57, .35);
            background: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            line-height: 1;
            z-index: 9;
            box-shadow: 0 4px 12px rgba(14, 41, 48, .17);
            pointer-events: none;
            transform: rotate(var(--facility-rotation, 0deg));
            transform-origin: 50% 50%;
        }

        .facility-item[data-facility-key="pohon"] {
            border-radius: 999px;
            border: 2px solid #2f8637;
            background: #3ea944;
            box-shadow: inset 0 0 0 2px rgba(255, 255, 255, .14), 0 4px 12px rgba(14, 41, 48, .17);
            color: transparent;
            text-shadow: none;
            font-size: 0;
        }

        .facility-item[data-facility-key="pohon"] > span {
            display: none;
        }

        .facility-item[data-facility-key="pohon"]::before {
            content: "";
            position: absolute;
            left: 7px;
            top: 6px;
            width: 14px;
            height: 14px;
            border-radius: 999px;
            background: #bde588;
        }

        .facility-item[data-facility-key="pintu_masuk"] {
            width: 56px;
            height: 40px;
            border: 0;
            background: transparent;
            color: transparent;
            box-shadow: none;
            font-size: 0;
        }

        .facility-item[data-facility-key="pintu_masuk"] > span {
            display: none;
        }

        .facility-item[data-facility-key="pintu_masuk"]::before {
            content: "";
            position: absolute;
            inset: 0;
            background: var(--gate-icon) center / contain no-repeat;
        }

        .facility-item[data-facility-key="pintu_masuk"].position-fixed {
            box-shadow: none !important;
        }

        .facility-item[data-facility-key="jalan"] {
            width: 44px;
            height: 28px;
            border-radius: 999px;
            background: #c8d0d4;
            border-color: #9aa8ad;
            color: transparent;
            font-size: 0;
            text-shadow: none;
            z-index: 1;
        }

        .facility-item[data-facility-key="jalan"] > span {
            display: none;
        }

        .facility-item[data-facility-key="jalan"]::before {
            content: "";
            position: absolute;
            left: 8px;
            right: 8px;
            top: 50%;
            height: 2px;
            transform: translateY(-50%);
            background: repeating-linear-gradient(90deg, #ffffff 0, #ffffff 6px, transparent 6px, transparent 12px);
            opacity: 0.95;
        }

        [data-scene-object-id].scene-object-hidden {
            display: none !important;
        }

        .hover-card {
            position: fixed;
            z-index: 60;
            width: 228px;
            background: #ffffff;
            border: 1px solid rgba(115, 38, 54, 0.2);
            border-radius: 12px;
            box-shadow: 0 14px 26px rgba(22, 4, 9, 0.24);
            padding: 8px;
            display: none;
            pointer-events: auto;
        }

        .hover-card.active {
            display: block;
        }

        .hover-head {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .hover-photo {
            width: 42px;
            height: 42px;
            border-radius: 8px;
            border: 1px solid #cbd8d5;
            object-fit: cover;
            background: #eef4f2;
        }

        .hover-title {
            margin: 0;
            font-size: .82rem;
            font-weight: 700;
            color: #19363e;
            line-height: 1.25;
        }

        .hover-sub {
            margin: 2px 0 0;
            font-size: .68rem;
            color: #5f7479;
            font-weight: 600;
        }

        .hover-grid {
            margin-top: 8px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px;
        }

        .ai-chatbot {
            position: fixed;
            right: 18px;
            bottom: 18px;
            z-index: 120;
        }

        .ai-chatbot-toggle {
            width: 56px;
            height: 56px;
            border-radius: 999px;
            border: 1px solid rgba(109, 15, 36, 0.7);
            background: linear-gradient(130deg, #a52142 0%, var(--brand) 58%, #4a0615 100%);
            color: #fff;
            font-size: 1.3rem;
            cursor: pointer;
            box-shadow: 0 10px 24px rgba(20, 4, 9, 0.35);
        }

        .ai-chatbot-card {
            position: absolute;
            right: 0;
            bottom: 68px;
            width: min(360px, calc(100vw - 26px));
            border: 1px solid var(--line);
            border-radius: 14px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 22px 44px rgba(22, 4, 9, 0.28);
            display: none;
        }

        .ai-chatbot-card.active {
            display: grid;
            grid-template-rows: auto 1fr auto;
        }

        .ai-chatbot-head {
            padding: 10px 12px;
            background: rgba(122, 17, 41, 0.08);
            border-bottom: 1px solid rgba(115, 38, 54, 0.2);
            font-size: .84rem;
            font-weight: 700;
            color: #5c2532;
        }

        .ai-chatbot-body {
            height: 300px;
            overflow: auto;
            padding: 10px;
            display: grid;
            align-content: start;
            gap: 8px;
            background: linear-gradient(180deg, #fdf6f7 0%, #ffffff 100%);
        }

        .ai-chatbot-bubble {
            max-width: 88%;
            border-radius: 10px;
            padding: 8px 10px;
            font-size: .84rem;
            line-height: 1.4;
            border: 1px solid var(--line);
            white-space: pre-wrap;
        }

        .ai-chatbot-bubble.user {
            justify-self: end;
            background: rgba(122, 17, 41, 0.1);
        }

        .ai-chatbot-bubble.bot {
            justify-self: start;
            background: rgba(122, 17, 41, 0.06);
        }

        .ai-chatbot-form {
            padding: 10px;
            border-top: 1px solid rgba(115, 38, 54, 0.2);
            display: flex;
            gap: 8px;
        }

        .ai-chatbot-input {
            flex: 1;
            min-height: 38px;
            border-radius: 9px;
            border: 1px solid rgba(115, 38, 54, 0.25);
            padding: 0 10px;
            font-family: inherit;
            font-size: .84rem;
        }

        .ai-chatbot-send {
            min-height: 38px;
            border-radius: 9px;
            border: 1px solid #6d0f24;
            background: linear-gradient(130deg, #a52142 0%, var(--brand) 58%, #4a0615 100%);
            color: #fff;
            padding: 0 11px;
            font-weight: 700;
            cursor: pointer;
        }

        .hover-cell {
            border: 1px solid #d8e4df;
            border-radius: 8px;
            padding: 5px 6px;
            background: #fbfdfc;
        }

        .hover-cell small {
            display: block;
            font-size: .62rem;
            color: #6f8387;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .hover-cell span {
            font-size: .73rem;
            font-weight: 700;
            color: #18353c;
        }

        .hover-action {
            margin-top: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            text-decoration: none;
            border-radius: 8px;
            border: 1px solid rgba(122, 17, 41, 0.24);
            color: #7a1129;
            background: rgba(122, 17, 41, 0.08);
            font-size: .7rem;
            font-weight: 700;
            min-height: 30px;
            pointer-events: auto;
        }

        .legend {
            margin-top: 12px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            color: var(--muted);
            font-size: .8rem;
        }

        .legend span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .dot {
            width: 11px;
            height: 11px;
            border-radius: 3px;
        }

        .site-footer {
            margin-top: 22px;
            border-top: 1px solid rgba(115, 38, 54, 0.18);
            background: rgba(255, 241, 243, 0.14);
        }

        .footer-inner {
            min-height: 74px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            color: #dfbec3;
            font-size: .83rem;
        }

        .footer-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 10px 16px;
        }

        .footer-meta span {
            display: inline-flex;
            align-items: baseline;
            gap: 6px;
        }

        .footer-meta strong {
            color: #1a3a41;
            font-size: .78rem;
            letter-spacing: .02em;
        }

        .footer-copy {
            color: #587177;
            font-size: .78rem;
            white-space: nowrap;
        }

        .chatbot-fab {
            position: fixed;
            right: 18px;
            bottom: 18px;
            z-index: 80;
            width: 48px;
            height: 48px;
            border-radius: 999px;
            border: 1px solid #0f5d4e;
            background: var(--brand);
            color: #fff;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 12px 24px rgba(31, 122, 103, 0.28);
            font-size: 1.1rem;
            font-weight: 700;
        }

        .chatbot-fab:hover {
            filter: brightness(1.05);
        }

        @media (max-width: 1080px) {
            .guest-hero {
                margin-top: 16px;
                padding: 16px;
            }
            .stats {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
            .hero {
                grid-template-columns: 1fr;
            }
            .chips {
                max-width: 100%;
                justify-content: flex-start;
            }
            .panel-head {
                display: block;
            }
        }

        @media (max-width: 720px) {
            .topbar-inner {
                min-height: 66px;
            }
            .stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
            .panel {
                padding: 10px;
            }
            .map-viewport {
                height: 60vh;
            }
            .footer-inner {
                min-height: 0;
                padding: 12px 0 14px;
                display: block;
            }
            .footer-copy {
                margin-top: 6px;
                display: block;
            }
            .chatbot-fab {
                right: 12px;
                bottom: 12px;
            }
        }
    </style>
</head>
<body>
    @php
        $logo = $setting->systemlogo ?? null;
        $logoUrl = null;
        if ($logo) {
            $normalizedLogoPath = str_replace('\\', '/', trim((string) $logo));

            if (\Illuminate\Support\Str::startsWith($normalizedLogoPath, ['http://', 'https://', '/'])) {
                $logoUrl = $normalizedLogoPath;
            } else {
                if (\Illuminate\Support\Str::startsWith($normalizedLogoPath, 'public/')) {
                    $normalizedLogoPath = \Illuminate\Support\Str::after($normalizedLogoPath, 'public/');
                }
                if (\Illuminate\Support\Str::startsWith($normalizedLogoPath, 'storage/')) {
                    $normalizedLogoPath = \Illuminate\Support\Str::after($normalizedLogoPath, 'storage/');
                }

                $normalizedLogoPath = ltrim($normalizedLogoPath, '/');

                if ($normalizedLogoPath !== '' &&
                    ! \Illuminate\Support\Str::contains($normalizedLogoPath, '..') &&
                    \Illuminate\Support\Facades\Storage::disk('public')->exists($normalizedLogoPath)) {
                    $logoUrl = route('media.deceased-photo', ['path' => $normalizedLogoPath]);
                } else {
                    $logoUrl = asset(ltrim((string) $logo, '/'));
                }
            }
        }
    @endphp

    <header class="topbar">
        <div class="topbar-inner">
            <div class="brand-wrap">
                @if ($logoUrl)
                    <img class="brand-logo" src="{{ $logoUrl }}" alt="Logo Website" onerror="this.style.display='none'">
                @endif
                <h2 class="brand">{{ $setting->systemname ?: 'Denah Kuburan' }}</h2>
            </div>
        </div>
    </header>

    <main class="shell">
        <section class="guest-hero">
            <h1>Denah Kuburan Publik</h1>
            <p>
                Halaman ini khusus untuk pengunjung melihat denah kuburan. Klik petak yang terisi untuk melihat detail almarhum,
                lalu gunakan gesture geser dan zoom pada peta untuk navigasi area.
            </p>
        </section>

        <section class="panel">
            <div class="panel-head">
                <div>
                    <h2>Denah Semua Blok</h2>
                    <p>Tampilan publik denah kuburan</p>
                </div>
            </div>

            <div class="map-panel">


                <div class="map-viewport" id="mapViewport">
                    @php
                        $layoutWidth = 1900.0;
                        $layoutHeight = 1300.0;
                        $blockLayouts = collect($blockMaps)
                            ->values()
                            ->map(function (array $blockMap, int $index): array {
                                return [
                                    'id' => (int) ($blockMap['id'] ?? 0),
                                    'name' => (string) ($blockMap['name'] ?? ('Blok ' . ($index + 1))),
                                    'x' => (float) ($blockMap['x'] ?? 0),
                                    'y' => (float) ($blockMap['y'] ?? 0),
                                    'width' => (float) ($blockMap['width'] ?? 240),
                                    'height' => (float) ($blockMap['height'] ?? 180),
                                    'color' => (string) ($blockMap['color'] ?? '#D8E4DF'),
                                ];
                            })
                            ->all();

                        foreach ($blockLayouts as $layout) {
                            $layoutWidth = max($layoutWidth, (float) $layout['x'] + (float) $layout['width'] + 520.0);
                            $layoutHeight = max($layoutHeight, (float) $layout['y'] + (float) $layout['height'] + 420.0);
                        }

                        if (isset($mapWidth)) {
                            $layoutWidth = max($layoutWidth, (float) $mapWidth + 520.0);
                        }
                        if (isset($mapHeight)) {
                            $layoutHeight = max($layoutHeight, (float) $mapHeight + 420.0);
                        }

                        $blockColorById = collect($blockLayouts)->mapWithKeys(fn (array $layout): array => [
                            (int) $layout['id'] => (string) $layout['color'],
                        ]);

                        $displayPlots = collect($mapPlots)
                            ->map(function (array $plot) use ($blockColorById): array {
                                $blockId = (int) ($plot['block_id'] ?? 0);
                                return [
                                    'block_id' => $blockId,
                                    'status' => (string) ($plot['status'] ?? 'empty'),
                                    'number' => $plot['plot_number'] ?? '-',
                                    'left' => (float) ($plot['x'] ?? 0),
                                    'top' => (float) ($plot['y'] ?? 0),
                                    'width' => (float) ($plot['width'] ?? 54),
                                    'height' => (float) ($plot['height'] ?? 38),
                                    'color' => (string) ($blockColorById->get($blockId, '#D8E4DF')),
                                    'deceased_name' => $plot['deceased_name'] ?? '',
                                    'deceased_age' => $plot['deceased_age'] ?? '',
                                    'deceased_death_date' => $plot['deceased_death_date'] ?? '',
                                    'deceased_photo_url' => $plot['deceased_photo_url'] ?? '',
                                    'plot_label' => $plot['plot_label'] ?? '',
                                    'deceased_id' => $plot['deceased_id'] ?? null,
                                ];
                            })
                            ->all();

                        $facilityIdByKey = collect($facilities ?? collect())
                            ->mapWithKeys(fn ($facility) => [
                                (string) ($facility->facility_key ?? '') => (int) ($facility->facilityid ?? 0),
                            ]);
                    @endphp
                    <div class="map-scene" id="mapScene" style="width: {{ $layoutWidth }}px; height: {{ $layoutHeight }}px;">
                        <div class="road-lane road-lane-horizontal" data-edit-draggable="1" data-scene-object-id="road_top_main" data-scene-facility-id="{{ (int) ($facilityIdByKey['jalan'] ?? 0) }}" data-scene-facility-key="jalan" style="left: 42px; top: 22px; width: 1096px; height: 58px;"></div>
                        <div class="road-lane road-lane-vertical" data-edit-draggable="1" data-scene-object-id="road_left_side" data-scene-facility-id="{{ (int) ($facilityIdByKey['jalan'] ?? 0) }}" data-scene-facility-key="jalan" style="left: 24px; top: 88px; width: 68px; height: {{ $layoutHeight - 132 }}px;"></div>
                        <div class="road-lane road-lane-vertical" data-edit-draggable="1" data-scene-object-id="road_right_side" data-scene-facility-id="{{ (int) ($facilityIdByKey['jalan'] ?? 0) }}" data-scene-facility-key="jalan" style="left: 1088px; top: 88px; width: 68px; height: {{ $layoutHeight - 132 }}px;"></div>
                        <div class="road-lane road-lane-horizontal" data-edit-draggable="1" data-scene-object-id="road_mid_top" data-scene-facility-id="{{ (int) ($facilityIdByKey['jalan'] ?? 0) }}" data-scene-facility-key="jalan" style="left: 118px; top: 248px; width: 944px; height: 70px;"></div>
                        <div class="road-lane road-lane-horizontal" data-edit-draggable="1" data-scene-object-id="road_mid_center" data-scene-facility-id="{{ (int) ($facilityIdByKey['jalan'] ?? 0) }}" data-scene-facility-key="jalan" style="left: 136px; top: 422px; width: 908px; height: 68px;"></div>
                        <div class="road-lane road-lane-horizontal" data-edit-draggable="1" data-scene-object-id="road_bottom" data-scene-facility-id="{{ (int) ($facilityIdByKey['jalan'] ?? 0) }}" data-scene-facility-key="jalan" style="left: 118px; top: 614px; width: 944px; height: 74px;"></div>
                        <div class="landmark" data-edit-draggable="1" data-scene-object-id="landmark_gatehouse" data-scene-facility-id="0" style="left: 246px; top: 318px; width: 54px; height: 84px;"></div>

                        @for ($i = 0; $i < 34; $i++)
                            <span class="tree-dot" data-edit-draggable="1" data-scene-object-id="tree_top_{{ $i }}" data-scene-facility-id="{{ (int) ($facilityIdByKey['pohon'] ?? 0) }}" data-scene-facility-key="pohon" style="left: {{ 20 + ($i * 33) }}px; top: 6px;"></span>
                        @endfor
                        @for ($i = 0; $i < 34; $i++)
                            <span class="tree-dot" data-edit-draggable="1" data-scene-object-id="tree_bottom_{{ $i }}" data-scene-facility-id="{{ (int) ($facilityIdByKey['pohon'] ?? 0) }}" data-scene-facility-key="pohon" style="left: {{ 20 + ($i * 33) }}px; top: {{ $layoutHeight - 22 }}px;"></span>
                        @endfor
                        @for ($i = 0; $i < 20; $i++)
                            <span class="tree-dot" data-edit-draggable="1" data-scene-object-id="tree_left_{{ $i }}" data-scene-facility-id="{{ (int) ($facilityIdByKey['pohon'] ?? 0) }}" data-scene-facility-key="pohon" style="left: 6px; top: {{ 42 + ($i * 34) }}px;"></span>
                            <span class="tree-dot" data-edit-draggable="1" data-scene-object-id="tree_right_{{ $i }}" data-scene-facility-id="{{ (int) ($facilityIdByKey['pohon'] ?? 0) }}" data-scene-facility-key="pohon" style="left: {{ $layoutWidth - 22 }}px; top: {{ 42 + ($i * 34) }}px;"></span>
                        @endfor

                        @foreach ($blockLayouts as $blockLayout)
                            <div
                                class="block-area"
                                style="
                                    --block-color: {{ $blockLayout['color'] }};
                                    left: {{ $blockLayout['x'] }}px;
                                    top: {{ $blockLayout['y'] }}px;
                                    width: {{ $blockLayout['width'] }}px;
                                    height: {{ $blockLayout['height'] }}px;
                                ">
                                <span class="block-badge">{{ strtoupper($blockLayout['name']) }}</span>
                            </div>
                        @endforeach

                        @foreach ($displayPlots as $plot)
                            <a href="{{ $plot['deceased_id'] ? route('deceased.detail', ['id' => $plot['deceased_id']]) : '#' }}" class="plot plot-{{ $plot['status'] }}" data-status="{{ $plot['status'] }}" data-name="{{ $plot['deceased_name'] }}" data-age="{{ $plot['deceased_age'] }}" data-death-date="{{ $plot['deceased_death_date'] }}" data-photo="{{ $plot['deceased_photo_url'] }}" data-plot-label="{{ $plot['plot_label'] }}" data-detail-url="{{ $plot['deceased_id'] ? route('deceased.detail', ['id' => $plot['deceased_id']]) : '' }}" data-block-id="{{ (int) $plot['block_id'] }}" style="--plot-color: {{ $plot['color'] }}; left: {{ $plot['left'] }}px; top: {{ $plot['top'] }}px; width: {{ $plot['width'] }}px; height: {{ $plot['height'] }}px; text-decoration: none;">{{ $plot['number'] }}</a>
                        @endforeach

                        @foreach (($facilityMapItems ?? collect()) as $facilityItem)
                            @php
                                $facility = ($facilities ?? collect())->firstWhere('facilityid', (int) $facilityItem->facility_id);
                                $facilityKey = $facility?->facility_key ?? 'facility';
                                $facilityIcon = $facility?->icon_emoji ?: 'F';
                            @endphp
                            <div
                                class="facility-item fixed position-fixed"
                                data-map-item-id="{{ (int) $facilityItem->facility_map_itemid }}"
                                data-facility-id="{{ (int) $facilityItem->facility_id }}"
                                data-facility-key="{{ $facilityKey }}"
                                data-facility-icon="{{ $facilityIcon }}"
                                data-facility-name="{{ $facility?->facility_name ?? 'Fasilitas' }}"
                                data-is-fixed="1"
                                data-locked="1"
                                data-rotation="{{ (float) ($facilityItem->map_rotation ?? 0) }}"
                                style="left: {{ (int) $facilityItem->map_x }}px; top: {{ (int) $facilityItem->map_y }}px;{{ isset($facilityItem->map_width) && $facilityItem->map_width ? ' width: ' . (int) $facilityItem->map_width . 'px;' : '' }}{{ isset($facilityItem->map_height) && $facilityItem->map_height ? ' height: ' . (int) $facilityItem->map_height . 'px;' : '' }} --facility-rotation: {{ (float) ($facilityItem->map_rotation ?? 0) }}deg;"
                                title="{{ $facility?->facility_name ?? 'Fasilitas' }}"
                            >
                                <span>{{ $facilityIcon }}</span>
                            </div>
                        @endforeach

                        <div class="entrance-gate" data-edit-draggable="1" data-scene-object-id="entrance_main_gate" data-scene-facility-id="{{ (int) ($facilityIdByKey['pintu_masuk'] ?? 0) }}" data-scene-facility-key="pintu_masuk" style="left: {{ ($layoutWidth / 2) - 70 }}px; top: {{ $layoutHeight - 46 }}px;">Pintu Masuk Utama</div>
                    </div>
                </div>
            </div>

            <div class="legend">
                <span><i class="dot" style="background: var(--danger);"></i> Terisi</span>
                <span><i class="dot" style="background: var(--ok);"></i> Kosong</span>
                <span><i class="dot" style="background: #e5f2ed;"></i> Pintu Masuk</span>
                <span><i class="dot" style="background: #e7ecea;"></i> Jalur Jalan</span>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="shell footer-inner">
            <div class="footer-meta">
                <span><strong>Manager:</strong> {{ $setting->systemmanager ?: '-' }}</span>
                <span><strong>Address:</strong> {{ $setting->systemaddress ?: '-' }}</span>
                <span><strong>Contact:</strong> {{ $setting->systemcontact ?: '-' }}</span>
            </div>
            <span class="footer-copy">{{ $setting->systemname ?: 'Denah Kuburan' }}</span>
        </div>
    </footer>

    <div class="hover-card" id="plotHoverCard">
        <div class="hover-head">
            <img class="hover-photo" id="hoverPhoto" src="" alt="Foto almarhum">
            <div>
                <h4 class="hover-title" id="hoverName">-</h4>
                <p class="hover-sub" id="hoverPlot">-</p>
            </div>
        </div>
        <div class="hover-grid">
            <div class="hover-cell">
                <small>Umur Wafat</small>
                <span id="hoverAge">-</span>
            </div>
            <div class="hover-cell">
                <small>Tanggal Wafat</small>
                <span id="hoverDeathDate">-</span>
            </div>
        </div>
        <a class="hover-action" id="hoverDetailBtn" href="#">Lihat Lebih Detail</a>
    </div>

    <section class="ai-chatbot" id="aiChatbot">
        <button class="ai-chatbot-toggle" id="aiChatbotToggle" type="button" aria-label="Buka chatbot">AI</button>
        <div class="ai-chatbot-card" id="aiChatbotCard">
            <div class="ai-chatbot-head">Chatbot AI Pemakaman</div>
            <div class="ai-chatbot-body" id="aiChatbotBody">
                <div class="ai-chatbot-bubble bot">Halo, silakan tanya lokasi kuburan, info blok, atau plot kosong.</div>
            </div>
            <form class="ai-chatbot-form" id="aiChatbotForm">
                <input class="ai-chatbot-input" id="aiChatbotInput" type="text" placeholder="Tulis pertanyaan..." autocomplete="off">
                <button class="ai-chatbot-send" type="submit">Kirim</button>
            </form>
        </div>
    </section>

    <script>
        (() => {
            const viewport = document.getElementById('mapViewport');
            const scene = document.getElementById('mapScene');
            const zoomLabel = document.getElementById('zoomLabel');
            const controls = document.querySelectorAll('[data-map-action]');
            const hoverCard = document.getElementById('plotHoverCard');
            const hoverPhoto = document.getElementById('hoverPhoto');
            const hoverName = document.getElementById('hoverName');
            const hoverPlot = document.getElementById('hoverPlot');
            const hoverAge = document.getElementById('hoverAge');
            const hoverDeathDate = document.getElementById('hoverDeathDate');
            const hoverDetailBtn = document.getElementById('hoverDetailBtn');
            const plotElements = document.querySelectorAll('.plot');
            const initialSceneMapItems = @json(($sceneMapItems ?? collect())->values());
            const initialFacilityMapStorage = @json($facilityMapStorage ?? 'none');
            const facilityMapShapePersisted = @json($facilityMapShapePersisted ?? false);
            const sceneStorageKey = 'kuburan.dashboard.scene-objects.v1';
            const facilityStorageKey = 'kuburan.dashboard.facility-items.v1';
            const shouldHydrateFacilityItemsFromStorage = initialFacilityMapStorage === 'facility_map_items' && !facilityMapShapePersisted;

            if (!viewport || !scene) {
                return;
            }

            const minScale = 0.35;
            const maxScale = 2.8;
            let scale = 1;
            let translateX = 0;
            let translateY = 0;
            let isDragging = false;
            let startX = 0;
            let startY = 0;
            let hideTimer = null;
            let isHoverCardActive = false;

            function render() {
                scene.style.transform = `translate(${translateX}px, ${translateY}px) scale(${scale})`;
                if (zoomLabel) {
                    zoomLabel.textContent = `${Math.round(scale * 100)}%`;
                }
            }

            function clampScale(value) {
                return Math.min(maxScale, Math.max(minScale, value));
            }

            function initialsFromName(name) {
                return (name || 'A')
                    .split(' ')
                    .filter(Boolean)
                    .slice(0, 2)
                    .map((part) => part.charAt(0).toUpperCase())
                    .join('');
            }

            function placeholderPhoto(name) {
                const initials = initialsFromName(name);
                const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="120" height="120"><rect width="100%" height="100%" fill="#dde9e5"/><text x="50%" y="54%" dominant-baseline="middle" text-anchor="middle" font-family="Arial, sans-serif" font-size="38" fill="#2f545d">${initials}</text></svg>`;
                return `data:image/svg+xml;utf8,${encodeURIComponent(svg)}`;
            }

            function placeHoverCard(clientX, clientY) {
                if (!hoverCard) {
                    return;
                }

                const margin = 16;
                const cardWidth = hoverCard.offsetWidth || 228;
                const cardHeight = hoverCard.offsetHeight || 188;
                let left = clientX + 18;
                let top = clientY + 18;

                if (left + cardWidth > window.innerWidth - margin) {
                    left = clientX - cardWidth - 18;
                }

                if (top + cardHeight > window.innerHeight - margin) {
                    top = window.innerHeight - cardHeight - margin;
                }

                if (top < margin) {
                    top = margin;
                }

                hoverCard.style.left = `${left}px`;
                hoverCard.style.top = `${top}px`;
            }

            function showHoverCard(plot, event) {
                if (!hoverCard || !hoverPhoto || !hoverName || !hoverPlot || !hoverAge || !hoverDeathDate || !hoverDetailBtn) {
                    return;
                }

                if (hideTimer) {
                    window.clearTimeout(hideTimer);
                }

                const name = plot.dataset.name || '-';
                const plotLabel = plot.dataset.plotLabel || '-';
                const age = plot.dataset.age ? `${plot.dataset.age} tahun` : '-';
                const deathDate = plot.dataset.deathDate || '-';
                const photo = plot.dataset.photo || placeholderPhoto(name);
                const statusRaw = (plot.dataset.status || '').toLowerCase();
                const status = statusRaw === 'occupied' ? 'Terisi' : 'Kosong';
                const detailUrl = plot.dataset.detailUrl || '#';
                const isEmptyPlot = statusRaw !== 'occupied';
                const displayName = isEmptyPlot ? 'Kosong' : name;

                hoverPhoto.src = isEmptyPlot ? placeholderPhoto('Kosong') : photo;
                hoverPhoto.alt = `Foto ${displayName}`;
                hoverName.textContent = displayName;
                hoverPlot.textContent = isEmptyPlot ? plotLabel : `${plotLabel} - ${status}`;
                hoverAge.textContent = isEmptyPlot ? '-' : age;
                hoverDeathDate.textContent = isEmptyPlot ? '-' : deathDate;
                hoverDetailBtn.href = detailUrl;
                hoverDetailBtn.style.display = isEmptyPlot || detailUrl === '#' ? 'none' : 'inline-flex';

                hoverCard.classList.add('active');
                placeHoverCard(event.clientX, event.clientY);
            }

            function hideHoverCard() {
                if (!hoverCard) {
                    return;
                }

                hoverCard.classList.remove('active');
            }

            function queueHideHoverCard() {
                if (hideTimer) {
                    window.clearTimeout(hideTimer);
                }

                hideTimer = window.setTimeout(() => {
                    if (!isHoverCardActive) {
                        hideHoverCard();
                    }
                }, 140);
            }

            function zoomAt(clientX, clientY, factor) {
                const rect = viewport.getBoundingClientRect();
                const nextScale = clampScale(scale * factor);
                const vx = clientX - rect.left;
                const vy = clientY - rect.top;
                const worldX = (vx - translateX) / scale;
                const worldY = (vy - translateY) / scale;

                scale = nextScale;
                translateX = vx - (worldX * scale);
                translateY = vy - (worldY * scale);
                render();
            }

            function fitToViewport() {
                const padding = 30;
                const viewWidth = viewport.clientWidth - (padding * 2);
                const viewHeight = viewport.clientHeight - (padding * 2);
                const sceneWidth = scene.offsetWidth;
                const sceneHeight = scene.offsetHeight;
                const fitScale = clampScale(Math.min(viewWidth / sceneWidth, viewHeight / sceneHeight, 1));

                scale = fitScale;
                translateX = (viewport.clientWidth - (sceneWidth * scale)) / 2;
                translateY = (viewport.clientHeight - (sceneHeight * scale)) / 2;
                render();
            }

            function applySceneObjectState(item) {
                if (!item) {
                    return;
                }

                const objectKey = item.id ?? item.scene_object_key ?? null;
                if (!objectKey) {
                    return;
                }

                const target = Array.from(scene.querySelectorAll('[data-scene-object-id]'))
                    .find((element) => (element.dataset.sceneObjectId || '') === String(objectKey));
                if (!target) {
                    return;
                }

                const left = Number(item.left ?? item.map_x);
                const top = Number(item.top ?? item.map_y);
                if (Number.isFinite(left)) {
                    target.style.left = `${left}px`;
                }
                if (Number.isFinite(top)) {
                    target.style.top = `${top}px`;
                }

                const width = Number(item.width ?? item.map_width);
                const height = Number(item.height ?? item.map_height);
                if (Number.isFinite(width) && width > 0) {
                    target.style.width = `${width}px`;
                }
                if (Number.isFinite(height) && height > 0) {
                    target.style.height = `${height}px`;
                }
                syncRoadLaneAppearance(target);

                const rotation = Number(item.rotation ?? item.map_rotation);
                if (Number.isFinite(rotation)) {
                    target.dataset.rotation = String(rotation);
                    target.style.setProperty('--facility-rotation', `${rotation}deg`);
                }

                const removedSource = item.removed ?? item.is_removed;
                const removed = removedSource === true || removedSource === 1 || removedSource === '1' || removedSource === 'true';
                target.dataset.removed = removed ? '1' : '0';
                target.classList.toggle('scene-object-hidden', removed);
            }

            function loadSceneObjectsFromDatabase() {
                if (!Array.isArray(initialSceneMapItems) || initialSceneMapItems.length === 0) {
                    return false;
                }

                const byKey = new Map();
                initialSceneMapItems.forEach((item) => {
                    const key = item?.scene_object_key ?? null;
                    if (key) {
                        byKey.set(String(key), item);
                    }
                });

                Array.from(scene.querySelectorAll('[data-scene-object-id]')).forEach((sceneObject) => {
                    const key = sceneObject.dataset.sceneObjectId || '';
                    const dbItem = byKey.get(key);
                    if (!dbItem) {
                        sceneObject.classList.add('scene-object-hidden');
                        return;
                    }
                    applySceneObjectState(dbItem);
                });

                return true;
            }

            function loadSceneObjectsFromStorage() {
                try {
                    const raw = window.localStorage.getItem(sceneStorageKey);
                    if (!raw) {
                        return;
                    }
                    const parsed = JSON.parse(raw);
                    if (!Array.isArray(parsed)) {
                        return;
                    }
                    parsed.forEach((item) => applySceneObjectState(item));
                } catch (_error) {
                    // ignore invalid local storage payload
                }
            }

            function facilityElements() {
                return Array.from(scene.querySelectorAll('.facility-item'));
            }

            function clearFacilityItemsStorage() {
                try {
                    window.localStorage.removeItem(facilityStorageKey);
                } catch (_error) {
                    // ignore local storage write failure
                }
            }

            function findFacilityElementForState(item) {
                const rawMapItemId = item?.mapItemId
                    ?? item?.map_item_id
                    ?? item?.facility_map_itemid
                    ?? item?.facilityMapItemId
                    ?? item?.id
                    ?? null;

                if (rawMapItemId === null || rawMapItemId === '') {
                    return null;
                }

                return facilityElements().find((element) => (element.dataset.mapItemId || '') === String(rawMapItemId)) || null;
            }

            function applyFacilityItemState(item) {
                if (!item) {
                    return;
                }

                const target = findFacilityElementForState(item);
                if (!target) {
                    return;
                }

                const left = Number(item.left ?? item.x ?? item.map_x);
                const top = Number(item.top ?? item.y ?? item.map_y);
                const width = Number(item.width ?? item.map_width);
                const height = Number(item.height ?? item.map_height);
                const rotation = Number(item.rotation ?? item.map_rotation);

                if (Number.isFinite(left)) {
                    target.style.left = `${left}px`;
                }
                if (Number.isFinite(top)) {
                    target.style.top = `${top}px`;
                }
                if (Number.isFinite(width) && width > 0) {
                    target.style.width = `${width}px`;
                }
                if (Number.isFinite(height) && height > 0) {
                    target.style.height = `${height}px`;
                }
                if (Number.isFinite(rotation)) {
                    target.dataset.rotation = String(rotation);
                    target.style.setProperty('--facility-rotation', `${rotation}deg`);
                }
            }

            function loadFacilityItemsFromStorage() {
                if (!shouldHydrateFacilityItemsFromStorage) {
                    return;
                }

                try {
                    const raw = window.localStorage.getItem(facilityStorageKey);
                    if (!raw) {
                        return;
                    }
                    const parsed = JSON.parse(raw);
                    if (!Array.isArray(parsed)) {
                        return;
                    }
                    parsed.forEach((item) => applyFacilityItemState(item));
                } catch (_error) {
                    // ignore invalid local storage payload
                }
            }

            function roadElements() {
                return Array.from(scene.querySelectorAll('.road-lane, .facility-item[data-facility-key="jalan"]'))
                    .filter((element) => !element.classList.contains('scene-object-hidden'));
            }

            function syncRoadLaneAppearance(element) {
                if (!(element instanceof HTMLElement) || !element.classList.contains('road-lane')) {
                    return;
                }

                const width = element.offsetWidth || parseFloat(element.style.width || '0');
                const height = element.offsetHeight || parseFloat(element.style.height || '0');
                const radius = Math.max(12, Math.round(Math.min(width, height) / 2));
                element.style.borderRadius = `${radius}px`;
            }

            function normalizeRotation(value) {
                let angle = Number(value);
                if (!Number.isFinite(angle)) {
                    return 0;
                }
                while (angle > 180) angle -= 360;
                while (angle < -180) angle += 360;
                return angle;
            }

            function getElementBox(element) {
                const fallbackLeft = parseFloat(element.style.left || '0');
                const fallbackTop = parseFloat(element.style.top || '0');
                const fallbackWidth = element.offsetWidth;
                const fallbackHeight = element.offsetHeight;
                const sceneRect = scene.getBoundingClientRect();
                const elementRect = element.getBoundingClientRect();
                const sceneWidth = scene.offsetWidth;
                const sceneHeight = scene.offsetHeight;
                const scaleX = sceneWidth > 0 ? sceneRect.width / sceneWidth : 1;
                const scaleY = sceneHeight > 0 ? sceneRect.height / sceneHeight : 1;

                if (
                    sceneRect.width <= 0 ||
                    sceneRect.height <= 0 ||
                    elementRect.width <= 0 ||
                    elementRect.height <= 0 ||
                    !Number.isFinite(scaleX) ||
                    !Number.isFinite(scaleY) ||
                    scaleX <= 0 ||
                    scaleY <= 0
                ) {
                    return {
                        left: Number.isFinite(fallbackLeft) ? fallbackLeft : 0,
                        top: Number.isFinite(fallbackTop) ? fallbackTop : 0,
                        right: (Number.isFinite(fallbackLeft) ? fallbackLeft : 0) + fallbackWidth,
                        bottom: (Number.isFinite(fallbackTop) ? fallbackTop : 0) + fallbackHeight,
                        width: fallbackWidth,
                        height: fallbackHeight,
                    };
                }

                const left = (elementRect.left - sceneRect.left) / scaleX;
                const top = (elementRect.top - sceneRect.top) / scaleY;
                const width = elementRect.width / scaleX;
                const height = elementRect.height / scaleY;

                return {
                    left: Number.isFinite(left) ? left : 0,
                    top: Number.isFinite(top) ? top : 0,
                    right: (Number.isFinite(left) ? left : 0) + width,
                    bottom: (Number.isFinite(top) ? top : 0) + height,
                    width,
                    height,
                };
            }

            function isRoadElementHorizontal(element, box) {
                if (element.classList.contains('facility-item')) {
                    const rotation = Math.abs(normalizeRotation(parseFloat(element.dataset.rotation || '0')));
                    if (rotation >= 45 && rotation <= 135) {
                        return false;
                    }
                }

                return box.width >= box.height;
            }

            function getRoadJointDirection(hasNorth, hasSouth, hasWest, hasEast) {
                if (hasNorth && hasEast && !hasSouth && !hasWest) {
                    return 'ne';
                }
                if (hasSouth && hasEast && !hasNorth && !hasWest) {
                    return 'se';
                }
                if (hasSouth && hasWest && !hasNorth && !hasEast) {
                    return 'sw';
                }
                if (hasNorth && hasWest && !hasSouth && !hasEast) {
                    return 'nw';
                }
                if (hasSouth && hasEast) {
                    return 'se';
                }
                if (hasNorth && hasEast) {
                    return 'ne';
                }
                if (hasSouth && hasWest) {
                    return 'sw';
                }
                return 'nw';
            }

            function getRoadJointShape(hasNorth, hasSouth, hasWest, hasEast) {
                const connectionCount = [hasNorth, hasSouth, hasWest, hasEast].filter(Boolean).length;

                if (connectionCount >= 4) {
                    return 'cross';
                }
                if (connectionCount === 3) {
                    if (hasNorth && hasEast && hasWest) {
                        return 'tee-north';
                    }
                    if (hasSouth && hasEast && hasWest) {
                        return 'tee-south';
                    }
                    if (hasNorth && hasSouth && hasEast) {
                        return 'tee-east';
                    }
                    if (hasNorth && hasSouth && hasWest) {
                        return 'tee-west';
                    }
                }

                return `corner-${getRoadJointDirection(hasNorth, hasSouth, hasWest, hasEast)}`;
            }

            function buildRoadJointShapePath(jointWidth, jointHeight, shape) {
                const midX = jointWidth / 2;
                const midY = jointHeight / 2;

                if (shape === 'corner-ne') {
                    return `M 0 0 L ${jointWidth} 0 L ${jointWidth} ${jointHeight} L ${midX} ${jointHeight} Q 0 ${jointHeight} 0 ${midY} Z`;
                }
                if (shape === 'corner-se') {
                    return `M ${midX} 0 L ${jointWidth} 0 L ${jointWidth} ${jointHeight} L 0 ${jointHeight} L 0 ${midY} Q 0 0 ${midX} 0 Z`;
                }
                if (shape === 'corner-sw') {
                    return `M 0 0 L ${midX} 0 Q ${jointWidth} 0 ${jointWidth} ${midY} L ${jointWidth} ${jointHeight} L 0 ${jointHeight} Z`;
                }
                if (shape === 'corner-nw') {
                    return `M 0 0 L ${jointWidth} 0 L ${jointWidth} ${midY} Q ${jointWidth} ${jointHeight} ${midX} ${jointHeight} L 0 ${jointHeight} Z`;
                }

                return `M 0 0 L ${jointWidth} 0 L ${jointWidth} ${jointHeight} L 0 ${jointHeight} Z`;
            }

            function buildRoadJointOutlinePath(jointWidth, jointHeight, shape) {
                const midX = jointWidth / 2;
                const midY = jointHeight / 2;

                if (shape === 'corner-ne') {
                    return `M ${jointWidth} ${jointHeight} L ${midX} ${jointHeight} Q 0 ${jointHeight} 0 ${midY} L 0 0`;
                }
                if (shape === 'corner-se') {
                    return `M 0 ${jointHeight} L 0 ${midY} Q 0 0 ${midX} 0 L ${jointWidth} 0`;
                }
                if (shape === 'corner-sw') {
                    return `M 0 0 L ${midX} 0 Q ${jointWidth} 0 ${jointWidth} ${midY} L ${jointWidth} ${jointHeight}`;
                }
                if (shape === 'corner-nw') {
                    return `M ${jointWidth} 0 L ${jointWidth} ${midY} Q ${jointWidth} ${jointHeight} ${midX} ${jointHeight} L 0 ${jointHeight}`;
                }
                if (shape === 'tee-north') {
                    return `M 0 ${jointHeight} L ${jointWidth} ${jointHeight}`;
                }
                if (shape === 'tee-south') {
                    return `M 0 0 L ${jointWidth} 0`;
                }
                if (shape === 'tee-east') {
                    return `M 0 0 L 0 ${jointHeight}`;
                }
                if (shape === 'tee-west') {
                    return `M ${jointWidth} 0 L ${jointWidth} ${jointHeight}`;
                }
                if (shape === 'cross') {
                    return '';
                }

                return null;
            }

            function buildRoadJointDashPaths(jointWidth, jointHeight, shape) {
                const curveRatio = 0.5522847498;
                const midX = jointWidth / 2;
                const midY = jointHeight / 2;
                const trim = Math.max(1, Math.min(3, Math.round(Math.min(jointWidth, jointHeight) * 0.04)));

                if (shape === 'corner-ne') {
                    return [
                        `M ${midX} ${trim} C ${midX} ${trim + (midY * curveRatio)}, ${jointWidth - (midX * curveRatio)} ${midY}, ${jointWidth - trim} ${midY}`,
                    ];
                }
                if (shape === 'corner-se') {
                    return [
                        `M ${midX} ${jointHeight - trim} C ${midX} ${jointHeight - trim - (midY * curveRatio)}, ${jointWidth - (midX * curveRatio)} ${midY}, ${jointWidth - trim} ${midY}`,
                    ];
                }
                if (shape === 'corner-sw') {
                    return [
                        `M ${midX} ${jointHeight - trim} C ${midX} ${jointHeight - trim - (midY * curveRatio)}, ${midX * curveRatio} ${midY}, ${trim} ${midY}`,
                    ];
                }
                if (shape === 'corner-nw') {
                    return [
                        `M ${midX} ${trim} C ${midX} ${trim + (midY * curveRatio)}, ${midX * curveRatio} ${midY}, ${trim} ${midY}`,
                    ];
                }

                const paths = [];

                let horizontalStart = 0;
                let horizontalEnd = jointWidth;
                if (shape === 'corner-ne' || shape === 'corner-se' || shape === 'tee-east') {
                    horizontalStart = midX;
                } else if (shape === 'corner-nw' || shape === 'corner-sw' || shape === 'tee-west') {
                    horizontalEnd = midX;
                }
                if (horizontalEnd - horizontalStart > 0) {
                    paths.push(`M ${horizontalStart} ${midY} L ${horizontalEnd} ${midY}`);
                }

                let verticalStart = 0;
                let verticalEnd = jointHeight;
                if (shape === 'corner-ne' || shape === 'corner-nw' || shape === 'tee-north') {
                    verticalEnd = midY;
                } else if (shape === 'corner-se' || shape === 'corner-sw' || shape === 'tee-south') {
                    verticalStart = midY;
                }
                if (verticalEnd - verticalStart > 0) {
                    paths.push(`M ${midX} ${verticalStart} L ${midX} ${verticalEnd}`);
                }

                return paths;
            }

            function appendRoadBridgeJoint(orientation, left, top, width, height, marked = true) {
                if (width <= 0 || height <= 0) {
                    return;
                }

                const bridge = document.createElement('span');
                bridge.className = `road-joint road-joint-bridge road-joint-bridge-${orientation}`;
                if (marked) {
                    bridge.classList.add('road-joint-bridge-marked');
                }
                bridge.style.left = `${left}px`;
                bridge.style.top = `${top}px`;
                bridge.style.width = `${width}px`;
                bridge.style.height = `${height}px`;
                scene.appendChild(bridge);
            }

            function appendRoadOverflowMask(left, top, width, height) {
                if (width <= 0 || height <= 0) {
                    return;
                }

                const mask = document.createElement('span');
                mask.className = 'road-joint road-joint-mask';
                mask.style.left = `${left}px`;
                mask.style.top = `${top}px`;
                mask.style.width = `${width}px`;
                mask.style.height = `${height}px`;
                mask.style.setProperty('--joint-bg-x', `${-left}px`);
                mask.style.setProperty('--joint-bg-y', `${-top}px`);
                scene.appendChild(mask);
            }

            function updateRoadJoints() {
                scene.querySelectorAll('.road-joint').forEach((joint) => joint.remove());
                const roads = roadElements().map((element) => {
                    const box = getElementBox(element);
                    return {
                        element,
                        box,
                        horizontal: isRoadElementHorizontal(element, box),
                    };
                });

                for (let i = 0; i < roads.length; i += 1) {
                    const a = roads[i];
                    const boxA = a.box;
                    for (let j = i + 1; j < roads.length; j += 1) {
                        const b = roads[j];
                        const boxB = b.box;
                        const left = Math.max(boxA.left, boxB.left);
                        const top = Math.max(boxA.top, boxB.top);
                        const right = Math.min(boxA.right, boxB.right);
                        const bottom = Math.min(boxA.bottom, boxB.bottom);
                        const width = right - left;
                        const height = bottom - top;
                        if (width <= 0 || height <= 0) {
                            continue;
                        }

                        const isAHorizontal = a.horizontal;
                        const isBHorizontal = b.horizontal;
                        if (isAHorizontal === isBHorizontal) {
                            continue;
                        }

                        const baseThickness = Math.min(
                            isAHorizontal ? boxA.height : boxA.width,
                            isBHorizontal ? boxB.height : boxB.width
                        );
                        const horizontalBox = isAHorizontal ? boxA : boxB;
                        const verticalBox = isAHorizontal ? boxB : boxA;
                        const branchThreshold = Math.max(10, Math.round(baseThickness * 0.35));
                        const northSpan = top - verticalBox.top;
                        const southSpan = verticalBox.bottom - bottom;
                        const westSpan = left - horizontalBox.left;
                        const eastSpan = horizontalBox.right - right;
                        const hasNorth = northSpan > branchThreshold;
                        const hasSouth = southSpan > branchThreshold;
                        const hasWest = westSpan > branchThreshold;
                        const hasEast = eastSpan > branchThreshold;
                        const jointShape = getRoadJointShape(hasNorth, hasSouth, hasWest, hasEast);
                        const overlapCenterX = left + (width / 2);
                        const overlapCenterY = top + (height / 2);
                        const jointWidth = Math.max(18, Math.round(verticalBox.width));
                        const jointHeight = Math.max(18, Math.round(horizontalBox.height));
                        const jointCoverPad = 2;
                        const jointLeft = overlapCenterX - (jointWidth / 2) - jointCoverPad;
                        const jointTop = overlapCenterY - (jointHeight / 2) - jointCoverPad;
                        const jointCanvasWidth = jointWidth + (jointCoverPad * 2);
                        const jointCanvasHeight = jointHeight + (jointCoverPad * 2);

                        const joint = document.createElement('span');
                        joint.className = 'road-joint';
                        joint.style.left = `${jointLeft}px`;
                        joint.style.top = `${jointTop}px`;
                        joint.style.width = `${jointCanvasWidth}px`;
                        joint.style.height = `${jointCanvasHeight}px`;
                        joint.style.setProperty('--joint-bg-x', `${-jointLeft}px`);
                        joint.style.setProperty('--joint-bg-y', `${-jointTop}px`);

                        const svgNS = 'http://www.w3.org/2000/svg';
                        const svg = document.createElementNS(svgNS, 'svg');
                        svg.setAttribute('viewBox', `0 0 ${jointCanvasWidth} ${jointCanvasHeight}`);
                        svg.setAttribute('width', `${jointCanvasWidth}`);
                        svg.setAttribute('height', `${jointCanvasHeight}`);
                        svg.style.display = 'block';
                        svg.style.overflow = 'hidden';

                        const group = document.createElementNS(svgNS, 'g');
                        group.setAttribute('transform', `translate(${jointCoverPad} ${jointCoverPad})`);

                        const shapePathData = buildRoadJointShapePath(jointWidth, jointHeight, jointShape);
                        const outlinePathData = buildRoadJointOutlinePath(jointWidth, jointHeight, jointShape);

                        const shapePath = document.createElementNS(svgNS, 'path');
                        shapePath.setAttribute('d', shapePathData);
                        shapePath.setAttribute('fill', '#cfd5d8');
                        group.appendChild(shapePath);

                        if (outlinePathData) {
                            const borderPath = document.createElementNS(svgNS, 'path');
                            borderPath.setAttribute('d', outlinePathData);
                            borderPath.setAttribute('fill', 'none');
                            borderPath.setAttribute('stroke', '#a7b0b4');
                            borderPath.setAttribute('stroke-width', '2');
                            borderPath.setAttribute('stroke-linejoin', 'round');
                            borderPath.setAttribute('stroke-linecap', 'round');
                            group.appendChild(borderPath);

                            const highlightPath = document.createElementNS(svgNS, 'path');
                            highlightPath.setAttribute('d', outlinePathData);
                            highlightPath.setAttribute('fill', 'none');
                            highlightPath.setAttribute('stroke', 'rgba(255,255,255,0.3)');
                            highlightPath.setAttribute('stroke-width', '1');
                            highlightPath.setAttribute('stroke-linejoin', 'round');
                            highlightPath.setAttribute('stroke-linecap', 'round');
                            group.appendChild(highlightPath);
                        }

                        const dashWidth = Math.max(2, Math.min(3, Math.round(baseThickness * 0.05)));
                        buildRoadJointDashPaths(jointWidth, jointHeight, jointShape).forEach((jointPath) => {
                            const path = document.createElementNS(svgNS, 'path');
                            path.setAttribute('d', jointPath);
                            path.setAttribute('fill', 'none');
                            path.setAttribute('stroke', '#ffffff');
                            path.setAttribute('stroke-width', String(dashWidth));
                            path.setAttribute('stroke-linecap', 'round');
                            path.setAttribute('stroke-dasharray', '9 8');
                            group.appendChild(path);
                        });

                        svg.appendChild(group);
                        joint.appendChild(svg);
                        scene.appendChild(joint);

                        if (jointShape.startsWith('corner-')) {
                            const overlapInset = 2;
                            const verticalCapTrim = Math.max(3, Math.ceil(verticalBox.width / 2) + jointCoverPad);
                            const horizontalCapTrim = Math.max(3, Math.ceil(horizontalBox.height / 2) + jointCoverPad);

                            if (!hasNorth) {
                                appendRoadOverflowMask(
                                    overlapCenterX - (verticalBox.width / 2) - overlapInset,
                                    jointTop - verticalCapTrim,
                                    verticalBox.width + (overlapInset * 2),
                                    verticalCapTrim + jointCoverPad + 2
                                );
                            }
                            if (!hasSouth) {
                                appendRoadOverflowMask(
                                    overlapCenterX - (verticalBox.width / 2) - overlapInset,
                                    jointTop + jointCanvasHeight - jointCoverPad,
                                    verticalBox.width + (overlapInset * 2),
                                    verticalCapTrim + jointCoverPad + 2
                                );
                            }
                            if (!hasWest) {
                                appendRoadOverflowMask(
                                    jointLeft - horizontalCapTrim,
                                    overlapCenterY - (horizontalBox.height / 2) - overlapInset,
                                    horizontalCapTrim + jointCoverPad + 2,
                                    horizontalBox.height + (overlapInset * 2)
                                );
                            }
                            if (!hasEast) {
                                appendRoadOverflowMask(
                                    jointLeft + jointCanvasWidth - jointCoverPad,
                                    overlapCenterY - (horizontalBox.height / 2) - overlapInset,
                                    horizontalCapTrim + jointCoverPad + 2,
                                    horizontalBox.height + (overlapInset * 2)
                                );
                            }
                        }
                    }
                }

                const bridgeKeys = new Set();

                for (let i = 0; i < roads.length; i += 1) {
                    for (let j = i + 1; j < roads.length; j += 1) {
                        const first = roads[i];
                        const second = roads[j];

                        if (first.horizontal !== second.horizontal) {
                            continue;
                        }

                        if (first.horizontal) {
                            const baseThickness = Math.min(first.box.height, second.box.height);
                            const centerYFirst = first.box.top + (first.box.height / 2);
                            const centerYSecond = second.box.top + (second.box.height / 2);
                            const alignThreshold = Math.max(12, Math.round(baseThickness * 0.45));
                            if (Math.abs(centerYFirst - centerYSecond) > alignThreshold) {
                                continue;
                            }

                            const leftRoad = first.box.left <= second.box.left ? first : second;
                            const rightRoad = leftRoad === first ? second : first;
                            const gapLeft = leftRoad.box.right;
                            const gapRight = rightRoad.box.left;
                            if (gapRight - gapLeft <= 0) {
                                continue;
                            }

                            const centerY = (centerYFirst + centerYSecond) / 2;
                            const bridgeRoad = roads.find((candidate) =>
                                !candidate.horizontal &&
                                candidate.box.left <= gapLeft + 1 &&
                                candidate.box.right >= gapRight - 1 &&
                                candidate.box.top <= centerY &&
                                candidate.box.bottom >= centerY
                            );

                            if (!bridgeRoad) {
                                continue;
                            }

                            const overlapPad = 1;
                            const capCover = Math.max(2, Math.round(baseThickness / 2));
                            const bridgeLeft = Math.min(leftRoad.box.left, rightRoad.box.left) - capCover;
                            const bridgeTop = centerY - (baseThickness / 2) - overlapPad;
                            const bridgeRight = Math.max(leftRoad.box.right, rightRoad.box.right) + capCover;
                            const bridgeWidth = bridgeRight - bridgeLeft;
                            const bridgeHeight = baseThickness + (overlapPad * 2);
                            const bridgeKey = `h:${Math.round(bridgeLeft)}:${Math.round(bridgeTop)}:${Math.round(bridgeWidth)}:${Math.round(bridgeHeight)}`;

                            if (bridgeKeys.has(bridgeKey)) {
                                continue;
                            }

                            bridgeKeys.add(bridgeKey);
                            const marked = Math.abs(
                                (leftRoad.box.top + (leftRoad.box.height / 2)) -
                                (rightRoad.box.top + (rightRoad.box.height / 2))
                            ) <= Math.max(6, Math.round(baseThickness * 0.18));
                            appendRoadBridgeJoint('horizontal', bridgeLeft, bridgeTop, bridgeWidth, bridgeHeight, marked);
                            continue;
                        }

                        const baseThickness = Math.min(first.box.width, second.box.width);
                        const centerXFirst = first.box.left + (first.box.width / 2);
                        const centerXSecond = second.box.left + (second.box.width / 2);
                        const alignThreshold = Math.max(12, Math.round(baseThickness * 1.1));
                        if (Math.abs(centerXFirst - centerXSecond) > alignThreshold) {
                            continue;
                        }

                        const topRoad = first.box.top <= second.box.top ? first : second;
                        const bottomRoad = topRoad === first ? second : first;
                        const gapTop = topRoad.box.bottom;
                        const gapBottom = bottomRoad.box.top;
                        if (gapBottom - gapTop <= 0) {
                            continue;
                        }

                        const centerX = (centerXFirst + centerXSecond) / 2;
                        const bridgeRoad = roads.find((candidate) =>
                            candidate.horizontal &&
                            candidate.box.top <= gapTop + 1 &&
                            candidate.box.bottom >= gapBottom - 1 &&
                            candidate.box.left <= centerX &&
                            candidate.box.right >= centerX
                        );

                        if (!bridgeRoad) {
                            continue;
                        }

                        const overlapPad = 1;
                        const capCover = Math.max(2, Math.round(baseThickness / 2));
                        const bridgeLeft = Math.min(topRoad.box.left, bottomRoad.box.left) - overlapPad;
                        const bridgeTop = gapTop - capCover;
                        const bridgeRight = Math.max(topRoad.box.right, bottomRoad.box.right) + overlapPad;
                        const bridgeWidth = bridgeRight - bridgeLeft;
                        const bridgeHeight = (gapBottom - gapTop) + (capCover * 2);
                        const bridgeKey = `v:${Math.round(bridgeLeft)}:${Math.round(bridgeTop)}:${Math.round(bridgeWidth)}:${Math.round(bridgeHeight)}`;

                        if (bridgeKeys.has(bridgeKey)) {
                            continue;
                        }

                        bridgeKeys.add(bridgeKey);
                        const marked = Math.abs(centerXFirst - centerXSecond) <= Math.max(6, Math.round(baseThickness * 0.18));
                        appendRoadBridgeJoint('vertical', bridgeLeft, bridgeTop, bridgeWidth, bridgeHeight, marked);
                    }
                }
            }

            viewport.addEventListener('pointerdown', (event) => {
                if (event.button !== 0) {
                    return;
                }

                isDragging = true;
                startX = event.clientX;
                startY = event.clientY;
                viewport.classList.add('dragging');
                viewport.setPointerCapture(event.pointerId);
            });

            viewport.addEventListener('pointermove', (event) => {
                if (!isDragging) {
                    return;
                }

                const dx = event.clientX - startX;
                const dy = event.clientY - startY;

                startX = event.clientX;
                startY = event.clientY;
                translateX += dx;
                translateY += dy;
                render();
            });

            viewport.addEventListener('pointerup', (event) => {
                isDragging = false;
                viewport.classList.remove('dragging');
                viewport.releasePointerCapture(event.pointerId);
            });

            viewport.addEventListener('pointercancel', () => {
                isDragging = false;
                viewport.classList.remove('dragging');
            });

            viewport.addEventListener('wheel', (event) => {
                event.preventDefault();
                const isPinchGesture = event.ctrlKey || event.metaKey;

                if (isPinchGesture) {
                    const factor = Math.exp(-event.deltaY * 0.0025);
                    zoomAt(event.clientX, event.clientY, factor);
                    return;
                }

                translateX -= event.deltaX;
                translateY -= event.deltaY;
                render();
            }, { passive: false });

            controls.forEach((button) => {
                button.addEventListener('click', () => {
                    const action = button.getAttribute('data-map-action');
                    const rect = viewport.getBoundingClientRect();
                    const centerX = rect.left + (rect.width / 2);
                    const centerY = rect.top + (rect.height / 2);

                    if (action === 'zoom-in') {
                        zoomAt(centerX, centerY, 1.15);
                    } else if (action === 'zoom-out') {
                        zoomAt(centerX, centerY, 0.87);
                    } else if (action === 'reset') {
                        fitToViewport();
                    }
                });
            });

            const refreshRoadLayout = () => {
                fitToViewport();
                updateRoadJoints();
            };

            scene.querySelectorAll('.road-lane').forEach((element) => {
                syncRoadLaneAppearance(element);
            });

            if (shouldHydrateFacilityItemsFromStorage) {
                loadFacilityItemsFromStorage();
            } else {
                clearFacilityItemsStorage();
            }
            loadSceneObjectsFromDatabase();
            refreshRoadLayout();
            requestAnimationFrame(refreshRoadLayout);
            window.addEventListener('load', refreshRoadLayout, { once: true });
            window.addEventListener('resize', refreshRoadLayout);

            plotElements.forEach((plot) => {
                plot.addEventListener('mouseenter', (event) => showHoverCard(plot, event));
                plot.addEventListener('mouseleave', queueHideHoverCard);
            });

            if (hoverCard) {
                hoverCard.addEventListener('mouseenter', () => {
                    isHoverCardActive = true;
                    if (hideTimer) {
                        window.clearTimeout(hideTimer);
                    }
                });

                hoverCard.addEventListener('mouseleave', () => {
                    isHoverCardActive = false;
                    queueHideHoverCard();
                });
            }
        })();
    </script>
    <script>
        (() => {
            const toggle = document.getElementById('aiChatbotToggle');
            const card = document.getElementById('aiChatbotCard');
            const form = document.getElementById('aiChatbotForm');
            const input = document.getElementById('aiChatbotInput');
            const body = document.getElementById('aiChatbotBody');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const askUrl = @json(route('chatbot.ask'));

            if (!toggle || !card || !form || !input || !body) {
                return;
            }

            const appendBubble = (role, text) => {
                const bubble = document.createElement('div');
                bubble.className = `ai-chatbot-bubble ${role}`;
                bubble.textContent = text;
                body.appendChild(bubble);
                body.scrollTop = body.scrollHeight;
                return bubble;
            };

            toggle.addEventListener('click', () => {
                card.classList.toggle('active');
                if (card.classList.contains('active')) {
                    input.focus();
                }
            });

            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                const message = input.value.trim();
                if (!message) {
                    return;
                }

                appendBubble('user', message);
                input.value = '';
                input.disabled = true;
                const loadingBubble = appendBubble('bot', 'Sedang memproses...');

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
                    loadingBubble.textContent = response.ok
                        ? (payload?.answer || 'Maaf, jawaban tidak tersedia.')
                        : (payload?.message || 'Terjadi kesalahan saat memproses pertanyaan.');
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
