<?php
declare(strict_types=1);
$extraJs = $extraJs ?? [];
?>
    </main>

    <footer class="site-footer" role="contentinfo">
        <div class="footer__top">
            <div class="footer__brand">
                <img class="footer__logo" src="<?= so_e(so_asset('images/logo/logo.svg')) ?>" alt="<?= so_e(SO_SITE['name']) ?>">
                <p>Where clinical expertise meets aesthetic artistry. We specialize in transformative skincare tailored to your unique biological blueprint.</p>
                <div class="footer__social">
                    <a href="<?= so_e(SO_SITE['facebook']) ?>" aria-label="Facebook">f</a>
                    <a href="<?= so_e(SO_SITE['instagram']) ?>" target="_blank" rel="noopener" aria-label="Instagram">ig</a>
                    <a href="<?= so_e(SO_SITE['youtube']) ?>" aria-label="YouTube">yt</a>
                </div>
            </div>

            <div class="footer__col">
                <h2>Services</h2>
                <ul>
                    <?php foreach (SO_FOOTER_SERVICES as $link): ?>
                        <li><a href="<?= so_e(so_url($link['path'])) ?>"><?= so_e($link['label']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="footer__col">
                <h2>Quick Links</h2>
                <ul>
                    <?php foreach (SO_FOOTER_LINKS as $link): ?>
                        <li><a href="<?= so_e(so_url($link['path'])) ?>"><?= so_e($link['label']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="footer__col">
                <h2>The Clinic</h2>
                <ul>
                    <li><a href="tel:<?= so_e(SO_SITE['phone_tel']) ?>"><?= so_e(SO_SITE['phone']) ?></a></li>
                    <li><a href="mailto:<?= so_e(SO_SITE['email']) ?>"><?= so_e(SO_SITE['email']) ?></a></li>
                    <li><?= so_e(SO_SITE['address']) ?></li>
                </ul>
            </div>
        </div>

        <div class="footer__bottom">
            &copy; <?= so_e(SO_SITE['year']) ?> <?= so_e(strtoupper(SO_SITE['name'])) ?>. ALL RIGHTS RESERVED.
        </div>
    </footer>

    <?php so_partial('fab.php'); ?>
    <?php so_partial('consult-modal.php'); ?>
</div>

<script src="<?= so_e(so_asset('js/main.js')) ?>" defer></script>
<script src="<?= so_e(so_asset('js/home.js')) ?>" defer></script>
<?php foreach ($extraJs as $js): ?>
    <script src="<?= so_e(so_asset($js)) ?>" defer></script>
<?php endforeach; ?>
</body>
</html>
