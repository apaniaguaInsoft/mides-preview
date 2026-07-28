<?php
/**
 * Single template para CPT 'documento'.
 */
get_header();

$doc_id  = get_the_ID();
$titulo  = get_the_title();
$excerpt = get_the_excerpt();
$content = get_the_content();

$archivo = get_post_meta( $doc_id, '_doc_archivo_url',  true ) ?: '';
$anio    = get_post_meta( $doc_id, '_doc_anio',         true ) ?: '';
$tipo    = get_post_meta( $doc_id, '_doc_tipo_archivo', true ) ?: 'PDF';

$doc_cats  = get_the_terms( $doc_id, 'categoria_documento' );
$cat_label = '';
$cat_color = '#192854';
if ( $doc_cats && ! is_wp_error( $doc_cats ) ) {
	$cat_label = $doc_cats[0]->name;
	$color_meta = get_term_meta( $doc_cats[0]->term_id, '_categoria_color', true );
	if ( $color_meta ) $cat_color = $color_meta;
}

$linea_svg   = get_stylesheet_directory_uri() . '/img/Linea-Resaltadora.svg';
$archive_url = get_post_type_archive_link( 'documento' );

$arrow_l = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>';
$dl_icon = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>';
$doc_icon = '<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>';
?>

<!-- HERO -->
<section class="contacto-hero">
  <div class="contacto-hero__overlay"></div>
  <div class="container contacto-hero__content">
    <?php if ( $cat_label ) : ?>
      <span class="contacto-hero__eyebrow"><?php echo esc_html( $cat_label ); ?></span>
    <?php endif; ?>
    <h1 class="contacto-hero__title"><?php echo esc_html( $titulo ); ?></h1>
    <img src="<?php echo esc_url( $linea_svg ); ?>" alt="" class="contacto-hero__line" />
    <?php if ( $anio ) : ?>
      <p class="contacto-hero__sub">Publicación <?php echo esc_html( $anio ); ?></p>
    <?php endif; ?>
  </div>
</section>

<!-- CONTENIDO -->
<section style="padding:56px 0 80px;">
  <div class="container" style="max-width:820px;">

    <!-- Card principal -->
    <div class="sdoc-card">

      <!-- Cabecera coloreada -->
      <div class="sdoc-card__header" style="background:<?php echo esc_attr( $cat_color ); ?>">
        <div class="sdoc-card__header-icon">
          <?php echo $doc_icon; ?>
        </div>
        <div class="sdoc-card__header-meta">
          <?php if ( $tipo ) : ?>
            <span class="sdoc-badge"><?php echo esc_html( $tipo ); ?></span>
          <?php endif; ?>
          <?php if ( $anio ) : ?>
            <span class="sdoc-badge sdoc-badge--light"><?php echo esc_html( $anio ); ?></span>
          <?php endif; ?>
        </div>
      </div>

      <!-- Cuerpo -->
      <div class="sdoc-card__body">
        <?php if ( $cat_label ) : ?>
          <span class="sdoc-cat"><?php echo esc_html( $cat_label ); ?></span>
        <?php endif; ?>
        <h2 class="sdoc-title"><?php echo esc_html( $titulo ); ?></h2>

        <?php if ( $excerpt ) : ?>
          <p class="sdoc-desc"><?php echo esc_html( $excerpt ); ?></p>
        <?php endif; ?>

        <?php if ( $content ) : ?>
          <div class="sdoc-content"><?php echo wp_kses_post( $content ); ?></div>
        <?php endif; ?>

        <!-- Botón de descarga -->
        <?php if ( $archivo ) : ?>
          <a href="<?php echo esc_url( $archivo ); ?>" class="sdoc-btn" target="_blank" rel="noopener">
            <?php echo $dl_icon; ?>
            Descargar <?php echo esc_html( $tipo ); ?>
          </a>
        <?php else : ?>
          <span class="sdoc-btn sdoc-btn--disabled">Sin archivo disponible</span>
        <?php endif; ?>
      </div>

    </div>

  </div>
</section>

<?php get_footer(); ?>
