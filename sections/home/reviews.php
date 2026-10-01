<?php
declare(strict_types=1);
$reviews = require SO_INCLUDES . '/data/home/reviews.php';
?>
<section class="reviews-section" id="reviews" aria-label="Google reviews">
    <div class="reviews-wrapper">
        <div class="reviews-header">
            <div class="g-logo">
                <svg width="144" height="45" viewBox="0 0 80 32" xmlns="http://www.w3.org/2000/svg" aria-label="Google">
                    <text x="0" y="22" font-family="Arial" font-size="24" font-weight="bold">
                        <tspan fill="#4285F4">G</tspan><tspan fill="#EA4335">o</tspan><tspan fill="#FBBC05">o</tspan><tspan fill="#4285F4">g</tspan><tspan fill="#34A853">l</tspan><tspan fill="#EA4335">e</tspan>
                    </text>
                </svg>
                <div class="g-text">
                    <div class="g-title">Reviews</div>
                    <div class="stars-row">
                        <span class="rating-num"><?= so_e((string) $reviews['rating']) ?></span>
                        <span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span>
                        <span class="count">(<?= (int) $reviews['count'] ?>)</span>
                    </div>
                </div>
            </div>
            <a class="review-btn" href="<?= so_e($reviews['review_url']) ?>" target="_blank" rel="noopener">Review us on Google ↗</a>
        </div>

        <div class="carousel-outer">
            <button class="nav-btn nav-prev hidden" type="button" id="prevBtn" aria-label="Previous reviews">‹</button>
            <div class="carousel-overflow">
                <div class="cards-track" id="track">
                    <?php foreach ($reviews['items'] as $item): ?>
                        <article class="card">
                            <div class="card-header">
                                <div class="avatar" style="background:<?= so_e($item['color']) ?>">
                                    <?= so_e($item['initial']) ?>
                                    <div class="g-badge"><span>G</span></div>
                                </div>
                                <div class="user-info">
                                    <div class="user-name"><?= so_e($item['name']) ?></div>
                                    <div class="user-time"><?= so_e($item['time']) ?></div>
                                </div>
                            </div>
                            <div class="card-stars">
                                <span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span>
                            </div>
                            <div class="card-text"><?= so_e($item['text']) ?></div>
                            <a class="read-more" href="<?= so_e($reviews['review_url']) ?>" target="_blank" rel="noopener">Read more</a>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
            <button class="nav-btn nav-next" type="button" id="nextBtn" aria-label="Next reviews">›</button>
        </div>

        <div class="see-all">
            <a href="<?= so_e($reviews['review_url']) ?>" target="_blank" rel="noopener">See all reviews on Google ↗</a>
        </div>
    </div>
</section>
