<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

$so_page = 'contact';
$pageTitle = 'Contact Us';
$pageDescription = 'Get in touch with Skin Origins Clinic, Jubilee Hills, Hyderabad. Call +91 90006 00177 or send us a message.';

$formStatus = null;
$formMessage = '';
$old = [
    'name' => '',
    'email' => '',
    'phone' => '',
    'service' => '',
    'message' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['name'] = trim((string) ($_POST['name'] ?? ''));
    $old['email'] = trim((string) ($_POST['email'] ?? ''));
    $old['phone'] = trim((string) ($_POST['phone'] ?? ''));
    $old['service'] = trim((string) ($_POST['service'] ?? ''));
    $old['message'] = trim((string) ($_POST['message'] ?? ''));

    $errors = [];
    if ($old['name'] === '' || strlen($old['name']) < 2) {
        $errors[] = 'Please enter your full name.';
    }
    if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    $digits = preg_replace('/\D+/', '', $old['phone']) ?? '';
    if ($old['phone'] === '' || strlen($digits) < 10) {
        $errors[] = 'Please enter a valid phone number.';
    }
    if ($old['message'] === '' || strlen($old['message']) < 10) {
        $errors[] = 'Please share a short message (at least 10 characters).';
    }

    if ($errors !== []) {
        $formStatus = 'error';
        $formMessage = implode(' ', $errors);
    } else {
        // Persist locally for deployments without mail transport.
        $logDir = SO_ROOT . '/storage';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0755, true);
        }
        $entry = [
            'received_at' => date('c'),
            'name' => $old['name'],
            'email' => $old['email'],
            'phone' => $old['phone'],
            'service' => $old['service'],
            'message' => $old['message'],
            'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
        ];
        @file_put_contents(
            $logDir . '/contact-submissions.log',
            json_encode($entry, JSON_UNESCAPED_UNICODE) . PHP_EOL,
            FILE_APPEND | LOCK_EX
        );

        // Best-effort email when the host supports it.
        $subject = 'New consultation request — Skin Origins';
        $body = "Name: {$old['name']}\nEmail: {$old['email']}\nPhone: {$old['phone']}\nService: {$old['service']}\n\nMessage:\n{$old['message']}\n";
        $headers = 'From: ' . SO_SITE['email'] . "\r\n" . 'Reply-To: ' . $old['email'] . "\r\n";
        @mail(SO_SITE['email'], $subject, $body, $headers);

        $formStatus = 'success';
        $formMessage = 'Thank you. Your message has been received — our team will get back to you within 24 hours.';
        $old = ['name' => '', 'email' => '', 'phone' => '', 'service' => '', 'message' => ''];
    }
}

$mapQuery = rawurlencode('Skin Origins Plot No 245 Road Number 78 Phase 3 Jubilee Hills Hyderabad 500034');

require SO_INCLUDES . '/header.php';
?>

<section class="page-hero" aria-label="Contact Skin Origins">
    <div
        class="page-hero__bg"
        style="background-image:url('<?= so_e(so_asset('images/clinic/Treatment-Room-1.webp')) ?>')"
        role="img"
        aria-label="Skin Origins clinic treatment room"></div>
    <div class="page-hero__overlay" aria-hidden="true"></div>
    <div class="page-hero__inner">
        <span class="page-hero__label">Get In Touch</span>
        <h1 class="page-hero__title">We'd Love to Hear From You</h1>
        <p class="page-hero__lead">
            Have questions about our treatments? Ready to start your transformation journey?
        </p>
    </div>
</section>

<section class="page-section page-section--cream">
    <div class="page-wrap--wide">
        <div class="contact-grid">
            <article class="contact-card">
                <div class="contact-card__label">Phone</div>
                <a href="tel:<?= so_e(SO_SITE['phone_tel']) ?>"><?= so_e(SO_SITE['phone']) ?></a>
                <p>Mon – Sat: 10AM – 8PM</p>
            </article>
            <article class="contact-card">
                <div class="contact-card__label">Email</div>
                <a href="mailto:<?= so_e(SO_SITE['email']) ?>"><?= so_e(SO_SITE['email']) ?></a>
                <p>We'll respond within 24 hours</p>
            </article>
            <article class="contact-card">
                <div class="contact-card__label">Visit Us</div>
                <strong>Skin Origins</strong>
                <p>Plot No - 245, Road Number 78<br>Phase 3, Jubilee Hills, Hyderabad 500034</p>
            </article>
        </div>
    </div>
</section>

<section class="page-section page-section--white">
    <div class="page-wrap--wide contact-layout">
        <aside>
            <span class="page-label">Find Us</span>
            <div class="map-card">
                <iframe
                    title="Skin Origins Clinic location map"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    src="https://www.google.com/maps?q=<?= so_e($mapQuery) ?>&output=embed"></iframe>
                <div class="map-card__body">
                    <h3>Visit Our Clinic</h3>
                    <p>Located in the heart of Jubilee Hills, easily accessible from all parts of Hyderabad.</p>
                    <p><?= so_e(SO_SITE['address']) ?></p>
                    <div class="hours-list">
                        <div><span>Mon – Sat</span><span>10AM – 8PM</span></div>
                        <div><span>Sunday</span><span>Closed</span></div>
                    </div>
                </div>
            </div>
        </aside>

        <div>
            <span class="page-label">Send Us a Message</span>
            <form class="contact-form" method="post" action="<?= so_e(so_url('/contact.php')) ?>" novalidate>
                <h2>Let's Start a Conversation</h2>
                <p>Fill out the form below and our team will get back to you within 24 hours.</p>

                <?php if ($formStatus !== null): ?>
                    <div class="form-status is-<?= so_e($formStatus) ?>" role="alert">
                        <?= so_e($formMessage) ?>
                    </div>
                <?php endif; ?>

                <div class="form-row form-row--2">
                    <div class="form-field">
                        <label for="name">Full Name</label>
                        <input id="name" name="name" type="text" autocomplete="name" required placeholder="Your full name" value="<?= so_e($old['name']) ?>">
                    </div>
                    <div class="form-field">
                        <label for="email">Email Address</label>
                        <input id="email" name="email" type="email" autocomplete="email" required placeholder="you@email.com" value="<?= so_e($old['email']) ?>">
                    </div>
                </div>

                <div class="form-row form-row--2">
                    <div class="form-field">
                        <label for="phone">Phone Number</label>
                        <input id="phone" name="phone" type="tel" autocomplete="tel" required placeholder="+91 XXXXX XXXXX" value="<?= so_e($old['phone']) ?>">
                    </div>
                    <div class="form-field">
                        <label for="service">Service Interest</label>
                        <select id="service" name="service">
                            <option value="" <?= $old['service'] === '' ? ' selected' : '' ?>>Select a service</option>
                            <option value="Skin" <?= $old['service'] === 'Skin' ? ' selected' : '' ?>>Skin</option>
                            <option value="Hair" <?= $old['service'] === 'Hair' ? ' selected' : '' ?>>Hair</option>
                            <option value="Wellness" <?= $old['service'] === 'Wellness' ? ' selected' : '' ?>>Wellness</option>
                            <option value="General" <?= $old['service'] === 'General' ? ' selected' : '' ?>>General Consultation</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-field">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" required placeholder="Tell us about your inquiry..."><?= so_e($old['message']) ?></textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button class="btn btn-primary" type="submit">Book Appointment</button>
                </div>
            </form>
        </div>
    </div>
</section>

<?php
require SO_INCLUDES . '/footer.php';
