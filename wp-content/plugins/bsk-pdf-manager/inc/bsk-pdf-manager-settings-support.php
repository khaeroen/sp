<?php

class BSKPDFManagerSettingsSupport {

	var $_categories_db_tbl_name = '';
	var $_pdfs_db_tbl_name = '';
	var $_pdfs_upload_path = '';
	var $_pdfs_upload_folder = '';
	var $_bsk_pdf_manager_plugin_version = '';

	var $_open_target_option_name = '';
	var $_show_category_title_when_listing_pdfs = '';
	var $_pdf_order_by_option_name = '';
	var $_pdf_order_option_name = '';
	
	var $_bsk_pdf_manager_product_details_page_url = 'http://www.bannersky.com/bsk-pdf-manager/';
	var $_bsk_pdfm_remote_license_policy_page = 'http://www.bannersky.com/plugin-licensing/';
	var $_bsk_pdfm_remote_license_checkout_page = 'http://www.bannersky.com/refund-policy/';
	var $_bsk_pdfm_remote_account_page = 'http://www.bannersky.com/your-account/';
	var $_bsk_plugin_support_center = 'http://www.bannersky.com/contact-us/';
	var $_bsk_plugin_documentation_page = 'http://www.bannersky.com/document/bsk-pdf-manager-documentation/';

    var $_default_pdf_icon_url = '';
	
	public function __construct( $args ) {
		global $wpdb;
		
		$this->_categories_db_tbl_name = $args['categories_db_tbl_name'];
		$this->_pdfs_db_tbl_name = $args['pdfs_db_tbl_name'];
		$this->_pdfs_upload_path = $args['pdf_upload_path'];
	    $this->_pdfs_upload_folder = $args['pdf_upload_folder'];
		$this->_open_target_option_name = $args['open_target_option_name'];
		$this->_show_category_title_when_listing_pdfs = $args['show_category_title'];
		$this->_pdf_order_by_option_name = $args['pdf_order_by'];
		$this->_pdf_order_option_name = $args['pdf_order'];		
		$this->_bsk_pdf_manager_plugin_version = $args['plugin_version'];		
		$this->_default_pdf_icon_url = $args['default_pdf_icon'];
		
		$this->_pdfs_upload_path = $this->_pdfs_upload_path.$this->_pdfs_upload_folder;
	}
	
	function show_settings(){

		?>
        
        <h2 class="nav-tab-wrapper">
            <a class="nav-tab nav-tab-active" href="javascript:void(0);" id="bsk_pdfm_setings_tab-general-settings">General settings</a>
            <a class="nav-tab" href="javascript:void(0);" id="bsk_pdfm_setings_tab-featured-image">Featured Image</a>
            <a class="nav-tab" href="javascript:void(0);" id="bsk_pdfm_setings_tab-multi-column-layout">Multi-column Layout</a>
        </h2>
        <div id="bsk_pdfm_setings_tab_content_wrap_ID">
            <section><?php $this->general_settings(); ?></section>
            <section><?php $this->featured_iamge_settings(); ?></section>
            <section><?php $this->multi_column_layout_settings(); ?></section>         	 
        </div>
        <div class="bsk-prdoucts">
            <div class="bsk-prdoucts-single">
            	<h3>BSK GravityForms Blacklist</h3>
                <p>The plugin was built to help block submissions from users using spam data or competitors info to create new entry to your site. This plugin allows you to validate a field's value against the keywords and email addresses that you added in admin area.</p>
                <ol>
                    <li>Blacklist use to block form submitting if field value match any of items( keywords ).</li>
                    <li>White list use to allow form submitting only the field value match any of items( keywords ).</li>
                    <li>Email list use to allow form submitting only the field value match any of items( email address ).</li>
                    <li>Support add items( keywords ) by import CSV file, also can export items ( keywords ) to CSV file.</li>
               	</ol>
                <p>
                	<span style="color:#F33;">Extra 20% off before July 1, 2017</span><br />
                	<span style="color:#F33;">Extra 10% off if you have any valid license of our product</span>
                </p>
                <p class="bsk-prdoucts-single-center">
                	<a class="button button-primary bsk-prdoucts-single-link-button" href="http://www.bannersky.com/bsk-gravityforms-blacklist/" target="_blank">More Info</a>
                </p>
            </div>
        </div>
		<?php
	}
	
	function general_settings(){

	?>
    <form action="" method="POST" id="bsk_pdfm_general_settings_form_ID">
    <div class="bsk_pdf_manager_settings">
    	<p>
        	<label>
            	<input type="checkbox" name="bsk_pdfm_general_setting_allow_author_access_pdf_category" value="1"/> Allows a user with Author capabilities to access Categories & PDF Documents menu
            </label>
            <span class="bsk-pdf-pro-tip-viewer dashicons dashicons-visibility" attrid="tip_4_bsk_pdfm_general_setting_allow_author_access_pdf_category_id"></span>
            <input type="hidden" id="tip_4_bsk_pdfm_general_setting_allow_author_access_pdf_category_id" value="Only available for Pro verison." />
        </p>
        <p><i>By default, only a user with Editor role ( or above ) can access all menus of BSK PDF Manager. With this setting you may allow an Author user to manage category & PDF without other settings visible.</i> </p>
        <?php $this->show_help(); ?>
        <h3 style="margin-top:40px;">Professional Edition</h3>
        <p>Professional edition available now, you may <a href="http://www.bannersky.com/bsk-pdf-manager/" target="_blank">visit it from here</a> to check more fetures.</p>
        <p>Before you upgrade to professional edition, please check <a href="http://www.bannersky.com/document/bsk-pdf-manager-documentation/upgrade-to-professional-version/" target="_blank">the documenation from here</a> first.</p>
        <p style="margin-top:20px;">
        	<input type="button" id="bsk_pdf_manager_settings_general_tab_save_form_ID" class="button-primary" value="Save General Settings" />
            <input type="hidden" name="bsk_pdf_manager_action" value="general_settings_save" />
            <span class="bsk-pdf-pro-tip-viewer dashicons dashicons-visibility" attrid="tip_4_bsk_pdf_manager_settings_general_tab_save_form_ID"></span>
            <input type="hidden" id="tip_4_bsk_pdf_manager_settings_general_tab_save_form_ID" value="Only available for Pro verison." />

        </p>
    </div>
    </form>
    <?php
	}
	

	function featured_iamge_settings(){

	?>
    <form action="" method="POST" id="bsk_pdfm_featured_image_settings_form_ID">
    <div class="bsk_pdf_manager_settings_featured_image_tab" style="width:80%;">
        <p>
        	<label><input type="checkbox" name="bsk_pdf_manager_enable_featured_image" id="bsk_pdf_manager_enable_featured_image_ID" value="1" /> Enable featured image</label>
        	<span class="bsk-pdf-pro-tip-viewer dashicons dashicons-visibility" attrid="tip_4_bsk_pdf_manager_enable_featured_image_ID"></span>
            <input type="hidden" id="tip_4_bsk_pdf_manager_enable_featured_image_ID" value="Only available for Pro verison." />
        </p>
        <div id="bsk_pdf_manager_featured_image_settings_containder_ID" style="display:block;">
            <h4>PDF Default Featured Image</h4>
            <p>
                <div id="postimagediv" class="postbox" style="width:95%;" >
                    <div class="inside">
                    <p class="hide-if-no-js">
                        <span id="bsk_pdf_manger_default_image_icon_container_ID" style="display:block;"><img src="<?php echo $this->_default_pdf_icon_url; ?>" /></span>
                        <a title="Set default featured image" href="javascript:void(0);" id="bsk_pdf_manager_set_default_featured_image_anchor_ID">
                        <?php echo 'Change default featured image'; ?>
                        </a>
                    </p>
                </div>
            </p>
            <h4>Register Featured Image Size</h4>
            <p>
                <span style="display:inline-bloc;">Name: <input type="text" name="bsk_pdf_manager_register_image_size_name_1" id="bsk_pdf_manager_register_image_size_name_1_ID" value="" style="width:150px;" /> Width: <input type="number" name="bsk_pdf_manager_register_image_size_width_1" id="bsk_pdf_manager_register_image_size_width_1_ID" value="" style="width:80px;" />px Height: <input type="number" name="bsk_pdf_manager_register_image_size_height_1"  id="bsk_pdf_manager_register_image_size_height_1_ID" value="" style="width:80px;" />px
                </span>
                <span style="display:inline-block; margin-left:15px;"><label><input type="checkbox" name="bsk_pdf_manager_register_image_size_crop_1" id="bsk_pdf_manager_register_image_size_crop_1_ID" value="Yes" />Crop thumbnail to exact dimensions ?</label></span>
            </p>
            <p>
                <span style="display:inline-bloc;">Name: <input type="text" name="bsk_pdf_manager_register_image_size_name_2" id="bsk_pdf_manager_register_image_size_name_2_ID" value="" style="width:150px;" /> Width: <input type="number" name="bsk_pdf_manager_register_image_size_width_2" id="bsk_pdf_manager_register_image_size_width_2_ID" value="" style="width:80px;" />px Height: <input type="number" name="bsk_pdf_manager_register_image_size_height_2" id="bsk_pdf_manager_register_image_size_height_2_ID" value="" style="width:80px;" />px</span>
                <span style="display:inline-block; margin-left:15px;"><label><input type="checkbox" name="bsk_pdf_manager_register_image_size_crop_2" value="Yes" />Crop thumbnail to exact dimensions ?</label></span>
            </p>
            <h4>Default Featured Image Size</h4>
            <p>
                <select name="bsk_pdf_manager_default_thumbnail_size" id="bsk_pdf_manager_default_thumbnail_size_ID">
                <?php
                    $image_sizes = $this->get_image_sizes();
                    $hidden_dimission_str = '';
                    $hidden_registers_size_names_array = array();
                    $selected_width_str = '';
                    $selected_height_str = '';
                    $selected_crop_ste = 'Crop: No';
                    foreach ( $image_sizes as $size_name => $size_name_dimission )  {
                        if ( $size_name_dimission['width'] < 1 || 
                            $size_name_dimission['height'] < 1 || 
                            $size_name == 'bsk-pdf-dashboard-list-thumbnail' ){
                            continue;
                        }
                        $selected_str = '';
						$selected_crop_str = '';
                        if ( $default_thumbnail_size == $size_name ) {
                            $selected_str = 'selected="selected"';
                            $selected_width_str = 'Width: '.$size_name_dimission['width'].'px';
                            $selected_height_str = 'Height: '.$size_name_dimission['height'].'px';
                            $selected_crop_str = $size_name_dimission['crop'] ? 'Crop: Yes' : 'Crop: No';
                        }
                        echo '<option value="'.$size_name.'" '.$selected_str.'>'.$size_name.'</option>';
                        $crop = $size_name_dimission['crop'] ? 'Yes' : 'No';
                        $hidden_value = $size_name_dimission['width'].'_'.$size_name_dimission['height'].'_'.$crop;
                        $hidden_dimission_str .= '<input type="hidden" id="bsk_pdfm_size_dimission_'.$size_name.'_ID" value="'.$hidden_value.'" />';
                
                        //organise register sizes array, excude regsiter size by me
                        if( is_array($register_image_size_2) && count($register_image_size_2) > 0 ){
                            if( $size_name == $register_image_size_2['name'] ){
                                continue;
                            }
                        }
                        if( is_array($register_image_size_1) && count($register_image_size_1) > 0 ){
                            if( $size_name == $register_image_size_1['name'] ){
                                continue;
                            }
                        }
                        $hidden_registers_size_names_array[] = $size_name;
                    }
                
                    if( $default_thumbnail_size == 'full' ){
                        echo '<option value="full" selected="selected">full</option>';
                    }else{
                        echo '<option value="full">full</option>';
                    }
                ?>
                </select>
            </p>
        </div>
        <p style="margin-top:20px;">
        	<input type="button" id="bsk_pdf_manager_settings_featured_image_tab_save_form_ID" class="button-primary" value="Save Featured Image Settings" />
            <input type="hidden" name="bsk_pdf_manager_action" value="featured_image_settings_save" />
            <span class="bsk-pdf-pro-tip-viewer dashicons dashicons-visibility" attrid="tip_4_bsk_pdf_manager_settings_featured_image_tab_save_form_ID"></span>
            <input type="hidden" id="tip_4_bsk_pdf_manager_settings_featured_image_tab_save_form_ID" value="Only available in Pro verison." />
        </p>
    </div>
    </form>
    <?php
	}

	
	function bsk_pdf_manager_current_user_can(){
		global $current_user;
		
		if ( current_user_can('level_3') ){
			return true;
		}else{
			/*
			//get role;
			$user_roles = $current_user->roles;
			$user_role = array_shift($user_roles);
			
			if ($user_role == 'spcial role'){
				return true;
			}
			*/
		}
		return false;
	}
	
	function get_image_sizes() {
		global $_wp_additional_image_sizes;
	
		$sizes = array();
	
		foreach ( get_intermediate_image_sizes() as $_size ) {
			if ( $_size == 'bsk-pdf-dashboard-list-thumbnail' ){
				continue;
			}
			if ( in_array( $_size, array('thumbnail', 'medium', 'medium_large', 'large') ) ) {
				$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
				$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
				$sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
			} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
				$sizes[ $_size ] = array(
					'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
					'height' => $_wp_additional_image_sizes[ $_size ]['height'],
					'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
				);
			}
		}
	
		return $sizes;
	}
	
	function show_help(){
		?>
        <h4 style="margin-top:40px;">Plugin Documentation</h4>
        <ul>
            <li>Check plugin documentation from <a href="<?php echo $this->_bsk_plugin_documentation_page; ?>" target="_blank">here >></a></li>
        </ul>
        <h4 style="margin-top:40px;">Plugin Support Centre</h4>
        <ul>
            <li><a href="<?php echo $this->_bsk_plugin_support_center; ?>" target="_blank">Visit the Support Centre</a> if you have a question on using this plugin</li>
        </ul>
        <?php
	}
	
	function multi_column_layout_settings(){
	?>
    <form action="" method="POST" id="bsk_pdfm_multi_column_layout_settings_form_ID">
    <div class="bsk_pdf_manager_settings">
    	<p>
        	<label>
            	<input type="checkbox" name="bsk_pdfm_multi_column_layout_enable" id="bsk_pdfm_multi_column_layout_enable_ID" value="1" /> Enable multi-column layout
            </label>
            <span class="bsk-pdf-pro-tip-viewer dashicons dashicons-visibility" attrid="tip_4_bsk_pdfm_multi_column_layout_enable_ID"></span>
            <input type="hidden" id="tip_4_bsk_pdfm_multi_column_layout_enable_ID" value="Only available for Pro verison." />
        </p>
        <p><i>The multi-column layout allows easy display of multiple columns of PDFs</i></p>
        <div id="bks_pdfm_multi_column_enabled_settings_container_ID" style="padding-top:20px;display:<?php echo $multi_column_settings_container_display; ?>;">
        	<p>
            	<h4>Number of columns for the output: </h4>
                <select name="bsk_pdfm_multi_column_layout_default_column_number" style="width:100px;">
                    <option value="2">2</option>
                    <option value="3">>3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                </select>
            </p>
            <p>
            	<h4>Choose PDF title position when featured image shown: </h4>
                <select name="bsk_pdfm_multi_column_layout_title_positon" style="width:100px;">
                	<option value="top">Top</option>
                    <option value="below">Below</option>
                </select>
                <span style="display:inline-block;margin-left:20px;font-style:italic;">Works when featuredimage="yes" showpdftitle="yes" added to shortcode.</span>
            </p>
        </div>
        <p style="margin-top:20px;">
        	<input type="button" id="bsk_pdf_manager_settings_mulit_column_tab_save_form_ID" class="button-primary" value="Save Multi-column Settings" />
            <input type="hidden" name="bsk_pdf_manager_action" value="multi_column_settings_save" />
            <span class="bsk-pdf-pro-tip-viewer dashicons dashicons-visibility" attrid="tip_4_bsk_pdf_manager_settings_mulit_column_tab_save_form_ID"></span>
            <input type="hidden" id="tip_4_bsk_pdf_manager_settings_mulit_column_tab_save_form_ID" value="Only available for Pro verison." />
        </p>
    </div>
    </form>
    <?php
	}
	

}