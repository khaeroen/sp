<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php //get theme options
global $oswc_front, $oswc_other, $oswc_ads, $oswc_misc, $oswcPostTypes;

//set theme options
$oswc_demo = $oswc_misc['demo'];
$oswc_background = $oswc_misc['background'];
$oswc_background_fixed = $oswc_misc['background_fixed'];
$oswc_logo = $oswc_misc['logo'];
$oswc_logo_iphone = $oswc_misc['logo_iphone'];
$oswc_logo_ipad = $oswc_misc['logo_ipad'];
$oswc_color_scheme = $oswc_misc['color_scheme'];
$oswc_logo_bar_image = $oswc_misc['logo_bar_image'];
$oswc_hide_logo_bar_bg = $oswc_misc['hide_logo_bar_bg'];
$oswc_top_widget_show = $oswc_misc['top_widget_show'];
$oswc_skin = $oswc_misc['skin'];
$oswc_dontmiss_hide = $oswc_misc['dontmiss_hide'];
$oswc_latest_hide = $oswc_misc['latest_hide'];
$oswc_search_show = $oswc_misc['search_show'];
$oswc_random_show = $oswc_misc['random_show'];
$oswc_latest_hide = $oswc_misc['latest_hide'];
$oswc_sub_menu_hide = $oswc_misc['sub_menu_hide'];
$oswc_header_ad_hide = $oswc_ads['header_ad_hide'];
$oswc_header_ad = $oswc_ads['header_ad'];
$oswc_menu_ad_hide = $oswc_ads['menu_ad_hide'];
$oswc_menu_ad = $oswc_ads['menu_ad'];
$oswc_latest_ad_hide = $oswc_ads['latest_ad_hide'];
$oswc_latest_ad = $oswc_ads['latest_ad'];
$oswc_shortcodeslider_duration = $oswc_front['shortcodeslider_duration'];
$oswc_archive_dontmiss_hide = $oswc_other['archive_dontmiss_hide'];
$oswc_search_dontmiss_hide = $oswc_other['search_dontmiss_hide'];
$oswc_author_dontmiss_hide = $oswc_other['author_dontmiss_hide'];
$oswc_404_dontmiss_hide = $oswc_other['404_dontmiss_hide'];
$oswc_archive_latest_hide = $oswc_other['archive_latest_hide'];
$oswc_search_latest_hide = $oswc_other['search_latest_hide'];
$oswc_author_latest_hide = $oswc_other['author_latest_hide'];
$oswc_404_latest_hide = $oswc_other['404_latest_hide'];
$oswc_rss_feed = $oswc_misc['rss_feed'];
$oswc_facebook_url = $oswc_misc['facebook_url'];
$oswc_twitter_url = 'http://twitter.com/'.$oswc_misc['twitter_name'];

//set the default color scheme
if($oswc_color_scheme == '') $oswc_color_scheme="#C32C0D";
//set the skin variable for use in the body_class function - this only matters if it's dark since light is the default
if($oswc_skin=="dark") $skin="dark-skin";

//check what kind of page is displayed to see if the latest slider should be shown
if(is_archive()) {
	$oswc_latest_hide = $oswc_archive_latest_hide;	
	$oswc_dontmiss_hide = $oswc_archive_dontmiss_hide;	
} elseif(is_search()) {
	$oswc_latest_hide = $oswc_search_latest_hide;
	$oswc_dontmiss_hide = $oswc_search_dontmiss_hide;
} elseif(is_page_template('template-authors.php')) {
	$oswc_latest_hide = $oswc_author_latest_hide;
	$oswc_dontmiss_hide = $oswc_author_dontmiss_hide;
} elseif(is_404()) {
	$oswc_latest_hide = $oswc_404_latest_hide;
	$oswc_dontmiss_hide = $oswc_404_dontmiss_hide;
}

//see if we're on a review page. if so, create the reviewtype object and set a boolean review variable to true
$reviewPage = false;
$taxonomyPage = false;
$postTypeName = oswc_get_review_meta($post->ID);
$postTypeId = get_post_type(); //setup the posttypeid object, which is used below to determine which post type we're on
//review listing page
if(!empty($postTypeName) && ($oswcPostTypes->has_type($postTypeName) || $oswcPostTypes->has_type(strtolower($postTypeName)))){
	$reviewPage = true;	
	$reviewType = $oswcPostTypes->get_type_by_name($postTypeName); //get the review type object	
	$reviewDontmissEnabled=$reviewType->dontmiss_enabled;	
}
//review taxonomy page
if(is_tax()) {
	$reviewPage = true;
	$taxonomyPage = true; //this is a taxonomy page	
	$reviewType = $oswcPostTypes->get_type_by_id($postTypeId); //get the review type object
	$reviewDontmissEnabled=$reviewType->tax_dontmiss_enabled;	
} 
//single review page
if (is_single() && $oswcPostTypes->has_type($postTypeId, true)) {
	$reviewPage = true;
	$singlePage = true; //this is a single review page
	$reviewType = $oswcPostTypes->get_type_by_id($postTypeId);	
	$reviewDontmissEnabled=$reviewType->single_dontmiss_enabled;	
}
if(empty($reviewType)) { //this is a taxonomy page for a taxonomy that doesn't have any posts
	$reviewPage = false;
	$taxonomyPage = false;
	$reviewDontmissEnabled=$reviewType->tax_dontmiss_enabled;	
}
//invert boolean dont-miss variable - i know this is nasty, don't hate...
if($reviewPage) {
	if($reviewDontmissEnabled) {
		$oswc_dontmiss_hide = false;
	} else {
		$oswc_dontmiss_hide = true;
	}
}

// use variables from page custom fields instead of itb options page (if they exist)
if(!is_front_page()) {
	$override = get_post_meta($post->ID, "Hide Don't Miss", $single = true);
	if($override!="" && $override!="null") {
		$oswc_dontmiss_hide=$override;
		if($oswc_dontmiss_hide=="false") {
			$oswc_dontmiss_hide=false;	
		} else {
			$oswc_dontmiss_hide=true;
		}
	}
	$override = get_post_meta($post->ID, "Hide Latest", $single = true);
	if($override!="" && $override!="null") {
		$oswc_latest_hide=$override;
		if($oswc_latest_hide=="false") {
			$oswc_latest_hide=false;	
		} else {
			$oswc_latest_hide=true;
		}
	}
	$override = get_post_meta($post->ID, "Hide Header Ad", $single = true);
	if($override!="" && $override!="null") {
		$oswc_header_ad_hide=$override;
		if($oswc_header_ad_hide=="false") {
			$oswc_header_ad_hide=false;	
		} else {
			$oswc_header_ad_hide=true;
		}
	}
	$override = get_post_meta($post->ID, "Hide Latest Ad", $single = true);
	if($override!="" && $override!="null") {
		$oswc_latest_ad_hide=$override;
		if($oswc_latest_ad_hide=="false") {
			$oswc_latest_ad_hide=false;	
		} else {
			$oswc_latest_ad_hide=true;
		}
	}
}
?>

<?php if ( ! isset( $content_width ) ) $content_width = 960; ?>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
	
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    
    <meta name="viewport" content="width=device-width" />
	
	<?php if (is_search()) { ?>
	   <meta name="robots" content="noindex, nofollow" /> 
	<?php } ?>

	<title>
		<?php //Print the <title> tag based on what is being viewed         
        global $page, $paged;         
        wp_title( '|', true, 'right' );         
        // Add the blog name.         
        bloginfo( 'name' );         
        // Add the blog description for the home/front page.         
        $site_description = get_bloginfo( 'description', 'display' );         
        if ( $site_description && ( is_home() || is_front_page() ) ) echo " | $site_description";         
        // Add a page number if necessary:         
        if ( $paged >= 2 || $page >= 2 ) echo ' | ' . sprintf( __( 'Page %s', 'itb' ), max( $paged, $page ) );         
        ?>
	</title>
	
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" /> <!-- the main structure and main page elements style --> 
    
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/js/js.css" type="text/css" media="screen" /> <!-- styles for the various jquery plugins -->
    <!--[if IE 7]>
            <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/ie7.css" />
    <![endif]-->
    
    <!--[if IE 8]>
            <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/ie8.css" />
    <![endif]-->
    
    <!--[if gt IE 8]>
            <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/ie9.css" />
    <![endif]-->
    
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/custom.css" type="text/css" />
    
    <?php if($oswc_background_fixed) { ?>    
    	<style type="text/css">		
			body { background-attachment:fixed !important; }		
		</style>    
    <?php } ?>
    
    <?php //this style hack is necessary because of the custom function that adds the ancestor css attribute based
	//on the custom menu item title attribute. if this wasn't here, any menu item with a child would appear
	//as if it was the current active page ancestor.
	if(is_front_page()) { ?>
    	<style type="text/css">	
			#top-menu ul li.current_page_ancestor a, 
			#top-menu ul li.current_page_parent a {background:none;color: #DCE6EE;}
			#top-menu ul li a:hover, #top-menu ul li:hover a, #top-menu ul li.over a {background: #05A;color: #fff;}
    		.cat-menu ul li.current_page_ancestor a, 
			.cat-menu ul li.current_page_parent a {background:none;}
			.cat-menu ul li a:hover, .cat-menu ul li:hover a, .cat-menu ul li.over a {#77C3E0;}
		</style>
    <?php } ?>
    
    <?php if($oswc_background!="") { ?>    
    	<style type="text/css">		
			body { background-image:url(<?php echo get_template_directory_uri(); ?>/images/backgrounds/bg-<?php echo $oswc_background; ?>); background-position:center top;
			<?php if(strpos($oswc_background,'stripe')!==false || strpos($oswc_background,'texture')!==false) { ?> 
				background-repeat:repeat;
			<?php } else { ?>
				background-repeat:no-repeat;
			<?php } ?>
			}
			#page-highlight {display:none;} /*only looks good with light bg, so just don't show it*/
			#top-menu-shadow {background:none;} /*still want the height of the element, just don't show it*/ }		
		</style>    
    <?php } ?>

	
	<style type="text/css">
        #logo-bar-wrapper {<?php if($oswc_logo_bar_image!='') { ?>background:url(<?php echo $oswc_logo_bar_image; ?>) no-repeat 0px 0px;<?php } ?>background-color:#05a;}
		<?php if($oswc_hide_logo_bar_bg && !$reviewPage) { ?>
			#logo-bar-wrapper {background:none !important;}
			#top-menu-wrapper .ribbon-shadow-left, #top-menu-wrapper .ribbon-shadow-right {display:none;} /*ribbon shadows don't look right if logo bar has no bg*/
			#top-menu-wrapper {width:100%;} /*looks better with a full-width top bar if there is no logo bar bg*/
			#top-menu {margin:0px auto;}
			#logo-bar-shadow {display:none;} /*don't need the logo bar shadow anymore since it's transparent*/	
			#cat-menu {-moz-border-radius-topleft: 5px;border-top-left-radius: 5px;-moz-border-radius-topright: 5px;border-top-right-radius: 5px;margin-top:10px;} /*round the corners of the cat menu*/
		<?php } ?>
        #dontmiss-header {color:#<?php echo $oswc_color_scheme; ?>;}
    </style>
    
    
    <?php 
	//set the default logo image
	if($oswc_logo_ipad == '') $oswc_logo_ipad = $oswc_logo;
	if($oswc_logo_iphone == '') $oswc_logo_iphone = $oswc_logo;
	?>
	
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

	<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
    
    <?php wp_enqueue_script("jquery"); //load jquery ?>
    
	<?php wp_head(); ?>
	
</head>

<body <?php body_class($skin); ?>>

	<?php oswc_get_template_part('demo-panel'); ?>

	<div id="top-menu-wrapper"> <!-- begin top menu -->
    	  
    	<div id="top-menu">
            
            <div class="container<?php if(!$oswc_search_show && !$oswc_top_widget_show) { ?> wide<?php } elseif($oswc_search_show) { ?> mid<?php } ?>">
            
				<?php //title attribute gets in the way - remove it
                $menu = wp_nav_menu( array( 'theme_location' => 'top-menu', 'container' => 'div', 'fallback_cb' => false, 'container_class' => 'menu', 'echo' => '0' ) );
                $menu = preg_replace('/title=\"(.*?)\"/','',$menu);
                //top menu, ganti dengan www.itb, yang ori nya $menu
				
				//echo $menu;
				
				echo '<div class="menu">
			<ul class="menu" id="menu-top-menu">
						<li class="menu-item"><a href="'.esc_url( home_url( '/' ) ).'" title="Home, accesskey 1" accesskey="1">Home</a> </li>
						<li class="menu-item"><a href="http://www.itb.ac.id/about-itb" title="Tentang ITB">Tentang ITB</a>
							<ul class="sub-menu">
								<li class="menu-item"><a href="http://www.itb.ac.id/about-itb/">Informasi Umum</a></li>
								<li class="menu-item"><a href="http://www.itb.ac.id/about-itb/facts" >Fakta dan Angka</a></li>
								<li class="menu-item"><a href="http://www.itb.ac.id/about-itb/timeline">Sejarah dan Masa Depan</a></li>
								<li class="menu-item"><a href="http://www.itb.ac.id/education/">Pendidikan</a></li>
								<li class="menu-item"><a href="http://www.itb.ac.id/research/">Penelitian</a></li>
								<li class="menu-item"><a href="http://ditbang.itb.ac.id/">Pengembangan Kampus</a></li>
							</ul>
						</li>
						<li class="menu-item"><a href="http://www.itb.ac.id/about-itb/chart" title="Organisasi">Organisasi</a>
							<ul class="sub-menu">
								<li class="menu-item"><a href="http://www.itb.ac.id/about-itb/officer/">Pimpinan Institut</a></li>   
								<li class="menu-item"><a href="http://www.itb.ac.id/about-itb/chart">Struktur Organisasi</a></li>
								<li class="menu-item"><a href="http://www.itb.ac.id/mwa">Majelis Wali Amanat</a></li>
								<li class="menu-item"><a href="http://mgb.itb.ac.id/">Majelis Guru Besar</a></li>
								<li class="menu-item"><a href="http://sa.itb.ac.id/">Senat Akademik</a></li>
								<li class="menu-item"><a href="http://www.itb.ac.id/community">Komunitas</a></li>			
							</ul>
						</li>
						<li class="menu-item"><a href="http://www.itb.ac.id/education/">Fakultas/Sekolah</a>	
							<ul class="sub-menu">
								<li class="menu-item"><a href="http://www.fmipa.itb.ac.id">FMIPA</a></li>
								<li class="menu-item"><a href="http://www.fti.itb.ac.id">FTI</a></li>
								<li class="menu-item"><a href="http://www.sps.itb.ac.id">SPS</a> </li>
								<li class="menu-item"><a href="http://www.fa.itb.ac.id">SF</a></li>
								<li class="menu-item"><a href="http://www.sappk.itb.ac.id">SAPPK</a></li>
								<li class="menu-item"><a href="http://www.fitb.itb.ac.id/">FITB</a></li>
								<li class="menu-item"><a href="http://www.fttm.itb.ac.id">FTTM</a></li>
								<li class="menu-item"><a href="http://www.sbm.itb.ac.id">SBM</a></li>
								<li class="menu-item"><a href="http://www.fsrd.itb.ac.id">FSRD</a></li>
								<li class="menu-item"><a href="http://www.stei.itb.ac.id">STEI</a></li>
								<li class="menu-item"><a href="http://www.ftmd.itb.ac.id">FTMD</a></li>
								<li class="menu-item"><a href="http://www.sith.itb.ac.id">SITH</a></li>
								<li class="menu-item"><a href="http://www.ftsl.itb.ac.id">FTSL</a></li>
							</ul>
						</li>
						<li class="menu-item"><a href="http://www.itb.ac.id/education">Akademik</a>
							<ul class="sub-menu">
								<li class="menu-item"><a href="http://www.itb.ac.id/agenda/academic">Kalender Akademik</a></li>
								<li class="menu-item"><a href="http://www.itb.ac.id/usm-itb">Penerimaan Program Sarjana</a></li>
								<li class="menu-item"><a href="http://sps.itb.ac.id">Penerimaan Program Pasca Sarjana</a></li>
								<li class="menu-item"><a href="http://international.itb.ac.id">International Students Admission</a></li>
								<!--<li class="menu-item"><a href="http://km.itb.ac.id/">Info Beasiswa</a></li>-->
							</ul>
						</li>	
						<li class="menu-item"><a href="http://www.itb.ac.id/about-itb/facilities/list">Fasilitas</a>
							<ul class="sub-menu">
								<li class="menu-item"><a href="http://www.lib.itb.ac.id">Perpustakaan</a></li>
								<li class="menu-item"><a href="http://www.lc.itb.ac.id">Pusat Bahasa</a></li>
								<li class="menu-item"><a href="http://yankes.itb.ac.id/">Bumi Medika Ganesha</a></li>
								<li class="menu-item"><a href="http://www.itb.ac.id/files/12/20130220/petaKantinITBGanesha.pdf">Kantin</a></li>			
							</ul>
						</li>
						<li class="menu-item"><a href="#">Layanan</a>
							<ul class="sub-menu">
								<li class="menu-item"><a href="http://www.itb.ac.id/research/journal">ITB Journals </a></li>
								<li class="menu-item"><a href="http://journal.itb.ac.id/">Proceedings ITB</a></li>
								<li class="menu-item"><a href="http://citation.itb.ac.id/">Citation Index</a></li>			
								<li class="menu-item"><a href="http://www.lppm.itb.ac.id/research">Research Output</a></li>			
								<li class="menu-item"><a href="http://digilib.itb.ac.id">Digital Library </a></li>
								<li class="menu-item"><a href="http://blendedlearning.itb.ac.id/">Digital Learning </a></li>
								<li class="menu-item"><a href="http://students.itb.ac.id">Webmail students</a></li>
								<li class="menu-item"><a href="http://staff.itb.ac.id">Webmail Staff</a></li>						
								<li class="menu-item"><a href="http://karir.itb.ac.id">Career Center</a></li>
								<li class="menu-item"><a href="http://www.itb.ac.id/directory">Directory</a> </li>
							</ul>
						</li>
					</ul>					
				</div>';
				
                ?>             
                
				<select id="select-menu-top-menu" class="selectBox" style="display: none;">
					<option>Top Menu</option>
					<option value="<?php echo home_url(); ?>/">Home</option>
					<option value="http://www.itb.ac.id/about-itb" title="Tentang ITB">Tentang ITB</option>
						<option value="http://www.itb.ac.id/about-itb/">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Informasi Umum</option>
						<option value="<a href="http://www.itb.ac.id/about-itb/facts" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fakta dan Angka</option>
						<option value="http://www.itb.ac.id/about-itb/timeline">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sejarah dan Masa Depan</option>
						<option value="http://www.itb.ac.id/education/">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pendidikan</option>
						<option value="http://www.itb.ac.id/research/">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Penelitian</option>
						<option value="http://ditbang.itb.ac.id/">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pengembangan Kampus</option>		
					<option value="http://www.itb.ac.id/about-itb/chart" title="Organisasi">Organisasi</option>
						<option value=""http://www.itb.ac.id/about-itb/officer/">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pimpinan Institut</option> 
						<option value="http://www.itb.ac.id/about-itb/chart">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Struktur Organisasi</option>
						<option value="http://www.itb.ac.id/mwa">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Majelis Wali Amanat</option>
						<option value="http://mgb.itb.ac.id/">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Majelis Guru Besar</option>
						<option value="http://sa.itb.ac.id/">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Senat Akademik</option>
						<option value="http://www.itb.ac.id/community">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Komunitas</option>
						
				</select>
				
            </div>
			
			      
				                
                
            
            
				<!-- social widget by default -->
            
            	<div id="top-widget">
                        
                        <div class="top-social">
                        
                        	<a href="<?php bloginfo('rss2_url'); ?>" class="rss">&nbsp;</a>
                            
                            <a href="<?php 
											if(trim($oswc_facebook_url) == '')
												echo 'http://www.facebook.com/institutteknologibandung';
											else
												echo $oswc_facebook_url;
							
										?>" class="facebook">&nbsp;</a>
                            
                            <a href="<?php 
											if(trim($oswc_twitter_url) == '')
												echo 'http://twitter.com/itbofficial';
											else
												echo $oswc_twitter_url;
						
										?>" class="twitter">&nbsp;</a>
                        
                        </div>      
                
                </div>
            
            
                <div id="search">
                
                    <div class="wrapper">
                    
                        <div class="inner">
                
                            <!-- SEARCH -->  
                            <form method="get" id="searchformtop" action="<?php echo home_url(); ?>/">                             
                                <input type="text" value="<?php _e( 'Cari', 'itb' ); ?>" onfocus="if (this.value == '<?php _e( 'Cari', 'itb' ); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e( 'Cari', 'itb' ); ?>';}" name="s" id="s" />          
                            </form>                       
                            
                        </div>
                        
                    </div>
                
                </div>
                
           
            
            <br class="clearer" />
        
        </div>
        
    
    </div>
	
	<div id="page-wrapper"> <!-- everything below the top menu should be inside the page wrapper div -->
    
    	<div id="logo-bar-wrapper">  <!--begin the main header logo area-->

            <div id="logo-bar">
            
                <div id="logo-wrapper">
                
                    <div id="logo"><!--logo and section header area-->
            
                        
                            <a href="<?php echo home_url(); ?>/">
                                <img id="site-logo" alt="<?php bloginfo('name'); ?>" style="float:left" src="<?php echo get_template_directory_uri().'/images/logo-main.png'; ?>" />
                                <img id="site-logo-iphone" alt="<?php bloginfo('name'); ?>" style="float:left" src="<?php echo get_template_directory_uri().'/images/logo-main.png'; ?>" />
                                <img id="site-logo-ipad" alt="<?php bloginfo('name'); ?>" style="float:left" src="<?php echo get_template_directory_uri().'/images/logo-main.png'; ?>" />
                            </a>
                             
                            <h1 style="float:right;"><a href="<?php echo home_url(); ?>/" style="color:#fff;"><?php bloginfo('name'); ?></a></h1>
                        
                    </div>
                    
                    <div class="clearer" /></div>
                    
                    <!--<div class="subtitle<?php echo $subtitleclass; ?>"><?php bloginfo('description'); ?></div>-->
					<div id="cat-menu" class="cat-menu">
        
        	<!--<a class="home-link" href="<?php echo home_url(); ?>">&nbsp;</a>-->
    
            <?php 
            //title attribute gets in the way - remove it
            $menu = wp_nav_menu( array( 'theme_location' => 'main-menu', 'container' => '0', 'fallback_cb' => 'fallback_categories', 'echo' => '0' ) );
            $menu = preg_replace('/title=\"(.*?)\"/','',$menu);
            echo $menu;
            ?> 
            
            <?php //get a separate drop down menu for responsive
			$menu_name = 'main-menu';
			if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) {
				$menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
			
				$menu_items = wp_get_nav_menu_items($menu->term_id);
			
				$menu_list = '<select id="select-menu-' . $menu_name . '"><option>'.__( 'Navigation','itb').'</option>';
			
				foreach ( (array) $menu_items as $key => $menu_item ) {
					$title = $menu_item->title;
					$url = $menu_item->url;
					$parentid = $menu_item->menu_item_parent;
					$indent = '';
					if($parentid!=0) { //see if this item needs to be indented
						$indent .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';							
					}
					$objectid = $menu_item->object_id;
					$type = $menu_item->type;
					$selected = '';
					$selectedoption = '';
					if((is_tax() || is_category() || is_tag()) && $type=='taxonomy') { //see if this is the currently displayed taxonomy
						$termid = get_queried_object()->term_id;
						if($termid == $objectid) {
							$selected = "selected";
							$selectedoption = 'selected="selected"';
						}
					} elseif($objectid == $post->ID && ($type == 'post_type')) { //see if this is the currently displayed page/post
						$selected = "selected";
						$selectedoption = 'selected="selected"';
					}
					
					$menu_list .= '<option ';
					if($selectedoption!='') {
						$menu_list .= $selectedoption;
					}
					$menu_list .= ' ';
					if($selected!='') {
						$menu_list .= 'class="' . $selected . '"';
					}
					$menu_list .= ' value="' . $url . '">' . $indent . $title . '</option>';
				}
				$menu_list .= '</select>';
			} else {
				//$menu_list = '<ul><li>Menu "' . $menu_name . '" not defined.</li></ul>';
				
				$menu_list = '';
			}
			
			echo $menu_list;
			?>                            
            
            
        </div>
                    
                </div>  
                
                <br class="clearer" />
                
            </div>
            
            <!--<div id="logo-bar-shadow">&nbsp;</div>-->
            
        </div> <!--end the logo area -->
            
         
        
        <br class="clearer hide-responsive-small" />
        
        <!--sub menu-->
        
        
        <div id="main-wrapper">
        
        <div id="main-wrapper-dark"> <!-- this is only used for the dark skin since it already uses an image for the background texture and light does not -->
        
			 
                     
