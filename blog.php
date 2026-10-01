<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

header('Location: ' . so_url('/blogs/'), true, 301);
exit;
