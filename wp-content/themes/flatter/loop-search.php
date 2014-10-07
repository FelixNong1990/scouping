<?php
/**
 * The loop that displays the coupons and blog posts.
 *
 * @package AppThemes
 * @subpackage Clipper
 *
 */


// hack needed for "<!-- more -->" to work with templates
// call before the loop
global $clpr_options, $more;
$more = 0;
?>

<?php appthemes_before_loop('search'); ?>

<?php if (have_posts()) : ?>

	<?php while (have_posts()) : the_post(); ?>

		<?php switch($post->post_type):
			case 'coupon': ?>

				<?php appthemes_before_post('search'); ?>

				<div <?php post_class('item'); ?> id="post-<?php echo $post->ID; ?>">

					<div class="item-holder">
						
						<div class="store-holder">
							<div class="store-image">
								<a href="<?php echo appthemes_get_custom_taxonomy($post->ID, APP_TAX_STORE, 'slug'); ?>"><img height="110" width="180" src="<?php echo fl_get_store_image_url($post->ID, 'post_id', '180'); ?>" alt="" /></a>
							</div>
							<div class="store-name">
								<?php echo get_the_term_list($post->ID, APP_TAX_STORE, ' ', ', ', ''); ?>
							</div>
						</div>
						
						<div class="item-frame">

								<div class="item-panel">

									<div class="clear"></div>								
										
										<?php appthemes_before_post_title( 'search' ); ?>

										<h3><?php clpr_coupon_title(); ?></h3>
										
										<?php appthemes_after_post_title( 'search' ); ?>
										
										<?php appthemes_before_post_content( 'search' ); ?>

										<p class="desc"><?php clpr_coupon_content(); ?></p>
										
										<?php appthemes_after_post_content( 'search' ); ?> 	
											
								</div> <!-- #item-panel -->

							<div class="clear"></div>

							<div class="taxonomy">
								<p class="category"><?php _e( 'Category:', APP_TD ); ?> <?php echo get_the_term_list($post->ID, APP_TAX_CAT, ' ', ', ', ''); ?></p>
								<p class="tag"><?php _e( 'Tags:', APP_TD ); ?> <?php echo get_the_term_list($post->ID, APP_TAX_TAG, ' ', ', ', ''); ?></p>
							</div>	

						</div> <!-- #item-frame -->
						
						<div class="item-actions">
							
							<?php clpr_vote_box_badge( $post->ID ); ?>
					
							<?php if( get_post_meta( $post->ID, 'clpr_expire_date', true ) ) { ?>
								<p class="time-left iconfix"><i class="icon-bell"></i><?php echo clpr_get_expire_date($post->ID, 'display'); ?></p>
							<?php } ?>
							
							<?php // display additional info if coupon is expired
							fl_display_expired_info( $post->ID ); ?>
							
							<?php fl_coupon_code_box(); ?>
							
						</div>

						<div class="item-footer">

							<ul class="social">
							
								<li class="stats">
									<?php if ( $clpr_options->stats_all && current_theme_supports( 'app-stats' ) ) { ?>
										<i class="icon-bar-chart"></i>
										<?php appthemes_stats_counter( $post->ID ); 
									} ?>
								</li>
								<li><i class="icon-share"></i><a class="share" href="#"><?php _e( 'Share', APP_TD ); ?> </a>

									<?php get_template_part( 'share', 'loop' ); ?>

								</li>
								
								<li><i class="icon-comments"></i><?php clpr_comments_popup_link( '<span>0</span> ' . __( 'Comments', APP_TD ), '<span>1</span> ' . __( 'Comment', APP_TD ), __( '<span>%</span> Comments', APP_TD ), 'show-comments' ); // leave spans for ajax to work correctly ?></li>
								
								<?php clpr_report_coupon(true);?>
								
							</ul>

							<div id="comments-<?php echo $post->ID; ?>" class="comments-list">

								<p class="links"><i class="icon-pencil"></i><?php if (comments_open()) clpr_comments_popup_link( __( 'Add a comment', APP_TD ), __( 'Add a comment', APP_TD ), __( 'Add a comment', APP_TD ), 'mini-comments' ); else echo '<span class="closed">' . __( 'Comments closed', APP_TD ) . '</span>'; ?><i class="icon-remove"></i><?php clpr_comments_popup_link( __( 'Close comments', APP_TD ), __( 'Close comments', APP_TD ), __( 'Close comments', APP_TD ), 'show-comments' ); ?></p>
								
								<?php global $withcomments; $withcomments = 1; ?>
								
								<?php comments_template('/comments-mini.php'); ?>

							</div>


						</div>

					</div>

				</div>

				<?php appthemes_after_post('search'); ?>

			<?php break; ?>
			<?php case 'post': ?>

				<?php appthemes_before_post('search'); ?>

				<div <?php post_class('content-box'); ?> id="post-<?php the_ID(); ?>">

					<div class="box-c">
						
						<div class="box-holder">
							
							<div class="blog cf">
								<?php $destination_url = get_field('destination_url'); ?>
								<div class="left_article_content">
									<span class="article-date"><i class="icon-calendar"></i><?php the_time('M j, Y') ?></span>
									<!--<span class="article-author"><i class="icon-calendar"></i>August 7, 2013</span>-->
									<?php 
										if (has_post_thumbnail()) the_post_thumbnail('thumbnail'); 
										//echo $thumb = get_the_post_thumbnail(get_the_ID(), 'thumb-large');
									?>
								</div>
								
								<div class="right_article_content">
									<h1 class="article-header">
										<a target="_blank" href="<?php echo $destination_url; ?>" class="button"><?php the_title(); ?></a>
									</h1>
									<p class="article-description">
										<?php echo wp_trim_words( get_the_content(), 50 ); ?>
									</p>
									<div class="review-buttons">
										<a target="_blank" href="<?php echo $destination_url; ?>" class="button"><?php _e( 'Read Full Review', APP_TD ); ?></a>
									</div>
								</div>
							</div>
							
						</div>
						
					</div>

				</div>

				<?php appthemes_after_post('search'); ?>

			<?php break; ?>
			<?php case 'page': ?>

				<?php appthemes_before_post('search'); ?>

				<div <?php post_class('content-box'); ?> id="post-<?php the_ID(); ?>">

					<div class="box-c">
						
						<div class="box-holder">
							
							<div class="blog">
								
								<?php appthemes_before_blog_post_title(); ?>
								
								<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
								
								<?php appthemes_after_blog_post_title(); ?>
								
								<?php appthemes_before_blog_post_content(); ?>
								
								<div class="text-box">
									
									<?php if (has_post_thumbnail()) the_post_thumbnail(); ?>
									
									<?php the_content( '<p>' . __( 'Continue reading &raquo;', APP_TD ) . '</p>' ); ?>
									
									<?php edit_post_link( __( 'Edit Post', APP_TD ), '<p class="edit">', '</p>' ); ?>
									
								</div>
								
								<?php appthemes_after_blog_post_content(); ?>
								
							</div>
							
						</div>
						
					</div>

				</div>


				<?php appthemes_after_post('search'); ?>

			<?php break; ?>
		<?php endswitch; ?>

<?php endwhile; ?>


	<?php appthemes_after_endwhile('search'); ?>


<?php else: ?>


	<?php appthemes_loop_else('search'); ?>


<div class="blog">

		<h3><?php _e( 'Sorry, no coupons found', APP_TD ); ?></h3>

</div> <!-- #blog -->


<?php endif; ?>

<?php appthemes_after_loop('search'); ?>

