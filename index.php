<?php
/**
 * Home — full page replica
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

$so_page = 'home';
$pageTitle = 'Home';
$pageDescription = 'REDEFINING BEAUTY & WELLNESS — Timeless Elegance, Modern Science. Skin Origins Clinic, Jubilee Hills, Hyderabad.';

require SO_INCLUDES . '/header.php';

so_section('home/hero.php');
so_section('home/about.php');
so_section('home/services.php');
so_section('home/results.php');
so_section('home/why-us.php');
so_section('home/reviews.php');
so_section('home/experience.php');
so_section('home/instagram.php');

require SO_INCLUDES . '/footer.php';
