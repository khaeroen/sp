<?php //get theme options
global $oswc_ads, $oswc_single, $oswcPostTypes;

//set theme options
$oswc_overview_ad_hide = $oswc_ads['review_overview_ad_hide'];
$oswc_overview_ad = $oswc_ads['review_overview_ad'];
$oswc_text_ad_hide = $oswc_ads['review_text_ad_hide'];
$oswc_text_ad = $oswc_ads['review_text_ad'];
$oswc_comments_ad_hide = $oswc_ads['review_comments_ad_hide'];
$oswc_comments_ad = $oswc_ads['review_comments_ad'];
$oswc_review_sidebar_unique = $oswc_single['review_sidebar_unique']; 
$oswc_featured_image_video_show = $oswc_single['review_featured_image_video_show'];
$oswc_trending_hide = $oswc_single['review_trending_hide']; 
$oswc_tags_hide = $oswc_single['review_tags_hide']; 

//setup the review variables
$postTypeId = get_post_type( $wp_query->post->ID );
$postType = $oswcPostTypes->get_type_by_id($postTypeId);
$reviewTrendingEnabled=$postType->trending_enabled;
$reviewSidebarEnabled=$postType->sidebar_enabled;
$reviewSummaryHeaderText=$postType->summary_header_text;
if($reviewSummaryHeaderText=='') { $reviewSummaryHeaderText="Overview"; }
$reviewFullArticleText=$postType->full_article_text;
if($reviewFullArticleText=='') { $reviewFullArticleText="Full Article"; }
$primaryTaxonomy = $postType->get_primary_taxonomy();	
$positiveText = $postType->positive;
$negativeText = $postType->negative;
$bottomLine = $postType->bottom_line;
$taxAboveMeta = $postType->tax_above_meta;
$positiveNegativeIcons = $postType->positive_negative_icons;
if(!isset($positiveNegativeIcons)) { $positiveNegativeIcons="hand"; }
$terms = wp_get_object_terms($post->ID,$primaryTaxonomy->id);				
$cat_name = $terms[0]->name;				
$cat_slug = $terms[0]->slug;
$cat_link = get_term_link($cat_slug, $primaryTaxonomy->id);	

//set defaults
//if(empty($reviewSidebarEnabled)) $reviewSidebarEnabled=true;	
?>

<?php // use variables from page custom fields instead of itb options page (if they exist)
$override = get_post_meta($post->ID, "Featured Image Size", $single = true);
if($override!="" && $override!="null") {
	$oswc_review_featured_image_size=$override;	
}
$override = get_post_meta($post->ID, "Hide Sidebar", $single = true);
if($override!="" && $override!="null") {
	$oswc_sidebar_hide=$override;
	if($oswc_sidebar_hide=="false") {	
		$reviewSidebarEnabled=true;	
	} else {
		$reviewSidebarEnabled=false;
	}
}
$override = get_post_meta($post->ID, "Hide Trending", $single = true);
if($override!="" && $override!="null") {
	$oswc_trending_hide=$override;
	if($oswc_trending_hide=="false") {
		$oswc_trending_hide=false;	
	} else {
		$oswc_trending_hide=true;
	}
}
$override = get_post_meta($post->ID, "Hide Overview Ad", $single = true);
if($override!="" && $override!="null") {
	$oswc_overview_ad_hide=$override;
	if($oswc_overview_ad_hide=="false") {
		$oswc_overview_ad_hide=false;	
	} else {
		$oswc_overview_ad_hide=true;
	}
}
$override = get_post_meta($post->ID, "Hide Review Ad", $single = true);
if($override!="" && $override!="null") {
	$oswc_review_ad_hide=$override;
	if($oswc_review_ad_hide=="false") {
		$oswc_review_ad_hide=false;	
	} else {
		$oswc_review_ad_hide=true;
	}
}
$override = get_post_meta($post->ID, "Hide Comments Ad", $single = true);
if($override!="" && $override!="null") {
	$oswc_comments_ad_hide=$override;
	if($oswc_comments_ad_hide=="false") {
		$oswc_comments_ad_hide=false;	
	} else {
		$oswc_comments_ad_hide=true;
	}
}
$override = get_post_meta($post->ID, "Hide Bottom Line", $single = true);
if($override!="" && $override!="null") {
	$oswc_bottomline_hide=$override;
	if($oswc_bottomline_hide=="false") {	
		$oswc_bottomline_hide=false;	
	} else {
		$oswc_bottomline_hide=true;
	}
}
?>

<?php // user specified a unique review sidebar
if ($oswc_review_sidebar_unique) {
	$sidebar=$postType->name . " Sidebar";
} else {
	$sidebar="Default Sidebar";
}
//get featured image size
switch ($oswc_review_featured_image_size) {
	case "small":
		$featured_image_size="single-review";
		$featured_image_size_responsive="single-review";
		$featured_image_size_responsive_small="single-medium";
		break;
	case "medium":
		$featured_image_size="single-review";
		$featured_image_size_responsive="single-review";
		$featured_image_size_responsive_small="single-medium";
		break;
	case "full":
		$featured_image_size="single";
		$featured_image_size_responsive="single-review";
		$featured_image_size_responsive_small="single-medium";
		break;
	default:
		$featured_image_size="single";
		$featured_image_size_responsive="single-review";
		$featured_image_size_responsive_small="single-medium";
		break;
}
?>

<?php get_header(); // show header ?>

<div class="hide-responsive"><?php oswc_get_template_part('sharebox'); // show the sharebox ?></div>  
       
<div class="main-content<?php if($reviewSidebarEnabled) { ?>-left<?php } else { ?> full-width<?php } ?>">
    
    <div class="page-content review">
    
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            
            <?php
            // Featured Image for FB Like
            $featured_image = get_the_post_thumbnail($post->ID);
            
            // Get image source
            if($featured_image) {
                $doc = new DOMDocument();
                $doc->loadHTML($featured_image);
                $imageTags = $doc->getElementsByTagName('img');
                
                foreach($imageTags as $tag) {
                    $image_url = $tag->getAttribute('src');
                }
            }				
            ?>
            <link rel="image_src" href="<?php echo $image_url; ?>" />
            
            <?php // get review info
            $rating = get_post_meta($post->ID, "Rating", $single = true); 
            //show rating?
            $rating_hide = get_post_meta($post->ID, "Hide Rating", $single = true); 
            if($rating_hide=="false") $rating_hide=false;
            //show overview?
            $overview_hide = get_post_meta($post->ID, "Hide Overview", $single = true); 
            if($overview_hide=="false") $overview_hide=false;
            //show full article bar?
            $full_article_hide = get_post_meta($post->ID, "Hide Full Article Bar", $single = true); 
            if($full_article_hide=="false") $full_article_hide=false;
            //check if this is a video post
            $isvideo=false;
            $video = get_post_meta($post->ID, "Video", $single = true);
            if($video!="") $isvideo=true;
            ?>
            
            <div class="overview-wrapper">
            
            	<h1 class="title"><?php the_title(); ?></h1>
                
                <div class="overview">
                
                	<div class="arrow-catpanel-bottom">&nbsp;</div> 
                    
                    <?php //for full-width featured image, put the video and image above the rest of the overview ?>
                    
                    <?php if($oswc_review_featured_image_size=='full') { ?>
                    
                    	<div class="full-width-image">
                    
							<?php //FEATURED VIDEO ?>
                        
                            <?php if($isvideo) { ?>
                        
                                <div class="article-image video">
                                                                    
                                    <?php echo $video; ?>
                                    
                                </div>
                                
                            <?php } ?>
                            
                            <?php //FEATURED IMAGE ?>
                            
                            <?php if((($isvideo && $oswc_featured_image_video_show) || !$isvideo) && $oswc_review_featured_image_size!='none') { ?>
        
                                <div class="article-image">
                                    <?php if ( has_post_thumbnail()) {
                                       $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large'); ?>
                                       <a class="darken" href="<?php echo $large_image_url[0]; ?>">
                                        <?php the_post_thumbnail($featured_image_size); ?>
                                       </a>
                                    <?php } ?>
                                </div>
                                
                                <!-- responsive iPads -->
                                <div class="article-image responsive">
        
                                    <?php if ( has_post_thumbnail()) {the_post_thumbnail($featured_image_size_responsive);} ?>
                                    
                                </div>
                                
                                <!-- responsive iPhones -->
                                <div class="article-image responsive-small">
        
                                    <?php if ( has_post_thumbnail()) {the_post_thumbnail($featured_image_size_responsive_small);} ?>
                                    
                                </div>
                                
                            <?php } ?>
                            
                            <br class="clearer" />
                            
                        </div>
                    
                    <?php } ?>
                    
                    <div class="left-panel">
                    
                    	<?php //for non-full-width featured image, put the video and image in the left panel ?>
                    
                    	<?php if($oswc_review_featured_image_size!='full') { ?>
                    
							<?php //FEATURED VIDEO ?>
                        
                            <?php if($isvideo) { ?>
                        
                                <div class="article-image video">
                                                                    
                                    <?php echo $video; ?>
                                    
                                </div>
                                
                            <?php } ?>
                            
                            <?php //FEATURED IMAGE ?>
                            
                            <?php if((($isvideo && $oswc_featured_image_video_show) || !$isvideo) && $oswc_review_featured_image_size!='none') { ?>
        
                                <div class="article-image">
                                    <?php if ( has_post_thumbnail()) {
                                       $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large'); ?>
                                       <a class="darken" href="<?php echo $large_image_url[0]; ?>">
                                        <?php the_post_thumbnail($featured_image_size); ?>
                                       </a>
                                    <?php } ?>
                                </div>
                                
                                <!-- responsive -->
                                <div class="article-image responsive">
        
                                    <?php if ( has_post_thumbnail()) {the_post_thumbnail($featured_image_size_responsive);} ?>
                                    
                                </div>
                                
                                <!-- responsive iPhones -->
                                <div class="article-image responsive-small">
        
                                    <?php if ( has_post_thumbnail()) {the_post_thumbnail($featured_image_size_responsive_small);} ?>
                                    
                                </div>
                                
                            <?php } ?>
                            
                            <br class="clearer" />
                            
                        <?php } ?>
                        
                        <?php //META FIELDS AND TAXONOMIES (hide for non-reviews) ?>
                        
                        <?php if(!$overview_hide) { ?>
                        
                        	
                            <br class="clearer" />
                    
                            <?php
                            if($taxAboveMeta){
                                $oswcPostTypes->the_taxonomies($postType);         
                            }
    
                            $oswcPostTypes->the_meta_fields($postType);
    
                            if(!$taxAboveMeta){
                                $oswcPostTypes->the_taxonomies($postType);         
                            }
                            ?>
                            
                        <?php } ?>
                        
                    </div>
                    
                    <div class="right-panel">
                    
                    	<?php //RATING CRITERIA (hide for non-reviews) ?>
                    
						<?php if($rating_hide!="true") { ?>
                                    
                            <div class="ratings-wrapper">
                            
                                <?php $oswcPostTypes->the_rating_criteria($postType); ?>
                                
                            </div>
                            
                              
                        
                        <?php } ?>
                        
                        <?php //POSITIVES AND NEGATIVES (hide for non-reviews) ?>
                        
                        <?php if(!$overview_hide) { ?>
                        
                        	<div class="summary">
                                                    
								<?php $positives = get_post_meta($post->ID, "Positives", $single = true); ?>
                            
                                <?php if($positives) { ?>
                                
                                	<div class="positive-wrapper">
                                
                                        <div class="positive <?php echo $positiveNegativeIcons; ?>">
                                        
                                            <h3><?php echo $positiveText; ?></h3>
                                            
                                            <br class="clearer" />                                            
                                            
                                        </div>
                                        
                                        <?php echo $positives; ?>
                                        
                                    </div>
                                
                                <?php } ?>
                                
                                <?php $negatives = get_post_meta($post->ID, "Negatives", $single = true); ?>
                                
                                <?php if($negatives) { ?>
                                
                                	<div class="negative-wrapper">
                                
                                        <div class="negative <?php echo $positiveNegativeIcons; ?>">
                                        
                                            <h3><?php echo $negativeText; ?></h3>
                                            
                                            <br class="clearer" />                                            
                                            
                                        </div>
                                        
                                        <?php echo $negatives; ?>
                                        
                                    </div>
                                    
                                <?php } ?>
                                
                            </div>
                            
                        <?php } ?>
                        
                    </div>
                    
                    <br class="clearer" /><br />
                    
                    <?php //THE BOTTOM LINE which comes from the excerpt field (hide for non-reviews) ?>
                    
                    <?php if(!$overview_hide && !$oswc_bottomline_hide) { ?>
                    
                        <div class="excerpt">
                        
                            <div class="bottom-line"><?php echo $bottomLine; ?></div>
                                    
							<?php the_excerpt(); ?>
                        
                        </div>
                        
                    <?php } ?>
                    
                    <div class="bottom">
                    
                        <div class="comment-bubble">
                    
                            <?php if(comments_open()) { ?><?php comments_popup_link(__('0', 'itb'), __('1', 'itb'), __('%', 'itb')); ?><?php } ?>
                            
                        </div>
                    
                        <div class="section">
                        
                            <?php _e('Posted','itb'); ?> 
                            <?php echo get_the_date(); ?>
                            <?php _e('by','itb'); ?>
                            <?php the_author_link(); ?>
                        
                        </div> 
                        
                    </div>
                
                </div>
            
            </div>
            
            <?php if(!$reviewSidebarEnabled) { ?>

                <div class="sidebar hide-responsive">
                
                    <?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar($sidebar) ) : else : ?>
                    
                        <div class="widget-wrapper">
                    
                            <div class="widget">
                    
                                <div class="section-wrapper"><div class="section">
                                
                                    <?php _e(' Made Magazine ', 'itb' ); ?>
                                
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
            
            <?php if(!$oswc_overview_ad_hide) { //the ad below the featured slider ?>
            
                <div class="<?php if(!$reviewSidebarEnabled) { ?>full-width-ad<?php } else { ?>left-ad<?php } ?> no-margin">  
                
                    <?php echo $oswc_overview_ad; ?>
                    
                </div>
            
            <?php } ?>
            
            <div class="review-content">
            
				<?php if($full_article_hide!="true") { ?>
                
                    
    
                    <div class="section-wrapper"> <!-- full article header -->
                    
                        <div class="section">
                        
                            <?php echo $reviewFullArticleText; ?>
                        
                        </div>        
                    
                    </div>
                    
                                    
                
                <?php } ?>
                
                <div id="post-<?php the_ID(); ?>" <?php post_class('content-panel'); ?>>
        
                    <div class="the-content">
					
						<?php the_content(); ?>
                        
                    </div>
                    
					<?php $pagination_args = array(
                    'before'           => '<div class="pagination-wrapper"><div class="pagination">',
                    'after'			   => '</div></div>',
                    'link_before'	   => '<span class="current">',
                    'link_after'       => '</span>',
                    'next_or_number'   => 'number',
                    'nextpagelink'     => '&raquo;',
                    'previouspagelink' => '$laquo;',
                    'pagelink'         => '%',
                    'echo'             => 1 ); ?>
                            
                    <?php wp_link_pages($pagination_args); ?> 
                    
                    <div class="clearer"></div><br />
                    
                    <?php if(!$oswc_tags_hide) { ?>
                    
                        <div class="tags">
                        
                            <?php echo oswc_get_tags($post->ID, ' '); //list tags excluding template tags ?>
                        
                        </div> 
                        
                        <div class="clearer"></div>  
                    
                    <?php } ?>
                    
                    <?php if(!$oswc_text_ad_hide) { //the ad below the featured slider ?>
                    
                        <div class="<?php if(!$reviewSidebarEnabled) { ?>full-width-ad<?php } else { ?>left-ad<?php } ?> no-margin">  
                        
                            <?php echo $oswc_text_ad; ?>
                            
                        </div>
                    
                    <?php } ?>  
                    
                </div>  
            
                <?php oswc_get_template_part('authorbox'); //show authorbox ?> 
                
                 
                
                <div class="clearer"></div> 
                
                    <?php if(!$oswc_comments_ad_hide) { //the ad below the featured slider ?>
                
                    <div class="<?php if(!$reviewSidebarEnabled) { ?>full-width-ad<?php } else { ?>left-ad<?php } ?> no-margin">  
                    
                        <?php echo $oswc_comments_ad; ?>
                        
                    </div>
                
                <?php } ?>
                
            </div>
        
        <?php endwhile;
        endif; ?>  
    
    </div>
    
    <?php if(comments_open()) { ?>

		<?php comments_template(); // show comments ?>
        
    <?php } ?>    
    
    <?php if(!$oswc_trending_hide) { ?>
    
        <?php oswc_get_template_part('trending'); // show trending ?>
        
    <?php } ?>

</div>

<?php if($reviewSidebarEnabled) { ?>

    <div class="sidebar">
    
        <?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar($sidebar) ) : else : ?>
        
            <div class="widget-wrapper">
        
                <div class="widget">
        
                    <div class="section-wrapper"><div class="section">
                    
                        <?php _e(' Made Magazine ', 'itb' ); ?>
                    
                    </div></div> 
                    
                    <div class="textwidget">  
                                                  
                        <p><?php _e( 'ini panel widget atau sidebar. login WordPress admin panel dan lalu masuk Appearance >> Widgets, drag &amp; drop konten panel.', 'itb' ); ?></p>
                        
                    </div>
                                
                </div>
            
            </div>
        
        <?php endif; ?>
    
    </div>
    
<?php } ?>

<div class="clearer"></div>

<?php get_footer(); // show footer ?>