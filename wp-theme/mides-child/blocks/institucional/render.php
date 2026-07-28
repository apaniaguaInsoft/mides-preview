<?php
/**
 * Render callback del bloque Institucional.
 */
$titulo      = $attributes['titulo']      ?? 'Institucional';
$intro       = $attributes['intro']       ?? '';
$cards       = $attributes['cards']       ?? [];
$url_ver_mas = $attributes['urlVerMas']   ?? '';
$texto_btn   = $attributes['textoVerMas'] ?? 'Conocer más sobre nosotros';
$columnas4   = $attributes['columnas4']   ?? false;
$linea_svg        = get_stylesheet_directory_uri() . '/img/line3.svg';
$linea_titulo_svg = get_stylesheet_directory_uri() . '/img/Linea-Resaltadora.svg';
$theme_img   = get_stylesheet_directory_uri() . '/img/';

/* Íconos por defecto (theme) si la card no trae uno */
$default_icons = [
	'Misión'       => $theme_img . 'Icono-Mision.svg',
	'Visión'       => $theme_img . 'Icono-Vison.svg',
	'Propósito'    => $theme_img . 'Icono-Proposito.svg',
	'Valores'      => $theme_img . 'Icono-Valores.svg',
	'Autoridades'  => $theme_img . 'Icono-Autoridades.svg',
];

$arrow_svg = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>';
?>
<section class="nosotros" id="nosotros">
  <div class="container">

    <div class="section-header">
      <h2 class="section-title">
        <?php
          $titulo_words = explode( ' ', $titulo );
          $ultima_word  = array_pop( $titulo_words );
          $resto_titulo = implode( ' ', $titulo_words );
        ?>
        <?php if ( $resto_titulo ) : ?>
          <?php echo esc_html( $resto_titulo ); ?>&nbsp;<span class="title-highlight"><?php echo esc_html( $ultima_word ); ?><img src="<?php echo esc_url( $linea_titulo_svg ); ?>" alt="" class="section-underline" /></span>
        <?php else : ?>
          <span class="title-highlight"><?php echo esc_html( $ultima_word ); ?><img src="<?php echo esc_url( $linea_titulo_svg ); ?>" alt="" class="section-underline" /></span>
        <?php endif; ?>
      </h2>
      <?php if ( $intro ) : ?>
        <p class="nosotros__intro"><?php echo esc_html( $intro ); ?></p>
      <?php endif; ?>
    </div>

    <div class="nosotros__cards<?php echo $columnas4 ? ' nosotros__cards--4col' : ''; ?>">
      <?php foreach ( $cards as $card ) :
        $nombre = $card['nombre'] ?? '';

        /* Resolver ícono */
        $icon_html = '';
        if ( ! empty( $card['iconId'] ) ) {
          $icon_url = wp_get_attachment_image_url( (int) $card['iconId'], 'full' ) ?: ( $card['iconUrl'] ?? '' );
        } else {
          $icon_url = $card['iconUrl'] ?? '';
        }

        if ( $icon_url ) {
          $icon_html = '<img src="' . esc_url( $icon_url ) . '" alt="' . esc_attr( $card['iconAlt'] ?? $nombre ) . '" />';
        } elseif ( isset( $default_icons[ $nombre ] ) ) {
          $icon_html = '<img src="' . esc_url( $default_icons[ $nombre ] ) . '" alt="' . esc_attr( $nombre ) . '" />';
        }

        $link      = $card['link']     ?? '';
        $link_text = $card['linkText'] ?? '';
      ?>
      <div class="card-nosotros">
        <div class="card-nosotros__inner">

          <div class="card-nosotros__front">
            <div class="card-nosotros__icon"><?php echo $icon_html; ?></div>
            <h3><?php echo esc_html( $nombre ); ?></h3>
            <img src="<?php echo esc_url( $linea_svg ); ?>" alt="" class="card-nosotros__line" />
          </div>

          <div class="card-nosotros__back">
            <h3><?php echo esc_html( $nombre ); ?></h3>
            <img src="<?php echo esc_url( $linea_svg ); ?>" alt="" class="card-nosotros__line" />
            <p><?php echo esc_html( $card['descripcion'] ?? '' ); ?></p>
            <?php if ( $link ) : ?>
              <a href="<?php echo esc_url( $link ); ?>" class="card-nosotros__link">
                <?php echo esc_html( $link_text ?: 'Ver más' ); ?>
                <?php echo $arrow_svg; ?>
              </a>
            <?php endif; ?>
          </div>

        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <?php if ( $url_ver_mas ) : ?>
    <div class="nosotros__cta">
      <a href="<?php echo esc_url( $url_ver_mas ); ?>" class="btn-ver-mas">
        <?php echo esc_html( $texto_btn ); ?>
      </a>
    </div>
    <?php endif; ?>

  </div>
</section>
