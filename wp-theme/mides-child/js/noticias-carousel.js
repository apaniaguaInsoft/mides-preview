document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.noticias').forEach(function (section) {
    var cards = Array.prototype.slice.call(section.querySelectorAll('.noticia-card'));
    var dots  = Array.prototype.slice.call(section.querySelectorAll('.noticias__dot'));
    var prev  = section.querySelector('.noticias__arrow--prev');
    var next  = section.querySelector('.noticias__arrow--next');

    if (cards.length <= 1) {
      if (prev) prev.style.display = 'none';
      if (next) next.style.display = 'none';
      return;
    }

    var current   = 0;
    var animating = false;
    var autoTimer = null;

    function activate(index) {
      cards[current].classList.remove('active');
      dots[current] && dots[current].classList.remove('active');
      current = (index + cards.length) % cards.length;
      cards[current].classList.add('active');
      dots[current] && dots[current].classList.add('active');
    }

    function goTo(index, direction) {
      if (animating || index === current) return;
      animating = true;
      resetAuto();

      var outCard   = cards[current];
      var nextIndex = (index + cards.length) % cards.length;
      var inCard    = cards[nextIndex];

      /* Salida del card actual */
      outCard.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
      outCard.style.transform  = direction === 'next' ? 'translateX(-24px)' : 'translateX(24px)';
      outCard.style.opacity    = '0';

      /* Entrada del nuevo card */
      inCard.style.transition = 'none';
      inCard.style.transform  = direction === 'next' ? 'translateX(24px)' : 'translateX(-24px)';
      inCard.style.opacity    = '0';
      inCard.classList.add('active');

      dots[current] && dots[current].classList.remove('active');
      current = nextIndex;
      dots[current] && dots[current].classList.add('active');

      requestAnimationFrame(function () {
        requestAnimationFrame(function () {
          inCard.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
          inCard.style.transform  = 'translateX(0)';
          inCard.style.opacity    = '1';
        });
      });

      setTimeout(function () {
        outCard.classList.remove('active');
        outCard.style.transition = '';
        outCard.style.transform  = '';
        outCard.style.opacity    = '';
        inCard.style.transition  = '';
        animating = false;
      }, 450);
    }

    function resetAuto() {
      clearInterval(autoTimer);
      autoTimer = setInterval(function () {
        goTo(current + 1, 'next');
      }, 5000);
    }

    if (prev) prev.addEventListener('click', function () { goTo(current - 1, 'prev'); });
    if (next) next.addEventListener('click', function () { goTo(current + 1, 'next'); });

    dots.forEach(function (dot, i) {
      dot.addEventListener('click', function () {
        goTo(i, i > current ? 'next' : 'prev');
      });
    });

    resetAuto();
  });
});
