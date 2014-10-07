<?php $sq = get_search_query() ? get_search_query() : __( 'Search for coupon codes', APP_TD ); ?>

<div class="search-box">

	<form method="get" class="search" id="searchform" action="<?php echo home_url('/'); ?>" >

	   <button  name="Search" value="Search" id="Search" title="<?php _e( 'Search', APP_TD ); ?>" type="submit" class="btn-submit"><i class="icon-search icon-large"></i><?php _e( 'Search', APP_TD ); ?></button>
			
		<input type="text" class="newtag" name="s" placeholder="<?php _e( 'Search for coupon codes', APP_TD ); ?>" />
		
	</form>

</div>