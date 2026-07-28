<?php
/**
 * Página de resultados de búsqueda — estilo MIDES.
 */
get_header();

$query_term = get_search_query();
$paged      = max( 1, get_query_var( 'paged' ) );
$linea_svg  = get_stylesheet_directory_uri() . '/img/Linea-Resaltadora.svg';
$arrow_r    = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>';

$labels = [
	'post'      => 'Artículo',
	'page'      => 'Página',
	'noticia'   => 'Noticia',
	'documento' => 'Documento',
];
$placeholders = [
	'documento' => '<svg width="72" height="72" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>',
	'noticia'   => '<svg width="72" height="72" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 22h16a2 2 0 002-2V4a2 2 0 00-2-2H8a2 2 0 00-2 2v16a2 2 0 01-2 2zm0 0a2 2 0 01-2-2v-9c0-1.1.9-2 2-2h2"/><line x1="18" y1="14" x2="12" y2="14"/><line x1="18" y1="18" x2="12" y2="18"/><line x1="18" y1="10" x2="12" y2="10"/></svg>',
	'page'      => '<svg width="72" height="72" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>',
	'post'      => '<svg width="72" height="72" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>',
];
?>

<section class="contacto-hero">
  <div class="contacto-hero__overlay"></div>
  <div class="container contacto-hero__content">
    <h1 class="contacto-hero__title">
      <?php if ( $query_term ) : ?>
        Resultados para <span style="color:#F2A119;">"<?php echo esc_html( $query_term ); ?>"</span>
      <?php else : ?>
        Búsqueda
      <?php endif; ?>
    </h1>
    <img src="<?php echo esc_url( $linea_svg ); ?>" alt="" class="contacto-hero__line" />
    <?php if ( have_posts() ) : ?>
      <p class="contacto-hero__sub"><?php echo number_format_i18n( $GLOBALS['wp_query']->found_posts ); ?> resultado<?php echo $GLOBALS['wp_query']->found_posts !== 1 ? 's' : ''; ?> encontrado<?php echo $GLOBALS['wp_query']->found_posts !== 1 ? 's' : ''; ?></p>
    <?php endif; ?>
  </div>
</section>

<section style="padding:48px 0 80px;">
  <div class="container">

    <!-- Buscador para refinar -->
    <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="news-search-form" style="margin-bottom:32px;">
      <div class="news-search-wrap">
        <div class="news-search">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input type="search" name="s" placeholder="Buscar en el sitio…"
                 value="<?php echo esc_attr( $query_term ); ?>"
                 class="news-search__input" autocomplete="off" />
          <?php if ( $query_term ) : ?>
            <a href="<?php echo esc_url( home_url( '/?s=' ) ); ?>"
               class="news-search__clear" aria-label="Limpiar" style="display:flex;text-decoration:none;">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </a>
          <?php endif; ?>
        </div>
      </div>
    </form>

    <?php if ( have_posts() ) : ?>

      <div class="news-grid">
        <?php while ( have_posts() ) : the_post();
          $type      = get_post_type();
          $type_label = $labels[ $type ] ?? ucfirst( $type );
          $thumb     = get_the_post_thumbnail_url( null, 'medium_large' );
          $excerpt   = wp_trim_words( get_the_excerpt(), 18, '…' );
        ?>
          <article class="news-card">
            <div class="news-card__img-wrap">
              <?php if ( $thumb ) : ?>
                <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php the_title_attribute(); ?>" class="news-card__img">
              <?php else : ?>
                <div class="news-card__img news-card__img--placeholder">
                  <?php echo $placeholders[ $type ] ?? $placeholders['page']; ?>
                </div>
              <?php endif; ?>
              <span class="news-card__date"><?php echo get_the_date( 'j M Y' ); ?></span>
            </div>
            <div class="news-card__body">
              <div class="news-card__tags">
                <span class="news-tag"><?php echo esc_html( $type_label ); ?></span>
              </div>
              <h3 class="news-card__title"><?php the_title(); ?></h3>
              <?php if ( $excerpt ) : ?>
                <p class="news-card__meta" style="font-weight:400;color:#718096;"><?php echo esc_html( $excerpt ); ?></p>
              <?php endif; ?>
              <a href="<?php the_permalink(); ?>" class="news-card__btn">
                Ver resultado <?php echo $arrow_r; ?>
              </a>
            </div>
          </article>
        <?php endwhile; ?>
      </div>

      <?php
      $total_pages = $GLOBALS['wp_query']->max_num_pages;
      if ( $total_pages > 1 ) :
        $base_url = home_url( '/?s=' . urlencode( $query_term ) . '&paged=' );
      ?>
        <div class="news-pagination" style="margin-top:48px;">
          <?php for ( $p = 1; $p <= $total_pages; $p++ ) :
            $active = $p === $paged ? ' active' : '';
            $page_url = home_url( '/?s=' . urlencode( $query_term ) . ( $p > 1 ? '&paged=' . $p : '' ) );
          ?>
            <a href="<?php echo esc_url( $page_url ); ?>" class="news-page<?php echo $active; ?>"><?php echo $p; ?></a>
          <?php endfor; ?>
          <?php if ( $paged < $total_pages ) :
            $next_url = home_url( '/?s=' . urlencode( $query_term ) . '&paged=' . ( $paged + 1 ) );
          ?>
            <a href="<?php echo esc_url( $next_url ); ?>" class="news-page news-page--next">
              Siguiente <?php echo $arrow_r; ?>
            </a>
          <?php endif; ?>
        </div>
      <?php endif; ?>

    <?php else : ?>

      <div style="text-align:center;padding:80px 0 60px;">
        <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="#CBD5E0" stroke-width="1.5" style="margin-bottom:20px;display:block;margin-left:auto;margin-right:auto;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <p style="font-family:'Altivo',sans-serif;font-size:1.1rem;font-weight:700;color:#192854;margin-bottom:8px;">
          No se encontraron resultados para <em>"<?php echo esc_html( $query_term ); ?>"</em>
        </p>
        <p style="font-family:'Altivo',sans-serif;font-size:0.9rem;color:#718096;">
          Intenta con otros términos o verifica la ortografía.
        </p>
      </div>

    <?php endif; ?>

  </div>
</section>

<?php get_footer(); ?>
