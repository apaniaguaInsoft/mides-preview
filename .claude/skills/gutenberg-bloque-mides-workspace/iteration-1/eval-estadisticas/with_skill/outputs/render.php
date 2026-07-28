<?php
$titulo       = $attributes['titulo']       ?? 'Estadísticas del Ministerio';
$estadisticas = $attributes['estadisticas'] ?? [];
$linea_svg    = get_stylesheet_directory_uri() . '/img/Linea-Resaltadora.svg';
?>
<section class="estadisticas-mides" id="estadisticas">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title section-title--light">
        <span class="title-highlight">
          <?php echo esc_html( $titulo ); ?>
          <img src="<?php echo esc_url( $linea_svg ); ?>" alt="" class="section-underline" />
        </span>
      </h2>
    </div>
    <div class="estadisticas-mides__grid">
      <?php foreach ( $estadisticas as $item ) :
        $icono_url = '';
        if ( ! empty( $item['iconoId'] ) ) {
          $icono_url = wp_get_attachment_image_url( (int) $item['iconoId'], 'full' ) ?: ( $item['iconoUrl'] ?? '' );
        } else {
          $icono_url = $item['iconoUrl'] ?? '';
        }
        $icono_alt = $item['iconoAlt'] ?? $item['etiqueta'] ?? '';
      ?>
      <div class="estadisticas-mides__item">
        <?php if ( $icono_url ) : ?>
          <div class="estadisticas-mides__icono">
            <img src="<?php echo esc_url( $icono_url ); ?>" alt="<?php echo esc_attr( $icono_alt ); ?>" />
          </div>
        <?php endif; ?>
        <span class="estadisticas-mides__numero"><?php echo esc_html( $item['numero'] ?? '' ); ?></span>
        <p class="estadisticas-mides__etiqueta"><?php echo esc_html( $item['etiqueta'] ?? '' ); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
