<?php
declare(strict_types=1);
?>
<section class="about" id="about">
    <div class="about__grid">
        <div class="about__media">
            <img
                src="<?= so_e(so_asset('images/home/SOG_Dr-Pic-Horizantal.jpg')) ?>"
                alt="who-we-are-image"
                width="1080"
                height="608"
                loading="lazy"
            >
        </div>
        <div class="about__copy">
            <span class="about__label">Who We Are</span>
            <h2 class="about__title">A Sanctuary for your Skin</h2>
            <p class="about__text">
                We believe in a holistic approach to aesthetics. Combining state-of-the-art technology with traditional wellness practices, we curate personalized treatments that deliver natural, lasting results.
            </p>
            <a class="about__link" href="<?= so_e(so_url('/about.php')) ?>">Read Our Story →</a>
        </div>
    </div>
</section>
