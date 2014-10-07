<?php

// Do not delete these lines
if ( !empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']) )
	die( __( 'Please do not load this page directly. Thanks!', APP_TD ) );

if ( post_password_required() ) { ?> 

	<p><?php _e( 'This post is password protected. Enter the password to view comments.', APP_TD ); ?></p> 
	
<?php
	return;
}
?>	

<?php appthemes_before_blog_comments(); ?>

<?php if ( have_comments() ) : ?>

<div class="content-box" id="comments">
	
    <div class="box-c">
	
        <div class="box-holder">
		
            <div class="comments-box">
			
                <div class="head iconfix">
				
                    <h3><i class="icon-comments"></i> <?php comments_number( __( 'No Responses', APP_TD ), __( 'One Response', APP_TD ), __( '% Responses', APP_TD ) );?> <?php _e( 'to', APP_TD ); ?> &#8220;<?php the_title(); ?>&#8221;</h3>
					
                </div>
				
                <ul class="comments">
				
                    <?php wp_list_comments( array( 'callback' => 'fl_comment_template' ) ); ?>
					
                </ul>
				
				<div class="comment-paging">

					<?php paginate_comments_links(); ?>

				</div>
				
            </div>
			
        </div>
		
    </div>
	
</div>

<?php endif; ?>

<?php appthemes_after_blog_comments(); ?>




<?php if(!empty($comments_by_type['pings'])) : ?>

	<div class="content-box">
		
		<div class="box-c">
		
			<div class="box-holder">
			
				<div class="post-box">
				
					<div class="head iconfix">
					
						<h3><i class="icon-arrow-left"></i> <?php _e( 'Trackbacks/Pingbacks', APP_TD ); ?></h3>						
						
					</div>

					<ul class="comments">
					
						<?php wp_list_comments( array( 'type' => 'pings' ) ); ?>
						
					</ul>
					
				</div>
				
			</div>
			
		</div>
	
	</div>		

<?php endif; ?>



<?php appthemes_before_blog_respond(); ?>

<?php if ( ! comments_open() && have_comments() ) : ?>

	<div class="content-box">
		
		<div class="box-c">
		
			<div class="box-holder">
			
				<div class="post-box">
				
					<div class="head iconfix">
					
						<h3><i class="icon-comment"></i> <?php echo fl_get_option( 'fl_lbl_leave_comment' ); ?> </h3>
						
					</div>

					<div class="pad5">&nbsp;</div>
					
						<p><?php _e( 'Sorry, we are no longer accepting new comments at this time.', APP_TD ); ?></p>
						
					<div class="pad5">&nbsp;</div>
					
				</div>
				
			</div>
			
		</div>
	
	</div>	

<?php endif; ?>



<?php if ( comments_open() ) : ?>

	<div class="content-box" id="reply">
		
		<div class="box-c">
		
			<div class="box-holder">
			
				<div class="post-box">
				
					<div class="head iconfix">
					
						<h3><i class="icon-comment"></i> <?php echo fl_get_option( 'fl_lbl_leave_comment' ); ?> </h3>
						
					</div>
					
					<?php appthemes_before_blog_comments_form(); ?>				
					
					<?php appthemes_blog_comments_form(); ?>
						
					<?php appthemes_after_blog_comments_form(); ?>
					
				</div>
				
			</div>
			
		</div>
		
	</div>

<?php endif; ?>

<?php appthemes_after_blog_respond(); ?>

