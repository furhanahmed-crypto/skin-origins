#!/usr/bin/env python3
"""Import Final Service Page Content SEO PDF text into includes/data/services/details/{category}/{slug}.php"""

from __future__ import annotations

import re
import shutil
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
OUT_DIR = ROOT / 'includes' / 'data' / 'services' / 'details'


def clean(s: str) -> str:
    if not s:
        return ''
    s = (
        s.replace('\uE029', '–')
        .replace('\uE089', '–')
        .replace('\uE001', '(')
        .replace('\uE081', '(')
        .replace('\uE002', ')')
        .replace('\uE082', ')')
        .replace('\uE041', '[')
        .replace('\uE083', '[')
        .replace('\uE009', '–')
        .replace('\uE00A', '–')
    )
    s = re.sub(r'[\uE000-\uF8FF]+', '', s)
    s = s.replace('\u2013', '–').replace('\u2014', '—')
    s = re.sub(r'[ \t]+', ' ', s)
    return s.strip()


def norm_space(s: str) -> str:
    return clean(re.sub(r'\s+', ' ', s))


def join_wrapped_attr(raw: str) -> str:
    """Rejoin PDF soft-wraps inside quoted HTML attributes (mid-word breaks)."""
    lines = [ln.strip() for ln in raw.splitlines() if ln.strip()]
    if not lines:
        return ''
    out = lines[0]
    for line in lines[1:]:
        if out.endswith('-'):
            out += line  # keep hyphenated compounds: Dermatologist-supervised
        elif out and out[-1].isalpha() and line[0].islower():
            out += line
        else:
            out += ' ' + line
    return clean(out)


def extract_meta_content(block: str, name: str) -> str:
    m = re.search(rf'<meta name="{name}" content="(.*?)"\s*/?>', block, re.S)
    return join_wrapped_attr(m.group(1)) if m else ''


def extract_og(block: str, prop: str) -> str:
    m = re.search(rf'<meta property="{re.escape(prop)}" content="(.*?)"\s*/?>', block, re.S)
    return join_wrapped_attr(m.group(1)) if m else ''


def paras_from_lines(lines: list[str]) -> list[str]:
    paras: list[str] = []
    buf: list[str] = []
    for line in lines:
        line = clean(line)
        if not line:
            if buf:
                paras.append(norm_space(' '.join(buf)))
                buf = []
            continue
        if 'Image slot' in line or 'Editor note' in line or 'not for publishing' in line.lower():
            continue
        if line.isupper() and len(line) < 40:
            continue
        if buf and re.search(r'[.!?]$', buf[-1]) and re.match(r'^[A-Z“"]', line):
            paras.append(norm_space(' '.join(buf)))
            buf = [line]
        else:
            buf.append(line)
    if buf:
        paras.append(norm_space(' '.join(buf)))
    return [p for p in paras if p]


BULLET_START_RE = re.compile(
    r"^(You |Your |You'd |You're |You've |Skin that |Tanning |Post-acne |Oily |Rough )"
)


def looks_like_bullet(text: str) -> bool:
    if BULLET_START_RE.match(text):
        return True
    if len(text) <= 110 and text.count('.') == 0 and not re.search(
        r'\b(usually|always|formula|reviewed|consultation|Hyderabad is|Medically)\b',
        text,
        re.I,
    ):
        return True
    return False


def is_bullet_start(line: str) -> bool:
    return bool(BULLET_START_RE.match(line)) or (
        len(line) <= 110
        and not line.endswith('.')
        and line[0].isupper()
        and not line[0].islower()
    )


def extract_ul_items(part: str, ul_m: re.Match) -> list[str]:
    """Lines immediately before UL marker are who-is bullets (PDF puts the UL label after the list)."""
    before_lines = part[: ul_m.start()].splitlines()
    collected_rev: list[str] = []
    for line in reversed(before_lines):
        raw = line.rstrip()
        stripped = raw.strip()
        if not stripped:
            if collected_rev:
                break
            continue
        if stripped.startswith(
            (
                'Medically reviewed',
                'Related treatments',
                'H2 ',
                'H3 ',
                'H1 ',
                'UL',
                'OL',
                '[Image',
                'Image slot',
            )
        ) or stripped.startswith('\ue083') or 'Image slot' in stripped:
            break
        if 'Jubilee Hills, Hyderabad · Last reviewed' in stripped or stripped.startswith(
            'Jubilee Hills, Hyderabad ·'
        ):
            break
        collected_rev.append(clean(stripped))

    lines = list(reversed(collected_rev))
    items: list[str] = []
    buf: str | None = None
    for line in lines:
        if not line:
            continue
        if buf is None:
            buf = line
            continue
        cont = line[0].islower() or bool(re.search(r'(?:/[\w\-]+,?|/|,\s*| and| or| from| after| with)$', buf))
        if cont and not is_bullet_start(line):
            buf = f'{buf} {line}'
            continue
        # New bullet (You/Your/... or short capital concern line)
        if is_bullet_start(line) or (len(buf) <= 120 and not buf.endswith('.')):
            items.append(norm_space(buf))
            buf = line
            continue
        if buf.endswith('.'):
            items.append(norm_space(buf))
            buf = line
            continue
        buf = f'{buf} {line}'
    if buf:
        items.append(norm_space(buf))
    return [i for i in items if looks_like_bullet(i)]


def extract_ol_items(part: str, ul_m: re.Match, ol_m: re.Match) -> list[str]:
    step_region = part[ul_m.end() : ol_m.start()]
    steps: list[str] = []
    buf: list[str] = []
    for line in step_region.splitlines():
        line = clean(line)
        if not line:
            if buf:
                steps.append(norm_space(' '.join(buf)))
                buf = []
            continue
        if buf and re.match(r'^[A-Z][^:]{0,50}:\s', line):
            steps.append(norm_space(' '.join(buf)))
            buf = [line]
        else:
            buf.append(line)
    if buf:
        steps.append(norm_space(' '.join(buf)))
    return [s for s in steps if s and re.match(r'^[A-Z].{0,60}:\s', s)]


def php_escape(s: str) -> str:
    return s.replace('\\', '\\\\').replace("'", "\\'")


def php_export(val, indent: int = 0) -> str:
    sp = '    ' * indent
    if isinstance(val, dict):
        if not val:
            return '[]'
        lines = ['[']
        for k, v in val.items():
            lines.append(f"{sp}    '{php_escape(str(k))}' => {php_export(v, indent + 1)},")
        lines.append(f'{sp}]')
        return '\n'.join(lines)
    if isinstance(val, list):
        if not val:
            return '[]'
        if all(isinstance(x, str) for x in val):
            lines = ['[']
            for x in val:
                lines.append(f"{sp}    '{php_escape(x)}',")
            lines.append(f'{sp}]')
            return '\n'.join(lines)
        lines = ['[']
        for x in val:
            lines.append(f'{sp}    {php_export(x, indent + 1)},')
        lines.append(f'{sp}]')
        return '\n'.join(lines)
    if isinstance(val, str):
        return f"'{php_escape(val)}'"
    if val is None:
        return 'null'
    if isinstance(val, bool):
        return 'true' if val else 'false'
    return repr(val)


def parse_pdf_text(raw: str) -> list[dict]:
    raw = re.sub(r'\n===== PAGE \d+ =====\n', '\n', raw)
    raw = re.sub(
        r'Skin Origins [–\uE089\uE029-] Final Service Page Content · October 2026 Page \d+ of 68\n?',
        '\n',
        raw,
    )
    parts = re.split(r'SEO SETUP · PAGE \d+ OF 22\n', raw)[1:]
    services: list[dict] = []

    for part in parts:
        part = re.split(r'\nSEO SETUP ·', part)[0]
        url = re.search(r'URL https://skinoriginsclinic\.com(/[^\s]+)', part).group(1)
        bits = [p for p in url.strip('/').split('/') if p]
        category, slug = bits[0], bits[1]

        meta_title = clean(re.search(r'<title>([^<]+)</title>', part).group(1))
        meta_desc = extract_meta_content(part, 'description')
        meta_kw = extract_meta_content(part, 'keywords')
        og_title = extract_og(part, 'og:title') or meta_title
        og_desc = extract_og(part, 'og:description') or meta_desc
        og_image_alt = extract_og(part, 'og:image:alt')

        h1m = re.search(r'^H1 (.+)$', part, re.M)
        h1 = clean(h1m.group(1))
        # H1 can soft-wrap onto the next line(s) in the PDF extract
        h1_tail_lines = part[h1m.end() :].splitlines(keepends=True)
        # Skip the empty segment created by the newline after H1
        idx = 0
        consumed = 0
        while idx < len(h1_tail_lines) and not clean(h1_tail_lines[idx]):
            consumed += len(h1_tail_lines[idx])
            idx += 1
        while idx < len(h1_tail_lines):
            raw_line = h1_tail_lines[idx]
            cont = clean(raw_line)
            if not cont:
                break
            if cont.startswith(('H2 ', 'H3 ', 'Related', 'Medically', 'UL', 'OL')):
                break
            # Body copy starts with a full sentence; title fragments are short
            if len(cont) > 80 or (cont.endswith('.') and len(cont) > 40):
                break
            if (
                cont[0].islower()
                or h1.endswith(',')
                or cont in {'Hyderabad', 'Hills, Hyderabad', 'Jubilee Hills, Hyderabad'}
                or cont.startswith('Hills')
                or (len(cont) < 40 and 'Hyderabad' in cont)
            ):
                h1 = norm_space(f'{h1} {cont}')
                consumed += len(raw_line)
                idx += 1
                continue
            break
        body_start = h1m.end() + consumed
        faq_h2 = re.search(r'^H2 (.+FAQs.+)$', part, re.M)
        faq_heading = clean(faq_h2.group(1))

        ul_m = re.search(r'^UL\s*$', part, re.M)
        ol_m = re.search(r'^OL\s*$', part, re.M)
        ul_items = extract_ul_items(part, ul_m) if ul_m else []
        ol_items = extract_ol_items(part, ul_m, ol_m) if ul_m and ol_m else []

        body = part[body_start : faq_h2.start()]
        body_main = re.split(r'^Related treatments:', body, maxsplit=1, flags=re.M)[0]
        body_main = re.sub(r'[\[\uE083]Image slot:.*?\]', '', body_main, flags=re.S)

        related: list[dict] = []
        rel_m = re.search(
            r'Related treatments:\s*(.+?)(?:\nMedically reviewed|\nUL\b|\nOL\b|\nH2 |\nH3 |\nYou |\Z)',
            body,
            re.S,
        )
        if not rel_m:
            rel_m = re.search(
                r'Related treatments:\s*(.+?)(?:\nMedically reviewed|\nUL\b|\nOL\b|\nH2 |\nH3 |\Z)',
                part,
                re.S,
            )
        if rel_m:
            rr = norm_space(rel_m.group(1))
            for label, path in re.findall(r'([^·/]+?)\s*(/(?:skin|hair|wellness)/[\w\-]+/)', rr):
                label = clean(label).strip(' ·')
                if label:
                    related.append({'label': label, 'path': path})

        reviewed = (
            'Medically reviewed by Dr. Suvidha Reddy, MBBS, MD (Dermatology), '
            'Cosmetic Dermatologist, Skin Origins Clinic, Jubilee Hills, Hyderabad · Last reviewed: October 2026'
        )
        rev_m = re.search(r'Medically reviewed by .+?(?=\n(?:UL|OL|H2|H3|Related|You )|$)', body, re.S)
        if rev_m:
            reviewed = norm_space(rev_m.group(0))
            reviewed = re.sub(r'\s*·\s*Last reviewed:.*$', ' · Last reviewed: October 2026', reviewed)
            reviewed = reviewed.replace('MD Dermatology)', 'MD (Dermatology)')

        h2_splits = re.split(r'^(H2 .+)$', body_main, flags=re.M)
        intro = paras_from_lines(h2_splits[0].splitlines())

        sections = []
        for j in range(1, len(h2_splits), 2):
            heading = clean(h2_splits[j][3:].strip())
            content = h2_splits[j + 1] if j + 1 < len(h2_splits) else ''
            paras = [
                p
                for p in paras_from_lines(content.splitlines())
                if 'Editor note' not in p and 'not for publishing' not in p.lower()
            ]
            sections.append({'heading': heading, 'paragraphs': paras, 'list': [], 'list_type': None})

        for sec in sections:
            h = sec['heading'].lower()
            if ul_items and any(k in h for k in ('who is', 'who are', 'right for', 'suit', 'candidate')):
                sec['list'] = ul_items
                sec['list_type'] = 'ul'
                ul_items = []
            elif ol_items and 'done at' in h:
                sec['list'] = ol_items
                sec['list_type'] = 'ol'
                ol_items = []

        if ul_items:
            for sec in sections:
                if not sec['list'] and any(k in sec['heading'].lower() for k in ('who', 'right for')):
                    sec['list'] = ul_items
                    sec['list_type'] = 'ul'
                    ul_items = []
                    break
        if ol_items:
            for sec in sections:
                if not sec['list'] and 'done at' in sec['heading'].lower():
                    sec['list'] = ol_items
                    sec['list_type'] = 'ol'
                    ol_items = []
                    break

        faqs = []
        faq_region = re.sub(r'(?ms)^UL\s*\n.*?\nOL\s*\n', '\n', part[faq_h2.end() :])
        for fp in re.split(r'^H3 ', faq_region, flags=re.M)[1:]:
            lines = fp.splitlines()
            q = clean(lines[0]) if lines else ''
            ans_lines: list[str] = []
            for line in lines[1:]:
                line = clean(line)
                if not line:
                    continue
                if line in ('UL', 'OL') or line.startswith(('H2 ', 'H1 ', 'SEO ', 'URL ')):
                    break
                if ans_lines and re.search(r'[.!?]$', ans_lines[-1]) and re.match(
                    r"^(You |Your |You'd |You're |You've |Skin that |Tanning |Post-acne |Oily |Rough |"
                    r'Medical screening:|Choosing the |Vital |Cannula |Drip session:|Observation )',
                    line,
                ):
                    break
                if re.search(r'Page \d+ of 68', line):
                    continue
                ans_lines.append(line)
            a = norm_space(' '.join(ans_lines))
            a = re.split(r'\bUL\b|\bOL\b', a)[0].strip()
            if q and a:
                faqs.append({'q': q, 'a': a})

        blocks = []
        for p in intro:
            blocks.append({'type': 'p', 'text': p})
        for sec in sections:
            blocks.append({'type': 'h2', 'text': sec['heading']})
            for p in sec['paragraphs']:
                blocks.append({'type': 'p', 'text': p})
            if sec['list']:
                blocks.append({'type': sec['list_type'] or 'ul', 'items': sec['list']})

        services.append(
            {
                'slug': slug,
                'category': category,
                'path': url,
                'h1': h1,
                'meta_title': meta_title,
                'meta_description': meta_desc,
                'meta_keywords': meta_kw,
                'og_title': og_title,
                'og_description': og_desc,
                'image_alt': og_image_alt or h1,
                'blocks': blocks,
                'related': related,
                'reviewed': reviewed,
                'faq_heading': faq_heading,
                'faqs': faqs,
            }
        )
    return services


def write_php(services: list[dict]) -> None:
    if OUT_DIR.exists():
        shutil.rmtree(OUT_DIR)
    OUT_DIR.mkdir(parents=True, exist_ok=True)
    for data in services:
        cat_dir = OUT_DIR / data['category']
        cat_dir.mkdir(exist_ok=True)
        php = (
            "<?php\n"
            "declare(strict_types=1);\n\n"
            f"/** SEO content for /{data['category']}/{data['slug']}/ — from Final Service Page Content PDF */\n"
            f"return {php_export(data)};\n"
        )
        (cat_dir / f"{data['slug']}.php").write_text(php)


def main() -> int:
    if len(sys.argv) < 2:
        print('Usage: import_service_seo.py <extracted-pdf.txt>', file=sys.stderr)
        return 1
    raw = Path(sys.argv[1]).read_text()
    services = parse_pdf_text(raw)
    write_php(services)
    for s in services:
        ul = sum(1 for b in s['blocks'] if b['type'] == 'ul')
        ol = sum(1 for b in s['blocks'] if b['type'] == 'ol')
        ul_n = next((len(b['items']) for b in s['blocks'] if b['type'] == 'ul'), 0)
        ol_n = next((len(b['items']) for b in s['blocks'] if b['type'] == 'ol'), 0)
        print(
            f"{s['category']}/{s['slug']}: faqs={len(s['faqs'])} rel={len(s['related'])} "
            f"ul={ul}:{ul_n} ol={ol}:{ol_n} title={len(s['meta_title'])}c desc={len(s['meta_description'])}c"
        )
    print(f'Wrote {len(services)} files to {OUT_DIR}')
    return 0


if __name__ == '__main__':
    raise SystemExit(main())
