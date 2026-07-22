(function () {
  'use strict';

  /* ── Hamburger / menú móvil ── */
  var hamburger = document.getElementById('hamburger');
  var navBar    = document.querySelector('.header__nav-bar');
  if ( hamburger && navBar ) {
    hamburger.addEventListener('click', function () {
      var open = navBar.classList.toggle('nav-bar--open');
      hamburger.setAttribute('aria-expanded', open);
      hamburger.classList.toggle('hamburger--open', open);
    });

    navBar.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', function () {
        navBar.classList.remove('nav-bar--open');
        hamburger.setAttribute('aria-expanded', 'false');
        hamburger.classList.remove('hamburger--open');
      });
    });
  }

  /* ── Buscador del header ── */
  var searchInput = document.querySelector('.header__search-input');
  var searchBtn   = document.querySelector('.header__search-btn');

  function doSearch() {
    var q = searchInput ? searchInput.value.trim() : '';
    if ( q ) {
      window.location.href = '/?s=' + encodeURIComponent(q);
    } else {
      searchInput && searchInput.focus();
    }
  }

  if ( searchBtn ) {
    searchBtn.addEventListener('click', doSearch);
  }

  if ( searchInput ) {
    searchInput.addEventListener('keydown', function (e) {
      if ( e.key === 'Enter' ) doSearch();
    });
  }
})();
