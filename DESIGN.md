# DESIGN.md — Skin Origins design system

Visual reference: [skinoriginsclinic.com](https://skinoriginsclinic.com/)  
Implementation source: `assets/css/variables.css`, `main.css`, `home.css`, `pages.css`

## Brand colors

| Token | Hex | Use |
|-------|-----|-----|
| `--color-cream` | `#ebe1ca` | Soft surfaces, Book Now fill |
| `--color-cream-light` | `#f1e6d0` | Light text on dark |
| `--color-cream-soft` | `#f0e7d6` | Section backgrounds |
| `--color-terracotta` | `#a84720` | Primary CTA, accents |
| `--color-terracotta-dark` | `#923b1f` | Header, solid page banners, dark CTAs |
| `--color-gold` | `#a0976c` | Labels, secondary accent |
| `--color-olive` | `#757056` | Body text on light |
| `--color-dark` | `#2d2418` | Footer, dark headings |
| `--color-text` | `#262626` | Default text |
| `--color-muted` | `#908a85` | Muted UI |
| `--color-whatsapp` | `#39b54a` | FAB |

### Contrast rules

- On terracotta/dark: use white or cream text (`#fff` / `--color-cream-light`).
- On cream/white: use `--color-dark`, `--color-terracotta-dark`, or `--color-olive` — never gold-on-cream for primary buttons.
- Book Now: cream background + terracotta-dark text.
- If a live-site combo is unreadable, fix contrast while keeping brand hues.

## Typography

- **Headings:** `Georgia, "Times New Roman", serif`
- **UI / body:** `"Montserrat", sans-serif` (Google Fonts weights 300–700)
- Labels: uppercase, wide letter-spacing (~`0.28em`–`0.35em`), gold or cream depending on surface
- Hero titles: large clamp sizes; homepage hero desktop up to ~`4.5rem`

## Layout

- Content width: `--max-width: 1120px` (wide: `1280px`)
- Section padding: ~`3rem`–`5rem` vertical
- Buttons: pill (`border-radius: 999px`), generous padding on hero CTAs
- Cards: soft radius `14px`–`18px`, light borders using terracotta/gold at low opacity
- Sticky header: terracotta-dark; Services dropdown matches header bg + caret

## Page patterns

### Homepage

1. Hero slider (4 images, shared copy)  
2. Who We Are (large doctor image + copy)  
3. Services tabs (Skin / Hair / Wellness)  
4. Clinical Results (before/after)  
5. Why Choose Us  
6. Google reviews carousel  
7. Experience Excellence infinite marquee (hover title overlay)  
8. Instagram reels (local video, `aspect-ratio: 9/16`, `object-fit: cover`)  
9. Footer + WhatsApp/Call FABs  

### Inner pages

- **About / Doctor banners:** solid `--color-terracotta-dark`, **centered** copy (no hero photo crop).
- **Contact banner:** clinic image + strong dark overlay OK if text stays crisp.
- Shared: `.page-hero`, `.page-section`, `.split`, `.cta-band`, form/map patterns in `pages.css`.

## Motion

- Keep transitions short (`~0.25s ease`)
- Marquee: CSS infinite scroll; pause on hover
- Hero: fade slides ~5.5s
- Respect `prefers-reduced-motion` via token overrides in `variables.css`

## Imagery

| Folder | Purpose |
|--------|---------|
| `assets/images/logo/` | Logo SVG/webp |
| `assets/images/home/hero/` | Homepage slides |
| `assets/images/home/services/` | Treatment circles |
| `assets/images/home/before-after/` | Clinical results |
| `assets/images/home/instagram/` | Reel posters |
| `assets/images/about/` | About story/sanctuary |
| `assets/images/doctor/` | Doctor portraits |
| `assets/images/clinic/` | Experience marquee |
| `assets/videos/` | Local Instagram reels |

Prefer `-scaled` or web-sized assets for performance; keep full-res available when needed.
