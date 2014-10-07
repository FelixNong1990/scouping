<?php
global $fl_defaults;

$fl_defaults = array(
	// button labels
	'fl_lbl_learn_more' => __( 'Learn More', 'clipper' ),
	'fl_lbl_show_coupon' => __( 'Show Coupon', 'clipper' ),
	'fl_lbl_print_coupon' => __( 'Print Coupon', 'clipper' ),
	'fl_lbl_redeem_offer' => __( 'Redeem Offer', 'clipper' ),
	'fl_lbl_leave_comment' => __( 'Leave a Comment', 'clipper' ),
	'fl_lbl_tinynav' => __( 'Navigation', 'clipper' ),
	'fl_enable_dual_navigation' => false,
	'fl_navigation_share_coupon' => false,
	'fl_mobile_navigation' => 'select',
	'fl_lbl_top_navigation' => __( 'Go to', 'clipper' ),
	'fl_lbl_share_coupon' => __( 'Share Coupon', 'clipper' ),
	'fl_store_thumbs_area' => 'below_coupons',
	'fl_store_thumbs_title' => __( 'Popular Stores', 'clipper' ),
	'fl_store_thumbs_number' => '21',
	'fl_store_thumbs_orderby' => 'count',
	'fl_store_thumbs_order' => 'DESC',
	'fl_store_thumbs_singular' => __( 'coupon', 'clipper' ),
	'fl_store_thumbs_plural' => __( 'coupons', 'clipper' ),
	'fl_coupon_popup_description' => __( 'Here\'s your %store_name% promo code:', 'clipper' ),
	'fl_coupon_popup_button' => __( 'Visit %store_name% Website', 'clipper' ),
);

function fl_get_option( $option ) {
	global $fl_defaults, $clpr_options;
	
	if( $clpr_options->{$option} )
		$option_value = $clpr_options->{$option};
	else
		$option_value = get_option( $option );
	
	if( empty( $option_value ) && array_key_exists( $option, $fl_defaults ) )
		$option_value = $fl_defaults[$option];
		
	return $option_value;
}
	