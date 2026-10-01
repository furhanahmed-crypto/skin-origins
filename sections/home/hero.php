<?php
declare(strict_types=1);
$hero = require SO_INCLUDES . '/data/home/hero.php';
?>
<section class="hero" id="hero" aria-label="Hero">
    <div class="hero__slides" data-hero-slides>
        <?php foreach ($hero['slides'] as $i => $slide): ?>
            <div
                class="hero__slide<?= $i === 0 ? ' is-active' : '' ?>"
                style="background-image:url('<?= so_e(so_asset($slide)) ?>')"
                role="img"
                aria-label="Clinic treatment slide <?= $i + 1 ?>"
            ></div>
        <?php endforeach; ?>
        <div class="hero__overlay" aria-hidden="true"></div>
    </div>

    <div class="hero__content">
        <span class="hero__eyebrow"><?= so_e($hero['eyebrow']) ?></span>
        <h1 class="hero__title"><?= so_e($hero['title']) ?></h1>
        <p class="hero__text"><?= so_e($hero['text']) ?></p>
        <div class="hero__ctas">
            <a class="btn btn-primary" href="<?= so_e($hero['primary']['href']) ?>"><?= so_e($hero['primary']['label']) ?></a>
            <a class="btn btn-outline" href="<?= so_e(so_url('/contact.php')) ?>"><?= so_e($hero['secondary']['label']) ?></a>
        </div>
    </div>

    <div class="hero__nav">
        <button class="hero__arrow" type="button" data-hero-prev aria-label="Previous slide">‹</button>
        <button class="hero__arrow" type="button" data-hero-next aria-label="Next slide">›</button>
    </div>

    <div class="hero__dots" data-hero-dots>
        <?php foreach ($hero['slides'] as $i => $_): ?>
            <button class="hero__dot<?= $i === 0 ? ' is-active' : '' ?>" type="button" data-hero-dot="<?= $i ?>" aria-label="Go to slide <?= $i + 1 ?>"></button>
        <?php endforeach; ?>
    </div>
</section>
