<?php
/**
 * Archivo de noticias: lista Noticias, Prensa y Galería.
 */
get_header();

$tipo_slug = sanitize_key( $_GET['tipo'] ?? '' );
$paged     = max( 1, get_query_var( 'paged' ) );

$args = [
	'post_type'      => 'noticia',
	'post_status'    => 'publish',
	'posts_per_page' => 12,
	'paged'          => $paged,
	'orderby'        => 'date',
	'order'          => 'DESC',
];

if ( $tipo_slug && $tipo_slug !== 'todos' ) {
	$args['tax_query'] = [ [
		'taxonomy' => 'tipo_noticia',
		'field'    => 'slug',
		'terms'    => $tipo_slug,
	] ];
}

$query = new WP_Query( $args );

$archive_url = get_post_type_archive_link( 'noticia' );

$tipos_filtro = [
	''        => 'Todos',
	'noticias' => 'Noticias',
	'prensa'   => 'Prensa',
	'galeria'  => 'Galería',
];

$linea_svg = get_stylesheet_directory_uri() . '/img/Linea-Resaltadora.svg';
$arrow_dl  = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>';
$arrow_r   = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>';
?>

<section class="contacto-hero">
  <div class="contacto-hero__overlay"></div>
  <div class="container contacto-hero__content">
    <h1 class="contacto-hero__title">Noticias</h1>
    <img src="<?php echo esc_url( $linea_svg ); ?>" alt="" class="contacto-hero__line" />
    <p class="contacto-hero__sub">Comunicación Social · Ministerio de Desarrollo Social</p>
  </div>
</section>

<section class="noticias-archive" style="padding:56px 0 80px">
  <div class="container">

    <div class="news-filters">
      <?php foreach ( $tipos_filtro as $slug => $label ) :
        $active = ( $tipo_slug === $slug || ( ! $tipo_slug && $slug === '' ) ) ? ' active' : '';
        $url    = $slug ? add_query_arg( 'tipo', $slug, $archive_url ) : $archive_url;
      ?>
        <a href="<?php echo esc_url( $url ); ?>" class="news-filter<?php echo $active; ?>"><?php echo esc_html( $label ); ?></a>
      <?php endforeach; ?>
    </div>

    <?php if ( $query->have_posts() ) : ?>

      <div class="news-grid">
        <?php while ( $query->have_posts() ) : $query->the_post();
          $tipos_post = get_the_terms( get_the_ID(), 'tipo_noticia' ) ?: [];
          $slugs_post = wp_list_pluck( $tipos_post, 'slug' );
          $is_galeria = in_array( 'galeria', $slugs_post, true );
          $thumb      = get_the_post_thumbnail_url( null, 'medium_large' );
        ?>
          <article class="news-card">
            <div class="news-card__img-wrap">
              <?php if ( $thumb ) : ?>
                <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php the_title_attribute(); ?>" class="news-card__img">
              <?php else : ?>
                <div class="news-card__img news-card__img--placeholder"></div>
              <?php endif; ?>
              <span class="news-card__date"><?php echo get_the_date( 'j M Y' ); ?></span>
            </div>
            <div class="news-card__body">
              <div class="news-card__tags">
                <?php foreach ( $tipos_post as $t ) : ?>
                  <span class="news-tag"><?php echo esc_html( $t->name ); ?></span>
                <?php endforeach; ?>
              </div>
              <h3 class="news-card__title"><?php the_title(); ?></h3>
              <p class="news-card__meta">Por <?php the_author(); ?></p>
              <a href="<?php the_permalink(); ?>" class="news-card__btn">
                <?php echo $is_galeria ? 'Ver galería ' . $arrow_dl : 'Detalles ' . $arrow_r; ?>
              </a>
            </div>
          </article>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>

      <?php if ( $query->max_num_pages > 1 ) : ?>
        <div class="news-pagination">
          <?php for ( $p = 1; $p <= $query->max_num_pages; $p++ ) :
            $active = $p === $paged ? ' active' : '';
            $page_url = add_query_arg( [ 'tipo' => $tipo_slug ?: null, 'paged' => $p > 1 ? $p : null ], $archive_url );
          ?>
            <a href="<?php echo esc_url( $page_url ); ?>" class="news-page<?php echo $active; ?>"><?php echo $p; ?></a>
          <?php endfor; ?>
          <?php if ( $paged < $query->max_num_pages ) :
            $next_url = add_query_arg( [ 'tipo' => $tipo_slug ?: null, 'paged' => $paged + 1 ], $archive_url );
          ?>
            <a href="<?php echo esc_url( $next_url ); ?>" class="news-page news-page--next">Siguiente <?php echo $arrow_r; ?></a>
          <?php endif; ?>
        </div>
      <?php endif; ?>

    <?php else : ?>
      <p style="text-align:center;color:#718096;padding:60px 0">No hay publicaciones todavía.</p>
    <?php endif; ?>

  </div>
</section>

<?php get_footer(); ?>
