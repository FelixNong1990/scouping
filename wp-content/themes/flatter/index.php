<?php
// Template Name: Blog Template

if ( ! defined( 'ABSPATH' ) )
	die();
?>


<div id="content">
	<!--<h1><?php //echo get_the_title( CLPR_Blog_Archive::get_id() ); ?></h1>-->
	<?php get_template_part( 'loop' ); ?>

</div>

<?php get_sidebar( 'blog' ); ?>
