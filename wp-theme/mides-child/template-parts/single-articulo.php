<?php
/**
 * Detalle de Noticia o Prensa.
 */
$tipos        = get_the_terms( get_the_ID(), 'tipo_noticia' ) ?: [];
$tipo_slugs   = wp_list_pluck( $tipos, 'slug' );
$is_prensa    = in_array( 'prensa', $tipo_slugs, true );
$numero_com   = get_post_meta( get_the_ID(), '_numero_comunicado', true );
$archive_url  = get_post_type_archive_link( 'noticia' );
$permalink    = get_permalink();
$title_enc    = rawurlencode( get_the_title() );
$url_enc      = rawurlencode( $permalink );

$words        = str_word_count( wp_strip_all_tags( get_the_content() ) );
$read_minutes = max( 1, round( $words / 200 ) );

/* Noticias relacionadas: mismos tipos, excluye el actual */
$related_args = [
	'post_type'      => 'noticia',
	'post_status'    => 'publish',
	'posts_per_page' => 3,
	'post__not_in'   => [ get_the_ID() ],
	'orderby'        => 'date',
	'order'          => 'DESC',
];
if ( ! empty( $tipo_slugs ) ) {
	$related_args['tax_query'] = [ [
		'taxonomy' => 'tipo_noticia',
		'field'    => 'slug',
		'terms'    => $tipo_slugs,
		'operator' => 'IN',
	] ];
}
$related = new WP_Query( $related_args );

$cal_svg = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>';
$usr_svg = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>';
$clk_svg = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>';
$bk_svg  = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>';
$cp_svg  = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>';
?>

<section class="article-hero">
  <div class="article-hero__content">
    <div class="container">
      <div class="article-hero__tags">
        <?php foreach ( $tipos as $t ) : ?>
          <span class="article-hero__tag"><?php echo esc_html( $t->name ); ?></span>
        <?php endforeach; ?>
      </div>
      <h1 class="article-hero__title"><?php the_title(); ?></h1>
      <div class="article-hero__meta">
        <span class="article-hero__meta-item"><?php echo $cal_svg; ?><?php echo get_the_date( 'j \d\e F \d\e Y' ); ?></span>
        <span class="article-hero__meta-item"><?php echo $usr_svg; ?>Por <?php the_author(); ?></span>
        <span class="article-hero__meta-item"><?php echo $clk_svg; ?><?php echo $read_minutes; ?> minuto<?php echo $read_minutes !== 1 ? 's' : ''; ?> de lectura</span>
      </div>
    </div>
  </div>
</section>

<section class="article-section">
  <div class="container">

    <a href="<?php echo esc_url( $archive_url ); ?>" class="article-back">
      <?php echo $bk_svg; ?> Regresar a noticias
    </a>

    <div class="article-layout">

      <!-- ── Contenido principal ── -->
      <main class="article-main">

        <?php if ( has_post_thumbnail() ) : ?>
          <figure class="article-featured">
            <?php the_post_thumbnail( 'large' ); ?>
            <?php $caption = get_the_post_thumbnail_caption(); if ( $caption ) : ?>
              <figcaption class="article-featured__caption"><?php echo esc_html( $caption ); ?></figcaption>
            <?php endif; ?>
          </figure>
        <?php endif; ?>

        <?php if ( $is_prensa && $numero_com ) : ?>
          <div style="background:#f0f4fb;border-left:4px solid #192854;padding:16px 20px;border-radius:0 8px 8px 0;margin-bottom:28px">
            <p style="font-family:'Altivo',sans-serif;font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#192854;margin:0 0 4px">Comunicado de Prensa · No. <?php echo esc_html( $numero_com ); ?></p>
            <p style="font-size:0.82rem;color:#4a5568;margin:0">Para publicación inmediata — <?php echo get_the_date( 'j \d\e F \d\e Y' ); ?></p>
          </div>
        <?php endif; ?>

        <div class="article-body entry-content">
          <?php the_content(); ?>
        </div>

      </main>

      <!-- ── Sidebar ── -->
      <aside class="article-sidebar">

        <!-- Card: Información -->
        <div class="sidebar-card">
          <p class="sidebar-card__title">Información</p>
          <div class="sidebar-meta__item">
            <span class="sidebar-meta__label">Fecha</span>
            <span class="sidebar-meta__value"><?php echo get_the_date( 'j \d\e F \d\e Y' ); ?></span>
          </div>
          <div class="sidebar-meta__item">
            <span class="sidebar-meta__label">Autor</span>
            <span class="sidebar-meta__value"><?php the_author(); ?></span>
          </div>
          <?php if ( ! empty( $tipos ) ) : ?>
          <div class="sidebar-meta__item">
            <span class="sidebar-meta__label">Categoría</span>
            <span class="sidebar-meta__value"><?php echo esc_html( implode( ' · ', wp_list_pluck( $tipos, 'name' ) ) ); ?></span>
          </div>
          <?php endif; ?>
          <?php if ( $is_prensa && $numero_com ) : ?>
          <div class="sidebar-meta__item">
            <span class="sidebar-meta__label">Comunicado</span>
            <span class="sidebar-meta__value">No. <?php echo esc_html( $numero_com ); ?></span>
          </div>
          <?php endif; ?>
        </div>

        <!-- Card: Compartir -->
        <div class="sidebar-card">
          <p class="sidebar-card__title">Compartir</p>
          <div class="share-icons">
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url_enc; ?>" target="_blank" rel="noopener" class="share-icon-btn share-icon-btn--fb" aria-label="Compartir en Facebook">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
            </a>
            <a href="https://twitter.com/intent/tweet?url=<?php echo $url_enc; ?>&text=<?php echo $title_enc; ?>" target="_blank" rel="noopener" class="share-icon-btn share-icon-btn--x" aria-label="Compartir en X">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
            </a>
            <a href="https://wa.me/?text=<?php echo $title_enc . '%20' . $url_enc; ?>" target="_blank" rel="noopener" class="share-icon-btn share-icon-btn--wa" aria-label="Compartir en WhatsApp">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.125.557 4.126 1.527 5.855L0 24l6.341-1.508A11.945 11.945 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.6a9.6 9.6 0 01-4.9-1.344l-.35-.208-3.664.872.936-3.546-.228-.364A9.6 9.6 0 012.4 12c0-5.302 4.298-9.6 9.6-9.6s9.6 4.298 9.6 9.6-4.298 9.6-9.6 9.6z"/></svg>
            </a>
            <a href="https://www.instagram.com/" target="_blank" rel="noopener" class="share-icon-btn share-icon-btn--ig" aria-label="Instagram">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
            </a>
            <a href="https://www.tiktok.com/" target="_blank" rel="noopener" class="share-icon-btn share-icon-btn--tt" aria-label="TikTok">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.18 8.18 0 004.78 1.52V6.72a4.85 4.85 0 01-1.01-.03z"/></svg>
            </a>
          </div>
          <div class="share-copy-wrap">
            <input type="text" class="share-copy-input" id="share-url" value="<?php echo esc_attr( $permalink ); ?>" readonly />
            <button class="share-copy-btn" id="share-copy-btn" aria-label="Copiar enlace">
              <?php echo $cp_svg; ?>
            </button>
          </div>
        </div>

        <!-- Card: Noticias relacionadas -->
        <?php if ( $related->have_posts() ) : ?>
        <div class="sidebar-card">
          <p class="sidebar-card__title">Noticias relacionadas</p>
          <div class="sidebar-related__list">
            <?php while ( $related->have_posts() ) : $related->the_post(); ?>
              <a href="<?php the_permalink(); ?>" class="sidebar-related__item">
                <?php if ( has_post_thumbnail() ) : ?>
                  <img src="<?php echo esc_url( get_the_post_thumbnail_url( null, 'thumbnail' ) ); ?>" alt="" class="sidebar-related__img" />
                <?php endif; ?>
                <div>
                  <p class="sidebar-related__text"><?php the_title(); ?></p>
                  <p class="sidebar-related__date"><?php echo get_the_date( 'j M Y' ); ?></p>
                </div>
              </a>
            <?php endwhile; wp_reset_postdata(); ?>
          </div>
        </div>
        <?php endif; ?>

      </aside>

    </div>

  </div>
</section>

<?php get_template_part( 'template-parts/comments', 'noticia' ); ?>

<script>
document.getElementById('share-copy-btn') && document.getElementById('share-copy-btn').addEventListener('click', function() {
  var input = document.getElementById('share-url');
  input.select();
  navigator.clipboard ? navigator.clipboard.writeText(input.value) : document.execCommand('copy');
  this.classList.add('share-copy-btn--copied');
  setTimeout(() => this.classList.remove('share-copy-btn--copied'), 2000);
});
</script>
