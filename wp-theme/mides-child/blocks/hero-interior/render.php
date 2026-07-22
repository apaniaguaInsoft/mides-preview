<?php
$titulo    = $attributes['titulo']    ?? '';
$subtitulo = $attributes['subtitulo'] ?? '';
$linea_svg = get_stylesheet_directory_uri() . '/img/Linea-Resaltadora.svg';
?>
<section class="contacto-hero">
  <div class="contacto-hero__overlay"></div>
  <div class="container contacto-hero__content">
    <h1 class="contacto-hero__title"><?php echo esc_html( $titulo ); ?></h1>
    <img src="<?php echo esc_url( $linea_svg ); ?>" alt="" class="contacto-hero__line" />
    <?php if ( $subtitulo ) : ?>
      <p class="contacto-hero__sub"><?php echo esc_html( $subtitulo ); ?></p>
    <?php endif; ?>
  </div>
</section>
