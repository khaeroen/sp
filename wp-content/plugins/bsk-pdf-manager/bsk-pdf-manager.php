<?php

/*
Plugin Name: BSK PDF Manager
Description: Help you manage your PDF documents. PDF documents can be filter by category. Support short code to show special PDF documents or all PDF documents under  category. Widget supported.
Version: 1.7.2
Author: bannersky
Author URI: http://www.bannersky.com/

------------------------------------------------------------------------

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, 
or any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*/
require_once('inc/bsk-pdf-manager-widget.php');
require_once('inc/bsk-pdf-manager-widget-category.php');

class BSKPDFManager {
	
	var $_bsk_pdf_manager_plugin_version = '1.7.2';
	var $_bsk_pdf_manager_upload_folder = 'wp-content/uploads/bsk-pdf-manager/';
	var $_bsk_pdf_manager_upload_path = ABSPATH;
	var $_bsk_pdf_manager_admin_notice_message = array();

	var $_bsk_pdf_manager_cats_tbl_name = 'bsk_pdf_manager_cats';
	var $_bsk_pdf_manager_pdfs_tbl_name = 'bsk_pdf_manager_pdfs';
	
	var $_bsk_pdf_manager_pages = array('category' => 'bsk-pdf-manager', 'pdf' => 'bsk-pdf-manager-pdfs', 'setting' => 'bsk-pdf-manager-settings-support', 'support' => 'bsk-pdf-manager-settings-support');
	
	//objects
	var $_bsk_pdf_manager_OBJ_dashboard = NULL;
	
	var $_default_pdf_icon_url = '';
	
	public function __construct() {
		global $wpdb;
		
		$this->_bsk_pdf_manager_cats_tbl_name = $wpdb->prefix.$this->_bsk_pdf_manager_cats_tbl_name;
		$this->_bsk_pdf_manager_pdfs_tbl_name = $wpdb->prefix.$this->_bsk_pdf_manager_pdfs_tbl_name;
		$this->_bsk_pdf_manager_upload_path = str_replace("\\", "/", $this->_bsk_pdf_manager_upload_path);
		
		$this->_default_pdf_icon_url = plugin_dir_url( __FILE__ ).'images/default_PDF_icon.png';

		if(is_admin()) {
			add_action( 'admin_notices', array($this, 'bsk_pdf_manager_admin_notice') );
			add_action( 'admin_enqueue_scripts', array($this, 'bsk_pdf_manager_enqueue_scripts_css') );
		}else{
			add_action( 'wp_enqueue_scripts', array($this, 'bsk_pdf_manager_enqueue_scripts_css') );
		}
		add_action( 'widgets_init', create_function( '', 'register_widget( "BSKPDFManagerWidget" );' ) );
		add_action( 'widgets_init', create_function( '', 'register_widget( "BSKPDFManagerWidgetCategory" );' ) );
		
		//include others class
		require_once( 'inc/bsk-pdf-dashboard.php' );
		
		$arg = array();
		$arg['upload_folder'] = $this->_bsk_pdf_manager_upload_folder;
		$arg['upload_path'] = $this->_bsk_pdf_manager_upload_path;
		$arg['cat_tbl_name'] = $this->_bsk_pdf_manager_cats_tbl_name;
		$arg['pdf_tbl_name'] = $this->_bsk_pdf_manager_pdfs_tbl_name;
		$arg['pages_name_A'] = $this->_bsk_pdf_manager_pages;
		$arg['plugin_version'] = $this->_bsk_pdf_manager_plugin_version;
		$arg['default_pdf_icon'] = $this->_default_pdf_icon_url;
		
		$this->_bsk_pdf_manager_OBJ_dashboard = new BSKPDFManagerDashboard( $arg );
		
		//hooks
		register_activation_hook(__FILE__, array($this, 'bsk_pdf_manager_activate') );
		register_deactivation_hook( __FILE__, array($this, 'bsk_pdf_manager_deactivate') );
		register_uninstall_hook( __FILE__, 'BSKPDFManager::bsk_pdf_manager_uninstall' );
		
		add_action('init', array($this, 'bsk_pdf_manager_post_action'));
		
		$this->bsk_pdf_create_upload_folder_and_set_secure();
	}
	
	function bsk_pdf_manager_activate(){
		//create or update table
		$this->bsk_pdf_manager_create_table();
		
		// Clear the permalinks
		flush_rewrite_rules();
	}
	
	function bsk_pdf_manager_deactivate(){
		// Clear the permalinks
		flush_rewrite_rules();
	}
	
	function bsk_pdf_manager_uninstall(){
		//check if pro version installed
		$plugin_root_path = str_replace( "\\", "/", ABSPATH );
		if ( file_exists( $plugin_root_path.'wp-content/plugins/bsk-pdf-manager-pro/bsk-pdf-manager-pro.php' ) ){
			return;
		}

		BSKPDFManager::bsk_pdf_manager_remove_table();
	}
	
	function bsk_pdf_manager_enqueue_scripts_css(){
		wp_enqueue_script('jquery');
		if( is_admin() ){
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_style( 'jquery-ui', 'https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css' );
			wp_enqueue_script( 'bsk-pdf-manager-admin', plugins_url('js/bsk_pdf_manager_admin.js', __FILE__), array('jquery'), $this->_bsk_pdf_manager_plugin_version );
			wp_enqueue_style( 'bsk-pdf-manager-pro-admin', plugins_url('css/bsk-pdf-manager-admin.css', __FILE__), array(), $this->_bsk_pdf_manager_plugin_version );
		}else{
			wp_enqueue_script( 'bsk-pdf-manager', plugins_url('js/bsk_pdf_manager.js', __FILE__), array('jquery'), $this->_bsk_pdf_manager_plugin_version );
		}
	}
	
	function bsk_pdf_manager_admin_notice(){
		$warning_message = array();
		$error_message = array();
		
		//admin message
		if (count($this->_bsk_pdf_manager_admin_notice_message) > 0){
			foreach($this->_bsk_pdf_manager_admin_notice_message as $msg){
				if($msg['type'] == 'ERROR'){
					$error_message[] = $msg['message'];
				}
				if($msg['type'] == 'WARNING'){
					$warning_message[] = $msg['message'];
				}
			}
		}
		
		//show error message
		if(count($warning_message) > 0){
			echo '<div class="update-nag">';
			foreach($warning_message as $msg_to_show){
				echo '<p>'.$msg_to_show.'</p>';
			}
			echo '</div>';
		}
		
		//show error message
		if(count($error_message) > 0){
			echo '<div class="error">';
			foreach($error_message as $msg_to_show){
				echo '<p>'.$msg_to_show.'</p>';
			}
			echo '</div>';
		}
	}

	function bsk_pdf_manager_create_table(){
		global $wpdb;
		
		require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
		
		
		if (!empty ($wpdb->charset)){
			$charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
		}
		if (!empty ($wpdb->collate)){
			$charset_collate .= " COLLATE {$wpdb->collate}";
		}
		
		$table_name = $this->_bsk_pdf_manager_cats_tbl_name;
		$sql = "CREATE TABLE IF NOT EXISTS " . $table_name . " (
				      `id` int(11) NOT NULL AUTO_INCREMENT,
					  `cat_title` varchar(512) NOT NULL,
					  `last_date` datetime DEFAULT NULL,
					  PRIMARY KEY (`id`)
				) $charset_collate;";
		dbDelta($sql);
		
		$table_name = $this->_bsk_pdf_manager_pdfs_tbl_name;
		$sql = "CREATE TABLE IF NOT EXISTS " . $table_name . " (
				     `id` int(11) NOT NULL AUTO_INCREMENT,
					  `cat_id` int(11) NOT NULL,
					  `title` varchar(512) DEFAULT NULL,
					  `file_name` varchar(512) NOT NULL,
					  `last_date` datetime DEFAULT NULL,
					  PRIMARY KEY (`id`)
				) $charset_collate;";
		dbDelta($sql);
	}
	
	function bsk_pdf_manager_remove_table(){
		global $wpdb;
		
        $table_cats = $wpdb->prefix."bsk_pdf_manager_cats";
		$table_pdfs = $wpdb->prefix."bsk_pdf_manager_pdfs";
		
		$wpdb->query("DROP TABLE IF EXISTS $table_cats");
		$wpdb->query("DROP TABLE IF EXISTS $table_pdfs");		
	}
	
	function bsk_pdf_manager_post_action(){
		if( isset( $_POST['bsk_pdf_manager_action'] ) && strlen($_POST['bsk_pdf_manager_action']) >0 ) {
			do_action( 'bsk_pdf_manager_' . $_POST['bsk_pdf_manager_action'], $_POST );
		}
	}
	
	function bsk_pdf_create_upload_folder_and_set_secure(){
		//create folder to upload 
		$_bsk_pdf_manager_upload_folder_path = $this->_bsk_pdf_manager_upload_path.$this->_bsk_pdf_manager_upload_folder;
		if ( !is_dir($_bsk_pdf_manager_upload_folder_path) ) {
			if ( !wp_mkdir_p( $_bsk_pdf_manager_upload_folder_path ) ) {
				$this->_bsk_pdf_manager_admin_notice_message['upload_folder_missing']  = array( 'message' => 'Directory <strong>' . $_bsk_pdf_manager_upload_folder . '</strong> can not be created. Please create it first yourself.',
				                                                                                'type' => 'ERROR');
			}
		}
		
		if ( !is_writeable( $_bsk_pdf_manager_upload_folder_path ) ) {
			$msg  = 'Directory <strong>' . $this->_bsk_pdf_manager_upload_folder_path . '</strong> is not writeable ! ';
			$msg .= 'Check <a href="http://codex.wordpress.org/Changing_File_Permissions">http://codex.wordpress.org/Changing_File_Permissions</a> for how to set the permission.';

			$this->_bsk_pdf_manager_admin_notice_message['upload_folder_not_writeable']  = array( 'message' => $msg,
			                                                                                      'type' => 'ERROR');
		}

		//copy file to upload foloder
		if( !file_exists($_bsk_pdf_manager_upload_folder_path.'/index.php') ){
			copy( dirname(__FILE__).'/assets/index.php', $_bsk_pdf_manager_upload_folder_path.'/index.php' );
		}
	}
}

$BSK_PDF_manager = new BSKPDFManager();
