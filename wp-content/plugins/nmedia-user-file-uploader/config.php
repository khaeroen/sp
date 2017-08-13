<?php
/*
 * this file contains pluing meta information and then shared
 * between pluging and admin classes
 * 
 * [1]
 */

$plugin_dir = 'nmedia-user-file-uploader';

$plugin_meta		= array('name'			=> 'FileManager',
							'dir_name'		=> $plugin_dir,
							'shortname'		=> 'nm_filemanager',
							'shortcode'		=> 'nm-wp-file-uploader',
							'path'			=> WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $plugin_dir,
							'url'			=> plugins_url( $plugin_dir , dirname(__FILE__) ),
							'db_version'	=> 3.0,
							'logo'			=> plugins_url( $plugin_dir.'/images/logo.png' , dirname(__FILE__) ),
							'menu_position'	=> 73);


function filemanager_get_plugin_meta(){
	
	global $plugin_meta;
	
	//print_r($plugin_meta);
	
	return $plugin_meta;
}




function filemanager_pa($arr){
	
	echo '<pre>';
	print_r($arr);
	echo '</pre>';
}

$filemanager_runtime = '';
function filemanager_get_browser_runtime()
{
	//print_r($_SERVER['HTTP_USER_AGENT']);

	if(!(isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false))){
		$filemanager_runtime = 'html5,flash,silverlight,html4,browserplus,gear';
	}else{
		$filemanager_runtime = 'html5,html4';
	}
	
	return $filemanager_runtime;
}

function filemanager_show_notice() {
	
	if( isset($_GET['do']) && $_GET['do'] == 'delete')
	echo '<div class="filemanager-notice">'.__('File is removed', 'nm-filemanager').'</div>';
}