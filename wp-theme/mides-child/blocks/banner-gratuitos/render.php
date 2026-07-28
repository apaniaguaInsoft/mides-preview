<?php
/**
 * Render callback del bloque Banner Gratuitos.
 */
$image_id  = $attributes['imageId']  ?? 0;
$image_url = $attributes['imageUrl'] ?? '';
$image_alt = $attributes['imageAlt'] ?? 'Beneficiaria recibiendo apoyo';
$pillar1   = $attributes['pillar1']  ?? 'Seguros y confiables';
$pillar2   = $attributes['pillar2']  ?? 'Para todas las familias';
$pillar3   = $attributes['pillar3']  ?? 'Comprometidos contigo';
$url_denuncia  = get_theme_mod( 'url_denuncia', '#' );
$text_top      = $attributes['textTop']      ?? 'TODOS NUESTROS';
$text_programs = $attributes['textPrograms'] ?? 'PROGRAMAS SOCIALES';
$text_son      = $attributes['textSon']      ?? 'SON';
$text_free     = $attributes['textFree']     ?? 'GRATUITOS';
$text_note     = $attributes['textNote']     ?? 'No debes pagar por';
$text_note_bold = $attributes['textNoteBold'] ?? 'NADA';
$text_btn      = $attributes['textBtn']      ?? 'DENUNCIA AQUÍ';
$text_btn_sub  = $attributes['textBtnSub']   ?? 'CUALQUIER IRREGULARIDAD';

if ( ! empty( $image_id ) ) {
	$image_url = wp_get_attachment_image_url( (int) $image_id, 'full' ) ?: $image_url;
}
?>
<section class="gratuitos-section">
<div class="container">
<div class="gratuitos-banner">
  <div class="gratuitos-banner__left">
    <div class="gratuitos-banner__text">
      <p class="gratuitos-banner__top"><?php echo esc_html( $text_top ); ?></p>
      <p class="gratuitos-banner__programs"><?php echo esc_html( $text_programs ); ?></p>
      <div class="gratuitos-banner__son-wrap">
        <span class="gratuitos-banner__son"><?php echo esc_html( $text_son ); ?></span>
      </div>
      <p class="gratuitos-banner__free"><?php echo esc_html( $text_free ); ?></p>
      <p class="gratuitos-banner__note"><?php echo esc_html( $text_note ); ?> <strong><?php echo esc_html( $text_note_bold ); ?></strong></p>
      <a href="<?php echo esc_url( $url_denuncia ); ?>" class="gratuitos-banner__btn">
        <strong><?php echo esc_html( $text_btn ); ?></strong>
        <span><?php echo esc_html( $text_btn_sub ); ?></span>
      </a>
      <div class="gratuitos-banner__pillars">
        <div class="gratuitos-pillar">
          <div class="gratuitos-pillar__icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          </div>
          <span class="gratuitos-pillar__label"><?php echo esc_html( $pillar1 ); ?></span>
        </div>
        <div class="gratuitos-pillar">
          <div class="gratuitos-pillar__icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
          </div>
          <span class="gratuitos-pillar__label"><?php echo esc_html( $pillar2 ); ?></span>
        </div>
        <div class="gratuitos-pillar">
          <div class="gratuitos-pillar__icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
          </div>
          <span class="gratuitos-pillar__label"><?php echo esc_html( $pillar3 ); ?></span>
        </div>
      </div>
    </div>
  </div>
  <?php if ( $image_url ) : ?>
  <div class="gratuitos-banner__right">
    <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" class="gratuitos-banner__img" />
  </div>
  <?php endif; ?>
</div>
</div>
</section>
