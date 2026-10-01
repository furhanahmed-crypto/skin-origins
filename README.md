# Skin Origins Clinic — PHP Replica

Standalone PHP website cloning [skinoriginsclinic.com](https://skinoriginsclinic.com/) for independent deployment (no WordPress).

## Quick start

```bash
php -S localhost:8080 router.php
```

Open [http://localhost:8080/](http://localhost:8080/). The router keeps SEO paths like `/skin/chemical-peeling/` working locally.

## Pages

| Route | File | Notes |
|-------|------|--------|
| `/` | `index.php` | Full homepage |
| `/about.php` | About | Solid terracotta banner, story, sanctuary |
| `/doctor.php` | Doctor | Bio, expertise, quote, own words + image |
| `/contact.php` | Contact | Info cards, form, map |
| `/blogs/` | `blogs/index.php` | Blog listing (10 SEO posts) |
| `/blog/{slug}/` | `blog/index.php` | Blog detail + FAQ schema |
| `/skin/`, `/hair/`, `/wellness/` | `skin|hair|wellness/index.php` | Treatments, why-us, FAQ, CTA |
| `/skin/{slug}/` (etc.) | same + router | Treatment detail (SSR + client nav) |

## Project docs

- [`AGENTS.md`](./AGENTS.md) — rules for AI/coding agents (phases, UX, do/don’t)
- [`DESIGN.md`](./DESIGN.md) — tokens, type, layout patterns, contrast rules

## Structure

```
assets/          CSS, JS, images, videos
includes/        config, helpers, header/footer, data
sections/home/   Homepage section includes
partials/        FAB and shared UI
storage/         Contact form log (*.log gitignored)
```

## Contact form

Contact and consultation forms post to `thank-you.php` and show a confirmation page. No email, logging, or API is used.

## Roadmap

1. ~~Foundation + assets~~  
2. ~~Homepage~~  
3. ~~About / Doctor / Contact~~  
4. Service pages (Skin, Hair, Wellness)  
5. Blog  

## License / content

Clinic branding, copy, and media belong to Skin Origins Clinic. This repo is an implementation replica for the project owner’s use.
