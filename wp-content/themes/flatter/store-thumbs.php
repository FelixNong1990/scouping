<?php
/**
 * The featured slider on the home page
 *
 */
 
global $clpr_options;

$hidden_stores = clpr_hidden_stores();

$args = array(
	'orderby' => fl_get_option( 'fl_store_thumbs_orderby' ),
	'order' => fl_get_option( 'fl_store_thumbs_order' ),
	'number' => fl_get_option( 'fl_store_thumbs_number' ),
	'exclude' => $hidden_stores,
);

if( fl_get_option( 'fl_store_thumbs_featured_only' ) ) :

	$featured_stores = clpr_featured_stores();
	$featured_stores = array_diff( $featured_stores, $hidden_stores );
	$args['include'] = $featured_stores;

endif;

if( fl_get_option( 'fl_store_thumbs_show_empty' ) ) :
	$args['hide_empty'] = false;
endif;

if ( $stores = fl_get_terms( APP_TAX_STORE, $args ) ) : ?>

<div class="store-thumbs">
	
	<div class="head">
		
		<h2><?php echo fl_get_option( 'fl_store_thumbs_title' ); ?></h2>
	
	</div>

    <ul class="store-thumb-list clearfix">
	
		<?php foreach( $stores as $store ) : ?>
		
			<li class="lazy" style="background-image: url('<?php echo content_url(); ?>/images/blank.gif'); background-size: 100% 100%;" data-original="<?php echo fl_get_store_image_url( $store->term_id, 'term_id', '110', '50' ); ?>">
				<a href="<?php echo get_term_link( $store, APP_TAX_STORE ); ?>"><span class="store-count"><?php echo $store->count; ?></span> <?php echo _n( fl_get_option( 'fl_store_thumbs_singular' ), fl_get_option( 'fl_store_thumbs_plural' ),  $store->count, APP_TD ); ?></a>
			</li>
		
		<?php endforeach; ?>
	
	</ul>
	
</div>

<?php endif; ?>

<?php wp_reset_postdata(); ?>
				