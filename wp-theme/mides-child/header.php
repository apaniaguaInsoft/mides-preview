<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- ═══════════════════════════════════════════
     PRE-HEADER — barra de aliados (no sticky)
═══════════════════════════════════════════ -->
<?php if ( has_nav_menu( 'menu-pre-header' ) ) : ?>
<div class="pre-header">
  <div class="container pre-header__inner">
    <?php
    wp_nav_menu( [
      'theme_location' => 'menu-pre-header',
      'container'      => false,
      'menu_class'     => 'pre-header__nav',
      'fallback_cb'    => false,
      'depth'          => 1,
    ] );
    ?>
  </div>
</div>
<?php endif; ?>

<!-- ═══════════════════════════════════════════
     HEADER — sticky
═══════════════════════════════════════════ -->
<header class="header">

  <div class="header__top">
    <div class="container header__top-inner">

      <!-- Hamburger (visible solo en móvil) -->
      <button class="hamburger" id="hamburger" aria-label="Menú" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>

      <!-- Logo MIDES -->
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="header__logo-link">
        <img
          src="<?php echo esc_url( get_stylesheet_directory_uri() . '/img/MIDES-HOR-COLOR.svg' ); ?>"
          alt="<?php bloginfo( 'name' ); ?>"
          class="header__logo-img"
        />
      </a>

      <!-- Derecha: redes + buscador + denuncia -->
      <div class="header__top-right">

        <div class="header__socials">
          <span class="header__social-label">Síguenos</span>
          <a href="<?php echo esc_url( get_theme_mod( 'social_facebook', '#' ) ); ?>" aria-label="Facebook" class="social-icon" target="_blank" rel="noopener">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
          </a>
          <a href="<?php echo esc_url( get_theme_mod( 'social_x', '#' ) ); ?>" aria-label="X" class="social-icon" target="_blank" rel="noopener">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
          </a>
          <a href="<?php echo esc_url( get_theme_mod( 'social_youtube', '#' ) ); ?>" aria-label="YouTube" class="social-icon" target="_blank" rel="noopener">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M22.54 6.42a2.78 2.78 0 00-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 001.46 6.42 29 29 0 001 12a29 29 0 00.46 5.58A2.78 2.78 0 003.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 001.95-1.95A29 29 0 0023 12a29 29 0 00-.46-5.58zM9.75 15.02V8.98L15.5 12l-5.75 3.02z"/></svg>
          </a>
          <a href="<?php echo esc_url( get_theme_mod( 'social_instagram', '#' ) ); ?>" aria-label="Instagram" class="social-icon" target="_blank" rel="noopener">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
          </a>
          <a href="<?php echo esc_url( get_theme_mod( 'social_tiktok', '#' ) ); ?>" aria-label="TikTok" class="social-icon" target="_blank" rel="noopener">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.18 8.18 0 004.78 1.52V6.72a4.85 4.85 0 01-1.01-.03z"/></svg>
          </a>
        </div>

        <div class="header__search">
          <input type="text" placeholder="Buscar…" class="header__search-input" />
          <button class="header__search-btn" aria-label="Buscar">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          </button>
        </div>

        <a href="<?php echo esc_url( get_theme_mod( 'url_denuncia', '#' ) ); ?>" class="btn-denuncia">¡DENUNCIA!</a>

      </div>

    </div>
  </div>

  <!-- fila de navegación -->
  <div class="header__nav-bar" id="nav-bar">
    <div class="container header__nav-inner">
      <nav class="header__nav" id="main-nav" aria-label="<?php esc_attr_e( 'Menú principal', 'mides-child' ); ?>">
        <?php
        wp_nav_menu( [
          'theme_location' => 'menu-principal',
          'container'      => false,
          'menu_class'     => 'nav-list',
          'fallback_cb'    => false,
          'depth'          => 1,
        ] );
        ?>
      </nav>
    </div>
  </div>

</header>
