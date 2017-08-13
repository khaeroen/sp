<?php
/*
 * rendering product meta on product page
*/

global $nmfilemanager;

/* $args = array('name'	=> 'title', 'data-attr'	=> 'Lovelyman', 'col'=>50, 'row'=>5);
$nmfilemanager -> inputs['textarea'] -> render_input($args, 'The value'); */

$single_form = $nmfilemanager -> get_forms( $nmfilemanager -> form_id );
/* $nmfilemanager -> pa($single_form); */

$existing_meta 		= json_decode( $single_form -> the_meta, true);

//filemanager_pa($existing_meta);

if($existing_meta){
?>

<style>
<?php
/*
 * pasting the custom css if used in form settings
 */
echo stripslashes( strip_tags($single_form -> form_style));
?>
</style>

<?php 

echo '<form id="filemanager-'.$nmfilemanager -> form_id .'"';
echo 'onsubmit = "return save_file_data(this)"';
echo 'data-form="'.esc_attr( $single_form -> the_meta ).'">';
echo '<div id="nm-filemanager-box-'. $nmfilemanager->form_id .'" class="nm-filemanager-box">';


		/*
		 * forms extra information being sent hidden
		*/
		echo '<input type="hidden" name="_form_id" value="'.$nmfilemanager -> form_id.'">';
		echo '<input type="hidden" name="_sender_email" value="'.$single_form -> sender_email.'">';
		echo '<input type="hidden" name="_sender_name" value="'.$single_form -> sender_name.'">';
		echo '<input type="hidden" name="_subject" value="'.$single_form -> subject.'">';
		echo '<input type="hidden" name="_receiver_emails" value="'.$single_form -> receiver_emails.'">';
		echo '<input type="hidden" name="_success_message" value="'.stripslashes($single_form ->success_message).'" />';
		echo '<input type="hidden" name="_error_message" value="'.stripslashes($single_form -> error_message).'" />';

		$started_section = '';
		
foreach($existing_meta as $key => $meta)
		{
			
			/* webcontact_pa($meta); */
			$type = $meta['type'];
			$name = strtolower(preg_replace("![^a-z0-9]+!i", "_", $meta['data_name']));
			
			// conditioned elements

			$visibility = '';
			$conditions_data = '';
			if ( isset( $meta['logic'] ) && $meta['logic'] == 'on') {
				
				if($meta['conditions']['visibility'] == 'Show')
					$visibility = 'display: none';
		
				$conditions_data	= 'data-rules="'.esc_attr( json_encode($meta['conditions'] )).'"';
			}
			
			

			$show_asterisk 		= (isset( $meta['required'] ) && $meta['required']) ? '<span class="show_required"> *</span>' : '';
			$show_description	= ($meta['description']) ? '<span class="show_description">'.stripslashes($meta['description']).'</span>' : '';

			$the_width = ($meta['width'] == '' ? 100 : $meta['width']);
			$the_width = intval( $the_width ) - 1 .'%';
			$the_margin = '1%';

			$field_label = stripslashes( $meta['title'] ) . $show_asterisk . $show_description;

			if( isset( $meta['required'] ) ){
				$required = $meta['required'];
			} else {
				$required = '';
			}
			if( isset( $meta['error_message'] ) ){
				$error_message = $meta['error_message'];
			} else {
				$error_message = '';
			}
			$args = '';
			
			switch($type)
			{
				
				case 'text':
					
					
					$args = array(	'name'			=> $name,
									'id'			=> $name,
									'data-type'		=> $type,
									'data-req'		=> $required,
									'data-message'	=> $error_message);
					echo '<div id="box-'.$name.'" style="width: '. $the_width.'; margin-right: '. $the_margin.';'.$visibility.'" '.$conditions_data.'>';
					echo '<label for="'.$name.'">'. $field_label.' </label> <br />';
					
					$nmfilemanager -> inputs[$type]	-> render_input($args);					
					
					//for validtion message
					echo '<span class="errors"></span>';
					echo '</div>';
					break;
					
				case 'masked':

					$args = array(	'name'			=> $name,
					'id'			=> $name,
					'data-type'		=> $type,
					'data-req'		=> $required,
					'data-mask'		=> $meta['mask'],
					'data-ismask'	=> "no",
					'data-message'	=> $error_message);
					echo '<div id="box-'.$name.'" style="width: '. $the_width.'; margin-right: '. $the_margin.';'.$visibility.'" '.$conditions_data.'>';
					echo '<label for="'.$name.'">'. $field_label.' </label> <br />';
						
					$nmfilemanager -> inputs[$type]	-> render_input($args);
						
					//for validtion message
					echo '<span class="errors"></span>';
					echo '</div>';
					break;
				
				case 'hidden':

					$args = array(	'name'			=> $name,
									'id'			=> $name,
									'data-type'		=> $type,
								);
					
					$nmfilemanager -> inputs[$type]	-> render_input($args);	
					break;
					
			
				case 'date':
					
					
					$args = array(	'name'			=> $name,
									'id'			=> $name,
									'data-type'		=> $type,
									'data-req'		=> $required,
									'data-message'	=> $error_message,
									'data-format'	=> $meta['date_formats']);
			
					echo '<div id="box-'.$name.'" style="width: '. $the_width.'; margin-right: '. $the_margin.';'.$visibility.'" '.$conditions_data.'>';
					echo '<label for="'.$name.'">'. $field_label.' </label> <br />';
			
					$nmfilemanager -> inputs[$type]	-> render_input($args);
					//for validtion message
					echo '<span class="errors"></span>';
					echo '</div>';
					break;
		
				case 'email':
					
					if( isset( $meta['send_email'] ) ){
						$send_email = $meta['send_email'];	
					} else {
						$send_email = '';
					}

					$args = array(	'name'			=> $name,
									'id'			=> $name,
									'data-type'		=> $type,
									'data-req'		=> $required,
									'data-message'	=> $error_message,
									'data-sendemail'=> $send_email);

					echo '<div id="box-'.$name.'" style="width: '. $the_width.'; margin-right: '. $the_margin.';'.$visibility.'" '.$conditions_data.'>';
					echo '<label for="'.$name.'">'. $field_label.' </label> <br />';
					
					$nmfilemanager -> inputs[$type]	-> render_input($args);
					//for validtion message
					echo '<span class="errors"></span>';
					echo '</div>';
					break;
					
				
				case 'textarea':
				
		
					$args = array(	'name'			=> $name,
							'id'			=> $name,
							'data-type'		=> $type,
							'data-req'		=> $required,
							'data-message'	=> $error_message);
					
					echo '<div id="box-'.$name.'" style="width: '. $the_width.'; margin-right: '. $the_margin.';'.$visibility.'" '.$conditions_data.'>';
					echo '<label for="'.$name.'">'. $field_label.' </label> <br />';
					
					$nmfilemanager -> inputs[$type]	-> render_input($args);				
					//for validtion message
					echo '<span class="errors"></span>';
					echo '</div>';
					break;
					
					
				case 'select':
				
					$options = explode("\n", $meta['options']);
					$default_selected = $meta['selected'];
					
					if( isset( $meta['required'] ) ){
						$required = $meta['required'];	
					} else {
						$required = '';
					}

					$args = array(	'name'			=> $name,
									'id'			=> $name,
									'data-type'		=> $type,
									'data-req'		=> $required,
									'data-message'	=> $error_message);
				
					echo '<div id="box-'.$name.'" style="width: '. $the_width.'; margin-right: '. $the_margin.';'.$visibility.'" '.$conditions_data.'>';
					echo '<label for="'.$name.'">'. $field_label.' </label> <br />';
					
					$nmfilemanager -> inputs[$type]	-> render_input($args, $options, $default_selected);
				
					//for validtion message
					echo '<span class="errors"></span>';
					echo '</div>';
					break;
						
				case 'radio':
					
		
					$options = explode("\n", $meta['options']);
					$default_selected = $meta['selected'];
					
					$args = array(	'name'			=> $name,
									'id'			=> $name,
									'data-type'		=> $type,
									'data-req'		=> $required,
									'data-message'	=> $error_message);
				
					echo '<div id="box-'.$name.'" style="width: '. $the_width.'; margin-right: '. $the_margin.';'.$visibility.'" '.$conditions_data.'>';
					echo '<label for="'.$name.'">'. $field_label.' </label> <br />';
					
					$nmfilemanager -> inputs[$type]	-> render_input($args, $options, $default_selected);
					//for validtion message
					echo '<span class="errors"></span>';
					echo '</div>';
					break;
		
				case 'checkbox':

					$options = explode("\n", $meta['options']);
					$defaul_checked = explode("\n", $meta['checked']);
		
					$args = array(	'name'			=> $name,
							'id'			=> $name,
							'data-type'		=> $type,
							'data-req'		=> $required,
							'data-message'	=> $error_message);
					
					echo '<div id="box-'.$name.'" style="width: '. $the_width.'; margin-right: '. $the_margin.';'.$visibility.'" '.$conditions_data.'>';
					echo '<label for="'.$name.'">'. $field_label.' </label> <br />';
					
					$nmfilemanager -> inputs[$type]	-> render_input($args, $options, $defaul_checked);
					//for validtion message
					echo '<span class="errors"></span>';
					echo '</div>';
					break;
					
				case 'file':
				
			
					$label_select = ($meta['button_label_select'] == '' ? __('Select files', 'nm-personalizedproduct') : $meta['button_label_select']);
					/*$label_upload = ($meta['button_label_upload'] == '' ? __('Upload files', 'nm-personalizedproduct') : $meta['button_label_upload']);*/
					$files_allowed = ($meta['files_allowed'] == '' ? 1 : $meta['files_allowed']);
					$file_types = ($meta['file_types'] == '' ? 'jpg,png,gif' : $meta['file_types']);
					$file_size = ($meta['file_size'] == '' ? '10mb' : $meta['file_size']);
					/*$chunk_size = ($meta['chunk_size'] == '' ? '5mb' : $meta['chunk_size']);*/
					
					$args = array(	'name'			=> $name,
									'id'			=> $name,
									'data-type'		=> $type,
									'data-req'		=> $required,
									'data-message'	=> $error_message,
									'button-label-select'	=> $label_select,
									'files-allowed'			=> $files_allowed,
									'file-types'			=> $file_types,
									'file-size'				=> $file_size,
									/*'chunk-size'			=> $chunk_size,*/
									'button-class'			=> $meta['button_class'],
									/*'photo-editing'			=> $meta['photo_editing'],
									'editing-tools'			=> $meta['editing_tools'],
									'aviary-api-key'		=> $single_form -> aviary_api_key,*/
									'popup-width'	=> $meta['popup_width'],
									'popup-height'	=> $meta['popup_height']);
					
					echo '<div id="box-'.$name.'" class="fileupload-box" style="float:left; width: '. $the_width.'; margin-right: '. $the_margin.';'.$visibility.'" '.$conditions_data.'>';
					echo '<label for="'.$name.'">'. $field_label.'</label>';
					echo '<div id="nm-uploader-area-'. $name.'" class="nm-uploader-area">';
					
					$nmfilemanager -> inputs[$type]	-> render_input($args);
				
					echo '<span class="errors"></span>';
				
					echo '</div>';		//.nm-uploader-area
					echo '</div>';
				
					// adding thickbox support
					add_thickbox();
					break;
					
					
					case 'image':

						if( isset( $meta['multiple_allowed'] ) ){
							$multiple_allowed = $meta['multiple_allowed'];	
						} else {
							$multiple_allowed = '';
						}
						
						$args = array(	'name'			=> $name,
								'id'			=> $name,
								'data-type'		=> $type,
								'data-req'		=> $required,
								'data-message'	=> $error_message,
								'popup-width'	=> $meta['popup_width'],
								'popup-height'	=> $meta['popup_height'],
								'multiple-allowed' => $multiple_allowed);
					
						echo '<div id="pre-uploaded-images-'.$name.'" style="width: '. $the_width.'; margin-right: '. $the_margin.';'.$visibility.'" '.$conditions_data.'>';
						printf( __('<label for="%1$s">%2$s</label><br />', 'nm-personalizedproduct'), $name, $field_label );
							
						$nmfilemanager -> inputs[$type]	-> render_input($args, $meta['images']);
											
						//for validtion message
						echo '<span class="errors"></span>';
						echo '</div>';
						
						// adding thickbox support
						add_thickbox();
					break;
					
					
					case 'section':
						
						if($started_section)		//if section already started then close it first
							echo '</section>';
						
						$section_title 		= strtolower(preg_replace("![^a-z0-9]+!i", "_", stripslashes( $meta['title'] ))); 
						$started_section 	= 'webcontact-section-'.$section_title;
						
						$args = array(	'id'			=> $started_section,
								'data-type'		=> $type,
								'title'			=> stripslashes( $meta['title'] ),
								'description'			=> $meta['description'],
								);
						
						$nmfilemanager -> inputs[$type]	-> render_input($args);
						
					break;
		
				}
		}
		
	echo '</div>';  //ends nm-filemanager-box
	echo '<div style="clear: both"></div>';
	
	
	echo '<div class="filemanager-save-button"><input type="submit" class="'.$single_form -> button_class.'" value="'.$single_form -> button_label.'"></div>';
	echo '<span id="nm-saving-file"></span>';
	wp_nonce_field('doing_contact','nm_filemanager_nonce');
	echo '</form>';
}

?>
