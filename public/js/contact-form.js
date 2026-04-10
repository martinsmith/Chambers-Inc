/**
 * contact-form.js
 * Lightweight vanilla-JS handler for the Chambers Inc contact forms.
 * Works with both the dedicated contact page form and the popup form
 * present on every page.
 */
(function () {
  'use strict';

  const ENDPOINT = '/contact/send.php';

  function showMessage(form, success, text) {
    let output = form.querySelector('.cf-response');
    if (!output) {
      output = document.createElement('p');
      output.className = 'cf-response';
      form.appendChild(output);
    }
    output.textContent = text;
    output.style.cssText = success
      ? 'color:#3a7d3a;margin-top:10px;font-weight:600;'
      : 'color:#b94040;margin-top:10px;font-weight:600;';
  }

  function setLoading(form, loading) {
    const btn = form.querySelector('[type="submit"]');
    if (!btn) return;
    btn.disabled = loading;
    btn.value = loading ? 'Sending…' : 'Send';
  }

  function handleSubmit(e) {
    e.preventDefault();
    const form = e.currentTarget;

    // Clear any previous message
    const prev = form.querySelector('.cf-response');
    if (prev) prev.remove();

    setLoading(form, true);

    fetch(ENDPOINT, {
      method: 'POST',
      body: new FormData(form),
    })
      .then(function (res) {
        return res.json();
      })
      .then(function (data) {
        setLoading(form, false);
        showMessage(form, data.success, data.message);
        if (data.success) {
          form.reset();
        }
      })
      .catch(function () {
        setLoading(form, false);
        showMessage(
          form,
          false,
          'Sorry, something went wrong. Please try again or call us directly.'
        );
      });
  }

  function init() {
    document.querySelectorAll('form.contact-form').forEach(function (form) {
      form.addEventListener('submit', handleSubmit);
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
