<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

$so_page = 'contact';
$pageTitle = 'Contact Us';
$pageDescription = 'Get in touch with Skin Origins Clinic, Jubilee Hills, Hyderabad. Call +91 90006 00177 or send us a message.';

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
            <form class="contact-form" method="post" action="<?= so_e(so_url('/thank-you.php')) ?>">
                <h2>Let's Start a Conversation</h2>
                <p>Fill out the form below and our team will get back to you within 24 hours.</p>

                <div class="form-row form-row--2">
                    <div class="form-field">
                        <label for="name">Full Name</label>
                        <input id="name" name="name" type="text" autocomplete="name" required placeholder="Your full name">
                    </div>
                    <div class="form-field">
                        <label for="email">Email Address</label>
                        <input id="email" name="email" type="email" autocomplete="email" required placeholder="you@email.com">
                    </div>
                </div>

                <div class="form-row form-row--2">
                    <div class="form-field">
                        <label for="phone">Phone Number</label>
                        <input id="phone" name="phone" type="tel" autocomplete="tel" required placeholder="+91 XXXXX XXXXX">
                    </div>
                    <div class="form-field">
                        <label for="service">Service Interest</label>
                        <select id="service" name="service">
                            <option value="" selected>Select a service</option>
                            <option value="Skin">Skin</option>
                            <option value="Hair">Hair</option>
                            <option value="Wellness">Wellness</option>
                            <option value="General">General Consultation</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-field">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" required placeholder="Tell us about your inquiry..."></textarea>
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
