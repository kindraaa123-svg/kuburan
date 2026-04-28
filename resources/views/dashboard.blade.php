<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Denah Kuburan</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&family=Space+Grotesk:wght@600;700&display=swap');

        :root {
            --bg-top: #2c0915;
            --bg-bottom: #080306;
            --card: rgba(247, 239, 236, 0.98);
            --line: rgba(115, 38, 54, 0.2);
            --text: #2b1017;
            --muted: #77525b;
            --brand: #7a1129;
            --danger: #a83c52;
            --ok: #7a1129;
            --info: #8f3d53;
            --gray: #8a6770;
            --gate-icon: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 -8 120 100' fill='none' stroke='%231f1f1f' stroke-width='4' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M8 82h104'/%3E%3Cpath d='M10 20h28'/%3E%3Cpath d='M82 20h28'/%3E%3Cpath d='M38 20a22 22 0 0 1 44 0'/%3E%3Cpath d='M10 20v62'/%3E%3Cpath d='M110 20v62'/%3E%3Cpath d='M22 20v62'/%3E%3Cpath d='M98 20v62'/%3E%3Cpath d='M60 20v62'/%3E%3Cpath d='M16 56h88'/%3E%3Cpath d='M30 20v36'/%3E%3Cpath d='M44 20v36'/%3E%3Cpath d='M76 20v36'/%3E%3Cpath d='M90 20v36'/%3E%3Cpath d='M26 56v26'/%3E%3Cpath d='M36 56v26'/%3E%3Cpath d='M46 56v26'/%3E%3Cpath d='M56 56v26'/%3E%3Cpath d='M66 56v26'/%3E%3Cpath d='M76 56v26'/%3E%3Cpath d='M86 56v26'/%3E%3Cpath d='M94 56v26'/%3E%3C/svg%3E");
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: "Manrope", sans-serif;
            color: var(--text);
            background:
                radial-gradient(920px 520px at -8% -10%, rgba(170, 44, 73, 0.4) 0%, transparent 58%),
                radial-gradient(760px 440px at 106% 110%, rgba(124, 18, 43, 0.4) 0%, transparent 64%),
                linear-gradient(180deg, var(--bg-top) 0%, #14070d 45%, var(--bg-bottom) 100%);
        }

        .layout {
            display: grid;
            grid-template-columns: 300px 1fr;
            min-height: 100vh;
            gap: 14px;
            width: min(1360px, calc(100vw - 28px));
            margin: 14px auto;
        }

        .sidebar, .main-card {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 22px;
            box-shadow: 0 28px 58px rgba(22, 4, 9, 0.34);
        }

        .sidebar {
            padding: 16px;
            display: grid;
            grid-template-rows: auto auto 1fr auto;
            gap: 14px;
            max-height: calc(100vh - 28px);
            position: sticky;
            top: 14px;
            background:
                linear-gradient(180deg, rgba(255, 241, 243, 0.08), rgba(255, 241, 243, 0) 22%),
                rgba(28, 7, 14, 0.88);
            box-shadow: 0 26px 54px rgba(22, 4, 9, 0.4);
            backdrop-filter: blur(8px);
        }

        .logo {
            margin: 0;
            font-family: "Space Grotesk", sans-serif;
            font-size: 1.05rem;
            letter-spacing: 0.03em;
            color: #fff2ee;
        }

        .user-box {
            border: 1px solid rgba(255, 231, 235, 0.14);
            border-radius: 14px;
            padding: 10px;
            background: rgba(255, 241, 243, 0.08);
            font-size: .82rem;
            color: #dfbec3;
        }

        .user-box strong { display: block; font-size: .92rem; margin-top: 3px; color: #fff2ee; }

        .sidebar-menu {
            display: grid;
            align-content: start;
            gap: 8px;
        }

        .sidebar-bottom {
            display: grid;
            gap: 8px;
        }

        .sidebar-menu-item {
            display: block;
            border: 1px solid rgba(255, 231, 235, 0.14);
            border-radius: 12px;
            padding: 10px;
            background: rgba(255, 241, 243, 0.05);
            color: #fff2ee;
            text-decoration: none;
            font-weight: 700;
            font-size: .88rem;
            transition: transform .16s ease, background-color .16s ease, border-color .16s ease;
        }

        .sidebar-menu-item:hover {
            transform: translateX(2px);
            background: rgba(255, 241, 243, 0.12);
            border-color: rgba(255, 241, 243, 0.28);
        }

        .sidebar-menu-item.active {
            border-color: rgba(255, 188, 199, 0.7);
            background: rgba(122, 17, 41, 0.38);
            color: #fff7f4;
        }

        .logout-btn {
            width: 100%;
            min-height: 42px;
            border: 1px solid rgba(255, 241, 243, 0.24);
            border-radius: 10px;
            background: rgba(255, 241, 243, 0.08);
            color: #fff2ee;
            font-weight: 700;
            cursor: pointer;
        }

        .main-card {
            padding: 20px;
        }

        .head {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: flex-end;
            margin-bottom: 12px;
        }

        .head h1 {
            margin: 0;
            font-family: "Space Grotesk", sans-serif;
            font-size: 1.4rem;
        }

        .head p {
            margin: 5px 0 0;
            color: var(--muted);
            font-size: .84rem;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 8px;
            margin-bottom: 12px;
        }

        .stat {
            border: 1px solid rgba(115, 38, 54, 0.18);
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.66);
            padding: 9px;
        }

        .stat small { color: var(--muted); font-size: .7rem; font-weight: 700; }
        .stat strong { display: block; margin-top: 4px; font-size: 1.1rem; }

        .map-panel {
            border: 1px solid rgba(115, 38, 54, 0.18);
            border-radius: 13px;
            overflow: hidden;
            background: #fdf6f7;
        }

        .map-workspace {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            min-height: 680px;
        }

        .map-canvas {
            border-right: 0;
        }

        .map-panel.facility-open .map-workspace {
            grid-template-columns: minmax(0, 1fr) 250px;
        }

        .map-panel.facility-open .map-canvas {
            border-right: 1px solid var(--line);
        }

        .map-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            padding: 10px;
            border-bottom: 1px solid rgba(115, 38, 54, 0.18);
            background: rgba(255, 255, 255, 0.88);
        }

        .map-edit-tools {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .map-zoom-tools {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: auto;
        }

        .map-edit-note {
            font-size: .74rem;
            color: #77525b;
            font-weight: 700;
            display: none;
        }

        .map-edit-note.active {
            display: inline;
        }

        .btn {
            min-width: 34px;
            height: 34px;
            border: 1px solid rgba(115, 38, 54, 0.2);
            border-radius: 8px;
            background: #fff8f6;
            font-weight: 700;
            cursor: pointer;
            color: #6f3a48;
        }

        .btn-edit {
            min-width: 0;
            padding: 0 12px;
            background: #fff;
            color: #7a1129;
        }

        .btn-save {
            min-width: 0;
            padding: 0 12px;
            background: linear-gradient(130deg, #a52142 0%, var(--brand) 52%, #4a0615 100%);
            border-color: #6d0f24;
            color: #fff;
            display: none;
        }

        .btn-cancel {
            min-width: 0;
            padding: 0 12px;
            display: none;
        }

        .btn-save.active,
        .btn-cancel.active {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .zoom {
            min-width: 48px;
            text-align: center;
            color: var(--muted);
            font-size: .82rem;
            font-weight: 700;
            line-height: 34px;
        }

        .map-viewport {
            position: relative;
            height: min(82vh, 860px);
            overflow: hidden;
            cursor: move;
            user-select: none;
            touch-action: none;
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
            z-index: 2;
        }
        .block-area.empty-no-plot {
            display: none;
            border-style: dashed;
            opacity: 0.72;
        }

        .map-scene.editing .block-area {
            cursor: grab;
            box-shadow: 0 4px 12px rgba(12, 35, 40, 0.16);
        }
        .map-scene.editing .block-area.empty-no-plot {
            display: block;
        }
        .map-scene .block-area.empty-no-plot.pending-hidden {
            display: none !important;
        }

        .map-scene.editing .block-area.is-dragging {
            cursor: grabbing;
            box-shadow: 0 8px 18px rgba(12, 35, 40, 0.22);
        }

        .map-scene.editing .plot {
            pointer-events: none;
        }

        .map-scene.editing .facility-item {
            pointer-events: auto;
        }

        .map-scene.editing [data-edit-draggable="1"] {
            cursor: grab;
        }

        .map-scene.editing [data-edit-draggable="1"].is-dragging {
            cursor: grabbing;
        }

        .block-badge {
            position: absolute;
            left: 10px;
            top: -13px;
            font-size: .73rem;
            color: #4f1f2a;
            background: rgba(252, 255, 255, 0.95);
            border: 1px solid rgba(115, 38, 54, 0.28);
            border-radius: 999px;
            padding: 4px 9px;
            font-weight: 800;
            z-index: 1;
        }

        .plot {
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
            border: 1px solid rgba(10, 31, 35, .34);
            font-size: .72rem;
            font-weight: 800;
            color: #11292d;
            text-shadow: 0 1px 0 rgba(255, 255, 255, .3);
            cursor: default;
            z-index: 3;
        }

        .plot-empty { background: color-mix(in srgb, var(--plot-color) 88%, #fff 12%); }
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

        .map-scene.editing .road-lane {
            overflow: visible;
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

        .facility-panel {
            background: #fbfdfc;
            padding: 10px;
            overflow: auto;
            display: none;
        }

        .map-panel.facility-open .facility-panel {
            display: block;
        }

        .facility-panel h4 {
            margin: 0;
            font-size: .9rem;
            font-family: "Space Grotesk", sans-serif;
        }

        .facility-panel p {
            margin: 6px 0 10px;
            font-size: .76rem;
            color: var(--muted);
        }

        .facility-list {
            display: grid;
            gap: 8px;
        }

        .facility-btn {
            width: 100%;
            min-height: 40px;
            border: 1px solid rgba(115, 38, 54, 0.2);
            border-radius: 10px;
            background: #fff8f6;
            text-align: left;
            padding: 8px 10px;
            cursor: pointer;
            font-weight: 700;
            color: #6f3a48;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .facility-btn:hover {
            border-color: rgba(122, 17, 41, 0.28);
            background: rgba(122, 17, 41, 0.08);
        }

        .facility-btn[data-facility-key="pohon"] > span:first-child {
            width: 24px;
            height: 24px;
            border-radius: 999px;
            border: 2px solid #2f8637;
            background: #3ea944;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, .14);
            position: relative;
            flex: 0 0 auto;
            font-size: 0;
            color: transparent;
            text-indent: -9999px;
            overflow: hidden;
        }

        .facility-btn[data-facility-key="pohon"] > span:first-child::before {
            content: "";
            position: absolute;
            left: 4px;
            top: 3px;
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: #bde588;
        }

        .facility-btn[data-facility-key="jalan"] > span:first-child {
            width: 28px;
            height: 16px;
            border-radius: 999px;
            border: 1px solid #9aa8ad;
            background: #c8d0d4;
            position: relative;
            flex: 0 0 auto;
            font-size: 0;
            color: transparent;
            text-indent: -9999px;
            overflow: hidden;
        }

        .facility-btn[data-facility-key="jalan"] > span:first-child::before {
            content: "";
            position: absolute;
            left: 5px;
            right: 5px;
            top: 50%;
            height: 2px;
            transform: translateY(-50%);
            background: repeating-linear-gradient(90deg, #ffffff 0, #ffffff 4px, transparent 4px, transparent 8px);
            opacity: 0.95;
        }

        .facility-btn[data-facility-key="pintu_masuk"] > span:first-child {
            width: 30px;
            height: 22px;
            border: 0;
            background: var(--gate-icon) center / contain no-repeat;
            position: relative;
            flex: 0 0 auto;
            font-size: 0;
            color: transparent;
            text-indent: -9999px;
            overflow: hidden;
        }

        .facility-note {
            margin-top: 10px;
            font-size: .72rem;
            color: #60787d;
            line-height: 1.35;
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
            cursor: default;
            z-index: 9;
            box-shadow: 0 4px 12px rgba(14, 41, 48, .17);
            transform: rotate(var(--facility-rotation, 0deg));
            transform-origin: 50% 50%;
            overflow: visible;
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

        .map-scene.editing .facility-item {
            cursor: grab;
        }

        .map-scene.editing .facility-item.is-dragging {
            cursor: grabbing;
        }

        .facility-resize-handle {
            position: absolute;
            width: 14px;
            height: 14px;
            border-radius: 999px;
            border: 1px solid #89a9a1;
            background: #f8fffc;
            box-shadow: 0 2px 6px rgba(14, 41, 48, .2);
            display: none;
            z-index: 12;
        }

        .facility-resize-handle[data-corner="nw"] {
            left: -6px;
            top: -6px;
            cursor: nwse-resize;
        }

        .facility-resize-handle[data-corner="ne"] {
            right: -6px;
            top: -6px;
            cursor: nesw-resize;
        }

        .facility-resize-handle[data-corner="sw"] {
            left: -6px;
            bottom: -6px;
            cursor: nesw-resize;
        }

        .facility-resize-handle[data-corner="se"] {
            right: -6px;
            bottom: -6px;
            cursor: nwse-resize;
        }

        .map-scene.editing .facility-item.selected .facility-resize-handle {
            display: block;
        }

        .map-scene.editing .road-lane.selected .facility-resize-handle {
            display: block;
        }

        .facility-rotate-handle {
            position: absolute;
            left: 50%;
            top: -26px;
            width: 14px;
            height: 14px;
            border-radius: 999px;
            border: 1px solid #89a9a1;
            background: #f8fffc;
            box-shadow: 0 2px 6px rgba(14, 41, 48, .2);
            cursor: grab;
            transform: translateX(-50%);
            display: none;
            z-index: 12;
        }

        .facility-rotate-handle::after {
            content: "";
            position: absolute;
            left: 50%;
            top: 13px;
            width: 2px;
            height: 10px;
            background: #89a9a1;
            transform: translateX(-50%);
        }

        .map-scene.editing .facility-item.selected .facility-rotate-handle {
            display: block;
        }

        .facility-actions {
            display: none;
        }

        .facility-action-btn {
            width: 30px;
            height: 30px;
            border: 1px solid #c0d2cc;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            color: #6f3a48;
            font-size: .9rem;
            font-weight: 800;
            cursor: pointer;
            line-height: 1;
        }

        .facility-action-btn.confirm {
            background: rgba(122, 17, 41, 0.1);
            border-color: rgba(122, 17, 41, 0.26);
            color: #7a1129;
        }

        .facility-action-btn.delete {
            background: rgba(146, 34, 59, 0.12);
            border-color: rgba(146, 34, 59, 0.28);
            color: #92223b;
        }

        .scene-action-card {
            position: absolute;
            z-index: 25;
            display: none;
            gap: 8px;
            background: #fff;
            border: 1px solid rgba(115, 38, 54, 0.2);
            border-radius: 999px;
            box-shadow: 0 6px 16px rgba(14, 41, 48, .2);
            padding: 7px 8px;
        }

        .scene-action-card.active {
            display: inline-flex;
        }

        .facility-item.position-fixed,
        [data-edit-draggable="1"].position-fixed {
            box-shadow: 0 0 0 2px rgba(122, 17, 41, .65), 0 6px 16px rgba(22, 4, 9, .2);
        }

        .road-lane.position-fixed,
        .facility-item[data-facility-key="jalan"].position-fixed {
            box-shadow: inset 0 0 0 2px rgba(255, 255, 255, .15) !important;
        }

        [data-edit-draggable="1"].scene-object-hidden {
            display: none !important;
        }

        .hover-card {
            position: fixed;
            z-index: 60;
            width: 228px;
            background: #ffffff;
            border: 1px solid rgba(115, 38, 54, 0.2);
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
            border: 1px solid rgba(122, 17, 41, 0.26);
            color: #7a1129;
            background: rgba(122, 17, 41, 0.1);
            font-size: .7rem;
            font-weight: 700;
            min-height: 30px;
            pointer-events: auto;
        }

        .legend {
            margin-top: 10px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            color: var(--muted);
            font-size: .8rem;
        }

        .dot { width: 11px; height: 11px; border-radius: 3px; display: inline-block; margin-right: 5px; }

        @media (max-width: 1080px) {
            .layout { grid-template-columns: 1fr; }
            .sidebar { position: static; max-height: none; }
            .stats { grid-template-columns: repeat(3, minmax(0, 1fr)); }
            .map-workspace { grid-template-columns: 1fr; }
            .map-canvas { border-right: 0; border-bottom: 1px solid var(--line); }
        }

        @media (max-width: 700px) {
            .stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
    </style>
</head>
<body>
    <main class="layout">
        <aside class="sidebar">
            <h2 class="logo">Dashboard Kuburan</h2>
            <div class="user-box">
                Login sebagai
                <strong>{{ (\Illuminate\Support\Facades\Schema::hasTable('employer') ? (\Illuminate\Support\Facades\DB::table('employer')->where('userid', (int) ($authUser['id'] ?? 0))->value('name') ?: ($authUser['username'] ?? 'user')) : ($authUser['username'] ?? 'user')) }}</strong>
            </div>
            @php
                $levelId = (int) ($authUser['levelid'] ?? 0);
                $allowedMenuKeys = null;
                if (\Illuminate\Support\Facades\Schema::hasTable('level_sidebar_access')) {
                    if ($levelId > 0) {
                        $allowedMenuKeys = \Illuminate\Support\Facades\DB::table('level_sidebar_access')
                            ->where('levelid', $levelId)
                            ->pluck('menu_key')
                            ->all();
                    } else {
                        $allowedMenuKeys = [];
                    }
                }
                $canAccessSidebarMenu = static function (string $menuKey) use ($allowedMenuKeys): bool {
                    if (in_array($menuKey, ['dashboard', 'account', 'logout'], true)) {
                        return true;
                    }
                    if ($allowedMenuKeys === null) {
                        return true;
                    }
                    return in_array($menuKey, $allowedMenuKeys, true);
                };
            @endphp
            <nav class="sidebar-menu">
                <a href="{{ route('dashboard') }}" class="sidebar-menu-item active">Dashboard</a>
                @if ($canAccessSidebarMenu('data-blok'))
                <a href="{{ route('dashboard.data-blok') }}" class="sidebar-menu-item">Data Blok</a>
                @endif
                @if ($canAccessSidebarMenu('data-plot'))
                <a href="{{ route('dashboard.data-plot') }}" class="sidebar-menu-item">Data Plot</a>
                @endif
                @if ($canAccessSidebarMenu('data-almarhum'))
                <a href="{{ route('dashboard.data-almarhum') }}" class="sidebar-menu-item">Data Almarhum</a>
                @endif
                @if ($canAccessSidebarMenu('data-kontak-keluarga'))
                <a href="{{ route('dashboard.data-kontak-keluarga') }}" class="sidebar-menu-item">Data Kontak Keluarga</a>
                @endif
                @if ($canAccessSidebarMenu('data-user'))
                <a href="{{ route('dashboard.data-user') }}" class="sidebar-menu-item">Data User</a>
                @endif
                @if ($canAccessSidebarMenu('activity-log'))
                <a href="{{ route('dashboard.activity-log') }}" class="sidebar-menu-item">Activity Log</a>
                @endif
                @if ($canAccessSidebarMenu('restore-data'))
                <a href="{{ route('dashboard.restore-data') }}" class="sidebar-menu-item">Restore Data</a>
                @endif
                @if ($canAccessSidebarMenu('hak-akses'))
                <a href="{{ route('dashboard.hak-akses') }}" class="sidebar-menu-item">Hak Akses</a>
                @endif
                @if ($canAccessSidebarMenu('settings'))
                <a href="{{ route('dashboard.settings') }}" class="sidebar-menu-item">Pengaturan</a>
                @endif
            </nav>
            <div class="sidebar-bottom">
                <a href="{{ route('dashboard.account') }}" class="sidebar-menu-item">Akun</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </aside>

        <section class="main-card">
            <div class="head">
                <div>
                    <h1>Denah Kuburan</h1>
                    <p>Drag untuk geser peta, scroll/tombol untuk zoom.</p>
                </div>
            </div>

            <div class="stats">
                <div class="stat"><small>Total Petak</small><strong>{{ (int) ($summary->total ?? 0) }}</strong></div>
                <div class="stat"><small>Terisi</small><strong>{{ (int) ($summary->occupied ?? 0) }}</strong></div>
                <div class="stat"><small>Kosong</small><strong>{{ (int) ($summary->empty ?? 0) }}</strong></div>
            </div>

            <div class="map-panel" id="mapPanel">
                <div class="map-toolbar">
                    <div class="map-edit-tools">
                        @if ($canAccessSidebarMenu('data-blok'))
                            <button class="btn btn-edit" type="button" id="mapEditBtn">Edit Posisi</button>
                            <button class="btn btn-save" type="button" id="mapSaveBtn">Simpan Posisi</button>
                            <button class="btn btn-cancel" type="button" id="mapCancelBtn">Batal</button>
                        @endif
                        <span class="map-edit-note" id="mapEditNote">Mode edit aktif: geser blok, tambah facility dari panel kanan, lalu centang untuk fix posisi.</span>
                    </div>
                    <div class="map-zoom-tools">
                        <button class="btn" type="button" data-map-action="zoom-out">-</button>
                        <span class="zoom" id="zoomLabel">100%</span>
                        <button class="btn" type="button" data-map-action="zoom-in">+</button>
                        <button class="btn" type="button" data-map-action="reset">Reset</button>
                    </div>
                </div>
                <div class="map-workspace">
                    <div class="map-canvas">
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
                                    <div class="block-area" data-block-id="{{ (int) $blockLayout['id'] }}" style="--block-color: {{ $blockLayout['color'] }}; left: {{ $blockLayout['x'] }}px; top: {{ $blockLayout['y'] }}px; width: {{ $blockLayout['width'] }}px; height: {{ $blockLayout['height'] }}px;">
                                        <span class="block-badge">{{ strtoupper($blockLayout['name']) }}</span>
                                    </div>
                                @endforeach
                                @foreach ($blocksWithoutPlots as $emptyBlock)
                                    @php
                                        $emptyBlockX = isset($emptyBlock->map_x) ? (int) $emptyBlock->map_x : 24;
                                        $emptyBlockY = isset($emptyBlock->map_y) ? (int) $emptyBlock->map_y : 40;
                                        $emptyBlockColor = (string) ($emptyBlock->map_color ?: '#D8E4DF');
                                        $emptyBlockIsHidden = !empty($hiddenBlocksInFacility) && collect($hiddenBlocksInFacility)->contains(fn ($item) => (int) ($item->blockid ?? 0) === (int) ($emptyBlock->blockid ?? 0));
                                    @endphp
                                    <div class="block-area empty-no-plot{{ $emptyBlockIsHidden ? ' pending-hidden' : '' }}" data-block-id="{{ (int) $emptyBlock->blockid }}" data-empty-no-plot="1" data-hidden-from-facility="{{ $emptyBlockIsHidden ? '1' : '0' }}" style="--block-color: {{ $emptyBlockColor }}; left: {{ $emptyBlockX }}px; top: {{ $emptyBlockY }}px; width: 480px; height: 300px;">
                                        <span class="block-badge">{{ strtoupper((string) $emptyBlock->block_name) }}</span>
                                    </div>
                                @endforeach
                                @foreach ($displayPlots as $plot)
                                    <a href="{{ $plot['deceased_id'] ? route('deceased.detail', ['id' => $plot['deceased_id']]) : '#' }}" class="plot plot-{{ $plot['status'] }}" data-status="{{ $plot['status'] }}" data-name="{{ $plot['deceased_name'] }}" data-age="{{ $plot['deceased_age'] }}" data-death-date="{{ $plot['deceased_death_date'] }}" data-photo="{{ $plot['deceased_photo_url'] }}" data-plot-label="{{ $plot['plot_label'] }}" data-detail-url="{{ $plot['deceased_id'] ? route('deceased.detail', ['id' => $plot['deceased_id']]) : '' }}" data-block-id="{{ (int) $plot['block_id'] }}" style="--plot-color: {{ $plot['color'] }}; left: {{ $plot['left'] }}px; top: {{ $plot['top'] }}px; width: {{ $plot['width'] }}px; height: {{ $plot['height'] }}px; text-decoration: none;">{{ $plot['number'] }}</a>
                                @endforeach
                                @foreach ($facilityMapItems as $facilityItem)
                                    @php
                                        $facility = $facilities->firstWhere('facilityid', (int) $facilityItem->facility_id);
                                        $facilityKey = $facility?->facility_key ?? 'facility';
                                        $facilityIcon = $facility?->icon_emoji ?: 'F';
                                    @endphp
                                    <div class="facility-item fixed position-fixed" data-map-item-id="{{ (int) $facilityItem->facility_map_itemid }}" data-facility-id="{{ (int) $facilityItem->facility_id }}" data-facility-key="{{ $facilityKey }}" data-facility-icon="{{ $facilityIcon }}" data-facility-name="{{ $facility?->facility_name ?? 'Fasilitas' }}" data-is-fixed="1" data-locked="1" data-rotation="{{ (float) ($facilityItem->map_rotation ?? 0) }}" style="left: {{ (int) $facilityItem->map_x }}px; top: {{ (int) $facilityItem->map_y }}px;{{ isset($facilityItem->map_width) && $facilityItem->map_width ? ' width: ' . (int) $facilityItem->map_width . 'px;' : '' }}{{ isset($facilityItem->map_height) && $facilityItem->map_height ? ' height: ' . (int) $facilityItem->map_height . 'px;' : '' }} --facility-rotation: {{ (float) ($facilityItem->map_rotation ?? 0) }}deg;" title="{{ $facility?->facility_name ?? 'Fasilitas' }}">
                                        <span>{{ $facilityIcon }}</span>
                                        <span class="facility-actions"><button type="button" class="facility-action-btn confirm" data-facility-action="confirm">&#10003;</button><button type="button" class="facility-action-btn delete" data-facility-action="delete">x</button></span>
                                    </div>
                                @endforeach
                                <div class="entrance-gate" data-edit-draggable="1" data-scene-object-id="entrance_main_gate" data-scene-facility-id="{{ (int) ($facilityIdByKey['pintu_masuk'] ?? 0) }}" data-scene-facility-key="pintu_masuk" style="left: {{ ($layoutWidth / 2) - 70 }}px; top: {{ $layoutHeight - 46 }}px;">Pintu Masuk Utama</div>
                                <div class="scene-action-card" id="sceneActionCard">
                                    <button type="button" class="facility-action-btn confirm" data-scene-action="confirm">&#10003;</button>
                                    <button type="button" class="facility-action-btn delete" data-scene-action="delete">x</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <aside class="facility-panel">
                        <h4>Data Facility</h4>
                        <p>Klik item untuk menaruh di denah, lalu geser posisinya.</p>
                        <div class="facility-list" id="facilityList">
                            @forelse ($facilities as $facility)
                                <button
                                    type="button"
                                    class="facility-btn"
                                    data-facility-id="{{ (int) $facility->facilityid }}"
                                    data-facility-key="{{ $facility->facility_key }}"
                                    data-facility-name="{{ $facility->facility_name }}"
                                    data-facility-icon="{{ $facility->icon_emoji ?: 'F' }}"
                                >
                                    <span>{{ $facility->icon_emoji ?: 'F' }}</span>
                                    <span>{{ $facility->facility_name }}</span>
                                </button>
                            @empty
                                <span class="facility-note">Data facility belum tersedia di tabel <code>facility</code>.</span>
                            @endforelse

                            @if (!empty($hiddenBlocksInFacility) && count($hiddenBlocksInFacility) > 0)
                                <div class="facility-note" style="margin-top: 10px; border-top: 1px dashed rgba(122,17,41,.2); padding-top: 10px;">
                                    Blok tersimpan dari denah:
                                </div>
                                @foreach ($hiddenBlocksInFacility as $savedBlock)
                                    <button
                                        type="button"
                                        class="facility-btn"
                                        data-facility-id="0"
                                        data-facility-key="blok_tersimpan"
                                        data-restore-block-id="{{ (int) $savedBlock->blockid }}"
                                        data-facility-name="Blok {{ $savedBlock->block_name }}"
                                        data-facility-icon="B"
                                        title="Klik saat mode edit untuk memunculkan lagi blok di denah"
                                    >
                                        <span>B</span>
                                        <span>Blok {{ $savedBlock->block_name }}</span>
                                    </button>
                                @endforeach
                            @endif
                        </div>
                        <div class="facility-note">
                            Saat mode edit aktif, arahkan kursor ke item facility di denah untuk melihat tombol centang (fix posisi) atau x (hapus).
                        </div>
                    </aside>
                </div>
            </div>

            <div class="legend">
                <span><i class="dot" style="background: var(--danger);"></i>Terisi</span>
                <span><i class="dot" style="background: var(--ok);"></i>Kosong</span>
            </div>
        </section>
    </main>

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

    <script>
        (() => {
            const viewport = document.getElementById('mapViewport');
            const scene = document.getElementById('mapScene');
            const sceneActionCard = document.getElementById('sceneActionCard');
            const mapPanel = document.getElementById('mapPanel');
            const zoomLabel = document.getElementById('zoomLabel');
            const controls = document.querySelectorAll('[data-map-action]');
            const hoverCard = document.getElementById('plotHoverCard');
            const hoverPhoto = document.getElementById('hoverPhoto');
            const hoverName = document.getElementById('hoverName');
            const hoverPlot = document.getElementById('hoverPlot');
            const hoverAge = document.getElementById('hoverAge');
            const hoverDeathDate = document.getElementById('hoverDeathDate');
            const hoverDetailBtn = document.getElementById('hoverDetailBtn');
            const mapEditBtn = document.getElementById('mapEditBtn');
            const mapSaveBtn = document.getElementById('mapSaveBtn');
            const mapCancelBtn = document.getElementById('mapCancelBtn');
            const mapEditNote = document.getElementById('mapEditNote');
            const plotElements = document.querySelectorAll('.plot');
            const blockElements = document.querySelectorAll('.block-area');
            const facilityList = document.getElementById('facilityList');
            const savePositionsUrl = @json(route('dashboard.data-blok.positions'));
            const csrfToken = @json(csrf_token());
            const initialSceneMapItems = @json(($sceneMapItems ?? collect())->values());
            const initialFacilityMapStorage = @json($facilityMapStorage ?? 'none');
            const facilityMapShapePersisted = @json($facilityMapShapePersisted ?? false);
            const sceneStorageKey = 'kuburan.dashboard.scene-objects.v1';
            const sceneStorageSyncKey = 'kuburan.dashboard.scene-objects.sync.v1';
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
            let isEditMode = false;
            let blockDragState = null;
            let facilityDragState = null;
            let facilityResizeState = null;
            let facilityRotateState = null;
            let sceneObjectDragState = null;
            let sceneObjectResizeState = null;
            let actionCardTarget = null;
            let isSavingPositions = false;
            let temporaryFacilityCounter = 0;
            let hiddenEmptyBlockIds = new Set();
            const initialBlockPositions = new Map();
            const initialHiddenEmptyBlockIds = new Set();
            const plotByBlockId = new Map();
            let initialFacilitySnapshot = [];
            let initialSceneObjectSnapshot = [];
            function render() {
                scene.style.transform = `translate(${translateX}px, ${translateY}px) scale(${scale})`;
                zoomLabel.textContent = `${Math.round(scale * 100)}%`;
            }

            function setBlockPosition(blockElement, x, y) {
                blockElement.style.left = `${x}px`;
                blockElement.style.top = `${y}px`;
            }

            function getBlockPosition(blockElement) {
                const left = parseFloat(blockElement.style.left || '0');
                const top = parseFloat(blockElement.style.top || '0');
                return {
                    x: Number.isFinite(left) ? left : 0,
                    y: Number.isFinite(top) ? top : 0,
                };
            }

            function toggleEditMode(nextState) {
                isEditMode = nextState;
                scene.classList.toggle('editing', isEditMode);
                if (mapPanel) {
                    mapPanel.classList.toggle('facility-open', isEditMode);
                }

                if (mapEditNote) {
                    mapEditNote.classList.toggle('active', isEditMode);
                }
                if (mapSaveBtn) {
                    mapSaveBtn.classList.toggle('active', isEditMode);
                }
                if (mapCancelBtn) {
                    mapCancelBtn.classList.toggle('active', isEditMode);
                }
                if (mapEditBtn) {
                    mapEditBtn.style.display = isEditMode ? 'none' : 'inline-flex';
                }

                if (isEditMode) {
                    hideHoverCard();
                    initialBlockPositions.clear();
                    initialHiddenEmptyBlockIds.clear();
                    hiddenEmptyBlockIds = new Set();
                    initialFacilitySnapshot = serializeFacilityItems();
                    initialSceneObjectSnapshot = serializeSceneObjects();

                    // Setelah simpan, item tetap fixed untuk disimpan ke DB, tapi mode edit berikutnya harus bisa diubah lagi.
                    facilityElements().forEach((facilityElement) => {
                        facilityElement.dataset.locked = '0';
                        facilityElement.classList.remove('position-fixed');
                    });
                    sceneObjects().forEach((sceneObject) => {
                        sceneObject.dataset.locked = '0';
                        sceneObject.classList.remove('position-fixed');
                    });

                    blockElements.forEach((blockElement) => {
                        const blockId = parseInt(blockElement.dataset.blockId || '0', 10);
                        if (!Number.isFinite(blockId) || blockId <= 0) {
                            return;
                        }
                        if (blockElement.dataset.emptyNoPlot === '1' && blockElement.dataset.hiddenFromFacility === '1') {
                            blockElement.classList.add('pending-hidden');
                            hiddenEmptyBlockIds.add(blockId);
                            initialHiddenEmptyBlockIds.add(blockId);
                        } else if (blockElement.dataset.emptyNoPlot === '1') {
                            blockElement.classList.remove('pending-hidden');
                        }
                        initialBlockPositions.set(blockId, getBlockPosition(blockElement));
                    });
                }
            }

            function clampScale(v) {
                return Math.min(maxScale, Math.max(minScale, v));
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
                if (isEditMode) {
                    return;
                }
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
                const fitScale = clampScale(Math.min(viewWidth / scene.offsetWidth, viewHeight / scene.offsetHeight, 1));
                scale = fitScale;
                translateX = (viewport.clientWidth - (scene.offsetWidth * scale)) / 2;
                translateY = (viewport.clientHeight - (scene.offsetHeight * scale)) / 2;
                render();
            }

            function applyBlockDeltaToPlots(blockId, dx, dy) {
                const relatedPlots = plotByBlockId.get(blockId) || [];
                relatedPlots.forEach((plotElement) => {
                    const left = parseFloat(plotElement.style.left || '0');
                    const top = parseFloat(plotElement.style.top || '0');
                    const nextLeft = (Number.isFinite(left) ? left : 0) + dx;
                    const nextTop = (Number.isFinite(top) ? top : 0) + dy;
                    plotElement.style.left = `${nextLeft}px`;
                    plotElement.style.top = `${nextTop}px`;
                });
            }

            function restoreInitialPositions() {
                hiddenEmptyBlockIds = new Set(initialHiddenEmptyBlockIds);
                blockElements.forEach((blockElement) => {
                    const blockId = parseInt(blockElement.dataset.blockId || '0', 10);
                    if (!Number.isFinite(blockId) || blockId <= 0) {
                        return;
                    }

                    const previous = initialBlockPositions.get(blockId);
                    if (!previous) {
                        return;
                    }

                    const current = getBlockPosition(blockElement);
                    const dx = previous.x - current.x;
                    const dy = previous.y - current.y;
                    setBlockPosition(blockElement, previous.x, previous.y);
                    applyBlockDeltaToPlots(blockId, dx, dy);
                    if (blockElement.dataset.emptyNoPlot === '1') {
                        if (hiddenEmptyBlockIds.has(blockId)) {
                            blockElement.classList.add('pending-hidden');
                            blockElement.dataset.hiddenFromFacility = '1';
                        } else {
                            blockElement.classList.remove('pending-hidden');
                            blockElement.dataset.hiddenFromFacility = '0';
                        }
                    }
                });
            }

            function facilityElements() {
                return Array.from(scene.querySelectorAll('.facility-item'));
            }

            function sceneObjects() {
                return Array.from(scene.querySelectorAll('[data-edit-draggable="1"][data-scene-object-id]'));
            }

            function roadElements() {
                return Array.from(scene.querySelectorAll('.road-lane, .facility-item[data-facility-key="jalan"]'))
                    .filter((element) => !element.classList.contains('scene-object-hidden') && element.dataset.removed !== '1');
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

            function serializeSceneObjects() {
                return sceneObjects().map((element) => ({
                    id: element.dataset.sceneObjectId || '',
                    left: parseFloat(element.style.left || '0'),
                    top: parseFloat(element.style.top || '0'),
                    width: element.offsetWidth || parseFloat(element.style.width || '0') || null,
                    height: element.offsetHeight || parseFloat(element.style.height || '0') || null,
                    rotation: parseFloat(element.dataset.rotation || '0'),
                    removed: element.dataset.removed === '1' || element.classList.contains('scene-object-hidden'),
                    locked: element.dataset.locked === '1',
                }));
            }

            function applySceneObjectState(item) {
                if (!item) {
                    return;
                }
                const objectKey = item.id ?? item.scene_object_key ?? null;
                if (!objectKey) {
                    return;
                }

                const target = sceneObjects().find((element) => (element.dataset.sceneObjectId || '') === String(objectKey));
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
                const isRemoved = removedSource === true || removedSource === 1 || removedSource === '1' || removedSource === 'true';
                target.dataset.removed = isRemoved ? '1' : '0';
                target.classList.toggle('scene-object-hidden', isRemoved);
                const isLocked = item.locked === true || item.locked === 1 || item.locked === '1' || item.locked === 'true';
                setElementLocked(target, isLocked);
                if (target.classList.contains('road-lane')) {
                    updateRoadJoints();
                }
            }

            function loadSceneObjectsFromDatabase() {
                if (!Array.isArray(initialSceneMapItems) || initialSceneMapItems.length === 0) {
                    return;
                }

                const byKey = new Map();
                initialSceneMapItems.forEach((item) => {
                    const key = item?.scene_object_key ?? null;
                    if (key) {
                        byKey.set(String(key), item);
                    }
                });

                sceneObjects().forEach((sceneObject) => {
                    const key = sceneObject.dataset.sceneObjectId || '';
                    const dbItem = byKey.get(key);
                    if (!dbItem) {
                        sceneObject.dataset.removed = '1';
                        sceneObject.classList.add('scene-object-hidden');
                        return;
                    }
                    applySceneObjectState(dbItem);
                });
            }

            function restoreInitialSceneObjects() {
                initialSceneObjectSnapshot.forEach((item) => applySceneObjectState(item));
            }

            function loadSceneObjectsFromStorage() {
                try {
                    const raw = window.localStorage.getItem(sceneStorageKey);
                    if (!raw) {
                        return false;
                    }
                    const parsed = JSON.parse(raw);
                    if (!Array.isArray(parsed)) {
                        return false;
                    }
                    parsed.forEach((item) => applySceneObjectState(item));
                    return parsed.length > 0;
                } catch (_error) {
                    // ignore invalid local storage payload
                    return false;
                }
            }

            function persistSceneObjectsToStorage() {
                try {
                    const payload = arguments.length > 0 && Array.isArray(arguments[0])
                        ? arguments[0]
                        : serializeSceneObjects();
                    window.localStorage.setItem(sceneStorageKey, JSON.stringify(payload));
                } catch (_error) {
                    // ignore local storage write failure
                }
            }

            function clearSceneObjectsStorage() {
                try {
                    window.localStorage.removeItem(sceneStorageKey);
                } catch (_error) {
                    // ignore local storage write failure
                }
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
                const fixedValue = item.isFixed ?? item.is_fixed;
                const isFixed = fixedValue === true || fixedValue === 1 || fixedValue === '1' || fixedValue === 'true';

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
                    setFacilityRotation(target, rotation);
                }
                setElementLocked(target, isFixed);

                if ((target.dataset.facilityKey || '') === 'jalan') {
                    updateRoadJoints();
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

            function persistFacilityItemsToStorage() {
                try {
                    const payload = arguments.length > 0 && Array.isArray(arguments[0])
                        ? arguments[0]
                        : serializeFacilityItems();
                    window.localStorage.setItem(facilityStorageKey, JSON.stringify(payload));
                } catch (_error) {
                    // ignore local storage write failure
                }
            }

            function serializeFacilityItems() {
                return facilityElements().map((element) => ({
                    mapItemId: element.dataset.mapItemId || '',
                    facilityId: element.dataset.facilityId || '',
                    facilityKey: element.dataset.facilityKey || '',
                    facilityIcon: element.dataset.facilityIcon || 'F',
                    facilityName: element.dataset.facilityName || 'Fasilitas',
                    isFixed: element.dataset.isFixed === '1',
                    left: parseFloat(element.style.left || '0'),
                    top: parseFloat(element.style.top || '0'),
                    width: element.offsetWidth,
                    height: element.offsetHeight,
                    rotation: parseFloat(element.dataset.rotation || '0'),
                }));
            }

            function clearSelectedFacilities() {
                facilityElements().forEach((facilityElement) => {
                    facilityElement.classList.remove('selected');
                });
                scene.querySelectorAll('.road-lane.selected').forEach((roadElement) => {
                    roadElement.classList.remove('selected');
                });
            }

            function ensureFacilityResizeHandles(element) {
                const corners = ['nw', 'ne', 'sw', 'se'];
                corners.forEach((corner) => {
                    const selector = `.facility-resize-handle[data-corner="${corner}"]`;
                    if (!element.querySelector(selector)) {
                        const handle = document.createElement('span');
                        handle.className = 'facility-resize-handle';
                        handle.dataset.corner = corner;
                        element.appendChild(handle);
                    }
                });
                return Array.from(element.querySelectorAll('.facility-resize-handle'));
            }

            function ensureSceneRoadResizeHandles(element) {
                if (!(element instanceof HTMLElement) || !element.classList.contains('road-lane')) {
                    return [];
                }

                const corners = ['nw', 'ne', 'sw', 'se'];
                corners.forEach((corner) => {
                    const selector = `.scene-road-resize-handle[data-corner="${corner}"]`;
                    if (!element.querySelector(selector)) {
                        const handle = document.createElement('span');
                        handle.className = 'facility-resize-handle scene-road-resize-handle';
                        handle.dataset.corner = corner;
                        element.appendChild(handle);
                    }
                });

                return Array.from(element.querySelectorAll('.scene-road-resize-handle'));
            }

            function ensureFacilityRotateHandle(element) {
                let handle = element.querySelector('.facility-rotate-handle');
                if (!handle) {
                    handle = document.createElement('span');
                    handle.className = 'facility-rotate-handle';
                    element.appendChild(handle);
                }
                return handle;
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

            function setFacilityRotation(element, angleDeg) {
                const angle = normalizeRotation(angleDeg);
                element.dataset.rotation = String(angle);
                element.style.setProperty('--facility-rotation', `${angle}deg`);
            }

            function isElementLocked(element) {
                // Di mode edit, item yang sudah dicentang tetap harus bisa digeser/diatur ulang.
                return !isEditMode && element.dataset.locked === '1';
            }

            function setElementLocked(element, locked) {
                const isLocked = locked === true;
                element.dataset.locked = isLocked ? '1' : '0';
                element.classList.toggle('position-fixed', isLocked);

                if (element.classList.contains('facility-item')) {
                    element.dataset.isFixed = isLocked ? '1' : '0';
                    element.classList.toggle('fixed', isLocked);
                    element.classList.toggle('pending', !isLocked);
                }
            }

            function createFacilityElement(item) {
                const element = document.createElement('div');
                element.className = `facility-item ${item.isFixed ? 'fixed position-fixed' : 'pending'}`;
                element.dataset.mapItemId = item.mapItemId || '';
                element.dataset.facilityId = String(item.facilityId || '');
                element.dataset.facilityKey = item.facilityKey || '';
                element.dataset.facilityIcon = item.facilityIcon || 'F';
                element.dataset.facilityName = item.facilityName || 'Fasilitas';
                element.dataset.isFixed = item.isFixed ? '1' : '0';
                element.dataset.locked = item.isFixed ? '1' : '0';
                element.style.left = `${item.left}px`;
                element.style.top = `${item.top}px`;
                if (Number.isFinite(item.width) && item.width > 0) {
                    element.style.width = `${item.width}px`;
                }
                if (Number.isFinite(item.height) && item.height > 0) {
                    element.style.height = `${item.height}px`;
                }
                setFacilityRotation(element, item.rotation || 0);
                element.title = item.facilityName || 'Fasilitas';
                element.innerHTML = `
                    <span>${item.facilityIcon || 'F'}</span>
                    <span class="facility-actions">
                        <button type="button" class="facility-action-btn confirm" data-facility-action="confirm">&#10003;</button>
                        <button type="button" class="facility-action-btn delete" data-facility-action="delete">x</button>
                    </span>
                `;
                bindFacilityElementInteractions(element);
                return element;
            }

            function restoreInitialFacilities() {
                facilityElements().forEach((element) => element.remove());
                initialFacilitySnapshot.forEach((item) => {
                    scene.appendChild(createFacilityElement(item));
                });
                updateRoadJoints();
            }

            function getFacilityPosition(element) {
                const left = parseFloat(element.style.left || '0');
                const top = parseFloat(element.style.top || '0');
                return {
                    x: Number.isFinite(left) ? left : 0,
                    y: Number.isFinite(top) ? top : 0,
                };
            }

            function setFacilityPosition(element, x, y) {
                const maxX = Math.max(0, scene.offsetWidth - element.offsetWidth);
                const maxY = Math.max(0, scene.offsetHeight - element.offsetHeight);
                const safeX = Math.max(0, Math.min(maxX, x));
                const safeY = Math.max(0, Math.min(maxY, y));
                element.style.left = `${safeX}px`;
                element.style.top = `${safeY}px`;
            }

            function hideSceneActionCard() {
                actionCardTarget = null;
                clearSelectedFacilities();
                if (!sceneActionCard) {
                    return;
                }
                sceneActionCard.classList.remove('active');
            }

            function showSceneActionCard(targetElement) {
                if (!sceneActionCard || !targetElement) {
                    return;
                }

                actionCardTarget = targetElement;
                clearSelectedFacilities();
                if (targetElement.classList.contains('facility-item') || targetElement.classList.contains('road-lane')) {
                    targetElement.classList.add('selected');
                }
                const cardWidth = sceneActionCard.offsetWidth || 56;
                const left = Math.max(0, Math.min(scene.offsetWidth - cardWidth, targetElement.offsetLeft + targetElement.offsetWidth - (cardWidth / 2)));
                const top = Math.max(0, targetElement.offsetTop - 26);
                sceneActionCard.style.left = `${left}px`;
                sceneActionCard.style.top = `${top}px`;
                sceneActionCard.classList.add('active');
            }

            function markEmptyBlockHidden(blockElement) {
                const blockId = parseInt(blockElement.dataset.blockId || '0', 10);
                if (!Number.isFinite(blockId) || blockId <= 0) {
                    return;
                }
                hiddenEmptyBlockIds.add(blockId);
                blockElement.classList.add('pending-hidden');
                blockElement.dataset.hiddenFromFacility = '1';
            }

            function unmarkEmptyBlockHidden(blockElement) {
                const blockId = parseInt(blockElement.dataset.blockId || '0', 10);
                if (!Number.isFinite(blockId) || blockId <= 0) {
                    return;
                }
                hiddenEmptyBlockIds.delete(blockId);
                blockElement.classList.remove('pending-hidden');
                blockElement.dataset.hiddenFromFacility = '0';
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

            function bindSceneObjectDrag(element) {
                const resizeHandles = ensureSceneRoadResizeHandles(element);

                resizeHandles.forEach((resizeHandle) => {
                    resizeHandle.addEventListener('pointerdown', (event) => {
                        if (!isEditMode || event.button !== 0) {
                            return;
                        }
                        if (!(event.target instanceof Element) || !event.target.closest('.scene-road-resize-handle')) {
                            return;
                        }
                        if (isElementLocked(element)) {
                            showSceneActionCard(element);
                            return;
                        }

                        event.preventDefault();
                        event.stopPropagation();

                        hideSceneActionCard();
                        clearSelectedFacilities();
                        element.classList.add('selected');

                        const left = parseFloat(element.style.left || '0');
                        const top = parseFloat(element.style.top || '0');
                        const width = element.offsetWidth || parseFloat(element.style.width || '0');
                        const height = element.offsetHeight || parseFloat(element.style.height || '0');
                        sceneObjectResizeState = {
                            pointerId: event.pointerId,
                            corner: resizeHandle.dataset.corner || 'se',
                            startClientX: event.clientX,
                            startClientY: event.clientY,
                            startX: Number.isFinite(left) ? left : 0,
                            startY: Number.isFinite(top) ? top : 0,
                            startWidth: Number.isFinite(width) ? width : 0,
                            startHeight: Number.isFinite(height) ? height : 0,
                            startRight: (Number.isFinite(left) ? left : 0) + (Number.isFinite(width) ? width : 0),
                            startBottom: (Number.isFinite(top) ? top : 0) + (Number.isFinite(height) ? height : 0),
                            element,
                            handle: resizeHandle,
                        };

                        resizeHandle.setPointerCapture(event.pointerId);
                    });

                    resizeHandle.addEventListener('pointermove', (event) => {
                        if (!sceneObjectResizeState || sceneObjectResizeState.pointerId !== event.pointerId) {
                            return;
                        }

                        const dx = (event.clientX - sceneObjectResizeState.startClientX) / scale;
                        const dy = (event.clientY - sceneObjectResizeState.startClientY) / scale;
                        const corner = sceneObjectResizeState.corner || 'se';
                        const minWidth = 40;
                        const minHeight = 40;

                        let nextX = sceneObjectResizeState.startX;
                        let nextY = sceneObjectResizeState.startY;
                        let nextWidth = sceneObjectResizeState.startWidth;
                        let nextHeight = sceneObjectResizeState.startHeight;

                        if (corner.includes('w')) {
                            nextX = Math.max(0, Math.min(sceneObjectResizeState.startX + dx, sceneObjectResizeState.startRight - minWidth));
                            nextWidth = sceneObjectResizeState.startRight - nextX;
                        } else {
                            const maxWidth = Math.max(minWidth, scene.offsetWidth - sceneObjectResizeState.startX);
                            nextWidth = Math.max(minWidth, Math.min(maxWidth, sceneObjectResizeState.startWidth + dx));
                            nextX = sceneObjectResizeState.startX;
                        }

                        if (corner.includes('n')) {
                            nextY = Math.max(0, Math.min(sceneObjectResizeState.startY + dy, sceneObjectResizeState.startBottom - minHeight));
                            nextHeight = sceneObjectResizeState.startBottom - nextY;
                        } else {
                            const maxHeight = Math.max(minHeight, scene.offsetHeight - sceneObjectResizeState.startY);
                            nextHeight = Math.max(minHeight, Math.min(maxHeight, sceneObjectResizeState.startHeight + dy));
                            nextY = sceneObjectResizeState.startY;
                        }

                        sceneObjectResizeState.element.style.left = `${nextX}px`;
                        sceneObjectResizeState.element.style.top = `${nextY}px`;
                        sceneObjectResizeState.element.style.width = `${nextWidth}px`;
                        sceneObjectResizeState.element.style.height = `${nextHeight}px`;
                        syncRoadLaneAppearance(sceneObjectResizeState.element);
                        updateRoadJoints();
                    });

                    const endResize = (event) => {
                        if (!sceneObjectResizeState || sceneObjectResizeState.pointerId !== event.pointerId) {
                            return;
                        }

                        const activeHandle = sceneObjectResizeState.handle;
                        if (activeHandle && activeHandle.hasPointerCapture(event.pointerId)) {
                            activeHandle.releasePointerCapture(event.pointerId);
                        }
                        syncRoadLaneAppearance(sceneObjectResizeState.element);
                        updateRoadJoints();
                        showSceneActionCard(sceneObjectResizeState.element);
                        sceneObjectResizeState = null;
                    };

                    resizeHandle.addEventListener('pointerup', endResize);
                    resizeHandle.addEventListener('pointercancel', endResize);
                });

                element.addEventListener('pointerdown', (event) => {
                    if (!isEditMode || event.button !== 0) {
                        return;
                    }
                    if (element.dataset.removed === '1') {
                        return;
                    }
                    if (event.target instanceof Element && event.target.closest('.scene-road-resize-handle')) {
                        return;
                    }
                    if (isElementLocked(element)) {
                        event.preventDefault();
                        event.stopPropagation();
                        showSceneActionCard(element);
                        return;
                    }
                    event.preventDefault();
                    event.stopPropagation();
                    hideSceneActionCard();

                    const left = parseFloat(element.style.left || '0');
                    const top = parseFloat(element.style.top || '0');

                    sceneObjectDragState = {
                        pointerId: event.pointerId,
                        startClientX: event.clientX,
                        startClientY: event.clientY,
                        startX: Number.isFinite(left) ? left : 0,
                        startY: Number.isFinite(top) ? top : 0,
                        element,
                        moved: false,
                    };

                    element.classList.add('is-dragging');
                    element.setPointerCapture(event.pointerId);
                });

                element.addEventListener('pointermove', (event) => {
                    if (!sceneObjectDragState || sceneObjectDragState.pointerId !== event.pointerId) {
                        return;
                    }

                    const dx = (event.clientX - sceneObjectDragState.startClientX) / scale;
                    const dy = (event.clientY - sceneObjectDragState.startClientY) / scale;
                    const nextX = sceneObjectDragState.startX + dx;
                    const nextY = sceneObjectDragState.startY + dy;
                    const maxX = Math.max(0, scene.offsetWidth - element.offsetWidth);
                    const maxY = Math.max(0, scene.offsetHeight - element.offsetHeight);
                    if (Math.abs(dx) > 1 || Math.abs(dy) > 1) {
                        sceneObjectDragState.moved = true;
                    }
                    element.style.left = `${Math.max(0, Math.min(maxX, nextX))}px`;
                    element.style.top = `${Math.max(0, Math.min(maxY, nextY))}px`;
                    if (element.classList.contains('road-lane')) {
                        updateRoadJoints();
                    }
                });

                const endDrag = (event) => {
                    if (!sceneObjectDragState || sceneObjectDragState.pointerId !== event.pointerId) {
                        return;
                    }
                    sceneObjectDragState.element.classList.remove('is-dragging');
                    if (sceneObjectDragState.element.hasPointerCapture(event.pointerId)) {
                        sceneObjectDragState.element.releasePointerCapture(event.pointerId);
                    }
                    if (!sceneObjectDragState.moved) {
                        showSceneActionCard(sceneObjectDragState.element);
                    }
                    if (sceneObjectDragState.element.classList.contains('road-lane')) {
                        updateRoadJoints();
                    }
                    sceneObjectDragState = null;
                };

                element.addEventListener('pointerup', endDrag);
                element.addEventListener('pointercancel', endDrag);
            }

            function bindFacilityElementInteractions(element) {
                const resizeHandles = ensureFacilityResizeHandles(element);
                const rotateHandle = ensureFacilityRotateHandle(element);

                resizeHandles.forEach((resizeHandle) => {
                    resizeHandle.addEventListener('pointerdown', (event) => {
                        if (!isEditMode || event.button !== 0) {
                            return;
                        }
                        if (!(event.target instanceof Element) || !event.target.closest('.facility-resize-handle')) {
                            return;
                        }
                        if (isElementLocked(element)) {
                            showSceneActionCard(element);
                            return;
                        }

                        event.preventDefault();
                        event.stopPropagation();

                        hideSceneActionCard();
                        clearSelectedFacilities();
                        element.classList.add('selected');

                        const start = getFacilityPosition(element);
                        facilityResizeState = {
                            pointerId: event.pointerId,
                            corner: resizeHandle.dataset.corner || 'se',
                            startClientX: event.clientX,
                            startClientY: event.clientY,
                            startWidth: element.offsetWidth,
                            startHeight: element.offsetHeight,
                            startX: start.x,
                            startY: start.y,
                            startRight: start.x + element.offsetWidth,
                            startBottom: start.y + element.offsetHeight,
                            element,
                            handle: resizeHandle,
                        };

                        resizeHandle.setPointerCapture(event.pointerId);
                    });

                    resizeHandle.addEventListener('pointermove', (event) => {
                        if (!facilityResizeState || facilityResizeState.pointerId !== event.pointerId) {
                            return;
                        }

                        const dx = (event.clientX - facilityResizeState.startClientX) / scale;
                        const dy = (event.clientY - facilityResizeState.startClientY) / scale;
                        const facilityKey = facilityResizeState.element.dataset.facilityKey || '';
                        const minWidth = facilityKey === 'jalan' ? 30 : 24;
                        const minHeight = facilityKey === 'jalan' ? 16 : 24;
                        const corner = facilityResizeState.corner || 'se';

                        let nextX = facilityResizeState.startX;
                        let nextY = facilityResizeState.startY;
                        let nextWidth = facilityResizeState.startWidth;
                        let nextHeight = facilityResizeState.startHeight;

                        if (corner.includes('w')) {
                            nextX = Math.max(0, Math.min(facilityResizeState.startX + dx, facilityResizeState.startRight - minWidth));
                            nextWidth = facilityResizeState.startRight - nextX;
                        } else {
                            const maxWidth = Math.max(minWidth, scene.offsetWidth - facilityResizeState.startX);
                            nextWidth = Math.max(minWidth, Math.min(maxWidth, facilityResizeState.startWidth + dx));
                            nextX = facilityResizeState.startX;
                        }

                        if (corner.includes('n')) {
                            nextY = Math.max(0, Math.min(facilityResizeState.startY + dy, facilityResizeState.startBottom - minHeight));
                            nextHeight = facilityResizeState.startBottom - nextY;
                        } else {
                            const maxHeight = Math.max(minHeight, scene.offsetHeight - facilityResizeState.startY);
                            nextHeight = Math.max(minHeight, Math.min(maxHeight, facilityResizeState.startHeight + dy));
                            nextY = facilityResizeState.startY;
                        }

                        facilityResizeState.element.style.left = `${nextX}px`;
                        facilityResizeState.element.style.top = `${nextY}px`;
                        facilityResizeState.element.style.width = `${nextWidth}px`;
                        facilityResizeState.element.style.height = `${nextHeight}px`;
                        if ((facilityResizeState.element.dataset.facilityKey || '') === 'jalan') {
                            updateRoadJoints();
                        }
                    });

                    const endResize = (event) => {
                        if (!facilityResizeState || facilityResizeState.pointerId !== event.pointerId) {
                            return;
                        }

                        const activeHandle = facilityResizeState.handle;
                        if (activeHandle && activeHandle.hasPointerCapture(event.pointerId)) {
                            activeHandle.releasePointerCapture(event.pointerId);
                        }
                        if ((facilityResizeState.element.dataset.facilityKey || '') === 'jalan') {
                            updateRoadJoints();
                        }
                        showSceneActionCard(facilityResizeState.element);
                        facilityResizeState = null;
                    };

                    resizeHandle.addEventListener('pointerup', endResize);
                    resizeHandle.addEventListener('pointercancel', endResize);
                });

                rotateHandle.addEventListener('pointerdown', (event) => {
                    if (!isEditMode || event.button !== 0) {
                        return;
                    }
                    if (isElementLocked(element)) {
                        showSceneActionCard(element);
                        return;
                    }

                    event.preventDefault();
                    event.stopPropagation();

                    hideSceneActionCard();
                    clearSelectedFacilities();
                    element.classList.add('selected');

                    const rect = element.getBoundingClientRect();
                    const centerX = rect.left + (rect.width / 2);
                    const centerY = rect.top + (rect.height / 2);
                    const startAngle = Math.atan2(event.clientY - centerY, event.clientX - centerX);
                    const startRotation = parseFloat(element.dataset.rotation || '0');

                    facilityRotateState = {
                        pointerId: event.pointerId,
                        element,
                        centerX,
                        centerY,
                        startAngle,
                        startRotation: Number.isFinite(startRotation) ? startRotation : 0,
                        handle: rotateHandle,
                    };

                    rotateHandle.setPointerCapture(event.pointerId);
                });

                rotateHandle.addEventListener('pointermove', (event) => {
                    if (!facilityRotateState || facilityRotateState.pointerId !== event.pointerId) {
                        return;
                    }

                    const currentAngle = Math.atan2(event.clientY - facilityRotateState.centerY, event.clientX - facilityRotateState.centerX);
                    const deltaDeg = (currentAngle - facilityRotateState.startAngle) * (180 / Math.PI);
                    setFacilityRotation(facilityRotateState.element, facilityRotateState.startRotation + deltaDeg);
                    if ((facilityRotateState.element.dataset.facilityKey || '') === 'jalan') {
                        updateRoadJoints();
                    }
                });

                const endRotate = (event) => {
                    if (!facilityRotateState || facilityRotateState.pointerId !== event.pointerId) {
                        return;
                    }

                    const activeHandle = facilityRotateState.handle;
                    if (activeHandle && activeHandle.hasPointerCapture(event.pointerId)) {
                        activeHandle.releasePointerCapture(event.pointerId);
                    }
                    if ((facilityRotateState.element.dataset.facilityKey || '') === 'jalan') {
                        updateRoadJoints();
                    }
                    showSceneActionCard(facilityRotateState.element);
                    facilityRotateState = null;
                };

                rotateHandle.addEventListener('pointerup', endRotate);
                rotateHandle.addEventListener('pointercancel', endRotate);

                element.addEventListener('pointerdown', (event) => {
                    if (!isEditMode || event.button !== 0) {
                        return;
                    }

                    if (event.target instanceof Element && event.target.closest('[data-facility-action]')) {
                        return;
                    }
                    if (event.target instanceof Element && event.target.closest('.facility-resize-handle')) {
                        return;
                    }
                    if (event.target instanceof Element && event.target.closest('.facility-rotate-handle')) {
                        return;
                    }
                    if (isElementLocked(element)) {
                        event.preventDefault();
                        event.stopPropagation();
                        showSceneActionCard(element);
                        return;
                    }

                    event.stopPropagation();
                    hideSceneActionCard();

                    const start = getFacilityPosition(element);
                    element.classList.add('is-dragging');
                    element.setPointerCapture(event.pointerId);
                    facilityDragState = {
                        pointerId: event.pointerId,
                        startClientX: event.clientX,
                        startClientY: event.clientY,
                        startX: start.x,
                        startY: start.y,
                        element,
                        moved: false,
                    };
                });

                element.addEventListener('pointermove', (event) => {
                    if (!facilityDragState || facilityDragState.pointerId !== event.pointerId) {
                        return;
                    }

                    const dx = (event.clientX - facilityDragState.startClientX) / scale;
                    const dy = (event.clientY - facilityDragState.startClientY) / scale;
                    if (Math.abs(dx) > 1 || Math.abs(dy) > 1) {
                        facilityDragState.moved = true;
                    }
                    setFacilityPosition(facilityDragState.element, facilityDragState.startX + dx, facilityDragState.startY + dy);
                    if ((facilityDragState.element.dataset.facilityKey || '') === 'jalan') {
                        updateRoadJoints();
                    }
                });

                const endDrag = (event) => {
                    if (!facilityDragState || facilityDragState.pointerId !== event.pointerId) {
                        return;
                    }

                    facilityDragState.element.classList.remove('is-dragging');
                    if (facilityDragState.element.hasPointerCapture(event.pointerId)) {
                        facilityDragState.element.releasePointerCapture(event.pointerId);
                    }
                    if (!facilityDragState.moved) {
                        showSceneActionCard(facilityDragState.element);
                    }
                    if ((facilityDragState.element.dataset.facilityKey || '') === 'jalan') {
                        updateRoadJoints();
                    }
                    facilityDragState = null;
                };

                element.addEventListener('pointerup', endDrag);
                element.addEventListener('pointercancel', endDrag);
            }

            function appendFacilityToScene(facilityButton) {
                if (!isEditMode) {
                    alert('Aktifkan mode edit dulu untuk menambahkan fasilitas.');
                    return;
                }

                const facilityId = parseInt(facilityButton.dataset.facilityId || '0', 10);
                if (!Number.isFinite(facilityId) || facilityId <= 0) {
                    return;
                }

                const icon = facilityButton.dataset.facilityIcon || 'F';
                const key = facilityButton.dataset.facilityKey || 'facility';
                const name = facilityButton.dataset.facilityName || 'Fasilitas';
                const worldCenterX = ((viewport.clientWidth / 2) - translateX) / scale;
                const worldCenterY = ((viewport.clientHeight / 2) - translateY) / scale;
                const item = createFacilityElement({
                    mapItemId: `tmp-${Date.now()}-${temporaryFacilityCounter++}`,
                    facilityId,
                    facilityKey: key,
                    facilityIcon: icon,
                    facilityName: name,
                    isFixed: false,
                    left: worldCenterX - 18,
                    top: worldCenterY - 18,
                });

                scene.appendChild(item);
                setFacilityPosition(item, worldCenterX - 18, worldCenterY - 18);
                if (key === 'jalan') {
                    updateRoadJoints();
                }
                showSceneActionCard(item);
            }

            async function saveBlockPositions() {
                if (isSavingPositions) {
                    return;
                }

                const blocks = [];
                blockElements.forEach((blockElement) => {
                    const blockId = parseInt(blockElement.dataset.blockId || '0', 10);
                    if (!Number.isFinite(blockId) || blockId <= 0) {
                        return;
                    }

                    const current = getBlockPosition(blockElement);
                    blocks.push({
                        id: blockId,
                        x: Math.round(current.x),
                        y: Math.round(current.y),
                    });
                });

                // Saat simpan, semua fasilitas yang masih ada di denah dianggap final/fixed.
                facilityElements().forEach((element) => {
                    setElementLocked(element, true);
                });

                const facilityItems = facilityElements()
                    .map((element) => {
                        const position = getFacilityPosition(element);
                        const rawMapItemId = element.dataset.mapItemId || '';
                        const numericMapItemId = parseInt(rawMapItemId, 10);
                        const rotation = parseFloat(element.dataset.rotation || '0');
                        const width = Math.round(element.offsetWidth || 0);
                        const height = Math.round(element.offsetHeight || 0);
                        return {
                            id: Number.isFinite(numericMapItemId) && numericMapItemId > 0 ? numericMapItemId : null,
                            facility_id: parseInt(element.dataset.facilityId || '0', 10),
                            item_type: 'icon',
                            scene_object_key: null,
                            x: Math.round(position.x),
                            y: Math.round(position.y),
                            width: width > 0 ? width : null,
                            height: height > 0 ? height : null,
                            rotation: Number.isFinite(rotation) ? Number(rotation.toFixed(2)) : 0,
                            is_fixed: true,
                        };
                    })
                    .filter((item) => Number.isFinite(item.facility_id) && item.facility_id > 0);

                const sceneFacilityItems = sceneObjects()
                    .map((element) => {
                        const sceneObjectKey = element.dataset.sceneObjectId || null;
                        if (!sceneObjectKey) {
                            return null;
                        }
                        const rawFacilityId = parseInt(element.dataset.sceneFacilityId || '0', 10);
                        const facilityId = Number.isFinite(rawFacilityId) && rawFacilityId > 0 ? rawFacilityId : 0;
                        const left = parseFloat(element.style.left || '0');
                        const top = parseFloat(element.style.top || '0');
                        const rotation = parseFloat(element.dataset.rotation || '0');
                        const width = Math.round(element.offsetWidth || 0);
                        const height = Math.round(element.offsetHeight || 0);
                        return {
                            id: null,
                            facility_id: facilityId,
                            item_type: 'scene',
                            scene_object_key: sceneObjectKey,
                            x: Math.round(Number.isFinite(left) ? left : 0),
                            y: Math.round(Number.isFinite(top) ? top : 0),
                            width: width > 0 ? width : null,
                            height: height > 0 ? height : null,
                            rotation: Number.isFinite(rotation) ? Number(rotation.toFixed(2)) : 0,
                            is_removed: element.dataset.removed === '1',
                            is_fixed: true,
                        };
                    })
                    .filter((item) => item !== null);

                const hiddenEmptyBlockSceneItems = blockElements
                    ? Array.from(blockElements)
                        .filter((element) => element.dataset.emptyNoPlot === '1' && element.classList.contains('pending-hidden'))
                        .map((element) => {
                            const blockId = parseInt(element.dataset.blockId || '0', 10);
                            const left = parseFloat(element.style.left || '0');
                            const top = parseFloat(element.style.top || '0');
                            const width = Math.round(element.offsetWidth || 0);
                            const height = Math.round(element.offsetHeight || 0);
                            if (!Number.isFinite(blockId) || blockId <= 0) {
                                return null;
                            }
                            return {
                                id: null,
                                facility_id: 0,
                                item_type: 'scene',
                                scene_object_key: `empty_block_${blockId}`,
                                x: Math.round(Number.isFinite(left) ? left : 0),
                                y: Math.round(Number.isFinite(top) ? top : 0),
                                width: width > 0 ? width : null,
                                height: height > 0 ? height : null,
                                rotation: 0,
                                is_removed: false,
                                is_fixed: true,
                            };
                        })
                        .filter((item) => item !== null)
                    : [];

                const allFacilityItems = [...facilityItems, ...sceneFacilityItems, ...hiddenEmptyBlockSceneItems];

                if (blocks.length === 0) {
                    toggleEditMode(false);
                    return;
                }

                isSavingPositions = true;
                if (mapSaveBtn) {
                    mapSaveBtn.disabled = true;
                }

                try {
                    const response = await fetch(savePositionsUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({
                            blocks,
                            facility_items: allFacilityItems,
                            hidden_empty_block_ids: Array.from(hiddenEmptyBlockIds),
                        }),
                    });

                    const payload = await response.json().catch(() => ({}));
                    if (!response.ok) {
                        throw new Error(payload?.message || 'Gagal menyimpan posisi denah.');
                    }

                    if (Array.isArray(payload?.scene_item_states) && payload.scene_item_states.length > 0) {
                        clearSceneObjectsStorage();
                    } else {
                        persistSceneObjectsToStorage(serializeSceneObjects());
                    }
                    if (payload?.facility_map_storage === 'facility_map_items' && payload?.facility_map_shape_persisted !== true) {
                        const nextFacilityState = Array.isArray(payload?.facility_item_states)
                            ? payload.facility_item_states
                            : serializeFacilityItems();
                        persistFacilityItemsToStorage(nextFacilityState);
                    } else {
                        clearFacilityItemsStorage();
                    }
                    toggleEditMode(false);
                    window.location.reload();
                } catch (error) {
                    alert(error.message || 'Gagal menyimpan posisi denah.');
                } finally {
                    isSavingPositions = false;
                    if (mapSaveBtn) {
                        mapSaveBtn.disabled = false;
                    }
                }
            }

            viewport.addEventListener('pointerdown', (event) => {
                if (event.button !== 0) return;
                const interactiveTarget = event.target instanceof Element
                    ? event.target.closest('.block-area, .facility-item, [data-edit-draggable="1"], #sceneActionCard')
                    : null;
                if (isEditMode && interactiveTarget) {
                    return;
                }
                hideHoverCard();
                isDragging = true;
                startX = event.clientX;
                startY = event.clientY;
                viewport.setPointerCapture(event.pointerId);
            });

            viewport.addEventListener('pointermove', (event) => {
                if (blockDragState || facilityDragState || facilityResizeState || facilityRotateState || sceneObjectDragState || sceneObjectResizeState) return;
                if (!isDragging) return;
                translateX += event.clientX - startX;
                translateY += event.clientY - startY;
                startX = event.clientX;
                startY = event.clientY;
                render();
            });

            viewport.addEventListener('pointerup', (event) => {
                isDragging = false;
                if (viewport.hasPointerCapture(event.pointerId)) {
                    viewport.releasePointerCapture(event.pointerId);
                }
            });

            viewport.addEventListener('pointercancel', () => {
                isDragging = false;
            });

            viewport.addEventListener('wheel', (event) => {
                event.preventDefault();
                zoomAt(event.clientX, event.clientY, event.deltaY < 0 ? 1.1 : 0.9);
            }, { passive: false });

            controls.forEach((button) => {
                button.addEventListener('click', () => {
                    const action = button.getAttribute('data-map-action');
                    const rect = viewport.getBoundingClientRect();
                    const cx = rect.left + (rect.width / 2);
                    const cy = rect.top + (rect.height / 2);

                    if (action === 'zoom-in') zoomAt(cx, cy, 1.15);
                    if (action === 'zoom-out') zoomAt(cx, cy, 0.87);
                    if (action === 'reset') fitToViewport();
                });
            });

            plotElements.forEach((plotElement) => {
                const blockId = parseInt(plotElement.dataset.blockId || '0', 10);
                if (!Number.isFinite(blockId) || blockId <= 0) {
                    return;
                }

                if (!plotByBlockId.has(blockId)) {
                    plotByBlockId.set(blockId, []);
                }
                plotByBlockId.get(blockId).push(plotElement);
            });

            blockElements.forEach((blockElement) => {
                blockElement.addEventListener('pointerdown', (event) => {
                    if (!isEditMode || event.button !== 0) {
                        return;
                    }

                    const blockId = parseInt(blockElement.dataset.blockId || '0', 10);
                    if (!Number.isFinite(blockId) || blockId <= 0) {
                        return;
                    }
                    if (blockElement.dataset.emptyNoPlot === '1') {
                        unmarkEmptyBlockHidden(blockElement);
                    }

                    event.preventDefault();
                    event.stopPropagation();
                    hideHoverCard();
                    hideSceneActionCard();

                    const position = getBlockPosition(blockElement);
                    blockElement.classList.add('is-dragging');
                    blockElement.setPointerCapture(event.pointerId);

                    blockDragState = {
                        blockId,
                        pointerId: event.pointerId,
                        startClientX: event.clientX,
                        startClientY: event.clientY,
                        startX: position.x,
                        startY: position.y,
                        element: blockElement,
                    };
                });

                blockElement.addEventListener('pointermove', (event) => {
                    if (!blockDragState || blockDragState.pointerId !== event.pointerId) {
                        return;
                    }

                    const dx = (event.clientX - blockDragState.startClientX) / scale;
                    const dy = (event.clientY - blockDragState.startClientY) / scale;
                    const nextX = blockDragState.startX + dx;
                    const nextY = blockDragState.startY + dy;
                    const current = getBlockPosition(blockDragState.element);
                    const moveX = nextX - current.x;
                    const moveY = nextY - current.y;

                    if (moveX === 0 && moveY === 0) {
                        return;
                    }

                    setBlockPosition(blockDragState.element, nextX, nextY);
                    applyBlockDeltaToPlots(blockDragState.blockId, moveX, moveY);
                });

                const endBlockDrag = (event) => {
                    if (!blockDragState || blockDragState.pointerId !== event.pointerId) {
                        return;
                    }

                    blockDragState.element.classList.remove('is-dragging');
                    if (blockDragState.element.hasPointerCapture(event.pointerId)) {
                        blockDragState.element.releasePointerCapture(event.pointerId);
                    }
                    blockDragState = null;
                };

                blockElement.addEventListener('pointerup', endBlockDrag);
                blockElement.addEventListener('pointercancel', endBlockDrag);
            });

            facilityElements().forEach((element) => {
                const existingRotation = parseFloat(element.dataset.rotation || '0');
                setFacilityRotation(element, Number.isFinite(existingRotation) ? existingRotation : 0);
                bindFacilityElementInteractions(element);
            });

            if (facilityList) {
                facilityList.querySelectorAll('.facility-btn').forEach((button) => {
                    button.addEventListener('click', () => {
                        const restoreBlockId = parseInt(button.dataset.restoreBlockId || '0', 10);
                        if (Number.isFinite(restoreBlockId) && restoreBlockId > 0) {
                            if (!isEditMode) {
                                alert('Aktifkan mode edit dulu untuk memunculkan blok.');
                                return;
                            }
                            const targetBlock = scene.querySelector(`.block-area.empty-no-plot[data-block-id="${restoreBlockId}"]`);
                            if (!targetBlock) {
                                alert('Data blok tidak ditemukan di denah.');
                                return;
                            }
                            unmarkEmptyBlockHidden(targetBlock);
                            targetBlock.dataset.hiddenFromFacility = '0';
                            hideSceneActionCard();
                            return;
                        }
                        appendFacilityToScene(button);
                    });
                });
            }

            scene.querySelectorAll('.road-lane').forEach((element) => {
                syncRoadLaneAppearance(element);
            });

            scene.querySelectorAll('[data-edit-draggable="1"]').forEach((element) => {
                bindSceneObjectDrag(element);
            });

            if (shouldHydrateFacilityItemsFromStorage) {
                loadFacilityItemsFromStorage();
            } else {
                clearFacilityItemsStorage();
            }
            const hasDatabaseSceneState = Array.isArray(initialSceneMapItems) && initialSceneMapItems.length > 0;
            if (hasDatabaseSceneState) {
                clearSceneObjectsStorage();
            }
            loadSceneObjectsFromDatabase();
            if (!hasDatabaseSceneState) {
                const loadedSceneFromStorage = loadSceneObjectsFromStorage();
                if (loadedSceneFromStorage && mapSaveBtn) {
                    try {
                        const syncMarker = window.sessionStorage.getItem(sceneStorageSyncKey);
                        if (syncMarker !== '1') {
                            window.sessionStorage.setItem(sceneStorageSyncKey, '1');
                            window.setTimeout(() => {
                                saveBlockPositions();
                            }, 0);
                        }
                    } catch (_error) {
                        // ignore session storage failure
                    }
                }
            }
            updateRoadJoints();

            if (sceneActionCard) {
                sceneActionCard.addEventListener('pointerdown', (event) => {
                    event.stopPropagation();
                });

                sceneActionCard.addEventListener('click', (event) => {
                    if (!isEditMode) {
                        return;
                    }

                    const actionButton = event.target instanceof Element
                        ? event.target.closest('[data-scene-action]')
                        : null;
                    if (!actionButton || !actionCardTarget) {
                        return;
                    }

                    event.preventDefault();
                    event.stopPropagation();
                    const action = actionButton.getAttribute('data-scene-action');

                    if (actionCardTarget.classList.contains('block-area') && actionCardTarget.dataset.emptyNoPlot === '1') {
                        if (action === 'confirm') {
                            saveBlockPositions();
                        } else if (action === 'delete') {
                            markEmptyBlockHidden(actionCardTarget);
                        }
                        hideSceneActionCard();
                        return;
                    }

                    if (action === 'confirm') {
                        setElementLocked(actionCardTarget, true);
                    } else if (action === 'delete' && actionCardTarget !== sceneActionCard) {
                        if (actionCardTarget.classList.contains('facility-item')) {
                            const shouldRefreshRoadJoints = (actionCardTarget.dataset.facilityKey || '') === 'jalan';
                            actionCardTarget.remove();
                            if (shouldRefreshRoadJoints) {
                                updateRoadJoints();
                            }
                        } else if (actionCardTarget.hasAttribute('data-edit-draggable')) {
                            actionCardTarget.dataset.removed = '1';
                            actionCardTarget.classList.add('scene-object-hidden');
                            if (actionCardTarget.classList.contains('road-lane')) {
                                updateRoadJoints();
                            }
                        }
                    }

                    hideSceneActionCard();
                });
            }

            scene.addEventListener('click', (event) => {
                if (!isEditMode) {
                    return;
                }

                const actionButton = event.target instanceof Element
                    ? event.target.closest('[data-scene-action]')
                    : null;
                if (actionButton && actionCardTarget) {
                    event.preventDefault();
                    event.stopPropagation();
                    const action = actionButton.getAttribute('data-scene-action');

                    if (actionCardTarget.classList.contains('block-area') && actionCardTarget.dataset.emptyNoPlot === '1') {
                        if (action === 'confirm') {
                            saveBlockPositions();
                        } else if (action === 'delete') {
                            markEmptyBlockHidden(actionCardTarget);
                        }
                        hideSceneActionCard();
                        return;
                    }

                    if (action === 'confirm') {
                        setElementLocked(actionCardTarget, true);
                    } else if (action === 'delete' && actionCardTarget !== sceneActionCard) {
                        if (actionCardTarget.classList.contains('facility-item')) {
                            const shouldRefreshRoadJoints = (actionCardTarget.dataset.facilityKey || '') === 'jalan';
                            actionCardTarget.remove();
                            if (shouldRefreshRoadJoints) {
                                updateRoadJoints();
                            }
                        } else if (actionCardTarget.hasAttribute('data-edit-draggable')) {
                            actionCardTarget.dataset.removed = '1';
                            actionCardTarget.classList.add('scene-object-hidden');
                            if (actionCardTarget.classList.contains('road-lane')) {
                                updateRoadJoints();
                            }
                        }
                    }

                    hideSceneActionCard();
                    return;
                }

                const clickedTarget = event.target instanceof Element
                    ? event.target.closest('.facility-item, [data-edit-draggable="1"], .block-area.empty-no-plot')
                    : null;

                if (clickedTarget && clickedTarget !== sceneActionCard) {
                    event.preventDefault();
                    event.stopPropagation();
                    showSceneActionCard(clickedTarget);
                    return;
                }

                hideSceneActionCard();
            });

            document.addEventListener('click', (event) => {
                if (!isEditMode) {
                    return;
                }

                const insideScene = event.target instanceof Element
                    ? event.target.closest('#mapScene')
                    : null;

                if (!insideScene) {
                    hideSceneActionCard();
                }
            });

            scene.addEventListener('pointerdown', (event) => {
                if (!isEditMode) {
                    return;
                }
                const onTarget = event.target instanceof Element
                    ? event.target.closest('.facility-item, [data-edit-draggable="1"], #sceneActionCard')
                    : null;
                if (!onTarget) {
                    hideSceneActionCard();
                }
            });

            if (mapEditBtn) {
                mapEditBtn.addEventListener('click', () => {
                    toggleEditMode(true);
                });
            }

            if (mapCancelBtn) {
                mapCancelBtn.addEventListener('click', () => {
                    restoreInitialPositions();
                    restoreInitialFacilities();
                    restoreInitialSceneObjects();
                    toggleEditMode(false);
                });
            }

            if (mapSaveBtn) {
                mapSaveBtn.addEventListener('click', saveBlockPositions);
            }

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
