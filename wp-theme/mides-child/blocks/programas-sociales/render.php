<?php
/**
 * Render callback del bloque Programas Sociales.
 */
$programs         = $attributes['programs']        ?? [];
$title_before     = $attributes['titleBefore']     ?? 'Programas e';
$title_highlight  = $attributes['titleHighlight']  ?? 'Intervenciones Sociales';
$linea_svg        = get_stylesheet_directory_uri() . '/img/Linea-Resaltadora.svg';
?>
<section class="programas" id="programas">
  <div class="container">

    <div class="section-header">
      <h2 class="section-title">
        <?php if ( $title_before ) : ?>
          <?php echo esc_html( $title_before ); ?>
        <?php endif; ?>
        <span class="title-highlight">
          <?php echo esc_html( $title_highlight ); ?>
          <img src="<?php echo esc_url( $linea_svg ); ?>" alt="" class="section-underline" />
        </span>
      </h2>
    </div>

    <div class="programas__grid">
      <?php foreach ( $programs as $prog ) :
        $icon_url = '';
        if ( ! empty( $prog['iconId'] ) ) {
          $icon_url = wp_get_attachment_image_url( (int) $prog['iconId'], 'full' ) ?: ( $prog['iconUrl'] ?? '' );
        } else {
          $icon_url = $prog['iconUrl'] ?? '';
        }
        $modal = ! empty( $prog['modal'] ) ? ' data-modal="' . esc_attr( $prog['modal'] ) . '"' : '';
      ?>
      <div class="card-programa"<?php echo $modal; ?>>
        <div class="card-programa__icon">
          <?php if ( $icon_url ) : ?>
            <img src="<?php echo esc_url( $icon_url ); ?>" alt="<?php echo esc_attr( $prog['iconAlt'] ?? '' ); ?>" />
          <?php endif; ?>
        </div>
        <span class="card-programa__label"><?php echo esc_html( $prog['label'] ?? 'Programa' ); ?></span>
        <h3 class="card-programa__name">
          <?php if ( ! empty( $prog['name'] ) ) : ?>
            <?php echo esc_html( $prog['name'] ); ?><br>
          <?php endif; ?>
          <?php if ( ! empty( $prog['nameBold'] ) ) : ?>
            <strong><?php echo esc_html( $prog['nameBold'] ); ?></strong>
          <?php endif; ?>
        </h3>
      </div>
      <?php endforeach; ?>
    </div>


  </div>
</section>
