<?php
/*
 * new template file for 
 * file uploader version 8.0
 */

$single_form = $this -> get_forms( $this -> form_id );
$file_meta 		= json_decode( $single_form -> the_meta, true);
echo 'aa gia';
filemanager_pa($single_form);
filemanager_pa($file_meta);


?>
