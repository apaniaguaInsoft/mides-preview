<?php
$titulo      = $attributes['titulo']      ?? 'Autoridades';
$autoridades = $attributes['autoridades'] ?? [];
$linea_svg   = get_stylesheet_directory_uri() . '/img/Linea-Resaltadora.svg';
?>
<section class="equipo" id="autoridades">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">
        <span class="title-highlight">
          <?php echo esc_html( $titulo ); ?>
          <img src="<?php echo esc_url( $linea_svg ); ?>" alt="" class="section-underline" />
        </span>
      </h2>
    </div>
    <div class="equipo__grid">
      <?php foreach ( $autoridades as $aut ) :
        $foto_url = '';
        if ( ! empty( $aut['fotoId'] ) ) {
          $foto_url = wp_get_attachment_image_url( (int) $aut['fotoId'], 'medium' ) ?: ( $aut['fotoUrl'] ?? '' );
        } else {
          $foto_url = $aut['fotoUrl'] ?? '';
        }
        $cv_url = $aut['cvUrl'] ?? '#';
      ?>
      <div class="equipo-card">
        <div class="equipo-card__photo">
          <?php if ( $foto_url ) : ?>
            <img src="<?php echo esc_url( $foto_url ); ?>" alt="<?php echo esc_attr( $aut['fotoAlt'] ?? $aut['nombre'] ?? '' ); ?>" />
          <?php else : ?>
            <div class="equipo-card__photo-placeholder"></div>
          <?php endif; ?>
        </div>
        <div class="equipo-card__info">
          <span class="equipo-card__cargo"><?php echo esc_html( $aut['cargo'] ?? '' ); ?></span>
          <p class="equipo-card__nombre"><?php echo esc_html( $aut['nombre'] ?? '' ); ?></p>
          <p class="equipo-card__puesto"><?php echo esc_html( $aut['puesto'] ?? '' ); ?></p>
          <?php if ( $cv_url && $cv_url !== '#' ) : ?>
            <a href="<?php echo esc_url( $cv_url ); ?>" class="equipo-card__cv">Ver hoja de vida</a>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
