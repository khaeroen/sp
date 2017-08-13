jQuery(document).ready( function($) {
	
	$("#bsk_pdf_manager_categories_id").change( function() {
		var cat_id = $(this).val();
		var new_action = $("#bsk-pdf-manager-pdfs-form-id").attr('action') + '&cat=' + cat_id;
		
		$("#bsk-pdf-manager-pdfs-form-id").attr('action', new_action);
		
		$("#bsk-pdf-manager-pdfs-form-id").submit();
	});
	
	$("#doaction").click( function() {
		var cat_id = $("#bsk_pdf_manager_categories_id").val();
		var new_action = $("#bsk-pdf-manager-pdfs-form-id").attr('action') + '&cat=' + cat_id;
		
		$("#bsk-pdf-manager-pdfs-form-id").attr('action', new_action);
		
		return true;
	});
	
	$("#bsk_pdf_manager_category_save").click( function() {
		var cat_title = $("#cat_title_id").val();
		if ($.trim(cat_title) == ''){
			alert('Category title can not be NULL.');
			
			$("#cat_title_id").focus();
			return false;
		}
		
		$("#bsk-pdf-manager-category-edit-form-id").submit();
	});
	
	$("#bsk_pdf_manager_pdf_save_form").click( function() {
		//check category
		var category = $("#bsk_pdf_manager_pdf_edit_categories_id").val();
		if (category < 1){
			alert('Please select category.');
			$("#bsk_pdf_manager_pdf_edit_categories_id").focus();
			return false;
		}
		
		//check title
		var pdf_title = $("#bsk_pdf_manager_pdf_titile_id").val();
		if ($.trim(pdf_title) == ''){
			alert('PDF title can not be NULL.');
			$("#bsk_pdf_manager_pdf_titile_id").focus();
			return false;
		}
		
		//check file
		if ($("#bsk_pdf_manager_pdf_file_old_id").length > 0){
			var is_delete = $("#bsk_pdf_manager_pdf_file_rmv_id").attr('checked');
			if (is_delete){
				var file_name = $("#bsk_pdf_file_id").val();
				file_name = $.trim(file_name);
				if (file_name == ""){
					alert('Please select a new PDF to upload because you checked delete old one.');
					$("#bsk_pdf_file_id").focus();
					return false;
				}
			}
			
		}else{
			var file_name = $("#bsk_pdf_file_id").val();
			file_name = $.trim(file_name);
			if (file_name == ""){
				alert('Please select a file to upload.');
				$("#bsk_pdf_file_id").focus();
				return false;
			}
		}
		
		$("#bsk-pdf-manager-pdfs-form-id").submit();
	});
	
	if( $(".bsk-date").length > 0 ){
		$(".bsk-date").datepicker({
			dateFormat : 'yy-mm-dd'
		});
	}
	
	/* Pro tips */
	$(".bsk-pdf-pro-tip-viewer").mouseover(function(){
		var attr_id = $(this).attr("attrid");
		var attr_text = $("#" + attr_id).val();
		if( attr_text == "" ){
			return;
		}
		$(".bsk-pro-tips-box-tip").html( attr_text );
	});
	
	$(".bsk-pdf-pro-tip-viewer").mouseout(function(){
		var attr_text = $("#bsk_pdf_manager_hidden_tip_box_tip_ID").val();
		if( attr_text == "" ){
			return;
		}
		$(".bsk-pro-tips-box-tip").html( attr_text );
	});
	
	if( $("#tip_4_bsk_pdf_manager_bulk_change_category_view_id").length > 0 ){
		$("#tip_4_bsk_pdf_manager_bulk_change_category_view_id").insertAfter( "#doaction2" );
		$("#tip_4_bsk_pdf_manager_bulk_change_category_view_id").css( "margin-top", "5px" );
	}
	
	/* tab switch */
	$("#bsk_pdfm_setings_wrap_ID .nav-tab-wrapper a").click(function(){
		//alert( $(this).index() );
		$('#bsk_pdfm_setings_wrap_ID section').hide();
		$('#bsk_pdfm_setings_wrap_ID section').eq($(this).index()).show();
		
		$(".nav-tab").removeClass( "nav-tab-active" );
		$(this).addClass( "nav-tab-active" );
		
		return false;
	});
	
});
