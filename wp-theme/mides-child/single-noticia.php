<?php
/**
 * Detalle de noticia: artículo (noticias/prensa) o galería según tipo_noticia.
 */
get_header();

while ( have_posts() ) :
	the_post();

	$tipos     = get_the_terms( get_the_ID(), 'tipo_noticia' ) ?: [];
	$slugs     = wp_list_pluck( $tipos, 'slug' );
	$is_galeria = in_array( 'galeria', $slugs, true );

	if ( $is_galeria ) {
		get_template_part( 'template-parts/single', 'galeria' );
	} else {
		get_template_part( 'template-parts/single', 'articulo' );
	}

endwhile;

get_footer();
