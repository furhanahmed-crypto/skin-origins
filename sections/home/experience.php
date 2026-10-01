<?php
declare(strict_types=1);
$gallery = require SO_INCLUDES . '/data/home/gallery.php';
// Duplicate for seamless infinite marquee
$loop = array_merge($gallery, $gallery);
?>
<section class="experience" id="experience">
    <div class="experience__head">
        <span class="section-label">Our Clinic</span>
        <h2 class="section-title">Experience Excellence</h2>
    </div>

    <div class="review-slider" aria-label="Clinic experience showcase">
        <div class="review-track">
            <?php foreach ($loop as $item): ?>
                <article class="review-card">
                    <img src="<?= so_e(so_asset($item['image'])) ?>" alt="<?= so_e($item['title']) ?>" loading="lazy">
                    <div class="overlay">
                        <h3><?= so_e($item['title']) ?></h3>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
