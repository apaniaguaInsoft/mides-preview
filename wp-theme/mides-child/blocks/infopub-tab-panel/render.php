<?php
/**
 * Render: Info Pública — Panel de Tab
 * Solo pasa el contenido (InnerBlocks ya renderizados) sin wrapper adicional.
 * El bloque padre (infopub-tabs) se encarga de envolver cada panel con el div correcto.
 */
echo $content; // phpcs:ignore WordPress.Security.EscapeOutput
