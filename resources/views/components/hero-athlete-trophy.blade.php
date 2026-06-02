{{-- Ilustrasi atlet mengangkat trofi — hero beranda publik --}}
<svg
    class="sitenor-hero-athlete"
    viewBox="0 0 320 320"
    fill="none"
    xmlns="http://www.w3.org/2000/svg"
    role="img"
    aria-label="Atlet memegang trofi juara"
>
    <defs>
        <linearGradient id="sitenorHeroBg" x1="40" y1="20" x2="280" y2="300" gradientUnits="userSpaceOnUse">
            <stop offset="0" stop-color="#eff6ff"/>
            <stop offset="0.5" stop-color="#fef2f2"/>
            <stop offset="1" stop-color="#fff7ed"/>
        </linearGradient>
        <linearGradient id="sitenorTrophyGold" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0" stop-color="#fde047"/>
            <stop offset="0.5" stop-color="#f59e0b"/>
            <stop offset="1" stop-color="#d97706"/>
        </linearGradient>
        <linearGradient id="sitenorShirt" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" stop-color="#dc2626"/>
            <stop offset="1" stop-color="#b91c1c"/>
        </linearGradient>
        <linearGradient id="sitenorShorts" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" stop-color="#1e40af"/>
            <stop offset="1" stop-color="#1e3a8a"/>
        </linearGradient>
        <filter id="sitenorSoftShadow" x="-20%" y="-20%" width="140%" height="140%">
            <feDropShadow dx="0" dy="8" stdDeviation="12" flood-color="#1e293b" flood-opacity="0.12"/>
        </filter>
    </defs>

    <circle cx="160" cy="165" r="118" fill="url(#sitenorHeroBg)"/>

    {{-- Confetti / semangat --}}
    <circle cx="72" cy="88" r="5" fill="#fde047" opacity="0.9"/>
    <circle cx="248" cy="72" r="4" fill="#dc2626" opacity="0.75"/>
    <circle cx="255" cy="118" r="3" fill="#3b82f6" opacity="0.8"/>
    <circle cx="58" cy="140" r="3.5" fill="#f59e0b" opacity="0.85"/>
    <path d="M88 58 L92 68 L82 64 Z" fill="#dc2626" opacity="0.6"/>
    <path d="M228 48 L232 58 L222 54 Z" fill="#2563eb" opacity="0.55"/>

    <g filter="url(#sitenorSoftShadow)">
        {{-- Kaki --}}
        <path d="M128 248 L118 278 L132 278 L138 248 Z" fill="#1e3a8a"/>
        <path d="M192 248 L202 278 L188 278 L182 248 Z" fill="#1e3a8a"/>
        <ellipse cx="125" cy="280" rx="18" ry="6" fill="#0f172a" opacity="0.08"/>
        <ellipse cx="195" cy="280" rx="18" ry="6" fill="#0f172a" opacity="0.08"/>

        {{-- Celana & tubuh --}}
        <path d="M118 198 C118 168 138 148 160 148 C182 148 202 168 202 198 L198 248 L122 248 Z" fill="url(#sitenorShorts)"/>
        <path d="M128 155 C128 132 142 118 160 118 C178 118 192 132 192 155 L188 198 L132 198 Z" fill="url(#sitenorShirt)"/>

        {{-- Lengan mengangkat trofi --}}
        <path d="M128 158 L88 108 L98 100 L132 148 Z" fill="#fca5a5"/>
        <path d="M192 158 L232 108 L222 100 L188 148 Z" fill="#fca5a5"/>

        {{-- Tangan --}}
        <circle cx="92" cy="102" r="11" fill="#fdba74"/>
        <circle cx="228" cy="102" r="11" fill="#fdba74"/>

        {{-- Kepala --}}
        <circle cx="160" cy="92" r="28" fill="#fdba74"/>
        <path d="M132 88 C132 72 145 62 160 62 C175 62 188 72 188 88 C188 78 175 70 160 70 C145 70 132 78 132 88 Z" fill="#1e293b"/>
        <ellipse cx="150" cy="90" rx="3" ry="4" fill="#1e293b"/>
        <ellipse cx="170" cy="90" rx="3" ry="4" fill="#1e293b"/>
        <path d="M152 102 Q160 108 168 102" stroke="#b45309" stroke-width="2" stroke-linecap="round" fill="none"/>

        {{-- Trofi (di tengah atas) --}}
        <g transform="translate(160 72)">
            <path d="M-38 -8 C-38 -28 -12 -42 0 -48 C12 -42 38 -28 38 -8 L32 8 L-32 8 Z" fill="url(#sitenorTrophyGold)" stroke="#b45309" stroke-width="1.5"/>
            <path d="M-22 -6 C-22 -20 -8 -30 0 -34 C8 -30 22 -20 22 -6" fill="#fde68a" opacity="0.5"/>
            <rect x="-28" y="8" width="56" height="10" rx="2" fill="#92400e"/>
            <rect x="-20" y="18" width="40" height="8" rx="2" fill="#78350f"/>
            <rect x="-14" y="2" width="28" height="8" rx="1" fill="#fef3c7" opacity="0.9"/>
            <path d="M-42 -4 L-48 12 L-36 12 Z" fill="url(#sitenorTrophyGold)"/>
            <path d="M42 -4 L48 12 L36 12 Z" fill="url(#sitenorTrophyGold)"/>
            <ellipse cx="0" cy="-50" rx="6" ry="6" fill="#fbbf24"/>
        </g>
    </g>

    {{-- Medali kecil --}}
    <circle cx="248" cy="200" r="22" fill="#fff" stroke="#e2e8f0" stroke-width="2"/>
    <circle cx="248" cy="200" r="16" fill="#fbbf24"/>
    <text x="248" y="205" text-anchor="middle" font-size="14" font-weight="800" fill="#92400e" font-family="system-ui,sans-serif">1</text>
</svg>
