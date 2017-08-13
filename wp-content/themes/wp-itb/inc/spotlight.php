<?php //get theme options
global $oswc_front, $oswcPostTypes;

//set theme options
$oswc_front_sidebar_show = $oswc_front['sidebar_show'];
$oswc_spotlight_tags = $oswc_front['spotlight_tags'];
$oswc_spotlight_header = $oswc_front['spotlight_header'];
$oswc_spotlight_num = $oswc_front['spotlight_num'];
$oswc_spotlight_type = $oswc_front['spotlight_type'];
$oswc_spotlight_scroll = $oswc_front['spotlight_scroll'];
?>

<?php //setup wp_query args
$hidemore = false;
$spotlighterror = "";
if($oswc_spotlight_type=="tag") {
	$spotlightargs = array('tag_id' => get_tag_id($oswc_spotlight_tags), 'posts_per_page' => $oswc_spotlight_num);
	$terms = get_terms('post_tag', array('slug' => $oswc_spotlight_tags)); //get term object for this tag
	if(empty($terms)) $slidererror = __("Ini adalah slider untuk spotlight, tentukan tag pada menu theme option","itb");
	$termslug = $terms[0]->slug; //get the slug
	$morelink = get_term_link($termslug,'post_tag'); //get the permalink for this tag	
	$oswc_spotlight_tags = get_term_by('slug', $oswc_spotlight_tags, 'post_tag')->name;
	$moretitle = "All articles in " . $oswc_spotlight_tags . " &raquo;"; //link title
} elseif($oswc_spotlight_type=="category") {
	$spotlightargs = array('cat' => get_category_id($oswc_spotlight_tags), 'posts_per_page' => $oswc_spotlight_num);
	$terms = get_terms('category', array('slug' => $oswc_spotlight_tags)); //get term object for this category
	if(empty($terms)) $slidererror = __("The category name you specified in the theme options for the Spotlight Slider does not match a category name in your database.","itb");
	$termslug = $terms[0]->slug; //get the slug
	$morelink = get_term_link($termslug,'category'); //get the permalink for this category
	$oswc_spotlight_tags = get_category_by_slug($oswc_spotlight_tags)->name;
	$moretitle = "All articles in " . $oswc_spotlight_tags . " &raquo;"; //link title
} elseif ($oswc_spotlight_type=="review") {
	$thisReviewType = $oswcPostTypes->get_type_by_name($oswc_spotlight_tags); //see if review type entered matches one in the system
	$reviewSlug = $thisReviewType->id; //get the slug for use in the query
	$spotlightargs = array('post_type' => $reviewSlug, 'posts_per_page' => $oswc_spotlight_num);
	$morelink = oswc_review_permalink($oswc_spotlight_tags); //get permalink for this review page 	
	$moretitle = "All articles in " . $oswc_spotlight_tags . " &raquo;"; //link title
} else {
	$spotlightargs = array('posts_per_page' => $oswc_spotlight_num);
	$hidemore = true; //there is no latest articles page to link to
}

//determine layout margins
if($oswc_front_sidebar_show) {
	$cols=2;
} else {
	$cols=3;
}
?>

<div id="spotlight-wrapper">

	<!--<div class="ribbon-shadow-left">&nbsp;</div>-->

	<div class="section-wrapper"> <!-- spotlight section header -->
    
    	<div class="section">
        
        	<?php echo $oswc_spotlight_header; ?>
        
        </div>        
    
    </div>
    
       
    
    <!--<div class="section-arrow">&nbsp;</div>-->
    
    <div class="spotlight">
    
        <div <?php if($oswc_spotlight_scroll) { ?>id="spotlight-slider"<?php } ?>> <!-- begin spotlight slider -->
        
            <ul>
                
                <?php if($slidererror!="") { ?>
      
                    <div style="font-style:italic;width:600px;margin:20px 0px;"><?php echo $slidererror; ?></div>
    
                <?php } else {
                    $postcount = 0;				
                    query_posts ( $spotlightargs );
                    if (have_posts()) : while (have_posts()) : the_post(); $postcount++;				
                        $postType = get_post_type(); //get post type
                        $reviewType = $oswcPostTypes->get_type_by_id($postType); //get review type object		
                        $isreview=false;
                        if($postType!='post') $isreview=true; //set review variable
						//get review info
						if($isreview) {
							$reviewHideReviewVerbiage=$reviewType->hide_review_verbiage;	
							$cat = $reviewType->name;
							if(!$reviewHideReviewVerbiage) $cat = $cat . __(' Review','itb');
							//show rating?
							$rating_hide = get_post_meta($post->ID, "Hide Rating", $single = true); 
							//get the icon
							$icon = $reviewType->icon;		
							$icon_light = $reviewType->icon_light;
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
                        
                        <li>
                
                            <div class="post-panel<?php if($postcount % $cols == 0) { ?> right<?php } ?>"> 
                            
                                <a class="darken<?php if($isvideo) { ?> video<?php } ?>" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('spotlight', array( 'title'=> '' )); ?></a>
                                
                                <div class="inner">
                                                           
                                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                    
                                    <div class="excerpt"><?php oswc_standard_excerpt(); ?></div>
                                    
                                </div>
                                
                                
                            
                            </div>
                            
                            <?php if ($postcount % $cols == 0) { // new line every x panels ?>
                                                    
                                <div class="clearer"></div>
                        
                            <?php } ?>
                        
                        </li>
                        
                    <?php endwhile; endif; 
                    
                } ?>
        
            </ul>
        
        </div> <!--end spotlight slider-->
        
        <!-- responsive version -->
    
        <div <?php if($oswc_spotlight_scroll) { ?>id="spotlight-slider-responsive"<?php } ?>> <!-- begin spotlight slider -->
        
            <ul>
                
                <?php if($slidererror!="") { ?>
      
                    <div style="font-style:italic;width:600px;margin:20px 0px;"><?php echo $slidererror; ?></div>
    
                <?php } else {
                    $postcount = 0;				
                    query_posts ( $spotlightargs );
                    if (have_posts()) : while (have_posts()) : the_post(); $postcount++;				
                        $postType = get_post_type(); //get post type
                        $reviewType = $oswcPostTypes->get_type_by_id($postType); //get review type object		
                        $isreview=false;
                        if($postType!='post') $isreview=true; //set review variable
						//get review info
						if($isreview) {
							$reviewHideReviewVerbiage=$reviewType->hide_review_verbiage;	
							$cat = $reviewType->name;
							if(!$reviewHideReviewVerbiage) $cat = $cat . __(' Review','itb');
							//show rating?
							$rating_hide = get_post_meta($post->ID, "Hide Rating", $single = true); 
							//get the icon
							$icon = $reviewType->icon;		
							$icon_light = $reviewType->icon_light;
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
                        
                        <li>
                
                            <div class="post-panel<?php if($postcount % $cols == 0) { ?> right<?php } ?>"> 
                            
                                <div class="category"> 
                                
                                    <?php if($isreview && $rating_hide!="true") { ?>
                                            
                                        <div class="icon" style="background:url(<?php echo $icon_light; ?>) no-repeat 0px 0px;">&nbsp;</div> 
                                        
                                    <?php } ?> 
                                    
                                    <div class="catname">
                                               
                                        <?php echo $cat; ?> 
                                        
                                    </div> 
                                    
                                    <div class="category-arrow">&nbsp;</div> 
                                             
                                </div>
                    
                                <a class="darken small<?php if($isvideo) { ?> video<?php } ?>" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('spotlight-small', array( 'title'=> '' )); ?></a>
                                
                                <div class="inner">
                                                           
                                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                    
                                    <div class="excerpt"><?php oswc_standard_excerpt(); ?></div>
                                    
                                </div>
                                
                                <div class="more-bar">
                                
                                	<div class="arrow-catpanel-top">&nbsp;</div>
                                    
                                    <?php if($isreview && $rating_hide!="true") { ?>
                                
                                        <div class="rating-wrapper small"><?php $oswcPostTypes->the_rating($reviewType); // show the rating ?></div>  
                                        
                                    <?php } ?> 
                                    
                                    
                                    
                                    <div class="more"><a href="<?php the_permalink(); ?>"><?php _e('',AS'itb'); ?></a></div>
                                
                                </div>
                            
                            </div>
                            
                            <?php if ($postcount % $cols == 0) { // new line every x panels ?>
                                                    
                                <div class="clearer"></div>
                        
                            <?php } ?>
                        
                        </li>
                        
                    <?php endwhile; endif; 
                    
                } ?>
        
            </ul>
        
        </div> <!--end spotlight slider-->
    
    	<!-- end responsive version -->
        
    </div>

</div>

<div class="clearer"></div><br class="clearer" />

<?php wp_reset_query(); ?>