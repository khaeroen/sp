<?php

/*
*** new html template for file uploader.
*** this will render as main file to render form.
*/

/** getting the parameters for delete file **/
if(isset($_GET['pid']) && isset($_GET['file_name']) && isset($_GET['do']) == 'delete')
{
	if(wp_delete_post($_GET['pid']) && $this->delete_file($_GET['file_name']) )
		_e('Removed', 'nm-filemanager');
	else
		_e('Fail', 'nm-filemanager');
}

/** getting the confirmation for delete all files **/
if( isset( $_GET['delete_all'] ) == 'yes' )
{
	$this->delete_all_posts();
}

/** getting the parameters for ordering **/
if(isset($_GET['order'])) 
	$post_order = $_GET['order'];
else
	$post_order = 'DESC';

/** getting the parameters for search string **/
if(isset($_GET['search_str'])) 
	$search_str = $_GET['search_str'];
else
	$search_str = '';

/** getting the uploader data-name of the form **/
//$uploader_name = $this->get_form_meta($this -> form_id);

global $wpdb;
$range = 2;
$showitems = ($range * 2)+1;  
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$args = array(
	'orderby'          => 'post_title',
	'order'            => $post_order,
	'post_type'        => 'nm-userfiles',
	'post_status'      => 'private',
	'paged'			   => $paged,
	'author'           => get_current_user_id(),
	'meta_query' 	   => array( 
								array('key' => 'uploaded_file_names',
								 	  'value' => $search_str,
								 	  'compare' => 'LIKE')
								)
	);

$my_query = new WP_Query();
$query_posts = $my_query->query($args);

$my_query1 = new WP_Query($args);
$pages = $my_query1->max_num_pages;
	if(!$pages)
    	$pages = 1;
		
$urlOrder_by_asc = $this->fixRequestURI(array('order' => 'ASC'));
$urlOrder_by_dsc = $this->fixRequestURI(array('order' => 'DESC'));
/*echo 'totla posts '. count($query_posts);
print_r($query_posts); exit;*/


?>
<style>

* {
	margin: 0px;
	padding: 0px;
}

.clearfix {
	clear: both;
}

.container {
	max-width: 960px;
	width: 100%;
	margin: 0 auto;
}

.nav {
	width: 20%;
	float: left;
}

.nav ul {
	list-style-type: none;
}

.nav ul li {
	display: block;
}

.nav ul li a {
	display: block;
	background-color: #B7B7B7;
	margin-top: 10px;
	text-decoration: none;
	color: #000;
	padding: 5px;
}

.red-bg {
	background-color: #FF0000 !important;
}

.nav ul li a:hover {
	display: block;
	background-color: #F00;
	margin-top: 10px;
	text-decoration: none;
	color: #000;
	padding: 5px;
}


.content {
	width: 80%;
	float: left;
}

#filemanager-uploadfile {
	width: 100%;
	text-align: center;
	min-height: 300px;
	margin-top: 10px;
	-webkit-box-shadow: 3px 3px 5px 0px rgba(50, 50, 50, 0.51);
	-moz-box-shadow:    3px 3px 5px 0px rgba(50, 50, 50, 0.51);
	box-shadow:         3px 3px 5px 0px rgba(50, 50, 50, 0.51);
}
#filemanager-uploadfile img {
	/* width: 150px; */
}

#filemanager-listfile {
	width: 100%;
	margin-top: 10px;
}

#filemanager-filedetail {
	width: 90%;
	margin-top: 10px;
	margin-left: auto;
	margin-right: auto;
}

#filemanager-filedetail h3{
	margin-bottom: 15px;
}

.detail-btns a {
	display: block;
	background-color: #B7B7B7;
	text-decoration: none;
	color: #000;
	width: 125px;
	float: right;
	margin-left: 10px;
}

.detail-btns a  img{
	vertical-align: middle;
}

.image-style {
	border: 2px solid black;
}

.image-preview {
	width: 30%;
	float: left;
}

.image-details {
	width: 70%;
	float: left;
}

.image-details p {
	border-bottom: 1px solid black;
	margin: 20px 0 20px 20px;
}


.search_bar {
	width: 90%;
	background-color: #000;
	margin: 0 auto;
}

.search_bar p {
	float: left;
	color: #FFF;
	margin: 16px;
	position: absolute;
}

.search_bar a {
	color: #FFF;
	text-decoration: none;
	margin-left: 6px;
	position: relative;
	top: 16px;
	left: 70px;
}

.search_bar input {
	width: 30%;
	margin: 10px;
	float: right;
	height: 20px;
	padding: 6px 12px;
	font-size: 14px;
	line-height: 1.428571429;
	color: #555;
	vertical-align: middle;
	background-color: #fff;
	border: 1px solid #ccc;
	border-radius: 4px;
	-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
	box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
	-webkit-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
	transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}

#mainwrapper {
	font: 10pt normal Arial, sans-serif;
	height: auto;
	text-align: center;
	width: 90%;
	margin: 0 auto;
}

#mainwrapper .box {
	border: 5px solid #fff;
	cursor: pointer;
	height: 182px;
	float: left;
	margin: 5px;
	position: relative;
	overflow: hidden;
	width: 30%;
	-webkit-box-shadow: 1px 1px 1px 1px #ccc;
	-moz-box-shadow: 1px 1px 1px 1px #ccc;
	box-shadow: 1px 1px 1px 1px #ccc;
}

#mainwrapper .box img {
	max-width: 100%;
	max-height: 100%;
	display: block;
	margin: auto;
	-webkit-transition: all 300ms ease-out;
	-moz-transition: all 300ms ease-out;
	-o-transition: all 300ms ease-out;
	-ms-transition: all 300ms ease-out;	
	transition: all 300ms ease-out;
}


#mainwrapper .box .caption {
	background-color: rgba(0,0,0,0.8);
	position: absolute;
	color: #fff;
	z-index: 100;
	-webkit-transition: all 300ms ease-out;
	-moz-transition: all 300ms ease-out;
	-o-transition: all 300ms ease-out;
	-ms-transition: all 300ms ease-out;	
	transition: all 300ms ease-out;
	left: 0;
}


#mainwrapper .box .bottom-caption {
	height: 30px;
	width: 100%;
	display: block;
	bottom: -30px;
	line-height: 25pt;
	text-align: center;
}

#mainwrapper .box:hover .bottom-caption {
	-moz-transform: translateY(-100%);
	-o-transform: translateY(-100%);
	-webkit-transform: translateY(-100%);
	opacity: 1;
	transform: translateY(-100%);
}


#mainwrapper .box .top-caption {
	height: 30px;
	width: 100%;
	display: block;
	top: -30px;
	line-height: 25pt;
	text-align: center;
}

#mainwrapper .box:hover .top-caption {
	-moz-transform: translateY(100%);
	-o-transform: translateY(100%);
	-webkit-transform: translateY(100%);
	opacity: 1;
	transform: translateY(100%);
}

.filemanager-sendfile-input {
	display: block;
	width: 30%;
	margin: 0 auto;
	height: 20px;
	padding: 6px 12px;
	font-size: 14px;
	line-height: 1.428571429;
	color: #555;
	vertical-align: middle;
	background-color: #fff;
	border: 1px solid #ccc;
	border-radius: 4px;
	-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
	box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
	-webkit-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
	transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}

.filemanager-sendfile-textarea {
	display: block;
	width: 30%;
	margin: 0 auto;
	height: 50px;
	padding: 6px 12px;
	font-size: 14px;
	line-height: 1.428571429;
	color: #555;
	vertical-align: middle;
	background-color: #fff;
	border: 1px solid #ccc;
	border-radius: 4px;
	-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
	box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
	-webkit-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
	transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}

.filemanager-sendfile-button {
	color: #333;
	background-color: #fff;
	display: inline-block;
	padding: 6px 12px;
	margin-bottom: 0;
	font-size: 14px;
	font-weight: bold;
	line-height: 1.428571429;
	text-align: center;
	white-space: nowrap;
	vertical-align: middle;
	cursor: pointer;
	border: 1px solid #ccc;
	border-radius: 4px;
	-webkit-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	-o-user-select: none;
	user-select: none;
}

.bottom-caption a {
	float: left;
	margin-left: 13px;
}

#filemanager-sendfile {
	text-align: center;
	margin-top: 10px;
}

.filemanager-sendsinglefile {
	text-align: center;
	margin-top: 10px;
}

.comments-topaera {
	border-bottom: 1px solid black;
}

.comments-head {
	background-color: #CFE2F3;
	clear: both;
	width: 26%;
	padding: 12px;
	border-top-left-radius: 10px;
	border-top-right-radius: 10px;
	border: 1px solid black;
	border-bottom: 0px;
}

.comments-textarea {
	margin-top: 10px;
	width: 70%;
	height: 36px;	
	padding: 6px 12px;
	font-size: 14px;
	line-height: 1.428571429;
	color: #555;
	vertical-align: middle;
	background-color: #fff;
	border: 1px solid #000;
	-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
	box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
	-webkit-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
	transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}

.comments-btn {
	margin-top: 10px;
	height: 50px;
	float: right;
	width: 25%;
	color: #333;
	background-color: #B7B7B7;
	display: inline-block;
	padding: 6px 12px;
	margin-bottom: 0;
	line-height: 1.428571429;
	text-align: center;
	white-space: nowrap;
	vertical-align: middle;
	cursor: pointer;
	border: 1px solid #ccc;
}

.comments-posts {
	width: 100%;
	margin-top: 15px;
}

.comments-time {
	color: #C2C2C2;
	font-size: 10px;
}

.comments-posts p {
	margin-bottom: 10px;
}

.image-details-inner {
	border-bottom: 1px solid black;
	margin: 20px 0 20px 20px;
}

.filename {
	font-weight: bold;
}

.details-left-side {
	width: 50%;
	float: left;
}

.details-right-side {
	width: 50%;
	float: left;
}

@media only screen and (max-width: 768px) {
	.container {
		overflow: hidden;
	}
	.box {
		width: 92% !important;
	}
	#mainwrapper {
		width: 90%;
	}
	.filemanager-sendfile-input, .filemanager-sendfile-textarea {
		width: 70%;
	}
	.image-preview {
		width: 100%;
	}
	.image-details, .details-right-side, .details-left-side  {
		width: 100%;
		float: none;
	}
	.detail-btns a {
		margin-bottom: 10px;
		float: none;
		margin-left: 0px;
	}
	.image-details-inner {
		margin: 20px 0 20px 0px;
	}
	.comments-textarea {
		width: 60%;
	}
}
/* pagination css starts*/
.pagination {
clear:both;
padding:20px 0;
position:relative;
font-size:11px;
line-height:13px;
}

.pagination span, .pagination a {
display:block;
float:left;
margin: 2px 2px 2px 0;
padding:6px 9px 5px 9px;
text-decoration:none;
width:auto;
color:#fff;
background: #555;
}

.pagination a:hover{
color:#fff;
background: #3279BB;
}

.pagination .current{
padding:6px 9px 5px 9px;
background: #3279BB;
color:#fff;
}
</style>

<div class="container">
    <div class="nav">
      	<ul>
            <li><a id="nav-manage-files" href="javascript:"><?php _e('Manage Files', 'nm-filemanager'); ?></a></li>
            <li><a id="nav-upload-files" href="javascript:"><?php _e('Upload Files', 'nm-filemanager'); ?></a></li>
            <li><a class="red-bg" href="javascript:confirmDeleteAll('<?php echo $this->fixRequestURI(array('delete_all'=>'yes'))?>')"><?php _e('Delete all Files', 'nm-filemanager'); ?></a></li>
        </ul>
    </div><!--- End Navigation --->
    <div class="content">
        <div id="filemanager-uploadfile">
            <?php $this->load_template ( 'render.input.php' ); ?>
        </div>
        <div id="filemanager-listfile">
            <div class="search_bar">
                <p>Sort By:</p>
                <a href="<?php echo $urlOrder_by_dsc ?>"><?php _e('A-Z', 'nm-filemanager'); ?></a>
                <a href="<?php echo $urlOrder_by_asc ?>"><?php _e('Z-A', 'nm-filemanager'); ?></a>
                <?php if ( isset( $_GET['search_str'] ) ): ?>
                	<a href="<?php echo wp_get_referer() ?>" ><?php printf(__('Clear Search', 'nm-filemanager')) ?></a>
                    <?php 
						$countarr = count($query_posts);
						printf(__('%u Result(s) found!', 'nm-filemanager'), $countarr) ?>
                <?php else: ?>
                	<a href="javascript:" id="page-refresh">  <?php printf(__('Refresh', 'nm-filemanager')) ?></a>
                <?php endif ?>
                <input type="text" placeholder="Search Files" id="search-files" onKeyPress="javascript:search_files('<?php echo get_permalink()?>'+'?search_str='+this.value)"/>
                <div class="clearfix"></div>
            </div>
            <div id="mainwrapper">
           	<?php
			/** while loop start **/
              while ( $my_query->have_posts() ) : 
			  		$my_query->the_post();
           		
					$share_files = $meta = get_post_meta(get_the_ID(), 'uploaded_file_names', true);
					$meta_val = explode(",", $meta);
					$file_name = $meta_val[0];
					$dir_path = $this->get_file_dir_url ();
					$file_url = ($file_name == '') ? $this->plugin_meta ['url'].'/images/noimage.png' : $dir_path.'thumbs/'. $file_name;
					//$file_url = $dir_path.'thumbs/'. $file_name;
					if ( !$file_name == '' && !$this -> is_image($file_name) ){
                    	$ext = pathinfo($file_name, PATHINFO_EXTENSION);
						$ext = strtolower($ext);
						$file_url = $this->plugin_meta ['url'] . '/images/ext/48px/'.$ext.'.png';
					}
					$params = array('pid'		=> get_the_ID(),
									'file_name' => $share_files,
									'do'		=> 'delete');
					$params_download = array('do'		 => 'download',
											 'file_name' => $file_name);
					$params_edit = array('do'		 => 'edit',
										 'file_name' => $file_name,
										 'pid'		=> get_the_ID(),);
					$params_mail = array('single_file_name' => $file_name);
					
					$urlDelete   = $this->fixRequestURI($params);
					$urlDownload = $this->fixRequestURI($params_download);
					$urlMailFile = $this->fixRequestURI($params_mail);
					$urlEditFile = $this->fixRequestURI($params_edit);
					
					$new_post_title = get_the_title();
					if (strlen($new_post_title) > 20)
						$new_post_title = substr($new_post_title, 9, 20).'...';
					else
						$new_post_title = substr($new_post_title, 9);
					?>
                <div class="box">
                    <a href="<?php echo $urlEditFile ?>"><img src="<?php echo $file_url; ?>"/></a>
                    <span class="caption top-caption"><div class="nav-previous alignleft"></div>
                        <h4><?php echo $new_post_title; ?></h4>
                    </span>
                    <span class="caption bottom-caption">
                        <a title="Edit" href="<?php echo $urlEditFile ?>"><img src="<?php echo $this->plugin_meta ['url'] ?>/images/edit-small.png"/></a>
                        <a title="Delete" href="javascript:confirmFirstDelete('<?php echo $urlDelete?>')"><img src="<?php echo $this->plugin_meta['url']?>/images/delet.png"/></a>
                        <a href="#TB_inline?width=400&height=400&inlineId=filemanager-sendfile" 
                           class="thickbox" 
                           title="<?php _e('Share', 'nm-filemanager'); ?>">
                           <img src="<?php echo $this->plugin_meta ['url'] ?>/images/msg.png"/>
                        </a>
                        
                        <a title="Download" href="<?php echo $urlDownload?>"><img src="<?php echo $this->plugin_meta ['url'] ?>/images/download.png"/></a>
                    </span> 
                </div>
                <div id="filemanager-sendfile" style="display:none">
                    <form id="frm-send-files" method="post" onsubmit = "return send_files_email(this)">
                    <input type="hidden" id="shared_single_file" value="<?php echo $share_files;?>">
                    <div id="print-file-name">
                        <span></span>
                    </div>
                    <p><?php _e('Subject', 'nm-filemanager'); ?></p>
                    <?php echo 'files are '.$share_files;?>
                    <input class="filemanager-sendfile-input" type="text" id="subject"/>
                    <!--<p id="txt-file-names">Start typing filename and pull list</p>
                    <input class="filemanager-sendfile-input" type="text" id="file-names"/>
                    <br/>-->
                    <p><?php _e('Email separated by commas', 'nm-filemanager'); ?></p>
                    <input class="filemanager-sendfile-input" type="text" id="email-to"/>
                    <br/>
                    <p><?php _e('Any message', 'nm-filemanager'); ?></p>
                    <textarea class="filemanager-sendfile-textarea" id="file-msg"></textarea>
                    <br/>
                    <input class="filemanager-sendfile-button" type="submit" value="<?php _e('Send File', 'nm-filemanager'); ?>" />
                    </form>
                </div>
				<?php endwhile;
				
               /** while loop end **/
			?>
            </div>
        </div>
        <div class="clearfix"></div>
        
        <!--Pagination code start here-->
        <div id="filemanager-pagination"> 
		<?php //echo $pages.' '.$paged.' '.$range.' '.$showitems.' ljkljklj '.$my_query1->max_num_pages;
	 	if(1 != $pages)
     	{
        	echo "<div class='pagination'>";
         	if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo;</a>";
         	if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo;</a>";

         	for ($i=1; $i <= $pages; $i++)
         	{
            	if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             	{
                	echo ($paged == $i)? "<span class='current'>".$i."</span>":"<a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a>";
             	}
         	}
         	if ($paged < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($paged + 1)."'>&rsaquo;</a>";  
         	if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>&raquo;</a>";
         	echo "</div>\n";
     	}	
		?>
        </div>       
		 <!--Pagination code end here-->
    </div><!--- End Content --->
  </div><!--- End Container --->
</body>
<script type="text/javascript">
<!--
jQuery(document).ready(function($){
		$("#filemanager-uploadfile").hide();
		
	$("#nav-upload-files").click(function(){
		$("#filemanager-listfile").hide();
		$("#filemanager-pagination").hide();
		$("#filemanager-uploadfile").show(1000);
	});
	
	$("#nav-manage-files").click(function(){
		$("#filemanager-uploadfile").hide();
		$("#filemanager-listfile").show(1000);
		$("#filemanager-pagination").show();
	});
	
	$("#page-refresh").click(function(){
		location.reload();
	});
});

function share_this_file(file_name){
	
	jQuery("#shared_single_file").val(file_name);
	jQuery("#file-names").hide();
	jQuery("#txt-file-names").hide();
	jQuery("#print-file-name span").text("File(s): "+file_name);
	jQuery("#nav-send-files").click();
}

function confirmFirstDelete(url)
{
	var a = confirm('Are you sure to delete this file?');
	if(a)
	{
		window.location = url;
	}
}

function confirmDeleteAll(url)
{
	var a = confirm('Are you sure to delete all your file?');
	if(a)
	{
		window.location = url;
	}
}

function search_files(url)
{
	jQuery("#search-files").keyup(function(event){
    	if(event.keyCode == 13){
			window.location = url;
   		}
	});
}
//--></script>