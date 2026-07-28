<?php
/**
 * Plugin Name: MIDES Documentos
 * Description: Gestión de documentos oficiales del MIDES — CPT, categorías con color, etiquetas y selector de archivo.
 * Version:     1.1.0
 * Author:      MIDES / Insoft
 * Text Domain: mides-docs
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* ── Custom Post Type ───────────────────────────────────────────────── */
add_action( 'init', function (): void {
	register_post_type( 'documento', [
		'labels' => [
			'name'               => 'Documentos',
			'singular_name'      => 'Documento',
			'add_new'            => 'Agregar nuevo',
			'add_new_item'       => 'Agregar documento',
			'edit_item'          => 'Editar documento',
			'new_item'           => 'Nuevo documento',
			'view_item'          => 'Ver documento',
			'search_items'       => 'Buscar documentos',
			'not_found'          => 'No se encontraron documentos',
			'not_found_in_trash' => 'No hay documentos en la papelera',
			'all_items'          => 'Todos los documentos',
			'menu_name'          => 'Documentos',
		],
		'public'             => true,
		'publicly_queryable' => true,
		'has_archive'        => false,
		'rewrite'            => [ 'slug' => 'documentos', 'with_front' => false ],
		'show_in_rest'       => true,
		'supports'           => [ 'title', 'excerpt' ],
		'menu_icon'          => 'dashicons-media-document',
		'menu_position'      => 6,
	] );
} );

/* ── Taxonomías ─────────────────────────────────────────────────────── */
add_action( 'init', function (): void {
	register_taxonomy( 'categoria_documento', 'documento', [
		'labels' => [
			'name'              => 'Categorías',
			'singular_name'     => 'Categoría',
			'all_items'         => 'Todas las categorías',
			'edit_item'         => 'Editar categoría',
			'add_new_item'      => 'Agregar categoría',
			'search_items'      => 'Buscar categorías',
			'parent_item'       => 'Categoría padre',
			'parent_item_colon' => 'Categoría padre:',
		],
		'hierarchical'      => true,
		'public'            => true,
		'show_in_rest'      => true,
		'rewrite'           => [ 'slug' => 'categoria-doc' ],
		'show_admin_column' => true,
	] );

	register_taxonomy( 'etiqueta_documento', 'documento', [
		'labels' => [
			'name'          => 'Etiquetas',
			'singular_name' => 'Etiqueta',
			'all_items'     => 'Todas las etiquetas',
			'edit_item'     => 'Editar etiqueta',
			'add_new_item'  => 'Agregar etiqueta',
			'search_items'  => 'Buscar etiquetas',
		],
		'hierarchical'      => false,
		'public'            => true,
		'show_in_rest'      => true,
		'rewrite'           => [ 'slug' => 'etiqueta-doc' ],
		'show_admin_column' => true,
	] );
} );

/* ── Color picker en Categorías ─────────────────────────────────────── */
add_action( 'admin_enqueue_scripts', function ( string $hook ): void {
	if ( ! in_array( $hook, [ 'edit-tags.php', 'term.php' ], true ) ) return;
	if ( ( $_GET['taxonomy'] ?? '' ) !== 'categoria_documento' ) return;
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'wp-color-picker' );
} );

/* Formulario "Agregar categoría" */
add_action( 'categoria_documento_add_form_fields', function (): void {
	wp_nonce_field( 'mides_cat_color_save', 'mides_cat_color_nonce' );
	?>
	<div class="form-field">
		<label for="cat_color">Color de la tarjeta</label>
		<input type="text" id="cat_color" name="cat_color" value="#223E71" class="mides-cat-color-picker">
		<p class="description">Color que se mostrará en el encabezado de las tarjetas de esta categoría.</p>
	</div>
	<script>jQuery(function($){ $('.mides-cat-color-picker').wpColorPicker(); }); </script>
	<?php
} );

/* Formulario "Editar categoría" */
add_action( 'categoria_documento_edit_form_fields', function ( WP_Term $term ): void {
	$color = get_term_meta( $term->term_id, '_categoria_color', true ) ?: '#223E71';
	wp_nonce_field( 'mides_cat_color_save', 'mides_cat_color_nonce' );
	?>
	<tr class="form-field">
		<th><label for="cat_color">Color de la tarjeta</label></th>
		<td>
			<input type="text" id="cat_color" name="cat_color" value="<?php echo esc_attr( $color ); ?>" class="mides-cat-color-picker">
			<p class="description">Color del encabezado de las tarjetas de esta categoría.</p>
		</td>
	</tr>
	<script>jQuery(function($){ $('.mides-cat-color-picker').wpColorPicker(); }); </script>
	<?php
} );

/* Guardar color al crear o editar categoría */
$mides_save_cat_color = function ( int $term_id ): void {
	if ( ! isset( $_POST['mides_cat_color_nonce'] ) ) return;
	if ( ! wp_verify_nonce( $_POST['mides_cat_color_nonce'], 'mides_cat_color_save' ) ) return;
	$color = sanitize_hex_color( $_POST['cat_color'] ?? '' ) ?: '#223E71';
	update_term_meta( $term_id, '_categoria_color', $color );
};
add_action( 'created_categoria_documento', $mides_save_cat_color );
add_action( 'edited_categoria_documento',  $mides_save_cat_color );

/* Columna de color en la lista de categorías */
add_filter( 'manage_edit-categoria_documento_columns', function ( array $cols ): array {
	return array_merge( [ 'cat_color' => 'Color' ], $cols );
} );
add_filter( 'manage_categoria_documento_custom_column', function ( string $out, string $col, int $term_id ): string {
	if ( $col !== 'cat_color' ) return $out;
	$color = get_term_meta( $term_id, '_categoria_color', true ) ?: '#223E71';
	return '<span style="display:inline-block;width:24px;height:24px;border-radius:4px;background:' . esc_attr( $color ) . ';border:1px solid #ddd;vertical-align:middle"></span> ' . esc_html( $color );
}, 10, 3 );

/* ── Meta Box del documento ─────────────────────────────────────────── */
add_action( 'add_meta_boxes', function (): void {
	add_meta_box(
		'mides_doc_meta',
		'Datos del documento',
		'mides_docs_meta_box_cb',
		'documento',
		'normal',
		'high'
	);
} );

function mides_docs_meta_box_cb( WP_Post $post ): void {
	wp_nonce_field( 'mides_doc_meta_save', 'mides_doc_nonce' );
	wp_enqueue_media();

	$archivo_id   = (int) get_post_meta( $post->ID, '_doc_archivo_id',   true );
	$archivo_url  = get_post_meta( $post->ID, '_doc_archivo_url',  true );
	$anio         = get_post_meta( $post->ID, '_doc_anio',         true ) ?: (string) date( 'Y' );
	$tipo_archivo = get_post_meta( $post->ID, '_doc_tipo_archivo', true ) ?: 'PDF';
	$tipos        = [ 'PDF', 'DOCX', 'DOC', 'XLS', 'XLSX', 'PPTX', 'ZIP' ];
	?>
	<style>
	.mides-doc-meta { max-width:700px; }
	.mides-doc-meta table { border-collapse:collapse; width:100%; }
	.mides-doc-meta th { width:180px; text-align:left; padding:10px 12px 10px 0; font-weight:600; vertical-align:middle; color:#1e293b; }
	.mides-doc-meta td { padding:8px 0; }
	.mides-doc-file-row { display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
	.mides-doc-file-row input[type="url"] { flex:1; min-width:220px; }
	#mides-doc-file-name { font-size:12px; color:#64748b; margin:4px 0 0; font-style:italic; }
	.mides-required { color:#c0392b; margin-left:2px; }
	.mides-field-error { border-color:#c0392b !important; box-shadow:0 0 0 1px #c0392b !important; }
	.mides-error-msg { color:#c0392b; font-size:12px; display:none; margin-top:4px; }
	</style>

	<div class="mides-doc-meta">
	<table>
	  <tr>
	    <th><label>Archivo <span class="mides-required">*</span></label></th>
	    <td>
	      <div class="mides-doc-file-row">
	        <input type="hidden" id="doc_archivo_id"  name="doc_archivo_id"  value="<?php echo esc_attr( $archivo_id ); ?>">
	        <input type="url" id="doc_archivo_url" name="doc_archivo_url"
	               value="<?php echo esc_attr( $archivo_url ); ?>"
	               placeholder="URL del archivo (o usa el botón)" class="large-text" required>
	        <button type="button" id="mides-doc-upload-btn" class="button">Seleccionar</button>
	        <?php if ( $archivo_url ) : ?>
	          <a href="<?php echo esc_url( $archivo_url ); ?>" target="_blank" class="button button-secondary" id="mides-doc-view-btn">Ver archivo</a>
	        <?php else : ?>
	          <a href="#" target="_blank" class="button button-secondary" id="mides-doc-view-btn" style="display:none">Ver archivo</a>
	        <?php endif; ?>
	      </div>
	      <p id="mides-doc-file-name"><?php echo $archivo_id ? esc_html( basename( $archivo_url ) ) : ''; ?></p>
	      <p class="mides-error-msg" id="error-archivo">El archivo es obligatorio.</p>
	    </td>
	  </tr>
	  <tr>
	    <th><label for="doc_anio">Año <span class="mides-required">*</span></label></th>
	    <td>
	      <input type="number" id="doc_anio" name="doc_anio" value="<?php echo esc_attr( $anio ); ?>"
	             min="2000" max="2100" step="1" style="width:90px" required>
	      <p class="mides-error-msg" id="error-anio">El año es obligatorio.</p>
	    </td>
	  </tr>
	  <tr>
	    <th><label for="doc_tipo_archivo">Tipo de archivo</label></th>
	    <td>
	      <select id="doc_tipo_archivo" name="doc_tipo_archivo">
	        <?php foreach ( $tipos as $tipo ) : ?>
	          <option value="<?php echo esc_attr( $tipo ); ?>" <?php selected( $tipo_archivo, $tipo ); ?>>
	            <?php echo esc_html( $tipo ); ?>
	          </option>
	        <?php endforeach; ?>
	      </select>
	      <span style="font-size:12px;color:#64748b;margin-left:8px">Se detecta automáticamente al seleccionar el archivo</span>
	    </td>
	  </tr>
	</table>
	<p style="margin:12px 0 0;font-size:12px;color:#64748b">
	  <span class="mides-required">*</span> El título y la categoría también son obligatorios (se configuran en los paneles de la derecha).
	</p>
	</div>

	<script>
	(function ($) {
	  var EXT_MAP = {
	    pdf:'PDF', doc:'DOC', docx:'DOCX', xls:'XLS', xlsx:'XLSX',
	    ppt:'PPTX', pptx:'PPTX', zip:'ZIP', rar:'ZIP'
	  };

	  /* ── Selector de archivo ── */
	  var frame;
	  $('#mides-doc-upload-btn').on('click', function () {
	    if (!frame) {
	      frame = wp.media({ title:'Seleccionar documento', multiple:false, button:{text:'Usar este archivo'} });
	      frame.on('select', function () {
	        var a = frame.state().get('selection').first().toJSON();
	        var filename = a.filename || a.url.split('/').pop();
	        var ext = filename.split('.').pop().toLowerCase();

	        $('#doc_archivo_id').val(a.id);
	        $('#doc_archivo_url').val(a.url).removeClass('mides-field-error');
	        $('#mides-doc-file-name').text(filename);
	        $('#error-archivo').hide();

	        /* Actualizar botón "Ver archivo" */
	        $('#mides-doc-view-btn').attr('href', a.url).show();

	        /* Auto-detectar tipo de archivo */
	        var tipo = EXT_MAP[ext] || ext.toUpperCase() || 'PDF';
	        if ($('#doc_tipo_archivo option[value="' + tipo + '"]').length) {
	          $('#doc_tipo_archivo').val(tipo);
	        }
	      });
	    }
	    frame.open();
	  });

	  /* ── Validación antes de publicar / actualizar ── */
	  $('#post').on('submit', function (e) {
	    var ok = true;

	    /* Título */
	    var titulo = $('#title').val().trim();
	    if (!titulo) {
	      $('#title').addClass('mides-field-error');
	      ok = false;
	    } else {
	      $('#title').removeClass('mides-field-error');
	    }

	    /* Archivo */
	    var archivo = $('#doc_archivo_url').val().trim();
	    if (!archivo) {
	      $('#doc_archivo_url').addClass('mides-field-error');
	      $('#error-archivo').show();
	      ok = false;
	    } else {
	      $('#doc_archivo_url').removeClass('mides-field-error');
	      $('#error-archivo').hide();
	    }

	    /* Año */
	    var anio = $('#doc_anio').val().trim();
	    if (!anio) {
	      $('#doc_anio').addClass('mides-field-error');
	      $('#error-anio').show();
	      ok = false;
	    } else {
	      $('#doc_anio').removeClass('mides-field-error');
	      $('#error-anio').hide();
	    }

	    /* Categoría */
	    var hasCat = $('input[name="tax_input[categoria_documento][]"]:checked').length > 0;
	    if (!hasCat) {
	      $('#categoria_documento-all').closest('.categorydiv').css('outline','2px solid #c0392b');
	      ok = false;
	    } else {
	      $('#categoria_documento-all').closest('.categorydiv').css('outline','');
	    }

	    if (!ok) {
	      e.preventDefault();
	      $('html, body').animate({ scrollTop: 0 }, 300);
	      /* Aviso visual en la parte superior */
	      if (!$('#mides-validation-notice').length) {
	        $('#wpbody-content').prepend(
	          '<div id="mides-validation-notice" class="notice notice-error">' +
	          '<p><strong>Completa los campos obligatorios:</strong> Título, Archivo, Año y Categoría.</p>' +
	          '</div>'
	        );
	      }
	    }
	  });

	})(jQuery);
	</script>
	<?php
}

add_action( 'save_post_documento', function ( int $post_id ): void {
	if ( ! isset( $_POST['mides_doc_nonce'] ) ) return;
	if ( ! wp_verify_nonce( $_POST['mides_doc_nonce'], 'mides_doc_meta_save' ) ) return;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	update_post_meta( $post_id, '_doc_archivo_id',   (int) ( $_POST['doc_archivo_id'] ?? 0 ) );
	update_post_meta( $post_id, '_doc_archivo_url',  esc_url_raw( $_POST['doc_archivo_url'] ?? '' ) );
	update_post_meta( $post_id, '_doc_anio',         sanitize_text_field( $_POST['doc_anio'] ?? '' ) );
	update_post_meta( $post_id, '_doc_tipo_archivo', sanitize_text_field( $_POST['doc_tipo_archivo'] ?? 'PDF' ) );
} );

/* ── Columnas de lista en el admin ─────────────────────────────────── */
add_filter( 'manage_documento_posts_columns', function ( array $cols ): array {
	unset( $cols['date'] );
	return array_merge( $cols, [
		'doc_anio'    => 'Año',
		'doc_archivo' => 'Archivo',
		'date'        => 'Fecha',
	] );
} );

add_action( 'manage_documento_posts_custom_column', function ( string $col, int $post_id ): void {
	if ( $col === 'doc_anio' ) {
		echo esc_html( get_post_meta( $post_id, '_doc_anio', true ) ?: '—' );
	}
	if ( $col === 'doc_archivo' ) {
		$url = get_post_meta( $post_id, '_doc_archivo_url', true );
		if ( $url ) {
			$ext = strtoupper( pathinfo( $url, PATHINFO_EXTENSION ) );
			echo '<a href="' . esc_url( $url ) . '" target="_blank" style="text-decoration:none">';
			echo '<span class="dashicons dashicons-download" style="vertical-align:middle;color:#223e71;margin-right:4px"></span>';
			echo esc_html( $ext ?: 'Archivo' );
			echo '</a>';
		} else {
			echo '<span style="color:#aaa">Sin archivo</span>';
		}
	}
}, 10, 2 );

add_filter( 'manage_edit-documento_sortable_columns', function ( array $cols ): array {
	$cols['doc_anio'] = 'doc_anio';
	return $cols;
} );

add_action( 'pre_get_posts', function ( WP_Query $q ): void {
	if ( ! is_admin() || ! $q->is_main_query() ) return;
	if ( $q->get('post_type') !== 'documento' ) return;
	if ( $q->get('orderby') === 'doc_anio' ) {
		$q->set( 'meta_key', '_doc_anio' );
		$q->set( 'orderby',  'meta_value_num' );
	}
} );

/* ── REST API ───────────────────────────────────────────────────────── */
add_action( 'rest_api_init', function (): void {
	foreach ( [ '_doc_archivo_id', '_doc_archivo_url', '_doc_anio', '_doc_tipo_archivo' ] as $key ) {
		register_post_meta( 'documento', $key, [
			'show_in_rest'  => true,
			'single'        => true,
			'type'          => 'string',
			'auth_callback' => function () { return current_user_can( 'edit_posts' ); },
		] );
	}
} );
