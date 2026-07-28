<?php
$titulo = $attributes['titulo'] ?? '¿Cómo funciona?';
$pasos  = $attributes['pasos']  ?? [];
$linea_svg = get_stylesheet_directory_uri() . '/img/Linea-Resaltadora.svg';
?>
<section class="como-funciona">
  <div class="container">

    <div class="section-header">
      <h2 class="section-title">
        <span class="title-highlight">
          <?php echo esc_html( $titulo ); ?>
          <img src="<?php echo esc_url( $linea_svg ); ?>" alt="" class="section-underline" />
        </span>
      </h2>
    </div>

    <div class="como-funciona__pasos">
      <?php foreach ( $pasos as $index => $paso ) : ?>
      <div class="como-funciona__paso">
        <div class="como-funciona__numero" aria-hidden="true">
          <?php echo esc_html( $paso['numero'] ?? '' ); ?>
        </div>
        <div class="como-funciona__contenido">
          <h3 class="como-funciona__paso-titulo">
            <?php echo esc_html( $paso['titulo'] ?? '' ); ?>
          </h3>
          <?php if ( ! empty( $paso['descripcion'] ) ) : ?>
          <p class="como-funciona__paso-desc">
            <?php echo esc_html( $paso['descripcion'] ); ?>
          </p>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>
