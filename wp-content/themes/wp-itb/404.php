<?php
//set theme options
$oswc_404_sidebar_unique = $oswc_other['404_sidebar_unique'];
$oswc_trending_show = $oswc_other['404_trending_enabled'];
?>

<?php get_header(); // show header 

// user specified a unique 404 sidebar
if ($oswc_404_sidebar_unique) {
	$sidebar="Archive Sidebar";
} else {
	$sidebar="Default Sidebar";
}
?>

<div class="main-content-left">

    <div class="page-content">     
        
        <div class="section-wrapper">
        
            <div class="section">
            
                <?php _e( '404 - Page Not Found', 'itb' ); ?>
            
            </div>        
        
        </div>
    
        
        
        <div class="content-panel">
        
        	<h1 class="error"><?php _e( 'That just happened.', 'itb' ); ?></h1>
            
            <p>
			<?php _e( 'We could not find the page that you requested. It is possible that it was deleted since the last time you viewed it, or that you typed in the wrong URL. In any case, we want you to find what you are looking for, so we offer you the following options:', 'itb' ); ?>
            </p>
            
            <p>
            
                <div class="home"><?php _e('1. Go back to the','itb'); ?> <a href="<?php echo home_url(); ?>/"><?php _e('home page','itb'); ?></a> <?php _e('and start your journey over.','itb'); ?></div>
                
                <div class="menu"><?php _e('2. Use the menu above to locate the section you wish to view.','itb'); ?></div>
                
                <div class="search"><?php _e('3. Search our site:','itb'); ?></div>
            
            </p>
            
            <div class="searchform">
            
                <!-- SEARCH -->  
                <form method="get" id="search404" action="<?php echo home_url(); ?>/">                             
                    <input type="text" value="<?php _e( 'search', 'itb' ); ?>" onfocus="if (this.value == '<?php _e( 'search', 'itb' ); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e( 'search', 'itb' ); ?>';}" name="s" id="s" />          
                </form>
                
            </div> 
            
            <div class="note">
            
            	<?php _e( 'Enter keyword(s) and press enter', 'itb' ); ?>
                
            </div>
            
        </div>      
        
    </div>
    
    <?php if($oswc_trending_show) { ?>
    
    	<?php oswc_get_template_part('trending'); // show trending ?>
        
    <?php } ?>

</div>

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

<br class="clearer" />

<?php get_footer(); // show footer ?>