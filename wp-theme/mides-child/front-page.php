<?php
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
?>
<div id="primary" <?php astra_primary_class(); ?>>
  <?php astra_primary_content_top(); ?>
  <?php astra_content_page_loop(); ?>
  <?php astra_primary_content_bottom(); ?>
</div>
<?php get_footer(); ?>
