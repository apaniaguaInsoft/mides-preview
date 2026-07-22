<?php
/**
 * MIDES Child Theme — functions.php
 */

/* ── Soporte de tema ────────────────────────────────────────────────────── */
add_action( 'after_setup_theme', function () {
	add_theme_support( 'align-wide' );
	register_nav_menus( [
		'menu-pre-header' => 'Menú Pre-Header (barra de aliados)',
		'menu-principal'  => 'Menú Principal',
		'menu-footer'     => 'Menú Footer (enlaces rápidos)',
	] );
} );

/* ── Encolar estilos del tema ───────────────────────────────────────────── */
add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'astra-child-style', get_stylesheet_uri(), [ 'astra-theme-css' ], wp_get_theme()->get( 'Version' ) );
	wp_enqueue_style( 'mides-styles', get_stylesheet_directory_uri() . '/mides.css', [ 'astra-child-style' ], wp_get_theme()->get( 'Version' ) );
	wp_enqueue_script( 'mides-menu-mobile', get_stylesheet_directory_uri() . '/js/menu-mobile.js', [], wp_get_theme()->get( 'Version' ), true );
} );

/* ── CPT Noticias + Taxonomía tipo_noticia ──────────────────────────────── */
add_action( 'init', function () {
	register_post_type( 'noticia', [
		'labels' => [
			'name'               => 'Noticias',
			'singular_name'      => 'Noticia',
			'add_new_item'       => 'Agregar noticia',
			'edit_item'          => 'Editar noticia',
			'new_item'           => 'Nueva noticia',
			'view_item'          => 'Ver noticia',
			'search_items'       => 'Buscar noticias',
			'not_found'          => 'No se encontraron noticias',
			'all_items'          => 'Todas las noticias',
			'menu_name'          => 'Noticias',
		],
		'public'       => true,
		'has_archive'  => 'noticias',
		'rewrite'      => [ 'slug' => 'noticias', 'with_front' => false ],
		'show_in_rest' => true,
		'supports'     => [ 'title', 'editor', 'thumbnail', 'excerpt', 'author', 'comments' ],
		'menu_icon'    => 'dashicons-megaphone',
		'menu_position' => 5,
	] );

	register_taxonomy( 'tipo_noticia', 'noticia', [
		'labels' => [
			'name'          => 'Tipo',
			'singular_name' => 'Tipo',
			'all_items'     => 'Todos los tipos',
			'edit_item'     => 'Editar tipo',
			'add_new_item'  => 'Agregar tipo',
		],
		'hierarchical'  => true,
		'public'        => true,
		'show_in_rest'  => true,
		'rewrite'       => [ 'slug' => 'tipo-noticia' ],
	] );
} );

/* ── Meta box: número de comunicado + imágenes de galería ───────────────── */
add_action( 'add_meta_boxes', function () {
	add_meta_box( 'mides_noticia_meta', 'Datos adicionales', 'mides_noticia_meta_box_cb', 'noticia', 'normal', 'high' );
} );

function mides_noticia_meta_box_cb( WP_Post $post ): void {
	wp_nonce_field( 'mides_noticia_meta_save', 'mides_noticia_nonce' );
	wp_enqueue_media();

	$numero   = get_post_meta( $post->ID, '_numero_comunicado', true );
	$raw_ids  = get_post_meta( $post->ID, '_galeria_imagenes', true );
	$ids      = $raw_ids ? json_decode( $raw_ids, true ) : [];
	$ids      = is_array( $ids ) ? array_filter( array_map( 'intval', $ids ) ) : [];
	?>
	<table class="form-table" style="max-width:700px">
	  <tr>
	    <th><label for="numero_comunicado">Número de comunicado <span style="font-weight:400;color:#666">(solo Prensa)</span></label></th>
	    <td><input type="text" id="numero_comunicado" name="numero_comunicado" value="<?php echo esc_attr( $numero ); ?>" placeholder="Ej: 048-2026" class="regular-text"></td>
	  </tr>
	  <tr>
	    <th style="vertical-align:top;padding-top:12px"><label>Imágenes de galería <span style="font-weight:400;color:#666">(solo Galería)</span></label></th>
	    <td>
	      <p style="margin:0 0 8px;color:#666;font-size:12px">Las captions se toman del campo "Título/Descripción" de cada imagen en la Librería de medios.</p>
	      <div id="mides-galeria-preview" style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:10px">
	        <?php foreach ( $ids as $id ) :
	          $url = wp_get_attachment_image_url( $id, 'thumbnail' );
	          if ( ! $url ) continue;
	        ?>
	          <div class="mides-gthumb" data-id="<?php echo $id; ?>" style="position:relative">
	            <img src="<?php echo esc_url( $url ); ?>" style="width:72px;height:72px;object-fit:cover;border-radius:4px;display:block">
	            <button type="button" class="mides-gremove" style="position:absolute;top:2px;right:2px;background:#c00;color:#fff;border:none;border-radius:50%;width:18px;height:18px;cursor:pointer;font-size:11px;line-height:1;padding:0">&times;</button>
	          </div>
	        <?php endforeach; ?>
	      </div>
	      <input type="hidden" id="galeria_imagenes" name="galeria_imagenes" value="<?php echo esc_attr( wp_json_encode( array_values( $ids ) ) ); ?>">
	      <button type="button" id="mides-galeria-add" class="button">+ Agregar imágenes</button>
	    </td>
	  </tr>
	</table>
	<script>
	(function($){
	  var ids = <?php echo wp_json_encode( array_values( $ids ) ); ?>;
	  function sync(){ $('#galeria_imagenes').val(JSON.stringify(ids)); }
	  $(document).on('click','.mides-gremove',function(){
	    var id = parseInt($(this).closest('.mides-gthumb').data('id'));
	    ids = ids.filter(function(i){ return i !== id; });
	    $(this).closest('.mides-gthumb').remove();
	    sync();
	  });
	  var frame;
	  $('#mides-galeria-add').on('click',function(){
	    if(!frame){ frame = wp.media({ title:'Seleccionar imágenes', multiple:true, library:{type:'image'} }); }
	    frame.on('select',function(){
	      frame.state().get('selection').each(function(a){
	        if(ids.indexOf(a.id)===-1){
	          ids.push(a.id);
	          var src = a.attributes.sizes && a.attributes.sizes.thumbnail ? a.attributes.sizes.thumbnail.url : a.attributes.url;
	          $('#mides-galeria-preview').append('<div class="mides-gthumb" data-id="'+a.id+'" style="position:relative"><img src="'+src+'" style="width:72px;height:72px;object-fit:cover;border-radius:4px;display:block"><button type="button" class="mides-gremove" style="position:absolute;top:2px;right:2px;background:#c00;color:#fff;border:none;border-radius:50%;width:18px;height:18px;cursor:pointer;font-size:11px;line-height:1;padding:0">&times;</button></div>');
	        }
	      });
	      sync();
	    });
	    frame.open();
	  });
	})(jQuery);
	</script>
	<?php
}

add_action( 'save_post_noticia', function ( int $post_id ): void {
	if ( ! isset( $_POST['mides_noticia_nonce'] ) ) return;
	if ( ! wp_verify_nonce( $_POST['mides_noticia_nonce'], 'mides_noticia_meta_save' ) ) return;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	update_post_meta( $post_id, '_numero_comunicado', sanitize_text_field( $_POST['numero_comunicado'] ?? '' ) );

	$raw = stripslashes( $_POST['galeria_imagenes'] ?? '[]' );
	$decoded = json_decode( $raw, true );
	$ids = is_array( $decoded ) ? array_values( array_filter( array_map( 'intval', $decoded ) ) ) : [];
	update_post_meta( $post_id, '_galeria_imagenes', wp_json_encode( $ids ) );
} );

/* ── Registrar el bloque Hero Carrusel ──────────────────────────────────── */
add_action( 'init', function () {
	register_block_type(
		get_stylesheet_directory() . '/blocks/hero-carousel',
		[ 'render_callback' => 'mides_render_hero_carousel' ]
	);
	register_block_type(
		get_stylesheet_directory() . '/blocks/programas-sociales',
		[ 'render_callback' => 'mides_render_programas_sociales' ]
	);
	register_block_type(
		get_stylesheet_directory() . '/blocks/banner-gratuitos',
		[ 'render_callback' => 'mides_render_banner_gratuitos' ]
	);
	register_block_type(
		get_stylesheet_directory() . '/blocks/noticias-recientes',
		[ 'render_callback' => 'mides_render_noticias_recientes' ]
	);
	register_block_type(
		get_stylesheet_directory() . '/blocks/institucional',
		[ 'render_callback' => 'mides_render_institucional' ]
	);
	register_block_type(
		get_stylesheet_directory() . '/blocks/hero-interior',
		[ 'render_callback' => 'mides_render_hero_interior' ]
	);
	register_block_type(
		get_stylesheet_directory() . '/blocks/about-block',
		[ 'render_callback' => 'mides_render_about_block' ]
	);
	register_block_type(
		get_stylesheet_directory() . '/blocks/autoridades',
		[ 'render_callback' => 'mides_render_autoridades' ]
	);
	register_block_type(
		get_stylesheet_directory() . '/blocks/mesas-tecnicas',
		[ 'render_callback' => 'mides_render_mesas_tecnicas' ]
	);
	register_block_type(
		get_stylesheet_directory() . '/blocks/documentos-lista',
		[ 'render_callback' => 'mides_render_documentos_lista' ]
	);
} );

function mides_render_hero_carousel( array $attributes ): string {
	wp_enqueue_script(
		'mides-hero-carousel',
		get_stylesheet_directory_uri() . '/js/hero-carousel.js',
		[],
		wp_get_theme()->get( 'Version' ),
		true
	);
	ob_start();
	include get_stylesheet_directory() . '/blocks/hero-carousel/render.php';
	return ob_get_clean();
}

function mides_render_programas_sociales( array $attributes ): string {
	wp_enqueue_script(
		'mides-modal-programas',
		get_stylesheet_directory_uri() . '/js/modal-programas.js',
		[],
		wp_get_theme()->get( 'Version' ),
		true
	);
	ob_start();
	include get_stylesheet_directory() . '/blocks/programas-sociales/render.php';
	return ob_get_clean();
}

function mides_render_banner_gratuitos( array $attributes ): string {
	ob_start();
	include get_stylesheet_directory() . '/blocks/banner-gratuitos/render.php';
	return ob_get_clean();
}

function mides_render_hero_interior( array $attributes ): string {
	ob_start();
	include get_stylesheet_directory() . '/blocks/hero-interior/render.php';
	return ob_get_clean();
}

function mides_render_about_block( array $attributes ): string {
	ob_start();
	include get_stylesheet_directory() . '/blocks/about-block/render.php';
	return ob_get_clean();
}

function mides_render_autoridades( array $attributes ): string {
	ob_start();
	include get_stylesheet_directory() . '/blocks/autoridades/render.php';
	return ob_get_clean();
}

function mides_render_mesas_tecnicas( array $attributes ): string {
	ob_start();
	include get_stylesheet_directory() . '/blocks/mesas-tecnicas/render.php';
	return ob_get_clean();
}

function mides_render_institucional( array $attributes ): string {
	wp_enqueue_script(
		'mides-institucional-flip',
		get_stylesheet_directory_uri() . '/js/institucional-flip.js',
		[],
		wp_get_theme()->get( 'Version' ),
		true
	);
	ob_start();
	include get_stylesheet_directory() . '/blocks/institucional/render.php';
	return ob_get_clean();
}

function mides_render_documentos_lista( array $attributes ): string {
	ob_start();
	include get_stylesheet_directory() . '/blocks/documentos-lista/render.php';
	return ob_get_clean();
}

function mides_render_noticias_recientes( array $attributes ): string {
	wp_enqueue_script(
		'mides-noticias-carousel',
		get_stylesheet_directory_uri() . '/js/noticias-carousel.js',
		[],
		wp_get_theme()->get( 'Version' ),
		true
	);
	ob_start();
	include get_stylesheet_directory() . '/blocks/noticias-recientes/render.php';
	return ob_get_clean();
}

/* ── Helper: ícono de enlace externo para tarjetas de trámites ──────────── */
function mides_tramite_link_icon(): string {
	return '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>';
}

/* ── Callback de comentario — avatar con iniciales ──────────────────────── */
function mides_comment_template( WP_Comment $comment, array $args, int $depth ): void {
	$author   = get_comment_author( $comment );
	$words    = preg_split( '/\s+/', trim( $author ) );
	$initials = strtoupper( mb_substr( $words[0], 0, 1 ) . ( isset( $words[1] ) ? mb_substr( $words[1], 0, 1 ) : '' ) );
	$date     = get_comment_date( 'j \d\e F \d\e Y', $comment );
	$text     = get_comment_text( $comment );
	?>
	<div id="comment-<?php echo $comment->comment_ID; ?>" class="comment<?php echo $comment->comment_approved == '0' ? ' comment--pending' : ''; ?>">
	  <div class="comment__avatar"><?php echo esc_html( $initials ); ?></div>
	  <div class="comment__body">
	    <div class="comment__header">
	      <span class="comment__author"><?php echo esc_html( $author ); ?></span>
	      <span class="comment__date"><?php echo esc_html( $date ); ?></span>
	    </div>
	    <?php if ( $comment->comment_approved == '0' ) : ?>
	      <p style="font-size:0.8rem;color:#F2A119;margin:0 0 6px">Tu comentario está pendiente de aprobación.</p>
	    <?php endif; ?>
	    <p class="comment__text"><?php echo wp_kses( $text, [ 'strong' => [], 'em' => [], 'a' => [ 'href' => [] ] ] ); ?></p>
	  </div>
	</div>
	<?php
}

/* ── Pre-Header antes del <header> de Astra ────────────────────────────── */
add_action( 'astra_header_before', function () {
	if ( ! has_nav_menu( 'menu-pre-header' ) ) return;
	echo '<div class="pre-header"><div class="container pre-header__inner">';
	wp_nav_menu( [ 'theme_location' => 'menu-pre-header', 'container' => false, 'menu_class' => 'pre-header__nav', 'fallback_cb' => false, 'depth' => 1 ] );
	echo '</div></div>';
} );

/* ── Customizer: redes sociales y botón denuncia ────────────────────────── */
add_action( 'customize_register', function ( WP_Customize_Manager $wpc ) {
	$wpc->add_section( 'mides_general', [ 'title' => 'Configuración MIDES', 'priority' => 29 ] );
	$wpc->add_setting( 'url_denuncia', [ 'default' => '#', 'sanitize_callback' => 'esc_url_raw' ] );
	$wpc->add_control( 'url_denuncia', [ 'label' => 'URL botón ¡DENUNCIA!', 'section' => 'mides_general', 'type' => 'url' ] );

	$wpc->add_section( 'mides_social', [ 'title' => 'Redes Sociales MIDES', 'priority' => 30 ] );
	foreach ( [ 'social_facebook' => 'Facebook', 'social_x' => 'X (Twitter)', 'social_youtube' => 'YouTube', 'social_instagram' => 'Instagram', 'social_tiktok' => 'TikTok' ] as $key => $label ) {
		$wpc->add_setting( $key, [ 'default' => '#', 'sanitize_callback' => 'esc_url_raw' ] );
		$wpc->add_control( $key, [ 'label' => $label, 'section' => 'mides_social', 'type' => 'url' ] );
	}

	$wpc->add_section( 'mides_footer', [ 'title' => 'Footer MIDES', 'priority' => 31 ] );
	$footer_fields = [
		'footer_direccion' => [ 'label' => 'Dirección',           'default' => '5a. Av. 8-78 Zona 9 Guatemala, Edificio Plaza Lauderdale', 'type' => 'textarea' ],
		'footer_horario'   => [ 'label' => 'Horario de atención', 'default' => 'lunes a viernes, 8:00 a 16:00 horas',                      'type' => 'textarea' ],
		'footer_pbx'       => [ 'label' => 'PBX',                 'default' => '+502 2300-5400',                                            'type' => 'text' ],
		'url_quejas'       => [ 'label' => 'URL Quejas y Reclamos', 'default' => '#',                                                       'type' => 'url' ],
	];
	foreach ( $footer_fields as $key => $cfg ) {
		$sanitize = $cfg['type'] === 'url' ? 'esc_url_raw' : 'sanitize_textarea_field';
		$wpc->add_setting( $key, [ 'default' => $cfg['default'], 'sanitize_callback' => $sanitize ] );
		$wpc->add_control( $key, [ 'label' => $cfg['label'], 'section' => 'mides_footer', 'type' => $cfg['type'] ] );
	}
} );
