<!DOCTYPE html> 
<html class="no-js" <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php wp_title(''); ?></title>

	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">	
	<link rel='stylesheet' href='<?php echo get_site_url()?>/wp-content/themes/itb2016/bootstrap.css' type='text/css' media='all' />
	<?php wp_head(); ?>
	<?php
	if ( ot_get_option('custom-logo') ) {
		$logo = '<link rel="image_src" href="'.ot_get_option('custom-logo').'" alt="'.get_bloginfo('name').'">';
	} else {
		$logo = get_bloginfo('name');
	}
	echo $logo; 
	?>
</head>

<body <?php body_class(); ?>>

<div id="wrapper">

	<header id="header">
	
		<?php if (has_nav_menu('topbar')): ?>
			<nav class="nav-container group" id="nav-topbar">
				<div class="nav-toggle"><i class="fa fa-bars"></i></div>
				<div class="nav-text"><!-- put your mobile menu text here --></div>
				<div class="nav-wrap container">

<?php /* wp_nav_menu(array('theme_location'=>'topbar','menu_class'=>'nav container-inner group','container'=>'','menu_id' => '','fallback_cb'=> false)); */ ?>

<?php /* do_action( 'the_languages', array('show_flags' => 1, 'show_names' => 0) ); */ ?>

<ul id="menu-top-menu" class="nav container-inner group">
						<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children"><a href="http://www.itb.ac.id/about-itb" title="Tentang ITB">Tentang ITB</a>
							<ul class="sub-menu" style="display: none;">
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://www.itb.ac.id/about-itb/">Informasi Umum</a></li>
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://www.itb.ac.id/about-itb/facts" >Fakta dan Angka</a></li>
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://www.itb.ac.id/about-itb/timeline">Sejarah dan Masa Depan</a></li>
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://www.itb.ac.id/education/">Pendidikan</a></li>
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://www.itb.ac.id/research/">Penelitian</a></li>
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://ditbang.itb.ac.id/">Pengembangan Kampus</a></li>
							</ul>
						</li>
						<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children"><a href="http://www.itb.ac.id/about-itb/chart" title="Organisasi">Organisasi</a>
							<ul class="sub-menu" style="display: none;">
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://www.itb.ac.id/about-itb/officer/">Pimpinan Institut</a></li>   
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://www.itb.ac.id/about-itb/chart">Struktur Organisasi</a></li>
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://www.itb.ac.id/mwa">Majelis Wali Amanat</a></li>
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://mgb.itb.ac.id/">Majelis Guru Besar</a></li>
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://sa.itb.ac.id/">Senat Akademik</a></li>
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://www.itb.ac.id/community">Komunitas</a></li>			
							</ul>
						</li>
						<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children"><a href="http://www.itb.ac.id/education/">Fakultas/Sekolah</a>	
							<ul class="sub-menu" style="display: none;">
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://www.fmipa.itb.ac.id">FMIPA</a></li>
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://www.fti.itb.ac.id">FTI</a></li>
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://www.sps.itb.ac.id">SPS</a> </li>
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://www.fa.itb.ac.id">SF</a></li>
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://www.sappk.itb.ac.id">SAPPK</a></li>
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://www.fitb.itb.ac.id/">FITB</a></li>
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://www.fttm.itb.ac.id">FTTM</a></li>
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://www.sbm.itb.ac.id">SBM</a></li>
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://www.fsrd.itb.ac.id">FSRD</a></li>
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://www.stei.itb.ac.id">STEI</a></li>
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://www.ftmd.itb.ac.id">FTMD</a></li>
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://www.sith.itb.ac.id">SITH</a></li>
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://www.ftsl.itb.ac.id">FTSL</a></li>
							</ul>
						</li>
						<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children"><a href="http://www.itb.ac.id/education">Akademik</a>
							<ul class="sub-menu" style="display: none;">
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://www.itb.ac.id/agenda/academic">Kalender Akademik</a></li>
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://www.itb.ac.id/usm-itb">Penerimaan Program Sarjana</a></li>
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://sps.itb.ac.id">Penerimaan Program Pasca Sarjana</a></li>
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://international.itb.ac.id">International Students Admission</a></li>
								<!--<li class="menu-item"><a href="http://km.itb.ac.id/">Info Beasiswa</a></li>-->
							</ul>
						</li>	
						<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children"><a href="http://www.itb.ac.id/about-itb/facilities/list">Fasilitas</a>
							<ul class="sub-menu" style="display: none;">
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://www.lib.itb.ac.id">Perpustakaan</a></li>
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://www.lc.itb.ac.id">Pusat Bahasa</a></li>
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://yankes.itb.ac.id/">Bumi Medika Ganesha</a></li>
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://www.itb.ac.id/files/12/20130220/petaKantinITBGanesha.pdf">Kantin</a></li>			
							</ul>
						</li>
						<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children"><a href="#">Layanan</a>
							<ul class="sub-menu" style="display: none;">
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://www.itb.ac.id/research/journal">ITB Journals </a></li>
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://journal.itb.ac.id/">Proceedings ITB</a></li>
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://citation.itb.ac.id/">Citation Index</a></li>			
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://www.lppm.itb.ac.id/research">Research Output</a></li>			
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://digilib.itb.ac.id">Digital Library </a></li>
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://blendedlearning.itb.ac.id/">Digital Learning </a></li>
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://students.itb.ac.id">Webmail students</a></li>
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://staff.itb.ac.id">Webmail Staff</a></li>						
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://karir.itb.ac.id">Career Center</a></li>
								<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="http://www.itb.ac.id/directory">Directory</a> </li>
							</ul>
						</li>
					</ul>	


</div>
				
				<div class="container">
					<div class="container-inner">		
						<div class="toggle-search"><i class="fa fa-search"></i></div>
						<div class="search-expand">
							<div class="search-expand-inner">
								<?php get_search_form(); ?>
							</div>
						</div>
					</div><!--/.container-inner-->
				</div><!--/.container-->
				
			</nav><!--/#nav-topbar-->
		<?php endif; ?>
		
		<div class="container group">
			<div class="container-inner">
				
				<div class="group pad">
					<?php echo alx_site_title(); ?>
					<?php if ( ot_get_option('site-description') != 'off' ): ?><p class="site-description"><?php bloginfo( 'description' ); ?></p><?php endif; ?>
                    <!--<h1 class="site-title titlepad">Megatron ITB - under construction</h1>
                    <h2>Institut Teknologi Bandung</h2>-->
				</div>
				
				<?php if (has_nav_menu('header')): ?>
					<nav class="nav-container group" id="nav-header">
						<div class="nav-toggle"><i class="fa fa-bars"></i></div>
						<div class="nav-text"><!-- put your mobile menu text here --></div>
						<div class="nav-wrap"><?php wp_nav_menu(array('theme_location'=>'header','menu_class'=>'nav container-inner group','container'=>'','menu_id' => '','fallback_cb'=> false)); ?></div>
					</nav><!--/#nav-header-->
				<?php endif; ?>
				
			</div><!--/.container-inner-->
		</div><!--/.container-->
		
	</header><!--/#header-->
	
	<div class="container" id="page">
		<div class="container-inner">
			<div class="main">
				<div class="main-inner group">