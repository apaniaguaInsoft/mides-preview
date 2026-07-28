<?php
/**
 * Render: Info Pública — Lista por Categoría
 * Muestra el panel estilo "infopub-maya" con documentos del CPT filtrados por categoría.
 */

$categoria_id = (int) ( $attributes['categoriaId'] ?? 0 );
$tag          = $attributes['tag']         ?? 'Documentos';
$titulo       = $attributes['titulo']      ?? 'Título del panel';
$descripcion  = $attributes['descripcion'] ?? '';
$icono        = $attributes['icono']       ?? 'globe';
$columnas     = max( 1, min( 5, (int) ( $attributes['columnas'] ?? 5 ) ) );

$iconos = [
	'globe'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>',
	'document' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>',
	'calendar' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>',
	'book'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>',
	'dollar'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>',
];

$icono_svg = $iconos[ $icono ] ?? $iconos['globe'];

$query_args = [
	'post_type'      => 'documento',
	'post_status'    => 'publish',
	'posts_per_page' => -1,
	'orderby'        => 'title',
	'order'          => 'ASC',
	'no_found_rows'  => true,
];

if ( $categoria_id > 0 ) {
	$query_args['tax_query'] = [ [
		'taxonomy' => 'categoria_documento',
		'field'    => 'term_id',
		'terms'    => $categoria_id,
	] ];
}

$query      = new WP_Query( $query_args );
$documentos = $query->posts;
wp_reset_postdata();

$dl_icon = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>';
?>

<div class="infopub-maya">
  <div class="infopub-maya__header">
    <div class="infopub-maya__icon">
      <?php echo $icono_svg; ?>
    </div>
    <div>
      <?php if ( $tag ) : ?>
      <span class="infopub-maya__tag"><?php echo esc_html( $tag ); ?></span>
      <?php endif; ?>
      <h3 class="infopub-maya__title"><?php echo esc_html( $titulo ); ?></h3>
      <?php if ( $descripcion ) : ?>
      <p class="infopub-maya__desc"><?php echo esc_html( $descripcion ); ?></p>
      <?php endif; ?>
    </div>
  </div>

  <div class="infopub-maya__grid" style="--maya-cols:<?php echo $columnas; ?>">
    <?php if ( empty( $documentos ) ) : ?>
      <p style="color:#94A3B8;grid-column:1/-1;font-size:14px;">
        No hay documentos publicados en esta categoría.
      </p>
    <?php else : ?>
      <?php foreach ( $documentos as $doc ) :
        $archivo_url = get_post_meta( $doc->ID, '_doc_archivo_url', true );
        $href        = $archivo_url ? esc_url( $archivo_url ) : '#';
        $target      = $archivo_url ? ' target="_blank" rel="noopener"' : '';
      ?>
      <a href="<?php echo $href; ?>" class="infopub-maya__item"<?php echo $target; ?>>
        <?php echo $dl_icon; ?>
        <?php echo esc_html( $doc->post_title ); ?>
      </a>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>
