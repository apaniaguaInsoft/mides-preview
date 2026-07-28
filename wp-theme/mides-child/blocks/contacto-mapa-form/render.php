<?php
$linea_svg    = get_stylesheet_directory_uri() . '/img/Linea-Resaltadora.svg';
$titulo_mapa  = $attributes['tituloMapa']      ?? 'Nuestra Ubicación';
$mapa_url     = $attributes['mapaUrl']         ?? '';
$mapa_pie     = $attributes['mapaPie']         ?? '';
$form_id      = intval( $attributes['formId']  ?? 60 );
$form_titulo  = $attributes['formTitulo']      ?? 'Envíanos un Mensaje';
$form_desc    = $attributes['formDescripcion'] ?? '';

// Partir título en dos partes: "Envíanos un" + "Mensaje"
$parts    = explode( ' ', $form_titulo, -1 );
$last     = explode( ' ', $form_titulo );
$last_w   = array_pop( $last );
$first_ws = implode( ' ', $last );
?>
<section class="contacto-main">
  <div class="container contacto-main__grid">

    <!-- Mapa -->
    <div class="contacto-map">
      <div class="contacto-map__header">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 32 32" fill="none"
             stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
          <circle cx="16" cy="11" r="4"/>
          <path d="M24 15c-3 7-8 15-8 15s-5-8-8-15s2-13 8-13s11 6 8 13"/>
        </svg>
        <span><?php echo esc_html( $titulo_mapa ); ?></span>
      </div>
      <?php if ( $mapa_url ) : ?>
      <div class="contacto-map__frame">
        <iframe
          title="Ubicación MIDES"
          src="<?php echo esc_url( $mapa_url ); ?>"
          width="100%" height="100%" style="border:0;"
          allowfullscreen="" loading="lazy"
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>
      <?php endif; ?>
      <?php if ( $mapa_pie ) : ?>
      <div class="contacto-map__footer">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="#192854">
          <path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0118 0z"/>
        </svg>
        <span><?php echo esc_html( $mapa_pie ); ?></span>
      </div>
      <?php endif; ?>
    </div>

    <!-- Formulario -->
    <div class="contacto-form-wrap">
      <div class="contacto-form__header">
        <h2 class="contacto-form__title">
          <?php echo esc_html( $first_ws ); ?><br>
          <span class="title-highlight">
            <?php echo esc_html( $last_w ); ?>
            <img src="<?php echo esc_url( $linea_svg ); ?>" alt="" class="section-underline" />
          </span>
        </h2>
        <?php if ( $form_desc ) : ?>
        <p class="contacto-form__desc">
          <?php echo esc_html( $form_desc ); ?>
        </p>
        <?php endif; ?>
      </div>
      <?php if ( $form_id ) : ?>
        <?php echo do_shortcode( '[wpforms id="' . $form_id . '"]' ); ?>
      <?php endif; ?>
    </div>

  </div>
</section>
