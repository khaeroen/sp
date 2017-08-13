<?php
/*
 * it is responsible to upload files
 */
 
global $nmfilemanager;

if( filemanager_get_browser_runtime() == 'flash' )
	echo '<p class="no-ie-support-message">'.__('Note: IE is not recommended browser please use Firefox/Chrome/Safari instead', 'nm-filemanager').'</p>';
?>

<?php filemanager_show_notice(); ?>

<form id="form-save-new-file" onsubmit="return save_uploaded_files(this);" style="background-color: <?php echo $this -> get_option('_uploader_bg_color'); ?>">
  
  	
	<div id="nm-uploadfile" class="nm-uploader-area">
	    <div id="container_buttons">
	        <div class="btn_center">
	            <a id="select-button" href="javascript:;" style="background-color: <?php echo $this -> get_option('_button_bg_color'); ?>; color: <?php echo $this -> get_option('_button_txt_color'); ?>">
	                <?php
	                /**
					 * DO IT:
					 * GET form option
					 */
					 $select_file_label = $this -> get_option ( '_button_title' );
				     $select_file_label	= (!$select_file_label == '') ? $select_file_label : 'Select Files';
					 printf(__('%s', 'nm-filemanager'), $select_file_label);
					?>
	            </a>
	            <?php
	            /**
				 * DO IT:
				 * Following text will be shown based on user choice
				 * options
				 * 1. nm_file_size
				 * 2. nm_file_types
				 * 3. nm_files_allowed
				 */
	            
				 $nm_file_size      = $this -> get_option ( '_max_file_size' );
				 $nm_file_types     = $this -> get_option ( '_file_types' );
				 
				 // in free version we will have condition here to limit to 5 files only
				 
				 $nm_files_allowed  = $this -> get_option ( '_max_files' );
				 $nm_files_allowed	= (!$nm_files_allowed == '') ? $nm_files_allowed : 5;
				 if( $nm_files_allowed > 5 ){
					$nm_files_allowed = 5;
				 }


				
				 $nm_file_size 		= (!$nm_file_size == '') ? $nm_file_size : '5mb';
				 $nm_file_types		= (!$nm_file_types == '') ? $nm_file_types : 'jpg,png,gif,zip,pdf';
				 
				 ?>
	            <em><?php printf( __('File max size: %s', 'nm-filemanager'), $nm_file_size);?></em><br />
	            <em><?php printf( __('File types: %s', 'nm-filemanager'), $nm_file_types);?></em><br />
                <em><?php printf( __('Files allowed: %s', 'nm-filemanager'), $nm_files_allowed);?></em>
	        </div>
	    </div>
	    <div id="filelist-uploadfile" class="filelist">
	        
	        
	    </div>
	</div>
	
	<div id="fileupload-button-bar" class="clearfix">
		<?php
		/**
		 * DO IT:
		 * Button label will be an option
		 */
		 $save_button_label = ( isset($save_button_label) ? $save_button_label : 'Save');
		 ?>
		<a id="fileupload-button" href="javascript:;">
			<?php printf( __('%s', 'nm-filemanager'), $save_button_label);?>
		</a>
		<p style="text-align:center;margin-top: 25px;" id="nm-saving-file"></p> 
	</div>
	
	<?php
	/**
	 * wp nonce
	 */
	 wp_nonce_field('saving_file','nm_filemanager_nonce');
	 ?>
</form>