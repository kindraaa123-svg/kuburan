<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $setting->systemname ?: 'Denah Kuburan' }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Space+Grotesk:wght@500;700&display=swap');

        :root {
            --bg: #eef3f1;
            --card: #ffffff;
            --text: #132a2f;
            --muted: #5e7277;
            --line: #d6e2de;
            --brand: #1f7a67;
            --brand-soft: #d9efe8;
            --danger: #d16b3c;
            --ok: #2f8f59;
            --info: #3f73c9;
            --gray: #7f8f95;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "DM Sans", sans-serif;
            color: var(--text);
            background:
                radial-gradient(900px 500px at 5% -10%, #d2e8e0 0%, transparent 60%),
                radial-gradient(800px 500px at 105% 0%, #e0ede8 0%, transparent 60%),
                var(--bg);
        }

        .shell {
            width: min(1240px, 93vw);
            margin: 0 auto;
            padding-bottom: 28px;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 20;
            border-bottom: 1px solid var(--line);
            background: rgba(238, 243, 241, 0.92);
            backdrop-filter: blur(8px);
        }

        .topbar-inner {
            width: min(1240px, 93vw);
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
            border: 1px solid #bfd4cd;
            background: #f3f8f6;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border: 1px solid #bfd4cd;
            border-radius: 999px;
            background: #f8fcfa;
            color: #36555f;
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

        .hero-main, .hero-side, .panel {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 18px;
            box-shadow: 0 10px 24px rgba(16, 41, 48, 0.06);
        }

        .hero-main {
            padding: 24px;
            background:
                linear-gradient(130deg, rgba(31, 122, 103, .07), rgba(255, 255, 255, 0)),
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
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 10px 12px;
            background: #fbfdfc;
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
            box-shadow: 0 10px 24px rgba(16, 41, 48, 0.05);
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
            border: 1px solid var(--line);
            border-radius: 14px;
            overflow: hidden;
            background: #f4f8f6;
        }

        .map-toolbar {
            display: flex;
            align-items: center;
            gap: 8px;
            justify-content: flex-end;
            padding: 10px;
            border-bottom: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.88);
        }

        .btn {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            border: 1px solid #bcd1c9;
            background: #fff;
            color: #1c3a3a;
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
            height: min(70vh, 620px);
            overflow: hidden;
            cursor: move;
            touch-action: none;
            user-select: none;
            background-color: #f8fbfa;
            background-image:
                linear-gradient(90deg, rgba(19, 42, 47, 0.08) 1px, transparent 1px),
                linear-gradient(rgba(19, 42, 47, 0.08) 1px, transparent 1px);
            background-size: 40px 40px;
            background-repeat: repeat;
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
            background-color: #f8fbfa;
            background-image:
                linear-gradient(90deg, rgba(19, 42, 47, 0.12) 1px, transparent 1px),
                linear-gradient(rgba(19, 42, 47, 0.12) 1px, transparent 1px);
            background-size: 40px 40px;
            background-position: 0 0, 0 0;
            background-repeat: repeat;
        }

        .block-area {
            position: absolute;
            border: 2px solid rgba(19, 42, 47, 0.18);
            border-radius: 14px;
            background: color-mix(in srgb, var(--block-color) 18%, #ffffff);
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.5);
            cursor: default;
            z-index: 3;
        }

        .block-badge {
            position: absolute;
            left: 10px;
            top: 8px;
            font-size: .75rem;
            color: #fff;
            background: var(--block-color);
            border-radius: 999px;
            padding: 4px 9px;
            font-weight: 700;
            cursor: default;
        }

        .plot {
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            border: 1px solid rgba(16, 45, 78, 0.15);
            font-size: .72rem;
            font-weight: 700;
            color: #fff;
            text-shadow: 0 1px 1px rgba(0, 0, 0, .22);
            cursor: default;
            z-index: 4;
        }

        .plot-occupied { background: var(--danger); }
        .plot-empty { background: var(--ok); }

        .road-lane {
            position: absolute;
            background: #e7ecea;
            border: 1px solid #c3d0cc;
            border-radius: 8px;
            z-index: 1;
            overflow: hidden;
        }

        .road-lane::after {
            content: "";
            position: absolute;
            background: repeating-linear-gradient(
                90deg,
                transparent 0,
                transparent 9px,
                #ffffff 9px,
                #ffffff 16px
            );
            opacity: 0.8;
        }

        .road-lane-vertical::after {
            top: 0;
            bottom: 0;
            left: 50%;
            width: 2px;
            transform: translateX(-50%);
            background: repeating-linear-gradient(
                180deg,
                transparent 0,
                transparent 9px,
                #ffffff 9px,
                #ffffff 16px
            );
        }

        .road-lane-horizontal::after {
            left: 0;
            right: 0;
            top: 50%;
            height: 2px;
            transform: translateY(-50%);
        }

        .entrance-gate {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            bottom: 16px;
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
            z-index: 6;
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

        .main-road {
            position: absolute;
            left: calc(50% - 36px);
            bottom: 62px;
            width: 72px;
            height: calc(100% - 130px);
            border-radius: 10px;
            background: #e7ecea;
            border: 1px solid #c3d0cc;
            z-index: 1;
            overflow: hidden;
        }

        .main-road::after {
            content: "";
            position: absolute;
            top: 0;
            bottom: 0;
            left: 50%;
            width: 2px;
            transform: translateX(-50%);
            background: repeating-linear-gradient(
                180deg,
                transparent 0,
                transparent 9px,
                #ffffff 9px,
                #ffffff 16px
            );
            opacity: 0.85;
        }

        .road-label {
            position: absolute;
            left: calc(50% + 44px);
            bottom: 140px;
            border-radius: 8px;
            border: 1px solid #c0ceca;
            background: #eef3f1;
            color: #2d5158;
            font-size: .66rem;
            font-weight: 700;
            padding: 5px 7px;
            z-index: 5;
        }

        .hover-card {
            position: fixed;
            z-index: 60;
            width: 228px;
            background: #ffffff;
            border: 1px solid #c9d8d3;
            border-radius: 12px;
            box-shadow: 0 14px 26px rgba(16, 41, 48, 0.18);
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
            border: 1px solid #a9c7bc;
            color: #12463d;
            background: #e9f4ef;
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
            border-top: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.68);
        }

        .footer-inner {
            min-height: 74px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            color: #365158;
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
            if (\Illuminate\Support\Str::startsWith($logo, ['http://', 'https://', '/'])) {
                $logoUrl = $logo;
            } elseif (\Illuminate\Support\Str::startsWith($logo, 'storage/')) {
                $logoUrl = asset($logo);
            } else {
                $logoUrl = asset('storage/' . ltrim($logo, '/'));
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
            <span class="badge">Data Real-Time</span>
        </div>
    </header>

    <main class="shell">
        <section class="hero">
            <article class="hero-main">
                <h1>{{ $setting->systemname ?: 'Denah Kuburan Terpadu' }}</h1>
                <p>
                    Menampilkan seluruh blok makam dalam satu tampilan peta yang bisa digeser dan di-zoom.
                    Posisi petak mengikuti koordinat denah yang tersimpan di sistem.
                </p>
            </article>
            <aside class="hero-side">
                <div class="mini">
                    <strong>Jumlah Blok</strong>
                    <span>{{ $blocks->count() }}</span>
                </div>
                <div class="mini">
                    <strong>Total Plot Aktif</strong>
                    <span>{{ (int) ($summary->total ?? 0) }}</span>
                </div>
            </aside>
        </section>

        <section class="stats">
            <article class="stat-card">
                <p>Total Petak</p>
                <h3>{{ (int) ($summary->total ?? 0) }}</h3>
            </article>
            <article class="stat-card">
                <p>Terisi</p>
                <h3>{{ (int) ($summary->occupied ?? 0) }}</h3>
            </article>
            <article class="stat-card">
                <p>Kosong</p>
                <h3>{{ max(0, (int) ($summary->total ?? 0) - (int) ($summary->occupied ?? 0)) }}</h3>
            </article>
        </section>

        <section class="panel">
            <div class="panel-head">
                <div>
                    <h2>Layout Semua Blok</h2>
                    <p>Drag untuk geser peta, scroll atau tombol untuk zoom.</p>
                </div>
                <div class="chips">
                    @foreach ($blockMaps as $blockMap)
                        <span class="chip">{{ $blockMap['name'] }}: {{ $blockMap['total_plots'] }} plot</span>
                    @endforeach
                </div>
            </div>

            <div class="map-panel">
                <div class="map-toolbar">
                    <button type="button" class="btn" data-map-action="zoom-out">-</button>
                    <span class="zoom" id="zoomLabel">100%</span>
                    <button type="button" class="btn" data-map-action="zoom-in">+</button>
                    <button type="button" class="btn btn-reset" data-map-action="reset">Reset</button>
                </div>

                <div class="map-viewport" id="mapViewport">
                    <div class="map-scene" id="mapScene" style="width: {{ $mapWidth }}px; height: {{ $mapHeight }}px;">
                        @foreach ($blockMaps as $blockMap)
                            @php
                                $roadGap = 20;
                                $lane = 14;
                                $roadX = (float) $blockMap['x'] - $roadGap;
                                $roadY = (float) $blockMap['y'] - $roadGap;
                                $roadW = (float) $blockMap['width'] + ($roadGap * 2);
                                $roadH = (float) $blockMap['height'] + ($roadGap * 2);
                            @endphp
                            <div
                                class="road-lane road-lane-horizontal"
                                style="
                                    left: {{ $roadX }}px;
                                    top: {{ $roadY }}px;
                                    width: {{ $roadW }}px;
                                    height: {{ $lane }}px;
                                "></div>
                            <div
                                class="road-lane road-lane-horizontal"
                                style="
                                    left: {{ $roadX }}px;
                                    top: {{ $roadY + $roadH - $lane }}px;
                                    width: {{ $roadW }}px;
                                    height: {{ $lane }}px;
                                "></div>
                            <div
                                class="road-lane road-lane-vertical"
                                style="
                                    left: {{ $roadX }}px;
                                    top: {{ $roadY }}px;
                                    width: {{ $lane }}px;
                                    height: {{ $roadH }}px;
                                "></div>
                            <div
                                class="road-lane road-lane-vertical"
                                style="
                                    left: {{ $roadX + $roadW - $lane }}px;
                                    top: {{ $roadY }}px;
                                    width: {{ $lane }}px;
                                    height: {{ $roadH }}px;
                                "></div>
                        @endforeach

                        @php
                            $entranceTarget = collect($blockMaps)->first(function (array $item): bool {
                                return strcasecmp(trim((string) ($item['name'] ?? '')), 'blok b') === 0;
                            });
                            if (! $entranceTarget) {
                                $entranceTarget = $blockMaps[1] ?? ($blockMaps[0] ?? null);
                            }
                            $entranceLeft = $entranceTarget
                                ? ((float) $entranceTarget['x'] + ((float) $entranceTarget['width'] / 2))
                                : ((float) $mapWidth / 2);

                            $mainTarget = collect($blockMaps)->first(function (array $item): bool {
                                return strcasecmp(trim((string) ($item['name'] ?? '')), 'blok a') === 0;
                            });
                            if (! $mainTarget) {
                                $mainTarget = $blockMaps[0] ?? null;
                            }

                            $branchLane = 14.0;
                            $mainRoadCenterX = $entranceLeft;
                            $mainRoadBottomY = (float) $mapHeight - 68.0;
                            $mainTargetX = $mainTarget
                                ? ((float) $mainTarget['x'] + ((float) $mainTarget['width'] / 2))
                                : $mainRoadCenterX;
                            $mainTargetY = $mainTarget
                                ? ((float) $mainTarget['y'] + ((float) $mainTarget['height'] / 2))
                                : ($mainRoadBottomY - 180.0);
                            $mainVerticalYStart = min($mainRoadBottomY, $mainTargetY);
                            $mainVerticalHeight = abs($mainRoadBottomY - $mainTargetY);
                            $mainHorizontalXStart = min($mainRoadCenterX, $mainTargetX);
                            $mainHorizontalWidth = abs($mainRoadCenterX - $mainTargetX);
                            $mainTargetId = (int) ($mainTarget['id'] ?? 0);
                        @endphp
                        <div
                            class="road-lane road-lane-vertical"
                            style="
                                left: {{ $mainRoadCenterX - ($branchLane / 2) }}px;
                                top: {{ $mainVerticalYStart }}px;
                                width: {{ $branchLane }}px;
                                height: {{ $mainVerticalHeight }}px;
                            "></div>
                        <div
                            class="road-lane road-lane-horizontal"
                            style="
                                left: {{ $mainHorizontalXStart }}px;
                                top: {{ $mainTargetY - ($branchLane / 2) }}px;
                                width: {{ $mainHorizontalWidth }}px;
                                height: {{ $branchLane }}px;
                            "></div>
                        @foreach ($blockMaps as $branchBlock)
                            @php
                                $branchId = (int) ($branchBlock['id'] ?? 0);
                                $blockCenterX = (float) $branchBlock['x'] + ((float) $branchBlock['width'] / 2);
                                $blockCenterY = (float) $branchBlock['y'] + ((float) $branchBlock['height'] / 2);
                                $verticalYStart = min($mainRoadBottomY, $blockCenterY);
                                $verticalHeight = abs($mainRoadBottomY - $blockCenterY);
                                $horizontalXStart = min($mainRoadCenterX, $blockCenterX);
                                $horizontalWidth = abs($mainRoadCenterX - $blockCenterX);
                            @endphp
                            @if ($branchId !== $mainTargetId)
                                <div
                                    class="road-lane road-lane-vertical"
                                    style="
                                        left: {{ $mainRoadCenterX - ($branchLane / 2) }}px;
                                        top: {{ $verticalYStart }}px;
                                        width: {{ $branchLane }}px;
                                        height: {{ $verticalHeight }}px;
                                    "></div>
                                <div
                                    class="road-lane road-lane-horizontal"
                                    style="
                                        left: {{ $horizontalXStart }}px;
                                        top: {{ $blockCenterY - ($branchLane / 2) }}px;
                                        width: {{ $horizontalWidth }}px;
                                        height: {{ $branchLane }}px;
                                    "></div>
                            @endif
                        @endforeach

                        @foreach ($blockMaps as $blockMap)
                            <div
                                class="block-area"
                                style="
                                    --block-color: {{ $blockMap['color'] }};
                                    left: {{ $blockMap['x'] }}px;
                                    top: {{ $blockMap['y'] }}px;
                                    width: {{ $blockMap['width'] }}px;
                                    height: {{ $blockMap['height'] }}px;
                                ">
                                <span class="block-badge">{{ $blockMap['name'] }}</span>
                            </div>
                        @endforeach

                        @foreach ($mapPlots as $plot)
                            <div
                                class="plot plot-{{ $plot['status'] }}"
                                data-status="{{ $plot['status'] }}"
                                data-name="{{ $plot['deceased_name'] ?? '' }}"
                                data-age="{{ $plot['deceased_age'] ?? '' }}"
                                data-death-date="{{ $plot['deceased_death_date'] ?? '' }}"
                                data-photo="{{ $plot['deceased_photo_url'] ?? '' }}"
                                data-plot-label="{{ $plot['plot_label'] }}"
                                data-detail-url="{{ $plot['deceased_id'] ? route('deceased.detail', ['id' => $plot['deceased_id']]) : '' }}"
                                style="
                                    left: {{ (float) $plot['x'] }}px;
                                    top: {{ (float) $plot['y'] }}px;
                                    width: {{ (float) $plot['width'] }}px;
                                    height: {{ (float) $plot['height'] }}px;
                                ">
                                {{ $plot['plot_number'] }}
                            </div>
                        @endforeach

                        <div class="entrance-gate" style="left: {{ $entranceLeft }}px;">Pintu Masuk Utama</div>
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

    <a class="chatbot-fab" href="{{ route('chatbot') }}" aria-label="Buka Chatbot" title="Chatbot">Chat</a>

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
                const padding = 36;
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
                const factor = event.deltaY < 0 ? 1.1 : 0.9;
                zoomAt(event.clientX, event.clientY, factor);
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

            window.addEventListener('resize', fitToViewport);
            fitToViewport();

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
</body>
</html>
