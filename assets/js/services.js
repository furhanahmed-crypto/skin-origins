/**
 * Skin Origins — Service category pages (Skin / Hair / Wellness)
 */
(function () {
  'use strict';

  function qs(sel, root) {
    return (root || document).querySelector(sel);
  }
  function qsa(sel, root) {
    return Array.prototype.slice.call((root || document).querySelectorAll(sel));
  }

  function asset(path, base) {
    if (!path) return '';
    if (path.indexOf('http') === 0 || path.charAt(0) === '/') return path;
    return String(base || '/assets').replace(/\/$/, '') + '/' + path.replace(/^\//, '');
  }

  function initFaq() {
    var list = qs('[data-faq-list]');
    if (!list) return;

    function setOpen(item, open) {
      var btn = qs('.faq-question', item);
      if (!btn) return;
      item.classList.toggle('is-open', open);
      btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    }

    qsa('.faq-item', list).forEach(function (item) {
      var btn = qs('.faq-question', item);
      if (!btn) return;

      btn.addEventListener('click', function () {
        var willOpen = !item.classList.contains('is-open');

        qsa('.faq-item', list).forEach(function (other) {
          setOpen(other, false);
        });

        if (willOpen) setOpen(item, true);
      });
    });
  }

  function initServiceTreatments() {
    var cfg = window.SO_SERVICE;
    var root = qs('[data-service-page]');
    if (!cfg || !root) return;

    var type = cfg.type;
    var treatments = cfg.treatments || [];
    var base = cfg.assetBase || '/assets';
    var wrapper = qs('#treatment-wrapper-' + type, root) || qs('.treatment-wrapper', root);
    if (!wrapper) return;

    var gridView = qs('#gridView', wrapper);
    var detailView = qs('#detailView', wrapper);
    if (!gridView || !detailView) return;

    function categoryUrl() {
      return '/' + type + '/';
    }

    function treatmentUrl(slug) {
      return '/' + type + '/' + slug + '/';
    }

    function renderMore(activeIndex) {
      var moreGrid = qs('#moreGrid', wrapper);
      var moreHeading = qs('#moreHeading', wrapper);
      if (!moreGrid) return;
      if (moreHeading) moreHeading.textContent = cfg.moreLabel || 'More Treatments';
      moreGrid.innerHTML = '';

      treatments.forEach(function (t, i) {
        if (i === activeIndex) return;
        var a = document.createElement('a');
        a.className = 'more-item';
        a.href = treatmentUrl(t.slug);
        a.setAttribute('data-index', String(i));
        a.setAttribute('data-slug', t.slug || '');
        a.innerHTML =
          '<img src="' + asset(t.image, base) + '" alt="' + t.title + '" width="96" height="96" loading="lazy" decoding="async">' +
          '<span>' + t.title + '</span>';
        a.addEventListener('click', function (e) {
          e.preventDefault();
          showDetail(i, true);
        });
        moreGrid.appendChild(a);
      });
    }

    function showGrid(pushHistory) {
      gridView.classList.remove('is-hidden');
      detailView.classList.remove('is-visible');
      document.title = cfg.categoryTitle || document.title;
      if (pushHistory) {
        history.pushState({ type: type, page: 'grid' }, '', categoryUrl());
      }
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function showDetail(index, pushHistory) {
      var item = treatments[index];
      if (!item) return;

      gridView.classList.add('is-hidden');
      detailView.classList.add('is-visible');

      var img = qs('#detailImg', wrapper);
      var title = qs('#detailTitle', wrapper);
      var desc = qs('#detailDesc', wrapper);
      if (img) {
        img.src = asset(item.image, base);
        img.alt = item.title;
      }
      if (title) title.textContent = item.title;
      if (desc) desc.textContent = item.desc;

      renderMore(index);
      document.title = item.title + ' - Skin Origins Clinic';

      if (pushHistory) {
        history.pushState({ type: type, slug: item.slug }, '', treatmentUrl(item.slug));
      }
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    qsa('.treatment-item', gridView).forEach(function (card) {
      card.addEventListener('click', function (e) {
        e.preventDefault();
        var idx = parseInt(card.getAttribute('data-index') || '-1', 10);
        if (idx >= 0) showDetail(idx, true);
      });
    });

    var backBtn = qs('#backBtn', wrapper);
    if (backBtn) {
      backBtn.addEventListener('click', function (e) {
        e.preventDefault();
        showGrid(true);
      });
    }

    window.addEventListener('popstate', function () {
      var parts = window.location.pathname.split('/').filter(Boolean);
      var slug = parts[1] || '';
      var idx = treatments.findIndex(function (t) { return t.slug === slug; });
      if (idx >= 0) showDetail(idx, false);
      else showGrid(false);
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    initFaq();
    initServiceTreatments();
  });
})();
