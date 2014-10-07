<?php

// return store image url with specified size
function fl_get_store_image_url( $id, $type = 'post_id', $width = 180, $height = 110 ) {
	$store_url = false;
	$store_image_id = false;
	
	$sizes = array( 75 => 'thumb-med', 180 => 'post-thumbnail', 110 => 'thumb-store-showcase', 180 => 'thumb-store', 180 => 'thumb-featured', 250 => 'thumb-large-preview' );
	$sizes = apply_filters( 'clpr_store_image_sizes', $sizes );
	
	if ( ! array_key_exists( $width, $sizes ) )
	$width = 180;
	
	if ( ! isset( $sizes[ $width ] ) )
	$sizes[$width] = 'post-thumbnail';
	
	if ( $type == 'term_id' && $id ) {
		$store_url = clpr_get_store_meta( $id, 'clpr_store_url', true );
		$store_image_id = clpr_get_store_meta( $id, 'clpr_store_image_id', true );
	}

	if ( $type == 'post_id' && $id ) {
		$term_id = appthemes_get_custom_taxonomy( $id, APP_TAX_STORE, 'term_id' );
		$store_url = clpr_get_store_meta( $term_id, 'clpr_store_url', true );
		$store_image_id = clpr_get_store_meta( $term_id, 'clpr_store_image_id', true );
	}
	
	if ( is_numeric( $store_image_id ) ) {
		$store_image_src = wp_get_attachment_image_src( $store_image_id, $sizes[ $width ] );
		if ( $store_image_src )
		return $store_image_src[0];
	}
	
	if ( ! empty( $store_url ) ) {
		$store_image_url = "http://s.wordpress.com/mshots/v1/" . urlencode($store_url) . "?w=" . $width . "&amp;h=" . $height;
		return apply_filters( 'clpr_store_image', $store_image_url, $width, $store_url );
	} else {
		$store_image_url = appthemes_locate_template_uri('images/clpr_default.jpg');
		return apply_filters( 'clpr_store_default_image', $store_image_url, $width );
	}
	
}

function fl_coupon_code_box( $coupon_type = null ) {
    global $post, $clpr_options;
	
	$class = 'coupon-code-link btn';
	
    if ( ! $coupon_type )
		$coupon_type = appthemes_get_custom_taxonomy( $post->ID, APP_TAX_TYPE, 'slug_name' );
	
    switch( $coupon_type ) {
		case 'printable-coupon':
		
		$class .= ' ' . $coupon_type;
	?>
		<div class="couponAndTip">
			<div class="link-holder">
				<a rel="nofollow" href="<?php clpr_get_coupon_image('thumb-med', 'url'); ?>" id="coupon-link-<?php echo $post->ID; ?>" class="<?php echo $class; ?>" title="<?php _e( 'Click to Print', APP_TD ); ?>" target="_blank" data-coupon-nonce="<?php echo wp_create_nonce( 'popup_' . $post->ID ); ?>" data-coupon-id="<?php echo $post->ID; ?>" data-clipboard-text="<?php echo fl_get_option( 'fl_lbl_print_coupon' ); ?>"><span><i class="icon-print"></i><?php echo fl_get_option( 'fl_lbl_print_coupon' ); ?></span></a>
			</div> <!-- #link-holder -->
			<p class="link-popup"><span class="link-popup-arrow"></span><span class="link-popup-inner"><?php _e( 'Click to print coupon', APP_TD ); ?></span></p>
		</div><!-- /couponAndTip -->
		
	<?php
        break;
		
		case 'coupon-code':
	?>
	<div class="couponAndTip">
		<div class="link-holder">
			<?php if ( $clpr_options->coupon_code_hide ) {
				$button_text = fl_get_option( 'fl_lbl_show_coupon' );
				$button_text = '<i class="icon-lock"></i>' . $button_text;
				$class .= ' coupon-hidden';
			} else {
				$class .= ' ' . $coupon_type;
				$button_text = wptexturize( get_post_meta( $post->ID, 'clpr_coupon_code', true ) ); 
			} ?>
			<a rel="nofollow" href="<?php echo clpr_get_coupon_out_url( $post ); ?>" id="coupon-link-<?php echo $post->ID; ?>" data-coupon-nonce="<?php echo wp_create_nonce( 'popup_' . $post->ID ); ?>" data-coupon-id="<?php echo $post->ID; ?>" class="<?php echo $class; ?>" title="<?php _e( 'Click to copy &amp; open site', APP_TD ); ?>" target="_blank" data-clipboard-text="<?php echo wptexturize( get_post_meta( $post->ID, 'clpr_coupon_code', true ) ); ?>"><span><?php echo $button_text; ?></span></a>
		</div> <!-- #link-holder -->
		<p class="link-popup"><span class="link-popup-arrow"></span><span class="link-popup-inner"><?php _e( 'Click to copy &amp; open site', APP_TD ); ?></span></p>
	</div><!-- /couponAndTip -->
	<?php
        break;
		
		default:
		
		$class .= ' ' . $coupon_type;
	?>
	<div class="couponAndTip">
		<div class="link-holder">
			<a rel="nofollow" href="<?php echo clpr_get_coupon_out_url( $post ); ?>" id="coupon-link-<?php echo $post->ID; ?>" class="<?php echo $class; ?>" title="<?php _e( 'Click to open site', APP_TD ); ?>" target="_blank" data-coupon-nonce="<?php echo wp_create_nonce( 'popup_' . $post->ID ); ?>" data-coupon-id="<?php echo $post->ID; ?>" data-clipboard-text="<?php echo fl_get_option( 'fl_lbl_redeem_offer' ); ?>"><span><?php echo fl_get_option( 'fl_lbl_redeem_offer' ); ?></span></a>
		</div> <!-- #link-holder -->
		<p class="link-popup"><span class="link-popup-arrow"></span><span class="link-popup-inner"><?php _e( 'Click to open site', APP_TD ); ?></span></p>
	</div><!-- /couponAndTip -->
	<?php
        break;
    } // end switch
}

// main comments callback function
function fl_comment_template($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; 
	
	switch ( $comment->comment_type ) :
	
	case '' :
?>

<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
	
	<div class="items">
				
		<div class="rt clearfix">
			
			<?php echo get_avatar($comment, 58 ); ?>
			
			<div class="bar">
				
				<?php comment_author_link(); ?>
				
				<span class="date-wrap iconfix"><span class="date"><i class="icon-calendar"></i><?php comment_time(get_option('date_format')); ?></span><span class="time"><i class="icon-time"></i><?php comment_time(get_option('time_format')); ?></span></span>
				
			</div> <!-- #bar -->
			
			<?php comment_text(); ?>
			
			<?php comment_reply_link(array_merge( $args, array(
				'reply_text' => '<i class="icon-reply"></i><span>' . __( 'Reply', APP_TD ) . '</span>',
				'respond_id' => 'respond',
				'before' => '',
				'after' => '',
				'depth' => $depth,
				'max_depth' => $args['max_depth']
            ))); ?>
			
		</div> <!-- #rt -->

	</div> <!-- #items -->
	
	<div id="comment-<?php comment_ID(); ?>"></div>
	
	<div class="clr"></div>
	
	<?php
		break;
		case 'pingback'  :
		case 'trackback' :
	?>
	
	<li class="post pingback"><?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', APP_TD ), ' ' ); ?></li>	
	
	<?php
		break;
		endswitch;
}

function fl_mini_comments($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment; ?>
	
    <li>
		<div class="items">
			
			<div class="rt clearfix">
	
				<?php echo get_avatar($comment, 32 ); ?>
				
				<?php comment_text(); ?>
				
				<p class="comment-meta">
					
					<span class="author"></span><?php _e( 'Posted by', APP_TD ); ?> <?php comment_author_link(); ?> <span class="date-wrap iconfix"><i class="icon-calendar"></i><?php comment_time(get_option('date_format')); ?><i class="icon-time"></i><?php comment_time(get_option('time_format')); ?></span>
					
				</p>
				
			</div>
		
		</div>
		<?php
}

// display the coupon submission form
function fl_show_coupon_form( $post = false ) {
	$errors = new WP_Error();
	?>


	<div class="blog">
		
		<h1><?php _e( 'Share a Coupon', APP_TD ); ?></h1>
		
		<div class="content-bar">
			<span><?php _e( 'Complete the form below to share your coupon with us.', APP_TD ); ?></span>
		</div>
		
		<?php clipper_coupon_form( $post ); ?>
		
	</div> <!-- #post-box -->

<?php
}

function fl_social_share() {
	global $post;
	$social_text = urlencode(strip_tags(get_the_title() . ' ' . __( 'coupon from', APP_TD ) . ' ' . get_bloginfo('name')));
	$social_url = urlencode(get_permalink($post->ID));
	?>
	<ul class="inner-social">								
		<li><a class="twitter" href="javascript:void(0);" onclick="window.open('http://twitter.com/home?status=<?php echo urlencode($post->post_title); ?>:%0a<?php the_permalink(); ?> via @Scouping','twitter-share-dialog','width=670,height=436');return false;" rel="nofollow" target="_blank"><?php _e( 'Twitter', APP_TD ); ?></a></li>
		<li><a class="facebook" href="javascript:void(0);" onclick="window.open('http://www.facebook.com/sharer.php?t=<?php echo $social_text; ?>&amp;u=<?php echo $social_url; ?>','doc', 'width=638,height=500,scrollbars=yes,resizable=auto');" rel="nofollow"><?php _e( 'Facebook', APP_TD ); ?></a></li>
		<li><a class="pinterest" href="//pinterest.com/pin/create/button/?url=<?php echo $social_url; ?>&amp;media=<?php echo fl_get_store_image_url($post->ID, 'post_id', '180'); ?>&amp;description=<?php echo $social_text; ?>" data-pin-do="buttonPin" data-pin-config="beside" rel="nofollow" target="_blank"><?php _e( 'Pinterest', APP_TD ); ?></a></li>
		<li><a class="digg" href="http://digg.com/submit?phase=2&amp;url=<?php echo $social_url; ?>&amp;title=<?php echo $social_text; ?>" rel="nofollow" target="_blank"><?php _e( 'Digg', APP_TD ); ?></a></li>
		<li><a class="reddit" href="http://reddit.com/submit?url=<?php echo $social_url; ?>&amp;title=<?php echo $social_text; ?>" rel="nofollow" target="_blank"><?php _e( 'Reddit', APP_TD ); ?></a></li>
		<!--<li><a class="rss" href="<?php //echo get_post_comments_feed_link(get_the_ID()); ?>" rel="nofollow"><?php //_e( 'Coupon Comments RSS', APP_TD ); ?></a></li>-->
	</ul>
	<?php 
}

// tinyMCE text editor
function fl_tinymce( $width = 420, $height = 300 ) {
	?>
	<script type="text/javascript">
		tinyMCEPreInit = {
			base : "<?php echo includes_url('js/tinymce'); ?>",
			suffix : "",
			mceInit : {
				mode : "specific_textareas",
				editor_selector : "mceEditor",
				theme : "advanced",
				plugins : "inlinepopups",
				skin : "default",
				theme_advanced_buttons1 : "formatselect,fontselect,fontsizeselect",
				theme_advanced_buttons2 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,forecolor,backcolor",
				theme_advanced_buttons3 : ( jQuery(window).width() < 400 ) ? "" : "cut,copy,paste,pastetext,pasteword,|,bullist,numlist,|,outdent,indent,|,undo,redo,|,link,unlink,cleanup,code",
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				theme_advanced_statusbar_location : "bottom",
				theme_advanced_resizing : true,
				theme_advanced_resize_horizontal : false,
				content_css : "<?php echo get_bloginfo('stylesheet_directory'); ?>/style.css",
				languages : 'en',
				disk_cache : true,
				width : ( jQuery(window).width() < 400 ) ? "260" : "<?php echo $width; ?>",
				height : "<?php echo $height; ?>",
				language : 'en',
				setup : function(editor) {
					editor.onKeyUp.add(function(editor, e) {
						tinyMCE.triggerSave();
						jQuery("#" + editor.id).valid();
					});
				}
				
			},
			load_ext : function(url,lang){var sl=tinymce.ScriptLoader;sl.markDone(url+'/langs/'+lang+'.js');sl.markDone(url+'/langs/'+lang+'_dlg.js');}
		};
		(function(){var t=tinyMCEPreInit,sl=tinymce.ScriptLoader,ln=t.mceInit.language,th=t.mceInit.theme;sl.markDone(t.base+'/langs/'+ln+'.js');sl.markDone(t.base+'/themes/'+th+'/langs/'+ln+'.js');sl.markDone(t.base+'/themes/'+th+'/langs/'+ln+'_dlg.js');})();
		tinyMCE.init(tinyMCEPreInit.mceInit);
	</script>
		
	<?php
}

function fl_shorten_content( $content = false, $len = 50 ) {
	if( !$content ) return;
	
	if( mb_strlen( $content ) > $len )
		return mb_substr( $content, 0, $len - 3 ) . '...';
	else
		return $content;
}

/**
 * Displays additional information if coupon is expired.
 *
 * @since 1.1 (Clipper 1.5)
 *
 * @param int $post_id Post ID.
 *
 * @return void
 */
function fl_display_expired_info( $post_id ) {
	// do not show on taxonomy pages, there is Unreliable section
	if ( is_tax() )
		return;

	$expire_time = clpr_get_expire_date( $post_id, 'time' );
	if ( ! $expire_time )
		return;

	$expire_time += ( 24 * 3600 ); // + 24h, coupons expire in the end of day
	if ( $expire_time > current_time( 'timestamp' ) )
		return;

	echo html( 'div class="expired-coupon-info iconfix"', html( 'i class="icon-info-sign"', '' ) . __( 'Expired!', APP_TD ) );
}

function fl_coupon_title( $len = 50 ) {
	global $clpr_options;

	if ( $clpr_options->link_single_page ) {
		$title = fl_shorten_content( get_the_title(), $len );
		$title_attr = sprintf( esc_attr__( 'View the "%s" coupon page', APP_TD ), the_title_attribute( 'echo=0' ) );
		echo html( 'a', array( 'href' => get_permalink(), 'title' => $title_attr ), $title );
	} else {
		echo fl_shorten_content( get_the_title(), $len );
	}
}

// categories list display
function fl_create_categories_list( $location = 'menu', $taxonomy = APP_TAX_STORE ) {

	$args['menu_cols'] = 3;
	$args['menu_depth'] = 3;
	$args['menu_sub_num'] = 3;
	$args['cat_parent_count'] = true;
	$args['cat_child_count'] = true;
	$args['cat_hide_empty'] = false;
	$args['cat_nocatstext'] = true;
	$args['cat_order'] = 'ASC';
	$args['taxonomy'] = $taxonomy;
	
	$term_args = array();
	
	if( $taxonomy == APP_TAX_STORE ) {
		$hidden_stores = clpr_hidden_stores();
		$term_args['exclude'] = $hidden_stores;
	}

	return appthemes_categories_list( $args, $term_args );
}

function fl_get_terms( $taxonomy = APP_TAX_STORE, $args = array() ) {
	$defaults = array(
		'orderby' => 'count',
		'order' => 'DESC',
		'number' => -1,
	);
	$args = wp_parse_args( $args, $default );
	
	return get_terms( $taxonomy, $args );
	
}