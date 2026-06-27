/* TEGRACH NIGERIA LIMITED — shared scripts (demo5) */
(function () {
  var prefersReducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // Year
  var yr = document.getElementById('yr');
  if (yr) yr.textContent = new Date().getFullYear();

  // Nav scroll state
  var nav = document.getElementById('nav');
  if (nav) {
    var navTicking = false;
    var syncNavState = function () {
      nav.classList.toggle('scrolled', window.scrollY > 20);
      navTicking = false;
    };

    syncNavState();
    addEventListener('scroll', function () {
      if (navTicking) return;
      navTicking = true;
      requestAnimationFrame(syncNavState);
    }, { passive: true });
  }

  // Mobile drawer
  var burger = document.getElementById('burger');
  var drawer = document.getElementById('drawer');
  var overlay = document.getElementById('overlay');
  var drawerClose = document.getElementById('drawerClose');
  if (burger && drawer && overlay) {
    var openMenu = function () { drawer.classList.add('open'); overlay.classList.add('show'); document.body.classList.add('lock'); };
    var closeMenu = function () { drawer.classList.remove('open'); overlay.classList.remove('show'); document.body.classList.remove('lock'); };
    burger.addEventListener('click', openMenu);
    if (drawerClose) drawerClose.addEventListener('click', closeMenu);
    overlay.addEventListener('click', closeMenu);
    addEventListener('keydown', function (e) { if (e.key === 'Escape') closeMenu(); });
    drawer.querySelectorAll('a').forEach(function (a) { a.addEventListener('click', closeMenu); });
  }

  // Reveal on scroll
  var reveals = document.querySelectorAll('.reveal');
  if (prefersReducedMotion) {
    reveals.forEach(function (el) { el.classList.add('in'); });
  } else if (reveals.length) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (!e.isIntersecting) return;
        e.target.classList.add('in');
        io.unobserve(e.target);
      });
    }, { threshold: .14 });
    reveals.forEach(function (el) { io.observe(el); });
  }

  // Count up
  var counted = new WeakSet();
  var cio = new IntersectionObserver(function (entries) {
    entries.forEach(function (e) {
      if (!e.isIntersecting) return;
      e.target.querySelectorAll('[data-count]').forEach(function (el) {
        if (counted.has(el)) return; counted.add(el);
        var target = +el.dataset.count, dur = 1300, t0 = performance.now();
        if (prefersReducedMotion) {
          el.textContent = target;
          return;
        }
        var tick = function (t) {
          var p = Math.min((t - t0) / dur, 1);
          var val = Math.floor((1 - Math.pow(1 - p, 3)) * target);
          el.textContent = val;
          if (p < 1) requestAnimationFrame(tick); else el.textContent = target;
        };
        requestAnimationFrame(tick);
      });
    });
  }, { threshold: .5 });
  document.querySelectorAll('.stat, .counter').forEach(function (s) { cio.observe(s); });

  // Project filtering
  var filterbar = document.querySelector('.filterbar');
  if (filterbar) {
    filterbar.addEventListener('click', function (e) {
      var btn = e.target.closest('button');
      if (!btn) return;
      filterbar.querySelectorAll('button').forEach(function (b) { b.classList.remove('active'); });
      btn.classList.add('active');
      var f = btn.dataset.filter;
      document.querySelectorAll('.proj[data-cat]').forEach(function (p) {
        var show = f === 'all' || p.dataset.cat === f;
        p.classList.toggle('hide', !show);
      });
    });
  }

  // Contact form: light client-side validation, then submit via fetch to the
  // Cloudflare Pages Function at /contact, which sends the email via Resend.
  var contactForm = document.querySelector('.form form');
  var statusBox = document.getElementById('formStatus');
  if (contactForm) {
    var v = function (id) { var el = document.getElementById(id); return el ? (el.value || '').trim() : ''; };
    var markBad = function (id) {
      var el = document.getElementById(id);
      if (!el) return;
      el.style.borderColor = '#ff4d4d';
      el.addEventListener('input', function once() { el.style.borderColor = ''; el.removeEventListener('input', once); });
    };
    var showStatus = function (kind, msg) {
      if (!statusBox) return;
      statusBox.className = 'form-status form-status--' + (kind === 'ok' ? 'ok' : 'err');
      statusBox.innerHTML = (kind === 'ok' ? '<strong>Enquiry received.</strong> ' : '') + msg;
      statusBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
    };

    contactForm.addEventListener('submit', function (e) {
      e.preventDefault();
      var name = v('f-name'), email = v('f-email');
      var emailOk = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
      if (!name || !emailOk) {
        if (!name) markBad('f-name');
        if (!emailOk) markBad('f-email');
        (!name ? document.getElementById('f-name') : document.getElementById('f-email')).focus();
        return;
      }

      var btn = document.getElementById('submit');
      var btnHTML = btn ? btn.innerHTML : '';
      if (btn) { btn.disabled = true; btn.innerHTML = '<span>Sending…</span>'; }

      var payload = {};
      new FormData(contactForm).forEach(function (val, key) { payload[key] = val; });

      fetch(contactForm.getAttribute('action') || '/api/contact', {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      })
        .then(function (r) { return r.json().catch(function () { return { ok: r.ok }; }); })
        .then(function (data) {
          if (data && data.ok) {
            showStatus('ok', 'Thank you — our team will respond within one working day.');
            contactForm.reset();
          } else {
            showStatus('err', (data && data.error) || 'Something went wrong. Please try again.');
          }
        })
        .catch(function () {
          showStatus('err', 'Could not send right now. Please try again or email contact@tegrach-nigeria.com.');
        })
        .finally(function () {
          if (btn) { btn.disabled = false; btn.innerHTML = btnHTML; }
          // Turnstile tokens are single-use — reset for any retry.
          if (window.turnstile) { try { window.turnstile.reset(); } catch (e) {} }
        });
    });
  }
})();
