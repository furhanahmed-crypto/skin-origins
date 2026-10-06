/**
 * Skin Origins — Service category pages (Skin / Hair / Wellness)
 * Full page navigation keeps per-treatment SEO meta/H1/FAQs intact.
 */
(function () {
  'use strict';

  function qs(sel, root) {
    return (root || document).querySelector(sel);
  }
  function qsa(sel, root) {
    return Array.prototype.slice.call((root || document).querySelectorAll(sel));
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

  function initSeoExpand() {
    var btn = qs('[data-seo-expand]');
    var more = qs('[data-seo-more]');
    if (!btn || !more) return;

    var label = qs('[data-seo-expand-label]', btn);

    btn.addEventListener('click', function () {
      var open = btn.getAttribute('aria-expanded') === 'true';
      var next = !open;

      if (label) {
        label.textContent = next ? 'Show less' : 'Read more';
      }

      btn.setAttribute('aria-expanded', next ? 'true' : 'false');
      btn.classList.toggle('is-open', next);
      more.setAttribute('aria-hidden', next ? 'false' : 'true');

      if (next) {
        // Expand: animate height open (content is above the control).
        more.classList.remove('is-collapsing');
        more.classList.add('is-open');
        return;
      }

      // Collapse: instant height close + one scroll correction in the same frame
      // so the button stays under the user's eye (no transition/scroll fighting).
      var anchorTop = btn.getBoundingClientRect().top;
      more.classList.add('is-collapsing');
      more.classList.remove('is-open');

      // Force layout so scroll correction uses the collapsed geometry.
      void more.offsetHeight;

      var delta = btn.getBoundingClientRect().top - anchorTop;
      if (Math.abs(delta) > 0.5) {
        window.scrollBy(0, delta);
      }

      // Re-enable expand animation on the next frame.
      requestAnimationFrame(function () {
        more.classList.remove('is-collapsing');
      });
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    initFaq();
    initSeoExpand();
  });
})();
