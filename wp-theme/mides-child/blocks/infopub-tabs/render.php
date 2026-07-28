<?php
/**
 * Render: Info Pública — Tabs
 * El tercer parámetro $block (WP_Block) da acceso a los paneles hijos para construir la nav.
 */

if ( ! isset( $block ) || ! ( $block instanceof WP_Block ) ) {
	return;
}

$uid = 'infopub-' . wp_unique_id();

/* ── SVG íconos para los botones del tab nav ── */
if ( ! function_exists( 'mides_infopub_tab_icon' ) ) :
function mides_infopub_tab_icon( string $key ): string {
	static $cache = [];
	if ( isset( $cache[ $key ] ) ) return $cache[ $key ];

	$icons = [
		'document'  => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>',
		'briefcase' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/><line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/></svg>',
		'chat'      => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>',
		'search'    => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>',
		'globe'     => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>',
		'calendar'  => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>',
		'book'      => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>',
		'dollar'    => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>',
		'home'      => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>',
		'users'     => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>',
		'mail'      => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>',
		'check'     => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>',
	];

	$cache[ $key ] = $icons[ $key ] ?? $icons['document'];
	return $cache[ $key ];
}
endif;

/* ── Construir tab nav y paneles desde los bloques hijos ── */
$tab_nav    = '';
$panels_out = '';
$idx        = 0;

foreach ( $block->inner_blocks as $inner ) {
	$label      = $inner->attributes['label'] ?? 'Tab';
	$icono      = $inner->attributes['icono'] ?? 'document';
	$panel_id   = $uid . '-' . $idx;
	$is_first   = $idx === 0;
	$active_cls = $is_first ? ' active' : '';
	$selected   = $is_first ? 'true' : 'false';
	$hidden_attr = $is_first ? '' : ' hidden';

	/* Botón del tab */
	$tab_nav .= sprintf(
		'<button class="infopub-tab%s" role="tab" data-tab="%s" aria-selected="%s">%s %s</button>',
		esc_attr( $active_cls ),
		esc_attr( $panel_id ),
		esc_attr( $selected ),
		mides_infopub_tab_icon( $icono ),
		esc_html( $label )
	);

	/* Contenido del panel — el bloque hijo ya tiene su render callback */
	$panel_content = $inner->render();

	$panels_out .= sprintf(
		'<div class="infopub-panel%s" id="%s" role="tabpanel"%s>%s</div>',
		esc_attr( $active_cls ),
		esc_attr( $panel_id ),
		$hidden_attr,
		$panel_content
	);

	$idx++;
}

if ( $idx === 0 ) return; // sin paneles, no renderizar nada
?>

<section class="infopub-section">
  <div class="container">

    <div class="infopub-tabs" role="tablist">
      <?php echo $tab_nav; ?>
    </div>

    <?php echo $panels_out; ?>

  </div>
</section>

<script>
(function () {
  var uid    = <?php echo wp_json_encode( $uid ); ?>;
  var tabs   = document.querySelectorAll('[data-tab^="' + uid + '"]');
  var panels = document.querySelectorAll('#' + uid.replace('-','\\-') + '-0, [id^="' + uid + '-"]');

  /* Re-select with proper scoping */
  var section  = document.currentScript.previousElementSibling;
  var tabBtns  = section ? section.querySelectorAll('.infopub-tab') : tabs;
  var tabPanels = section ? section.querySelectorAll('.infopub-panel') : panels;

  tabBtns.forEach(function (btn) {
    btn.addEventListener('click', function () {
      var target = btn.dataset.tab;

      tabBtns.forEach(function (b) {
        b.classList.remove('active');
        b.setAttribute('aria-selected', 'false');
      });
      tabPanels.forEach(function (p) {
        p.classList.remove('active');
        p.hidden = true;
      });

      btn.classList.add('active');
      btn.setAttribute('aria-selected', 'true');

      var panel = document.getElementById(target);
      if (panel) {
        panel.classList.add('active');
        panel.hidden = false;
      }
    });
  });
})();
</script>
