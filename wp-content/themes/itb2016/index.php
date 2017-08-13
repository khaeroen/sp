<?php get_header(); ?>

<section class="content">

	<?php //get_template_part('inc/page-title'); ?>

	<?php if ( is_home() && !is_paged()): ?>

		<div class="featured">
			<?php echo do_shortcode( '[recent_post_slider design="design-3" show_category_name="false" content_words_limit="10" recent_post_slider show_author="false"]' ); ?>
		</div><!--/.featured-->
		<hr>

	<?php endif; ?>
	
	<div class="pad group" style="text-align: left;">
		<h1 style="padding-left: 10px;">Berita Terbaru</h1>
		<?php /* get_template_part('inc/featured');*/ ?>
		
		<?php if ( have_posts() ) : ?>
		
			<?php if ( ot_get_option('blog-standard') == 'on' ): ?>
				<?php while ( have_posts() ): the_post(); ?>
					<?php get_template_part('content-standard'); ?>
				<?php endwhile; ?>
			<?php else: ?>
			<div class="post-list group">
				<?php $i = 0; echo '<div class="post-row">'; while ( have_posts() && $i < 9 ): the_post(); ?>
					<?php get_template_part('content'); ?>
				<?php if(($i == 2) || ($i == 5)) { echo '</div><div class="post-row">'; } $i++; endwhile; echo '</div>'; ?>
			</div><!--/.post-list-->
			<?php endif; ?>
		
			<?php get_template_part('inc/pagination'); ?>
			
		<?php endif; ?>
		
	</div><!--/.pad-->
	
</section><!--/.content-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>