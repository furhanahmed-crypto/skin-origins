<?php
/**
 * Router for PHP built-in server — SEO-friendly URLs:
 * /skin|hair|wellness/{slug}/, /blogs/, /blog/{slug}/
 *
 * Usage: php -S localhost:8080 router.php
 */

declare(strict_types=1);

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
$file = __DIR__ . $uri;

// Serve real files/assets as-is
if ($uri !== '/' && is_file($file)) {
    return false;
}

// Directory with index.php
if (is_dir($file)) {
    $index = rtrim($file, '/') . '/index.php';
    if (is_file($index)) {
        require $index;
        return true;
    }
}

// /skin|hair|wellness/{slug}/
if (preg_match('#^/(skin|hair|wellness)(?:/([^/]+))?/?$#', $uri, $m)) {
    require __DIR__ . '/' . $m[1] . '/index.php';
    return true;
}

// /blog/{slug}/
if (preg_match('#^/blog/([^/]+)/?$#', $uri, $m)) {
    require __DIR__ . '/blog/index.php';
    return true;
}

// /blogs
if (preg_match('#^/blogs/?$#', $uri)) {
    require __DIR__ . '/blogs/index.php';
    return true;
}

return false;
