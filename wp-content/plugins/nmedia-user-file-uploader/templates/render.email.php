<?php
/*
 * rendering product meta on product page
*/

global $nmfilemanager, $_REQUEST;

$submitted_data = $_REQUEST;
$uploaded_files = '';

unset($submitted_data['action']);
unset($submitted_data['nm_filemanager_nonce']);
unset($submitted_data['_wp_http_referer']);
unset($submitted_data['_sender_email']);
unset($submitted_data['_sender_name']);
unset($submitted_data['_subject']);
unset($submitted_data['_receiver_emails']);
unset($submitted_data['_reply_to']);
unset($submitted_data['_send_file_as']);


$single_form = $nmfilemanager -> get_forms( $submitted_data['_form_id'] );
$nmfilemanager -> allow_file_upload = $single_form -> allow_file_upload;

$existing_meta 		= json_decode( $single_form -> the_meta, true);

//print_r($submitted_data);

if($existing_meta){

	echo '<div id="nm-filemanager-box-'.$submitted_data['_form_id'].'" class="nm-filemanager-box">';

	/*
	 * forms extra information being sent hidden
	*/
	$row_size = 0;

	$started_section = '';


	foreach($existing_meta as $key => $meta)
	{
		
		$type = $meta['type'];		
		$name = strtolower(preg_replace("![^a-z0-9]+!i", "_", $meta['data_name']));
		

		if(($row_size + intval($meta['width'])) > 100 || $meta['type'] == 'section'){

			echo '<div style="clear:both; margin: 0;"></div>';

			if($meta['type'] == 'section'){
				$row_size = 100;
			}else{

				$row_size = intval( $meta['width'] );
			}

		}else{

			$row_size += intval( $meta['width'] );
		}


		$the_width = intval( $meta['width'] ) - 1 .'%';
		$the_margin = '1%';

		$field_label = $meta['title'];

		switch($type)
		{
			
case 'text':
?>

<p style="width: <?php echo $the_width?>; margin-right: <?php echo $the_margin?>; float:left">
	<label style="font-weight:bold" for="<?php echo $name?>"><?php echo $field_label?> </label> <br />
	<?php echo $submitted_data[$name]?>
</p>

<?php
break;

case 'masked':
	?>

<p style="width: <?php echo $the_width?>; margin-right: <?php echo $the_margin?>; float:left">
	<label style="font-weight:bold" for="<?php echo $name?>"><?php echo $field_label?> </label> <br />
	<?php echo $submitted_data[$name]?>
</p>

<?php
break;

case 'date':
?>

<p style="width: <?php echo $the_width?>; margin-right: <?php echo $the_margin?>; float:left">
	<label style="font-weight:bold" for="<?php echo $name?>"><?php echo $field_label?> </label> <br />
	<?php echo $submitted_data[$name]?>

</p>

<?php
break;
case 'email':
?>

<p style="width: <?php echo $the_width?>; margin-right: <?php echo $the_margin?>; float:left">
	<label style="font-weight:bold" for="<?php echo $name?>"><?php echo $field_label?> </label> <br />
	<?php echo $submitted_data[$name]?>
</p>

<?php
break;
case 'checkbox':
?>

<p style="width: <?php echo $the_width?>; margin-right: <?php echo $the_margin?>; float:left">
	<label style="font-weight:bold" for="<?php echo $name?>"><?php echo $field_label?> </label> <br />
	<?php echo implode(",", $submitted_data[$name])?>
</p>

<?php
break;
case 'select':
?>

<p style="width: <?php echo $the_width?>; margin-right: <?php echo $the_margin?>; float:left">
	<label style="font-weight:bold" for="<?php echo $name?>"><?php echo $field_label?> </label> <br />
	<?php echo $submitted_data[$name]?>
</p>

<?php
break;
case 'textarea':
?>

<p style="width: <?php echo $the_width?>; margin-right: <?php echo $the_margin?>; float:left">
	<label style="font-weight:bold" for="<?php echo $name?>"><?php echo $field_label?> </label> <br />
	<?php echo stripslashes( $submitted_data[$name] )?>
</p>

<?php
break;
case 'file':
?>

<p style="width: <?php echo $the_width?>; margin-right: <?php echo $the_margin?>; float:left">
	<label style="font-weight:bold" for="<?php echo $name?>"><?php echo $field_label?> </label> <br />
	<?php 
	if($_REQUEST ['_send_file_as'] == 'file'){

		$file_index = 'thefile_'.$name;
		$uploaded_files = $submitted_data[$file_index];
		
		echo "<ul>";
		foreach ( $uploaded_files as $file_id => $file ) {
		
			if ($file != '') {
					$file_url = $nmfilemanager -> get_file_dir_url () . $file;
					echo "<li><a href=\"$file_url\">$file</a></li>";
			}
		}
		
		echo "</ul>";
	}else{
		
		_e('File(s) are attached', 'nm-filemanager');
	}
	?>
</p>

<?php     
break;
case 'image':
	?>

<p style="width: <?php echo $the_width?>; margin-right: <?php echo $the_margin?>; float:left">
	<label style="font-weight:bold" for="<?php echo $name?>"><?php echo $field_label?> </label> <br />
	
	<?php
	echo "<ul>";
	if (is_array($submitted_data[$name])) {
		
		foreach ($submitted_data[$name] as $image){
			echo "<li><a href=\"$image\">$image</a></li>";
		}
		
	}else{
		echo "<li><a href=\"$submitted_data[$name]\">$submitted_data[$name]</a></li>";
	}
	echo "</ul>";
?>
</p>

<?php
break;

case 'section':

	if($started_section)		//if section already started then close it first
		echo '</section>';

	$section_title 		= strtolower(preg_replace("![^a-z0-9]+!i", "_", $meta['title'])); 
	$started_section 	= 'filemanager-section-'.$section_title;
	?>

<section id="<?php echo $started_section?>">

	<div style="clear: both"></div>

	<header class="filemanager-section-header">
		<h1>
			<?php echo stripslashes( $meta['title'] ) ?>
		</h1>
		<p>
			<?php echo stripslashes( $meta['description']) ?>
		</p>
	</header>

	<div style="clear: both"></div>
	<?php     
	break;

		}
	}


	?>

	<div style="clear: both"></div>
	</div>
	<?php
}?>