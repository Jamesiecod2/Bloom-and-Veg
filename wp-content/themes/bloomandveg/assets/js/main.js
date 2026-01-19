(function () {
  const toggle = document.querySelector('.menu-toggle');
  if (toggle) {
    toggle.addEventListener('click', function () {
      const isOpen = document.body.classList.toggle('nav-open');
      toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });
  }

  const miniCartRoot = document.querySelector('[data-bv-mini-cart]');

  function closeMiniCart() {
    if (!miniCartRoot) return;
    const panel = miniCartRoot.querySelector('#bv-mini-cart-panel');
    if (!panel) return;
    panel.hidden = true;
    miniCartRoot.classList.remove('is-open');
    const openButton = miniCartRoot.querySelector('.bv-cart-toggle');
    if (openButton) openButton.setAttribute('aria-expanded', 'false');
  }

  if (miniCartRoot) {
    const panel = miniCartRoot.querySelector('#bv-mini-cart-panel');
    const openButton = miniCartRoot.querySelector('.bv-cart-toggle');
    const closeButton = miniCartRoot.querySelector('.bv-mini-cart-close');
    const cartLink = miniCartRoot.querySelector('.bv-cart-link');

    if (panel) {
      function setOpen(nextOpen) {
        panel.hidden = !nextOpen;
        if (openButton) {
          openButton.setAttribute('aria-expanded', nextOpen ? 'true' : 'false');
        }
        miniCartRoot.classList.toggle('is-open', nextOpen);
      }

      function toggleOpen() {
        setOpen(panel.hidden);
      }

      // Desktop: allow opening the dropdown without navigating away.
      if (cartLink) {
        cartLink.addEventListener('click', function (e) {
          // If user holds modifiers, respect default behavior.
          if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
          e.preventDefault();
          toggleOpen();
        });
      }

      // Optional explicit toggle button (currently visually hidden via CSS).
      if (openButton) openButton.addEventListener('click', toggleOpen);
      if (closeButton) closeButton.addEventListener('click', function () { setOpen(false); });

      document.addEventListener('click', function (e) {
        if (panel.hidden) return;
        if (!miniCartRoot.contains(e.target)) setOpen(false);
      });

      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') setOpen(false);
      });
    }
  }

  const backToTop = document.querySelector('.bv-back-to-top');
  if (backToTop) {
    function updateBackToTop() {
      const shouldShow = window.scrollY > 400;
      backToTop.hidden = !shouldShow;
    }

    updateBackToTop();
    window.addEventListener('scroll', updateBackToTop, { passive: true });
    backToTop.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }
})();
