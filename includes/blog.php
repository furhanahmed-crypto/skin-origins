<?php
/**
 * Blog helpers — listing + post loading + block rendering
 */

declare(strict_types=1);

require_once __DIR__ . '/functions.php';

function so_blog_posts(): array
{
    static $posts = null;
    if ($posts === null) {
        $posts = require SO_INCLUDES . '/data/blog/index.php';
    }

    return $posts;
}

function so_blog_post(string $slug): ?array
{
    $file = SO_INCLUDES . '/data/blog/posts/' . $slug . '.php';
    if (!is_file($file)) {
        return null;
    }

    $post = require $file;

    return is_array($post) ? $post : null;
}

function so_blog_url(string $slug = ''): string
{
    if ($slug === '') {
        return so_url('/blogs/');
    }

    return so_url('/blog/' . trim($slug, '/') . '/');
}

/**
 * Escape text and expand {{linkKey}} placeholders into <a> tags.
 *
 * links: [ 'key' => ['label' => '...', 'href' => '...', 'external' => bool] ]
 */
function so_blog_rich_text(string $text, array $links = []): string
{
    if ($links === [] || !str_contains($text, '{{')) {
        return so_e($text);
    }

    $parts = preg_split('/(\{\{[a-zA-Z0-9_]+\}\})/', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
    if ($parts === false) {
        return so_e($text);
    }

    $html = '';
    foreach ($parts as $part) {
        if ($part === '') {
            continue;
        }
        if (preg_match('/^\{\{([a-zA-Z0-9_]+)\}\}$/', $part, $m) === 1) {
            $key = $m[1];
            if (!isset($links[$key]) || !is_array($links[$key])) {
                $html .= so_e($part);
                continue;
            }
            $href = trim((string) ($links[$key]['href'] ?? ''));
            $label = trim((string) ($links[$key]['label'] ?? ''));
            if ($href === '' || $label === '') {
                $html .= so_e($label !== '' ? $label : $part);
                continue;
            }
            $attrs = 'href="' . so_e($href) . '" class="blog-inline-link"';
            if (!empty($links[$key]['external'])) {
                $attrs .= ' target="_blank" rel="noopener"';
            }
            $html .= '<a ' . $attrs . '>' . so_e($label) . '</a>';
            continue;
        }
        $html .= so_e($part);
    }

    return $html;
}

/**
 * Render structured content blocks from a blog post array.
 */
function so_blog_render_blocks(array $blocks): void
{
    foreach ($blocks as $block) {
        $type = $block['type'] ?? 'p';
        $links = is_array($block['links'] ?? null) ? $block['links'] : [];

        if ($type === 'h2') {
            echo '<h2>' . so_blog_rich_text((string) ($block['text'] ?? ''), $links) . '</h2>';
            continue;
        }

        if ($type === 'ul') {
            echo '<ul>';
            foreach ($block['items'] ?? [] as $item) {
                if (is_array($item)) {
                    $itemLinks = is_array($item['links'] ?? null) ? $item['links'] : $links;
                    echo '<li>' . so_blog_rich_text((string) ($item['text'] ?? ''), $itemLinks) . '</li>';
                } else {
                    echo '<li>' . so_blog_rich_text((string) $item, $links) . '</li>';
                }
            }
            echo '</ul>';
            continue;
        }

        if ($type === 'table') {
            $headers = $block['headers'] ?? [];
            $rows = $block['rows'] ?? [];
            if ($headers === [] || $rows === []) {
                continue;
            }
            echo '<div class="blog-table-wrap"><table class="blog-table"><thead><tr>';
            foreach ($headers as $header) {
                echo '<th scope="col">' . so_e((string) $header) . '</th>';
            }
            echo '</tr></thead><tbody>';
            foreach ($rows as $row) {
                echo '<tr>';
                foreach ($row as $cell) {
                    echo '<td>' . so_e((string) $cell) . '</td>';
                }
                echo '</tr>';
            }
            echo '</tbody></table></div>';
            continue;
        }

        $text = (string) ($block['text'] ?? '');
        if ($text === '') {
            continue;
        }
        echo '<p>' . so_blog_rich_text($text, $links) . '</p>';
    }
}
