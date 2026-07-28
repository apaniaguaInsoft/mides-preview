<?php
$titulo = $attributes['titulo'] ?? 'Cómo funciona';
$pasos  = $attributes['pasos']  ?? [];
?>
<section class="como-funciona" id="como-funciona">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title"><?php echo esc_html( $titulo ); ?></h2>
    </div>
    <div class="como-funciona__grid">
      <?php foreach ( $pasos as $paso ) : ?>
      <div class="como-funciona__paso">
        <div class="como-funciona__numero">
          <?php echo esc_html( $paso['numero'] ?? '' ); ?>
        </div>
        <div class="como-funciona__contenido">
          <h3 class="como-funciona__paso-titulo">
            <?php echo esc_html( $paso['titulo'] ?? '' ); ?>
          </h3>
          <p class="como-funciona__paso-desc">
            <?php echo esc_html( $paso['descripcion'] ?? '' ); ?>
          </p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
