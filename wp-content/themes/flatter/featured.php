<?php
/**
 * The featured slider on the home page
 *
 */
 
global $clpr_options;
?>

<?php if ( $featured = clpr_get_featured_slider_coupons() ) : ?>

<div class="featured-slider">
	
	<div class="head">
		
		<h2><?php _e( 'Featured Coupons', APP_TD ); ?></h2>
	
	</div>

    <div class="gallery-c">

        <div class="gallery-holder">
		
			<div class="link-l">

				<a href="#" class="prev"><?php _e( 'prev', APP_TD ); ?></a>

			</div>

            <div class="slide">

                <div class="slide-contain">

                    <ul class="slider">

                    <?php while ( $featured->have_posts() ) : $featured->the_post(); ?>

                        <li>
						
							<div class="wrapper">

								<div class="image">

									<a href="<?php echo appthemes_get_custom_taxonomy($post->ID, APP_TAX_STORE, 'slug'); ?>"><img height="110" width="180" src="<?php echo fl_get_store_image_url($post->ID, 'post_id', '180'); ?>" alt="" /></a>

								</div>

								<h3><?php fl_coupon_title( 40 ); ?></h3>
								
								<p class="store-name"><?php echo get_the_term_list($post->ID, APP_TAX_STORE, ' ', ', ', ''); ?></p>
								
								<?php if ( $clpr_options->link_single_page ) : ?>
									<a class="btn blue" href="<?php the_permalink(); ?>"><?php echo fl_get_option( 'fl_lbl_learn_more' ); ?></a>
								<?php else : ?>
									<?php fl_coupon_code_box(); ?>
								<?php endif; ?>
							
							</div>

                        </li>

                    <?php endwhile; ?>

                    </ul>

                </div>

            </div>
			
			 <div class="link-r">

				<a href="#" class="next"><?php _e( 'next', APP_TD ); ?></a>

			</div>


        </div>

    </div>

</div>

<?php endif; ?>

<?php wp_reset_postdata(); ?>
				