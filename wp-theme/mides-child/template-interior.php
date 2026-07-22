<?php
/**
 * Template Name: MIDES Página Interior
 * Template Post Type: page
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
while ( have_posts() ) :
	the_post();
	the_content();
endwhile;
get_footer();
