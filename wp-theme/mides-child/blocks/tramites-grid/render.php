<?php
/**
 * Render callback del bloque Trámites Grid.
 */
$titulo_seccion = $attributes['tituloSeccion'] ?? 'Servicios Digitales';
$intro          = $attributes['introTexto']    ?? 'Realiza y consulta tus solicitudes de forma rápida y segura desde cualquier dispositivo. Selecciona la categoría que necesitas.';
$linea_svg      = get_stylesheet_directory_uri() . '/img/Linea-Resaltadora.svg';
$nota  = $attributes['notaTexto']  ?? 'Para realizar trámites necesitarás tu número de DPI. Si tienes inconvenientes, comunícate a nuestro PBX: +502 2300-5400 en horario de lunes a viernes de 8:00 a 16:00 horas.';

$link_icon = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>';

$icon_svgs = [
	'lista'     => '<svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>',
	'documento' => '<svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>',
	'personas'  => '<svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>',
	'busqueda'  => '<svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><circle cx="11" cy="14" r="3"/><line x1="16" y1="19" x2="13.5" y2="16.5"/></svg>',
	'chat'      => '<svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>',
];

$tarjetas = [
	[
		'cat'  => $attributes['t1Cat']     ?? 'General',
		'desc' => $attributes['t1Desc']    ?? '',
		'icono'=> $attributes['t1Icono']   ?? 'lista',
		'color'=> $attributes['t1Color']   ?? 'blue',
		'l1t'  => $attributes['t1L1Texto'] ?? '',
		'l1u'  => $attributes['t1L1Url']   ?? '',
		'l2t'  => $attributes['t1L2Texto'] ?? '',
		'l2u'  => $attributes['t1L2Url']   ?? '',
		'full' => false,
	],
	[
		'cat'  => $attributes['t2Cat']     ?? 'FODES',
		'desc' => $attributes['t2Desc']    ?? '',
		'icono'=> $attributes['t2Icono']   ?? 'documento',
		'color'=> $attributes['t2Color']   ?? 'amber',
		'l1t'  => $attributes['t2L1Texto'] ?? '',
		'l1u'  => $attributes['t2L1Url']   ?? '',
		'l2t'  => $attributes['t2L2Texto'] ?? '',
		'l2u'  => $attributes['t2L2Url']   ?? '',
		'full' => false,
	],
	[
		'cat'  => $attributes['t3Cat']     ?? 'Recursos Humanos',
		'desc' => $attributes['t3Desc']    ?? '',
		'icono'=> $attributes['t3Icono']   ?? 'personas',
		'color'=> $attributes['t3Color']   ?? 'blue',
		'l1t'  => $attributes['t3L1Texto'] ?? '',
		'l1u'  => $attributes['t3L1Url']   ?? '',
		'l2t'  => $attributes['t3L2Texto'] ?? '',
		'l2u'  => $attributes['t3L2Url']   ?? '',
		'full' => false,
	],
	[
		'cat'  => $attributes['t4Cat']     ?? 'Información Pública',
		'desc' => $attributes['t4Desc']    ?? '',
		'icono'=> $attributes['t4Icono']   ?? 'busqueda',
		'color'=> $attributes['t4Color']   ?? 'amber',
		'l1t'  => $attributes['t4L1Texto'] ?? '',
		'l1u'  => $attributes['t4L1Url']   ?? '',
		'l2t'  => $attributes['t4L2Texto'] ?? '',
		'l2u'  => $attributes['t4L2Url']   ?? '',
		'full' => false,
	],
	[
		'cat'  => $attributes['t5Cat']     ?? 'Consultas y Reclamos',
		'desc' => $attributes['t5Desc']    ?? '',
		'icono'=> $attributes['t5Icono']   ?? 'chat',
		'color'=> $attributes['t5Color']   ?? 'blue',
		'l1t'  => $attributes['t5L1Texto'] ?? '',
		'l1u'  => $attributes['t5L1Url']   ?? '',
		'l2t'  => $attributes['t5L2Texto'] ?? '',
		'l2u'  => $attributes['t5L2Url']   ?? '',
		'full' => true,
	],
];
?>

<section class="tramites-section">
  <div class="container">

    <?php if ( $titulo_seccion ) : ?>
    <div class="section-header">
      <h2 class="section-title">
        <span class="title-highlight"><?php echo esc_html( $titulo_seccion ); ?><img src="<?php echo esc_url( $linea_svg ); ?>" alt="" class="section-underline" /></span>
      </h2>
    </div>
    <?php endif; ?>

    <?php if ( $intro ) : ?>
    <p class="tramites-intro__text"><?php echo esc_html( $intro ); ?></p>
    <?php endif; ?>

    <div class="tramites-grid">
      <?php foreach ( $tarjetas as $t ) :
        $icon_svg    = $icon_svgs[ $t['icono'] ] ?? $icon_svgs['lista'];
        $card_class  = 'tramite-card' . ( $t['full'] ? ' tramite-card--full' : '' );
        $icon_class  = 'tramite-card__icon' . ( $t['color'] === 'amber' ? ' tramite-card__icon--amber' : '' );
        $actions_class = 'tramite-card__actions' . ( $t['full'] && $t['l2t'] ? ' tramite-card__actions--row' : '' );
      ?>
      <div class="<?php echo esc_attr( $card_class ); ?>">
        <div class="<?php echo esc_attr( $icon_class ); ?>">
          <?php echo $icon_svg; ?>
        </div>
        <div class="tramite-card__body">
          <?php if ( $t['cat'] ) : ?>
          <span class="tramite-card__cat"><?php echo esc_html( $t['cat'] ); ?></span>
          <?php endif; ?>
          <p class="tramite-card__desc"><?php echo esc_html( $t['desc'] ); ?></p>
          <div class="<?php echo esc_attr( $actions_class ); ?>">
            <?php if ( $t['l1t'] && $t['l1u'] ) : ?>
            <a href="<?php echo esc_url( $t['l1u'] ); ?>" target="_blank" rel="noopener" class="tramite-card__action">
              <?php echo $link_icon; ?>
              <?php echo esc_html( $t['l1t'] ); ?>
            </a>
            <?php endif; ?>
            <?php if ( $t['l2t'] && $t['l2u'] ) : ?>
            <a href="<?php echo esc_url( $t['l2u'] ); ?>" target="_blank" rel="noopener" class="tramite-card__action">
              <?php echo $link_icon; ?>
              <?php echo esc_html( $t['l2t'] ); ?>
            </a>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <?php if ( $nota ) : ?>
    <div class="tramites-note">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      <p><?php echo esc_html( $nota ); ?></p>
    </div>
    <?php endif; ?>

  </div>
</section>
