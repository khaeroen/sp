<?php

class BSKPDFManagerPDF {

	var $_categories_db_tbl_name = '';
	var $_pdfs_db_tbl_name = '';
	var $_pdfs_upload_path = '';
	var $_pdfs_upload_folder = '';
	var $_pdfs_upload_folder_4_ftp = 'bsk-pdf-manager/ftp/';
	
	var $_bsk_pdfs_page_name = '';
	var $_file_upload_message = array();
	
	var $_plugin_pages_name = array();
	var $_open_target_option_name = '';
   
	public function __construct( $args ) {
		global $wpdb;
		
		$this->_categories_db_tbl_name = $args['categories_db_tbl_name'];
		$this->_pdfs_db_tbl_name = $args['pdfs_db_tbl_name'];
		$this->_pdfs_upload_path = $args['pdf_upload_path'];
	    $this->_pdfs_upload_folder = $args['pdf_upload_folder'];
		$this->_plugin_pages_name = $args['pages_name_A'];
		$this->_open_target_option_name = $args['open_target_option_name'];
		
		$this->_bsk_pdfs_page_name = $this->_plugin_pages_name['pdf'];
		
		$this->_pdfs_upload_path = $this->_pdfs_upload_path.$this->_pdfs_upload_folder;
		$this->bsk_pdf_manager_init_message();

		add_action('admin_notices', array($this, 'bsk_pdf_manager_admin_notice') );
		add_action( 'bsk_pdf_manager_pdf_save', array($this, 'bsk_pdf_manager_pdf_save_fun') );
		add_shortcode('bsk-pdf-manager-pdf', array($this, 'bsk_pdf_manager_show_pdf') );
	}
	
	function bsk_pdf_manager_init_message(){
	
		$this->_file_upload_message[1] = array( 'message' => 'The uploaded file exceeds the maximum file size allowed.', 
												'type' => 'ERROR');
		$this->_file_upload_message[2] = array( 'message' => 'The uploaded file exceeds the maximum file size allowed.', 
												'type' => 'ERROR');
		$this->_file_upload_message[3] = array( 'message' => 'The uploaded file was only partially uploaded. Please try again in a few minutes.', 
												'type' => 'ERROR');
		$this->_file_upload_message[4] = array( 'message' => 'No file was uploaded. Please try again in a few minutes.', 
												'type' => 'ERROR');
		$this->_file_upload_message[5] = array( 'message' => 'File size is 0 please check and try again in a few minutes.', 
												'type' => 'ERROR');
		$this->_file_upload_message[6] = array( 'message' => 'Failed, seems there is no temporary folder. Please try again in a few minutes.', 
												'type' => 'ERROR');
		$this->_file_upload_message[7] = array( 'message' => 'Failed to write file to disk. Please try again in a few minutes.', 
												'type' => 'ERROR');
		$this->_file_upload_message[8] = array( 'message' => 'A PHP extension stopped the file upload. Please try again in a few minutes.', 
												'type' => 'ERROR');
		$this->_file_upload_message[15] = array( 'message' => 'Invalid file type, the file you uploaded is not allowed.', 
												 'type' => 'ERROR');
		$this->_file_upload_message[16] = array( 'message' => 'Faild to write file to destination folder.', 
												 'type' => 'ERROR');
		$this->_file_upload_message[17] = array( 'message' => 'Invalid file. Please try again.', 
												 'type' => 'ERROR');
		
		$this->_file_upload_message[20] = array( 'message' => 'Your file uploaded successfully.', 
												 'type' => 'WARNING');
		$this->_file_upload_message[21] = array( 'message' => 'Insert file record into database failed.', 
												 'type' => 'WARNING');												 
												 
	}
	
	function bsk_pdf_manager_admin_notice(){
		$current_page = isset($_REQUEST['page']) ? $_REQUEST['page'] : '';
		if(!$current_page || !in_array($current_page, $this->_plugin_pages_name)){
			return;
		}
		
		$message_id = isset($_REQUEST['message']) ? $_REQUEST['message'] : 0;
		if(!$message_id){
			return;
		}
		if (!isset($this->_file_upload_message[ $message_id ])){
			return;
		}
		
		$type = $this->_file_upload_message[ $message_id ]['type'];
		$msg_to_show = $this->_file_upload_message[ $message_id ]['message'];
		if(!$msg_to_show){
			return;
		}
		//admin message
		if($type == 'WARNING'){
			echo '<div class="updated">';
			echo '<p>'.$msg_to_show.'</p>';
			echo '</div>';
		}else if($type == 'ERROR'){
			echo '<div class="error">';
			echo '<p>'.$msg_to_show.'</p>';
			echo '</div>';
		}
	}

	
	function pdf_edit( $pdf_id = -1 ){
		global $wpdb;
		
		//get all categories
		$sql = 'SELECT * FROM '.$this->_categories_db_tbl_name;
		$categories = $wpdb->get_results( $sql );
		
		$pdf_date = date('Y-m-d', current_time('timestamp'));
		$pdf_obj_array = array();
		if ($pdf_id > 0){
			$sql = 'SELECT * FROM '.$this->_pdfs_db_tbl_name.' WHERE id = %d';
			$sql = $wpdb->prepare( $sql, $pdf_id );
			$pdfs_obj_array = $wpdb->get_results( $sql );
			if (count($pdfs_obj_array) > 0){
				$pdf_obj_array = (array)$pdfs_obj_array[0];
				$pdf_date = date('Y-m-d', strtotime($pdf_obj_array['last_date']));
			}
		}
		$category_id = isset($_REQUEST['cat']) ? $_REQUEST['cat'] : 0;
		if ( isset($pdf_obj_array['cat_id']) ){
			$category_id = $pdf_obj_array['cat_id'];
		}

		?>
        <div class="bsk_pdf_manager_pdf_edit">
        <?php
			$u_bytes = $this->bsk_pdf_manager_pdf_convert_hr_to_bytes( ini_get( 'upload_max_filesize' ) );
			$p_bytes = $this->bsk_pdf_manager_pdf_convert_hr_to_bytes( ini_get( 'post_max_size' ) );
			$maximumUploaded = floor(min($u_bytes, $p_bytes) / 1024).' K bytes.';
			if ($maximumUploaded > 1024){
				$maximumUploaded = floor( $maximumUploaded / 1024).' M bytes.';
			}
			
			
			if( $pdf_obj_array && isset($pdf_obj_array['file_name']) && $pdf_obj_array['file_name'] && file_exists($this->_pdfs_upload_path.$pdf_obj_array['file_name']) ){
				$file_url = get_option('home').'/'.$this->_pdfs_upload_folder.$pdf_obj_array['file_name'];
			}else{
				$file_str = '';
			}
		?>
        <p>
            <table style="width:80%;">
            	<tr>
                    <td style="width:150px;">Category:</td>
                    <td>
                    	<select name="bsk_pdf_manager_pdf_edit_categories" id="bsk_pdf_manager_pdf_edit_categories_id" style="width:350px;">
                        <option value="0">Please select category</option>
                        <?php 
                        foreach($categories as $category){ 
                            if ($category->id == $category_id){
                                echo '<option value="'.$category->id.'" selected="selected">'.$category->cat_title.'</option>';
                            }else{
                                echo '<option value="'.$category->id.'">'.$category->cat_title.'</option>';
                            }
                        } 
                        ?>
                        </select>
                        <span class="bsk-pdf-pro-tip-viewer dashicons dashicons-visibility" attrid="tip_4_bsk_pdf_manager_pdf_edit_categories_id"></span>
                        <input type="hidden" id="tip_4_bsk_pdf_manager_pdf_edit_categories_id" value="A PDF can be assigned to multiple categories with Pro verison." />
                    </td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <?php
				$title_value = '';
				if( $pdf_obj_array && isset($pdf_obj_array['title']) ){
					$title_value = $pdf_obj_array['title'];
				}
				?>
                <tr>
                    <td style="width:250px;">Title:</td>
                    <td>
                    	<input type="text" name="bsk_pdf_manager_pdf_titile" id="bsk_pdf_manager_pdf_titile_id" value="<?php echo $title_value; ?>" maxlength="512" style="width:350px;"/>
                        <span class="bsk-pdf-pro-tip-viewer dashicons dashicons-visibility" attrid="tip_4_bsk_pdf_bsk_pdf_manager_pdf_titile_id"></span>
                        <input type="hidden" id="tip_4_bsk_pdf_bsk_pdf_manager_pdf_titile_id" value="PDF file name with / without extension can be used as title with Pro version." />
                    </td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                    <td style="width:250px;">Use WordPress Media Uploader:</td>
                    <td>
                    	<input type="checkbox" id="bsk_pdf_manager_wp_uplpoader_id" />
                        <span class="bsk-pdf-pro-tip-viewer dashicons dashicons-visibility" attrid="tip_4_bsk_pdf_manager_wp_uplpoader_id"></span>
                        <input type="hidden" id="tip_4_bsk_pdf_manager_wp_uplpoader_id" value="You may link to PDF files that have been upload to WordPress, only available with Pro version." />
                    </td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <?php if ($pdf_id > 0 && $file_url){ ?>
                <tr>
                    <td>Old File:</td>
                    <td>
                        <a href="<?php echo $file_url; ?>" target="_blank"><?php echo $pdf_obj_array['file_name']; ?></a>
                        <input type="hidden" name="bsk_pdf_manager_pdf_file_old" id="bsk_pdf_manager_pdf_file_old_id" value="<?php echo $pdf_obj_array['file_name']; ?>" />
                    </td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <?php } ?>
                <tr>
                    <td>Upload new:</td>
                    <td><input type="file" name="bsk_pdf_file" id="bsk_pdf_file_id" value="Browse" /></td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                	<td>&nbsp;</td>
                    <td><span class="bsk_description">Maximum file size: <?php echo $maximumUploaded; ?></span> To change this please modify your hosting configuration in php.ini or .htaccess file. </td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                	<td>&nbsp;</td>
                    <td><span class="bsk_description">Only <b>.pdf</b> allowed.</span></td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                    <td style="width:250px;">Featured Image:</td>
                    <td>
                    	<a href="javascript:void(0);">Set featured image</a> 
                        <span class="bsk-pdf-pro-tip-viewer dashicons dashicons-visibility" attrid="tip_4_bsk_pdf_manager_set_featured_image_id"></span>
                        <input type="hidden" id="tip_4_bsk_pdf_manager_set_featured_image_id" value="Assign a fetured image to PDF from WordPress' media library, only available for Pro version." />
                    </td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                	<td>Date:</td>
                    <td><input type="text" name="pdf_date" id="pdf_date_id" value="<?php echo $pdf_date ?>" class="bsk-date" /></td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                	<input type="hidden" name="bsk_pdf_manager_action" value="pdf_save" />
                    <input type="hidden" name="bsk_pdf_manager_pdf_id" value="<?php echo $pdf_id; ?>" />
                    <?php echo wp_nonce_field( plugin_basename( __FILE__ ), 'bsk_pdf_manager_pdf_save_oper_nonce', true, false ); ?>
                </tr>
            </table>
          </p>
		</div>
		<?php
	}
	
	function bsk_pdf_manager_pdf_save_fun( $data ){
		global $wpdb;
		//check nonce field
		if ( !wp_verify_nonce( $data['bsk_pdf_manager_pdf_save_oper_nonce'], plugin_basename( __FILE__ ) ) ){
			return;
		}
		if ( !isset($data['bsk_pdf_manager_pdf_edit_categories']) ){
			return;
		}

		$pdf_id = trim($data['bsk_pdf_manager_pdf_id']);
		$pdf_data = array();
		$pdf_data['cat_id'] = $data['bsk_pdf_manager_pdf_edit_categories'];
		$pdf_data['title'] = $data['bsk_pdf_manager_pdf_titile'];
		$pdf_data['last_date'] = trim($data['pdf_date']) ? trim($data['pdf_date']).' 00:00:00' : date('Y-m-d 00:00:00', current_time('timestamp'));
		
		$quotes_sybase = strtolower(ini_get('magic_quotes_sybase'));
		if( get_magic_quotes_gpc() || empty($quotes_sybase) || $quotes_sybase === 'off'){
			foreach($pdf_data as $key => $val){
				$pdf_data[$key] = stripcslashes($val); 
			}
		}
		
		$message_id = 20;
		if ($pdf_id > 0){
			//update
			if (isset($data['bsk_pdf_manager_pdf_file_rmv']) && $data['bsk_pdf_manager_pdf_file_rmv'] == 'true'){
				if ($data['bsk_pdf_manager_pdf_file_old']){
					unlink($this->_pdfs_upload_path.$data['bsk_pdf_manager_pdf_file_old']);
					$pdf_data['file_name'] = '';
				}
			}
			$return_detinate_name = $this->bsk_pdf_manager_pdf_upload_file($_FILES['bsk_pdf_file'], $pdf_id, $message_id, $bsk_pdf_manager_pdf_file_old);
			if ($return_detinate_name){
				$pdf_data['file_name'] = $return_detinate_name;
				//new one uploaded, the old one should be removed
				if ($data['bsk_pdf_manager_pdf_file_old']){
					unlink($this->_pdfs_upload_path.$data['bsk_pdf_manager_pdf_file_old']);
				}
			}
			unset($pdf_data['id']); //for update, dont't chagne id
			$wpdb->update( $this->_pdfs_db_tbl_name, $pdf_data, array('id' => $pdf_id) );
		}else{
			//insert
			$return = $wpdb->insert( $this->_pdfs_db_tbl_name, $pdf_data );
			if ( !$return ){
				$message_id = 21;
				
				$redirect_to = admin_url( 'admin.php?page='.$this->_bsk_pdfs_page_name.'&cat='.$pdf_data['cat_id'].'&message='.$message_id );
				wp_redirect( $redirect_to );
				exit;
			}else{
				$message_id = '';
				
				$new_pdf_id = $wpdb->insert_id;
				$return_detinate_name = $this->bsk_pdf_manager_pdf_upload_file($_FILES['bsk_pdf_file'], $new_pdf_id, $message_id);
				if ( $return_detinate_name ){
					$wpdb->update( $this->_pdfs_db_tbl_name, array('file_name' => $return_detinate_name), array('id' => $new_pdf_id) );
				}else{
					$sql = 'DELETE FROM `'.$this->_pdfs_db_tbl_name.'` WHERE id ='.$new_pdf_id;
					$wpdb->query( $sql );
				}
			}
		}
		
		$redirect_to = admin_url( 'admin.php?page='.$this->_bsk_pdfs_page_name.'&cat='.$pdf_data['cat_id'].'&message='.$message_id  );
		wp_redirect( $redirect_to );
		exit;
	}
	
	function bsk_pdf_manager_pdf_upload_file($file, $destination_name_prefix, &$message_id, $old_file = ''){
		if (!$file["name"]){
			if($old_file){
				$message_id = 17;
			}
			return false;
		}				
		if ( $file["error"] != 0 ){
			$message_id = $file["error"];
			return false;
		}
		$extension = strtolower( end(explode(".", $file["name"])) );
		if( $extension != 'pdf' ){
			$message_id = 15;
			return false;
		}
		//check file type
		$wp_filetype = wp_check_filetype( $file["name"], array( 'pdf' => 'application/pdf' ) );
		if ( !$wp_filetype || !isset($wp_filetype['type'])  || !isset($wp_filetype['ext']) || !$wp_filetype['type'] || !$wp_filetype['ext'] ){
			$message_id = 15;
			return false;
		}

		//check if PDF name is unicode or not
		$upload_pdf_name = $file["name"];
		if( strlen($upload_pdf_name) != strlen(utf8_decode($upload_pdf_name)) ){
			$destinate_file_name = date('Y-m-d', current_time('timestamp')).'_'.$destination_name_prefix.'.pdf';
		}else{
			$upload_pdf_name_new = str_replace('.pdf', '', $upload_pdf_name);
			$destinate_file_name = $upload_pdf_name_new.'_'.$destination_name_prefix.'.pdf';
		}
		//$destinate_file_name = strtoupper( $destinate_file_name );
		$destinate_file_name = str_replace(' ', '_', $destinate_file_name);
		$ret = move_uploaded_file($file["tmp_name"], $this->_pdfs_upload_path.$destinate_file_name);
		if( !$ret ){
			$message_id = 16;
			return false;
		}
		return $destinate_file_name;
	}
	
	function bsk_pdf_manager_pdf_convert_hr_to_bytes( $size ) {
		$size  = strtolower( $size );
		$bytes = (int) $size;
		if ( strpos( $size, 'k' ) !== false )
			$bytes = intval( $size ) * 1024;
		elseif ( strpos( $size, 'm' ) !== false )
			$bytes = intval($size) * 1024 * 1024;
		elseif ( strpos( $size, 'g' ) !== false )
			$bytes = intval( $size ) * 1024 * 1024 * 1024;
		return $bytes;
	}
	
	function bsk_pdf_manager_show_pdf($atts, $content){
		global $wpdb;
		
		extract( shortcode_atts( array('id' => '', 
									   'linkonly' => '',
									   'linktext' => '',
									   'orderby' => '', 
									   'order' => '', 
									   'target' => '',
									   'dropdown' => 'false',
									   'mosttop' => 0,
									   'nofollowtag' => 'false',
									   'showdate' => 'false',
									   'dateformat' => 'd/m/Y',
									   'orderedlist' => '' 
									   ), 
								 $atts) );
		//organise ids array
		$ids_array = array();
		if( trim($id) == "" ){
			return '';
		}
		if( $id && is_string($id) ){
			$ids_array = explode(',', $id);
			foreach($ids_array as $key => $pdf_id){
				$pdf_id = intval(trim($pdf_id));
				if( is_int($pdf_id) == false ){
					unset($ids_array[$key]);
				}
				$ids_array[$key] = $pdf_id;
			}
		}
		if( !is_array($ids_array) || count($ids_array) < 1 ){
			return '';
		}

		//process open target
		$open_target_str = '';
		if( $target == '_blank' ){
			$open_target_str = ' target="_blank"';
		}
		//process order
		$order_by_str = ' ORDER BY `title`'; //default set to title
		$order_str = ' ASC';
		if( $orderby == 'title' ){
			//default
		}else if( $orderby == 'filename' ){
			$order_by_str = ' ORDER BY `file_name`';
		}else if( $orderby == 'date' ){
			$order_by_str = ' ORDER BY `last_date`';
		}
		if( trim($order) == 'DESC' ){
			$order_str = ' DESC';
		}
		//link only
		$show_link_only = false;
		if( $linkonly && is_string($linkonly) ){
			$show_link_only = $linkonly == 'yes' ? true : false;
			if( $linkonly == 'true' ){
				$show_link_only = true;
			}
		}else if( is_bool($linkonly) ){
			$return_link_only = $linkonly;
		}
		//link text
		$custom_link_text = '';
		if( $linktext && is_string($linkonly) ){
			$custom_link_text = $linktext;
		}
		//dropdown
		$output_as_dropdown = false;
		if( $dropdown && is_string($dropdown) ){
			$output_as_dropdown = $dropdown == 'yes' ? true : false;
			if( $dropdown == 'true' ){
				$output_as_dropdown = true;
			}
		}else if( is_bool($dropdown) ){
			$output_as_dropdown = $dropdown;
		}
		//most recent count
		$most_recent_count = intval($mosttop);
		if( $most_recent_count < 1 ){
			$most_recent_count = 99999;
		}
		//anchor nofollow tag
		$nofollow_tag = false;
		if( $nofollowtag && is_string($nofollowtag) ){
			$nofollow_tag = $nofollowtag == 'yes' ? true : false;
			if( $nofollowtag == 'true' ){
				$nofollow_tag = true;
			}
		}else if( is_bool($nofollowtag) ){
			$nofollow_tag = $nofollowtag;
		}
		//show date in title
		$show_date_in_title = false;
		if( $showdate && is_string($showdate) ){
			$show_date_in_title = $showdate == 'yes' ? true : false;
			if( $showdate == 'true' ){
				$show_date_in_title = true;
			}
		}else if( is_bool($showdate) ){
			$show_date_in_title = $showdate;
		}
		//date format
		$date_format_str = 'd/m/Y';
		if( $dateformat && is_string($dateformat) ){
			$date_format_str = $dateformat;
		}
		//show as ordered list
		$show_as_ordered_list = false;
		if( $orderedlist && is_string($orderedlist) ){
			$show_as_ordered_list = $orderedlist == 'yes' ? true : false;
			if( $orderedlist == 'true' ){
				$show_as_ordered_list = true;
			}
		}else if( is_bool($orderedlist) ){
			$show_as_ordered_list = $orderedlist;
		}
		
		$str_body = '';
		$sql = 'SELECT * FROM `'.$this->_pdfs_db_tbl_name.'` '.
		       'WHERE `id` IN('.implode(',', $ids_array).') '.
			   $order_by_str.$order_str.' '.
			   'LIMIT 0, %d';
		$sql = $wpdb->prepare( $sql, $most_recent_count );
		$pdf_items_results = $wpdb->get_results( $sql );
		if( !$pdf_items_results || !is_array($pdf_items_results) || count($pdf_items_results) < 1 ){
			return '';
		}
		
		if( $show_link_only == false ){
			if( $output_as_dropdown == false ){
				if( $show_as_ordered_list ){
					$str_body .= '<ol class="bsk-special-pdfs-container-ordered-list">';
				}else{
				$str_body .= '<ul class="bsk-special-pdfs-container">';
				}
			}else{
				$str_body .= '<select name="bsk_pdf_manager_special_pdfs_select" class="bsk-pdf-manager-pdfs-select" attr_target="'.$target.'">';
			}
		}
		if( $orderby == "" ){
			//order by id sequence
			$pdf_items_results_id_as_key = array();
			foreach( $pdf_items_results as $pdf_object ){
				$pdf_items_results_id_as_key[$pdf_object->id] = $pdf_object;
			}
			foreach( $ids_array as $pdf_id ){
				if( !isset($pdf_items_results_id_as_key[$pdf_id]) ){
					continue;
				}
				$pdf_item = $pdf_items_results_id_as_key[$pdf_id];
				if( $pdf_item->file_name && file_exists($this->_pdfs_upload_path.$pdf_item->file_name) ){
					$file_url = site_url().'/'.$this->_pdfs_upload_folder.$pdf_item->file_name;
					if( $show_link_only ){
						$str_body .= $file_url;
					}else{
						if( $output_as_dropdown == false ){
							$nofollow_tag_str = $nofollow_tag ? ' rel="nofollow"' : '';
							$link_text = $custom_link_text ? $custom_link_text : $pdf_item->title;
							if( $show_date_in_title ){
								$link_text .= '<span class="bsk-pdf-manager-pdf-date">'.date($date_format_str, strtotime($pdf_item->last_date)).'</span>';
							}
							$str_body .= '<li><a href="'.$file_url.'"'.$open_target_str.$nofollow_tag_str.'>'.$link_text.'</a></li>'."\n";
						}else{
							$option_text = $pdf_item->title;
							if( $show_date_in_title ){
								$option_text .= '--'.date($date_format_str, strtotime($pdf_item->last_date));
							}
							$str_body .= '<option value="'.$file_url.'">'.$option_text.'</option>'."\n";
						}
					}
				}
			}
		}else{
			foreach($pdf_items_results as $pdf_item){
				if ( $pdf_item->file_name && file_exists($this->_pdfs_upload_path.$pdf_item->file_name) ){
					$file_url = site_url().'/'.$this->_pdfs_upload_folder.$pdf_item->file_name;
					if( $show_link_only ){
						$str_body .= $file_url;
					}else{
						if( $output_as_dropdown == false ){
							$nofollow_tag_str = $nofollow_tag ? ' rel="nofollow"' : '';
							$link_text = $custom_link_text ? $custom_link_text : $pdf_item->title;
							if( $show_date_in_title ){
								$link_text .= '<span class="bsk-pdf-manager-pdf-date"> '.date($date_format_str, strtotime($pdf_item->last_date)).'</span>';
							}
							$str_body .= '<li><a href="'.$file_url.'" '.$open_target_str.$nofollow_tag_str.'>'.$link_text.'</a></li>'."\n";
						}else{
							$option_text = $pdf_item->title;
							if( $show_date_in_title ){
								$option_text .= '--'.date($date_format_str, strtotime($pdf_item->last_date));
							}
							$str_body .= '<option value="'.$file_url.'">'.$option_text.'</option>'."\n";
						}
					}
				}
			}
		}
		if( $show_link_only == false ){
			if( $output_as_dropdown == false ){
				if( $show_as_ordered_list ){
					$str_body .= '</ol>';
				}else{
				$str_body .= '</ul>';
				}
			}else{
				$str_body .= '</select>';
			}
		}

		return $str_body;
	}
	
	function bsk_pdf_manager_pdfs_add_by_ftp(){
		global $current_user, $wpdb;
		
		//get all categories
		$sql = 'SELECT * FROM '.$this->_categories_db_tbl_name;
		$categories = $wpdb->get_results( $sql );
		if( !$categories || !is_array($categories) || count($categories) < 1 ){
			$create_category_url = add_query_arg( 'page', $this->_plugin_pages_name['category'], admin_url() );
			$create_category_url = add_query_arg( 'view', 'addnew', $create_category_url );
			echo 'Please <a href="'.$create_category_url.'">create category</a> first';
			
			return;
		}
		
		
		echo '  <p style="margin-top:30px;">Please uplaod all you PDF files to the folder <b>'.$this->_pdfs_upload_path.$this->_pdfs_upload_folder_4_ftp.'</b> first.</p>';
		echo '  <p>After upload, your PDF will be moved out from this folder.<p>';
		echo '  <p>The maximum of PDF fiels that can be listed here is 50.</p>';
		?>
        <table class="widefat" style="width:60%;">
		<thead>
			<tr>
				<td class="check-column" style="width:15%; padding-left:10px;"><input type='checkbox' /></td>
				<td style="width:85%;">File</td>
			</tr>
		</thead>
		<tbody>
        	<?php
			$pdf_files_under_ftp_folder = array();
			if( count($pdf_files_under_ftp_folder) < 1 ){
			?>
            <tr class="alternate">
				<td colspan="2">Only availabe for Pro version.</td>
			</tr>
			<?php
            }else{
				$i = 1;
				foreach( $pdf_files_under_ftp_folder as $file_name ){
					$class_str = '';
					if( $i % 2 == 1 ){
						$class_str = ' class="alternate"';
					}
			?>
                <tr<?php echo $class_str; ?>>
                    <th class='check-column' style="padding-left:10px;"><input type='checkbox' name='bsk_pdf_manager_ftp_files[]' value='<?php echo esc_attr($file_name) ?>' style="padding:0; margin:0;" /></th>
                    <td><label><?php echo esc_html($file_name) ?></label></td>
                </tr>
        	<?php
					$i++;
				}
			}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td class="check-column" style="padding-left:10px;"><input type='checkbox' /></td>
				<td>File</td>
			</tr>
		</tfoot>
		</table>
		<?php
		if( 1 ){
		?>
        <h3>Please select category<span class="bsk-pdf-pro-tip-viewer dashicons dashicons-visibility" attrid="tip_4_bsk_pdf_manager_pdf_edit_categories_id"></span></h3>
        <input type="hidden" id="tip_4_bsk_pdf_manager_pdf_edit_categories_id" value="A PDF can be assigned to multiple categories for Pro verison." />
        <p>
		<?php
		if( count($categories ) > 1 && $categories ){
			foreach($categories as $category){
				echo '<label style="display:block;"><input type="checkbox" name="bsk_pdf_manager_ftp_categories[]" value="-cat-'.$category->id.'-" />'.$category->cat_title.'</label>&nbsp;&nbsp;&nbsp;&nbsp;';
			}
		}
        ?>
        </p>
        <p style="font-size:1.3em; font-weight:bold;">
        	<label>
            	<input type="checkbox" name="bsk_pdf_manager_ftp_use_file_name_as_title" id="bsk_pdf_manager_ftp_use_file_name_as_title_ID" />Use file name as title
            </label>
            <span class="bsk-pdf-pro-tip-viewer dashicons dashicons-visibility" attrid="tip_4_bsk_pdf_manager_pdf_edit_categories_id"></span>
        	<input type="hidden" id="tip_4_bsk_pdf_manager_pdf_edit_categories_id" value="A PDF can be assigned to multiple categories for Pro verison." />
        </p>
        <p id="bsk_pdf_manager_ftp_pdf_title_exclude_extension_container_ID" style="display:block;">
            	<span class="bsk-pdfm-field-label">Exclude extension(.pdf) from title:</span>
                <span class="bsk-pdf-field">
                    <label><input type="radio" name="bsk_pdf_manager_ftp_pdf_exclude_extension_from_title" value="YES" /> Yes</label>
                    <label style="margin-left:20px;"><input type="radio" name="bsk_pdf_manager_ftp_pdf_exclude_extension_from_title" value="NO" checked="checked" /> No</label>
                </span>
            </p>
		<p style="margin-top:20px;">
        	<input type="hidden" name="nonce" value="<?php echo $nonce; ?>" />
        	<input type="hidden" name="bsk_pdf_manager_action" value="pdf_upload_by_ftp" />
        	<input type="button" id="bsk_pdf_manager_add_by_ftp_save_button_ID" class="button-primary" value="Upload..." />
            <span class="bsk-pdf-pro-tip-viewer dashicons dashicons-visibility" attrid="tip_4_bsk_pdf_manager_pdf_add_by_ftp_button_id"></span>
            <input type="hidden" id="tip_4_bsk_pdf_manager_pdf_add_by_ftp_button_id" value="Only available for Pro verison." />
        </p>
        <?php
		}
	}
}