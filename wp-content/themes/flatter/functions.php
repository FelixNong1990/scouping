<?php
/**
 * Child Theme functions file
 *
 * @package Flatter
 * @author Themebound
 */

require( dirname(__FILE__) . '/includes/theme-defaults.php' );
if( is_admin() ) 
	require( dirname(__FILE__) . '/includes/theme-admin.php' );
require( dirname(__FILE__) . '/includes/theme-actions.php' );
require( dirname(__FILE__) . '/includes/theme-functions.php' );
require( dirname(__FILE__) . '/includes/theme-mobile-widgets.php' );

//allow redirection, even if my theme starts to send output to the browser
add_action('init', 'do_output_buffer');
function do_output_buffer() {
        ob_start();
}

//Page Slug Body Class
function add_slug_body_class( $classes ) {
	global $post;
	if ( isset( $post ) ) {
		$classes[] = $post->post_name;
	}
	return $classes;
}
add_filter( 'body_class', 'add_slug_body_class' );

// if(!function_exists('custom_avatar')){
	// function custom_avatar($avatar_defaults){
		// $new_default_icon = 'http://localhost/gv/wp-content/images/mystery-man.png';
		// $avatar_defaults[$new_default_icon] = 'Custom Avatar';
		// return $avatar_defaults;
	// }
	// add_filter('avatar_defaults','custom_avatar');
// }

add_filter( 'avatar_defaults', 'newgravatar' );
function newgravatar ($avatar_defaults) {
    $myavatar = content_url() . '/images/mystery-man.jpg';
    $avatar_defaults[$myavatar] = "Scouping";
    return $avatar_defaults;
}


function redirect_login_page(){
    // Store for checking if this page equals wp-login.php
	$page_viewed = basename($_SERVER['REQUEST_URI']);
	
    // Where we want them to go
 	$login_page  = site_url();
 	//Write your site URL here.
	// Two things happen here, we make sure we are on the login page
	// and we also make sure that the request isn't coming from a form
	// this ensures that our scripts & users can still log in and out.
	if( !is_user_logged_in() && $page_viewed == "wp-admin" && $_SERVER['REQUEST_METHOD'] == 'GET') {
		wp_redirect($login_page);
  		exit();
 	}
}

add_action('init','redirect_login_page');


// This will occur when the comment is posted
function plc_comment_post( $incoming_comment ) {

	// convert everything in a comment to display literally
	$incoming_comment['comment_content'] = htmlspecialchars($incoming_comment['comment_content']);

	// the one exception is single quotes, which cannot be #039; because WordPress marks it as spam
	$incoming_comment['comment_content'] = str_replace( "'", '&apos;', $incoming_comment['comment_content'] );

	return( $incoming_comment );
}

// This will occur before a comment is displayed
function plc_comment_display( $comment_to_display ) {

	// Put the single quotes back in
	$comment_to_display = str_replace( '&apos;', "'", $comment_to_display );

	return $comment_to_display;
}

add_filter( 'preprocess_comment', 'plc_comment_post', '', 1 );
add_filter( 'comment_text', 'plc_comment_display', '', 1 );
add_filter( 'comment_text_rss', 'plc_comment_display', '', 1 );
add_filter( 'comment_excerpt', 'plc_comment_display', '', 1 );
// This stops WordPress from trying to automatically make hyperlinks on text:
remove_filter( 'comment_text', 'make_clickable', 9 );
