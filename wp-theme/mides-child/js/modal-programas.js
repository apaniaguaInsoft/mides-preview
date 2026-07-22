(function () {
  'use strict';

  var dataScript = document.getElementById('programa-data');
  var overlay    = document.getElementById('modal-overlay');
  if ( ! dataScript || ! overlay ) return;

  var data     = JSON.parse( dataScript.textContent || dataScript.innerHTML || '{}' );
  var closeBtn = document.getElementById('modal-close');

  function openModal( key ) {
    var p = data[ key ];
    if ( ! p ) return;

    document.getElementById('modal-label').textContent = p.label;
    document.getElementById('modal-title').textContent = p.title;
    document.getElementById('modal-icon').innerHTML    = p.icon ? '<img src="' + p.icon + '" alt="' + p.title + '" />' : '';

    var html = '<p class="modal__desc">' + p.desc + '</p>';

    if ( p.chips && p.chips.length ) {
      html += '<div class="modal__chips">' + p.chips.map( function (c) {
        return '<span class="modal__chip">' + c + '</span>';
      }).join('') + '</div>';
    }

    if ( p.sections && p.sections.length ) {
      html += '<div class="modal__sections">';
      p.sections.forEach( function (s) {
        html += '<div class="modal__section">';
        html += '<p class="modal__section-title">' + s.titulo + '</p>';
        if ( s.items && s.items.length ) {
          html += '<ul>' + s.items.map( function (item) {
            return '<li>' + item + '</li>';
          }).join('') + '</ul>';
        }
        html += '</div>';
      });
      html += '</div>';
    }

    if ( p.page ) {
      html += '<div class="modal__footer"><a href="' + p.page + '" target="_blank" class="modal__mas-info">Más información <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg></a></div>';
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

  document.querySelectorAll('.card-programa[data-modal]').forEach( function (card) {
    card.style.cursor = 'pointer';
    card.addEventListener('click', function () { openModal( card.dataset.modal ); });
  });

  closeBtn.addEventListener('click', closeModal);
  overlay.addEventListener('click', function (e) { if ( e.target === overlay ) closeModal(); });
  document.addEventListener('keydown', function (e) { if ( e.key === 'Escape' ) closeModal(); });
})();
