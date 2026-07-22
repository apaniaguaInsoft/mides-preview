<?php
/**
 * Render callback del bloque Noticias Recientes.
 */
$titulo        = $attributes['titulo']        ?? 'Noticias';
$subtitulo     = $attributes['subtitulo']     ?? '';
$cantidad      = (int) ( $attributes['cantidad']  ?? 3 );
$categorias    = $attributes['categorias']    ?? [];
$url_ver_todas = $attributes['urlVerTodas']   ?? '';
$texto_btn     = $attributes['textoVerTodas'] ?? 'Ver todas las noticias';
$linea_svg     = get_stylesheet_directory_uri() . '/img/Linea-Resaltadora.svg';
$arrow_svg     = get_stylesheet_directory_uri() . '/img/Ver-mas-flechas.svg';

$query_args = [
	'post_type'      => 'noticia',
	'post_status'    => 'publish',
	'posts_per_page' => $cantidad,
	'orderby'        => 'date',
	'order'          => 'DESC',
	'no_found_rows'  => true,
];

if ( ! empty( $categorias ) ) {
	$query_args['tax_query'] = [ [
		'taxonomy' => 'tipo_noticia',
		'field'    => 'term_id',
		'terms'    => array_map( 'intval', $categorias ),
	] ];
}

$query = new WP_Query( $query_args );
$posts = $query->posts;
wp_reset_postdata();

if ( empty( $posts ) ) {
	return '';
}
?>
<section class="noticias" id="noticias">
  <div class="container">

    <div class="section-header section-header--white">
      <h2 class="section-title section-title--white">
        <span class="title-highlight title-highlight--white">
          <?php echo esc_html( $titulo ); ?>
          <img src="<?php echo esc_url( $linea_svg ); ?>" alt="" />
        </span>
      </h2>
      <?php if ( $subtitulo ) : ?>
        <p class="noticias__subtitle"><?php echo esc_html( $subtitulo ); ?></p>
      <?php endif; ?>
    </div>

    <div class="noticias__carousel">
      <button class="noticias__arrow noticias__arrow--prev" aria-label="Anterior">&#8249;</button>

      <div class="noticias__track">
        <?php foreach ( $posts as $i => $post ) :
          $thumb_url  = get_the_post_thumbnail_url( $post->ID, 'large' ) ?: '';
          $post_cats  = get_the_category( $post->ID );
          $tag_label  = ! empty( $post_cats ) ? $post_cats[0]->name : 'Noticia';
          $post_url   = get_permalink( $post->ID );
          $post_date  = get_the_date( 'j F, Y', $post->ID );
          $excerpt    = wp_trim_words( get_the_excerpt( $post->ID ) ?: wp_strip_all_tags( $post->post_content ), 30, '…' );
          $is_first   = $i === 0 ? ' active' : '';
        ?>
        <article class="noticia-card<?php echo $is_first; ?>">
          <div class="noticia-card__body">
            <div class="noticia-card__tags">
              <span class="news-tag"><?php echo esc_html( $tag_label ); ?></span>
            </div>
            <h3 class="noticia-card__title"><?php echo esc_html( get_the_title( $post->ID ) ); ?></h3>
            <p class="noticia-card__desc"><?php echo esc_html( $excerpt ); ?></p>
            <a href="<?php echo esc_url( $post_url ); ?>" class="noticias__link">
              Ver más
              <img src="<?php echo esc_url( $arrow_svg ); ?>" alt="" class="noticias__link-arrow" />
            </a>
          </div>
          <div class="noticia-card__img-wrap">
            <?php if ( $thumb_url ) : ?>
              <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( get_the_title( $post->ID ) ); ?>" class="noticia-card__img" />
            <?php else : ?>
              <div class="noticia-card__img noticia-card__img--placeholder"></div>
            <?php endif; ?>
            <span class="noticia-card__date"><?php echo esc_html( $post_date ); ?></span>
          </div>
        </article>
        <?php endforeach; ?>
      </div>

      <button class="noticias__arrow noticias__arrow--next" aria-label="Siguiente">&#8250;</button>
    </div>

    <?php if ( count( $posts ) > 1 ) : ?>
    <div class="noticias__dots">
      <?php foreach ( $posts as $i => $post ) : ?>
        <button class="noticias__dot<?php echo $i === 0 ? ' active' : ''; ?>" aria-label="Ir a noticia <?php echo $i + 1; ?>"></button>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if ( $url_ver_todas ) : ?>
    <div class="noticias__footer">
      <a href="<?php echo esc_url( $url_ver_todas ); ?>" class="btn-ver-todas">
        <?php echo esc_html( $texto_btn ); ?>
      </a>
    </div>
    <?php endif; ?>

  </div>
</section>
