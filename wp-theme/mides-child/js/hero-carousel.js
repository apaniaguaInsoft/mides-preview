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

  startAuto();
})();
