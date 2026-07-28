<?php
/**
 * Render callback del bloque Documentos Lista.
 */
$max_docs         = (int) ( $attributes['maxDocumentos'] ?? 100 );
$por_pagina       = (int) ( $attributes['porPagina']     ?? 9 );
$mostrar_buscador  = (bool) ( $attributes['mostrarBuscador'] ?? true );
$mostrar_filtros   = (bool) ( $attributes['mostrarFiltros']  ?? true );
$cats_filtradas    = array_map( 'intval', $attributes['categorias'] ?? [] );

$doc_icon_svg = '<svg class="doc-card__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>';

/* ── Consultar documentos ── */
$query_args = [
	'post_type'      => 'documento',
	'post_status'    => 'publish',
	'posts_per_page' => $max_docs,
	'orderby'        => [ 'meta_value_num' => 'DESC', 'date' => 'DESC' ],
	'meta_key'       => '_doc_anio',
	'no_found_rows'  => true,
];
if ( ! empty( $cats_filtradas ) ) {
	$query_args['tax_query'] = [ [
		'taxonomy' => 'categoria_documento',
		'field'    => 'term_id',
		'terms'    => $cats_filtradas,
	] ];
}
$query = new WP_Query( $query_args );
$documentos = $query->posts;
wp_reset_postdata();

/* ── Categorías para los filtros ── */
$terms_args = [
	'taxonomy'   => 'categoria_documento',
	'hide_empty' => false,
	'orderby'    => 'name',
];
if ( ! empty( $cats_filtradas ) ) {
	$terms_args['include'] = $cats_filtradas;
}
$categorias = get_terms( $terms_args );

$instance_id = 'docs-' . wp_unique_id();
?>

<section class="docs-section">
  <div class="container">

    <?php if ( $mostrar_buscador ) : ?>
    <div class="news-search-wrap">
      <div class="news-search">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" id="<?php echo esc_attr( $instance_id ); ?>-search"
               placeholder="Buscar documentos…" class="news-search__input" autocomplete="off" />
        <button class="news-search__clear" id="<?php echo esc_attr( $instance_id ); ?>-clear"
                aria-label="Limpiar búsqueda" style="display:none;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
    </div>
    <?php endif; ?>

    <?php if ( $mostrar_filtros && ! empty( $categorias ) && ! is_wp_error( $categorias ) ) : ?>
    <div class="news-filters" id="<?php echo esc_attr( $instance_id ); ?>-filters">
      <button class="news-filter active" data-filter="todos">Todos</button>
      <?php foreach ( $categorias as $cat ) : ?>
        <button class="news-filter" data-filter="<?php echo esc_attr( $cat->slug ); ?>">
          <?php echo esc_html( $cat->name ); ?>
        </button>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="docs-grid" id="<?php echo esc_attr( $instance_id ); ?>-grid">
      <?php if ( empty( $documentos ) ) : ?>
        <p style="color:#94A3B8;grid-column:1/-1;text-align:center;padding:48px 0">No hay documentos publicados aún.</p>
      <?php else : ?>
        <?php foreach ( $documentos as $doc ) :
          $doc_id    = $doc->ID;
          $titulo_doc = get_the_title( $doc_id );
          $excerpt   = wp_trim_words( get_the_excerpt( $doc_id ), 20, '…' );
          $anio    = get_post_meta( $doc_id, '_doc_anio',         true ) ?: '';
          $tipo    = get_post_meta( $doc_id, '_doc_tipo_archivo', true ) ?: 'PDF';
          $archivo = get_post_meta( $doc_id, '_doc_archivo_url',  true ) ?: '';

          $doc_cats  = get_the_terms( $doc_id, 'categoria_documento' );
          $cat_label = '';
          $cat_slugs = [];
          $bg_color  = '#223E71';
          if ( $doc_cats && ! is_wp_error( $doc_cats ) ) {
            $cat_label = $doc_cats[0]->name;
            $cat_slugs = wp_list_pluck( $doc_cats, 'slug' );
            $cat_color = get_term_meta( $doc_cats[0]->term_id, '_categoria_color', true );
            if ( $cat_color ) $bg_color = $cat_color;
          }
          $doc_tags  = get_the_terms( $doc_id, 'etiqueta_documento' );
          $tag_slugs = [];
          if ( $doc_tags && ! is_wp_error( $doc_tags ) ) {
            $tag_slugs = wp_list_pluck( $doc_tags, 'slug' );
          }

          $data_cat   = implode( ' ', array_unique( array_merge( $cat_slugs, $tag_slugs ) ) );
          $data_title = strtolower( $titulo_doc );
        ?>
        <article class="doc-card"
                 data-category="<?php echo esc_attr( $data_cat ); ?>"
                 data-title="<?php echo esc_attr( $data_title ); ?>">
          <div class="doc-card__header" style="background:<?php echo esc_attr( $bg_color ); ?>">
            <?php echo $doc_icon_svg; ?>
            <span class="doc-card__type"><?php echo esc_html( $tipo ); ?></span>
          </div>
          <div class="doc-card__body">
            <?php if ( $cat_label ) : ?>
            <span class="doc-card__cat"><?php echo esc_html( $cat_label ); ?></span>
            <?php endif; ?>
            <h3 class="doc-card__title"><?php echo esc_html( $titulo_doc ); ?></h3>
            <?php if ( $excerpt ) : ?>
            <p class="doc-card__desc"><?php echo esc_html( $excerpt ); ?></p>
            <?php endif; ?>
            <div class="doc-card__footer">
              <?php if ( $anio ) : ?>
              <span class="doc-card__year"><?php echo esc_html( $anio ); ?></span>
              <?php endif; ?>
              <?php if ( $archivo ) : ?>
              <a href="<?php echo esc_url( $archivo ); ?>" class="doc-card__btn" target="_blank" rel="noopener">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Ver documento
              </a>
              <?php else : ?>
              <span class="doc-card__btn" style="opacity:.4;cursor:default">Sin archivo</span>
              <?php endif; ?>
            </div>
          </div>
        </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <div class="docs-empty" id="<?php echo esc_attr( $instance_id ); ?>-empty" style="display:none;">
      <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <p>No se encontraron documentos con ese criterio.</p>
    </div>

    <div class="news-pagination" id="<?php echo esc_attr( $instance_id ); ?>-pagination"></div>

  </div>
</section>

<script>
(function () {
  var ID          = <?php echo wp_json_encode( $instance_id ); ?>;
  var PER_PAGE    = <?php echo (int) $por_pagina; ?>;
  var searchInput = document.getElementById(ID + '-search');
  var clearBtn    = document.getElementById(ID + '-clear');
  var filters     = document.querySelectorAll('#' + ID + '-filters .news-filter');
  var grid        = document.getElementById(ID + '-grid');
  var emptyMsg    = document.getElementById(ID + '-empty');
  var pagination  = document.getElementById(ID + '-pagination');
  var allCards    = grid ? Array.from(grid.querySelectorAll('.doc-card')) : [];
  var activeFilter = 'todos';
  var currentPage  = 1;

  function getFiltered() {
    var q = searchInput ? searchInput.value.trim().toLowerCase() : '';
    return allCards.filter(function (card) {
      var cats = (card.dataset.category || '').split(' ');
      var matchFilter = activeFilter === 'todos' || cats.indexOf(activeFilter) > -1;
      var matchSearch = !q || (card.dataset.title || '').indexOf(q) > -1;
      return matchFilter && matchSearch;
    });
  }

  function renderPagination(total) {
    if (!pagination) return;
    if (total <= 1) { pagination.innerHTML = ''; return; }
    var html = '';
    if (currentPage > 1) {
      html += '<button class="news-page" data-page="' + (currentPage - 1) + '">'
            + '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>'
            + 'Anterior</button>';
    }
    for (var i = 1; i <= total; i++) {
      html += '<button class="news-page' + (i === currentPage ? ' active' : '') + '" data-page="' + i + '">' + i + '</button>';
    }
    if (currentPage < total) {
      html += '<button class="news-page news-page--next" data-page="' + (currentPage + 1) + '">'
            + 'Siguiente <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></button>';
    }
    pagination.innerHTML = html;
    pagination.querySelectorAll('.news-page').forEach(function (btn) {
      btn.addEventListener('click', function () {
        currentPage = parseInt(btn.dataset.page, 10);
        renderPage();
        var section = pagination.closest('.docs-section');
        if (section) section.scrollIntoView({ behavior: 'smooth', block: 'start' });
      });
    });
  }

  function renderPage() {
    var filtered   = getFiltered();
    var totalPages = Math.max(1, Math.ceil(filtered.length / PER_PAGE));
    if (currentPage > totalPages) currentPage = 1;
    var start = (currentPage - 1) * PER_PAGE;
    var end   = start + PER_PAGE;

    allCards.forEach(function (c) { c.style.display = 'none'; });
    filtered.forEach(function (c, i) {
      c.style.display = (i >= start && i < end) ? '' : 'none';
    });

    if (emptyMsg) emptyMsg.style.display = filtered.length === 0 ? 'flex' : 'none';
    renderPagination(totalPages);
  }

  if (filters.length) {
    filters.forEach(function (btn) {
      btn.addEventListener('click', function () {
        filters.forEach(function (b) { b.classList.remove('active'); });
        btn.classList.add('active');
        activeFilter = btn.dataset.filter;
        currentPage  = 1;
        renderPage();
      });
    });
  }

  if (searchInput) {
    searchInput.addEventListener('input', function () {
      if (clearBtn) clearBtn.style.display = searchInput.value ? 'flex' : 'none';
      currentPage = 1;
      renderPage();
    });
  }

  if (clearBtn) {
    clearBtn.addEventListener('click', function () {
      if (searchInput) searchInput.value = '';
      clearBtn.style.display = 'none';
      currentPage = 1;
      renderPage();
    });
  }

  renderPage();
})();
</script>
