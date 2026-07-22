<?php
/**
 * Template Name: MIDES Contacto
 * Template Post Type: page
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$linea_svg = get_stylesheet_directory_uri() . '/img/Linea-Resaltadora.svg';
$loc_svg   = get_stylesheet_directory_uri() . '/img/location.svg';
$tel_svg   = get_stylesheet_directory_uri() . '/img/phone.svg';
$mail_svg  = get_stylesheet_directory_uri() . '/img/email.svg';

get_header();
?>

<!-- HERO -->
<section class="contacto-hero">
  <div class="contacto-hero__overlay"></div>
  <div class="container contacto-hero__content">
    <h1 class="contacto-hero__title">CONTÁCTANOS</h1>
    <img src="<?php echo esc_url( $linea_svg ); ?>" alt="" class="contacto-hero__line" />
    <p class="contacto-hero__sub">Estamos aquí para servirte. Tu bienestar es nuestra misión.</p>
  </div>
</section>

<!-- TARJETAS DE CONTACTO -->
<section class="contacto-info">
  <div class="container">
    <div class="contacto-info__grid">

      <div class="contacto-card">
        <div class="contacto-card__icon">
          <img src="<?php echo esc_url( $loc_svg ); ?>" alt="Ubicación" />
        </div>
        <h3 class="contacto-card__title">Dirección</h3>
        <p class="contacto-card__text">5a. Avenida 8-78 Zona 9<br>Guatemala, Guatemala</p>
        <p class="contacto-card__text contacto-card__text--light">Edificio Plaza Lauderdale</p>
        <p class="contacto-card__text contacto-card__text--light">Lunes a Viernes, 8:00 — 16:00 hrs</p>
      </div>

      <div class="contacto-card">
        <div class="contacto-card__icon">
          <img src="<?php echo esc_url( $tel_svg ); ?>" alt="Teléfono" />
        </div>
        <h3 class="contacto-card__title">Teléfonos</h3>
        <a href="tel:+50223005400" class="contacto-card__text contacto-card__text--phone">+502 2300-5400</a>
        <a href="tel:+50223026900" class="contacto-card__text contacto-card__text--phone">+502 2302-6900</a>
        <a href="tel:+50223026800" class="contacto-card__text contacto-card__text--phone">+502 2302-6800</a>
      </div>

      <div class="contacto-card">
        <div class="contacto-card__icon">
          <img src="<?php echo esc_url( $mail_svg ); ?>" alt="Correo electrónico" />
        </div>
        <h3 class="contacto-card__title">Correo Electrónico</h3>
        <a href="mailto:info@mides.gob.gt" class="contacto-card__text contacto-card__text--phone">info@mides.gob.gt</a>
      </div>

    </div>
  </div>
</section>

<!-- MAPA + FORMULARIO -->
<section class="contacto-main">
  <div class="container contacto-main__grid">

    <!-- Mapa -->
    <div class="contacto-map">
      <div class="contacto-map__header">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><circle cx="16" cy="11" r="4"/><path d="M24 15c-3 7-8 15-8 15s-5-8-8-15s2-13 8-13s11 6 8 13"/></svg>
        <span>Nuestra Ubicación</span>
      </div>
      <div class="contacto-map__frame">
        <iframe
          title="Ubicación MIDES"
          src="https://maps.google.com/maps?q=Ministerio+de+Desarrollo+Social+MIDES+5a+Avenida+8-78+Zona+9+Guatemala&output=embed&z=16"
          width="100%" height="100%" style="border:0;"
          allowfullscreen="" loading="lazy"
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>
      <div class="contacto-map__footer">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="#192854"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0118 0z"/></svg>
        <span>5a. Av. 8-78 Zona 9, Guatemala — Edificio Plaza Lauderdale</span>
      </div>
    </div>

    <!-- Formulario WPForms -->
    <div class="contacto-form-wrap">
      <div class="contacto-form__header">
        <h2 class="contacto-form__title">Envíanos un<br><span class="title-highlight">Mensaje<img src="<?php echo esc_url( $linea_svg ); ?>" alt="" class="section-underline" /></span></h2>
        <p class="contacto-form__desc">Tu dirección de correo electrónico no será publicada. Los campos requeridos están marcados con <span class="req">*</span></p>
      </div>
      <?php echo do_shortcode( '[wpforms id="60"]' ); ?>
    </div>

  </div>
</section>

<?php get_footer(); ?>
