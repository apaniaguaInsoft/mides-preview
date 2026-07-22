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

    <?php
    /* Construir JSON de datos para los modales */
    $modal_data = [];
    foreach ( $programs as $prog ) {
      $key = $prog['modal'] ?? '';
      if ( ! $key ) continue;
      $icon_url_modal = '';
      if ( ! empty( $prog['iconId'] ) ) {
        $icon_url_modal = wp_get_attachment_image_url( (int) $prog['iconId'], 'full' ) ?: ( $prog['iconUrl'] ?? '' );
      } else {
        $icon_url_modal = $prog['iconUrl'] ?? '';
      }
      $modal_data[ $key ] = [
        'label'    => $prog['label']    ?? 'Programa',
        'title'    => ( $prog['name'] ?? '' ) . ( ! empty( $prog['nameBold'] ) ? ' ' . $prog['nameBold'] : '' ),
        'icon'     => $icon_url_modal,
        'desc'     => $prog['desc']     ?? '',
        'page'     => $prog['page']     ?? '',
        'chips'    => $prog['chips']    ?? [],
        'sections' => $prog['sections'] ?? [],
      ];
    }
    ?>
    <script id="programa-data" type="application/json"><?php echo wp_json_encode( $modal_data ); ?></script>

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

<div class="modal-overlay" id="modal-overlay" role="dialog" aria-modal="true" aria-hidden="true">
  <div class="modal">
    <button class="modal__close" id="modal-close" aria-label="Cerrar">&#10005;</button>
    <div class="modal__header">
      <div class="modal__icon" id="modal-icon"></div>
      <div>
        <p class="modal__label" id="modal-label"></p>
        <h2 class="modal__title" id="modal-title"></h2>
      </div>
    </div>
    <div class="modal__body" id="modal-body"></div>
  </div>
</div>
