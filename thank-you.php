<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

$so_page = 'contact';
$pageTitle = 'Thank You';
$pageDescription = 'Thank you for contacting Skin Origins Clinic. We have received your response.';

require SO_INCLUDES . '/header.php';
?>

<section class="page-section page-section--cream thank-you">
    <div class="page-wrap thank-you__inner">
        <span class="page-label">Thank You</span>
        <h1 class="page-title">We have received your response</h1>
        <p class="page-text">
            Thank you for reaching out to Skin Origins. Our team will get back to you shortly.
        </p>
        <div class="thank-you__actions">
            <a class="btn btn-primary" href="<?= so_e(so_url('/')) ?>">Back to Home</a>
            <a class="btn btn-outline-dark" href="<?= so_e(so_url('/contact.php')) ?>">Contact Us</a>
        </div>
    </div>
</section>

<?php
require SO_INCLUDES . '/footer.php';
