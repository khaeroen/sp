<?php 
global $separator;
global $parents; 
global $post_id;
global $post; ?> 
 <div class="post-slides">
	<div class="post-list">
		<div class="post-list-content">
		<div class="wp-medium-5 wpcolumns">
		<div class="post-image-bg">
			<?php the_post_thumbnail('url'); ?>
			</div>
			
			</div>
			<div class="wp-medium-7 wpcolumns">
			<?php if($showCategory == "true") { ?>
	<div class="recentpost-categories">		
			<?php echo get_the_category_list( $separator, $parents, $post_id ); ?>
		</div>
	<?php } ?>
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