<?php
$dir      = get_stylesheet_directory_uri() . '/img/';

$titulo_dir  = $attributes['tituloDir']  ?? 'Dirección';
$titulo_tel  = $attributes['tituloTel']  ?? 'Teléfonos';
$titulo_mail = $attributes['tituloMail'] ?? 'Correo Electrónico';

// Íconos: preferir media library; caer en URL guardada; caer en SVG del tema
if ( ! function_exists( 'mides_contacto_icon' ) ) {
    function mides_contacto_icon( $id, $url, $fallback ) {
        $resolved = $id ? ( wp_get_attachment_image_url( $id, 'full' ) ?: $url ) : $url;
        return $resolved ?: $fallback;
    }
}
$loc_icon  = mides_contacto_icon( $attributes['iconDirId']  ?? 0, $attributes['iconDirUrl']  ?? '', $dir . 'location.svg' );
$tel_icon  = mides_contacto_icon( $attributes['iconTelId']  ?? 0, $attributes['iconTelUrl']  ?? '', $dir . 'phone.svg' );
$mail_icon = mides_contacto_icon( $attributes['iconMailId'] ?? 0, $attributes['iconMailUrl'] ?? '', $dir . 'email.svg' );

$d1  = $attributes['direccion1'] ?? '5a. Avenida 8-78 Zona 9';
$d2  = $attributes['direccion2'] ?? 'Guatemala, Guatemala';
$ed  = $attributes['edificio']   ?? 'Edificio Plaza Lauderdale';
$hr  = $attributes['horario']    ?? 'Lunes a Viernes, 8:00 — 16:00 hrs';
$t1  = $attributes['telefono1']  ?? '+502 2300-5400';
$t2  = $attributes['telefono2']  ?? '+502 2302-6900';
$t3  = $attributes['telefono3']  ?? '+502 2302-6800';
$cor = $attributes['correo']     ?? 'info@mides.gob.gt';

$tel_href = function( $n ) {
    return 'tel:+502' . preg_replace( '/[^0-9]/', '', str_replace( '+502 ', '', $n ) );
};
?>
<section class="contacto-info">
  <div class="container">
    <div class="contacto-info__grid">

      <div class="contacto-card">
        <div class="contacto-card__icon">
          <img src="<?php echo esc_url( $loc_icon ); ?>" alt="Ubicación" />
        </div>
        <h3 class="contacto-card__title"><?php echo esc_html( $titulo_dir ); ?></h3>
        <p class="contacto-card__text"><?php echo esc_html( $d1 ); ?><br><?php echo esc_html( $d2 ); ?></p>
        <p class="contacto-card__text contacto-card__text--light"><?php echo esc_html( $ed ); ?></p>
        <p class="contacto-card__text contacto-card__text--light"><?php echo esc_html( $hr ); ?></p>
      </div>

      <div class="contacto-card">
        <div class="contacto-card__icon">
          <img src="<?php echo esc_url( $tel_icon ); ?>" alt="Teléfono" />
        </div>
        <h3 class="contacto-card__title"><?php echo esc_html( $titulo_tel ); ?></h3>
        <a href="<?php echo esc_attr( $tel_href( $t1 ) ); ?>" class="contacto-card__text contacto-card__text--phone"><?php echo esc_html( $t1 ); ?></a>
        <a href="<?php echo esc_attr( $tel_href( $t2 ) ); ?>" class="contacto-card__text contacto-card__text--phone"><?php echo esc_html( $t2 ); ?></a>
        <a href="<?php echo esc_attr( $tel_href( $t3 ) ); ?>" class="contacto-card__text contacto-card__text--phone"><?php echo esc_html( $t3 ); ?></a>
      </div>

      <div class="contacto-card">
        <div class="contacto-card__icon">
          <img src="<?php echo esc_url( $mail_icon ); ?>" alt="Correo electrónico" />
        </div>
        <h3 class="contacto-card__title"><?php echo esc_html( $titulo_mail ); ?></h3>
        <a href="mailto:<?php echo esc_attr( $cor ); ?>" class="contacto-card__text contacto-card__text--phone"><?php echo esc_html( $cor ); ?></a>
      </div>

    </div>
  </div>
</section>
