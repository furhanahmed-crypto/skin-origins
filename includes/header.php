<?php
declare(strict_types=1);

if (!isset($pageTitle)) {
    $pageTitle = 'Home';
}
if (!isset($pageDescription)) {
    $pageDescription = 'Experience world-class dermatological treatments and aesthetic care at Skin Origins Clinic, Jubilee Hills, Hyderabad.';
}
$currentPage = so_current_page();
$extraCss = $extraCss ?? [];
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= so_e($so_document_title ?? so_page_title($pageTitle)) ?></title>
    <meta name="description" content="<?= so_e($pageDescription) ?>">
    <link rel="icon" type="image/svg+xml" href="<?= so_e(so_asset('images/logo/logo.svg')) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= so_e(so_asset('css/main.css')) ?>">
    <link rel="stylesheet" href="<?= so_e(so_asset('css/home.css')) ?>">
    <link rel="stylesheet" href="<?= so_e(so_asset('css/pages.css')) ?>">
    <?php foreach ($extraCss as $css): ?>
        <link rel="stylesheet" href="<?= so_e(so_asset($css)) ?>">
    <?php endforeach; ?>
</head>
<body class="page-<?= so_e($currentPage) ?>">
<div class="site-shell">
    <header class="site-header" id="siteHeader" role="banner">
        <div class="site-header__inner">
            <a class="site-logo" href="<?= so_e(so_url('/')) ?>" aria-label="<?= so_e(SO_SITE['name']) ?>">
                <img src="<?= so_e(so_asset('images/logo/logo.svg')) ?>" alt="<?= so_e(SO_SITE['name']) ?>" width="160" height="52">
            </a>

            <nav class="main-nav" aria-label="Primary">
                <?php foreach (SO_NAV as $item): ?>
                    <?php if (!empty($item['children'])): ?>
                        <div class="has-children">
                            <a
                                href="<?= so_e(so_url($item['path'])) ?>"
                                class="nav-parent<?= so_is_active($item['slug']) ? ' is-active' : '' ?>"
                                aria-haspopup="true"
                                aria-expanded="false"
                            >
                                <span><?= so_e($item['label']) ?></span>
                                <svg class="nav-caret" viewBox="0 0 12 8" width="10" height="7" aria-hidden="true" focusable="false">
                                    <path d="M1 1.5L6 6.5L11 1.5" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
                            <div class="submenu" role="menu">
                                <?php foreach ($item['children'] as $child): ?>
                                    <a
                                        href="<?= so_e(so_url($child['path'])) ?>"
                                        role="menuitem"
                                        class="<?= so_is_active($child['slug']) ? 'is-active' : '' ?>"
                                    ><?= so_e($child['label']) ?></a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?= so_e(so_url($item['path'])) ?>" class="<?= so_is_active($item['slug']) ? 'is-active' : '' ?>">
                            <?= so_e($item['label']) ?>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </nav>

            <div class="header-actions">
                <a class="btn-book" href="<?= so_e(so_url('/contact.php')) ?>">Book Now</a>
                <button class="nav-toggle" type="button" aria-label="Menu Toggle" aria-expanded="false" aria-controls="mobileNav" data-nav-toggle>
                    <span></span>
                </button>
            </div>
        </div>

        <nav class="mobile-nav" id="mobileNav" aria-label="Mobile">
            <?php foreach (SO_NAV as $item): ?>
                <a href="<?= so_e(so_url($item['path'])) ?>"><?= so_e($item['label']) ?></a>
                <?php if (!empty($item['children'])): ?>
                    <div class="submenu">
                        <?php foreach ($item['children'] as $child): ?>
                            <a href="<?= so_e(so_url($child['path'])) ?>"><?= so_e($child['label']) ?></a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
            <a href="<?= so_e(so_url('/contact.php')) ?>">Book Now</a>
        </nav>
    </header>

    <main class="site-main" id="main-content">
