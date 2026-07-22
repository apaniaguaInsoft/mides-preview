<?php
/**
 * Detalle de Galería fotográfica.
 */
$archive_url = get_post_type_archive_link( 'noticia' );
$linea_svg   = get_stylesheet_directory_uri() . '/img/Linea-Resaltadora.svg';

$raw_ids = get_post_meta( get_the_ID(), '_galeria_imagenes', true );
$ids     = $raw_ids ? json_decode( $raw_ids, true ) : [];
$ids     = is_array( $ids ) ? array_filter( array_map( 'intval', $ids ) ) : [];

$tipos = get_the_terms( get_the_ID(), 'tipo_noticia' ) ?: [];

$bk_svg  = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>';
$dl_svg  = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>';
$dl_sm   = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>';
?>

<section class="contacto-hero">
  <div class="contacto-hero__overlay"></div>
  <div class="container contacto-hero__content">
    <div class="article-hero__tags" style="margin-bottom:12px">
      <?php foreach ( $tipos as $t ) : ?>
        <span class="article-hero__tag"><?php echo esc_html( $t->name ); ?></span>
      <?php endforeach; ?>
    </div>
    <h1 class="contacto-hero__title"><?php the_title(); ?></h1>
    <img src="<?php echo esc_url( $linea_svg ); ?>" alt="" class="contacto-hero__line" />
    <p class="contacto-hero__sub"><?php echo esc_html( get_the_excerpt() ); ?> · <?php echo get_the_date( 'j \d\e F \d\e Y' ); ?></p>
  </div>
</section>

<section class="gallery-section">
  <div class="container">

    <div class="gallery-header">

      <a href="<?php echo esc_url( $archive_url ); ?>" class="gallery-back">
        <?php echo $bk_svg; ?> Regresar a noticias
      </a>

      <div class="gallery-meta">
        <span class="news-tag">Galería</span>
        <span style="font-size:0.8rem;color:#718096"><?php echo get_the_date( 'j \d\e F \d\e Y' ); ?> · <?php echo count( $ids ); ?> foto<?php echo count( $ids ) !== 1 ? 's' : ''; ?></span>
      </div>

      <h2 class="gallery-title"><?php the_title(); ?></h2>

      <?php $desc = get_the_content(); if ( $desc ) : ?>
        <div class="gallery-desc"><?php echo wp_kses_post( wpautop( $desc ) ); ?></div>
      <?php endif; ?>

      <?php if ( ! empty( $ids ) ) : ?>
        <button type="button" class="gallery-download-all" id="mides-dl-all" data-ids="<?php echo esc_attr( wp_json_encode( $ids ) ); ?>">
          <?php echo $dl_svg; ?> Descargar todas las imágenes
        </button>
      <?php endif; ?>

    </div>

    <?php if ( ! empty( $ids ) ) : ?>
      <div class="gallery-grid">
        <?php foreach ( array_values( $ids ) as $i => $id ) :
          $full_url  = wp_get_attachment_image_url( $id, 'full' );
          $large_url = wp_get_attachment_image_url( $id, 'large' ) ?: $full_url;
          if ( ! $full_url ) continue;
          $attachment = get_post( $id );
          $caption    = $attachment ? ( $attachment->post_excerpt ?: $attachment->post_title ) : '';
          $num        = str_pad( $i + 1, 2, '0', STR_PAD_LEFT );
          $filename   = sanitize_file_name( get_the_title() . '-' . $num . '.jpg' );
        ?>
          <div class="gallery-item">
            <span class="gallery-item__num"><?php echo $num; ?></span>
            <img src="<?php echo esc_url( $large_url ); ?>" alt="<?php echo esc_attr( $caption ); ?>" class="gallery-item__img" loading="lazy" />
            <div class="gallery-item__overlay">
              <?php if ( $caption ) : ?>
                <span class="gallery-item__caption"><?php echo esc_html( $caption ); ?></span>
              <?php endif; ?>
              <a href="<?php echo esc_url( $full_url ); ?>" download="<?php echo esc_attr( $filename ); ?>" class="gallery-download-btn">
                <?php echo $dl_sm; ?> Descargar
              </a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else : ?>
      <p style="text-align:center;color:#718096;padding:40px 0">No hay imágenes en esta galería todavía.</p>
    <?php endif; ?>

  </div>
</section>

<script>
document.getElementById('mides-dl-all') && document.getElementById('mides-dl-all').addEventListener('click', function() {
  var ids = JSON.parse(this.dataset.ids);
  var links = document.querySelectorAll('.gallery-download-btn');
  links.forEach(function(a, i) {
    setTimeout(function() {
      var tmp = document.createElement('a');
      tmp.href = a.href;
      tmp.download = a.download;
      document.body.appendChild(tmp);
      tmp.click();
      document.body.removeChild(tmp);
    }, i * 300);
  });
});
</script>
