<?php
/*
 * this is main plugin class
 */


/* ======= the model main class =========== */
if (! class_exists ( 'NM_Framwork_V1_filemanager' )) {
	$_framework = dirname ( __FILE__ ) . DIRECTORY_SEPARATOR . 'nm-framework.php';
	if (file_exists ( $_framework ))
		include_once ($_framework);
	else
		die ( 'Reen, Reen, BUMP! not found ' . $_framework );
}

/*
 * [1]
 */
class NM_WP_FileManager extends NM_Framwork_V1_filemanager {
	
	
	private static $ins = null;
	
	public static function init()
	{
		add_action('plugins_loaded', array(self::get_instance(), '_setup'));
	}
	
	public static function get_instance()
	{
		// create a new object if it doesn't exist.
		is_null(self::$ins) && self::$ins = new self;
		return self::$ins;
	}
	
	
	static $tbl_file_meta = 'nm_files_meta';
	var $allow_file_upload;
	var $inputs;
	var $is_html5;

	//this property is set to true when shortcode is render, then images thumbnails didn't generated
	var $is_uploader = false;
	
	/*
	 * plugin constructur
	 */
	function _setup() {
		//ini_set( 'mysql.trace_mode', 0 );
		// setting plugin meta saved in config.php
		$this->plugin_meta = filemanager_get_plugin_meta ();
		
		// getting saved settings
		$this->plugin_settings = get_option ( $this->plugin_meta ['shortname'] . '_settings' );
		
		// file upload dir name
		
		$this->user_uploads = 'user_uploads';
		
		// this will hold form form_id
		$this->form_id = '';
		
		
		
		/*
		 * [5] TODO: this array will grow as plugin grow all functions which need to be called back MUST be in this array setting callbacks
		 */
		// following array are functions name and ajax callback handlers
		$this->ajax_callbacks = array (
				'save_settings', // do not change this action, is for admin
				'save_file_data',
				'upload_file',
				'delete_file',
				'delete_file_new',
				'delete_meta',
				'save_edited_photo',
				'load_shortcodes',
				'get_form_meta',
				'send_files_email',
				'delete_all_posts',
				'delete_all_directores_of_user',
				'share_file',
				'edit_file_meta',
		);
		
		/*
		 * plugin localization being initiated here
		 */
		
		add_action ( 'init', array (
				$this,
				'wpp_textdomain' 
		) );
		
		
		/*
		 * plugin main shortcode if needed
		 */
		add_shortcode ( $this->plugin_meta ['shortcode'], array (
				$this,
				'render_shortcode_template' 
		) );
		
		/*
		 * hooking up scripts for front-end
		 */
		add_action ( 'wp_enqueue_scripts', array (
				$this,
				'load_scripts' 
		) );
		
		/*
		 * registering callbacks
		 */
		$this->do_callbacks ();
		
		/*
		 * add custom post type support if enabled
		 */
		add_action ( 'init', array (
				$this,
				'enable_custom_post' 
		) );
		
		
		/**
		 * following hooks adding post column for images and other meta
		 */
		add_filter('manage_nm-userfiles_posts_columns', array($this ,'file_thumb_column_head'));
		add_action('manage_nm-userfiles_posts_custom_column', array($this ,'file_thumb_column_content'), 10, 2);	
		
		/**
		 * adding some styles to admin
		 */
		 add_action('admin_head', array($this, 'admin_styles'));
		 
		 //preventing to not generate thumbs for images
		add_filter('intermediate_image_sizes_advanced', array($this, 'prevent_thumbs_generation'));
	}
	
	
	// i18n and l10n support here
	// plugin localization
	function wpp_textdomain() {
		$locale_dir = $this->plugin_meta['dir_name'] . '/locale/';
		load_plugin_textdomain('nm-filemanager', false, $locale_dir);
		
		$this->download_private_file();
	}
	
	
	/*
	 * =============== NOW do your JOB ===========================
	 */
	function enable_custom_post() {
		register_post_type ( 'nm-userfiles', array (
				'labels' => array (
						'name' => __ ( 'User Files', 'nm-filemanager' ),
						'singular_name' => __ ( 'User Files', 'nm-filemanager' ),
						'add_new' => __('Add New', 'nm-filemanager'),
						'add_new_item' => __('Add User Files', 'nm-filemanager'),
						'edit' => __('Edit', 'nm-filemanager'),
						'edit_item' => __('Edit User Files', 'nm-filemanager'),
						'new_item' => __('New User Files', 'nm-filemanager'),
						'view' => __('View', 'nm-filemanager'),
						'view_item' => __('View User Files', 'nm-filemanager'),
						'search_items' => __('Search User Files', 'nm-filemanager'),
						'not_found' => __('No User Files found', 'nm-filemanager'),
						'not_found_in_trash' => __('No User Files found in Trash', 'nm-filemanager'),
						'parent' => __('Parent User Files', 'nm-filemanager') 
				),
				'public' => true,
				'supports' => array (
						'title',
						'editor',
						'custom-fields' 
				),
				'menu_icon' => $this->plugin_meta ['logo'] 
		) );
	}
	
	function load_shortcodes(){
	
		$this -> load_template('admin/meta-shortcodes.php');
	
		die(0);
	}
	
	
	
	/*
	 * updating form meta in admin call
	 */
	function update_form_meta() {
		
		//print_r($_REQUEST); exit;
		global $wpdb;
		
		extract ( $_REQUEST );
		
		$dt = array (
				'form_name' => $form_name,
				'sender_email' => $sender_email,
				'sender_name' => $sender_name,
				'subject' => $subject,
				'receiver_emails' => $receiver_emails,
				'button_label' => $button_label,
				'button_class' => $button_class,
				'success_message' => stripslashes ( $success_message ),
				'error_message' => stripslashes ( $error_message ),
				'thumb_size' => stripslashes ( $thumb_size ),
				'form_style' => $form_style,
				'the_meta' => json_encode ( $form_meta ) 
		);
		
		$where = array (
				'form_id' => $form_id 
		);
		
		$format = array (
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s' 
		);
		$where_format = array (
				'%d' 
		);
		
		$res_id = $this->update_table ( self::$tbl_file_meta, $dt, $where, $format, $where_format );
		
		// $wpdb->show_errors(); $wpdb->print_error();
		
		$resp = array ();
		if ($res_id) {
			
			$resp = array (
					'message' => __ ( 'Form updated successfully', 'nm-filemanager' ),
					'status' => 'success',
					'form_id' => $form_id 
			);
		} else {
			
			$resp = array (
					'message' => __ ( 'Error while updating form, please try again', 'nm-filemanager' ),
					'status' => 'failed',
					'form_id' => $form_id 
			);
		}
		
		echo json_encode ( $resp );
		
		die ( 0 );
	}
	
	/*
	 * saving admin setting in wp option data table
	 */
	function save_settings() {
		
		 
		$senatized_settings['nm_filemanager_thumb_size'] = sanitize_text_field($_REQUEST['nm_filemanager_thumb_size']);
		$senatized_settings['nm_filemanager_button_title'] = sanitize_text_field($_REQUEST['nm_filemanager_button_title']);
		$senatized_settings['nm_filemanager_button_bg_color'] = sanitize_text_field($_REQUEST['nm_filemanager_button_bg_color']);
		$senatized_settings['nm_filemanager_button_txt_color'] = sanitize_text_field($_REQUEST['nm_filemanager_button_txt_color']);
		$senatized_settings['nm_filemanager_uploader_bg_color'] = sanitize_text_field($_REQUEST['nm_filemanager_uploader_bg_color']);
		$senatized_settings['nm_filemanager_max_file_size'] = sanitize_text_field($_REQUEST['nm_filemanager_max_file_size']);
		$senatized_settings['nm_filemanager_max_files'] = sanitize_text_field($_REQUEST['nm_filemanager_max_files']);
		$senatized_settings['nm_filemanager_file_types'] = sanitize_text_field($_REQUEST['nm_filemanager_file_types']);
		$senatized_settings['nm_filemanager_file_saved'] = sanitize_text_field($_REQUEST['nm_filemanager_file_saved']);
		$senatized_settings['nm_filemanager_error_msg'] = sanitize_text_field($_REQUEST['nm_filemanager_error_msg']);
		
		update_option ( $this->plugin_meta ['shortname'] . '_settings', $senatized_settings );
		_e ( 'All options are updated', 'nm-filemanager' );
		die ( 0 );
	}
	
	/*
	 * rendering template against shortcode
	 */
	function render_shortcode_template($atts) {
		$allow_public = $this -> get_option('_allow_public');
		if ( is_user_logged_in() || $allow_public[0] == 'yes' ) {
			
			wp_enqueue_script( 'filemanager-script', $this->plugin_meta['url'].'/js/script.js');
			
			$filemanager_filesize = $this -> get_option ( '_max_file_size' );
			$filemanager_filesize 		= (!$filemanager_filesize == '') ? $filemanager_filesize : '5mb';
			
			$nmfilemanager_filetypes     = $this -> get_option ( '_file_types' );
			$nmfilemanager_filetypes		= (!$nmfilemanager_filetypes == '') ? $nmfilemanager_filetypes : 'jpg,png,gif,zip,pdf';
			
			$nmfilemanaer_filesallowed  = $this -> get_option ( '_max_files' );
			 $nmfilemanaer_filesallowed	= (!$nmfilemanaer_filesallowed == '') ? $nmfilemanaer_filesallowed : 5;
			 if( $nmfilemanaer_filesallowed > 5 ){
				$nmfilemanaer_filesallowed = 5;
			 }
			
			$filemanager_vars = array (
					'ajaxurl' 					=> admin_url ( 'admin-ajax.php?no_cache='.rand() ),
					'plugin_url'				=> $this->plugin_meta ['url'],
					'doing' 					=> $this->plugin_meta ['url'] . '/images/loading.gif',
					'settings' 					=> $this->plugin_settings,
					'file_upload_path_thumb' 	=> $this->get_file_dir_url ( true ),
					'file_upload_path' 			=> $this->get_file_dir_url (),
					'message_max_files_limit'	=> __(' File are allowed to upload', 'nm-filemanager'),
					'delete_file_message'		=> __('Are you sure?', 'nm-filemanager'),
					'share_file_heading'		=> __('Share file', 'nm-filemanager'),
					'file_meta_heading'			=> __('Edit file meta', 'nm-filemanager'),
					'file_meta' 				=> '',
					'plupload_swf'			=> $this->plugin_meta['url'].'/js/plupload-2.1.2/js/Moxie.swf?no_cache='.rand(),
					'plupload_xap'			=> $this->plugin_meta['url'].'/js/plupload-2.1.2/js/Moxie.xap?no_cache='.rand(),
					'filesize'				=> $filemanager_filesize,
					'filetypes'				=> $nmfilemanager_filetypes,
					'runtime'				=> filemanager_get_browser_runtime(),
					'filesallowed'			=> $nmfilemanaer_filesallowed,
					'placeholder'			=> __('File Title', 'nm-filemanager'),
					'description'			=> __('File Description (optional)', 'nm-filemanager'),
			);
			
			wp_localize_script( 'filemanager-script', $this->plugin_meta['shortname'].'_vars', $filemanager_vars);
			
			wp_enqueue_style( 'filemanager-css', $this->plugin_meta['url'].'/css/_template_main_style.css');
			
			wp_enqueue_style( 'data-table-css', $this->plugin_meta['url'].'/js/dataTables-1.10.10/css/jquery.dataTables.css');
			wp_enqueue_script( 'data-table-js', $this->plugin_meta['url'].'/js/dataTables-1.10.10/js/jquery.dataTables.js', array('jquery'));
				
			// PlUpload
			wp_enqueue_script('plupload', array('plupload', 'plupload-all'));
			
			extract ( shortcode_atts ( array (
			'form_id' => ''
					), $atts ) );
					
			$this -> is_uploader = false;
			$this->form_id = $form_id;
			
			ob_start ();
			
				$this->load_template ( '_template_main.php' );
			
			$output_string = ob_get_contents ();
			ob_end_clean ();
			
			return $output_string;
			
		}else{
			
			echo '<script type="text/javascript">
			window.location = "'.wp_login_url( get_permalink() ).'"
			</script>';
		}
		
		
	}
	
	/*
	 * sending data to admin/others
	 */
	function save_file_data() {
		
		global $wpdb;
		$current_user = get_userdata( get_current_user_id() );
		$allow_public = $this -> get_option('_allow_public');
		if ($current_user -> ID == 0 && $allow_public[0] == 'yes')
			$current_user = get_userdata($this -> get_option('_public_user'));
		//print_r($_REQUEST); exit;
		extract( $_REQUEST );
		
		if (empty ( $_POST ) || ! wp_verify_nonce ( $_POST ['nm_filemanager_nonce'], 'saving_file' )) {
			print 'Sorry, You are not HUMANE.';
			exit ();
		}
		
		//it's a flag property to restrict thumbs generation only for uploader not for all other media upload in wp
		$this -> is_uploader = true;
		
		$submitted_data = $_REQUEST;
		
		unset ( $submitted_data ['action'] );
		unset ( $submitted_data ['nm_filemanager_nonce'] );
		unset ( $submitted_data ['_wp_http_referer'] );
	
		//merging all file title and description in each array
		$all_files_with_data = array();
		foreach($uploaded_files as $key => $file){
			
			$all_files_with_data[$key] = array('filename'	=> $file,
												'title'		=> $file_title[$key],
												'description'	=> $file_description[$key],
												);
		}
				
		foreach( $all_files_with_data as $key => $file_data){
			
			$allowed_html = array (
				'a' => array (
						'href' => array (),
						'title' => array () 
				),
				'br' => array (),
				'em' => array (),
				'strong' => array (),
				'p' => array (),
				'ul' => array (),
				'li' => array (),
				'h3' => array () 
			);
		
			$title = sanitize_text_field ( $file_data['title'] );
		
			// creating post
			$file_post = array (
					'post_title' => $title,
					'post_content' => wp_kses ( $file_data['description'], $allowed_html ),
					'post_status' => 'publish',
					'post_type' => 'nm-userfiles',
					'post_author' => $current_user -> ID,
					'comment_status' => 'closed',
					'ping_status' => 'closed' 
			);
			
			// saving the post into the database
			$the_post_id = wp_insert_post ( $file_post );
			
			//now attaching the file with post
			$image_base_url = $this -> get_file_dir_url();
			//echo 'uploaded file '.$this -> uploadedFileName;
			$post_attachment = $image_base_url . $file_data['filename'];
			$post_attachment_path = $this -> get_file_dir_path() . $file_data['filename'];
			$wp_filetype = wp_check_filetype(basename( $post_attachment ), null );
			
			$attachment = array(
					'guid' => $post_attachment,
					'post_mime_type' => $wp_filetype['type'],
					'post_title' => basename($post_attachment),
					'post_content' => '',
					'post_status' => 'inherit'
			);
	
			$attach_id = wp_insert_attachment($attachment, $post_attachment, $the_post_id);
			
			wp_update_attachment_metadata( $attach_id, wp_generate_attachment_metadata($attach_id, $post_attachment_path ));
			
			$resp ['status'] = 'success';
			$resp ['message'] = $this -> get_option ( '_file_saved' );
		}
		$mail_to_admin = $this -> get_option ( '_admin_email' );
		$admin_email = get_option('admin_email');
		//$mail_sent_by = ($current_user->user_login == '') ? 'Public_Users' : $current_user->user_login;
		$msg = 'Files uploaded '.$file_data['filename'] . ' by ' . $current_user -> user_login;
		if ($the_post_id && $mail_to_admin[0] == 'yes' ){
		  if (wp_mail ( $admin_email, __('New File Uploaded', 'nm-filemanager'), $msg )) {
					  
			  $resp ['status'] = 'success';
			  $resp ['message'] = __( 'Mail sent successfully!', 'nm-filemanager' );
	  
	  
		  } else {
	  
			  $resp ['status'] = 'error';
			  $resp ['message'] = __ ( 'Error: while seding Email', 'nm-filemanager' );
		  }
		}
		echo json_encode ( $resp );
		
		die ( 0 );
	}

	/**
	 * migration function to support old version
	 */
	function migrate_files (){
		/**
		* 1. check if table exists.
		* 2. if yes, fetch userID, fileTitle, fileName, fileMeta, fileUploadedOn
		* 3. check file meta. if array then set meta options.
		* 4. call function migrate_script(); and update/add new posts
		* 5. set option _nm_fileuploader_migrated
		*/
		global $wpdb;
		$table_name = $wpdb -> prefix . "userfiles";
		if ( $wpdb -> get_var("SHOW TABLES LIKE '$table_name'" ) == $table_name && get_option('_nm_fileuploader_migrated') != 'migrated' ) {
		
			$old_files = $wpdb -> get_results( 
				"
				SELECT userID, fileTitle, fileName, fileMeta, fileUploadedOn, fileDescription 
				FROM $table_name
				", ARRAY_A
			);
		
			foreach ($old_files as $val){
				$file_title = ($val['fileTitle'] == '') ? $val['fileName'] : $val['fileTitle'];
				$file_desc   = ($val['fileDescription'] == '') ? 'This file was uploaded on '.$val['fileUploadedOn'] : $val['fileDescription'];
				$this -> migration_script($val['userID'], $file_title, $val['fileName'], json_decode($val['fileMeta'], true), $file_desc);
				
			}
			update_option( '_nm_fileuploader_migrated', 'migrated' );
		}
	}	
	
	/**
	 * migration script to support old version
	 * should be called on plugin activate event
	 */
	function migration_script($auth_id, $post_title = NULL, $file_names, $file_meta = NULL, $file_description) {
	
		global $wpdb;
		// now attaching the file with post
		$image_base_url = $this -> get_file_dir_url ();
	
		// merging all file title and description in each array
		$all_files_with_data = explode(",", $file_names);
	
		foreach ( $all_files_with_data as $file ) {
				
			$file_path = $this->get_file_dir_path () . $file;
				
			if( file_exists($file_path) ){
				$all_files_with_data1 [$file] = array (
						'filename' => $file,
						'title' => $post_title,
						'description' => $file_description,
				);
			}
				
		}
	//filemanager_pa($all_files_with_data1);
		foreach ( $all_files_with_data1 as $key => $file_data ) {
				
			$allowed_html = array (
					'a' => array (
							'href' => array (),
							'title' => array ()
					),
					'br' => array (),
					'em' => array (),
					'strong' => array (),
					'p' => array (),
					'ul' => array (),
					'li' => array (),
					'h3' => array ()
			);
				//filemanager_pa($file_data);
			$title = sanitize_text_field ( $file_data ['title'] );
			// creating post
			$file_post = array (
					'post_title' => $title,
					'post_content' => wp_kses ( $file_data ['description'], $allowed_html ),
					'post_status' => 'publish',
					'post_type' => 'nm-userfiles',
					'post_author' => $auth_id,
					'comment_status' => 'closed',
					'ping_status' => 'closed'
			);
				
			// saving the post into the database
			$the_post_id = wp_insert_post ( $file_post );
				
			//saving file meta if exists
			if( $file_meta ){
				$admin_meta = array();
				foreach ($file_meta as $key => $val){

					$the_key = preg_replace ( '/[^\w\._]+/', '_', $key );
					update_post_meta($the_post_id, $the_key, $val);
					
					$admin_meta[] = array('type' => 'text',
										  'title' => $key,
										  'data_name' => $the_key,
										  'description' => 'any tags',
										  'error_message' => '',
										  'required' => '',
										  'class' => '',
										  'width' => '100%'
										  );
				}
				//filemanager_pa($admin_meta);
				//echo json_encode($admin_meta);
				update_option('filemanager_meta', $admin_meta);
	
			}
				
				
			// echo 'uploaded file '.$this -> uploadedFileName;
			$post_attachment = $image_base_url . $file_data ['filename'];
			$post_attachment_path = $this->get_file_dir_path () . $file_data ['filename'];
			$wp_filetype = wp_check_filetype ( basename ( $post_attachment ), null );
				
			$attachment = array (
					'guid' => $post_attachment,
					'post_mime_type' => $wp_filetype ['type'],
					'post_title' => basename ( $post_attachment ),
					'post_content' => '',
					'post_status' => 'inherit'
			);
				
			$attach_id = wp_insert_attachment ( $attachment, $post_attachment, $the_post_id );
			//	panga	
			if($this -> is_image(basename ( $post_attachment ))){
				require_once ( ABSPATH . 'wp-admin/includes/image.php' );
				wp_update_attachment_metadata ( $attach_id, wp_generate_attachment_metadata ( $attach_id, $post_attachment_path ) );
			}
			$resp ['status'] = 'success';
			$resp ['message'] = $this->get_option ( '_file_saved' );
		}
	
	
	}
		
	/*
	 * rendering email template
	 */
	function render_email_template() {
		ob_start ();
		$this->load_template ( '/render.email.php' );
		return ob_get_clean ();
	}
	
	/*
	 * uploading file here
	 */
	function upload_file() {
	
	
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: no-store, no-cache, must-revalidate" );
		header ( "Cache-Control: post-check=0, pre-check=0", false );
		header ( "Pragma: no-cache" );
	
		// setting up some variables
		$file_dir_path = $this->setup_file_directory ();
		$response = array ();
		if ($file_dir_path == 'errDirectory') {
				
			$response ['status'] = 'error';
			$response ['message'] = __ ( 'Error while creating directory', 'nm-postfront' );
			die ( 0 );
		}
	
		$cleanupTargetDir = true; // Remove old files
		$maxFileAge = 5 * 3600; // Temp file age in seconds
	
		// 5 minutes execution time
		@set_time_limit ( 5 * 60 );
	
		$file_name = '';
		
		if( isset($_REQUEST['name']) && $_REQUEST['name'] != '') {
			$file_name = sanitize_file_name( $_REQUEST['name'] );
		}elseif( isset($_REQUEST['_file']) && $_REQUEST['_file'] != '') {
			$file_name = sanitize_file_name( $_REQUEST['_file'] );
		}
		
		
		// Clean the fileName for security reasons
		$file_name = preg_replace ( '/[^\w\._]+/', '_', $file_name );
		$file_name = strtolower($file_name);
		
		$file_name = apply_filters('filemanager_filename', $file_name);
		
		/* ========== Invalid File type checking ========== */
		$file_type = wp_check_filetype_and_ext($file_dir_path, $file_name);
		$extension = $file_type['ext'];
		
		// for some files if above function fails to check extension we need to check otherway
		if( ! $extension ) {
			$extension = pathinfo($file_name, PATHINFO_EXTENSION);
		}
		
		$allowed_types = $this -> get_option('_file_types');
		
		if( ! $allowed_types ) {
			$good_types = apply_filters('nm_allowed_file_types', array('jpg', 'png', 'gif', 'zip','pdf') );
		}else {
			$good_types = explode(",", $allowed_types );
		}
		
	
		if( ! in_array($extension, $good_types ) ){
			$response ['status'] = 'error';
			$response ['message'] = __ ( 'File type not valid', 'nm-filemanager' );
			die ( json_encode($response) );
		}
		/* ========== Invalid File type checking ========== */
	
		// Get parameters
		$chunk = isset ( $_REQUEST ["chunk"] ) ? intval ( $_REQUEST ["chunk"] ) : 0;
		$chunks = isset ( $_REQUEST ["chunks"] ) ? intval ( $_REQUEST ["chunks"] ) : 0;
	
		// Make sure the fileName is unique but only if chunking is disabled
		if ($chunks < 2 && file_exists ( $file_dir_path . $file_name )) {
			$ext = strrpos ( $file_name, '.' );
			$file_name_a = substr ( $file_name, 0, $ext );
			$file_name_b = substr ( $file_name, $ext );
				
			$count = 1;
			while ( file_exists ( $file_dir_path . $file_name_a . '_' . $count . $file_name_b ) )
				$count ++;
				
			$file_name = $file_name_a . '_' . $count . $file_name_b;
		}
	
		// Remove old temp files
		if ($cleanupTargetDir && is_dir ( $file_dir_path ) && ($dir = opendir ( $file_dir_path ))) {
			while ( ($file = readdir ( $dir )) !== false ) {
				$tmpfilePath = $file_dir_path . $file;
	
				// Remove temp file if it is older than the max age and is not the current file
				if (preg_match ( '/\.part$/', $file ) && (filemtime ( $tmpfilePath ) < time () - $maxFileAge) && ($tmpfilePath != "{$file_path}.part")) {
					@unlink ( $tmpfilePath );
				}
			}
				
			closedir ( $dir );
		} else
			die ( '{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}' );
	
		$file_path = $file_dir_path . $file_name;
	
		// Look for the content type header
		if (isset ( $_SERVER ["HTTP_CONTENT_TYPE"] ))
			$contentType = $_SERVER ["HTTP_CONTENT_TYPE"];
	
		if (isset ( $_SERVER ["CONTENT_TYPE"] ))
			$contentType = $_SERVER ["CONTENT_TYPE"];
			
		// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
		if (strpos ( $contentType, "multipart" ) !== false) {
			if (isset ( $_FILES ['file'] ['tmp_name'] ) && is_uploaded_file ( $_FILES ['file'] ['tmp_name'] )) {
				// Open temp file
				$out = fopen ( "{$file_path}.part", $chunk == 0 ? "wb" : "ab" );
				if ($out) {
					// Read binary input stream and append it to temp file
					$in = fopen ( $_FILES ['file'] ['tmp_name'], "rb" );
						
					if ($in) {
						while ( $buff = fread ( $in, 4096 ) )
							fwrite ( $out, $buff );
					} else
						die ( '{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}' );
					fclose ( $in );
					fclose ( $out );
					@unlink ( $_FILES ['file'] ['tmp_name'] );
				} else
					die ( '{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}' );
			} else
				die ( '{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}' );
		} else {
			// Open temp file
			$out = fopen ( "{$file_path}.part", $chunk == 0 ? "wb" : "ab" );
			if ($out) {
				// Read binary input stream and append it to temp file
				$in = fopen ( "php://input", "rb" );
	
				if ($in) {
					while ( $buff = fread ( $in, 4096 ) )
						fwrite ( $out, $buff );
				} else
					die ( '{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}' );
	
				fclose ( $in );
				fclose ( $out );
			} else
				die ( '{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}' );
		}
	
		// Check if file has been uploaded
		if (! $chunks || $chunk == $chunks - 1) {
			// Strip the temp .part suffix off
			rename ( "{$file_path}.part", $file_path );
				
			// making thumb if images
			if($this -> is_image($file_name))
			{
				$h = $this -> get_option('_thumb_size', 150);
				$w = $this -> get_option('_thumb_size', 150);				
				$thumb_size = array(array('h' => $h, 'w' => $w, 'crop' => true),
				);
				$thumb_meta = $this -> create_thumb($file_dir_path, $file_name, $thumb_size);
	
				$response = array(
						'file_name'			=> $file_name,
						'thumb_meta'			=> $thumb_meta);
			}else{
				$response = array(
						'file_name'			=> $file_name,
						'file_w'			=> 'na',
						'file_h'			=> 'na');
			}
		}
			
		// Return JSON-RPC response
		//die ( '{"jsonrpc" : "2.0", "result" : '. json_encode($response) .', "id" : "id"}' );
		die ( json_encode($response) );
	
	
	}

	/**
	 * follwing function is deleting file physically once it is uploaded
	 * but not saved as post
	 */
	function delete_file_new() {
		$dir_path = $this -> setup_file_directory();
		
		$file_name = sanitize_file_name( $_REQUEST['name'] );
		$file_path = $dir_path . $file_name;

		if (file_exists($file_path)) {
			if (unlink($file_path)) {

				$file_path_thumb = $dir_path . 'thumbs/' . $file_name;
				if (file_exists($file_path_thumb)) {
					if (unlink($file_path_thumb)) {
						_e('File removed', 'nm-filemanager');
					}
				}
			}

		} else {
			printf(__('Error while deleting file %s', 'nm-filemanager'), $file_path);
		}

		die(0);
	}
	
	/*
	 * deleting uploaded file from directory
	 */
	function delete_file() {

		$file_id = intval($_REQUEST['pid']);
		$file_title = get_the_title($file_id);
		$args = array(
			'post_type' => 'attachment',
			'numberposts' => null,
			'post_status' => null,
			'post_parent' => $file_id
		);
	  	
	  	$attachments = get_posts($args);
	  
	  	if ($attachments) {
			foreach($attachments as $attachment){
				$file_name = $this -> get_attachment_file_name( $file_id );
				$dir_path = $this -> setup_file_directory();
				
				$file_path = $dir_path . $file_name;
	
				if(get_post_meta($file_id, 'file_location', true) == 'amazon')
					$this -> delete_file_amazon($file_name);
				
				if (file_exists($file_path)) {
					if (unlink($file_path)) {
						$file_path_thumb = $dir_path . 'thumbs/' . $file_name;
						if (file_exists($file_path_thumb)) {
							unlink($file_path_thumb);
						}
					}
				}
				
				/********* deleting all thumbs of the image *********/
				$sizes = array_merge(array('full'),get_intermediate_image_sizes());
				foreach ($sizes as $imageSize) {
					$image_object = wp_get_attachment_image_src($attachment->ID, $imageSize );
					$image_thumb_path = $dir_path.basename($image_object[0]);
					if (file_exists($file_path_thumb) && !is_dir($file_path_thumb)) {
						unlink($image_thumb_path);
					}
				}
				/***************************************************/

				wp_delete_attachment($attachment->ID, true);
			}
		}

	  	if( wp_delete_post( $file_id ) ){
			  echo '<div class="nm-prefix">';
			  	echo '<div class="alert alert-info">';
			  		echo sprintf(__('%s is successfully Removed', 'nm-filemanager'), $file_title);
			  	echo '</div>';
			  echo '</div>';
			  
			  /*$sendback = remove_query_arg( array('pid', 'do'), wp_get_referer() );
			  wp_redirect( $sendback );
   			  exit;*/
		}

	}
	
	/*
	 * saving contact form as CPT: nm-userfiles
	 */
	function save_contact_form($subject, $message, $attachments, $submitted_data) {
		$allowed_html = array (
				'a' => array (
						'href' => array (),
						'title' => array () 
				),
				'br' => array (),
				'em' => array (),
				'strong' => array (),
				'p' => array (),
				'ul' => array (),
				'li' => array (),
				'h3' => array () 
		);
		
		$title = date ( 'D,m-Y' ) . '-' . sanitize_text_field ( $subject );
		//$file_name = array_values($attachments);
		
		//$file_name = preg_replace ( '/[^\w\._]+/', '_', $file_name );
		$posttitle = ($attachments == '') ? $title : $attachments;
		// creating post
		$contact_form = array (
				'post_title' => $posttitle,
				'post_content' => wp_kses ( $message, $allowed_html ),
				'post_status' => 'private',
				'post_type' => 'nm-userfiles',
				'post_author' => '',
				'comment_status' => 'closed',
				'ping_status' => 'closed' 
		);
		
		// saving the post into the database
		$formid = wp_insert_post ( $contact_form );
		
		// now adding submitted data as form/post meta
		foreach ( $submitted_data as $key => $val ) {
			update_post_meta ( $formid, $key, $val );
		}
		
		// files uploaded
		//update_post_meta ( $formid, '_file_meta', json_encode ( $attachments ) );
		$file_dir_path = $this->get_file_dir_path();
		update_post_meta ($formid, '_file_path', $file_dir_path);
	}
	
	/*
	 * this function is saving photo returned by Aviary
	 */
	function save_edited_photo() {
		$file_path = $this->plugin_meta ['path'] . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'aviary.php';
		if (! file_exists ( $file_path )) {
			die ( 'Could not find file ' . $file_path );
		}
		
		include_once $file_path;
		
		$aviary = new NM_Aviary ();
		
		$aviary->plugin_meta = filemanager_get_plugin_meta();
		$aviary->dir_path = $this->get_file_dir_path ();
		$aviary->dir_name = $this->user_uploads;
		$aviary->posted_data = json_decode ( stripslashes ( $_REQUEST ['postdata'] ) );
		$aviary->image_data = file_get_contents ( $_REQUEST ['url'] );
		
		$aviary->save_file_locally ();
		die ( 0 );
	}
	

	// ================================ SOME HELPER FUNCTIONS =========================================
	
	/*
	 * getting meta based on id
	 */
	function get_forms($form_id = '') {
		$select = array (
				self::$tbl_file_meta => '*' 
		);
		
		if ($form_id) {
			$where = array (
					'd' => array (
							'form_id' => $form_id 
					) 
			);
			
			$res = $this->get_row_data ( $select, $where );
		} else {
			$where = NULL;
			$res = $this->get_rows_data ( $select, $where );
		}
		
		return $res;
	}
	
	/*
	 * simplifying meta for admin view in existing-meta.php
	 */
	function simplify_meta($meta) {
		$metas = json_decode ( $meta );
		
		echo '<ul>';
		if ($metas) {
			foreach ( $metas as $meta => $data ) {
				
				$req = ( isset( $data->required ) == 'on') ? 'yes' : 'no';
				
				echo '<li>';
				echo '<strong>label:</strong> ' . $data->title;
				echo ' | <strong>type:</strong> ' . $data->type;
				
				if ( isset( $data->options ) && ! is_object ( $data->options ))
					echo ' | <strong>options:</strong> ' . $data->options;
				echo ' | <strong>required:</strong> ' . $req;
				echo '</li>';
			}
			
			echo '</ul>';
		}
	}
	
	/*
	 * delete meta
	 */
	function delete_meta() {
		global $wpdb;
		
		extract ( $_REQUEST );
		
		$res = $wpdb->query ( "DELETE FROM `" . $wpdb->prefix . self::$tbl_file_meta . "` WHERE form_id = " . $formid );
		
		if ($res) {
			
			_e ( 'Meta deleted successfully', 'nm-filemanager' );
		} else {
			$wpdb->show_errors ();
			$wpdb->print_error ();
		}
		
		die ( 0 );
	}
	
	/*
	 * setting up user directory
	 */
	function setup_file_directory() {
		$current_user = get_userdata( get_current_user_id() );
		$allow_public = $this -> get_option('_allow_public');
		if ($current_user -> ID == 0 && $allow_public[0] == 'yes')
			$current_user = get_userdata($this -> get_option('_public_user'));
		$upload_dir = wp_upload_dir ();
		
		$file_dir_path = $upload_dir ['basedir'] . '/' . $this->user_uploads . '/' . $current_user -> user_login . '/' ;
		
		if (! is_dir ( $file_dir_path )) {
			if (mkdir ( $file_dir_path, 0775, true ))
				$dirThumbPath = $file_dir_path . 'thumbs/';
			if (mkdir ( $dirThumbPath, 0775, true ))
				return $file_dir_path;
			else
				return 'errDirectory';
		} else {
			$dirThumbPath = $file_dir_path . 'thumbs/';
			if (! is_dir ( $dirThumbPath )) {
				if (mkdir ( $dirThumbPath, 0775, true ))
					return $file_dir_path;
				else
					return 'errDirectory';
			} else {
				return $file_dir_path;
			}
		}
	}
	
	/*
	 * getting file URL
	 */
	function get_file_dir_url($thumbs = false) {
		$current_user = get_userdata( get_current_user_id() );
		$allow_public = $this -> get_option('_allow_public');
		if ($current_user -> ID == 0 && $allow_public[0] == 'yes')
			$current_user = get_userdata($this -> get_option('_public_user'));
		$upload_dir = wp_upload_dir ();
		
		if ($thumbs)
			return $upload_dir ['baseurl'] . '/' . $this->user_uploads . '/' . $current_user -> user_login . '/thumbs/';
		else
			return $upload_dir ['baseurl'] . '/' . $this->user_uploads . '/' . $current_user -> user_login . '/';
	}
	
	function get_file_dir_path($sub_path = false) {
		$current_user = get_userdata( get_current_user_id() );
		$allow_public = $this -> get_option('_allow_public');
		if ($current_user -> ID == 0 && $allow_public[0] == 'yes')
			$current_user = get_userdata($this -> get_option('_public_user'));
		$upload_dir = wp_upload_dir ();
		
		if ($sub_path) {
			return $upload_dir ['basedir'] . '/' . $this->user_uploads . '/' . $current_user -> user_login . '/' . $sub_path;
		}else{
			return $upload_dir ['basedir'] . '/' . $this->user_uploads . '/' . $current_user -> user_login . '/';
		}
		
		
	}
	
	/*
	 * creating thumb using wp_get_image_editor
	 */
	function create_thumb($dest, $image_name, $thumb_size) {
	
		// using wp core image processing editor, 6 May, 2014
		$image = wp_get_image_editor ( $dest . $image_name );
		
		$thumbs_resp = '';
		if( is_array($thumb_size) ){
			
			foreach($thumb_size as $size){
				$thumb_name = $image_name;
				$thumb_dest = $dest . 'thumbs/' . $thumb_name;
				if (! is_wp_error ( $image )) {
					$image->resize ( $size['h'], $size['w'], $size['crop'] );
					$image->save ( $thumb_dest );
					$thumbs_resp[$thumb_name] = array('name' => $thumb_name, 'thumb_size' => getimagesize($thumb_dest) );
				}
			}
		}
		return $thumbs_resp;
	}
	
	
	function activate_plugin() {
		global $wpdb;
		$filemanager_db_version = "2.0.1";
		
		/*
		 * meta_for: this is to make this table to contact more then one metas for NM plugins in future in this plugin it will be populated with: forms
		 */
		/*$forms_table_name = $wpdb->prefix . self::$tbl_file_meta;
		
		$sql = "CREATE TABLE $forms_table_name (
		form_id INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		form_name VARCHAR(50) NOT NULL,
		sender_email VARCHAR(50),
		sender_name VARCHAR(50),
		subject VARCHAR(50),
		receiver_emails VARCHAR(250),
		button_label VARCHAR(50),
		button_class VARCHAR(50),
		success_message VARCHAR(50),
		error_message VARCHAR(50),
		thumb_size int(3),
		form_style MEDIUMTEXT,
		the_meta MEDIUMTEXT NOT NULL,
		form_created DATETIME NOT NULL
		);";
		
		require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta ( $sql );
		*/
		update_option ( "filemanager_db_version", $filemanager_db_version );
	}
	function deactivate_plugin() {
		
		// do nothing so far.
	}
	
	/*
	 * checking if aviary addon is installed or not
	 */
	function is_aviary_installed() {
		$aviary_file = $this->plugin_meta ['path'] . '/lib/aviary.php';
		
		if (file_exists ( $aviary_file ))
			return true;
		else
			return false;
	}
	

	
	
	/*
	 * check if file is image and return true
	 */
	function is_image($file){
		
		$type = strtolower ( substr ( strrchr ( $file, '.' ), 1 ) );
		
		if (($type == "gif") || ($type == "jpeg") || ($type == "png") || ($type == "pjpeg") || ($type == "jpg"))
			return true;
		else 
			return false;
	}
	
	
	/*
	 * download file
	 */
	function download_private_file(){

		if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'download' && !$_REQUEST['file_name'] == '') {
		
			$current_user = get_userdata( get_current_user_id() );
			$allow_public = $this -> get_option('_allow_public');
			if ($current_user -> ID == 0 && $allow_public[0] == 'yes')
				$current_user = get_userdata($this -> get_option('_public_user'));
			//$user_dir = $current_user -> user_login . '/';
			$file_dir_path = $this->get_file_dir_path() . $_REQUEST['file_name'];
			
			if (file_exists($file_dir_path)) {
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename='.basename($file_dir_path));
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-Length: ' . filesize($file_dir_path));
				ob_clean();
				flush();
				readfile($file_dir_path);
				exit;
			}else{
			
				die( printf(__('no file found at %s', 'nm-filemanager'), $file_dir_path) );
			}
			
		}
		
		
	}
	/*
	 ** to fix url re-occuring, written by Naseer sb
	*/

	function fixRequestURI($vars){
		$uri = str_replace( '%7E', '~', $_SERVER['REQUEST_URI']);
		$parts = explode("?", $uri);

		$qsArr = array();
		if(isset($parts[1])){	////// query string present explode it
			$qsStr = explode("&", $parts[1]);
			foreach($qsStr as $qv){
				$p = explode("=",$qv);
				$qsArr[$p[0]] = $p[1];
			}
		}

		//////// updatig query string
		foreach($vars as $key=>$val){
			if($val==NULL) unset($qsArr[$key]); else $qsArr[$key]=$val;
		}

		////// rejoin query string
		$qsStr="";
		foreach($qsArr as $key=>$val){
			$qsStr.=$key."=".$val."&";
		}
		if($qsStr!="") $qsStr=substr($qsStr,0,strlen($qsStr)-1);
		$uri = $parts[0];
		if($qsStr!="") $uri.="?".$qsStr;
		return $uri;
		//echo($uri);
	}	
	
	/*
	 * getting the data-name of the file uploader.
	 */
	function get_form_meta ($formid) {
		$single_form = $this -> get_forms( $formid );
		$file_meta = json_decode( $single_form -> the_meta, true);
		foreach ( $file_meta as $key => $val ) {
			if ( $val['type'] == 'file' ) {
				return $val['data_name'];
			}
		}

	}
	
	/*
	 * sending email.
	 */
	function send_files_email() {
	      $current_user = get_userdata( get_current_user_id() );
		  $allow_public = $this -> get_option('_allow_public');
		  if ($current_user -> ID == 0 && $allow_public[0] == 'yes')
			$current_user = get_userdata($this -> get_option('_public_user'));
		$receiver_emails = $_REQUEST['email_to'];
		$send_files = $_REQUEST['file_names'];
		$subject1 = $_REQUEST['subject'];
		$message.= "<p>" . __ ( 'Following message is being sent by ', 'nm-filemanager' ) . $current_user->user_login . "</p>";
		$message.= "<p>" . $_REQUEST['file_msg'] . "</p>";
		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
				
		$receiver_emails = explode ( ',', $receiver_emails );
		$send_files = explode ( ',', $send_files );

		foreach ( $send_files as $single_file ) {
			$params_download = array('do' 		 => 'download',
									 'file_name' => $single_file);
			$urlDownload = $this->fixRequestURI($params_download);
			
			$message.= '<p> <a href="' .get_site_url().$urlDownload. '">'.$single_file.'</a> </p>';
		}
		
		$resp = '';
		foreach ( $receiver_emails as $to ) {
					
			$to = trim ( $to );
			if (wp_mail ( $to, $subject1, $message, $headers )) {
					
				echo "Mail Sent...";
		
			} else {
			
				echo 'Error sending mail...';
			}
		}
	}
	
	/*
	 * deleting all files by author.
	 */	
	function delete_all_posts(){
		if ( !is_user_logged_in() ) 
			return;
		$current_user = wp_get_current_user();
		$userName = $current_user->user_login;
		$upPath = wp_upload_dir();
		$dirPath = $upPath['basedir']."/user_uploads/".$userName;

		if( is_dir( $dirPath ) ){
			$this -> delete_all_directores_of_user($dirPath);
		}		
		
		//get_current_user_id();
		//$uploader_name = $this->get_form_meta($this -> form_id);
		
		$args = array(
		'post_type'   => 'nm-userfiles',
		'post_status' => 'private',
		'author'      => get_current_user_id(),
		'nopaging'    => true	
		);
		
		$the_query = new WP_Query( $args ); 

  		while ( $the_query->have_posts() ) : $the_query->the_post();
			wp_delete_post( get_the_ID() );
    		/*if ( wp_delete_post( get_the_ID() ) ) {
				if ($meta = get_post_meta( get_the_ID(), $uploader_name, true) ) {
					$meta_val = array_values($meta);
					$this->delete_file($meta_val[0]);
				}
			}*/
  		endwhile; 
		echo '<script type="text/javascript">
			 window.location = "'. get_permalink($post->ID) .'"
			 </script>';
	}
	
	/*
	 ** Deleting all files of a user
	*/
	function delete_all_directores_of_user($dirPath){

		if (! is_dir($dirPath)) {
			throw new InvalidArgumentException("$dirPath must be a directory");
		}
		if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
			$dirPath .= '/';
		}
		$files = glob($dirPath . '*', GLOB_MARK);
		foreach ($files as $file) {
			if (is_dir($file)) {
				self::delete_all_directores_of_user($file);
			} else {
				unlink($file);
			}
		}
		rmdir($dirPath);
	}
	
	
	/**
	 * file information rendering functions
	 */
	 function set_file_download( $file_post_id ){
	 	
		
	 	

			$args = array(
			'post_type' => 'attachment',
			'numberposts' => null,
			'post_status' => null,
			'post_parent' => $file_post_id,
			);
			
			$attachments = get_posts($args);
			
			if ($attachments) {
				foreach($attachments as $attachment){
				$file_path = get_post_meta($attachment->ID, '_wp_attached_file');
				$file_type = wp_check_filetype(basename( $file_path[0] ), null );
				
				//the link and name
				//echo '<a href="'.wp_get_attachment_url( $attachment -> ID).'">'.$attachment -> post_title.'</a><br />';
				if( $file_type['type'] == 'image/png' || $file_type['type'] == 'image/gif' || $file_type['type'] == 'image/jpg' || $file_type['type'] == 'image/jpeg'){
					$file_meta	 = wp_get_attachment_metadata($attachment -> ID);
					echo '<a href="'.wp_get_attachment_url( $attachment -> ID).'">';
					echo '<img width="75" src="'.wp_get_attachment_thumb_url( $attachment -> ID).'" />';
					echo '</a>';
				}else{
					
					echo '<img alt="'.__('Download file', 'nm-filemanager').'" src="'.$this -> plugin_meta['url'].'/images/file.png" />';
				}
				
				echo '<a href="'.wp_get_attachment_url( $attachment -> ID).'">';
				echo '<div class="nm-download-file"></div>';
				echo '</a>';
				
			}
			}
	 }
	
	
	/**
	 * Few funcitons related to template files
	 * added by Najeeb Ahmad
	 * 15 June, 2014
	 */
	
	function render_file_title_description($file_object){
		$args = array(
			'orderby'          => 'post_title',
			'order'            => $post_order,
			'post_type'        => 'nm-userfiles',
			'post_status'      => 'publish',
			'paged'			   => $paged,
			'author'           => get_current_user_id(),
			'meta_query' 	   => array( 
										array('key' => 'uploaded_file_names',
											  'value' => $search_str,
											  'compare' => 'LIKE')
										)
			);
		
		$my_query = new WP_Query();
		$query_posts = $my_query->query($args);
		
		$my_query1 = new WP_Query($args);

		while ( $my_query->have_posts() ) : 
		  		$my_query->the_post();
			
			echo '<span class="rendering-file-title">'. the_title() .count($my_query).'</span>';
			echo '<em>File uploaded 2 hours ago by usename</em>';
			
		endwhile;
	}
	
	function render_file_tools($file_object){
		
		echo '<a href="">Share</a>';
		echo '<a href="">Edit</a>';
		echo '<a href="">Delete</a>';
	}
	
	
	/**
	 * adding columns to nm-files post type listing
	 */
	 
	 // ADD NEW COLUMN
	function file_thumb_column_head($defaults) {
		
		unset( $defaults['date'] );
	    //$defaults['file_title'] = __('Title', 'nm-filemanager');
	    $defaults['file_author'] = __('File owner', 'nm-filemanager');
		$defaults['file_download'] = __('Download file', 'nm-filemanager');
		$defaults['date'] = __('Date', 'nm-filemanager');
		
	    return $defaults;
	}
	 
	// SHOW THE FEATURED IMAGE
	function file_thumb_column_content($column_name, $post_id) {
		
		$file_post = get_post( $post_id );
		
		switch( $column_name ){
			
/*			case 'file_title':
				echo apply_filters( 'the_title', $file_post -> post_title );
				echo '<br />';
				echo apply_filters( 'the_excerpt', $file_post -> post_excerpt );
			break;
*/			
			case 'file_author':
				$author_fullname = get_the_author_meta('user_firstname', $file_post -> post_author) . ' ' . get_the_author_meta('user_lastname', $file_post -> post_author);
				if( $author_fullname ){
					echo $author_fullname;
				}else{
					echo get_the_author_meta('user_login', $file_post -> post_author);
				}
			break;
			
			case 'file_download';
				$this -> set_file_download( $post_id );
			break;
			
		}
		
	}
	
	/**
	 * admin some styles to admin
	 * being hooked from action
	 */
	 function admin_styles(){
	 	
		echo '<style>';
			echo '.nm-non-image-file:before{';
			echo 'font-family: "dashicons";';
			echo 'content: "\f105";';
			echo 'font-size: 75px;';
			echo '}';
			echo '.nm-non-image-file{';
				echo 'margin-top: 27px;';
			echo '}';
			
			echo '.nm-download-file:before{';
			echo 'font-family: "dashicons";';
			echo 'content: "\f316";';
			echo 'font-size: 25px;';
			echo '}';
		echo '</style>';
	 }
	 
	 /**
	 * remove the default thumbs sizes when saving
	 * post
	 */
	 function prevent_thumbs_generation($sizes){
	 	
	 	/*if( function_exists('get_intermediate_image_sizes') ) {
	 		$wp_images = get_intermediate_image_sizes();
	 	}*/
	 	
	 	if($this -> is_uploader){
	 		/*unset( $sizes['thumbnail']);	
	 		unset( $sizes['medium']);
        	unset( $sizes['large']);*/
        	unset( $sizes ) ;
	 	}
	 	
	 	//var_dump($sizes); exit;
        return $sizes;
	 }
	 
	/**
	 * time difference function
	 */
	 
	function time_difference($date)
	{
		if(empty($date)) {
			return "No date provided";
		}

		$periods         = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
		$lengths         = array("60","60","24","7","4.35","12","10");

		$now             = current_time('timestamp');
		$unix_date       = strtotime($date);

		// check validity of date
		if(empty($unix_date)) {
			return "Bad date";
		}

		// is it future date or past date
		if($now > $unix_date) {
			$difference     = $now - $unix_date;
			$tense         = "ago";

		} else {
			$difference     = $unix_date - $now;
			$tense         = "from now";
		}

		for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
			$difference /= $lengths[$j];
		}

		$difference = round($difference);

		if($difference != 1) {
			$periods[$j].= "s";
		}

		return "$difference $periods[$j] {$tense}";
	}
	

	/**
	 * file pre download functions
	 */
	 function get_attachment_file_name( $file_post_id ){
	 	
			$args = array(
			'post_type' => 'attachment',
			'numberposts' => null,
			'post_status' => null,
			'post_parent' => $file_post_id,
			);
			
			$attachments = get_posts($args);
			
			if ($attachments) {
				foreach($attachments as $attachment){
				$file_path = get_post_meta($attachment->ID, '_wp_attached_file');
				$file_type = wp_check_filetype(basename( $file_path[0] ), null );
				$filename = basename ( get_attached_file( $attachment->ID ) );
				
				return $filename;
				
			}
			}
	 }
}