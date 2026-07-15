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
	] );
} );

/* ── Encolar estilos del tema ───────────────────────────────────────────── */
add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'astra-child-style', get_stylesheet_uri(), [ 'astra-theme-css' ], wp_get_theme()->get( 'Version' ) );
	wp_enqueue_style( 'mides-styles', get_stylesheet_directory_uri() . '/mides.css', [ 'astra-child-style' ], wp_get_theme()->get( 'Version' ) );
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
	ob_start();
	include get_stylesheet_directory() . '/blocks/programas-sociales/render.php';
	return ob_get_clean();
}

function mides_render_banner_gratuitos( array $attributes ): string {
	ob_start();
	include get_stylesheet_directory() . '/blocks/banner-gratuitos/render.php';
	return ob_get_clean();
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
} );
