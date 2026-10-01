<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

$so_page = 'doctor';
$pageTitle = 'Doctor';
$pageDescription = 'Meet Dr. Suvidha Reddy — MBBS, MD (Dermatology). Cosmetic dermatologist and aesthetic medicine specialist at Skin Origins Clinic, Hyderabad.';

$expertise = [
    'Medical Dermatology',
    'Cosmetic & Aesthetic Procedures',
    'Laser Therapies',
    'Anti-Aging & Injectables',
    'Acne & Pigmentation',
    'Hair Restoration',
];

require SO_INCLUDES . '/header.php';
?>

<section class="page-hero page-hero--solid" aria-label="About the doctor">
    <div class="page-hero__inner">
        <span class="page-hero__label">The Mind Behind Skin Origins</span>
        <h1 class="page-hero__title">Dr. Suvidha Reddy</h1>
        <p class="page-hero__lead">
            <strong>MBBS, MD (Dermatology)</strong><br>
            Cosmetic Dermatologist &amp; Aesthetic Medicine Specialist
        </p>
    </div>
</section>

<section class="page-section page-section--white">
    <div class="page-wrap split">
        <div class="split__media">
            <img
                src="<?= so_e(so_asset('images/doctor/drsuvidha.webp')) ?>"
                alt="Dr. Suvidha Reddy"
                width="800"
                height="1000"
                loading="lazy"
            >
        </div>
        <div class="split__copy">
            <span class="page-label">About the Doctor</span>
            <h2 class="page-title">Empathy Before<br>Every Treatment</h2>
            <div class="doctor-meta">
                <span class="doctor-chip">10+ Years Experience</span>
                <span class="doctor-chip">Board Certified</span>
                <span class="doctor-chip">Patient-First Care</span>
            </div>
            <p class="page-text">
                Dr. Suvidha Reddy brings over a decade of clinical and cosmetic dermatology expertise to Skin Origins. Her practice is built on patient-first thinking — listening deeply before prescribing, and choosing precision over complexity.
            </p>
            <p class="page-text">
                Every consultation is an opportunity to understand the person behind the concern, and to design a plan that respects both science and self-confidence.
            </p>
            <a class="btn btn-primary" href="<?= so_e(so_url('/contact.php')) ?>" style="margin-top:1.25rem;">Book a Consultation</a>
        </div>
    </div>
</section>

<section class="page-section page-section--cream">
    <div class="page-wrap">
        <span class="page-label">Clinical Focus</span>
        <h2 class="page-title">Areas of Expertise</h2>
        <div class="expertise-grid" style="margin-top:2rem;">
            <?php foreach ($expertise as $item): ?>
                <article class="expertise-card">
                    <span class="expertise-card__mark" aria-hidden="true"></span>
                    <h3><?= so_e($item) ?></h3>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="page-section page-section--dark">
    <div class="page-wrap">
        <div class="quote-block">
            <blockquote>
                “Dermatology is not just about treating conditions, but about restoring reassurance, dignity, and confidence.”
            </blockquote>
            <cite>— Dr. Suvidha Reddy</cite>
        </div>
    </div>
</section>

<section class="page-section page-section--white">
    <div class="page-wrap split">
        <div class="split__copy">
            <span class="page-label">In Her Own Words</span>
            <h2 class="page-title">A personal path to dermatology</h2>
            <div class="story-stack" style="margin-top:1.5rem;">
                <p class="page-text">
                    My path into dermatology was never just academic — it was deeply personal. Growing up, I watched those around me struggle quietly with skin concerns they were told to ignore, hide, or simply “live with.”
                </p>
                <p class="page-text">
                    Medical school sharpened my clinical instincts, but it was the patients who truly taught me. Every consultation revealed a story — one of frustration, self-consciousness, and the quiet hope of feeling comfortable again.
                </p>
                <p class="page-text">
                    At Skin Origins, I have built a practice rooted in that belief: that care must be as much emotional as it is clinical. I take time. I listen before I prescribe. And I stay present through every step of the journey.
                </p>
            </div>
        </div>
        <div class="split__media">
            <img
                src="<?= so_e(so_asset('images/doctor/ChatGPT-Image-Jun-21-2026-08_00_34-PM.jpg')) ?>"
                alt="Dr. Suvidha Reddy"
                width="800"
                height="1000"
                loading="lazy"
            >
        </div>
    </div>
</section>

<section class="cta-band">
    <div class="page-wrap">
        <h2>Ready to meet Dr. Suvidha?</h2>
        <p>Book a thoughtful consultation and start with a plan designed around you.</p>
        <a class="btn btn-primary" href="<?= so_e(so_url('/contact.php')) ?>">Request Your Consultation</a>
    </div>
</section>

<?php
require SO_INCLUDES . '/footer.php';
