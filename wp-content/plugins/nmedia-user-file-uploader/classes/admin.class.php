<?php
/*
 * working behind the seen
 */
class NM_WP_FileManager_Admin extends NM_WP_FileManager {
	var $menu_pages, $plugin_scripts_admin, $plugin_settings;
	function __construct() {
		
		// setting plugin meta saved in config.php
		$this->plugin_meta = filemanager_get_plugin_meta ();
		
		// getting saved settings
		$this->plugin_settings = get_option ( 'nm-filemanager' . '_settings' );
		
		// file upload dir name
		$this->contact_files = 'contact_files';
		
		/*
		 * [1] TODO: change this for plugin admin pages
		 */
		$this->menu_pages = array (
				array (
							'page_title' => $this->plugin_meta ['name'],
							'menu_title' => $this->plugin_meta ['name'],
							'cap' => 'edit_plugins',
							'slug' => $this->plugin_meta ['shortname'],
							'callback' => 'main_settings',
							'parent_slug' => ''
					),
		);
		
		/*
		 * [2] TODO: Change this for admin related scripts JS scripts and styles to loaded ADMIN
		 */
		$this->plugin_scripts_admin = array (
				
				array (
						'script_name' => 'scripts-admin',
						'script_source' => '/js/admin.js',
						'localized' => true,
						'type' => 'js',
						'page_slug' => $this->plugin_meta ['shortname'],
						'depends' => array (
								'jquery',
								'jquery-ui-accordion',
								'jquery-ui-draggable',
								'jquery-ui-droppable',
								'jquery-ui-sortable',
								'jquery-ui-slider',
								'jquery-ui-dialog',
								'jquery-ui-tabs',
								'media-upload',
								'thickbox'
						) 
				),
				array (
						'script_name' => 'ui-style',
						'script_source' => '/js/ui/css/smoothness/jquery-ui-1.10.3.custom.min.css',
						'localized' => false,
						'type' => 'style',
						'page_slug' => $this->plugin_meta ['shortname'],
						'depends' => '',
				),
				
				
				array (
						'script_name' => 'plugin-css',
						'script_source' => '/templates/admin/style.css',
						'localized' => false,
						'type' => 'style',
						'page_slug' => $this->plugin_meta ['shortname'],
						'depends' => '',
				) 
		);
		
		add_action ( 'admin_menu', array (
				$this,
				'add_menu_pages' 
		) );
		add_action ( 'admin_init', array (
				$this,
				'init_admin' 
		) );
	}
	function load_scripts_admin() {
		
		// localized vars in js
		$arrLocalizedVars = array (
				'plugin_url' => $this->plugin_meta ['url'],
				'doing' => $this->plugin_meta ['url'] . '/images/loading.gif',
				'plugin_admin_page' => admin_url ( 'admin.php?page='.$this->plugin_meta ['shortname'] ) 
		);
		
		// admin end scripts
		
		if ($this->plugin_scripts_admin) {
			foreach ( $this->plugin_scripts_admin as $script ) {
				
				// checking if it is style
				if ($script ['type'] == 'js') {
					wp_enqueue_script ( 'nm-filemanager' . '-' . $script ['script_name'], $this->plugin_meta ['url'] . $script ['script_source'], $script ['depends'] );
					
					// if localized
					if ($script ['localized'])
						wp_localize_script ( 'nm-filemanager' . '-' . $script ['script_name'], $this->plugin_meta ['shortname'] . '_vars', $arrLocalizedVars );
				} else {
					
					if($script ['script_source'] == 'shipped')
						wp_enqueue_style($script ['script_name']);
					else 
						wp_enqueue_style ( 'nm-filemanager' . '-' . $script ['script_name'], $this->plugin_meta ['url'] . $script ['script_source'] );
				}
			}
		}
	}
	
	/*
	 * creating menu page for this plugin
	 */
	function add_menu_pages() {
		foreach ( $this->menu_pages as $page ) {
			
			if ($page ['parent_slug'] == '') {
				
				$menu = add_menu_page ( __ ( $page ['page_title'] . ' Settings', 'nm-filemanager' ), __ ( $page ['menu_title'] . ' Settings', 'nm-filemanager' ), $page ['cap'], $page ['slug'], array (
						$this,
						$page ['callback'] 
				), $this->plugin_meta ['logo'], $this->plugin_meta ['menu_position'] );
			} else {
				
				$menu = add_submenu_page ( $page ['parent_slug'], __ ( $page ['page_title'] . ' Settings', 'nm-filemanager' ), __ ( $page ['menu_title'] . ' Settings', 'nm-filemanager' ), $page ['cap'], $page ['slug'], array (
						$this,
						$page ['callback'] 
				) );
			}
			
			// loading script for only plugin optios pages
			// page_slug is key in $plugin_scripts_admin which determine the page
			foreach ( $this->plugin_scripts_admin as $script ) {
				
				if (is_array ( $script ['page_slug'] )) {
					
					if (in_array ( $page ['slug'], $script ['page_slug'] ))
						add_action ( 'admin_print_scripts-' . $menu, array (
								$this,
								'load_scripts_admin' 
						) );
				} else if ($script ['page_slug'] == $page ['slug']) {
					add_action ( 'admin_print_scripts-' . $menu, array (
							$this,
							'load_scripts_admin' 
					) );
				}
			}
		}
	}
	
	/**
	 * after init admin
	 */
	function init_admin() {
		add_meta_box ( 'contact_forms_meta_box', 'Uploaded file', array (
				$this,
				'display_contact_form_meta_box' 
		), 'nm-forms', 'normal', 'high' );
	}
	
	/**
	* these are loading files in custom post type editor in admin
	*/
	
	function display_contact_form_meta_box($form) {
		echo '<p>' . __ ( 'Following files are uploaded:', 'nm-filemanager' ) . '</p>';
		
		$uploaded_files = get_post_meta ( $form->ID, 'uploaded_files', true );
		$uploaded_files = json_decode ( $uploaded_files );
		
		
		echo '<table>';
		
		if ($uploaded_files) {
			foreach ( $uploaded_files as $id => $file ) {
				
				$file_url = $this->get_file_dir_url () . $file;
				
				$type = strtolower ( substr ( strrchr ( $file, '.' ), 1 ) );
				if (($type == "gif") || ($type == "jpeg") || ($type == "png") || ($type == "pjpeg") || ($type == "jpg"))
					$thumb_url = $this->get_file_dir_url ( true ) . $file;
				else
					$thumb_url = $this->plugin_meta ['url'] . '/images/file.png';
				
				echo '<tr>';
				echo '<td style="width: 20%"><img src="' . $thumb_url . '" /></td>';
				echo '<td><a href="' . $file_url . '" target="_blank">' . __ ( 'Download file/image: '.$id, 'nm-filemanager' ) . '</a></td>';
				
				$edited_path = $this->get_file_dir_path() . 'edits/' . $file;
				if (file_exists($edited_path)) {
					$file_url_edit = $this->get_file_dir_url () .  'edits/' . $file;
					echo '<td><a href="' . $file_url_edit . '" target="_blank">' . __ ( 'Download edited image: '.$id, 'nm-filemanager' ) . '</a></td>';;
				}
				echo '</tr>';
			}
		}
		echo '</table>';
	}
	
	// ====================== CALLBACKS =================================
	
	
	function main_settings() {
		$this->load_template ( 'admin/settings.php' );
	}
}