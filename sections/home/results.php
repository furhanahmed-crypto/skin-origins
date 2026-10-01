<?php
declare(strict_types=1);
$results = require SO_INCLUDES . '/data/home/results.php';
?>
<section class="results" id="results">
    <div class="results__head">
        <h2 class="section-title"><?= so_e($results['title']) ?></h2>
        <p class="section-text"><?= so_e($results['text']) ?></p>
    </div>

    <div class="results__grid">
        <?php foreach ($results['cases'] as $case): ?>
            <div class="ba" data-ba style="--pos:50%">
                <div class="ba__after">
                    <img src="<?= so_e(so_asset($case['after'])) ?>" alt="After" loading="lazy">
                </div>
                <div class="ba__before" style="width:50%">
                    <img src="<?= so_e(so_asset($case['before'])) ?>" alt="Before" loading="lazy">
                </div>
                <span class="ba__label ba__label--before">Before</span>
                <span class="ba__label ba__label--after">After</span>
                <div class="ba__handle" style="left:50%"><span>⟷</span></div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
