<?php

function fl_init() {
	
	global $clpr_options;

	//set_post_thumbnail_size( 200, 200, true ); // blog post thumbnails
	add_image_size( 'thumb-store-showcase', 110, 50, true ); // used on the store page
	add_image_size( 'thumb-store', 180, 110, true ); // used on the store page
	add_image_size( 'thumb-featured', 180, 110, true ); // used in featured coupons slider
	add_image_size( 'thumb-large', 200, 200, true );
	
	//Remove the main theme actions
	remove_action( 'clipper_coupon_form', 'clpr_do_coupon_form' );
	remove_action('appthemes_before_blog_post_content', 'clpr_blog_post_meta');
	remove_action('appthemes_after_blog_post_content', 'clpr_blog_post_tags');
	remove_action('appthemes_after_blog_post_content', 'clpr_user_bar_box'); 
	remove_action( 'stores_edit_form_fields', 'clpr_edit_stores' );
	remove_action( 'stores_add_form_fields', 'add_store_extra_fields' );
	
	remove_action('appthemes_blog_comments_form', 'clpr_main_comment_form');
	remove_action( 'wp_ajax_nopriv_comment-form', 'clpr_comment_form' );
	remove_action( 'wp_ajax_comment-form', 'clpr_comment_form' );
	remove_action( 'wp_ajax_nopriv_post-comment', 'clpr_post_comment_ajax' );
	remove_action( 'wp_ajax_post-comment', 'clpr_post_comment_ajax' );
	
	if( is_admin() ) {
		appthemes_add_instance( array( 'FL_Theme_Settings_General' => $clpr_options ) );
	}
	
	
	register_nav_menus(array(
		'top' => sprintf( __( 'Top Panel Navigation (Shows only in <a href="%s">Dual Navigation Mode</a>)', APP_TD ), admin_url( 'admin.php?page=flatter' ) ),
	));

	
	if( fl_get_option( 'fl_store_thumbs_area' ) == 'slider_area' ) {
		add_action( 'appthemes_after_header', 'fl_add_store_thumbs' );
	} elseif( fl_get_option( 'fl_store_thumbs_area' ) == 'below_coupons' ) {
		add_action( 'appthemes_before_footer', 'fl_add_store_thumbs' );
	}
	
}
add_action( 'init', 'fl_init' );

/**
 * Enqueue Font Awesome
 */
function fl_add_my_stylesheet() {
	// Respects SSL, Style.css is relative to the current file
	wp_register_style( 'font-awesome', get_stylesheet_directory_uri() . '/css/font-awesome/css/font-awesome.css' );
	wp_enqueue_style( 'font-awesome' );
	
	if( isset( $_GET['css'] ) ) {
		$css = $_GET['css'];
	} else {
		$css = fl_get_option( 'fl_stylesheet' );
	}
	
	wp_enqueue_style( 'fl-color', get_stylesheet_directory_uri() . '/css/' . $css . '.css', false );
}
add_action( 'wp_enqueue_scripts', 'fl_add_my_stylesheet', 100 );

function fl_load_scripts() {
	wp_deregister_script( 'theme-scripts' );
	wp_enqueue_script( 'tinynav', get_bloginfo( 'stylesheet_directory' ) . '/includes/js/jquery.tinynav.js', array( 'jquery' ) );
	wp_enqueue_script( 'theme-scripts', get_bloginfo( 'stylesheet_directory' ) . '/includes/js/theme-scripts.js', array( 'jquery' ) );
	
	/* Script variables */
	$params = array(
		'app_tax_store' => APP_TAX_STORE,
		'ajax_url' => admin_url( 'admin-ajax.php', 'relative' ),
		'templateurl' => get_template_directory_uri(),
		'is_mobile' => wp_is_mobile(),
		'text_copied' => __( 'Copied', APP_TD ),
		'home_url' => home_url( '/' ),
		'text_mobile_primary' => fl_get_option( 'fl_lbl_tinynav' ),
		'text_mobile_top' => fl_get_option( 'fl_lbl_top_navigation' ),
		'text_before_delete_coupon' => __( 'Are you sure you want to delete this coupon?', APP_TD ),
		'text_sent_email' => __( 'Your email has been sent!', APP_TD ),
		'text_shared_email_success' => __( 'This coupon was successfully shared with', APP_TD ),
		'text_shared_email_failed' => __( 'There was a problem sharing this coupon with', APP_TD ),
		'force_affiliate' => fl_get_option( 'fl_coupon_force_affiliate' ),
	);
	wp_localize_script( 'theme-scripts', 'flatter_params', $params );
	
}
if ( !is_admin() ) {
	add_action( 'wp_print_scripts', 'fl_load_scripts', 20 );
}

function fl_body_class( $classes ) {
	
	if( fl_get_option( 'fl_mobile_navigation' ) == 'css' ) {
		$classes[] = 'responsive-menu';
	}
	
	return $classes;
}
add_filter( 'body_class', 'fl_body_class' );

function fl_add_featured_slider() {
	global $clpr_options;
	if( is_front_page() && $clpr_options->featured_slider ) :
?>
<div id="featured">
	<div class="frame">
		<?php get_template_part( 'featured' ); ?>
	</div>
</div>
<?php
	endif;
}
add_action( 'appthemes_after_header', 'fl_add_featured_slider' );

// add the post meta before the blog post content 
function fl_blog_post_meta() {
	if(is_page()) return; // don't do post-meta on pages
?>		
<div class="content-bar iconfix">
	<p class="meta">
		<span><i class="icon-calendar"></i><?php echo get_the_date(get_option('date_format')); ?></span>
		<span><i class="icon-folder-open"></i><?php the_category(', '); ?></span>
	</p>
	<p class="comment-count"><i class="icon-comments"></i><?php comments_popup_link( __( '0 Comments', APP_TD ), __( '1 Comment', APP_TD ), __( '% Comments', APP_TD ) ); ?></p>
</div>
<?php
}
// hook into the correct action
add_action('appthemes_before_blog_post_content', 'fl_blog_post_meta');

// add the post tags after the blog post content 
function fl_blog_post_tags() {
	global $post, $clpr_options;
	if( is_page() ) return; // don't do post-meta on pages
	
?>		

<div class="text-footer iconfix">
	
	<?php if( get_the_tags() ) { ?>
		<div class="tags"><i class="icon-tags"></i><?php _e( 'Tags:', APP_TD ); ?> <?php the_tags(' ', ', ', ''); ?></div>
	<?php } ?>
	
	<?php if ( $clpr_options->stats_all && current_theme_supports( 'app-stats' ) ) { ?>
		<div class="stats"><i class="icon-bar-chart"></i><?php appthemes_stats_counter( $post->ID ); ?></div>
	<?php } ?>
	
	<div class="clear"></div>
	
</div>

<?php
}
// hook into the correct action
add_action('appthemes_after_blog_post_content', 'fl_blog_post_tags');

// add the user bar box after the blog post content 
function fl_user_bar_box() {
	global $post;
	
	if( !is_singular('post') ) return; // only show on blog post single page
	
	// assemble the text and url we'll pass into each social media share link
	$social_text = urlencode(strip_tags(get_the_title() . ' ' . __( 'post from', APP_TD ) . ' ' . get_bloginfo('name')));
	$social_url  = urlencode(get_permalink($post->ID));
?>

<div class="user-bar">
	
	<?php if (comments_open()) comments_popup_link( ('<span>' . __( 'Leave a comment', APP_TD ) . '</span>'), ('<span>' . __( 'Leave a comment', APP_TD ) . '</span>'), ('<span>' . __( 'Leave a comment', APP_TD ) . '</span>'), 'btn', '' ); ?>	
	
	<?php fl_social_share(); ?>
	
</div>

<?php
}
// hook into the correct action
add_action('appthemes_after_blog_post_content', 'fl_user_bar_box', 100); 


// main comments form 
function fl_main_comment_form() {
	global $post; 
?>
<div id="respond">
	
	<form action="<?php echo site_url('wp-comments-post.php'); ?>" method="post" id="commentForm" class="post-form">
		
		<?php do_action( 'comment_form_top' ); ?>
		
		<div class="cancel-comment-reply"><?php cancel_comment_reply_link( __( 'Click here to cancel reply', APP_TD ) ); ?></div>
		
		<div class="clr">&nbsp;</div>
		
		<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
		
		<p><?php printf( __( 'You must be <a href="%s">logged in</a> to post a comment.', APP_TD ), wp_login_url( get_permalink() ) ); ?></p>
		
		<?php else : ?>
		
		<?php if ( is_user_logged_in() ) : global $user_identity; ?>
		
		<p><?php printf( __( 'Logged in as <a href="%1$s">%2$s</a>.', APP_TD ), CLPR_PROFILE_URL, $user_identity ); ?> <a href="<?php echo clpr_logout_url(get_permalink()); ?>"><?php _e( 'Log out &raquo;', APP_TD ); ?></a></p>
		
		<?php else : ?>
		
        <?php 
			$commenter = wp_get_current_commenter();
			$req = get_option( 'require_name_email' ); 
        ?>
		
		<div>
		
			<p class="comment-text">
				<label><?php _e( 'Name:', APP_TD ); ?></label>
				<input type="text" class="text required" name="author" id="author" value="<?php echo esc_attr( $commenter['comment_author'] ); ?>" />
			</p>
			
			<p class="comment-text">
				<label><?php _e( 'Email:', APP_TD ); ?></label>
				<input type="text" class="text required" name="email" id="email" value="<?php echo esc_attr(  $commenter['comment_author_email'] ); ?>" />
			</p>
			
			<p class="comment-text">
				<label><?php _e( 'Website:', APP_TD ); ?></label>
				<input type="text" class="text" name="url" id="url" value="<?php echo esc_attr( $commenter['comment_author_url'] ); ?>" />
			</p>
			
		</div>
		
		<?php endif; ?>
		
		<p class="comment-textarea">
			<label><?php _e( 'Comments:', APP_TD ); ?></label>
			<textarea cols="30" rows="10" name="comment" class="commentbox required" id="comment"></textarea>						
		</p>
		
		<p>
			<button type="submit" class="btn submit" id="submitted" name="submitted" value="submitted"><?php _e( 'Submit', APP_TD ); ?></button>
		</p>
		
		<p>
			
			<?php
				comment_id_fields();
				do_action('comment_form', $post->ID);
			?>
			
		</p>
		
		<?php endif; ?>
		
	</form>	
	
	
	
</div> <!-- #respond -->	
<div class="clr">&nbsp;</div>
<?php
}
// use this comments form within the appthemes action hook
add_action('appthemes_blog_comments_form', 'fl_main_comment_form');

// mini comments pop-up form  	
function fl_comment_form() {
	
	$comment_author = '';
	$comment_author_email = '';
	$comment_author_url = '';
	
	global $id;
	global $post; 
	$post = get_post( $_GET['id'] );	
	
	if ( isset($_COOKIE['comment_author_'.COOKIEHASH]) ) {
		$comment_author = apply_filters('pre_comment_author_name', $_COOKIE['comment_author_'.COOKIEHASH]);
		$comment_author = stripslashes($comment_author);
		$comment_author = esc_attr($comment_author);
		$_COOKIE['comment_author_'.COOKIEHASH] = $comment_author;
	}
	
	if ( isset($_COOKIE['comment_author_email_'.COOKIEHASH]) ) {
		$comment_author_email = apply_filters('pre_comment_author_email', $_COOKIE['comment_author_email_'.COOKIEHASH]);
		$comment_author_email = stripslashes($comment_author_email);
		$comment_author_email = esc_attr($comment_author_email);
		$_COOKIE['comment_author_email_'.COOKIEHASH] = $comment_author_email;
	}
	
	if ( isset($_COOKIE['comment_author_url_'.COOKIEHASH]) ) {
		$comment_author_url = apply_filters('pre_comment_author_url', $_COOKIE['comment_author_url_'.COOKIEHASH]);
		$comment_author_url = stripslashes($comment_author_url);
		$_COOKIE['comment_author_url_'.COOKIEHASH] = $comment_author_url;
	}	
?>

<div class="content-box comment-form">
	
	<div class="box-c">
		
		<div class="box-holder">
			
			<div class="post-box clearfix">
				
				<div class="head iconfix"><h3> <i class="icon-comment"></i> <?php echo fl_get_option( 'fl_lbl_leave_comment' ); ?> </h3></div>
				
				<div id="respond">
					
					<form action="/" method="post" id="commentform-<?php echo $post->ID; ?>" class="commentForm">
						
						<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
						
						<p><?php printf( __( 'You must be <a href="%s">logged in</a> to post a comment.', APP_TD ), wp_login_url( get_permalink() ) ); ?></p>
						
						<?php else : ?>
						
						<?php if ( is_user_logged_in() ) : global $user_identity; ?>
						
						<p><?php printf( __( 'Logged in as <a href="%1$s">%2$s</a>.', APP_TD ), CLPR_PROFILE_URL, $user_identity ); ?> <a href="<?php echo clpr_logout_url(get_permalink()); ?>"><?php _e( 'Log out &raquo;', APP_TD ); ?></a></p>
						
						<?php else : ?>
						
						<div>
						
							<p class="comment-text">
								<label><?php _e( 'Name:', APP_TD ); ?></label>
								<input type="text" class="text required" name="author" id="author-<?php echo $post->ID; ?>" value="<?php echo esc_attr($comment_author); ?>" />
							</p>
							
							<p class="comment-text">
								<label><?php _e( 'Email:', APP_TD ); ?></label>
								<input type="text"  class="text required email" name="email" id="email-<?php echo $post->ID; ?>" value="<?php echo esc_attr($comment_author_email); ?>" />
							</p>
							
							<p class="comment-text">
								<label><?php _e( 'Website:', APP_TD ); ?></label>
								<input type="text"  class="text" name="url" id="url-<?php echo $post->ID; ?>" value="<?php echo esc_attr($comment_author_url); ?>" />
							</p>
							
						</div>
						
						<?php endif; ?>
						
						<p class="comment-textarea">
							<label><?php _e( 'Comments:', APP_TD ); ?></label>
							<textarea cols="30" rows="10" name="comment" class="commentbox required" id="comment-<?php echo $post->ID; ?>"></textarea>						
						</p>
						
						<p>
							<button type="submit" class="comment-submit btn submit" id="submitted" name="submitted" value="submitted"><?php _e( 'Submit', APP_TD ); ?></button>			
							<input type='hidden' name='comment_post_ID' value='<?php echo $post->ID; ?>' id='comment_post_ID' />
						</p>
						
						<?php do_action('comment_form', $post->ID); ?>
						
						<?php endif; ?>
						
					</form>
					
				</div> <!-- #respond -->
				
			</div> <!-- #post-box -->
			
		</div> <!-- #box-holder -->
		
	</div> <!-- #box-c -->
	
</div> <!-- #content-box -->

<?php
	die;
}
add_action( 'wp_ajax_nopriv_comment-form', 'fl_comment_form' );
add_action( 'wp_ajax_comment-form', 'fl_comment_form' );

// mini comments post via ajax
function fl_post_comment_ajax() {
	global $wpdb;
	
    if ( 'POST' != $_SERVER['REQUEST_METHOD'] )
		die( json_encode( array( 'success' => false, 'message' => __( 'Sorry, only post method allowed.', APP_TD ) ) ) );
	
    $comment_post_ID = isset( $_POST['comment_post_ID'] ) ? (int) $_POST['comment_post_ID'] : 0;
	$post = get_post( $comment_post_ID );

	if ( ! $post )
		die( json_encode( array( 'success' => false, 'message' => __( 'Sorry, item does not exist.', APP_TD ) ) ) );

	// get_post_status() will get the parent status for attachments.
	$status = get_post_status( $post );

	$status_obj = get_post_status_object( $status );

	if ( ! comments_open( $comment_post_ID ) ) {
		die( json_encode( array( 'success' => false, 'message' => __( 'Sorry, comments are closed for this item.', APP_TD ) ) ) );
	} elseif ( 'trash' == $status ) {
		die( json_encode( array( 'success' => false, 'message' => __( 'Sorry, this item is in trash.', APP_TD ) ) ) );
	} elseif ( ! $status_obj->public && ! $status_obj->private ) {
		die( json_encode( array( 'success' => false, 'message' => __( 'Sorry, this item is not public.', APP_TD ) ) ) );
	} elseif ( post_password_required( $comment_post_ID ) ) {
		die( json_encode( array( 'success' => false, 'message' => __( 'Sorry, this item is password protected.', APP_TD ) ) ) );
	}

	$comment_author = ( isset( $_POST['author'] ) ) ? trim( strip_tags( $_POST['author'] ) ) : null;
	$comment_author_email = ( isset( $_POST['email'] ) ) ? trim( $_POST['email'] ) : null;
	$comment_author_url = ( isset( $_POST['url'] ) ) ? trim( $_POST['url'] ) : null;
	$comment_content = ( isset( $_POST['comment'] ) ) ? trim( $_POST['comment'] ) : null;

	// If the user is logged in
	$user = wp_get_current_user();
	if ( $user->exists() ) {
		if ( empty( $user->display_name ) )
			$user->display_name = $user->user_login;
		$comment_author = esc_sql( $user->display_name );
		$comment_author_email = esc_sql( $user->user_email );
		$comment_author_url = esc_sql( $user->user_url );
	} else {
		if ( get_option('comment_registration') || 'private' == $status )
			die( json_encode( array( 'success' => false, 'message' => __( 'Sorry, you must be logged in to post a comment.', APP_TD ) ) ) );
	}

	$comment_type = '';

	if ( get_option('require_name_email') && ! $user->ID ) {
		if ( 6 > strlen( $comment_author_email ) || '' == $comment_author )
			die( json_encode( array( 'success' => false, 'message' => __( 'Error: please fill the required fields (name, email).', APP_TD ) ) ) );
		elseif ( ! is_email( $comment_author_email ) )
			die( json_encode( array( 'success' => false, 'message' => __( 'Error: please enter a valid email address.', APP_TD ) ) ) );
	}

	if ( empty( $comment_content ) )
		die( json_encode( array( 'success' => false, 'message' => __( 'Error: please type a comment.', APP_TD ) ) ) );

	$comment_parent = isset( $_POST['comment_parent'] ) ? absint( $_POST['comment_parent'] ) : 0;

	$commentdata = compact( 'comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_type', 'comment_parent', 'user_ID' );

	// create the new comment in the db and get the comment id
	$comment_id = wp_new_comment( $commentdata );

	// go back and get the full comment so we can return it via ajax
	$comment = get_comment( $comment_id );

	if ( ! $user->ID ) {
		$comment_cookie_lifetime = apply_filters( 'comment_cookie_lifetime', 30000000 );
		setcookie( 'comment_author_' . COOKIEHASH, $comment->comment_author, time() + $comment_cookie_lifetime, COOKIEPATH, COOKIE_DOMAIN );
		setcookie( 'comment_author_email_' . COOKIEHASH, $comment->comment_author_email, time() + $comment_cookie_lifetime, COOKIEPATH, COOKIE_DOMAIN );
		setcookie( 'comment_author_url_' . COOKIEHASH, esc_url( $comment->comment_author_url ), time() + $comment_cookie_lifetime, COOKIEPATH, COOKIE_DOMAIN );
	}
	
    $results['success'] = true;
    
    $GLOBALS['comment'] = $comment;
	
	$results['comment'] = '';
	$results['comment'] .= '<li>';
	$results['comment'] .= '<div class="items">';
	$results['comment'] .= '<div class="rt clearfix">';
	$results['comment'] .= get_avatar($comment, 32 );
	$results['comment'] .= '<p>' . get_comment_text() . '</p>';
	$results['comment'] .= '<p class="comment-meta">';
	$results['comment'] .= '<span class="author"></span>' . __( 'Posted by', APP_TD ) . ' ' . get_comment_author_link() . '<span class="date-wrap iconfix"><i class="icon-calendar"></i>' . get_comment_time(get_option('date_format')) . '<i class="icon-time"></i>' . get_comment_time(get_option('time_format')) . '</span>';
	$results['comment'] .= '</p>';
	$results['comment'] .= '</div>';
	$results['comment'] .= '</div>';
	$results['comment'] .= '</li>';
	
	// get the comment count so we can update via ajax
	$comment_count = $wpdb->get_var( $wpdb->prepare( "SELECT comment_count FROM $wpdb->posts WHERE post_status IN ('publish', 'unreliable') AND ID = %d", $post->ID ) );
	$results['count'] = $comment_count;

	die( json_encode( $results ) );
	
}
add_action( 'wp_ajax_nopriv_post-comment', 'fl_post_comment_ajax' );
add_action( 'wp_ajax_post-comment', 'fl_post_comment_ajax' );


add_action('wp_ajax_nopriv_check-captcha', 'check_captcha');
add_action('wp_ajax_check-captcha', 'check_captcha');
function check_captcha()
{
	if(class_exists('ReallySimpleCaptcha')) {
		$comment_captcha = new ReallySimpleCaptcha();
		// This variable holds the CAPTCHA image prefix, which corresponds to the correct answer
		$comment_captcha_prefix = $_POST['comment_captcha_prefix'];
		// This variable holds the CAPTCHA response, entered by the user
		$comment_captcha_code = $_POST['comment_captcha_code'];
		// This variable will hold the result of the CAPTCHA validation. Set to 'false' until CAPTCHA validation passes
		$comment_captcha_correct = false;
		// Validate the CAPTCHA response
		$comment_captcha_check = $comment_captcha->check( $comment_captcha_prefix, $comment_captcha_code );
		// Set to 'true' if validation passes, and 'false' if validation fails
		$comment_captcha_correct = $comment_captcha_check;
		
		// If CAPTCHA validation fails (incorrect value entered in CAPTCHA field) don't process the comment.
		if ( ! $comment_captcha_correct ) {
			echo "The captcha code is incorrect. Please try again."; 
		} else {
			// clean up the tmp directory
			//$comment_captcha->remove($comment_captcha_prefix);
			//$comment_captcha->cleanup();
			echo "correct";
		}
	}

    exit;
}


// add the coupon submission form in submit-coupon-form.php
function fl_do_coupon_form( $post ) {
	global $clpr_options;

	$form_fields = array(
	'post_title' => 'post',
	'clpr_store_name' => APP_TAX_STORE,
	'clpr_new_store_name' => 'none',
	'clpr_new_store_url' => 'none',
	'cat' => APP_TAX_CAT,
	'coupon_type_select' => APP_TAX_TYPE,
	'clpr_coupon_code' => 'postmeta',
	'clpr_coupon_aff_url' => 'postmeta',
	'clpr_expire_date' => 'postmeta',
	'coupon-upload' => 'upload',
	'tags_input' => APP_TAX_TAG,
	'post_content' => 'post',
	);
	
	foreach( $form_fields as $name => $type ) {
		if ( isset( $_POST[ $name ] ) ) {
			$field[ $name ] = trim( $_POST[ $name ] );
		} elseif ( $post && $type == 'post' ) {
			$field[ $name ] = $post->$name;
		} elseif ( $post && $type == 'postmeta' ) {
			$field[ $name ] = get_post_meta( $post->ID, $name, true );
		} elseif ( $post && in_array( $type, array( APP_TAX_STORE, APP_TAX_CAT, APP_TAX_TYPE ) ) ) {
			$field[ $name ] = appthemes_get_custom_taxonomy( $post->ID, $type, 'term_id' );
		} elseif ( $post && $type == APP_TAX_TAG ) {
			$term_list = wp_get_post_terms( $post->ID, APP_TAX_TAG, array( 'fields' => 'names' ) );
			$term_list = implode( ', ', (array) $term_list );
			$field[ $name ] = $term_list;
		} else {
			$field[ $name ] = '';
		}
		
	}
	
?>

<form action="" id="couponForm" method="post" class="post-form" enctype="multipart/form-data">
		
		<ol>
			<li>
				<label><?php _e( 'Coupon Title:', APP_TD ); ?> </label>
				<input type="text" class="text required" id="post_title" name="post_title" value="<?php echo esc_attr( $field['post_title'] ); ?>" />
			</li>
			
			<li>
				<label><?php _e( 'Store:', APP_TD ); ?></label>
				<select id="store_name_select" name="clpr_store_name" class="text required">
					<option value=""><?php _e( '-- Select One --', APP_TD ); ?></option>
					<option value="add-new"><?php _e( 'Add New Store', APP_TD ); ?></option>
					<?php
						$terms = get_terms( APP_TAX_STORE, array( 'hide_empty' => 0 ) );
						foreach( $terms as $term ) {
							if ( clpr_get_store_meta( $term->term_id, 'clpr_store_active', true ) != 'no' ) {
								$selected = selected( $term->term_id, $field['clpr_store_name'], false );
								echo '<option value="' . $term->term_id . '" ' . $selected . '>' . $term->name . '</option>';
							}
						} 
					?>
				</select>
			</li>
			
			<li id="new-store-name" class="new-store">
				<label><?php _e( 'New Store Name:', APP_TD ); ?></label>
				<input type="text" class="text" name="clpr_new_store_name" value="<?php echo esc_attr( $field['clpr_new_store_name'] ); ?>"/>
			</li>
			
			<li id="new-store-url" class="new-store">
				<label><?php _e( 'New Store URL:', APP_TD ); ?> </label>
				<input type="text" class="text" id="clpr_new_store_url" name="clpr_new_store_url" value="<?php echo ( ! empty( $field['clpr_new_store_url'] ) ? esc_attr( $field['clpr_new_store_url'] ) : 'http://' ); ?>" />
			</li>
			
			<li>
				<label><?php _e( 'Coupon Category:', APP_TD ); ?> </label>
				<?php
					$args = array( 'taxonomy' => APP_TAX_CAT, 'selected' => $field['cat'], 'hierarchical' => 1, 'class' => 'text required', 'show_option_none' => __( '-- Select One --', APP_TD ), 'hide_empty' => 0, 'echo' => 0 );
					$select = wp_dropdown_categories( $args );
					$select = preg_replace('"-1"', "", $select); // remove the -1 for the "select one" option so jquery validation works
					echo $select;
				?>
			</li>
			
			<li>
				<label><?php _e( 'Coupon Type:', APP_TD ); ?> </label>
				<select id="coupon_type_select" name="coupon_type_select" class="text required">
					<option value=""><?php _e( '-- Select One --', APP_TD ); ?></option>
					<?php
						$terms = get_terms( APP_TAX_TYPE, array( 'hide_empty' => 0 ) );
						foreach( $terms as $term ) {
							$selected = selected( $term->term_id, $field['coupon_type_select'], false );
							echo '<option value="' . $term->slug . '" ' . $selected . '>' . $term->name . '</option>';
						}
					?>
				</select>
			</li>
			
			<li id="ctype-coupon-code" class="ctype">
				<label><?php _e( 'Coupon Code:', APP_TD ); ?> </label>
				<input type="text" class="text" id="ctype-coupon-code" name="clpr_coupon_code" value="<?php echo esc_attr( $field['clpr_coupon_code'] ); ?>"/>
			</li>
			
			<?php if ( $post && clpr_has_printable_coupon( $post->ID ) ) { ?>
				<li id="ctype-printable-coupon-preview" class="ctype">
					<label><?php _e( 'Current Coupon:', APP_TD ); ?> </label>
					<?php echo clpr_get_printable_coupon( $post->ID ); ?>
				</li>
			<?php } ?>
			
			<li id="ctype-printable-coupon" class="ctype">
				<label><?php _e( 'Printed Coupon:', APP_TD ); ?> </label>
				<input type="file" class="fileupload text" name="coupon-upload" value="<?php echo esc_attr( $field['coupon-upload'] ); ?>" />
			</li>
			
			<li>
				<label><?php _e( 'Destination URL:', APP_TD ); ?></label>
				<input type="text" class="text required" name="clpr_coupon_aff_url" value="<?php echo esc_attr( $field['clpr_coupon_aff_url'] ); ?>"/>
			</li>
			
			<li>
				<label><?php _e( 'Expiration Date:', APP_TD ); ?> </label>
				<input type="text" class="text required datepicker" name="clpr_expire_date" value="<?php echo esc_attr( $field['clpr_expire_date'] ); ?>" />
			</li>
			
			<li>
				<label><?php _e( 'Tags:', APP_TD ); ?> </label>
				<input type="text" class="text" name="tags_input" value="<?php echo esc_attr( $field['tags_input'] ); ?>" />
				<p class="tip"><?php _e( 'Separate tags with commas', APP_TD ); ?></p>
			</li>
			
			<li class="description">
				<label for="post_content"><?php _e( 'Full Description:', APP_TD ); ?> </label>
				<?php if ( $clpr_options->allow_html && ! wp_is_mobile() ) { ?>
					<?php wp_editor( $field['post_content'], 'post_content', clpr_get_editor_settings() ); ?>
				<?php } else { ?>
					<textarea class="required" id="post_content" cols="30" rows="5" name="post_content"><?php echo esc_textarea( $field['post_content'] ); ?></textarea>
				<?php } ?>
			</li>
			
			<li class="captcha">
				<?php
					// include the spam checker if enabled
					//appthemes_recaptcha();
					
					// Instantiate the ReallySimpleCaptcha class, which will handle all of the heavy lifting
					$comment_captcha = new ReallySimpleCaptcha();

					// ReallySimpleCaptcha class option defaults.
					// Changing these values will hav no impact. For now, these are here merely for reference.
					// If you want to configure these options, see "Set Really Simple CAPTCHA Options", below
					// TODO: Add admin page to allow configuration of options.
					$comment_captcha_defaults = array(
					'chars' => 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789',
					'char_length' => '4',
					'img_size' => array( '72', '24' ),
					'fg' => array( '0', '0', '0' ),
					'bg' => array( '255', '255', '255' ),
					'font_size' => '16',
					'font_char_width' => '15',
					'img_type' => 'png',
					'base' => array( '6', '18'),
					);

					/**************************************
					* All configurable options are below  *
					***************************************/

					// Set Really Simple CAPTCHA Options
					$comment_captcha->chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
					$comment_captcha->char_length = '5';
					$comment_captcha->img_size = array( '85', '24' );
					$comment_captcha->fg = array( '0', '0', '0' );
					$comment_captcha->bg = array( '255', '255', '255' );
					$comment_captcha->font_size = '16';
					$comment_captcha->font_char_width = '15';
					$comment_captcha->img_type = 'png';
					$comment_captcha->base = array( '6', '18' );

					// Set Comment Form Options
					$comment_captcha_form_label = 'Human Verification';

					/********************************************************************
					* Nothing else to edit.  No configurable options below this point.  *
					*********************************************************************/

					// Generate random word and image prefix
					$comment_captcha_word = $comment_captcha->generate_random_word();
					$comment_captcha_prefix = mt_rand();
					// Generate CAPTCHA image
					$comment_captcha_image_name = $comment_captcha->generate_image($comment_captcha_prefix, $comment_captcha_word);
					// Define values for comment form CAPTCHA fields
					$comment_captcha_image_url =  get_bloginfo('wpurl') . '/wp-content/plugins/really-simple-captcha/tmp/';
					$comment_captcha_image_src = $comment_captcha_image_url . $comment_captcha_image_name;
					$comment_captcha_image_width = $comment_captcha->img_size[0];
					$comment_captcha_image_height = $comment_captcha->img_size[1];
					$comment_captcha_field_size = $comment_captcha->char_length;
					// Output the comment form CAPTCHA fields
					?>
					<p class="comment-form-captcha">
					<label for="captcha_code"><?php echo $comment_captcha_form_label; ?></label>
					<img src="<?php echo $comment_captcha_image_src; ?>"
					 alt="captcha"
					 width="<?php echo $comment_captcha_image_width; ?>"
					 height="<?php echo $comment_captcha_image_height; ?>" />
					<input placeholder="<?php _e( 'Please type the verification code here', APP_TD ); ?>" id="comment_captcha_code" name="comment_captcha_code"
					 size="<?php echo $comment_captcha_field_size; ?>" type="text" />
					<input id="comment_captcha_prefix" name="comment_captcha_prefix" type="hidden"
					 value="<?php echo $comment_captcha_prefix; ?>" />
					</p>
			<?php	?>
			</li>
			
			<?php
				$button_text = ( clpr_payments_is_enabled() ) ? __( 'Continue', APP_TD ) : __( 'Share It!', APP_TD );
			?>
			
			<?php
				if ( clpr_payments_is_enabled() )
				do_action( 'appthemes_purchase_fields' );
			?>
			
			<li>
				<button type="submit" class="btn coupon" id="submitted" name="submitted" value="submitted"><?php echo $button_text; ?></button>
				<img id="spinner" src="<?php echo content_url(); ?>/images/spinner.gif" alt="Loading..."/>
			</li>
			
		</ol>
		
	
	<!-- autofocus the field -->
	<script type="text/javascript">try{document.getElementById('post_title').focus();}catch(e){}</script>
	
</form>

<?php
}
// hook into the correct action
add_action( 'clipper_coupon_form', 'fl_do_coupon_form', 10, 1 );

function fl_disable_children( $items, $args ) {

	if( !fl_get_option( 'fl_enable_dual_navigation' ) || $args->theme_location != 'primary' ) {
		return $items;
	}

	$menu_ids = array();
	
	

	foreach ( $items as $key => $item ) {
		if ( fl_get_option( 'fl_enable_store_mega_menu' ) && $item->object_id == CLPR_Coupon_Stores::get_id() ) {
			$item->current_item_ancestor = false;
			$item->current_item_parent = false;
			$menu_ids[] = $item->ID;
		}
		
		if ( fl_get_option( 'fl_enable_category_mega_menu' ) && $item->object_id == CLPR_Coupon_Categories::get_id() ) {
			$item->current_item_ancestor = false;
			$item->current_item_parent = false;
			$menu_ids[] = $item->ID;
		}
	}

	if ( $menu_ids ) {
		foreach ( $items as $key => $item )
			if ( in_array( $item->menu_item_parent, $menu_ids ) )
				unset( $items[$key] );
	}

	return $items;
}
add_filter( 'wp_nav_menu_objects', 'fl_disable_children', 10, 2 );

function fl_insert_stores_dropdown( $item_output, $item, $depth, $args ) {

	if( !( fl_get_option( 'fl_enable_dual_navigation' ) && fl_get_option( 'fl_enable_store_mega_menu' ) && $args->theme_location == 'primary' ) ) {
		return $item_output;
	}

	if ( $item->object_id == CLPR_Coupon_Stores::get_id() && $item->object == 'page' ) {
		$item_output .= '<div class="adv_taxonomies" id="adv_stores">' . fl_create_categories_list( 'menu', APP_TAX_STORE ) . '</div>';
	}
	return $item_output;
}
// Replace any children the "Stores" menu item might have with the stores dropdown
add_filter( 'walker_nav_menu_start_el', 'fl_insert_stores_dropdown', 10, 4 );

function fl_insert_categories_dropdown( $item_output, $item, $depth, $args ) {

	if( !( fl_get_option( 'fl_enable_dual_navigation' ) && fl_get_option( 'fl_enable_category_mega_menu' ) && $args->theme_location == 'primary' ) ) {
		return $item_output;
	}

	if ( $item->object_id == CLPR_Coupon_Categories::get_id() && $item->object == 'page' ) {
		$item_output .= '<div class="adv_taxonomies" id="adv_categories">' . fl_create_categories_list( 'menu', APP_TAX_CAT ) . '</div>';
	}
	return $item_output;
}
// Replace any children the "Categories" menu item might have with the category dropdown
add_filter( 'walker_nav_menu_start_el', 'fl_insert_categories_dropdown', 10, 4 );

function fl_add_store_thumbs() {
	global $clpr_options;
	if( is_front_page() && fl_get_option( 'fl_store_thumbs_area' ) != 'no' ) :
	
?>
<div id="store-thumb-container" class="<?php echo fl_get_option( 'fl_store_thumbs_area' ); ?>">
	<div class="frame">
		<?php get_template_part( 'store-thumbs' ); ?>
	</div>
</div>
<?php
	endif;
}


function fl_dynamic_sidebar_params( $params ) {
	if( $params[0]['id'] != 'sidebar_footer' ) {
		$params[0]['after_widget'] = '</div><br /><div class="sb-bottom"></div></div>';
	}
	return $params;
}
add_filter( 'dynamic_sidebar_params', 'fl_dynamic_sidebar_params' );