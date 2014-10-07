<!DOCTYPE html>

<html <?php language_attributes(); ?>>

<head>

	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<title>
	<?php
		$paged = $wp_query->get( 'paged' );
      	if (function_exists('is_tag') && is_tag()) {                    //tag page
        	single_tag_title("Tag Archive for &quot;"); echo '&quot; - '; 
        } elseif (is_archive()) {                                         //Archive page
         	wp_title('-',true,'right'); 
        } elseif (is_search()) {                                          //Search page
         	echo 'Search for &quot;'.wp_specialchars($s).'&quot; - '; 
        } elseif (!(is_404()) && (is_single()) || (is_page()) && !$paged && !is_front_page()) {          //Single page
         	wp_title(''); echo ' - '; 
        } elseif (is_404()) {                                             //404 page
         	echo 'Page Not Found - '; 
        }
        if (is_home()) {                                                //Home page
        	// Get current page title as well as uppercase first letter of each word
        	$pagename = ucwords(get_query_var('pagename'));
        	echo $pagename; echo ' - '; bloginfo('name');
         	//bloginfo('name'); echo ' - '; bloginfo('description'); 
        } else if(!is_front_page()){                                                          //Blog Page
          	bloginfo('name'); 
      	}
 	 	if ($paged>1) {                                                 //Paged Search page
         	bloginfo('name'); echo ' - Page '. $paged;
     	}
     	if(is_front_page() && !$paged) {
     		wp_title('');
     	}
    ?>
	</title>
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php echo appthemes_get_feed_url(); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<?php
	if(is_page('contact') || is_page('share-coupon')) {
	?>
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
	<?php 
	} 
	?>
	<!--[if IE]>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<![endif]-->
	<meta name="viewport" content="width=device-width, initial-scale=1" />

    <!--[if lt IE 7]><link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/styles/ie.css" media="screen"/><![endif]-->
       <!--[if IE 7]><link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/styles/ie7.css" media="screen"/><![endif]-->

	<?php wp_head(); ?>
	
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	
	<?php flush(); ?>
</head>

<body id="top" <?php body_class(); ?>>

	<?php appthemes_before(); ?>

	<div id="wrapper">

		<div class="bg">&nbsp;</div>

		<div class="w1">

			<?php appthemes_before_header(); ?>
			<?php get_header( app_template_base() ); ?>
			<?php appthemes_after_header(); ?>

			<div id="main">

			<?php load_template( app_template_path() ); ?>

			</div> <!-- #main -->

		</div> <!-- #w1 -->

		<?php appthemes_before_footer(); ?>
		<?php get_footer( app_template_base() ); ?>
		<?php appthemes_after_footer(); ?>

	</div> <!-- #wrapper -->

	<?php wp_footer(); ?>

	<?php appthemes_after(); ?>

</body>

</html>
