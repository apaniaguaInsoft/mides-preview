<?php
$eyebrow       = $attributes['eyebrow']      ?? 'Programa';
$titulo        = $attributes['titulo']       ?? '';
$hero_icon_id  = (int) ( $attributes['heroIconId']  ?? 0 );
$hero_icon_url = $attributes['heroIconUrl']  ?? '';
$hero_icon_alt = $attributes['heroIconAlt']  ?? $titulo;
$img_id        = (int) ( $attributes['imgId']   ?? 0 );
$img_url       = $attributes['imgUrl']       ?? '';
$img_alt       = $attributes['imgAlt']       ?? '';
$secciones     = $attributes['secciones']    ?? [];
$show_monto    = $attributes['showMonto']    ?? true;
$monto_label   = $attributes['montoLabel']   ?? 'Transferencia periódica';
$monto_amount  = $attributes['montoAmount']  ?? '';
$monto_sub     = $attributes['montoSub']     ?? 'Por familia beneficiaria';
$chips_label   = $attributes['chipsLabel']   ?? 'Intervenciones';
$chips_str     = $attributes['chips']        ?? '';
$sidebar_cards = $attributes['sidebarCards'] ?? [];

if ( $hero_icon_id ) {
	$resolved = wp_get_attachment_image_url( $hero_icon_id, 'full' );
	if ( $resolved ) $hero_icon_url = $resolved;
}
if ( $img_id ) {
	$resolved = wp_get_attachment_image_url( $img_id, 'large' );
	if ( $resolved ) $img_url = $resolved;
}

$chips = array_values( array_filter( array_map( 'trim', explode( "\n", $chips_str ) ) ) );
?>
<section class="programa-detail__hero">
  <div class="container">
    <div class="programa-detail__hero-inner">
      <?php if ( $hero_icon_url ) : ?>
      <div class="programa-detail__hero-icon">
        <img src="<?php echo esc_url( $hero_icon_url ); ?>" alt="<?php echo esc_attr( $hero_icon_alt ); ?>" />
      </div>
      <?php endif; ?>
      <div>
        <p class="programa-detail__eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
        <h1 class="programa-detail__title"><?php echo esc_html( $titulo ); ?></h1>
      </div>
    </div>
  </div>
</section>

<section class="programa-detail">
  <div class="container">
    <div class="programa-detail__body">

      <div class="programa-detail__main">

        <?php if ( $img_url ) : ?>
        <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $img_alt ); ?>" class="programa-detail__img" />
        <?php endif; ?>

        <?php foreach ( $secciones as $sec ) :
          $sec_titulo   = $sec['titulo']    ?? '';
          $sec_tipo     = $sec['tipo']      ?? 'texto';
          $sec_contenido = $sec['contenido'] ?? '';
          if ( ! $sec_titulo && ! $sec_contenido ) continue;
        ?>
        <div class="pd-block">
          <h2 class="pd-block__title"><?php echo esc_html( $sec_titulo ); ?></h2>

          <?php if ( $sec_tipo === 'texto' ) : ?>
            <p class="pd-block__text"><?php echo esc_html( $sec_contenido ); ?></p>

          <?php elseif ( $sec_tipo === 'lista' ) :
            $items = array_values( array_filter( array_map( 'trim', explode( "\n", $sec_contenido ) ) ) );
            if ( $items ) : ?>
            <ul class="pd-block__list">
              <?php foreach ( $items as $item ) : ?>
              <li><?php echo esc_html( $item ); ?></li>
              <?php endforeach; ?>
            </ul>
            <?php endif; ?>

          <?php elseif ( $sec_tipo === 'modalidades' ) :
            $lines = array_values( array_filter( array_map( 'trim', explode( "\n", $sec_contenido ) ) ) );
            foreach ( $lines as $line ) :
              $parts     = explode( '|||', $line, 2 );
              $mod_nombre = trim( $parts[0] ?? '' );
              $mod_desc   = trim( $parts[1] ?? '' );
              if ( ! $mod_nombre ) continue;
            ?>
            <div class="pd-modalidad">
              <p class="pd-modalidad__name"><?php echo esc_html( $mod_nombre ); ?></p>
              <?php if ( $mod_desc ) : ?>
              <p class="pd-modalidad__desc"><?php echo esc_html( $mod_desc ); ?></p>
              <?php endif; ?>
            </div>
            <?php endforeach; ?>

          <?php endif; ?>
        </div>
        <?php endforeach; ?>

      </div>

      <div class="programa-detail__sidebar">

        <?php
        $monto_free = $attributes['montoFree'] ?? false;
        if ( $show_monto && $monto_amount ) : ?>
        <div class="pd-monto<?php echo $monto_free ? ' pd-monto--free' : ''; ?>">
          <p class="pd-monto__label"><?php echo esc_html( $monto_label ); ?></p>
          <p class="pd-monto__amount"><?php echo esc_html( $monto_amount ); ?></p>
          <p class="pd-monto__sub"><?php echo esc_html( $monto_sub ); ?></p>
        </div>
        <?php endif; ?>

        <?php if ( $chips ) : ?>
        <div class="pd-block">
          <p class="pd-block__title" style="font-size:0.82rem;"><?php echo esc_html( $chips_label ); ?></p>
          <div class="pd-chips">
            <?php foreach ( $chips as $chip ) : ?>
            <span class="pd-chip"><?php echo esc_html( $chip ); ?></span>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <?php foreach ( $sidebar_cards as $card ) :
          $card_titulo = $card['titulo'] ?? '';
          $card_items  = array_values( array_filter( array_map( 'trim', explode( "\n", $card['items'] ?? '' ) ) ) );
          if ( ! $card_titulo && ! $card_items ) continue;
        ?>
        <div class="pd-sidebar-card">
          <p class="pd-sidebar-card__title"><?php echo esc_html( $card_titulo ); ?></p>
          <?php if ( $card_items ) : ?>
          <ul class="pd-sidebar-card__list">
            <?php foreach ( $card_items as $item ) : ?>
            <li><?php echo esc_html( $item ); ?></li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>

      </div>
    </div>
  </div>
</section>
