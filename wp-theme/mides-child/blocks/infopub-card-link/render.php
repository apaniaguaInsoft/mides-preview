<?php
/**
 * Render: Info Pública — Card con Link
 * Muestra una infopub-card individual con enlace a documento del CPT o URL manual.
 */

$modo_doc    = (bool) ( $attributes['modoDocumento'] ?? false );
$doc_id      = (int)  ( $attributes['documentoId']   ?? 0 );
$url_manual  = $attributes['urlManual']   ?? '';
$tag         = $attributes['tag']         ?? '';
$titulo      = $attributes['titulo']      ?? '';
$descripcion = $attributes['descripcion'] ?? '';
$texto_btn   = $attributes['textoBoton']  ?? 'Ver documento';
$color_icono = $attributes['colorIcono']  ?? 'default';
$icono       = $attributes['icono']       ?? 'document';
$tipo_boton  = $attributes['tipoBoton']   ?? 'download';

/* ── URL final ── */
$url = '';
if ( $modo_doc && $doc_id > 0 ) {
	$url = (string) get_post_meta( $doc_id, '_doc_archivo_url', true );
} else {
	$url = $url_manual;
}

/* ── Clase del ícono ── */
$icon_class = 'infopub-card__icon';
if ( $color_icono === 'amber' ) $icon_class .= ' infopub-card__icon--amber';
elseif ( $color_icono === 'teal' ) $icon_class .= ' infopub-card__icon--teal';

/* ── SVGs disponibles ── */
$iconos = [
	'document' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>',
	'globe'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>',
	'home'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>',
	'users'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>',
	'dollar'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>',
	'phone'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>',
	'mail'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>',
	'check'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>',
	'coffee'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8h1a4 4 0 010 8h-1"/><path d="M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>',
	'book'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>',
	'calendar' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>',
];

$icono_svg = $iconos[ $icono ] ?? $iconos['document'];

/* ── SVG del botón ── */
$btn_svgs = [
	'download' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>',
	'external' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>',
	'none'     => '',
];

$btn_icon = $btn_svgs[ $tipo_boton ] ?? $btn_svgs['download'];
$target   = ( $url && ( $tipo_boton === 'external' || $modo_doc ) ) ? ' target="_blank" rel="noopener"' : '';
?>

<div class="infopub-card">
  <div class="infopub-card__header">
    <div class="<?php echo esc_attr( $icon_class ); ?>">
      <?php echo $icono_svg; ?>
    </div>
    <div class="infopub-card__header-text">
      <?php if ( $tag ) : ?>
      <span class="infopub-card__tag"><?php echo esc_html( $tag ); ?></span>
      <?php endif; ?>
      <?php if ( $titulo ) : ?>
      <h3 class="infopub-card__title"><?php echo esc_html( $titulo ); ?></h3>
      <?php endif; ?>
    </div>
  </div>
  <hr class="infopub-card__divider" />
  <?php if ( $descripcion ) : ?>
  <p class="infopub-card__desc"><?php echo esc_html( $descripcion ); ?></p>
  <?php endif; ?>
  <?php if ( $url ) : ?>
  <a href="<?php echo esc_url( $url ); ?>" class="infopub-btn infopub-btn--primary"<?php echo $target; ?>>
    <?php echo $btn_icon; ?>
    <?php echo esc_html( $texto_btn ); ?>
  </a>
  <?php elseif ( $texto_btn ) : ?>
  <span class="infopub-btn infopub-btn--primary" style="opacity:.45;cursor:default;pointer-events:none;">
    <?php echo $btn_icon; ?>
    <?php echo esc_html( $texto_btn ); ?>
  </span>
  <?php endif; ?>
</div>
