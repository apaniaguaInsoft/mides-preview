<?php
$slides = get_post_meta( get_the_ID(), '_hero_slides', true ) ?: [];

if ( empty( $slides ) ) {
	return;
}

$whiteline = get_stylesheet_directory_uri() . '/img/whiteline.svg';
$fallback  = get_stylesheet_directory_uri() . '/img/banner1.svg';
?>
<section class="hero">

  <?php foreach ( $slides as $i => $slide ) :
    $bg = $slide['bg_id'] ? wp_get_attachment_image_url( (int) $slide['bg_id'], 'full' ) : $fallback;
  ?>
  <div class="hero__slide<?php echo $i === 0 ? ' active' : ''; ?>">
    <div class="hero__bg" style="background-image:url('<?php echo esc_url( $bg ); ?>')"></div>
    <div class="container hero__content">
      <?php if ( ! empty( $slide['pre'] ) ) : ?>
        <p class="hero__pre"><?php echo esc_html( $slide['pre'] ); ?></p>
      <?php endif; ?>
      <?php if ( ! empty( $slide['title'] ) ) : ?>
        <h1 class="hero__title"><?php echo esc_html( $slide['title'] ); ?></h1>
      <?php endif; ?>
      <?php if ( ! empty( $slide['sub1'] ) ) : ?>
        <p class="hero__sub"><?php echo esc_html( $slide['sub1'] ); ?></p>
      <?php endif; ?>
      <?php if ( ! empty( $slide['sub2'] ) ) : ?>
        <p class="hero__sub hero__sub--bold"><?php echo esc_html( $slide['sub2'] ); ?></p>
      <?php endif; ?>
      <img src="<?php echo esc_url( $whiteline ); ?>" alt="" class="hero__whiteline" />
    </div>
  </div>
  <?php endforeach; ?>

  <button class="hero__arrow hero__arrow--prev" aria-label="Anterior">&#8249;</button>
  <button class="hero__arrow hero__arrow--next" aria-label="Siguiente">&#8250;</button>

  <div class="hero__dots">
    <?php foreach ( $slides as $i => $_ ) : ?>
      <button class="hero__dot<?php echo $i === 0 ? ' active' : ''; ?>" aria-label="Ir al slide <?php echo $i + 1; ?>"></button>
    <?php endforeach; ?>
  </div>

</section>
