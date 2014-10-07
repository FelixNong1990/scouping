<?php
/**
 * The loop that displays the blog posts.
 *
 * @package AppThemes
 * @subpackage Clipper
 *
 */


// hack needed for "<!-- more -->" to work with templates
// call before the loop
global $more;
$more = 0;
?>

<?php appthemes_before_blog_loop(); ?>

<?php if ( have_posts() ) : ?>

	<?php while ( have_posts() ) : the_post(); ?>

		<?php if ( is_single() ) appthemes_stats_update( $post->ID ); //records the page hit on single blog page view ?>

		<?php appthemes_before_blog_post(); ?>

		<div <?php post_class('content-box'); ?> id="post-<?php the_ID(); ?>">

			<div class="box-c">



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

		<?php appthemes_after_blog_post(); ?>


	<?php endwhile; ?>

	<?php appthemes_after_blog_endwhile(); ?>


<?php else: ?>


	<?php appthemes_blog_loop_else(); ?>

	<div class="content-box">

		<div class="box-c">

			<div class="box-holder">

				<div class="blog">

					<h4><?php _e( 'No Posts Found', APP_TD ); ?></h4>

					<div class="text-box">

						<?php _e( 'Sorry, no posts found.', APP_TD ); ?>

					</div>

				</div>

			</div>

		</div>

	</div>


<?php endif; ?>

<?php appthemes_after_blog_loop(); ?>
