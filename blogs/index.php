<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/blog.php';

$so_page = 'blog';
$pageTitle = 'Blog';
$so_document_title = 'Blog | Skin Origins Clinic, Jubilee Hills Hyderabad';
$pageDescription = 'Dermatologist-written guides on laser hair removal, melasma, acne scars, Cosmelan, HIFU and more — Skin Origins Clinic, Jubilee Hills, Hyderabad.';

$posts = so_blog_posts();

require SO_INCLUDES . '/header.php';
?>

<section class="page-hero page-hero--solid blog-hero" aria-label="Blog">
    <div class="page-hero__inner">
        <span class="page-hero__label">Insights</span>
        <h1 class="page-hero__title">Skin Origins Blog</h1>
        <p class="page-hero__lead">
            Evidence-led guides on treatments for Indian skin — written for patients in Hyderabad and Jubilee Hills.
        </p>
    </div>
</section>

<section class="page-section page-section--cream blog-listing">
    <div class="page-wrap page-wrap--wide">
        <div class="blog-grid">
            <?php foreach ($posts as $post): ?>
                <article class="blog-card">
                    <a class="blog-card__media" href="<?= so_e(so_blog_url($post['slug'])) ?>">
                        <img
                            src="<?= so_e(so_asset($post['image'])) ?>"
                            alt="<?= so_e($post['image_alt'] ?? $post['h1']) ?>"
                            width="640"
                            height="400"
                            loading="lazy"
                        >
                    </a>
                    <div class="blog-card__body">
                        <span class="blog-card__meta"><?= so_e(date('M j, Y', strtotime((string) $post['published']))) ?></span>
                        <h2 class="blog-card__title">
                            <a href="<?= so_e(so_blog_url($post['slug'])) ?>"><?= so_e($post['h1']) ?></a>
                        </h2>
                        <p class="blog-card__excerpt"><?= so_e($post['excerpt']) ?></p>
                        <a class="blog-card__link" href="<?= so_e(so_blog_url($post['slug'])) ?>">Read more →</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php
require SO_INCLUDES . '/footer.php';
