/**
 * Skin Origins — Homepage interactions
 */
(function () {
  'use strict';

  function qs(sel, root) { return (root || document).querySelector(sel); }
  function qsa(sel, root) { return Array.prototype.slice.call((root || document).querySelectorAll(sel)); }

  /* ── Mobile nav ── */
  function initNav() {
    var header = qs('#siteHeader');
    var toggle = qs('[data-nav-toggle]');
    if (!header || !toggle) return;
    toggle.addEventListener('click', function () {
      var open = header.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }

  /* ── Hero slider ── */
  function initHero() {
    var root = qs('#hero');
    if (!root) return;
    var slides = qsa('.hero__slide', root);
    var dots = qsa('[data-hero-dot]', root);
    var i = 0;
    var timer;

    function go(n) {
      i = (n + slides.length) % slides.length;
      slides.forEach(function (s, idx) { s.classList.toggle('is-active', idx === i); });
      dots.forEach(function (d, idx) { d.classList.toggle('is-active', idx === i); });
    }

    function next() { go(i + 1); }
    function prev() { go(i - 1); }

    function start() {
      stop();
      timer = setInterval(next, 5500);
    }
    function stop() {
      if (timer) clearInterval(timer);
    }

    var nextBtn = qs('[data-hero-next]', root);
    var prevBtn = qs('[data-hero-prev]', root);
    if (nextBtn) nextBtn.addEventListener('click', function () { next(); start(); });
    if (prevBtn) prevBtn.addEventListener('click', function () { prev(); start(); });
    dots.forEach(function (d) {
      d.addEventListener('click', function () {
        go(parseInt(d.getAttribute('data-hero-dot'), 10));
        start();
      });
    });

    root.addEventListener('mouseenter', stop);
    root.addEventListener('mouseleave', start);
    start();
  }

  /* ── Services tabs ── */
  function initServices() {
    var treatments = window.SO_TREATMENTS;
    var base = window.SO_ASSET_BASE || '/assets';
    if (!treatments) return;

    var gridView = qs('#gridView');
    var detailView = qs('#detailView');
    var currentTab = 'skin';
    if (!gridView || !detailView) return;

    function asset(path) {
      if (!path) return '';
      if (path.indexOf('http') === 0 || path.charAt(0) === '/') return path;
      return base.replace(/\/$/, '') + '/' + path.replace(/^\//, '');
    }

    function renderGrid(tab) {
      currentTab = tab;
      gridView.style.display = 'flex';
      detailView.style.display = 'none';
      gridView.innerHTML = '';
      (treatments[tab] || []).forEach(function (item, index) {
        var el = document.createElement('div');
        el.className = 'treatment-item';
        el.innerHTML =
          '<img decoding="async" src="' + asset(item.image) + '" alt="' + item.title + '">' +
          '<h3>' + item.title + '</h3>';
        el.addEventListener('click', function () { showDetail(tab, index); });
        gridView.appendChild(el);
      });
    }

    function showDetail(tab, index) {
      var item = treatments[tab][index];
      gridView.style.display = 'none';
      detailView.style.display = 'block';
      qs('#detailImg').src = asset(item.image);
      qs('#detailImg').alt = item.title;
      qs('#detailTitle').textContent = item.title;
      qs('#detailDesc').textContent = item.desc;
      qs('#moreHeading').textContent = 'More ' + tab + ' Treatments';

      var moreGrid = qs('#moreGrid');
      moreGrid.innerHTML = '';
      treatments[tab].forEach(function (t, i) {
        if (i === index) return;
        var el = document.createElement('div');
        el.className = 'more-item';
        el.innerHTML =
          '<img decoding="async" src="' + asset(t.image) + '" alt="' + t.title + '">' +
          '<span>' + t.title + '</span>';
        el.addEventListener('click', function () { showDetail(tab, i); });
        moreGrid.appendChild(el);
      });
    }

    qsa('.tabs button').forEach(function (btn) {
      btn.addEventListener('click', function () {
        qsa('.tabs button').forEach(function (b) {
          b.classList.remove('active');
          b.setAttribute('aria-selected', 'false');
        });
        btn.classList.add('active');
        btn.setAttribute('aria-selected', 'true');
        renderGrid(btn.getAttribute('data-tab'));
      });
    });

    var backBtn = qs('#backBtn');
    if (backBtn) {
      backBtn.addEventListener('click', function (e) {
        e.preventDefault();
        renderGrid(currentTab);
      });
    }

    renderGrid('skin');
  }

  /* ── Before / After sliders ── */
  function initBeforeAfter() {
    qsa('[data-ba]').forEach(function (ba) {
      var before = qs('.ba__before', ba);
      var handle = qs('.ba__handle', ba);
      var dragging = false;

      function syncWidth() {
        ba.style.setProperty('--ba-width', ba.offsetWidth + 'px');
      }
      syncWidth();
      window.addEventListener('resize', syncWidth);

      function setPos(clientX) {
        var rect = ba.getBoundingClientRect();
        var pct = ((clientX - rect.left) / rect.width) * 100;
        pct = Math.max(5, Math.min(95, pct));
        before.style.width = pct + '%';
        handle.style.left = pct + '%';
      }

      function onDown(e) {
        dragging = true;
        ba.classList.add('is-dragging');
        setPos(e.touches ? e.touches[0].clientX : e.clientX);
        e.preventDefault();
      }
      function onMove(e) {
        if (!dragging) return;
        setPos(e.touches ? e.touches[0].clientX : e.clientX);
      }
      function onUp() {
        dragging = false;
        ba.classList.remove('is-dragging');
      }

      ba.addEventListener('mousedown', onDown);
      ba.addEventListener('touchstart', onDown, { passive: false });
      window.addEventListener('mousemove', onMove);
      window.addEventListener('touchmove', onMove, { passive: false });
      window.addEventListener('mouseup', onUp);
      window.addEventListener('touchend', onUp);
    });
  }

  /* ── Google reviews carousel ── */
  function initReviews() {
    var track = qs('#track');
    var prevBtn = qs('#prevBtn');
    var nextBtn = qs('#nextBtn');
    if (!track || !prevBtn || !nextBtn) return;

    var current = 0;
    var gap = 14;

    function visibleCount() {
      var w = window.innerWidth;
      if (w <= 576) return 1;
      if (w <= 768) return 2;
      if (w <= 1200) return 3;
      return 5;
    }

    function cardWidth() {
      var parent = track.parentElement;
      var v = visibleCount();
      return parent.offsetWidth / v - (gap * (v - 1)) / v;
    }

    function maxIndex() {
      return Math.max(0, track.children.length - visibleCount());
    }

    function update() {
      var step = cardWidth() + gap;
      track.style.transform = 'translateX(-' + current * step + 'px)';
      prevBtn.classList.toggle('hidden', current <= 0);
      nextBtn.classList.toggle('hidden', current >= maxIndex());
    }

    prevBtn.addEventListener('click', function () {
      current = Math.max(0, current - 1);
      update();
    });
    nextBtn.addEventListener('click', function () {
      current = Math.min(maxIndex(), current + 1);
      update();
    });
    window.addEventListener('resize', function () {
      current = Math.min(current, maxIndex());
      update();
    });
    update();
  }

  /* ── Instagram local video playback ── */
  function initInstagram() {
    qsa('[data-ig-post]').forEach(function (post) {
      var media = qs('[data-ig-media]', post);
      var videoSrc = post.getAttribute('data-video');
      if (!media || !videoSrc) return;

      media.addEventListener('click', function () {
        var existing = media.querySelector('video');
        if (existing) {
          if (existing.paused) existing.play();
          else existing.pause();
          return;
        }
        var thumb = qs('.thumb-bg', media);
        var playBtn = qs('.play-btn', media);
        var video = document.createElement('video');
        video.src = videoSrc;
        video.setAttribute('playsinline', '');
        video.muted = true;
        video.loop = true;
        video.controls = true;
        video.autoplay = true;
        video.style.cssText = 'position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center;z-index:1;background:#111;';
        if (thumb) thumb.style.display = 'none';
        if (playBtn) playBtn.style.display = 'none';
        media.appendChild(video);
        video.play().catch(function () {});
      });
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    initNav();
    initHero();
    initServices();
    initBeforeAfter();
    initReviews();
    initInstagram();
  });
})();
