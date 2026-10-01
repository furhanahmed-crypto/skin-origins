<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

$so_page = 'about';
$pageTitle = 'About';
$pageDescription = 'The Heart of Skin Origins — Where Science Meets Empathy. Learn our story and sanctuary approach to aesthetic care in Jubilee Hills, Hyderabad.';

require SO_INCLUDES . '/header.php';
?>

<section class="page-hero page-hero--solid" aria-label="About Skin Origins">
    <div class="page-hero__inner">
        <span class="page-hero__label">The Heart of Skin Origins</span>
        <h1 class="page-hero__title">Where Science<br>Meets <em>Empathy</em></h1>
        <p class="page-hero__lead">
            Some journeys into dermatology begin with textbooks.<br>
            <strong>Skin Origins began with lived experience.</strong>
        </p>
        <p class="page-hero__text">
            Dr. Suvidha’s early relationship with her own appearance shaped a belief that true aesthetic care is as much about emotional reassurance as it is about clinical expertise.
        </p>
    </div>
</section>

<section class="page-section page-section--white">
    <div class="page-wrap split">
        <div class="split__media">
            <img
                src="<?= so_e(so_asset('images/about/drsuvidha.webp')) ?>"
                alt="Dr. Suvidha Reddy"
                width="800"
                height="1000"
                loading="lazy"
            >
        </div>
        <div class="split__copy">
            <span class="page-label">Our Story</span>
            <h2 class="page-title">Lived experience, clinical precision</h2>
            <p class="split__highlight">Our focus is not on quick fixes, but on understanding the root cause.</p>
            <p class="page-text">
                At Skin Origins, every treatment begins with time, conversation, and care—because no two individuals, no two concerns, and no two stories are the same. Dr. Suvidha believes in being present before, during, and long after the procedure.
            </p>
        </div>
    </div>
</section>

<section class="page-section page-section--cream">
    <div class="page-wrap">
        <span class="page-label">How We Care</span>
        <h2 class="page-title">A practice built on presence</h2>
        <p class="page-text">
            Care at Skin Origins is designed to feel personal from the first consultation through recovery and follow-up.
        </p>
        <div class="value-grid">
            <article class="value-card">
                <h3>Personalized</h3>
                <p>Tailored to your unique story and lifestyle.</p>
            </article>
            <article class="value-card">
                <h3>Persistent</h3>
                <p>Support that continues long after your visit.</p>
            </article>
        </div>
    </div>
</section>

<section class="page-section page-section--white">
    <div class="page-wrap split split--media-right">
        <div class="split__media split__media--landscape">
            <img
                src="<?= so_e(so_asset('images/about/relaxingspa.webp')) ?>"
                alt="The Skin Origins sanctuary space"
                width="1000"
                height="750"
                loading="lazy"
            >
        </div>
        <div class="split__copy">
            <span class="page-label">The Sanctuary</span>
            <h2 class="page-title">Airy, Calm &amp; Welcoming</h2>
            <p class="page-text">
                Designed to feel like a retreat, Skin Origins is filled with open spaces and lush plants. It is a place where you can breathe, relax, and feel safe.
            </p>
            <ul class="check-list">
                <li>Not Clinical. Not Rushed.</li>
                <li>Comfort and Trust First.</li>
            </ul>
            <p class="page-text">
                Here, skincare, hair care, and aesthetic treatments are friendly, transparent, and thoughtful. Questions are encouraged. Concerns are respected.
            </p>
        </div>
    </div>
</section>

<section class="cta-band">
    <div class="page-wrap">
        <h2>Step out feeling confident, comfortable, and at home in yourself again.</h2>
        <p>Experience thoughtful dermatology and aesthetic care in Jubilee Hills.</p>
        <a class="btn btn-primary" href="<?= so_e(so_url('/contact.php')) ?>">Experience Skin Origins</a>
    </div>
</section>

<?php
require SO_INCLUDES . '/footer.php';
