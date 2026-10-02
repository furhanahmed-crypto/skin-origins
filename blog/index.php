<?php
/**
 * Blog detail — /blog/{slug}/
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/blog.php';

$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
$parts = array_values(array_filter(explode('/', (string) $requestPath), static fn($p) => $p !== ''));
$slug = $parts[1] ?? '';

if ($slug === '') {
    header('Location: ' . so_blog_url(), true, 301);
    exit;
}

$post = so_blog_post($slug);
if ($post === null) {
    http_response_code(404);
    $so_page = 'blog';
    $pageTitle = 'Not Found';
    $pageDescription = 'Blog post not found.';
    require SO_INCLUDES . '/header.php';
    echo '<section class="page-section page-section--cream"><div class="page-wrap"><h1 class="page-title">Article not found</h1><p class="page-text"><a href="' . so_e(so_blog_url()) . '">Back to all articles</a></p></div></section>';
    require SO_INCLUDES . '/footer.php';
    exit;
}

$so_page = 'blog';
$pageTitle = $post['h1'];
$so_document_title = $post['meta_title'];
$pageDescription = $post['meta_description'];

$allPosts = so_blog_posts();
$otherPosts = array_values(array_filter($allPosts, static fn(array $p): bool => $p['slug'] !== $slug));

$faqSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => array_map(static function (array $faq): array {
        return [
            '@type' => 'Question',
            'name' => $faq['q'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => $faq['a'],
            ],
        ];
    }, $post['faqs'] ?? []),
];

$blogSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'BlogPosting',
    'headline' => $post['h1'],
    'description' => $post['meta_description'],
    'datePublished' => $post['published'],
    'image' => so_asset($post['image']),
    'author' => [
        '@type' => 'Person',
        'name' => $post['author'],
    ],
    'reviewedBy' => [
        '@type' => 'Physician',
        'name' => $post['author'],
    ],
    'publisher' => [
        '@type' => 'MedicalBusiness',
        'name' => 'Skin Origins',
    ],
    'mainEntityOfPage' => so_blog_url($slug),
];

$extraJs = ['js/services.js']; // FAQ accordion reuse

require SO_INCLUDES . '/header.php';
?>

<script type="application/ld+json"><?= json_encode($blogSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) ?></script>
<?php if (($post['faqs'] ?? []) !== []): ?>
<script type="application/ld+json"><?= json_encode($faqSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) ?></script>
<?php endif; ?>

<article class="blog-article">
    <header class="blog-article__hero">
        <div class="page-wrap blog-article__hero-inner">
            <span class="blog-article__label"><?= so_e($post['primary_keyword'] ?? 'Dermatology') ?></span>
            <h1 class="blog-article__title"><?= so_e($post['h1']) ?></h1>
            <div class="blog-article__byline">
                <span>Medically reviewed by <?= so_e($post['author']) ?>, <?= so_e($post['author_credentials']) ?></span>
                <span aria-hidden="true">·</span>
                <time datetime="<?= so_e($post['published']) ?>"><?= so_e(date('F j, Y', strtotime((string) $post['published']))) ?></time>
            </div>
        </div>
    </header>

    <div class="blog-article__feature">
        <div class="page-wrap">
            <img
                src="<?= so_e(so_asset($post['image'])) ?>"
                alt="<?= so_e($post['image_alt'] ?? $post['h1']) ?>"
                width="1200"
                height="640"
                loading="eager"
            >
        </div>
    </div>

    <div class="page-section page-section--white">
        <div class="page-wrap blog-article__layout">
            <div class="blog-article__content">
                <?php so_blog_render_blocks($post['blocks'] ?? []); ?>

                <?php if (($post['faqs'] ?? []) !== []): ?>
                    <section class="blog-article__faqs service-faq" aria-labelledby="blogFaqHeading">
                        <h2 id="blogFaqHeading">Frequently Asked Questions</h2>
                        <div class="faq-list" data-faq-list>
                            <?php foreach ($post['faqs'] as $i => $faq): ?>
                                <div class="faq-item<?= $i === 0 ? ' is-open' : '' ?>">
                                    <button
                                        class="faq-question"
                                        type="button"
                                        aria-expanded="<?= $i === 0 ? 'true' : 'false' ?>"
                                        id="blog-faq-q-<?= (int) $i ?>"
                                        aria-controls="blog-faq-a-<?= (int) $i ?>"
                                    >
                                        <span class="faq-question__text"><?= so_e($faq['q']) ?></span>
                                        <span class="faq-question__icon" aria-hidden="true"></span>
                                    </button>
                                    <div class="faq-answer" id="blog-faq-a-<?= (int) $i ?>" role="region" aria-labelledby="blog-faq-q-<?= (int) $i ?>">
                                        <div class="faq-answer__inner">
                                            <p><?= so_blog_rich_text((string) ($faq['a'] ?? ''), is_array($faq['links'] ?? null) ? $faq['links'] : []) ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <aside class="blog-article__disclaimer">
                    <p><?= so_e($post['disclaimer'] ?? '') ?></p>
                </aside>
            </div>

            <aside class="blog-article__aside">
                <div class="blog-aside-card blog-aside-card--consult">
                    <h2>Book a consultation</h2>
                    <p>Discuss suitability with Dr. Suvidha Reddy at Skin Origins, Jubilee Hills.</p>
                    <a class="btn btn-primary" href="<?= so_e(so_url('/contact.php')) ?>">Book Consultation</a>
                    <a class="btn btn-outline-dark" href="https://wa.me/<?= so_e(SO_SITE['whatsapp']) ?>" target="_blank" rel="noopener">WhatsApp</a>
                </div>
            </aside>
        </div>
    </div>

    <?php if ($otherPosts !== []): ?>
        <section class="page-section page-section--cream">
            <div class="page-wrap">
                <div class="blog-related__head">
                    <span class="page-label">Keep reading</span>
                    <h2 class="page-title">More articles</h2>
                </div>
                <div class="blog-grid blog-grid--related">
                    <?php foreach ($otherPosts as $item): ?>
                        <article class="blog-card">
                            <a class="blog-card__media" href="<?= so_e(so_blog_url($item['slug'])) ?>">
                                <img src="<?= so_e(so_asset($item['image'])) ?>" alt="<?= so_e($item['image_alt'] ?? $item['h1']) ?>" width="640" height="400" loading="lazy">
                            </a>
                            <div class="blog-card__body">
                                <h3 class="blog-card__title">
                                    <a href="<?= so_e(so_blog_url($item['slug'])) ?>"><?= so_e($item['h1']) ?></a>
                                </h3>
                                <a class="blog-card__link" href="<?= so_e(so_blog_url($item['slug'])) ?>">Read more →</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
</article>

<section class="cta-band service-cta">
    <div class="page-wrap">
        <h2>Ready to talk to a dermatologist?</h2>
        <p>Book a consultation at Skin Origins, Jubilee Hills — call <?= so_e(SO_SITE['phone']) ?> or message us on WhatsApp.</p>
        <div class="service-cta__actions">
            <a class="btn btn-primary" href="<?= so_e(so_url('/contact.php')) ?>">Book Consultation</a>
            <a class="btn btn-outline" href="tel:<?= so_e(SO_SITE['phone_tel']) ?>"><?= so_e(SO_SITE['phone']) ?></a>
        </div>
    </div>
</section>

<?php
require SO_INCLUDES . '/footer.php';
