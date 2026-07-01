// Hamburger menu toggle
const hamburger = document.getElementById('hamburger');
const navBar    = document.querySelector('.header__nav-bar');
if (hamburger && navBar) {
  hamburger.addEventListener('click', () => {
    const open = navBar.classList.toggle('nav-bar--open');
    hamburger.setAttribute('aria-expanded', open);
    hamburger.classList.toggle('hamburger--open', open);
  });
  navBar.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => {
      navBar.classList.remove('nav-bar--open');
      hamburger.setAttribute('aria-expanded', false);
      hamburger.classList.remove('hamburger--open');
    });
  });
}

// Modal — programas sociales
(function () {
  const overlay   = document.getElementById('modal-overlay');
  const closeBtn  = document.getElementById('modal-close');
  const dataEl    = document.getElementById('programa-data');
  if (!overlay || !closeBtn || !dataEl) return;
  const data      = JSON.parse(dataEl.textContent);

  function openModal(key) {
    const p = data[key];
    if (!p) return;

    document.getElementById('modal-label').textContent  = p.label;
    document.getElementById('modal-title').textContent  = p.title;
    document.getElementById('modal-icon').innerHTML     = `<img src="${p.icon}" alt="${p.title}" />`;

    let html = `<p class="modal__desc">${p.desc}</p>`;

    if (p.chips && p.chips.length) {
      html += `<div class="modal__chips">${p.chips.map(c => `<span class="modal__chip">${c}</span>`).join('')}</div>`;
    }

    if (p.sections.length) {
      html += `<div class="modal__sections">`;
      p.sections.forEach(s => {
        html += `<div class="modal__section">
          <p class="modal__section-title">${s.titulo}</p>
          <ul>${s.items.map(i => `<li>${i}</li>`).join('')}</ul>
        </div>`;
      });
      html += `</div>`;
    }

    if (p.page) {
      html += `<div class="modal__footer"><a href="${p.page}" target="_blank" class="modal__mas-info">Más información <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg></a></div>`;
    }

    document.getElementById('modal-body').innerHTML = html;

    overlay.setAttribute('aria-hidden', 'false');
    overlay.classList.add('modal--open');
    document.body.style.overflow = 'hidden';
  }

  function closeModal() {
    overlay.classList.remove('modal--open');
    overlay.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }

  document.querySelectorAll('.card-programa[data-modal]').forEach(card => {
    card.style.cursor = 'pointer';
    card.addEventListener('click', () => openModal(card.dataset.modal));
  });

  closeBtn.addEventListener('click', closeModal);
  overlay.addEventListener('click', e => { if (e.target === overlay) closeModal(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
})();

// Flip cards — nosotros (click en toda la card)
document.querySelectorAll('.card-nosotros').forEach(card => {
  card.addEventListener('click', () => card.classList.toggle('flipped'));
});

// Hero carousel dots interaction
document.querySelectorAll('.hero__dot').forEach((dot, i) => {
  dot.addEventListener('click', () => {
    document.querySelectorAll('.hero__dot').forEach(d => d.classList.remove('active'));
    dot.classList.add('active');
  });
});

// Noticias carousel
(function () {
  const cards = document.querySelectorAll('.noticia-card');
  const dots  = document.querySelectorAll('.noticias__dot');
  const prev  = document.querySelector('.noticias__arrow--prev');
  const next  = document.querySelector('.noticias__arrow--next');
  if (!cards.length) return;

  let current = 0;
  let animating = false;

  function goTo(index, direction) {
    if (animating || index === current) return;
    animating = true;

    const outCard = cards[current];
    const nextIndex = (index + cards.length) % cards.length;

    outCard.style.transform = direction === 'next' ? 'translateX(-24px)' : 'translateX(24px)';
    outCard.style.opacity = '0';

    dots[current].classList.remove('active');
    current = nextIndex;
    dots[current].classList.add('active');

    const inCard = cards[current];
    inCard.style.transition = 'none';
    inCard.style.transform = direction === 'next' ? 'translateX(24px)' : 'translateX(-24px)';
    inCard.style.opacity = '0';
    inCard.classList.add('active');

    requestAnimationFrame(() => {
      requestAnimationFrame(() => {
        inCard.style.transition = '';
        inCard.style.transform = 'translateX(0)';
        inCard.style.opacity = '1';
      });
    });

    setTimeout(() => {
      outCard.classList.remove('active');
      outCard.style.transform = '';
      outCard.style.opacity = '';
      animating = false;
    }, 420);
  }

  prev && prev.addEventListener('click', () => goTo(current - 1, 'prev'));
  next && next.addEventListener('click', () => goTo(current + 1, 'next'));
  dots.forEach((dot, i) => dot.addEventListener('click', () => goTo(i, i > current ? 'next' : 'prev')));
})();
