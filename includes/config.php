<?php
/**
 * Skin Origins Clinic — Site configuration
 */

declare(strict_types=1);

if (!defined('SO_ROOT')) {
    define('SO_ROOT', dirname(__DIR__));
}

/** Base URL path (empty if site is at domain root) */
define('SO_BASE_PATH', '');

/** Absolute filesystem paths */
define('SO_INCLUDES', SO_ROOT . '/includes');
define('SO_SECTIONS', SO_ROOT . '/sections');
define('SO_PARTIALS', SO_ROOT . '/partials');
define('SO_PAGES', SO_ROOT . '/pages');
define('SO_ASSETS', SO_ROOT . '/assets');

/** Public asset URL prefix */
define('SO_ASSETS_URL', SO_BASE_PATH . '/assets');

/** Clinic info (from live site) */
const SO_SITE = [
    'name'        => 'Skin Origins Clinic',
    'tagline'     => 'Where Confidence Begins',
    'phone'       => '+91 90006 00177',
    'phone_tel'   => '+919000600177',
    'email'       => 'info@skinoriginsclinic.com',
    'address'     => 'Skin Origins Plot No - 245, Road Number 78, Phase 3, Jubilee Hills, Hyderabad, 500034',
    'whatsapp'    => '919000600177',
    'instagram'   => 'https://www.instagram.com/skinorigins',
    'facebook'    => '#',
    'twitter'     => '#',
    'youtube'     => '#',
    'google_reviews' => 'https://www.google.com/search?q=Skin+Origins+Clinic+Hyderabad+reviews',
    'year'        => '2026',
];

/** Primary navigation */
const SO_NAV = [
    ['label' => 'Home',     'slug' => 'home',     'path' => '/'],
    ['label' => 'About',    'slug' => 'about',    'path' => '/about.php'],
    ['label' => 'Doctor',   'slug' => 'doctor',   'path' => '/doctor.php'],
    [
        'label' => 'Services',
        'slug'  => 'service',
        'path'  => '/skin/',
        'children' => [
            ['label' => 'Skin',     'slug' => 'skin',     'path' => '/skin/'],
            ['label' => 'Hair',     'slug' => 'hair',     'path' => '/hair/'],
            ['label' => 'Wellness', 'slug' => 'wellness', 'path' => '/wellness/'],
        ],
    ],
    ['label' => 'Blog',       'slug' => 'blog',     'path' => '/blogs/'],
    ['label' => 'Contact Us', 'slug' => 'contact',  'path' => '/contact.php'],
];

/** Footer quick links */
const SO_FOOTER_LINKS = [
    ['label' => 'Home',       'path' => '/'],
    ['label' => 'About',      'path' => '/about.php'],
    ['label' => 'Blog',       'path' => '/blogs/'],
    ['label' => 'Contact Us', 'path' => '/contact.php'],
];

/** Footer services — live SEO paths */
const SO_FOOTER_SERVICES = [
    ['label' => 'Skin',     'path' => '/skin/'],
    ['label' => 'Hair',     'path' => '/hair/'],
    ['label' => 'Wellness', 'path' => '/wellness/'],
];

/** Brand color tokens (PHP mirror of CSS variables — useful for inline/email later) */
const SO_COLORS = [
    'cream'            => '#ebe1ca',
    'cream_light'      => '#f1e6d0',
    'cream_soft'       => '#f0e7d6',
    'terracotta'       => '#a84720',
    'terracotta_dark'  => '#923b1f',
    'gold'             => '#a0976c',
    'olive'            => '#757056',
    'dark'             => '#2d2418',
    'text'             => '#262626',
    'muted'            => '#908a85',
    'white'            => '#ffffff',
    'whatsapp'         => '#39b54a',
];
