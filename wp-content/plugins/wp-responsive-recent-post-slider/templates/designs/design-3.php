  <?php 
global $separator;
global $parents; 
global $post_id;
global $post;
$feat_image = wp_get_attachment_url( get_post_thumbnail_id($post_id) ); ?> 
 
 <div class="post-slides">
	<div class="post-overlay">
	<div class="post-image-bg">
		<img src="<?php echo $feat_image; ?>"alt="<?php the_title(); ?>" />
	</div>
	<?php if($showCategory == "true") { ?>
	<div class="recentpost-categories">		
			<?php echo get_the_category_list( $separator, $parents, $post_id ); ?>
		</div>
	<?php } ?>
	<div class="post-short-content">
	<div class="item-meta bottom">
			  <h2 class="wp-post-title">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h2>
	<?php if($showDate == "true" || $showAuthor == 'true')    {  ?>	
			<div class="wp-post-date">		
				<?php  if($showAuthor == 'true') { ?> <span><?php  esc_html_e( 'By', 'wp-responsive-recent-post-slider' ); ?> <?php the_author(); ?></span><?php } ?>
				<?php echo ($showAuthor == 'true' && $showDate == 'true') ? '&nbsp;/&nbsp;' : '' ?>
				<?php if($showDate == "true") { echo get_the_date(); } ?>
				</div>
				<?php }   ?>
		
				<?php if($showContent == "true") {  ?>	
				<div class="wp-post-content">
					<?php
					$customExcerpt = get_the_excerpt();				
					if (has_excerpt($post->ID))  { ?>
						<div class="wp-sub-content"><?php echo $customExcerpt ; ?></div> 
					<?php } else {
						$excerpt = strip_tags(get_the_content()); ?>
					<div class="wp-sub-content"><?php echo wprps_limit_words($excerpt,$words_limit); ?></div>					
					<?php } ?>

					<?php if($showreadmore == 'true') { ?>
						<a class="readmorebtn" href="<?php the_permalink(); ?>"><?php _e('Read More', 'wp-responsive-recent-post-slider'); ?></a>
					<?php } ?>

				</div>
				<?php } ?>
				</div>
		</div>
		</div>
	</div>