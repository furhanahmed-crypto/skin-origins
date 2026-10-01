<?php
/**
 * Shared Skin / Hair / Wellness service page renderer.
 * Expects $serviceType in: skin | hair | wellness
 */

declare(strict_types=1);

require_once __DIR__ . '/functions.php';

$serviceType = $serviceType ?? 'skin';
$servicePages = require SO_INCLUDES . '/data/services/pages.php';
$allTreatments = require SO_INCLUDES . '/data/services/treatments.php';

if (!isset($servicePages[$serviceType], $allTreatments[$serviceType])) {
    http_response_code(404);
    echo 'Service not found.';
    exit;
}

$page = $servicePages[$serviceType];
$treatments = $allTreatments[$serviceType];

// Resolve treatment slug from pretty URL: /skin/chemical-peeling/
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
$pathParts = array_values(array_filter(explode('/', (string) $requestPath), static fn($p) => $p !== ''));
$treatmentSlug = $pathParts[1] ?? '';

$activeTreatment = null;
$activeIndex = null;
if ($treatmentSlug !== '') {
    foreach ($treatments as $i => $item) {
        if (($item['slug'] ?? '') === $treatmentSlug) {
            $activeTreatment = $item;
            $activeIndex = $i;
            break;
        }
    }
    if ($activeTreatment === null) {
        http_response_code(404);
        $pageTitle = 'Not Found';
        $pageDescription = 'Treatment not found.';
        $so_page = $serviceType;
        require SO_INCLUDES . '/header.php';
        echo '<section class="page-section page-section--cream"><div class="page-wrap"><h1 class="page-title">Treatment not found</h1><p class="page-text"><a href="' . so_e(so_url('/' . $serviceType . '/')) . '">Back to ' . so_e($page['label']) . ' treatments</a></p></div></section>';
        require SO_INCLUDES . '/footer.php';
        exit;
    }
}

$so_page = $serviceType;
$extraJs = ['js/services.js'];

if ($activeTreatment) {
    $pageTitle = $activeTreatment['title'];
    $pageDescription = substr($activeTreatment['desc'], 0, 155);
    $documentTitle = $activeTreatment['title'] . ' - Skin Origins Clinic';
} else {
    $pageTitle = $page['page_title'];
    $pageDescription = $page['description'];
    $documentTitle = $page['document_title'];
}

// Override default so_page_title formatting for SEO-faithful titles
$so_document_title = $documentTitle;

$whyItems = [
    [
        'title' => 'Expert Care',
        'text'  => 'Led by Dr. Suvidha Reddy with specialized experience',
        'icon'  => 'images/services/icons/why-01.png',
    ],
    [
        'title' => 'Advanced Technology',
        'text'  => 'FDA-approved devices and techniques',
        'icon'  => 'images/services/icons/why-02.png',
    ],
    [
        'title' => 'Personalized Treatment',
        'text'  => 'Customized plans for your concerns',
        'icon'  => 'images/services/icons/why-03.png',
    ],
    [
        'title' => 'Safe & Effective',
        'text'  => 'Treatments for Indian skin types',
        'icon'  => 'images/services/icons/why-04.png',
    ],
];

$faqSchema = [
    '@context'   => 'https://schema.org',
    '@type'      => 'FAQPage',
    'mainEntity' => array_map(static function (array $faq): array {
        return [
            '@type' => 'Question',
            'name'  => $faq['q'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text'  => $faq['a'],
            ],
        ];
    }, $page['faqs']),
];

require SO_INCLUDES . '/header.php';
?>

<script type="application/ld+json"><?= json_encode($faqSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) ?></script>

<section class="service-page" data-service-page data-service-type="<?= so_e($serviceType) ?>" aria-label="<?= so_e($page['label']) ?> treatments">
    <div class="service-page__tabs-wrap">
        <nav class="service-tabs tabs" aria-label="Service categories">
            <?php foreach (['skin' => 'Skin', 'hair' => 'Hair', 'wellness' => 'Wellness'] as $key => $label): ?>
                <a
                    href="<?= so_e(so_url('/' . $key . '/')) ?>"
                    class="<?= $key === $serviceType ? 'active' : '' ?>"
                    <?= $key === $serviceType ? 'aria-current="page"' : '' ?>
                ><?= so_e($label) ?></a>
            <?php endforeach; ?>
        </nav>
    </div>

    <div class="service-page__treatments treatment-wrapper" id="treatment-wrapper-<?= so_e($serviceType) ?>">
        <div id="gridView" class="service-grid<?= $activeTreatment ? ' is-hidden' : '' ?>" aria-live="polite">
            <?php foreach ($treatments as $index => $item): ?>
                <a
                    class="treatment-item"
                    href="<?= so_e(so_url('/' . $serviceType . '/' . ($item['slug'] ?? '') . '/')) ?>"
                    data-index="<?= (int) $index ?>"
                    data-slug="<?= so_e($item['slug'] ?? '') ?>"
                >
                    <img
                        src="<?= so_e(so_asset($item['image'])) ?>"
                        alt="<?= so_e($item['title']) ?>"
                        width="140"
                        height="140"
                        loading="<?= $index < 6 ? 'eager' : 'lazy' ?>"
                        decoding="async"
                    >
                    <h3><?= so_e($item['title']) ?></h3>
                </a>
            <?php endforeach; ?>
        </div>

        <div id="detailView" class="service-detail<?= $activeTreatment ? ' is-visible' : '' ?>">
            <a href="<?= so_e(so_url('/' . $serviceType . '/')) ?>" id="backBtn" class="service-detail__back">← Back to all</a>
            <div class="detail-layout">
                <div class="detail-left">
                    <img
                        id="detailImg"
                        src="<?= so_e($activeTreatment ? so_asset($activeTreatment['image']) : '') ?>"
                        alt="<?= so_e($activeTreatment['title'] ?? '') ?>"
                        width="520"
                        height="520"
                    >
                </div>
                <div class="detail-right">
                    <h1 id="detailTitle" class="service-detail__title"><?= so_e($activeTreatment['title'] ?? '') ?></h1>
                    <p id="detailDesc" class="service-detail__desc"><?= so_e($activeTreatment['desc'] ?? '') ?></p>
                    <a class="btn btn-primary" href="<?= so_e(so_url('/contact.php')) ?>">Book Consultation</a>
                </div>
            </div>
            <div class="more-treatments">
                <h2 id="moreHeading" class="more-treatments__title"><?= so_e($page['more_label']) ?></h2>
                <div id="moreGrid" class="more-grid">
                    <?php if ($activeTreatment): ?>
                        <?php foreach ($treatments as $i => $item): ?>
                            <?php if ($i === $activeIndex) {
                                continue;
                            } ?>
                            <a
                                class="more-item"
                                href="<?= so_e(so_url('/' . $serviceType . '/' . ($item['slug'] ?? '') . '/')) ?>"
                                data-index="<?= (int) $i ?>"
                                data-slug="<?= so_e($item['slug'] ?? '') ?>"
                            >
                                <img src="<?= so_e(so_asset($item['image'])) ?>" alt="<?= so_e($item['title']) ?>" width="96" height="96" loading="lazy" decoding="async">
                                <span><?= so_e($item['title']) ?></span>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="page-section page-section--cream why-service">
    <div class="page-wrap">
        <div class="why-service__intro">
            <span class="page-label">The Skin Origins Difference</span>
            <h2 class="page-title">Why Choose Skin Origins</h2>
        </div>
        <div class="why-service__grid">
            <?php foreach ($whyItems as $item): ?>
                <article class="why-service__card">
                    <div class="why-service__icon">
                        <img src="<?= so_e(so_asset($item['icon'])) ?>" alt="" width="56" height="56" loading="lazy">
                    </div>
                    <h3><?= so_e($item['title']) ?></h3>
                    <p><?= so_e($item['text']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="page-section page-section--white service-faq" aria-labelledby="faqHeading">
    <div class="page-wrap page-wrap--narrow">
        <div class="service-faq__intro">
            <span class="page-label">FAQs</span>
            <h2 class="page-title" id="faqHeading">Frequently Asked Questions</h2>
        </div>
        <div class="faq-list" data-faq-list>
            <?php foreach ($page['faqs'] as $i => $faq): ?>
                <div class="faq-item<?= $i === 0 ? ' is-open' : '' ?>">
                    <button
                        class="faq-question"
                        type="button"
                        aria-expanded="<?= $i === 0 ? 'true' : 'false' ?>"
                        id="faq-q-<?= (int) $i ?>"
                        aria-controls="faq-a-<?= (int) $i ?>"
                    >
                        <span class="faq-question__text"><?= so_e($faq['q']) ?></span>
                        <span class="faq-question__icon" aria-hidden="true"></span>
                    </button>
                    <div
                        class="faq-answer"
                        id="faq-a-<?= (int) $i ?>"
                        role="region"
                        aria-labelledby="faq-q-<?= (int) $i ?>"
                    >
                        <div class="faq-answer__inner">
                            <p><?= so_e($faq['a']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="cta-band service-cta">
    <div class="page-wrap">
        <h2><?= so_e($page['cta']['title']) ?></h2>
        <p><?= so_e($page['cta']['text']) ?></p>
        <div class="service-cta__actions">
            <a class="btn btn-primary" href="<?= so_e(so_url('/contact.php')) ?>">Book Consultation</a>
            <a class="btn btn-outline" href="tel:<?= so_e(SO_SITE['phone_tel']) ?>"><?= so_e(SO_SITE['phone']) ?></a>
        </div>
    </div>
</section>

<script>
window.SO_SERVICE = {
    type: <?= json_encode($serviceType, JSON_UNESCAPED_SLASHES) ?>,
    categoryTitle: <?= json_encode($page['document_title'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>,
    moreLabel: <?= json_encode($page['more_label'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>,
    treatments: <?= json_encode($treatments, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) ?>,
    assetBase: <?= json_encode(SO_ASSETS_URL, JSON_UNESCAPED_SLASHES) ?>,
    initialSlug: <?= json_encode($treatmentSlug, JSON_UNESCAPED_SLASHES) ?>
};
</script>

<?php
require SO_INCLUDES . '/footer.php';
