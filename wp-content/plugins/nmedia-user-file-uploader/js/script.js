var file_count_filemanager = 1;
var uploader_filemanager;

jQuery(function($){
	
	$('#user-files').dataTable(get_dt_options());
	
	// Handling Uploading
	
	$('.tabs .tab-links a').on('click', function(e)  {
        var currentAttrValue = $(this).attr('href');
 
        // Show/Hide Tabs
        $('.tabs ' + currentAttrValue).show().siblings().hide();
 
        // Change/remove current tab to active
        $(this).parent('li').addClass('active').siblings().removeClass('active');
 
        e.preventDefault();
    });

    $('#fileupload-button').on('click', function(e)  {
    	e.preventDefault();
    	$('#form-save-new-file').submit();
    });
    

    // file uploader script
    var $filelist_DIV = $('#filelist-uploadfile');
    uploader_filemanager = new plupload.Uploader({
		runtimes 			: nm_filemanager_vars.runtime,
		browse_button 		: 'select-button', // you can pass in id...
		container			: 'nm-uploadfile', // ... or DOM Element itself
		drop_element		: 'nm-uploadfile',
		url 				: nm_filemanager_vars.ajaxurl,
		multipart_params 	: {'action' : 'nm_filemanager_upload_file'},
		max_file_size 		: nm_filemanager_vars.filesize,
		max_file_count 		: nm_filemanager_vars.filesallowed,
	    
	    chunk_size: '2mb',
		
	    // Flash settings
		flash_swf_url 		: nm_filemanager_vars.plupload_swf,
		// Silverlight settings
		silverlight_xap_url : nm_filemanager_vars.plupload_xap,
		
		filters : {
			mime_types: [
				{title : "Filetypes", extensions : nm_filemanager_vars.filetypes}
			]
		},
		
		init: {
			PostInit: function() {
				$filelist_DIV.html('');

			},

			FilesAdded: function(up, files) {

				var files_added = up.files.length;
				var max_count_error = false;

			    plupload.each(files, function (file) {

			    	if(file_count_filemanager <= uploader_filemanager.settings.max_file_count){
			        
			        	// Code to add pending file details, if you want
			            add_thumb_box(file, $filelist_DIV, up);
			            setTimeout('uploader_filemanager.start()', 100); 
			            file_count_filemanager++;
			      		
			        }else{
			            max_count_error = true;
			        }
			        
			        
			    });

			    
			    if(max_count_error){
			    	alert(nm_filemanager_vars.filesallowed + nm_filemanager_vars.message_max_files_limit);
			    }
				
			},
			
			FileUploaded: function(up, file, info){
				
				/* console.log(up);
				console.log(file);*/

				var obj_resp = $.parseJSON(info.response);
				if(obj_resp.status === 'error'){
					
					alert(obj_resp.message);
					window.location.reload(true);
				}
				
				$filelist_DIV.find('#u_i_c_' + file.id).find('.file-thumb-title-description').show();
				$('#fileupload-button-bar').show();
				
				var file_thumb 	= '';
							
				// checking if uploaded file is thumb
				ext = obj_resp.file_name.substring(obj_resp.file_name.lastIndexOf('.') + 1);					
				ext = ext.toLowerCase();
				
				if(ext == 'png' || ext == 'gif' || ext == 'jpg' || ext == 'jpeg'){

					$.each(obj_resp.thumb_meta, function(i, item){
						file_thumb = nm_filemanager_vars.file_upload_path_thumb + item.name;
						$filelist_DIV.find('#u_i_c_' + file.id).find('.u_i_c_thumb').append('<img width="150" src="'+file_thumb+ '" id="thumb_'+file.id+'" />');
					});
					
					
					
					var file_full 	= nm_filemanager_vars.file_upload_path + obj_resp.file_name;
					// thumb thickbox only shown if it is image
					$filelist_DIV.find('#u_i_c_' + file.id).find('.u_i_c_thumb').append('<div style="display:none" id="u_i_c_big' + file.id + '"><img src="'+file_full+ '" /></div>');
					
					// zoom effect
					$filelist_DIV.find('#u_i_c_' + file.id).find('.u_i_c_tools_zoom').append('<a href="#TB_inline?width=900&height=500&inlineId=u_i_c_big'+file.id+'" class="thickbox" title="'+obj_resp.file_name+'"><img width="15" src="'+nm_filemanager_vars.plugin_url+'/images/zoom.png" /></a>');
					is_image = true;
				}else{
					
					
					file_thumb = nm_filemanager_vars.plugin_url+'/images/file.png';
					$filelist_DIV.find('#u_i_c_' + file.id).find('.u_i_c_thumb').html('<img src="'+file_thumb+ '" id="thumb_'+file.id+'" />');
					is_image = false;
				}

				//file name	
				$filelist_DIV.find('#u_i_c_' + file.id).find('.progress_bar').html(obj_resp.file_name);
				
				// adding checkbox input to Hold uploaded file name as array
				$filelist_DIV.append('<input style="display:none" checked="checked" type="checkbox" value="'+obj_resp.file_name+'" name="uploaded_files['+file.id+']" />');
			},

			UploadProgress: function(up, file) {
				//document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
				//console.log($filelist_DIV.find('#' + file.id).find('.progress_bar_runner'));
				$filelist_DIV.find('#u_i_c_' + file.id).find('.progress_bar_number').html(file.percent + '%');
				$filelist_DIV.find('#u_i_c_' + file.id).find('.progress_bar_runner').css({'display':'block', 'width':file.percent + '%'});
			},

			Error: function(up, err) {
				//document.getElementById('console').innerHTML += "\nError #" + err.code + ": " + err.message;
				alert("\nError #" + err.code + ": " + err.message);
			}
		}
		

	});

    uploader_filemanager.init();
    
    // delete file
			$(".nm-file-thumb").find('.u_i_c_tools_del > a').live('click', function(){

				// console.log($(this));
				var a = confirm(nm_filemanager_vars.delete_file_message);
				if(a){
					// it is removing from uploader instance
					var fileid = $(this).attr("data-fileid");
					uploader_filemanager.removeFile(fileid);

					var filename  = jQuery('input:checkbox[name="uploaded_files['+fileid+']"]').val();
					
					// it is removing physically if uploaded
					jQuery("#u_i_c_"+fileid).find('img').attr('src', nm_filemanager_vars.plugin_url+'/images/loading.gif');
					
						var data = {action: 'nm_filemanager_delete_file_new', file_name: filename};
					
						jQuery.post(nm_filemanager_vars.ajaxurl, data, function(resp){
							alert(resp);
							jQuery("#u_i_c_"+fileid).hide(500).remove();

							// it is removing for input Holder
							jQuery('input:checkbox[name="uploaded_files['+fileid+']"]').remove();
						
					});
				}
			});
});

function add_thumb_box(file, $filelist_DIV){
		
		var inner_html 		= '<div class="file-thumb-title-description" style="display:none">';
				inner_html 		+= '<div class="nm-file-thumb">';
				inner_html 		+= '<div class="u_i_c_thumb"></div>';
				inner_html		+= '<span class="u_i_c_tools_zoom"></span> ';
				inner_html		+= '<span class="u_i_c_tools_del"><a href="javascript:;" data-fileid="' + file.id+'" title="Delete"><img width="15" src="'+nm_filemanager_vars.plugin_url+'/images/delete.png" /></a></span>';
				        
		    	inner_html		+= '</div>';
		    
			    inner_html		+= '<div class="nm-file-title-description">';
			    inner_html		+= '<input name="file_title['+file.id+']" type="text" placeholder="'+nm_filemanager_vars.placeholder+'" />';
			    inner_html		+= '<textarea name="file_description['+file.id+']" rows="5" placeholder="'+nm_filemanager_vars.description+'"></textarea>';
			    inner_html		+= '</div>';
		    
		    	inner_html		+= '<div class="clear"></div>';
		    	
		    inner_html		+= '</div>';
			
			inner_html		+= '<div class="progress_bar"><span class="progress_bar_runner"></span><span class="progress_bar_number">(' + plupload.formatSize(file.size) + ')<span></div>';
		
		jQuery( '<div />', {
			'id'	: 'u_i_c_'+file.id,
			'class'	: 'u_i_c_box',
			'html'	: inner_html,
			
		}).appendTo($filelist_DIV);

		// clearfix
		// 1- removing last clearfix first
		$filelist_DIV.find('.u_i_c_box_clearfix').remove();
		
		jQuery( '<div />', {
			'class'	: 'u_i_c_box_clearfix',				
		}).appendTo($filelist_DIV);
	}

function save_uploaded_files(form) {

	//console.log(form);
	var is_validated = validate_file_data();
	if ( is_validated ) {
		jQuery(form).find("#nm-saving-file").html(
				'<img src="' + nm_filemanager_vars.doing + '">');

		var data = jQuery(form).serialize();
		data = data + '&action=nm_filemanager_save_file_data';
		
		//console.log(data); return false;

		jQuery.post(nm_filemanager_vars.ajaxurl, data, function(resp) {

			//console.log(resp); return false;
			
			if(resp.status == 'error'){
				jQuery(form).find("#nm-saving-file").html(resp.message).css('color', 'red');
			}else{
				if(get_option('_redirect_url') != '')
					window.location = get_option('_redirect_url');
				else{
					jQuery(form).find("#nm-saving-file").html(resp.message).css('color', 'green');
					window.location.reload(true);
				}
			}
		}, 'json');

	} 
	return false;
}


/**
 * validating the file data
 * return true if ok
 */
function validate_file_data(){
	var total_files = jQuery('input:checkbox[name^="uploaded_files"]').length;
	var title_text = jQuery('.filelist').find('input[type="text"]');
	var is_ok = true;

	if( !get_option('_min_files') == '' && total_files < get_option('_min_files') ){
		is_ok = false;
		alert('You must upload atleast '+get_option('_min_files')+' files.');
	} else {	
		jQuery.each(title_text, function(i, item){
			
			jQuery(item).css({'border-color':'#000'});
			
			if( jQuery(item).val() == ''){
				is_ok = false;
				jQuery(item).css({'border-color':'#ff0000'});
			}
		});
	}
	return is_ok;
}

/**
 * saving file meta 
 */
function update_file_data(form) {

	//console.log(form);
	jQuery(form).find("#nm-saving-file-meta").html(
			'<img src="' + nm_filemanager_vars.doing + '">');
	
	var is_ok = validate_update_data(form);
	var file_ok = true;
	
	if (is_ok && file_ok) {

		var data = jQuery(form).serialize();
		data = data + '&action=nm_filemanager_update_file_data';
		
		jQuery.post(nm_filemanager_vars.ajaxurl, data, function(resp) {

			//console.log(resp); return false;
			
			if(resp.status == 'error'){
				jQuery(form).find("#nm-saving-file-meta").html(jQuery('input:hidden[name="_error_message"]').val()).css('color', 'red');
			}else{
				if(get_option('_redirect_url') != '')
					window.location = get_option('_redirect_url');
				else
					jQuery(form).find("#nm-saving-file-meta").html(resp.message).css('color', 'green');
				
				
			}
		}, 'json');

	} else {

		//show all sections if hidden
		jQuery(".nm-filemanager-box section").slideDown(200);
		
		jQuery(form).find("#nm-saving-file")
				.html('Please remove above Errors').css('color', 'red');
	}

	return false;
}

function validate_update_data(form){
	
	var form_data = jQuery.parseJSON( jQuery(form).attr('data-form') );
	var has_error = true;
	var error_in = '';
	
	jQuery.each( form_data, function( key, meta ) {
		
		var type = meta['type'];
		var error_message	= stripslashes( meta['error_message'] );
		
		console.log('typ e'+type+' error message '+error_message+'\n\n');
		  
		if(type === 'text' || type === 'textarea' || type === 'select' || type === 'email' || type === 'date'){
			
			var input_control = jQuery('#'+meta['data_name']);
			
			if(meta['required'] === "on" && jQuery(input_control).val() === ''){
				jQuery(input_control).closest('div').find('span.errors').html(error_message).css('color', 'red');
				has_error = false;
				error_in = meta['data_name']
			}else{
				jQuery(input_control).closest('div').find('span.errors').html('').css({'border' : '','padding' : '0'});
			}
		}else if(type === 'checkbox'){
			
			//console.log('im error in cb '+error_message);	
			if(meta['required'] === "on" && jQuery('input:checkbox[name="'+meta['data_name']+'[]"]:checked').length === 0){
				
				jQuery('input:checkbox[name="'+meta['data_name']+'[]"]').closest('div').find('span.errors').html(error_message).css('color', 'red');
				has_error = false;
			}else if(meta['min_checked'] != '' && jQuery('input:checkbox[name="'+meta['data_name']+'[]"]:checked').length < meta['min_checked']){
				jQuery('input:checkbox[name="'+meta['data_name']+'[]"]').closest('div').find('span.errors').html(error_message).css('color', 'red');
				has_error = false;
			}else if(meta['max_checked'] != '' && jQuery('input:checkbox[name="'+meta['data_name']+'[]"]:checked').length > meta['max_checked']){
				jQuery('input:checkbox[name="'+meta['data_name']+'[]"]').closest('div').find('span.errors').html(error_message).css('color', 'red');
				has_error = false;
			}else{
				
				jQuery('input:checkbox[name="'+meta['data_name']+'[]"]').closest('div').find('span.errors').html('').css({'border' : '','padding' : '0'});
				
				}
		}else if(type === 'radio'){
				
				if(meta['required'] === "on" && jQuery('input:radio[name="'+meta['data_name']+'"]:checked').length === 0){
					jQuery('input:radio[name="'+meta['data_name']+'"]').closest('div').find('span.errors').html(error_message).css('color', 'red');
					has_error = false;
					error_in = meta['data_name']
				}else{
					jQuery('input:radio[name="'+meta['data_name']+'"]').closest('div').find('span.errors').html('').css({'border' : '','padding' : '0'});
				}
		}else if(type === 'masked'){
			
			var input_control = jQuery('#'+meta['data_name']);
			
			if(meta['required'] === "on" && (jQuery(input_control).val() === '' || jQuery(input_control).attr('data-ismask') === 'no')){
				jQuery(input_control).closest('div').find('span.errors').html(error_message).css('color', 'red');
				has_error = false;
				error_in = meta['data_name'];
			}else{
				jQuery(input_control).closest('div').find('span.errors').html('').css({'border' : '','padding' : '0'});
			}
		}
		
	});
	
	//console.log( error_in ); return false;
	return has_error;
}

/**
 * this function extract values from setting 
 */
function get_option(key) {

	/*
	 * TODO: change plugin shortname
	 */
	var keyprefix = 'nm_filemanager';

	key = keyprefix + key;

	var req_option = '';

	jQuery.each(nm_filemanager_vars.settings, function(k, option) {

		// console.log(k);

		if (k == key)
			req_option = option;
	});

	// console.log(req_option);
	return req_option;
}
/**
 * this function confirms before deleting file 
 */
function confirmFirstDelete(url)
{
	var a = confirm('Are you sure to delete this file?');
	if(a)
	{
		window.location = url;
	}
}

/* sharing file with thick box dialog */
function share_file( file_name ){
	
var uri_string = encodeURI('action=nm_filemanager_share_file&width=800&height=500&filename='+file_name);
	
	var url = nm_filemanager_vars.ajaxurl + '?' + uri_string;
	tb_show(nm_filemanager_var.share_file_heading, url);
}

/* sharing file ajax function */
function send_files_email(form) {
	//console.log(form);
	jQuery("#sending-mail").show();
		if (jQuery("#shared_single_file").val() != "") 
			var files_to_send = jQuery("#shared_single_file").val();
		else
			var files_to_send = jQuery("#file-names").val();
			
		var data = {
			action: 'nm_filemanager_send_files_email',
			file_names: files_to_send,
			subject: jQuery("#subject").val(),
			email_to: jQuery("#email-to").val(),
			file_msg: jQuery("#file-msg").val()
		};
		//alert("done");
		jQuery.post(nm_filemanager_vars.ajaxurl, data, function(resp) {
			jQuery("#sending-mail").hide();
			alert(resp); 
			location.reload(true);
			//return false;
			
			
		});

	return false;
}


/* edit file meta with thick box dialog */
function edit_file_meta(postid){
	
var uri_string = encodeURI('action=nm_filemanager_edit_file_meta&width=800&postid='+postid);
	
	var url = nm_filemanager_vars.ajaxurl + '?' + uri_string;
	tb_show(nm_filemanager_vars.file_meta_heading, url);
}



function stripslashes (str) {
	  // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	  // +   improved by: Ates Goral (http://magnetiq.com)
	  // +      fixed by: Mick@el
	  // +   improved by: marrtins
	  // +   bugfixed by: Onno Marsman
	  // +   improved by: rezna
	  // +   input by: Rick Waldron
	  // +   reimplemented by: Brett Zamir (http://brett-zamir.me)
	  // +   input by: Brant Messenger (http://www.brantmessenger.com/)
	  // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
	  // *     example 1: stripslashes('Kevin\'s code');
	  // *     returns 1: "Kevin's code"
	  // *     example 2: stripslashes('Kevin\\\'s code');
	  // *     returns 2: "Kevin\'s code"
	  return (str + '').replace(/\\(.?)/g, function (s, n1) {
	    switch (n1) {
	    case '\\':
	      return '\\';
	    case '0':
	      return '\u0000';
	    case '':
	      return '';
	    default:
	      return n1;
	    }
	  });
	}

function get_dt_options(){
	
	var dt_options = { paging: false,
			 searching: false,
			 ordering:  false};
	
	return dt_options;
}