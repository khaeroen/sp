<?php get_header(); ?>

<section class="content">

	<?php get_template_part('inc/page-title'); ?>
	
	<div class="pad group">
		
		<?php get_template_part('inc/featured'); ?>
		
		<?php if ( have_posts() ) : ?>
		
			<div class="post-list group">
				<?php $i = 1; echo '<div class="post-row">'; while ( have_posts() ): the_post(); ?>
				<?php get_template_part('content'); ?>
				<?php if($i % 3 == 0) { echo '</div><div class="post-row">'; } $i++; endwhile; echo '</div>'; ?>
			</div><!--/.post-list-->
		
			
			
		<?php endif; ?>
		
		<div class="post-list group" style="margin-top:20px;">
			<div class="post-row">
			
				<div class="span3 sixbox">
					<div class="post-inner post-hover" style="background-color:#09f;">
						<div class="news-block">
							<div class="p-thumb">
								<?php
									define('ALAMAT_SUMBER0', 'http://dcpusat.itb.ac.id/');
								define('ALAMAT_SUMBER', 'http://dcpusat.itb.ac.id/v3/');
									
									$ch = curl_init();
									curl_setopt($ch, CURLOPT_URL, ALAMAT_SUMBER."dc_charter/statistik_klasifikasi_sumber_beasiswa/2012");
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
									$output = curl_exec($ch);
									curl_close($ch);
								?>
								<a href="http://www.usdi.itb.ac.id/datainfo/sumber-beasiswa-dan-jumlah-penerima-beasiswa/"><img class="aligncenter size-medium wp-image-289" title="Statistik Berdasarkan Sumber Beasiswa" src="<?=$output?>" alt="" /></a>
							</div>
							<div class="news-pad">
								<p><a href="http://www.usdi.itb.ac.id/datainfo/sumber-beasiswa-dan-jumlah-penerima-beasiswa/">Sumber Beasiswa dan Jumlah Penerima Beasiswa</a></p>
							</div>
						</div>
					</div>
				</div>

			<div class="span3 sixbox">
				<div class="post-inner post-hover" style="background-color:#FEB100;">
					<div class="news-block">
						<div class="p-thumb">
						</div>
						<div class="news-pad">
							<p><a href="http://167.205.108.170/wp-itb/?page_id=197">Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit.</a></p>
						</div>
					</div>
				</div>
			</div>

			<div class="span3 sixbox">
				<div class="post-inner post-hover"  style="background-color:#71C246;">
					<div class="news-block">
						<div class="p-thumb">
						</div>
						<div class="news-pad">
							<p><a href="http://167.205.108.170/wp-itb/?page_id=197">Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit.</a></p>
						</div>
					</div>
				</div>
			</div>

			<div class="span3 sixbox">
				<div class="post-inner post-hover"  style="background-color:#D65BFF;">
					<div class="news-block">
						<div class="p-thumb">
						</div>
						<div class="news-pad">
							<p><a href="http://167.205.108.170/wp-itb/?page_id=197">Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit.</a></p>
						</div>
					</div>
				</div>
			</div>
			
		</div><!--/.pad-->
		
		
			
		</div>
	</div>
</section><!--/.content-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>