<?php
//set theme options
$oswc_ad_shuffle=$oswc_ads['ad_shuffle'];
$oswc_ad1 = $oswc_ads['ad1'];
$oswc_ad2 = $oswc_ads['ad2'];
$oswc_ad3 = $oswc_ads['ad3'];
$oswc_ad4 = $oswc_ads['ad4'];
$oswc_ad5 = $oswc_ads['ad5'];
$oswc_ad6 = $oswc_ads['ad6'];
$oswc_ad7 = $oswc_ads['ad7'];
$oswc_ad8 = $oswc_ads['ad8'];
$oswc_ad9 = $oswc_ads['ad9'];
$oswc_ad10 = $oswc_ads['ad10'];
$oswc_archive_sidebar_unique = $oswc_other['archive_sidebar_unique'];
$oswc_archive_sidebar_show = $oswc_other['archive_sidebar_show'];
$oswc_archive_layout = $oswc_other['archive_layout'];
$oswc_archive_header = $oswc_other['archive_header'];
$oswc_archive_meta_enabled = $oswc_other['archive_meta_enabled'];
$oswc_archive_excerpt_enabled = $oswc_other['archive_excerpt_enabled'];
$oswc_archive_trending_show = $oswc_other['archive_trending_enabled'];

//setup ads array
$ads = array($oswc_ad1,$oswc_ad2,$oswc_ad3,$oswc_ad4,$oswc_ad5,$oswc_ad6,$oswc_ad7,$oswc_ad8,$oswc_ad9,$oswc_ad10);
if($oswc_ad_shuffle) {
	shuffle($ads);
}
?>

<?php
get_header(); // show header

// user specified a unique archive sidebar
if ($oswc_archive_sidebar_unique) {
	$sidebar="Archive Sidebar";
} else {
	$sidebar="Default Sidebar";
}
//get proper thumbnail size based on layout
switch ($oswc_archive_layout) {
	case "A":
		$thumbnailsize="spotlight";
		if($oswc_archive_sidebar_show) {
			$cols=2;
		} else {
			$cols=3;
		}
		break;
	case "B":
		$thumbnailsize="loop-large";
		if(!$oswc_archive_sidebar_show) {
			$thumbnailsize="loop-large-full";
		}
		$cols=1;
		break;
	case "C":
		$thumbnailsize="spotlight";
		$cols=1;
}	
?>

<?php // setup header text
if($oswc_archive_header=="") {
	$post = $posts[0]; // Hack. Set $post so that the_date() works.
	if (is_category()) {
		$oswc_archive_header = single_cat_title('', false);
	} elseif( is_tag() ) {
		$oswc_archive_header = __("Posts Tagged &#8216;".single_tag_title('', false)."&#8217;", "itb");
	} elseif (is_day()) {
		//$oswc_archive_header = __("Archive for ".date_i18n('F jS, Y'), "itb");
		$oswc_archive_header = __("Archive for ".get_the_date('F jS, Y'), "itb");
	} elseif (is_month()) {
		//$oswc_archive_header = __("Archive for ".date_i18n('F, Y'), "itb");
		$oswc_archive_header = __("Archive for ".get_the_date('F, Y'), "itb");
	} elseif (is_year()) {
		//$oswc_archive_header = __("Archive for ".date_i18n('Y'), "itb");
		$oswc_archive_header = __("Archive for ".get_the_date('Y'), "itb");
	} elseif (is_author()) {
		$oswc_archive_header = __("Author Archive", "itb");
	} elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {
		$oswc_archive_header = __("Blog Archives", "itb");
	} 
} ?>

<div class="main-content<?php if($oswc_archive_sidebar_show) { ?>-left<?php } ?>"> 
		
    <div class="post-loop">  
        
        <div class="section-wrapper">
        
            <div class="section">
            
                <?php echo $oswc_archive_header; ?>
            
            </div>        
        
        </div>
        
        
            
        <?php	
        if (have_posts()) : while (have_posts()) : the_post(); $postcount++;       
            
            
                                                                
            $thisPostType = get_post_type(); //get post type
            $thisReviewType = $oswcPostTypes->get_type_by_id($thisPostType); //get review type object	
            $isreview=false;
            if($thisPostType!='post') $isreview=true; //set review variable
            if($isreview) { 
                $icon = $thisReviewType->icon; 
				$icon_light = $thisReviewType->icon_light;
                $cat = $thisReviewType->name;
            } else {
                $cats = get_the_category();
                $cat = $cats[0]->cat_name;	
            }
            //show rating?
            $rating_hide = get_post_meta($post->ID, "Hide Rating", $single = true);
            //check if this is a video post
            $isvideo=false;
            $video = get_post_meta($post->ID, "Video", $single = true);
            if($video!="") $isvideo=true;	
            ?>
            
            <div class="post-panel<?php if($postcount % $cols == 0) { ?> right<?php } ?><?php if($oswc_archive_layout=="B") { ?> layout-b<?php } elseif($oswc_archive_layout=="C") { ?> layout-c<?php } ?><?php if(!$oswc_archive_meta_enabled) { ?> no-more<?php } ?>">
                
                <div class="category"> 
                
                    <?php if($isreview && $rating_hide!="true") { ?>
                            
                        <div class="icon" style="background:url(<?php echo $icon_light; ?>) no-repeat 0px 0px;">&nbsp;</div> 
                        
                    <?php } ?> 
                    
                    <div class="catname">
                               
                        <?php echo $cat; ?> 
                        
                    </div> 
                    
                    <div class="category-arrow">&nbsp;</div> 
                             
                </div>
                
                <div class="article-image">
                    <a class="thumbnail darken<?php if($isvideo) { ?> video<?php } ?>" href="<?php the_permalink(); ?>"><?php the_post_thumbnail($thumbnailsize, array( 'title'=> '' )); ?></a>
                </div>
                
                <div class="article-image responsive-large">
                    <a class="thumbnail darken<?php if($isvideo) { ?> video<?php } ?>" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('loop-large', array( 'title'=> '' )); ?></a>
                </div>
                
                <div class="article-image responsive">
                    <a class="thumbnail darken<?php if($isvideo) { ?> video<?php } ?>" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('spotlight', array( 'title'=> '' )); ?></a>
                </div>
            
                <?php if($oswc_archive_layout=="A" || $oswc_archive_layout=="B") { //layout A ?>
                    
                    <div class="inner">
                                               
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        
                        <?php if($oswc_archive_excerpt_enabled) { ?>
                        
                            <div class="excerpt">
                            
                                <?php if(!$oswc_archive_sidebar_show && $oswc_archive_layout=="B") { ?>
                            
                                    <?php oswc_long_excerpt(); ?>
                                    
                                <?php } else { ?>
                                
                                    <?php oswc_standard_excerpt(); ?>
                                
                                <?php } ?>
                                
                            </div>
                            
                        <?php } ?>
                  
                    </div>
                    
                    <?php if($oswc_archive_meta_enabled) { ?>
                    
                        
                        
                        <?php if($oswc_archive_layout=="B") { ?>
                        
                            <br class="clearer" />
                            
                        <?php } ?>
                        
                    <?php } ?> 
                
                <?php } else { //layout C ?>
                
                    <?php if($oswc_archive_meta_enabled) { ?>
                        
                        <div class="more-bar">
                                
                            <div class="arrow-catpanel-top">&nbsp;</div>
                            
                            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                            
                            <?php if($isreview && $rating_hide!="true") { ?>
                        
                                <div class="rating-wrapper small"><?php $oswcPostTypes->the_rating($thisReviewType); // show the rating ?></div>  
                                
                            <?php } ?>
                            
                            <div class="clear-responsive">&nbsp;</div>
                            
                            <div class="date">
                            
                                <?php echo get_the_date(); ?>
                                
                            </div> 
                            
                            
                            
                            <br class="clearer" />                        
                                                        
                            <div class="tags">
                            
                                <?php echo oswc_get_tags($post->ID, ', '); //list tags excluding template tags ?>    
                            
                            </div>
                            
                            <br class="clearer" />
                        
                        </div> 
                        
                        <br class="clearer" />
                        
                    <?php } ?> 
                    
                    <div class="inner">
                    
                        <?php if(!$oswc_archive_meta_enabled) { ?>
                    
                            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                            
                        <?php } ?>
                        
                        <?php if($oswc_archive_excerpt_enabled) { ?>
                        
                            <div class="excerpt">
                            
                                <?php if(!$oswc_archive_sidebar_show) { ?>
                                
                                    <?php oswc_long_excerpt(); ?>
                                    
                                <?php } else { ?>
                                
                                    <?php oswc_standard_excerpt(); ?>
                                
                                <?php } ?>
                                
                            </div>
                            
                        <?php } ?>
                        
                        <div class="more"><a href="<?php the_permalink(); ?>"><?php _e('Read more','itb'); ?></a></div>
                  
                    </div>
                
                <?php } ?>
            
            </div> 
            
            <?php if($postcount % $cols == 0) { ?> <br class="clearer" /><?php } ?>
            
        <?php endwhile; 
        endif; ?> 
        
        <br class="clearer" /> 
            
        <?php // pagination
        pagination($wp_query->max_num_pages);
        ?> 
        
    </div>
    
    <br class="clearer" />
    
	<?php if($oswc_archive_trending_show) { ?>
    
        <?php oswc_get_template_part('trending'); // show trending ?>
        
    <?php } ?> 
    
</div>

<?php if($oswc_archive_sidebar_show) { ?>

	<div class="sidebar">

		<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar($sidebar) ) : else : ?>
		
			<div class="widget-wrapper">
        
                <div class="widget">
        
                    <div class="section-wrapper"><div class="section">
                    
                        <?php _e(' USDI ', 'itb' ); ?>
                    
                    </div></div> 
                    
                    <div class="textwidget">  
                                                  
                        <p><?php _e( 'ini panel widget atau sidebar. login WordPress admin panel dan lalu masuk Appearance >> Widgets, drag &amp; drop konten panel.', 'itb' ); ?></p>
                        
                    </div>
                                
                </div>
            
            </div>
		
		<?php endif; ?>
	
	</div>
	
<?php } ?>		

<br class="clearer" />

<?php get_footer(); // show footer ?>