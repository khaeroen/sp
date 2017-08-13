<?php
/*
 * it is loading all other templates
 */

global $nmfilemanager;
?>

<?php 
/*
 * loading uploader template
 */
 	
		$nmfilemanager -> load_template( '_template_uploader.php' );	
	
	
/*
 * loading uploaded files (list files) template
 */

		$nmfilemanager -> load_template( '_template_list_files.php' );

?>