<?php
declare(strict_types=1);
$treatments = require SO_INCLUDES . '/data/services/treatments.php';
?>
<section class="treatment-wrapper services-section" id="services">
    <div class="tabs" role="tablist" aria-label="Service categories">
        <button type="button" class="active" data-tab="skin" role="tab" aria-selected="true">Skin</button>
        <button type="button" data-tab="hair" role="tab" aria-selected="false">Hair</button>
        <button type="button" data-tab="wellness" role="tab" aria-selected="false">Wellness</button>
    </div>

    <div id="gridView" aria-live="polite"></div>

    <div id="detailView">
        <a href="#" id="backBtn">← BACK TO ALL</a>
        <div class="detail-layout">
            <div class="detail-left">
                <img id="detailImg" src="" alt="">
            </div>
            <div class="detail-right">
                <h2 id="detailTitle"></h2>
                <p id="detailDesc"></p>
            </div>
        </div>
        <div class="more-treatments">
            <h4 id="moreHeading">More Treatments</h4>
            <div id="moreGrid"></div>
        </div>
    </div>
</section>

<script>
window.SO_TREATMENTS = <?= json_encode($treatments, JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) ?>;
window.SO_ASSET_BASE = <?= json_encode(SO_ASSETS_URL, JSON_UNESCAPED_SLASHES) ?>;
</script>
