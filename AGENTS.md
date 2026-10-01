# AGENTS.md — Rules for AI / coding agents

This file is the source of truth for how agents should work on the **Skin Origins PHP** replica. Follow it for every change.

## Project goal

Pixel-faithful, independently deployable PHP clone of [skinoriginsclinic.com](https://skinoriginsclinic.com/).

- Stack: plain PHP includes, CSS variables, vanilla JS (no WordPress/Elementor runtime).
- Assets are local under `assets/` (do not hotlink production media in final pages).
- Prefer shared includes (`includes/`, `sections/`, `partials/`) over duplicated markup.

## Phased delivery (do not skip ahead)

| Phase | Scope | Status guidance |
|-------|--------|-----------------|
| Foundation | Theme tokens, folder structure, image download | Done |
| Home | Full homepage (all sections) | Done — polish only unless asked |
| Phase 3 | About, Doctor, Contact | Done — polish only unless asked |
| Phase 4 | Service pages (Skin, Hair, Wellness) | Done — SEO paths `/skin/`, `/hair/`, `/wellness/` |
| Phase 5 | Blog | Current — `/blogs/` listing + `/blog/{slug}/` detail |

## Accuracy vs UX judgment

1. Match live **content, structure, and visual language** (colors, type, spacing, components).
2. When the live site has **bad contrast** (text unreadable on bg), fix professionally: keep brand colors, improve contrast. Do not blindly copy broken combos.
3. Prefer local images/videos + `<video>` over Instagram embeds for deployability.
4. Google reviews on the live site are hardcoded cards + Google links — mirror that unless a Places API is explicitly requested.

## Communication & change discipline

- Read the request carefully. If the user says **image size**, do not change **title size**.
- When correcting a mistake, **revert the wrong change** then apply the intended one.
- Prefer concise status updates; do not invent extra scope.
- Do not create commits unless the user asks.
- Do not write markdown files unless asked — **except** `AGENTS.md`, `DESIGN.md`, and `README.md` when the user requests project docs.

## Code structure rules

```
assets/css/     variables.css, reset.css, main.css, home.css, pages.css, layout.css
assets/js/      main.js, home.js, services.js
assets/images/  logo, home/*, about, doctor, clinic, services/*, icons
assets/videos/  Instagram reels (reel-01…04)
includes/       config.php, functions.php, header.php, footer.php, service-page.php
includes/data/  home/*, services/*, blog/index.php + blog/posts/{slug}.php
skin|hair|wellness/  SEO service category pages (index.php)
blogs/ + blog/  Blog listing (`/blogs/`) and detail (`/blog/{slug}/`)
sections/       home/*
partials/       fab.php, shared UI
*.php           index, about, doctor, contact, blog stub; router.php for pretty URLs
storage/        contact form logs (gitignored *.log)
```

- Page shell: `header.php` + page content + `footer.php` (+ FAB).
- Site config / nav: `includes/config.php`.
- Content arrays: fat/list sections live under `includes/data/`; short About/Doctor/Contact prose may stay in page files.
- Shared treatments: `data/services/treatments.php` powers homepage tabs + `/skin|hair|wellness/` pages.
- Helpers: `so_e()`, `so_url()`, `so_asset()`, `so_section()`, `so_partial()`.
- Homepage interactions live in `assets/js/home.js`; service pages in `assets/js/services.js`.
- Inner-page styles in `assets/css/pages.css`; homepage-heavy styles in `home.css`.
- Local server: `php -S localhost:8080 router.php` so `/skin/{slug}/` resolves.

## UI / UX standards

- Brand tokens in `assets/css/variables.css` — use CSS variables, do not invent a new palette.
- Typography: Georgia (headings), Montserrat (body/UI).
- Header Services dropdown: same terracotta header background, caret indicator, readable hover states.
- About & Doctor **banners**: solid `var(--color-terracotta-dark)`, **centered** content — no cropped portrait as hero background.
- Contact banner may use a clinic/interior image with a strong dark overlay for contrast.
- Forms: labeled fields, clear focus states, validation messages with readable contrast.
- Responsive: mobile-first checks for hero, tabs/services grid, reviews, marquee, Instagram cards (`object-fit: cover` + fixed aspect-ratio), footer, FABs.

## Content fidelity checklist

Before finishing a page:

- [ ] Compare headings/body copy to the live page (no missing hero paragraphs).
- [ ] All CTAs point to local routes (`/contact.php`, `#services`, etc.), not only the WordPress domain.
- [ ] Images exist under `assets/images/...` and use `so_asset()`.
- [ ] Active nav state via `$so_page` / `so_is_active()`.
- [ ] Desktop + mobile pass for alignment and contrast.

## What not to do

- Do not rebuild WordPress/Elementor plugins.
- Service SEO URLs must stay `/skin/`, `/hair/`, `/wellness/` (and `/skin/{slug}/` etc.); use `router.php` for the PHP built-in server.
- Do not expand Blog beyond a stub until Phase 5.
- Do not weaken contrast for “aesthetic” reasons.
- Do not leave remote `skinoriginsclinic.com` URLs as primary image `src`s in shipping pages.
