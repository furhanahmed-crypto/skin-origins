<?php
declare(strict_types=1);
$why = require SO_INCLUDES . '/data/home/why-us.php';
?>
<section class="why" id="why-us">
    <div class="why__head">
        <span class="section-label"><?= so_e($why['label']) ?></span>
        <h2 class="section-title"><?= nl2br(so_e($why['title'])) ?></h2>
    </div>
    <div class="why__grid">
        <?php foreach ($why['items'] as $item): ?>
            <article class="why__card">
                <img src="<?= so_e(so_asset($item['icon'])) ?>" alt="" width="56" height="56" loading="lazy">
                <p><?= so_e($item['label']) ?></p>
            </article>
        <?php endforeach; ?>
    </div>
</section>
