<?php
/*
 * it is loading all files uploaded by user
 */

global $nmfilemanager, $wpdb;

/** migrating old files if any **/
$nmfilemanager -> migrate_files();

/** getting the parameters for delete file **/
if(isset($_GET['pid']) && isset($_GET['do']) == 'delete')
{
	$nmfilemanager -> delete_file();
}

$login_user_id = get_current_user_id();
$allow_public = $nmfilemanager -> get_option('_allow_public');
if ($login_user_id == 0 && $allow_public[0] == 'yes')
	$login_user_id = $nmfilemanager -> get_option('_public_user');

$range = 2;
$showitems = ($range * 2)+1;  
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = array(
			'orderby'          => 'post_title',
			//'order'            => $post_order,
			'post_type'        => 'nm-userfiles',
			'post_status'      => 'publish',
			//'paged'			   => $paged,
			'nopaging'		   => true,
			'author'           => $login_user_id,
/*			'meta_query' 	   => array( 
										array('key' => 'uploaded_file_names',
											  'value' => $search_str,
											  'compare' => 'LIKE')
										)
*/			);
		
?>
<table id="user-files" class="display">
<thead>
	<tr>
        <th>
        	<strong><?php _e('Thumb', 'nm-filemanager')?></strong>
        </th>
        <th>
        	<strong><?php _e('File Title', 'nm-filemanager')?></strong>
        </th>
        <th>
        	<strong><?php _e('Uploaded on', 'nm-filemanager')?></strong>
        </th>
        <th>
        	<strong><?php _e('File Tools', 'nm-filemanager')?></strong>
        </th>
    </tr>
</thead>
<tbody>
<!--<div id="nm-uploaded-files">-->
    <h2><?php _e('My Files', 'nm-filemanager');?></h2>
	<?php
		$my_query = new WP_Query();
		$query_posts = $my_query->query($args);
		
		$my_query1 = new WP_Query($args);
		$pages = $my_query1->max_num_pages;
			if(!$pages)
    			$pages = 1;

		while ( $my_query->have_posts() ) : 
		  		$my_query->the_post();

        $params = array('pid'	=> get_the_ID(),
						'do'	=> 'delete');
						
		$filemanager_filename = $nmfilemanager -> get_attachment_file_name( get_the_ID() );
		
        $private_download = array('file_name'	=> $filemanager_filename,
		 						  'do'			=> 'download');

		$filemanager_delete_file = $nmfilemanager -> fixRequestURI($params);
		$filemanager_download = $nmfilemanager -> fixRequestURI($private_download);	
		$filemanager_del_icon = $this -> plugin_meta['url'].'/images/delet.png';
		
		$filemanager_filepath = $nmfilemanager->get_file_dir_path() . $filemanager_filename;
		
		if( ! file_exists($filemanager_filepath ) )
			continue;
		
	?>
      <tr id="file-list-row-<?php echo get_the_ID();?>">
	    <td>
			<?php     echo '<div id="file-list-row-'.get_the_ID().'" class="file-list-container">';
    	echo '<div id="file-thumb-'.get_the_ID().'" class="nm-file-thumb">';
    		$nmfilemanager -> set_file_download( get_the_ID() );
        echo '</div>';
		
        /**
         * rendering file title, description
         */ 
        //$nmfilemanager -> render_file_title_description( $file );
		$current_user = wp_get_current_user();
			echo '<div id="file-title-'.get_the_ID().'" class="nm-file-title">';
			//echo '<span class="rendering-file-title">'. the_title() .'</span>';
			//echo '<em> File uploaded by ' .$current_user->user_login.' '.get_the_date(). '</em>';
			//echo '<em> File uploaded '.$nmfilemanager -> time_difference( get_the_date() ).' by ' .$current_user->user_login. '</em>';
			echo '</div>';	
		 ?>
		</td>
        <td>
        	<?php echo '<span class="rendering-file-title">'. the_title() .'</span>'; ?>
        </td>
        <td>
        	<?php echo '<span class="rendering-file-title">'. get_the_date() .'</span>'; ?>
        </td>
    	<td>
			<?php         echo '<div id="file-tools-'.get_the_ID().'" class="nm-file-tools">';
            
            /*
             * rendering file tootls, edit, sharing, delete etc
             */
            //$nmfilemanager -> render_file_tools( $file );
			echo '<a title="'.__('Download file', 'nm-filemanager').'" href="'.esc_url($filemanager_download).'"><img alt="'.__('Download file', 'nm-filemanager').'" src="'.$this -> plugin_meta['url'].'/images/download.png" /></a>';
			
			echo '<a title="'.__('Delete file', 'nm-filemanager').'" href="javascript:confirmFirstDelete('."'".$filemanager_delete_file."'".')"><img alt="'.__('Delete file', 'nm-filemanager').'" src="'.esc_url($filemanager_del_icon).'" /></a>';
        echo '</div>';
        echo '<div class="clear"></div>';
    echo '</div>';
 ?>
		</td>
	  </tr>

	<?php


	endwhile;
	?>
<!--</div>-->

 </tbody>
</table>