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

    var statusEl = document.getElementById('consultModalStatus');
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

    function setStatus(message, type) {
      if (!statusEl) return;
      if (!message) {
        statusEl.hidden = true;
        statusEl.textContent = '';
        statusEl.classList.remove('is-error', 'is-success');
        return;
      }
      statusEl.hidden = false;
      statusEl.textContent = message;
      statusEl.classList.remove('is-error', 'is-success');
      if (type) statusEl.classList.add(type);
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
      setStatus('');
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

    form.addEventListener('submit', function (event) {
      event.preventDefault();
      setStatus('');

      var submitBtn = form.querySelector('.consult-modal__submit');
      if (submitBtn) submitBtn.disabled = true;

      var body = new FormData(form);

      fetch(form.action, {
        method: 'POST',
        body: body,
        headers: { Accept: 'application/json' },
      })
        .then(function (res) {
          return res.json().then(function (data) {
            return { ok: res.ok && data && data.ok, data: data };
          });
        })
        .then(function (result) {
          if (!result.ok) {
            setStatus(
              (result.data && result.data.message) || 'Something went wrong. Please try again.',
              'is-error'
            );
            return;
          }
          setStatus(
            (result.data && result.data.message) || 'Thank you. Our team will get back to you shortly.',
            'is-success'
          );
          form.reset();
          markDismissed();
          window.setTimeout(closeModal, 1600);
        })
        .catch(function () {
          setStatus('Unable to send right now. Please call us or try again.', 'is-error');
        })
        .finally(function () {
          if (submitBtn) submitBtn.disabled = false;
        });
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
