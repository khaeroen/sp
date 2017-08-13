jQuery(function($) {

	// ================== new meta form creator ===================

	$("#filemanager-tabs").tabs();
	
	var meta_removed;
	
	//attaching hide and delete events for existing meta data
	$("#file-meta-input-holder li").each(function(i, item){
		$(item).find(".ui-icon-carat-2-n-s").click(function(e) {
			$(item).find("table").slideToggle(300);
		});
		// for delete box
		$(item).find(".ui-icon-trash").click(function(e) {
			$("#remove-meta-confirm").dialog("open");
			meta_removed = $(item);
		});	
	});
	
	$('.ui-icon-circle-triangle-n').click(function(e){
		$("#file-meta-input-holder li").find('table').slideUp();
	});
	$('.ui-icon-circle-triangle-s').click(function(e){
		$("#file-meta-input-holder li").find('table').slideDown();
	});
	
	
	
	$("#file-meta-input-holder").sortable({
		revert : true,
		stop : function(event, ui) {
			// console.log(ui);

			// only attach click event when dropped from right panel
			if (ui.originalPosition.left > 20) {
				$(ui.item).find(".ui-icon-carat-2-n-s").click(function(e) {
					$(this).parent('.postbox').find("table").slideToggle(300);
				});

				// for delete box
				$(ui.item).find(".ui-icon-trash").click(function(e) {
					$("#remove-meta-confirm").dialog("open");
					meta_removed = $(ui.item);
				});
			}
		}
	});

	// =========== remove dialog ===========
	$("#remove-meta-confirm").dialog({
		resizable : false,
		height : 160,
		autoOpen : false,
		modal : true,
		buttons : {
			"Remove" : function() {
				$(this).dialog("close");
				meta_removed.remove();
			},
			Cancel : function() {
				$(this).dialog("close");
			}
		}
	});

	$("#nm-input-types li").draggable(
			{
				connectToSortable : "#file-meta-input-holder",
				helper : "clone",
				revert : "invalid",
				stop : function(event, ui) {
					

					$('.ui-sortable .ui-draggable').removeClass(
							'input-type-item').find('div').addClass('postbox');

					// now replacing the icons with arrow
					$('.postbox').find('.ui-icon-arrow-4').removeClass(
							'ui-icon-arrow-4').addClass('ui-icon-carat-2-n-s');
					$('.postbox').find('.ui-icon-placehorder').removeClass(
							'ui-icon-placehorder').addClass(
							'ui-icon ui-icon-trash');

				}
			});

	// ================== new meta form creator ===================

	// add validation message if required
	$('input:checkbox[name="meta-required"]').change(function() {

		if ($(this).is(':checked')) {
			$(this).parent().find('span').show();
		} else {
			$(this).parent().find('span').hide();
		}
	});

	// increaing/saming the width of section's element
	$(".the-section").find('input, select, textarea').css({
		'width' : '35%'
	});

	// meta table setting first colum to 20%

	$('input:checkbox[name="allow_file_upload"]').change(function() {

		if ($(this).is(':checked')) {
			$('#file-upload-settings').show();
		} else {
			$('#file-upload-settings').hide();
		}
	});

	// making table sortable
	// make table rows sortable
	$('#nm-file-meta-admin tbody').sortable(
			{
				start : function(event, ui) {
					// fix firefox position issue when dragging.
					if (navigator.userAgent.toLowerCase().match(/firefox/)
							&& ui.helper !== undefined) {
						ui.helper.css('position', 'absolute').css('margin-top',
								$(window).scrollTop());
						// wire up event that changes the margin whenever the
						// window scrolls.
						$(window).bind(
								'scroll.sortableplaylist',
								function() {
									ui.helper.css('position', 'absolute')
											.css('margin-top',
													$(window).scrollTop());
								});
					}
				},
				beforeStop : function(event, ui) {
					// undo the firefox fix.
					if (navigator.userAgent.toLowerCase().match(/firefox/)
							&& ui.offset !== undefined) {
						$(window).unbind('scroll.sortableplaylist');
						ui.helper.css('margin-top', 0);
					}
				},
				helper : function(e, ui) {
					ui.children().each(function() {
						$(this).width($(this).width());
					});
					return ui;
				},
				scroll : true,
				stop : function(event, ui) {
					// SAVE YOUR SORT ORDER
				}
			}).disableSelection();

	// condtions handling
	populate_conditional_elements();
	
	$("#form-meta-setting img.add_rule").live("click", function(){
		
		var $div    = $(this).closest('div');
		var $clone = $div.clone();
		$clone.find('strong').val('Rule just added');
		
		var $td = $div.closest('td');
		$td.append($clone);
	});
	
	$("#form-meta-setting img.remove_rule").live("click", function(){
		
		var $div    = $(this).closest('div');
		var $td = $div.closest('td');
		if($td.find('div').length > 1)
			$div.remove();
		else
			alert('Insaan ban');
	});
	
	
/* ============== pre uploaded images 1- Media uploader launcher ================= */
	
	var $uploaded_image_container;
	
	$('input:button[name="pre_upload_image_button"]').live('click', function() {
		
		var pre_box_id = $(this).attr('data-upload-for');
		
		$uploaded_image_container = $(this).closest('div');
		tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
		return false;
	});
		 
	// user inserts file into post. only run custom if user started process using the above process
    // window.send_to_editor(html) is how wp would normally handle the received data

    window.original_send_to_editor = window.send_to_editor;
    window.send_to_editor = function(html){
    	
    	var fileurl = $(html).attr('href');
        if (fileurl) {
        	
        	var existing_images;
        	var image_box 	 = '<table>';
        	image_box 		+= '<tr>';
        	image_box 		+= '<td><img width="75" src="'+fileurl+'">';
        	image_box 		+= '<input type="hidden" name="pre-upload-link" value="'+fileurl+'"></td>';
        	image_box 		+= '<td><input placeholder="title" style="width:100px" type="text" name="pre-upload-title"><br>';
        	image_box 		+= '<input style="width:100px; color:red" name="pre-upload-delete" type="button" class="button" value="Delete"><br>';
        	image_box 		+= '</td></tr>';
        	image_box 		+= '</table><br>';
        	
        	$uploaded_image_container.append(image_box);
        	
            tb_remove();
       } else {
    	   window.original_send_to_editor(html);
       }
       
    }
    
    $('input:button[name="pre-upload-delete"]').live('click', function(){
    	
    	$(this).closest('table').remove();
    });
    
    
});

// saving form meta
function save_file_meta() {

 
	jQuery("#nm-saving-form").html('<img src="'+nm_filemanager_vars.doing+'" />');
	
	var form_meta_values = new Array(); // {}; //Array();
	jQuery("#file-meta-input-holder li")
			.each(
					function(i, item) {

						var inner_array = {};
						inner_array['type'] = jQuery(item).attr(
								'data-inputtype');

						jQuery(this)
								.find('td.table-column-input')
								.each(
										function(i, col) {

											var meta_input_type = jQuery(col)
													.attr('data-type');
											var meta_input_name = jQuery(col)
													.attr('data-name');
											var cb_value = '';
											if (meta_input_type == 'checkbox') {
												cb_value = (jQuery(this).find('input:checkbox[name="' + meta_input_name + '"]:checked').val() === undefined ? '' : jQuery(this).find('input:checkbox[name="' + meta_input_name + '"]:checked').val());
									inner_array[meta_input_name] = cb_value;
											} else if (meta_input_type == 'textarea') {
												inner_array[meta_input_name] = jQuery(
														this)
														.find(
																'textarea[name="'
																		+ meta_input_name
																		+ '"]')
														.val();
											} else if (meta_input_type == 'select') {
												inner_array[meta_input_name] = jQuery(
														this)
														.find(
																'select[name="'
																		+ meta_input_name
																		+ '"]')
														.val();
											} else if (meta_input_type == 'html-conditions') {
												
												var all_conditions = {};
												var the_conditions = new Array();	//{};
												
												all_conditions['visibility'] = jQuery(
														this)
														.find(
																'select[name="condition_visibility"]')
														.val();
												all_conditions['bound'] = jQuery(
														this)
														.find(
																'select[name="condition_bound"]')
														.val();
												jQuery(this).find('div').each(function(i, div_box){
												
													var the_rule = {};
													
													the_rule['elements'] = jQuery(
															this)
															.find(
																	'select[name="condition_elements"]')
															.val();
													the_rule['operators'] = jQuery(
															this)
															.find(
																	'select[name="condition_operators"]')
															.val();
													the_rule['element_values'] = jQuery(
															this)
															.find(
																	'select[name="condition_element_values"]')
															.val();
													
													the_conditions.push(the_rule);
												});
												
												all_conditions['rules'] = the_conditions;
												inner_array[meta_input_name] = all_conditions;
											}else if (meta_input_type == 'pre-images') {
												var all_preuploads = new Array();
												jQuery(this).find('div.pre-upload-box table').each(function(i, preupload_box){
													var pre_upload_obj = {	link: jQuery(preupload_box).find('input[name="pre-upload-link"]').val(),
															title: jQuery(preupload_box).find('input[name="pre-upload-title"]').val(),
															price: jQuery(preupload_box).find('input[name="pre-upload-price"]').val(),};
													
													all_preuploads.push(pre_upload_obj);
												});
												
												inner_array['images'] = all_preuploads;
												
											} else {
												inner_array[meta_input_name] = jQuery.trim(jQuery(this).find('input[name="'+ meta_input_name+ '"]').val())
												// inner_array.push(temp);
											}

										});

						form_meta_values.push(inner_array);

					});

	//console.log(form_meta_values); return false;
	// ok data is collected, so send it to server now Huh?


	do_action = 'nm_filemanager_save_file_meta';

	var server_data = {
		action 					: do_action,
		file_meta : form_meta_values
	}
	
	jQuery.post(ajaxurl, server_data, function(resp) {

		console.log(resp);
		if (resp.status == 'success') {

			jQuery("#nm-saving-form").html(resp.message);
			window.location.reload(true);
		}

	}, 'json');
	
}

function update_options(options) {

	var opt = jQuery.parseJSON(options);

	jQuery("#filemanager-settigns-saving").html('<img src="'+nm_filemanager_vars.doing+'" />');
	/*
	 * extractElementData defined in nm-globals.js
	 */
	var data = extractElementData(opt);

	if (data.bug) {
		// jQuery("#reply_err").html('Red are required');
		alert('bug here');
	} else {

		/*
		 * [1]
		 */
		data.action = 'nm_filemanager_save_settings';

		jQuery.post(ajaxurl, data, function(resp) {

			jQuery("#filemanager-settigns-saving").html(resp);
			window.location.reload(true);

		});
	}

}

function are_sure(form_id) {

	var a = confirm('Are you sure to delete this file?');
	if (a) {
		//jQuery("#del-file-" + form_id).attr("src", nm_filemanager_vars.doing);

		jQuery.post(ajaxurl, {
			action : 'nm_filemanager_delete_meta',
			formid : form_id
		}, function(resp) {
			// alert(data);
			alert(resp);
			window.location.reload(true);

		});

	}

}


/* ================ below script is for admin settings framework ============== */

/*
 * extract data from html elements
 */

function extractElementData(elements) {

	var data = new Object;

	data.bug = false;
	jQuery.each(elements,
			function(i, item) {

			if(item.req == undefined || item.req == 0){
				item.req = false;
				
			}else{
				item.req = true;
				
			}
			
				switch (item.type) {
				
				case 'text':

					data[i] = jQuery("input[name^='" + i + "']").val();
					if(jQuery("input[name^='" + i + "']").val() == '' && item.req){
						jQuery("input[name^='" + i + "']").css('border', 'red 1px solid');
						data.bug = true;
						/*alert(item.type+' is required');*/
					}
					break;

				case 'select':

					data[i] = jQuery("select[name^='" + i + "']").val();
					if(jQuery("select[name^='" + i + "']").val() == '' && item.req){
						jQuery("select[name^='" + i + "']").css('border', 'red 1px solid');
						data.bug = true;
						/*alert(item.type+' is required');*/
					}
					break;

				case 'checkbox':
					
					var checkedVals = [];
					jQuery('input:checkbox[name^="' + i + '"]:checked').each(function() {
						checkedVals.push(jQuery(this).val());
					});
					
					data[i] = (checkedVals.length == 0) ? null : checkedVals;
					
					if (!jQuery("input:checkbox[name^='" + i + "']").is(':checked') && item.req){
						
						jQuery("input:checkbox[name^='" + i + "']").parent('label').css('color', 'red');
						data.bug = true;
						/*alert(item.type+' is required');*/
					}
						
					break;

				case 'radio':

					data[i] = jQuery(
							"input:radio[name^='" + i + "']:checked").val();
					if (!jQuery("input:radio[name^='" + i + "']").is(':checked') && item.req){
											
						jQuery("input:radio[name^='" + i + "']").css('border', 'red 1px solid');
						data.bug = true;
						alert(item.type+' is required');
					}
					break;
					
				case 'textarea':

					data[i] = jQuery("textarea[name^='" + i + "']").val();
					
					if(jQuery("textarea[name^='" + i + "']").val() == '' && item.req){
						jQuery("textarea[name^='" + i + "']").css('border', 'red 1px solid');
						data.bug = true;
						/*alert(item.type+' is required');*/
					}
					break;
				}

			});

	return data;
}


/*
 * function checking the checkbox for current value
 * current value: json object
 * @Author: Najeeb
 * 13 Oct, 2012
 */

function setChecked(elementName, currentValue){
	
	var elementCB = jQuery('input:checkbox[name="' + elementName + '"]');
	
	var currentValues = jQuery.parseJSON(currentValue);
	
	
	//console.log(currentValues);
	
	jQuery.each(elementCB, function(i, item){
		
		//console.log(item.id);
		var current_cb_id = item.id;
		
		jQuery.each(currentValues, function(i, item){
			
			//console.log(item + jQuery("#"+current_cb_id).attr('value'));
			if(jQuery("#"+current_cb_id).attr('value') == item){
				
				jQuery("#"+current_cb_id).attr('checked', true);
			}else{
				if ( jQuery("#"+current_cb_id).attr('checked') == true)
					jQuery("#"+current_cb_id).attr('checked', false);
			}
			//jQuery('input:checkbox[value="' + item + '"]').attr("checked", "checked");
		});
	});
	
	
	
}

/*
 * function checking the RADIO for current value
 * current value: single
 * @Author: Najeeb
 * 3 July, 2012
 */

function setCheckedRadio(elementName, currentValue) {

	var elementRadio = jQuery('input:radio[name="' + elementName + '"]');

	//console.log(elementRadio);
	jQuery.each(elementRadio, function(i, item) {

		//console.log(item.id);
		var current_radio_id = item.id;
		
		if (jQuery("#" + current_radio_id).attr('value') == currentValue) {

			jQuery("#" + current_radio_id).attr('checked', true);
		} else {
			if (jQuery("#" + current_radio_id).attr('checked') == true)
				jQuery("#" + current_radio_id).attr('checked', false);
		}
						
	});

}
