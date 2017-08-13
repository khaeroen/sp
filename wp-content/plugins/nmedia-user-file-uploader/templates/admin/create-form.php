<?php
/*
 * This our new world, Inshallah 29 aug, 2013
 */
global $nmfilemanager;

/* filemanager_pa( $nmfilemanager -> inputs); */

// text, password, textarea types settings

	$form_name = '';
	$sender_email = '';
	$sender_name = '';
	$subject = '';
	$receiver_emails = '';
	$button_label = '';
	$button_class = '';
	$success_message = '';
	$error_message = '';
	$thumb_size = '';
	$form_style = '';

if (isset ( $_REQUEST ['form_id'] ) && $_REQUEST ['form_id'] != '') {
	
	$single_form = $nmfilemanager->get_forms ( intval ( $_REQUEST ['form_id'] ) );
	//$nmfilemanager -> pa($single_form);
	
	$form_name = $single_form->form_name;
	$sender_email = $single_form->sender_email;
	$sender_name = $single_form->sender_name;
	$subject = $single_form->subject;
	$receiver_emails = $single_form->receiver_emails;
	$button_label = $single_form->button_label;
	$button_class = $single_form->button_class;
	$success_message = $single_form->success_message;
	$error_message = $single_form->error_message;
	$thumb_size = $single_form->thumb_size;
	$form_style = $single_form->form_style;
	
	$form_meta = json_decode ( $single_form->the_meta, true );
	
	//$nmfilemanager->pa ( $form_meta );
}

$url_cancel = $this -> nm_plugin_fix_request_uri(array('action'=>'','form_id'=>''));
echo '<p><a class="button" href="'.$url_cancel.'">&laquo; '.__('Existing forms', 'nm-filemanager').'</a></p>';
?>

<input type="hidden" name="form_id"
	value="<?php echo $_REQUEST['form_id']?>">
<div id="nmcontact-form-generator">
	<ul>
		<li><a href="#formbox-1"><?php _e('Form Settings', 'nm-filemanager')?></a></li>
		<li><a href="#formbox-2"><?php _e('Form Fields', 'nm-filemanager')?></a></li>
		<li style="float: right"><button class="button-primary button"
				onclick="save_form_meta(<?php echo $form_id?>)"><?php _e('Save settings', 'nm-filemanager')?></button>
			<span id="nm-saving-form" style="display:none"><img alt="saving..." src="<?php echo $nmfilemanager->plugin_meta['url']?>/images/loading.gif"></span></li>
	</ul>

	<div id="formbox-1">

		<table id="form-main-settings" border="0" bordercolor=""
			style="background-color: #F8F8F8; padding: 10px" width="100%"
			cellpadding="0" cellspacing="0">
			<tr>
				<td class="headings"><?php _e('Form name', 'nm-filemanager')?></td>
				<td><input type="text" name="form_name" id="form_name"
					value="<?php echo $form_name?>" /> <br />
					<p class="s-font"><?php _e('For your reference', 'nm-filemanager')?></p></td>
			</tr>
			<tr>
				<td class="headings"><?php _e('Receiver(s)', 'nm-filemanager')?></td>
				<td><input type="text" name="receiver_emails"
					value="<?php echo $receiver_emails?>" /> <br />
					<p class="s-font"><?php _e('Define the emails used (separeted by comma) to receive emails.', 'nm-filemanager')?></p></td>
			</tr>
			<tr>
				<td class="headings"><?php _e('Sender Email', 'nm-filemanager')?></td>
				<td><input type="email" name="sender_email"
					value="<?php echo $sender_email?>" /> <br />
					<p class="s-font"><?php _e('Define from what email send the message.', 'nm-filemanager')?></p></td>
			</tr>
			<tr>
				<td class="headings"><?php _e('Sender Name', 'nm-filemanager')?></td>
				<td><input type="text" name="sender_name"
					value="<?php echo $sender_name?>" /> <br />
					<p class="s-font"><?php _e('Define the name of email that send the message.', 'nm-filemanager')?></p>
				</td>
			</tr>
			<tr>
				<td class="headings"><?php _e('Subject', 'nm-filemanager')?></td>
				<td><input type="text" name="subject" value="<?php echo $subject?>" />
					<br />
					<p class="s-font"><?php _e('Define the subject of the email sent to you.', 'nm-filemanager')?></p></td>
			</tr>

			<tr>
				<td class="headings"><?php _e('Submit Button Label', 'nm-filemanager')?></td>
				<td><input type="text" name="button_label"
					value="<?php echo $button_label?>" /> <br />
					<p class="s-font"><?php _e('Define the label of submit button.', 'nm-filemanager')?></p></td>
			</tr>
			<tr>
				<td class="headings"><?php _e('Submit Button Class', 'nm-filemanager')?></td>
				<td><input type="text" name="button_class"
					value="<?php echo $button_class?>" /> <br />
					<p class="s-font"><?php _e('Define the class of submit button.', 'nm-filemanager')?></p></td>
			</tr>
			
			<tr>
				<td class="headings"><?php _e('Success Message', 'nm-filemanager')?></td>
				<td><input type="text" name="success_message"
					value="<?php echo $success_message?>" /> <br />
					<p class="s-font"><?php _e('Define the message when there is an error on send of email.', 'nm-filemanager')?></p></td>
			</tr>
			<tr>
				<td class="headings"><?php _e('Error Message', 'nm-filemanager')?></td>
				<td><input type="text" name="error_message"
					value="<?php echo $error_message?>" /> <br />
					<p class="s-font"><?php _e('Define the message when there is an error on send of email.', 'nm-filemanager')?></p></td>
			</tr>
            <tr>
				<td class="headings"><?php _e('Thumb size for images', 'nm-filemanager')?></td>
				<td><input type="text" name="thumb_size"
					value="<?php echo $thumb_size?>" /> <br />
					<p class="s-font"><?php _e('Provide thumb size for images e.g: 75', 'nm-filemanager')?></p></td>
			</tr>

			
			<tr>
				<td class="headings"><?php _e('Form styling/css', 'nm-filemanager')?></td>
				<td><textarea rows="7" cols="25" name="form_style"><?php echo stripslashes($form_style)?></textarea> <br />
					<p class="s-font"><?php _e('Form styling/css.', 'nm-filemanager')?></p></td>
			</tr> 
		</table>

	</div>
	<!--------------------- END formbox-1 ---------------------------------------->

	<div id="formbox-2">
		<div id="form-meta-bttons">
			<p>
		<?php _e('select input type below and drag it on right side. Then set more options', 'nm-filemanager')?>
		</p>

			<ul id="nm-input-types">
		<?php
		foreach ( $nmfilemanager -> inputs as $type => $meta ) {
			
			echo '<li class="input-type-item" data-inputtype="' . $type . '">';
			echo '<div><h3><span class="top-heading-text">' . $meta -> title . '</span>';
			echo '<span class="top-heading-icons ui-icon ui-icon-arrow-4"></span>';
			echo '<span class="top-heading-icons ui-icon-placehorder"></span>';
			echo '<span style="clear:both;display:block"></span>';
			echo '</h3>';
			
			// this function Defined below
			echo render_input_settings ( $meta -> settings );
			
			echo '</div></li>';
			// echo '<div><p>'.$data['desc'].'</p></div>';
		}
		?>
		</ul>
		</div>


		<div id="form-meta-setting" class="postbox-container">

			<div id="postcustom" class="postbox">
				<h3>
					<span style="float: left"><?php _e('Drage form fields here', 'nm-filemanager')?></span>
					<span style="float: right"><span style="float: right"
						title="<?php _e('Collapse all', 'nm-filemanager')?>"
						class="ui-icon ui-icon-circle-triangle-n"></span><span
						title="<?php _e('Expand all', 'nm-filemanager')?>"
						class="ui-icon ui-icon-circle-triangle-s"></span></span> <span
						class="clearfix"></span>
				</h3>
				<div class="inside" style="background-color: #fff;">
					<ul id="meta-input-holder">
					<?php render_existing_form_meta($form_meta, $nmfilemanager -> inputs)?>
					</ul>
				</div>
			</div>
		</div>

		<div class="clearfix"></div>
	</div>
</div>

<!-- ui dialogs -->
<div id="remove-meta-confirm"
	title="<?php _e('Are you sure?', 'nm-filemanager')?>">
	<p>
		<span class="ui-icon ui-icon-alert"
			style="float: left; margin: 0 7px 20px 0;"></span>
  <?php _e('Are you sure to remove this input field?', 'nm-filemanager')?></p>
</div>

<?php
function render_input_settings($settings, $values = '') {
	
	//filemanager_pa($values);
	$setting_html = '<table>';
	foreach ( $settings as $meta_type => $data ) {
		
		$input_data_options	 = (isset( $data ['options'] ) ? $data ['options'] : '');
		$colspan	= ($data ['type'] == 'html-conditions' ? 'colspan="2"' : '' );
		
		$setting_html .= '<tr>';
		$setting_html .= '<td class="table-column-title">' . $data ['title'] . '</td>';
		
		$data_values = NULL;
		if ($values){
			$data_values = (isset( $values [$meta_type] ) ? $values [$meta_type] : NULL );
		}
		$setting_html .= '<td '.$colspan.' class="table-column-input" data-type="' . $data ['type'] . '" data-name="' . $meta_type . '">' . render_input_types ( $data ['type'], $meta_type, $data_values, $input_data_options ) . '</td>';
		
		//removing the desc column for type: html-conditions
		if ($data ['type'] != 'html-conditions') {
			$setting_html .= '<td class="table-column-desc">' . $data ['desc'] . '</td>';;
		}
		
		$setting_html .= '</tr>';
	}
	
	$setting_html .= '</table>';
	
	return $setting_html;
}

/*
 * this function is rendring input field for settings
 */
function render_input_types($type, $name, $value = '', $options = '') {
	global $nmfilemanager;
	$html_input = '';
	
	//filemanager_pa($value);
	
	if(!is_array($value))
		$value = stripslashes($value);
	
	switch ($type) {
		
		case 'text' :
			$html_input .= '<input type="text" name="' . $name . '" value="' . $value . '">';
			break;
		
		case 'textarea' :
			$html_input .= '<textarea name="' . $name . '">' . $value . '</textarea>';
			break;
		
		case 'select' :
			$html_input .= '<select name="' . $name . '">';
			if ( is_array( $options ) ) {
			foreach ( $options as $key => $val ) {
				$selected = ($key == $value) ? 'selected="selected"' : '';
				$html_input .= '<option value="' . $key . '" ' . $selected . '>' . $val . '</option>';
			}
			}
			$html_input .= '</select>';
			break;
		
		case 'checkbox' :
			
			$checked = '';
			if ($options) {
				foreach ( $options as $key => $val ) {
					
					parse_str ( $value, $saved_data );
					if ($saved_data ['editing_tools']) {
						if (in_array($key, $saved_data['editing_tools'])) {
							$checked = 'checked="checked"';
						}else{
							$checked = '';
						}
					}
					// $html_input .= '<option value="' . $key . '" ' . $selected . '>' . $val . '</option>';
					$html_input .= '<input type="checkbox" value="' . $key . '" name="' . $name . '[]" ' . $checked . '> ' . $val . '<br>';
				}
			} else {
				if ($value)
					$checked = 'checked = "checked"';
				$html_input .= '<input type="checkbox" name="' . $name . '" ' . $checked . '>';
			}
			break;
			
		case 'html-conditions' :
			
			//filemanager_pa($value);
			$rule_i = 1;
			if($value){
				
	
					$visibility_show = ($value['visibility'] == 'Show') ? 'selected="selected"' : '';
					$visibility_hide = ($value['visibility'] == 'Hide') ? 'selected="selected"' : '';
					
					$html_input	 = '<select name="condition_visibility">';
					$html_input .= '<option '.$visibility_show.'>'.__('Show','nm-filemanager').'</option>';
					$html_input .= '<option '.$visibility_hide.'>'.__('Hide', 'nm-filemanager').'</option>';
					$html_input	.= '</select> ';
					
					
					$html_input .= __('only if', 'nm-filemanager');
					
					$bound_all = ($value['bound'] == 'All') ? 'selected="selected"' : '';
					$bound_any = ($value['bound'] == 'Any') ? 'selected="selected"' : '';
					
					$html_input	.= '<select name="condition_bound">';
					$html_input 	.= '<option '.$bound_all.'>'.__('All','nm-filemanager').'</option>';
					$html_input .= '<option '.$bound_any.'>'.__('Any', 'nm-filemanager').'</option>';
					$html_input	.= '</select> ';
						
					$html_input .= __(' of the following matches', 'nm-filemanager');
					
					
				foreach ($value['rules'] as $condition){
					
					$condition_elements = (isset($condition['elements']) ? $condition['elements'] : '' );
					$condition_elements_values = (isset($condition['element_values']) ? $condition['element_values'] : '' );
					
					// conditional elements
					$html_input .= '<div class="filemanager-rules" id="rule-box-'.$rule_i.'">';
					$html_input .= '<br><strong>'.__('Rule # ', 'nm-filemanager') . $rule_i++ .'</strong><br>';
					$html_input .= '<select name="condition_elements" data-existingvalue="'.$condition_elements.'" onchange="load_conditional_values(this)"></select>';
					
					// is
					
					$operator_is 		= ($condition['operators'] == 'is') ? 'selected="selected"' : '';
					$operator_not 		= ($condition['operators'] == 'not') ? 'selected="selected"' : '';
					$operator_greater 	= ($condition['operators'] == 'greater then') ? 'selected="selected"' : '';
					$operator_less 		= ($condition['operators'] == 'less then') ? 'selected="selected"' : '';
					
					$html_input .= '<select name="condition_operators">';
					$html_input	.= '<option '.$operator_is.'>'.__('is','nm-filemanager').'</option>';
					$html_input .= '<option '.$operator_not.'>'.__('not', 'nm-filemanager').'</option>';
					$html_input .= '<option '.$operator_greater.'>'.__('greater then', 'nm-filemanager').'</option>';
					$html_input .= '<option '.$operator_less.'>'.__('less then', 'nm-filemanager').'</option>';
					$html_input	.= '</select> ';
					
					// conditional elements values
					$html_input .= '<select name="condition_element_values" data-existingvalue="'.$condition_elements_values.'"></select>';
					$html_input	.= '<img class="add_rule" src="'.$nmfilemanager->plugin_meta['url'].'/images/plus.png" title="add rule" alt="add rule" style="cursor:pointer; margin:0 3px;">';
					$html_input	.= '<img class="remove_rule" src="'.$nmfilemanager->plugin_meta['url'].'/images/minus.png" title="remove rule" alt="remove rule" style="cursor:pointer; margin:0 3px;">';
					$html_input .= '</div>';
					
				}
			}else{

					
				$html_input	 = '<select name="condition_visibility">';
				$html_input .= '<option>'.__('Show','nm-filemanager').'</option>';
				$html_input .= '<option>'.__('Hide', 'nm-filemanager').'</option>';
				$html_input	.= '</select> ';
					
				$html_input	.= '<select name="condition_bound">';
				$html_input .= '<option>'.__('All','nm-filemanager').'</option>';
				$html_input .= '<option>'.__('Any', 'nm-filemanager').'</option>';
				$html_input	.= '</select> ';
					
				$html_input .= __(' of the following matches', 'nm-filemanager');
				// conditional elements
				
				$html_input .= '<div class="filemanager-rules" id="rule-box-'.$rule_i.'">';
				$html_input .= '<br><strong>'.__('Rule # ', 'nm-filemanager') . $rule_i++ .'</strong><br>';
				if ( isset( $condition['elements'] ) )
					$html_input .= '<select name="condition_elements" data-existingvalue="'.$condition['elements'].'" onchange="load_conditional_values(this)"></select>';
					
				// is
					
				$html_input .= '<select name="condition_operators">';
				$html_input	.= '<option>'.__('is','nm-filemanager').'</option>';
				$html_input .= '<option>'.__('not', 'nm-filemanager').'</option>';
				$html_input .= '<option>'.__('greater then', 'nm-filemanager').'</option>';
				$html_input .= '<option>'.__('less then', 'nm-filemanager').'</option>';
				$html_input	.= '</select> ';
					
				// conditional elements values
				if ( isset( $condition['element_values'] ) )
					$html_input .= '<select name="condition_element_values" data-existingvalue="'.$condition['element_values'].'"></select>';
				$html_input	.= '<img class="add_rule" src="'.$nmfilemanager->plugin_meta['url'].'/images/plus.png" title="add rule" alt="add rule" style="cursor:pointer; margin:0 3px;">';
				$html_input	.= '<img class="remove_rule" src="'.$nmfilemanager->plugin_meta['url'].'/images/minus.png" title="remove rule" alt="remove rule" style="cursor:pointer; margin:0 3px;">';
				$html_input .= '</div>';
			}

			break;
			
			case 'pre-images' :
				
				//$html_input	.= '<textarea name="pre_upload_images">'.$pre_uploaded_images.'</textarea>';
				$html_input	.= '<div class="pre-upload-box">';
				$html_input	.= '<input name="pre_upload_image_button" type="button" value="'.__('Select/Upload Image', 'nm-personalizedproduct').'" />';
				// nm_personalizedproduct_pa($value);
				if ( is_array($value) && $value ) {
					foreach ($value as $pre_uploaded_image){
				
						$html_input .='<table>';
						$html_input .= '<tr>';
						$html_input .= '<td><img width="75" src="'.$pre_uploaded_image['link'].'">';
						$html_input .= '<input type="hidden" name="pre-upload-link" value="'.$pre_uploaded_image['link'].'"></td>';
						$html_input .= '<td><input style="width:100px" type="text" value="'.stripslashes($pre_uploaded_image['title']).'" name="pre-upload-title"><br>';
						$html_input .= '<input style="width:100px; color:red" name="pre-upload-delete" type="button" class="button" value="Delete"><br>';
						$html_input .= '</td></tr>';
						$html_input .= '</table><br>';
				
					}
					//$pre_uploaded_images = $value;
				}
				
				$html_input .= '</div>';
				
			break;
	}
	
	return $html_input;
}

/*
 * this function is rendering the existing form meta
 */
function render_existing_form_meta($form_meta, $types) {
	if ($form_meta) {
		foreach ( $form_meta as $key => $meta ) {
			
			$type = $meta ['type'];
			
			//filemanager_pa($types);
			
			echo '<li data-inputtype="' . $type . '"><div class="postbox">';
			echo '<h3><span class="top-heading-text">' . $meta ['title'] . ' (' . $type . ')</span>';
			echo '<span class="top-heading-icons ui-icon ui-icon-carat-2-n-s"></span>';
			echo '<span class="top-heading-icons ui-icon ui-icon-trash"></span>';
			echo '<span style="clear:both;display:block"></span></h3>';
			
			echo render_input_settings ( $types[$type] -> settings, $meta );
			
			echo '</div></li>';
		}
	}
}


?>