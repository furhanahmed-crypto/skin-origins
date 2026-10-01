/**
 * Skin Origins — Global JS
 */
(function () {
  'use strict';

  document.documentElement.classList.add('js');

  window.SkinOrigins = window.SkinOrigins || {
    version: '0.1.0-phase1',
    init: function () {
      initConsultModal();
    },
  };

  var CONSULT_STORAGE_KEY = 'so_consult_modal_dismissed';
  var CONSULT_DELAY_MS = 5000;

  function initConsultModal() {
    var modal = document.getElementById('consultModal');
    var form = document.getElementById('consultModalForm');
    if (!modal || !form) return;

    try {
      if (sessionStorage.getItem(CONSULT_STORAGE_KEY) === '1') {
        return;
      }
    } catch (err) {
      // sessionStorage unavailable — still show once this page load
    }

    var closeButtons = modal.querySelectorAll('[data-consult-close]');
    var openTimer = null;
    var previouslyFocused = null;

    function markDismissed() {
      try {
        sessionStorage.setItem(CONSULT_STORAGE_KEY, '1');
      } catch (err) {
        // ignore quota / private mode failures
      }
    }

    function openModal() {
      previouslyFocused = document.activeElement;
      modal.hidden = false;
      modal.setAttribute('aria-hidden', 'false');
      document.body.classList.add('consult-modal-open');
      var firstInput = form.querySelector('input');
      if (firstInput) firstInput.focus();
    }

    function closeModal() {
      modal.hidden = true;
      modal.setAttribute('aria-hidden', 'true');
      document.body.classList.remove('consult-modal-open');
      markDismissed();
      if (previouslyFocused && typeof previouslyFocused.focus === 'function') {
        previouslyFocused.focus();
      }
    }

    closeButtons.forEach(function (btn) {
      btn.addEventListener('click', function () {
        closeModal();
      });
    });

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && !modal.hidden) {
        closeModal();
      }
    });

    form.addEventListener('submit', function () {
      markDismissed();
    });

    openTimer = window.setTimeout(openModal, CONSULT_DELAY_MS);

    window.addEventListener('pagehide', function () {
      if (openTimer) window.clearTimeout(openTimer);
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    window.SkinOrigins.init();
  });
})();
