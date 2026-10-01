<?php
/**
 * Skin Origins — Shared helpers
 */

declare(strict_types=1);

require_once __DIR__ . '/config.php';

/**
 * Escape for HTML body/text.
 */
function so_e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Build a site URL from a path.
 */
function so_url(string $path = '/'): string
{
    if ($path === '' || $path[0] !== '/') {
        $path = '/' . $path;
    }

    return SO_BASE_PATH . $path;
}

/**
 * Asset URL helper.
 */
function so_asset(string $path): string
{
    return SO_ASSETS_URL . '/' . ltrim($path, '/');
}

/**
 * Include a section partial (e.g. sections/home/hero.php).
 */
function so_section(string $relativePath, array $data = []): void
{
    $file = SO_SECTIONS . '/' . ltrim($relativePath, '/');

    if (!is_file($file)) {
        echo '<!-- Missing section: ' . so_e($relativePath) . ' -->';
        return;
    }

    if ($data !== []) {
        extract($data, EXTR_SKIP);
    }

    include $file;
}

/**
 * Include a shared partial (e.g. partials/fab.php).
 */
function so_partial(string $relativePath, array $data = []): void
{
    $file = SO_PARTIALS . '/' . ltrim($relativePath, '/');

    if (!is_file($file)) {
        echo '<!-- Missing partial: ' . so_e($relativePath) . ' -->';
        return;
    }

    if ($data !== []) {
        extract($data, EXTR_SKIP);
    }

    include $file;
}

/**
 * Current page slug for active nav states.
 */
function so_current_page(): string
{
    return $GLOBALS['so_page'] ?? 'home';
}

/**
 * Whether a nav item matches the current page.
 */
function so_is_active(string $slug): bool
{
    $current = so_current_page();

    if ($slug === $current) {
        return true;
    }

    // Parent "service" active on skin/hair/wellness
    if ($slug === 'service' && in_array($current, ['skin', 'hair', 'wellness'], true)) {
        return true;
    }

    return false;
}

/**
 * Page title for <title> tag.
 */
function so_page_title(?string $title = null): string
{
    $site = SO_SITE['name'];

    if ($title === null || $title === '') {
        return $site;
    }

    return $title . ' - skinoriginsclinic';
}
