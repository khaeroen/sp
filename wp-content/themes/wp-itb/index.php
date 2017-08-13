<?php //get theme options
global $oswc_front, $oswc_ads, $oswcPostTypes;

//set theme options
$oswc_front_sidebar_unique = $oswc_front['sidebar_unique'];
$oswc_front_sidebar_show = $oswc_front['sidebar_show'];
$oswc_featured_ad_hide = $oswc_ads['featured_ad_hide'];
$oswc_featured_ad = $oswc_ads['featured_ad'];
$oswc_spotlight_ad_hide = $oswc_ads['spotlight_ad_hide'];
$oswc_spotlight_ad = $oswc_ads['spotlight_ad'];
$oswc_tabs_ad_hide = $oswc_ads['tabs_ad_hide'];
$oswc_tabs_ad = $oswc_ads['tabs_ad'];
$oswc_trending_ad_hide = $oswc_ads['trending_ad_hide'];
$oswc_trending_ad = $oswc_ads['trending_ad'];
$oswc_categorypanels_ad_hide = $oswc_ads['categorypanels_ad_hide'];
$oswc_categorypanels_ad = $oswc_ads['categorypanels_ad'];
$oswc_featured_show = $oswc_front['featured_show'];
$oswc_featured_size = $oswc_front['featured_size'];
$oswc_spotlight_show = $oswc_front['spotlight_show'];
$oswc_tabs_show = $oswc_front['tabs_show'];
$oswc_latestposts_show = $oswc_front['latestposts_show'];
$oswc_trending_show = $oswc_front['trending_show'];
$oswc_categorypanels_show = $oswc_front['categorypanels_show'];
?>

<?php //setup variables
$sidebar="Default Sidebar";
if($oswc_front_sidebar_unique) { $sidebar="Frontpage Sidebar"; } //which sidebar to display
?>

<?php get_header(); // show header ?>

<?php if($oswc_featured_show && $oswc_featured_size=="large") { ?>
    
	<?php oswc_get_template_part('featured'); // show featured area ?>
    
<?php } else { 
	echo '<img src="'.get_template_directory_uri().'/images/slideNetral.jpg" alt="Institut Teknologi Bandung" style="max-width:100%; margin:20px 0;"/>';	

} ?>
<?php /*kasih "else" buat image pengganti*/ ?>
<div class="main-content<?php if($oswc_front_sidebar_show) { ?>-left<?php } ?>">

	<?php if($oswc_featured_show && $oswc_featured_size=="small") { // show featured area using small format ?>
    
		<?php oswc_get_template_part('featured'); ?>
                
		<?php if(!$oswc_front_sidebar_show) { // don't show main sidebar, only sidebar next to featured area (only if featured area is set to small format) ?>
        
            <div id="featured-sidebar" class="sidebar">
            
                <?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar($sidebar) ) : else : ?>
            
                    <div class="widget-wrapper">
            
                        <div class="widget">
                
                            <div class="section-wrapper"><div class="section">
                            
                                <?php _e('Made Magazine', 'itb' ); ?>
                            
                            </div></div> 
                            
                            <div class="textwidget">  
                                                          
                                <p><?php _e( 'ini panel widget atau sidebar. login WordPress admin panel dan lalu masuk Appearance >> Widgets, drag &amp; drop konten panel.', 'itb' ); ?></p>
                                
                            </div>
                                        
                        </div>
                    
                    </div>
                
                <?php endif; ?>
            
            </div>
            
        <?php } ?>
        
    <?php } ?>
    
    <br class="clearer" />
    
       
    <?php if($oswc_spotlight_show) { ?>
    
    	<?php oswc_get_template_part('spotlight'); // show spotlight articles ?>
        
    <?php } ?>
    
    
    
    <?php if($oswc_tabs_show) { ?>
    
    	<?php oswc_get_template_part('tabs'); // show tabbed articles ?>
        
    <?php } ?>
    
       
    <?php if($oswc_categorypanels_show) { ?>
    
    	<?php oswc_get_template_part('category-panels'); // show category panels ?>
        
    <?php } ?>
    
       
        
    <?php if($oswc_latestposts_show) { ?>
    
    	<?php oswc_get_template_part('front-latest-posts'); // show latest posts ?>
        
    <?php } ?>
    
    <?php oswc_get_template_part('frontpage-widgets'); // show frontpage widgets ?>

</div>

<?php if($oswc_front_sidebar_show) { ?>

	<div class="sidebar<?php if($oswc_featured_size=="large") { ?> front-page-large-featured<?php } ?>">

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

<!--
<div class="hide-pagination">
	<?php // there is an error when running ThemeCheck that says this theme does not have pagination when
    // in fact it does (see feed.php >> which calls the pagination function in functions/custom.php
    // so this code is added to bypass that error, but it is hidden so it doesn't show up on the page
    paginate_links();
	$args="";
	wp_link_pages( $args );
    ?>
</div>
-->

<?php get_footer(); // show footer ?>