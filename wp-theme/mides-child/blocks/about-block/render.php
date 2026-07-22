<?php
$titulo     = $attributes['titulo']    ?? '';
$cuerpo     = $attributes['cuerpo']    ?? '';
$imagen_id  = (int) ( $attributes['imagenId']  ?? 0 );
$imagen_url = $attributes['imagenUrl'] ?? '';
$imagen_alt = $attributes['imagenAlt'] ?? $titulo;
$reverso    = $attributes['reverso']   ?? false;
$fondo_alt  = $attributes['fondoAlt']  ?? false;
$linea_svg  = get_stylesheet_directory_uri() . '/img/Linea-Resaltadora.svg';

if ( $imagen_id ) {
	$imagen_url = wp_get_attachment_image_url( $imagen_id, 'large' ) ?: $imagen_url;
}

/* Doble salto de línea → párrafos */
$parrafos = array_filter( array_map( 'trim', explode( "\n\n", $cuerpo ) ) );

$section_class = 'about-block' . ( $reverso ? '' : '' ) . ( $fondo_alt ? ' about-block--alt' : '' );
$grid_class    = 'about-block__grid' . ( $reverso ? ' about-block__grid--reverse' : '' );
?>
<section class="<?php echo esc_attr( $section_class ); ?>">
  <div class="container <?php echo esc_attr( $grid_class ); ?>">

    <div class="about-block__content">
      <div class="about-block__heading-col">
        <h2 class="about-block__title"><?php echo esc_html( $titulo ); ?></h2>
        <img src="<?php echo esc_url( $linea_svg ); ?>" alt="" class="about-block__line" />
      </div>
      <div class="about-block__body">
        <?php foreach ( $parrafos as $p ) : ?>
          <p><?php echo wp_kses( $p, [ 'strong' => [], 'em' => [], 'a' => [ 'href' => [], 'target' => [] ] ] ); ?></p>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="about-block__img-col">
      <?php if ( $imagen_url ) : ?>
        <img src="<?php echo esc_url( $imagen_url ); ?>" alt="<?php echo esc_attr( $imagen_alt ); ?>" />
      <?php endif; ?>
    </div>

  </div>
</section>
