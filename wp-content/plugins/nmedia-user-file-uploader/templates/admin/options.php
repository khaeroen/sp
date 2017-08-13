<?php

$meatGeneral = array('thumb-size'	=> array(	'label'		=> __('Images thumb size', 'nm-filemanager'),
					 							'desc'		=> __('Enter integer value for thumb size for images', 'nm-filemanager'),
					 							'id'			=> 'nm_filemanager'.'_thumb_size',
					 							'type'			=> 'text',
					 							'default'		=> '75',
					 							'help'			=> __('type size in px like: <strong>100</strong>', 'nm-filemanager')
					 							),
					'button-title'	=> array(	'label'		=> __('Uploader button title', 'nm-filemanager'),
					 							'desc'		=> __('Enter text for uploader button', 'nm-filemanager'),
					 							'id'			=> 'nm_filemanager'.'_button_title',
					 							'type'			=> 'text',
					 							'default'		=> 'Upload',
					 							'help'			=> ''
					 							),
					'button-bg-color'=> array(	'label'		=> __('Uploader button background color', 'nm-filemanager'),
					 							'desc'		=> __('Enter background color for uploader button', 'nm-filemanager'),
					 							'id'			=> 'nm_filemanager'.'_button_bg_color',
					 							'type'			=> 'text',
					 							'default'		=> '#ccc',
					 							'help'			=> 'in Hex code like: #ffccc'
					 							),
					'button-txt-color'=> array(	'label'		=> __('Uploader button text color', 'nm-filemanager'),
					 							'desc'		=> __('Enter text color for uploader button', 'nm-filemanager'),
					 							'id'			=> 'nm_filemanager'.'_button_txt_color',
					 							'type'			=> 'text',
					 							'default'		=> '#fff',
					 							'help'			=> 'in Hex code like: #ffccc'
					 							),
					'uploader-bg-color'=> array('label'		=> __('Uploader area background color', 'nm-filemanager'),
					 							'desc'		=> __('Enter background color for uploader area', 'nm-filemanager'),
					 							'id'			=> 'nm_filemanager'.'_uploader_bg_color',
					 							'type'			=> 'text',
					 							'default'		=> '#999',
					 							'help'			=> 'in Hex code like: #ffccc'
					 							),
					'max-file_size'	=> array(	'label'		=> __('Maximum file size in mb', 'nm-filemanager'),
					 							'desc'		=> __('Enter maximum file size in mb', 'nm-filemanager'),
					 							'id'			=> 'nm_filemanager'.'_max_file_size',
					 							'type'			=> 'text',
					 							'default'		=> '',
					 							'help'			=> __('e.g: <strong>3mb</strong>', 'nm-filemanager')
					 							),
					'max-files'		=> array(	'label'		=> __('Max numbers of files (Max 5 allowed in free version)', 'nm-filemanager'),
					 							'desc'		=> __('Enter no for max files to upload at once', 'nm-filemanager'),
					 							'id'			=> 'nm_filemanager'.'_max_files',
					 							'type'			=> 'text',
					 							'default'		=> '',
					 							'help'			=> __('e.g: <strong>3</strong>', 'nm-filemanager')
					 							),
					'file-types'	=> array(	'label'		=> __('File types', 'nm-filemanager'),
					 							'desc'		=> __('Enter type of files to upload', 'nm-filemanager'),
					 							'id'			=> 'nm_filemanager'.'_file_types',
					 							'type'			=> 'text',
					 							'default'		=> '',
					 							'help'			=> __('e.g: <strong>jpg,png,gif,zip,pdf</strong>', 'nm-filemanager')
					 							),							
					'file-uploaded'	=> array(	'label'		=> __('File saved message', 'nm-filemanager'),
												'desc'		=> __('Displayed when file is uploaded/saved successfully', 'nm-filemanager'),
												'id'			=> 'nm_filemanager'.'_file_saved',
												'type'			=> 'textarea',
												'default'		=> __('File(s) Uploaded!', 'nm-filemanager'),
												'help'			=> ''
												),
					'filetype-error'=> array(	'label'		=> __('Error message', 'nm-filemanager'),
												'desc'		=> __('This message will be shown when error occur in uploading file', 'nm-filemanager'),
												'id'			=> 'nm_filemanager'.'_error_msg',
												'type'			=> 'textarea',
												'default'		=> __('Fail to upload!', 'nm-filemanager'),
												'help'			=> ''
												),
							
					/*'send-files'	=>  array(	'label'		=> __('Do you want to send files as email?', 'nm-filemanager'),
												'desc'		=> __('Send files to user when it is uploaded/saved?', 'nm-filemanager'),
												'id'			=> 'nm-filemanager'.'_send_files',
												'type'			=> 'checkbox',
												'default'		=> '',
												'options'		=> array('yes'	=> 'Yes', 'No'	=> 'No'),
												'help'			=> __('', 'nm-filemanager')
												),*/
					);
					



$meat_pro_features = array('file-meta'	=> array(	
									'desc'		=> '',
									'type'		=> 'file',
									'id'		=> 'get-pro.php',
									),
								);


$this -> the_options = array('general-settings'	=> array(	'name'		=> __('Basic Setting', 'nm-filemanager'),
															'type'	=> 'tab',
															'desc'	=> __('<p>Use this shortcode any of the page: [nm-wp-file-uploader] and set following options as per your need', 'nm-filemanager'),
															'meat'	=> $meatGeneral,
														
														),
								'get-pro'		=> array(	'name'		=> __('Pro - Get 25% Discount', 'nm-filemanager'),
															'type'	=> 'tab',
															'desc'	=> __('Get PRO version and enjoy following features', 'nm-filemanager'),
															'meat'	=> $meat_pro_features,
														),
																				'more-plugins'	=> array('name'		=> __('More Plugins', 'nm-mailchimp'),
														'type'	=> 'tab',
														'desc'	=> __('You may also like other plugins by N-Media.', 'nm-mailchimp'),
														'meat'	=> 'more',
														),

	
							);

//print_r($repo_options);