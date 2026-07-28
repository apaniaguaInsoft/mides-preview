<?php
$titulo              = $attributes['titulo']             ?? 'GEDS';
$intro               = $attributes['intro']              ?? '';
$mesas               = $attributes['mesas']              ?? [];
$url_ordinarias        = $attributes['urlOrdinarias']        ?? '';
$url_extraordinarias   = $attributes['urlExtraordinarias']   ?? '';
$label_rectora         = $attributes['labelRectora']         ?? 'Entidad rectora:';
$label_ordinarias      = $attributes['labelOrdinarias']      ?? 'Reuniones Ordinarias';
$texto_ordinarias      = $attributes['textoOrdinarias']      ?? 'Ver galería de sesiones';
$label_extraordinarias = $attributes['labelExtraordinarias'] ?? 'Reuniones Extraordinarias';
$texto_extraordinarias = $attributes['textoExtraordinarias'] ?? 'Ver galería de sesiones';
$linea_svg           = get_stylesheet_directory_uri() . '/img/Linea-Resaltadora.svg';

$svg_table  = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>';
$svg_star   = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>';
$svg_arrow  = '<svg class="mesas-btn__arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>';
?>
<section class="mesas-tecnicas">
  <div class="container">

    <div class="section-header">
      <h2 class="section-title">
        <span class="title-highlight">
          <?php echo esc_html( $titulo ); ?>
          <img src="<?php echo esc_url( $linea_svg ); ?>" alt="" class="section-underline" />
        </span>
      </h2>
      <?php if ( $intro ) : ?>
        <p class="mesas-tecnicas__intro"><?php echo esc_html( $intro ); ?></p>
      <?php endif; ?>
    </div>

    <div class="mesas-tecnicas__grid">
      <?php foreach ( $mesas as $mesa ) : ?>
      <div class="mesa-card">
        <div class="mesa-card__num"><?php echo esc_html( $mesa['num'] ?? '' ); ?></div>
        <div class="mesa-card__body">
          <h3 class="mesa-card__title"><?php echo esc_html( $mesa['titulo'] ?? '' ); ?></h3>
          <span class="mesa-card__rectora"><?php echo esc_html( $label_rectora ); ?> <strong><?php echo esc_html( $mesa['rectora'] ?? '' ); ?></strong></span>
          <p class="mesa-card__desc"><?php echo esc_html( $mesa['descripcion'] ?? '' ); ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <?php if ( $url_ordinarias || $url_extraordinarias ) : ?>
    <div class="mesas-tecnicas__actions">
      <?php if ( $url_ordinarias ) : ?>
      <a href="<?php echo esc_url( $url_ordinarias ); ?>" class="mesas-btn">
        <span class="mesas-btn__icon"><?php echo $svg_table; ?></span>
        <span class="mesas-btn__text">
          <strong><?php echo esc_html( $label_ordinarias ); ?></strong>
          <span><?php echo esc_html( $texto_ordinarias ); ?></span>
        </span>
        <?php echo $svg_arrow; ?>
      </a>
      <?php endif; ?>
      <?php if ( $url_extraordinarias ) : ?>
      <a href="<?php echo esc_url( $url_extraordinarias ); ?>" class="mesas-btn mesas-btn--alt">
        <span class="mesas-btn__icon"><?php echo $svg_star; ?></span>
        <span class="mesas-btn__text">
          <strong><?php echo esc_html( $label_extraordinarias ); ?></strong>
          <span><?php echo esc_html( $texto_extraordinarias ); ?></span>
        </span>
        <?php echo $svg_arrow; ?>
      </a>
      <?php endif; ?>
    </div>
    <?php endif; ?>

  </div>
</section>
