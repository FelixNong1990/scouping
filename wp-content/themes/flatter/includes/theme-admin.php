<?php


global $clpr_options;
require_once( get_template_directory() . '/framework/load.php' );


class FL_Theme_Settings_General extends APP_Tabs_Page {

	function setup() {
		$this->textdomain = 'clipper';

		$this->args = array(
			'page_title' => __( 'Flatter Settings', APP_TD ),
			'menu_title' => __( 'Flatter Settings', APP_TD ),
			'page_slug' => 'flatter',
			'parent' => 'app-dashboard',
			'screen_icon' => 'options-general',
			'admin_action_priority' => 100,
		);

	}


	protected function init_tabs() {
		// Remove unwanted query args from urls
		$_SERVER['REQUEST_URI'] = remove_query_arg( array( 'firstrun', 'prune-coupons', 'reset-votes', 'reset-stats', 'reset-search-stats' ), $_SERVER['REQUEST_URI'] );
		
		$this->tabs->add( 'general', __( 'General', APP_TD ) );
		
		$this->tab_sections['general']['styling'] = array(
			'title' => __( 'Styling', APP_TD ),
			'fields' => array(
				array(
					'title' => __( 'Colour Scheme', APP_TD ),
					'type' => 'select',
					'name' => 'fl_stylesheet',
					'desc' => '',
					'tip' => __( 'Select the color scheme you would like your classified ads site to use.', APP_TD ),
					'value' => fl_get_option( 'fl_stylesheet' ),
					'values' => array(
						'blue' => 'Blue (Default)',
						'green' => 'Green',
						'red' => 'Red',
						'golden' => 'Golden',
						'dark-blue' => 'Dark Blue',
						'purple' => 'Purple',
						'grey' => 'Grey',
						'bronze' => 'Bronze',
						'brown' => 'Brown',
						'turquoise' => 'Turquoise',
						'charcoal' => 'Charcoal',
						/*'yellow' => 'Yellow',
						'black-white' => 'Black and White',*/
					),
				),
			),
		);
		
		$this->tab_sections['general']['navigation'] = array(
			'title' => __( 'Navigation', APP_TD ),
			'fields' => array(
				array(
					'title' => __( 'Primary Navigation Label on Mobile', APP_TD ),
					'type' => 'text',
					'name' => 'fl_lbl_tinynav',
					'desc' => '',
					'tip' => __( 'Select the label for primary navigation for smaller screen mobile widths.', APP_TD ),
					'value' => fl_get_option( 'fl_lbl_tinynav' ),
				),
				array(
					'title' => __( 'Enable Dual Navigation', APP_TD ),
					'type' => 'checkbox',
					'name' => 'fl_enable_dual_navigation',
					'desc' => __( '<br /><strong>Recommended!</strong> Enabling this option will push the "Primary Navigation" <em>below</em> the logo and create an additional navigation area (menu location) above the logo in the topmost panel. It is recommended you enable it to take maximum advantage of some very significant child theme features as they will only work when this option is selected.', APP_TD ),
				),
				array(
					'title' => __( 'Enable Category Mega Menu', APP_TD ),
					'type' => 'checkbox',
					'name' => 'fl_enable_category_mega_menu',
					'desc' => __( '<br />Only works if dual navigation is enabled. Enable large three column mega menu under Categories link in the primary navigation.', APP_TD ),
					'tip' => '',
				),
				array(
					'title' => __( 'Enable Stores Mega Menu', APP_TD ),
					'type' => 'checkbox',
					'name' => 'fl_enable_store_mega_menu',
					'desc' => __( '<br />Only works if dual navigation is enabled. Enable large three column mega menu under Stores link in the primary navigation.', APP_TD ),
					'tip' => '',
				),
				array(
					'title' => __( 'Primary Navigation on Mobile', APP_TD ),
					'type' => 'select',
					'name' => 'fl_mobile_navigation',
					'desc' => __( '<br />Only works if dual navigation is enabled as that is when the primary navigation is below the logo', APP_TD ),
					'tip' => __( 'Select the type of primary navigation for smaller screen mobile widths.', APP_TD ),
					'value' => fl_get_option( 'fl_mobile_navigation' ),
					'values' => array(
						'select' => 'HTML Select Box (Default)',
						'css' => 'Responsive CSS',
					),
				),
				array(
					'title' => __( 'Top Panel Navigation Label on Mobile', APP_TD ),
					'type' => 'text',
					'name' => 'fl_lbl_top_navigation',
					'desc' => __( '<br />Only works if dual navigation is enabled as that is when the additional menu will show.', APP_TD ),
					'tip' => __( 'Select the label for top panel navigation (additional menu above logo) for smaller screen mobile widths.', APP_TD ),
					'value' => fl_get_option( 'fl_lbl_top_navigation' ),
				),
				array(
					'title' => __( 'Enable "Share Coupon" Button from Navigation', APP_TD ),
					'type' => 'checkbox',
					'name' => 'fl_navigation_share_coupon',
					'desc' => __( '<br />Only works if dual navigation is enabled, the button will show next to primary menu with the label you set in the next option.', APP_TD ),
				),
				array(
					'title' => __( '"Share Coupon" Button Label', APP_TD ),
					'type' => 'text',
					'name' => 'fl_lbl_share_coupon',
					'desc' => __( '<br />Only works if dual navigation and "Share Coupon" Button (the last option) are both enabled.', APP_TD ),
					'value' => fl_get_option( 'fl_lbl_share_coupon' ),
				),
			),
		);
		
		$this->tab_sections['general']['instructions'] = array(
			'title' => __( 'Instructions', APP_TD ),
			'description' => html( 'p', __('If you were using the default Clipper theme or another child theme before, your image thumbnails may need to be updated for optimum usage with the Flatter theme. Please install the <a href="http://wordpress.org/plugins/ajax-thumbnail-rebuild/" target="_blank">AJAX Thumbnail Rebuild</a> plugin and rebuild all your thumbnails just once for this purpose.', APP_TD) )
			. html( 'p', __('Read the installation guide <a href="http://themebound.com/shop/flatter-responsive-child-theme-clipper/" target="_blank">here</a>. Enjoy the theme and please report any issue you encounter to <a href="mailto:info@themebound.com">info@themebound.com</a>. Follow us on <a href="https://twitter.com/#!/themebound" target="_blank">Twitter</a>, and like us on <a href="https://www.facebook.com/themebound" target="_blank">Facebook</a> to get updates on new products and useful tips.', APP_TD) ),
			'renderer' => array( $this, 'render_instructions' ),
			'fields' => array(),
		);
		
		$this->tabs->add( 'homepage', __( 'Homepage', APP_TD ) );
		
		$this->tab_sections['homepage']['store_thumbs'] = array(
			'title' => __( 'Stores Area', APP_TD ),
			'fields' => array(
				array(
					'title' => __( 'Enable on Homepage', APP_TD ),
					'type' => 'select',
					'name' => 'fl_store_thumbs_area',
					'desc' => '<br />This will display a list of store thumbnails on the home page configurable via the following options',
					'value' => fl_get_option( 'fl_store_thumbs_area' ),
					'values' => array(
						'below_coupons' => 'Below Coupon List (Default)',
						'slider_area' => 'Above Coupon List',
						'no' => 'Do not enable',
					),
				),
				array(
					'title' => __( 'Title', APP_TD ),
					'desc' => __( '<br />Title for the Stores Area', APP_TD ),
					'name' => 'fl_store_thumbs_title',
					'type' => 'text',
					'value' => fl_get_option( 'fl_store_thumbs_title' ),
				),
				array(
					'title' => __( 'Number of Stores', APP_TD ),
					'desc' => __( '<br />Total number of store thumbnails to display, ideally a multiple of 7', APP_TD ),
					'name' => 'fl_store_thumbs_number',
					'type' => 'text',
					'value' => fl_get_option( 'fl_store_thumbs_number' ),
				),
				array(
					'title' => __( 'Order By', APP_TD ),
					'type' => 'select',
					'name' => 'fl_store_thumbs_orderby',
					'desc' => '<br />Order the list of stores by',
					'value' => fl_get_option( 'fl_store_thumbs_orderby' ),
					'values' => array(
						'count' => 'Number of coupons (default)',
						'name' => 'Store name',
					),
				),
				array(
					'title' => __( 'Order', APP_TD ),
					'type' => 'select',
					'name' => 'fl_store_thumbs_order',
					'desc' => '<br />Order direction',
					'value' => fl_get_option( 'fl_store_thumbs_order' ),
					'values' => array(
						'DESC' => 'Descending (Default)',
						'ASC' => 'Ascending',
					),
				),
				array(
					'title' => __( 'Show Only Featured Stores', APP_TD ),
					'type' => 'checkbox',
					'name' => 'fl_store_thumbs_featured_only',
					'desc' => __( '<br />Show only featured stores?', APP_TD ),
					'tip' => '',
				),
				array(
					'title' => __( 'Include Empty Stores', APP_TD ),
					'type' => 'checkbox',
					'name' => 'fl_store_thumbs_show_empty',
					'desc' => __( '<br />Include stores with no coupons listed under them?', APP_TD ),
					'tip' => '',
				),
				array(
					'title' => __( 'Singular "coupon"', APP_TD ),
					'type' => 'text',
					'name' => 'fl_store_thumbs_singular',
					'desc' => '<br />Singular for coupon, eg. 1 <em>coupon</em>',
					'value' => fl_get_option( 'fl_store_thumbs_singular' ),
				),
				array(
					'title' => __( 'Plural "coupons"', APP_TD ),
					'type' => 'text',
					'name' => 'fl_store_thumbs_plural',
					'desc' => '<br />Plural for coupon, eg. 5 <em>coupons</em>',
					'value' => fl_get_option( 'fl_store_thumbs_plural' ),
				),
			),
		);
		
		$this->tabs->add( 'coupons', __( 'Coupon Listings', APP_TD ) );
		
		$this->tab_sections['coupons']['navigation'] = array(
			'title' => __( 'Coupon Code', APP_TD ),
			'fields' => array(
				array(
					'title' => __( 'Force Open Affiliate Link', APP_TD ),
					'type' => 'checkbox',
					'name' => 'fl_coupon_force_affiliate',
					'desc' => __( '<br /><strong>Recommended!</strong> Enabling this option will try to force open the affiliate link in a new tab when clicking on "Show Coupon" button that opens the code in a popup to copy implemented in Clipper 1.5.1. It seems to not work sometimes with IE but it is better to have it enabled anyway.', APP_TD ),
				),
			),
		);

		$this->tab_sections['coupons']['labels'] = array(
			'title' => __( 'Button and Other Labels', APP_TD ),
			'fields' => array(
				array(
					'title' => __( 'Show Coupon', APP_TD ),
					'desc' => __( '<br />Appears on coupon list for coupon codes when they are hidden', APP_TD ),
					'tip' => __( 'Keep it short.', APP_TD ),
					'name' => 'fl_lbl_show_coupon',
					'type' => 'text',
					'value' => fl_get_option( 'fl_lbl_show_coupon' ),
				),
				array(
					'title' => __( 'Print Coupon', APP_TD ),
					'desc' => __( '<br />Appears on coupon list for printable coupons', APP_TD ),
					'tip' => __( 'Keep it short.', APP_TD ),
					'name' => 'fl_lbl_print_coupon',
					'type' => 'text',
					'value' => fl_get_option( 'fl_lbl_print_coupon' ),
				),
				array(
					'title' => __( 'Redeem Offer', APP_TD ),
					'desc' => __( '<br />Appears on coupon list for promo coupons', APP_TD ),
					'tip' => __( 'Keep it short.', APP_TD ),
					'name' => 'fl_lbl_redeem_offer',
					'type' => 'text',
					'value' => fl_get_option( 'fl_lbl_redeem_offer' ),
				),
				array(
					'title' => __( 'Learn More', APP_TD ),
					'type' => 'text',
					'name' => 'fl_lbl_learn_more',
					'desc' => __( '<br />Appears on the slider', APP_TD ),
					'tip' => __( 'Keep it short.', APP_TD ),
					'value' => fl_get_option( 'fl_lbl_learn_more'),
				),
				array(
					'title' => __( 'Leave a Comment', APP_TD ),
					'desc' => __( '<br />Appears as title on the comment form, replacing the duplicate title which shows above the list of comments as well', APP_TD ),
					'tip' => __( 'Keep it short.', APP_TD ),
					'name' => 'fl_lbl_leave_comment',
					'type' => 'text',
					'value' => fl_get_option( 'fl_lbl_leave_comment' ),
				),
			),
		);

	}
	
	function form_handler() {
		if ( empty( $_POST['action'] ) || ! $this->tabs->contains( $_POST['action'] ) )
			return;

		check_admin_referer( $this->nonce );

		$form_fields = array();

		foreach ( $this->tab_sections[ $_POST['action'] ] as $section )
			$form_fields = array_merge( $form_fields, $section['fields'] );

		$to_update = scbForms::validate_post_data( $form_fields, null, $this->options->get() );

		$this->options->update( $to_update, false );

		do_action( 'tabs_' . $this->pagehook . '_form_handler', $this );
		add_action( 'admin_notices', array( $this, 'admin_msg' ) );
	}
	
		function page_footer() {
		parent::page_footer();
?>
<script type="text/javascript">
jQuery(document).ready(function($) {
	
	fl_options_toggle($);
	$( 'input[name="fl_enable_dual_navigation"]' ).click( function() {
		fl_options_toggle($);
	});
	$( 'input[name="fl_coupon_popup_enable"]' ).click( function() {
		fl_options_toggle($);
	});
	
});
function fl_options_toggle($) {

	if( $( 'input[name="fl_enable_dual_navigation"]' ).is( ':checked' ) ) {
		$( '[name="fl_mobile_navigation"], [name="fl_navigation_share_coupon"], [name="fl_lbl_top_navigation"], [name="fl_lbl_share_coupon"], [name="fl_enable_category_mega_menu"], [name="fl_enable_store_mega_menu"]' ).parents('tr').show();
	} else {
		$( '[name="fl_mobile_navigation"], [name="fl_navigation_share_coupon"], [name="fl_lbl_top_navigation"], [name="fl_lbl_share_coupon"], [name="fl_enable_category_mega_menu"], [name="fl_enable_store_mega_menu"]' ).parents('tr').hide();
	}
	
	if( $( 'input[name="fl_coupon_popup_enable"]' ).is( ':checked' ) ) {
		$( '[name="fl_coupon_popup_description"], [name="fl_coupon_popup_button"]' ).parents('tr').show();
	} else {
		$( '[name="fl_coupon_popup_description"], [name="fl_coupon_popup_button"]' ).parents('tr').hide();
	}

}
</script>
<?php
	}
	
	function render_instructions( $section, $section_id ) {
		if( in_array( $section_id, array( 'instructions', 'popup_solution_info' ) ) ) {
			echo $section['description'];
		}
	}


}

// display the custom url meta field for the stores taxonomy
function fl_edit_stores( $tag, $taxonomy ) {
	
	$the_store_url = clpr_get_store_meta( $tag->term_id, 'clpr_store_url', true );
	$the_store_aff_url = clpr_get_store_meta( $tag->term_id, 'clpr_store_aff_url', true );
	$the_store_active = clpr_get_store_meta( $tag->term_id, 'clpr_store_active', true );
	$store_featured = clpr_get_store_meta( $tag->term_id, 'clpr_store_featured', true );
	$the_store_aff_url_clicks = clpr_get_store_meta( $tag->term_id, 'clpr_aff_url_clicks', true );
	// $clpr_store_image_url = clpr_get_store_meta( $tag->term_id, 'clpr_store_image_url', true );
	$clpr_store_image_id = clpr_get_store_meta( $tag->term_id, 'clpr_store_image_id', true );
	$clpr_store_image_preview = clpr_get_store_image_url( $tag->term_id, 'term_id', 100 );
?>

<tr class="form-field">
	<th scope="row" valign="top"><label for="clpr_store_url"><?php _e( 'Store URL', APP_TD ); ?></label></th>
	<td>
		<input type="text" name="clpr_store_url" id="clpr_store_url" value="<?php echo $the_store_url; ?>"/><br />
		<p class="description"><?php _e( 'The URL for the store (i.e. http://www.website.com)', APP_TD ); ?></p>
	</td>
</tr>

<tr class="form-field">
	<th scope="row" valign="top"><label for="clpr_store_aff_url"><?php _e( 'Destination URL', APP_TD ); ?></label></th>
	<td>
		<input type="text" name="clpr_store_aff_url" id="clpr_store_aff_url" value="<?php echo $the_store_aff_url; ?>"/><br />
		<p class="description"><?php _e( 'The affiliate URL for the store (i.e. http://www.website.com/?affid=12345)', APP_TD ); ?></p>
	</td>
</tr>

<tr class="form-field">
	<th scope="row" valign="top"><label for="clpr_store_aff_url_cloaked"><?php _e( 'Display URL', APP_TD ); ?></label></th>
	<td><?php echo clpr_get_store_out_url( $tag ); ?></td>
</tr>

<tr class="form-field">
	<th scope="row" valign="top"><label for="clpr_aff_url_clicks"><?php _e( 'Clicks', APP_TD ); ?></label></th>
	<td><?php echo esc_attr( $the_store_aff_url_clicks ); ?></td>
</tr>

<tr class="form-field">
	<th scope="row" valign="top"><label for="clpr_store_active"><?php _e( 'Store Active', APP_TD ); ?></label></th>
	<td>
		<select class="postform" id="clpr_store_active" name="clpr_store_active" style="min-width:125px;">
				<option value="yes" <?php selected( $the_store_active, 'yes' ); ?>><?php _e( 'Yes', APP_TD ); ?></option>
				<option value="no" <?php selected( $the_store_active, 'no' ); ?>><?php _e( 'No', APP_TD ); ?></option>
		</select>
	</td>
</tr>

<tr class="form-field">
	<th scope="row" valign="top"><label for="clpr_store_featured"><?php _e( 'Store Featured', APP_TD ); ?></label></th>
	<td>
		<input type="checkbox" value="1" name="clpr_store_featured" <?php checked( $store_featured ); ?>> <span class="description"><?php _e( 'Yes', APP_TD ); ?></span>
	</td>
</tr>

<tr class="form-field">
	<th scope="row" valign="top"><label for="clpr_store_url"><?php _e( 'Store Screenshot', APP_TD ); ?></label></th>
	<td>     			
		<span class="thumb-wrap">
			<a href="<?php echo $the_store_url; ?>" target="_blank"><img class="store-thumb" src="<?php echo clpr_get_store_image_url($tag->term_id, 'term_id', '250'); ?>" alt="" /></a>
		</span>
	</td>
</tr>

<tr class="form-field">
	<th scope="row" valign="top"><label for="clpr_store_image_id"><?php _e( 'Store Image <strong>(180x110)</strong>', APP_TD ); ?></label></th>
	<td>
		<div id="stores_image" style="float:left; margin-right:15px;"><img src="<?php echo $clpr_store_image_preview; ?>" width="100px" height="55px" /></div>
		<div style="line-height:75px;">
			<input type="hidden" name="clpr_store_image_id" id="clpr_store_image_id" value="<?php echo $clpr_store_image_id; ?>" />
			<button type="submit" class="button" id="button_add_image" rel="clpr_store_image_url"><?php _e( 'Add Image', APP_TD ); ?></button>
			<button type="submit" class="button" id="button_remove_image"><?php _e( 'Remove Image', APP_TD ); ?></button>
		</div>
		<div class="clear"></div>
		<p class="description"><?php _e( 'Choose custom image for the store. For best results with <strong>Flatter</strong> child theme, use the size 180x110.', APP_TD ); ?></p>
		<p class="description"><?php _e( 'Leave blank if you want use image generated by store URL.', APP_TD ); ?></p>
	</td>
</tr>
<script type="text/javascript">
	//<![CDATA[	
	jQuery(document).ready(function() {
		
		var formfield;
		
		if ( ! jQuery('#clpr_store_image_id').val() ) {
			jQuery('#button_remove_image').hide();
		} else {
			jQuery('#button_add_image').hide();
		}
		
		jQuery( document ).on('click', '#button_add_image', function() {
			formfield = jQuery(this).attr('rel');
			tb_show('', 'media-upload.php?post_id=0&amp;type=image&amp;taxonomy=<?php echo APP_TAX_STORE; ?>&amp;TB_iframe=true');
			return false;
		});
		
		jQuery( document ).on('click', '#button_remove_image', function() {
			jQuery('#stores_image img').attr('src', '<?php echo appthemes_locate_template_uri('images/clpr_default.jpg'); ?>');
			jQuery('#clpr_store_image_id').val('0');
			jQuery('#button_remove_image').hide();
			jQuery('#button_add_image').show();
			return false;
		});
		
		window.original_send_to_editor = window.send_to_editor;
		
		window.send_to_editor = function(html) {
			if ( formfield ) {
				var imageClass = jQuery('img', html).attr('class');
				var imageID = parseInt(/wp-image-(\d+)/.exec(imageClass)[1], 10);
				var imageURL = jQuery('img', html).attr('src');
				
				jQuery('input[name=clpr_store_image_id]').val(imageID);
				jQuery('#stores_image img').attr('src', imageURL);
				jQuery('#button_remove_image').show();
				jQuery('#button_add_image').hide();
				tb_remove();
				formfield = null;
			} else {
				window.original_send_to_editor(html);
			}
		}
		
	});
	//]]>
</script>

<?php
}
add_action( 'stores_edit_form_fields', 'fl_edit_stores', 10, 2 );


// add extra fields to the create store admin page
function fl_add_store_fields( $tag ) {
?>

<div class="form-field">
	<label for="clpr_store_url"><?php _e( 'Store URL', APP_TD ); ?></label>
	<input type="text" name="clpr_store_url" id="clpr_store_url" value="" />
	<p class="description"><?php _e( 'The URL for the store (i.e. http://www.website.com)', APP_TD ); ?></p>
</div>

<div class="form-field">
	<label for="clpr_store_image_id"><?php _e( 'Store Image <strong>(180x110)</strong>', APP_TD ); ?></label>
	<div id="stores_image" style="float:left; margin-right:15px;"><img src="<?php echo appthemes_locate_template_uri('images/clpr_default.jpg'); ?>" width="100px" height="55px" /></div>
	<div style="line-height:75px;">
		<input type="hidden" name="clpr_store_image_id" id="clpr_store_image_id" value="" />
		<button type="submit" class="button" id="button_add_image" rel="clpr_store_image_url"><?php _e( 'Add Image', APP_TD ); ?></button>
		<button type="submit" class="button" id="button_remove_image"><?php _e( 'Remove Image', APP_TD ); ?></button>
	</div>
	<div class="clear"></div>
	<p class="description"><?php _e( 'Choose custom image for the store. For best results with <strong>Flatter</strong> child theme, use the size 180x110.', APP_TD ); ?></p>
	<p class="description"><?php _e( 'Leave blank if you want use image generated by store URL.', APP_TD ); ?></p>
</div>
<script type="text/javascript">
	//<![CDATA[	
	jQuery(document).ready(function() {
		
		var formfield;
		
		if ( ! jQuery('#clpr_store_image_id').val() ) {
			jQuery('#button_remove_image').hide();
		} else {
			jQuery('#button_add_image').hide();
		}
		
		jQuery( document ).on('click', '#button_add_image', function() {
			formfield = jQuery(this).attr('rel');
			tb_show('', 'media-upload.php?post_id=0&amp;type=image&amp;taxonomy=<?php echo APP_TAX_STORE; ?>&amp;TB_iframe=true');
			return false;
		});
		
		jQuery( document ).on('click', '#button_remove_image', function() {
			jQuery('#stores_image img').attr('src', '<?php echo appthemes_locate_template_uri('images/clpr_default.jpg'); ?>');
			jQuery('#clpr_store_image_id').val('0');
			jQuery('#button_remove_image').hide();
			jQuery('#button_add_image').show();
			return false;
		});
		
		window.original_send_to_editor = window.send_to_editor;
		
		window.send_to_editor = function(html) {
			if ( formfield ) {
				var imageClass = jQuery('img', html).attr('class');
				var imageID = parseInt(/wp-image-(\d+)/.exec(imageClass)[1], 10);
				var imageURL = jQuery('img', html).attr('src');
				
				jQuery('input[name=clpr_store_image_id]').val(imageID);
				jQuery('#stores_image img').attr('src', imageURL);
				jQuery('#button_remove_image').show();
				jQuery('#button_add_image').hide();
				tb_remove();
				formfield = null;
			} else {
				window.original_send_to_editor(html);
			}
		}
		
	});
	//]]>
</script>

<?php
}
add_action( 'stores_add_form_fields', 'fl_add_store_fields', 10, 2 );