<?php
/**
 * MIDES Child — Footer
 */
$logo_svg   = get_stylesheet_directory_uri() . '/img/MIDES-VERT-W.svg';
$linea_svg  = get_stylesheet_directory_uri() . '/img/line3.svg';
$url_quejas = get_theme_mod( 'url_quejas', '#' );
$direccion  = get_theme_mod( 'footer_direccion', '5a. Av. 8-78 Zona 9 Guatemala, Edificio Plaza Lauderdale' );
$horario    = get_theme_mod( 'footer_horario', 'lunes a viernes, 8:00 a 16:00 horas' );
$pbx        = get_theme_mod( 'footer_pbx', '+502 2300-5400' );

$socials = [
	'social_facebook'  => [ 'label' => 'Facebook',  'svg' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>' ],
	'social_x'         => [ 'label' => 'X',         'svg' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>' ],
	'social_youtube'   => [ 'label' => 'YouTube',   'svg' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M22.54 6.42a2.78 2.78 0 00-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 001.46 6.42 29 29 0 001 12a29 29 0 00.46 5.58A2.78 2.78 0 003.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 001.95-1.95A29 29 0 0023 12a29 29 0 00-.46-5.58zM9.75 15.02V8.98L15.5 12l-5.75 3.02z"/></svg>' ],
	'social_instagram' => [ 'label' => 'Instagram', 'svg' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>' ],
	'social_tiktok'    => [ 'label' => 'TikTok',    'svg' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.18 8.18 0 004.78 1.52V6.72a4.85 4.85 0 01-1.01-.03z"/></svg>' ],
];
?>

<footer class="footer">

  <div class="footer__banner">
    <div class="container">
      <h2 class="footer__banner-title">
        ENTE RECTOR DE LOS PROGRAMAS
        <span class="title-highlight">
          SOCIALES<img src="<?php echo esc_url( $linea_svg ); ?>" alt="" class="section-underline" />
        </span>
      </h2>
    </div>
  </div>

  <div class="footer__main">
    <div class="container footer__grid">

      <!-- Logo + Quejas -->
      <div class="footer__col footer__col--logo">
        <img src="<?php echo esc_url( $logo_svg ); ?>" alt="MIDES" class="footer__logo" />
        <a href="<?php echo esc_url( $url_quejas ); ?>" class="footer__quejas-btn">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
          Quejas y Reclamos
        </a>
      </div>

      <!-- Contacto -->
      <div class="footer__col footer__col--contact">
        <ul class="footer__contact">
          <li>
            <strong>Dirección:</strong><br>
            <?php echo nl2br( esc_html( $direccion ) ); ?>
          </li>
          <li>
            <strong>Horario de atención:</strong><br>
            <?php echo nl2br( esc_html( $horario ) ); ?>
          </li>
          <li>
            <strong>PBX:</strong><br>
            <?php echo esc_html( $pbx ); ?>
          </li>
        </ul>
      </div>

      <!-- Links rápidos -->
      <div class="footer__col footer__col--links">
        <?php if ( has_nav_menu( 'menu-footer' ) ) : ?>
          <?php wp_nav_menu( [
            'theme_location' => 'menu-footer',
            'container'      => false,
            'menu_class'     => 'footer__links',
            'fallback_cb'    => false,
            'depth'          => 1,
          ] ); ?>
        <?php else : ?>
          <ul class="footer__links">
            <li><a href="#">Fondo de Desarrollo Social</a></li>
            <li><a href="#">Fondo de Protección Social</a></li>
            <li><a href="#">Registro Social de Hogares</a></li>
            <li><a href="#">Acceso a la Información Pública</a></li>
            <li><a href="#">Mano a Mano</a></li>
            <li><a href="#">Denuncia</a></li>
            <li><a href="#">GEDS</a></li>
          </ul>
        <?php endif; ?>
      </div>

    </div>
  </div>

  <div class="footer__bottom">
    <div class="container footer__bottom-inner">
      <span class="footer__midesgt">Síguenos</span>
      <div class="footer__socials">
        <?php foreach ( $socials as $key => $social ) :
          $url = get_theme_mod( $key, '#' );
        ?>
          <a href="<?php echo esc_url( $url ); ?>" class="footer__social" aria-label="<?php echo esc_attr( $social['label'] ); ?>">
            <?php echo $social['svg']; ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

</footer>

<?php wp_footer(); ?>
</body>
</html>
