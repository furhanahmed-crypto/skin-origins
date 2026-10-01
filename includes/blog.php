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
 * Render structured content blocks from a blog post array.
 */
function so_blog_render_blocks(array $blocks): void
{
    foreach ($blocks as $block) {
        $type = $block['type'] ?? 'p';

        if ($type === 'h2') {
            echo '<h2>' . so_e((string) ($block['text'] ?? '')) . '</h2>';
            continue;
        }

        if ($type === 'ul') {
            echo '<ul>';
            foreach ($block['items'] ?? [] as $item) {
                echo '<li>' . so_e((string) $item) . '</li>';
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
        echo '<p>' . so_e($text) . '</p>';
    }
}
