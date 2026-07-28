(function () {
  const section = document.querySelector('.hero');
  if (!section) return;

  const slides = section.querySelectorAll('.hero__slide');
  const dots   = section.querySelectorAll('.hero__dot');
  const prev   = section.querySelector('.hero__arrow--prev');
  const next   = section.querySelector('.hero__arrow--next');

  if (slides.length <= 1) {
    if (prev) prev.style.display = 'none';
    if (next) next.style.display = 'none';
    return;
  }

  const intervalMs = (parseInt(section.dataset.interval, 10) || 5) * 1000;
  let current = 0;
  let timer;

  function goTo(n) {
    slides[current].classList.remove('active');
    dots[current].classList.remove('active');
    current = (n + slides.length) % slides.length;
    slides[current].classList.add('active');
    dots[current].classList.add('active');
  }

  function startAuto() {
    timer = setInterval(() => goTo(current + 1), intervalMs);
  }

  function resetAuto() {
    clearInterval(timer);
    startAuto();
  }

  if (prev) prev.addEventListener('click', () => { goTo(current - 1); resetAuto(); });
  if (next) next.addEventListener('click', () => { goTo(current + 1); resetAuto(); });
  dots.forEach((dot, i) => dot.addEventListener('click', () => { goTo(i); resetAuto(); }));

  /* ── Swipe táctil ── */
  let touchStartX = 0;
  let touchStartY = 0;

  section.addEventListener('touchstart', function (e) {
    touchStartX = e.changedTouches[0].clientX;
    touchStartY = e.changedTouches[0].clientY;
  }, { passive: true });

  section.addEventListener('touchend', function (e) {
    const dx = e.changedTouches[0].clientX - touchStartX;
    const dy = e.changedTouches[0].clientY - touchStartY;

    // Solo actuar si el movimiento horizontal supera 50px y es mayor que el vertical
    if (Math.abs(dx) < 50 || Math.abs(dx) < Math.abs(dy)) return;

    if (dx < 0) {
      goTo(current + 1); // swipe izquierda → siguiente
    } else {
      goTo(current - 1); // swipe derecha → anterior
    }
    resetAuto();
  }, { passive: true });

  startAuto();
})();
