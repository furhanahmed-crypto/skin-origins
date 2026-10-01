<?php
declare(strict_types=1);
$ig = require SO_INCLUDES . '/data/home/instagram.php';
?>
<section class="instagram" id="instagram">
    <div class="section-heading">
        <div class="ig-title">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <rect x="2" y="2" width="20" height="20" rx="5" ry="5" stroke="#c2185b" stroke-width="2"/>
                <circle cx="12" cy="12" r="4.5" stroke="#c2185b" stroke-width="2"/>
                <circle cx="17.5" cy="6.5" r="1.3" fill="#c2185b"/>
            </svg>
            Follow Us on Instagram
        </div>
        <div class="subtitle">Check out our latest treatments, transformations, and skincare tips</div>
    </div>

    <div class="feed-grid" id="feedGrid">
        <?php foreach ($ig['posts'] as $post): ?>
            <article class="post" data-ig-post data-video="<?= so_e(so_asset($post['video'])) ?>">
                <div class="post-header">
                    <div class="post-header-left">
                        <div class="avatar-img" style="outline-color:<?= so_e($post['color']) ?>;background:<?= so_e($post['color']) ?>">
                            <?= so_e($post['label']) ?>
                        </div>
                        <div class="post-usernames">
                            <div class="username-line"><?= so_e($post['user']) ?></div>
                            <div class="audio-line">Original audio</div>
                        </div>
                    </div>
                    <a class="view-profile-btn" href="<?= so_e($ig['profile_url']) ?>" target="_blank" rel="noopener">View Profile</a>
                </div>

                <div class="post-media" data-ig-media>
                    <img class="thumb-bg" src="<?= so_e(so_asset($post['poster'])) ?>" alt="thumbnail" loading="lazy">
                    <div class="play-btn" aria-hidden="true">
                        <svg viewBox="0 0 24 24"><polygon points="6,3 20,12 6,21" fill="rgba(255,255,255,0.92)"/></svg>
                    </div>
                </div>

                <div class="post-footer">
                    <div class="view-more-wrap">
                        <a class="view-more" href="<?= so_e($ig['profile_url']) ?>" target="_blank" rel="noopener">View more on Instagram</a>
                    </div>
                    <div class="action-row">
                        <div class="action-left">
                            <span class="action-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                            </span>
                            <span class="action-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                            </span>
                        </div>
                    </div>
                    <div class="likes"><?= (int) $post['likes'] ?> likes</div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>

    <div class="follow-wrap">
        <button class="follow-btn" type="button" onclick="window.open('<?= so_e($ig['profile_url']) ?>','_blank')">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
            </svg>
            Follow @skinorigins on Instagram
        </button>
    </div>
</section>
