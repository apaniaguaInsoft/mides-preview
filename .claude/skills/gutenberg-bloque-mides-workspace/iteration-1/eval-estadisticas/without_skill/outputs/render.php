<?php
$titulo       = $attributes['titulo']       ?? 'Nuestro Impacto';
$estadisticas = $attributes['estadisticas'] ?? [];
?>
<section class="estadisticas-ministerio" id="estadisticas">
  <div class="container">
    <div class="section-header section-header--center">
      <h2 class="section-title section-title--white">
        <?php echo esc_html( $titulo ); ?>
      </h2>
    </div>
    <div class="estadisticas__grid estadisticas__grid--<?php echo esc_attr( count( $estadisticas ) ); ?>">
      <?php foreach ( $estadisticas as $stat ) :
        $icono_url = '';
        if ( ! empty( $stat['iconoId'] ) ) {
          $icono_url = wp_get_attachment_image_url( (int) $stat['iconoId'], 'thumbnail' ) ?: ( $stat['iconoUrl'] ?? '' );
        } else {
          $icono_url = $stat['iconoUrl'] ?? '';
        }
        $icono_alt = $stat['iconoAlt'] ?? ( $stat['etiqueta'] ?? '' );
      ?>
      <div class="estadistica-card">
        <?php if ( $icono_url ) : ?>
          <div class="estadistica-card__icono">
            <img
              src="<?php echo esc_url( $icono_url ); ?>"
              alt="<?php echo esc_attr( $icono_alt ); ?>"
              loading="lazy"
            />
          </div>
        <?php endif; ?>
        <span class="estadistica-card__numero">
          <?php echo esc_html( $stat['numero'] ?? '' ); ?>
        </span>
        <p class="estadistica-card__etiqueta">
          <?php echo esc_html( $stat['etiqueta'] ?? '' ); ?>
        </p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
