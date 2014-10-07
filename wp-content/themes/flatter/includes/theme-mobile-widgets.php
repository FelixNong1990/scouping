<?php

function fl_add_mobile_checkbox( $widget, $return, $instance ) {
	$checked = $instance['hide_on_mobile'] == 1 ? 'checked' : '';
	?>
	<p class="hide_on_mobile" style="padding:5px; background:#CFF1F9;">
		<input type="checkbox" class="checkbox" id="<?php echo $widget->get_field_id( 'hide_on_mobile' ); ?>" name="<?php echo $widget->get_field_name( 'hide_on_mobile' ); ?>" value="1" <?php echo $checked; ?> />
		&nbsp;<label for="<?php echo $widget->get_field_id( 'hide_on_mobile' ); ?>"><?php _e( 'Hide on Mobile Devices', APP_TD ); ?></label>
	</p>
	<?php
}
add_action( 'in_widget_form', 'fl_add_mobile_checkbox', 25, 3 );

function fl_widget_update_callback( $instance, $new_instance ) {
	
	$instance['hide_on_mobile'] = $new_instance['hide_on_mobile'];
	return $instance;
	
}
add_filter( 'widget_update_callback', 'fl_widget_update_callback', 10, 2 );

function fl_widget_display_callback( $instance, $widget ) {
	
	if( isset( $instance['hide_on_mobile'] ) && wp_is_mobile() ) {
		return false;
	}
	
   return $instance;
   
}
add_filter( 'widget_display_callback', 'fl_widget_display_callback', 10, 2 );

function fl_add_mobile_class( $params ) {
	
	global $wp_registered_widgets, $widget_number;
	
	$arr_registered_widgets = wp_get_sidebars_widgets(); // Get an array of ALL registered widgets
	$this_id                = $params[0]['id']; // Get the id for the current sidebar we're processing
	$widget_id              = $params[0]['widget_id'];
	$widget_obj             = $wp_registered_widgets[$widget_id];
	$widget_num             = $widget_obj['params'][0]['number'];
	
	$widget_opt = get_option( $widget_obj['callback'][0]->option_name );
	
	if( isset( $widget_opt[$widget_num]['hide_on_mobile'] ) ) {
		$params[0]['before_widget'] = preg_replace( '/class="/', "class=\"tb-hide-on-mobile ", $params[0]['before_widget'], 1 );
	}
	
	return $params;
	
}
add_filter( 'dynamic_sidebar_params', 'fl_add_mobile_class' );