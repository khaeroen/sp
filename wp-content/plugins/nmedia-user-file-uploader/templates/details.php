<?php
$dir_path = $this->get_file_dir_url ();
$file_url = $dir_path . $_REQUEST['file_name'];

/** getting the parameters for delete file **/
if(isset($_GET['pid']) && isset($_GET['file_name']) && $_GET['do'] == 'delete')
{
	if(wp_delete_post($_GET['pid']) && $this->delete_file($_GET['file_name']) )
		_e('Removed', 'nm-filemanager');
	else
		_e('Fail', 'nm-filemanager');
}

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

.detail-btns img {
	box-shadow: none !important;
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
	width: 100%;
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

/**** Flex Slider .CSS ****/
/*
 * jQuery FlexSlider v2.2.0
 * http://www.woothemes.com/flexslider/
 *
 * Copyright 2012 WooThemes
 * Free to use under the GPLv2 license.
 * http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Contributing author: Tyler Smith (@mbmufffin)
 */


/* Browser Resets
*********************************/
.flex-container a:active,
.flexslider a:active,
.flex-container a:focus,
.flexslider a:focus  {outline: none;}
.slides,
.flex-control-nav,
.flex-direction-nav {margin: 0; padding: 0; list-style: none;}

/* Icon Fonts
*********************************/
/* Font-face Icons */
@font-face {
	font-family: 'flexslider-icon';
	src:url(<?php echo $this->plugin_meta ['url'].'/templates/fonts/flexslider-icon.eot'; ?>);
	src:url(<?php echo $this->plugin_meta ['url'].'/templates/fonts/flexslider-icon.eot?#iefix'; ?>) format('embedded-opentype'),
		url(<?php echo $this->plugin_meta ['url'].'/templates/fonts/flexslider-icon.woff'; ?>) format('woff'),
		url(<?php echo $this->plugin_meta ['url'].'/templates/fonts/flexslider-icon.ttf'; ?>) format('truetype'),
		url(<?php echo $this->plugin_meta ['url'].'/templates/fonts/flexslider-icon.svg#flexslider-icon'; ?>) format('svg');
	font-weight: normal;
	font-style: normal;
}

/* FlexSlider Necessary Styles
*********************************/
.flexslider {margin: 0; padding: 0;}
.flexslider .slides > li {display: none; -webkit-backface-visibility: hidden;} /* Hide the slides before the JS is loaded. Avoids image jumping */
.flexslider .slides img {display: block; max-width: 200px; max-height: 150px; margin: auto;}
.flex-pauseplay span {text-transform: capitalize;}

/* Clearfix for the .slides element */
ul.slides {margin: 0px !important;}
.slides:after {content: "\0020"; display: block; clear: both; visibility: hidden; line-height: 0; height: 0;}
html[xmlns] .slides {display: block;}
* html .slides {height: 1%;}

/* No JavaScript Fallback */
/* If you are not using another script, such as Modernizr, make sure you
 * include js that eliminates this class on page load */
.no-js .slides > li:first-child {display: block;}

/* FlexSlider Default Theme
*********************************/
.flexslider { margin: 0 0 5px; background: #fff; border: 4px solid #fff; position: relative; -webkit-border-radius: 4px; -moz-border-radius: 4px; -o-border-radius: 4px; border-radius: 4px; -webkit-box-shadow: 0 1px 4px rgba(0,0,0,.2); -moz-box-shadow: 0 1px 4px rgba(0,0,0,.2); -o-box-shadow: 0 1px 4px rgba(0,0,0,.2); box-shadow: 0 1px 4px rgba(0,0,0,.2); zoom: 1; }
.flex-viewport { max-height: 2000px; -webkit-transition: all 1s ease; -moz-transition: all 1s ease; -o-transition: all 1s ease; transition: all 1s ease; }
.loading .flex-viewport { max-height: 300px; }
.flexslider .slides { zoom: 1; }
.carousel li { margin-right: 5px; }

.slides li {margin: 0;}
/* Direction Nav */
.flex-direction-nav {*height: 0; list-style-type: none !important; margin: 0 !important;}
.flex-direction-nav a  { text-decoration:none; display: block; width: 40px; height: 50px; margin: -20px 0 0; position: absolute; top: 50%; z-index: 10; overflow: hidden; opacity: 0; cursor: pointer; color: rgba(0,0,0,0.8); text-shadow: 1px 1px 0 rgba(255,255,255,0.3); -webkit-transition: all .3s ease; -moz-transition: all .3s ease; transition: all .3s ease; }
.flex-direction-nav .flex-prev { left: -50px; }
.flex-direction-nav .flex-next { right: -50px; text-align: right; }
.flexslider:hover .flex-prev { opacity: 0.7; left: 10px; }
.flexslider:hover .flex-next { opacity: 0.7; right: 10px; }
.flexslider:hover .flex-next:hover, .flexslider:hover .flex-prev:hover { opacity: 1; }
.flex-direction-nav .flex-disabled { opacity: 0!important; filter:alpha(opacity=0); cursor: default; }
.flex-direction-nav a:before  { font-family: "flexslider-icon"; font-size: 40px; display: inline-block; content: '\f001'; }
.flex-direction-nav a.flex-next:before  { content: '\f002'; }

/* Pause/Play */
.flex-pauseplay a { display: block; width: 20px; height: 20px; position: absolute; bottom: 5px; left: 10px; opacity: 0.8; z-index: 10; overflow: hidden; cursor: pointer; color: #000; }
.flex-pauseplay a:before  { font-family: "flexslider-icon"; font-size: 20px; display: inline-block; content: '\f004'; }
.flex-pauseplay a:hover  { opacity: 1; }
.flex-pauseplay a.flex-play:before { content: '\f003'; }

/* Control Nav */
.flex-control-nav {width: 100%; position: absolute; bottom: -40px; text-align: center;}
.flex-control-nav li {margin: 0 6px; display: inline-block; zoom: 1; *display: inline;}
.flex-control-paging li a {width: 11px; height: 11px; display: block; background: #666; background: rgba(0,0,0,0.5); cursor: pointer; text-indent: -9999px; -webkit-border-radius: 20px; -moz-border-radius: 20px; -o-border-radius: 20px; border-radius: 20px; -webkit-box-shadow: inset 0 0 3px rgba(0,0,0,0.3); -moz-box-shadow: inset 0 0 3px rgba(0,0,0,0.3); -o-box-shadow: inset 0 0 3px rgba(0,0,0,0.3); box-shadow: inset 0 0 3px rgba(0,0,0,0.3); }
.flex-control-paging li a:hover { background: #333; background: rgba(0,0,0,0.7); }
.flex-control-paging li a.flex-active { background: #000; background: rgba(0,0,0,0.9); cursor: default; }

.flex-control-thumbs {margin: 5px 0 0; position: static; overflow: hidden;}
.flex-control-thumbs li {width: 25%; float: left; margin: 0;}
.flex-control-thumbs img {width: 100%; display: block; opacity: .7; cursor: pointer;}
.flex-control-thumbs img:hover {opacity: 1;}
.flex-control-thumbs .flex-active {opacity: 1; cursor: default;}

@media screen and (max-width: 860px) {
  .flex-direction-nav .flex-prev { opacity: 1; left: 10px;}
  .flex-direction-nav .flex-next { opacity: 1; right: 10px;}
}

/**** Light Box .CSS ****/
/*
    Colorbox Core Style:
    The following CSS is consistent between example themes and should not be altered.
*/
#colorbox, #cboxOverlay, #cboxWrapper{position:absolute; top:0; left:0; z-index:9999; overflow:hidden;}
#cboxWrapper {max-width:none;}
#cboxOverlay{position:fixed; width:100%; height:100%;}
#cboxMiddleLeft, #cboxBottomLeft{clear:left;}
#cboxContent{position:relative;}
#cboxLoadedContent{overflow:auto; -webkit-overflow-scrolling: touch;}
#cboxTitle{margin:0;}
#cboxLoadingOverlay, #cboxLoadingGraphic{position:absolute; top:0; left:0; width:100%; height:100%;}
#cboxPrevious, #cboxNext, #cboxClose, #cboxSlideshow{cursor:pointer;}
.cboxPhoto{float:left; margin:auto; border:0; display:block; max-width:none; -ms-interpolation-mode:bicubic;}
.cboxIframe{width:100%; height:100%; display:block; border:0; padding:0; margin:0;}
#colorbox, #cboxContent, #cboxLoadedContent{box-sizing:content-box; -moz-box-sizing:content-box; -webkit-box-sizing:content-box;}

/* 
    User Style:
    Change the following styles to modify the appearance of Colorbox.  They are
    ordered & tabbed in a way that represents the nesting of the generated HTML.
*/
#cboxOverlay{background:url(<?php echo $this->plugin_meta ['url'].'/images/overlay.png'?>) repeat 0 0;}
#colorbox{outline:0;}
    #cboxTopLeft{width:21px; height:21px; background:url(<?php echo $this->plugin_meta ['url'].'/images/controls.png'?>) no-repeat -101px 0;}
    #cboxTopRight{width:21px; height:21px; background:url(<?php echo $this->plugin_meta ['url'].'/images/controls.png'?>) no-repeat -130px 0;}
    #cboxBottomLeft{width:21px; height:21px; background:url(<?php echo $this->plugin_meta ['url'].'/images/controls.png'?>) no-repeat -101px -29px;}
    #cboxBottomRight{width:21px; height:21px; background:url(<?php echo $this->plugin_meta ['url'].'/images/controls.png'?>) no-repeat -130px -29px;}
    #cboxMiddleLeft{width:21px; background:url(<?php echo $this->plugin_meta ['url'].'/images/controls.png'?>) left top repeat-y;}
    #cboxMiddleRight{width:21px; background:url(<?php echo $this->plugin_meta ['url'].'/images/controls.png'?>) right top repeat-y;}
    #cboxTopCenter{height:21px; background:url(<?php echo $this->plugin_meta ['url'].'/images/border.png'?>) 0 0 repeat-x;}
    #cboxBottomCenter{height:21px; background:url(<?php echo $this->plugin_meta ['url'].'/images/border.png'?>) 0 -29px repeat-x;}
    #cboxContent{background:#fff; overflow:hidden;}
        .cboxIframe{background:#fff;}
        #cboxError{padding:50px; border:1px solid #ccc;}
        #cboxLoadedContent{margin-bottom:28px;}
        #cboxTitle{position:absolute; bottom:4px; left:0; text-align:center; width:100%; color:#949494;}
        #cboxCurrent{position:absolute; bottom:4px; left:58px; color:#949494;}
        #cboxLoadingOverlay{background:url(<?php echo $this->plugin_meta ['url'].'/images/loading_background.png'?>) no-repeat center center;}
        #cboxLoadingGraphic{background:url(<?php echo $this->plugin_meta ['url'].'/images/loading.gif'?>) no-repeat center center;}

        /* these elements are buttons, and may need to have additional styles reset to avoid unwanted base styles */
        #cboxPrevious, #cboxNext, #cboxSlideshow, #cboxClose {border:0; padding:0; margin:0; overflow:visible; width:auto; background:none; }
        
        /* avoid outlines on :active (mouseclick), but preserve outlines on :focus (tabbed navigating) */
        #cboxPrevious:active, #cboxNext:active, #cboxSlideshow:active, #cboxClose:active {outline:0;}

        #cboxSlideshow{position:absolute; bottom:4px; right:30px; color:#0092ef;}
        #cboxPrevious{position:absolute; bottom:0; left:0; background:url(<?php echo $this->plugin_meta ['url'].'/images/controls.png'?>) no-repeat -75px 0; width:25px; height:25px; text-indent:-9999px;}
        #cboxPrevious:hover{background-position:-75px -25px;}
        #cboxNext{position:absolute; bottom:0; left:27px; background:url(<?php echo $this->plugin_meta ['url'].'/images/controls.png'?>) no-repeat -50px 0; width:25px; height:25px; text-indent:-9999px;}
        #cboxNext:hover{background-position:-50px -25px;}
        #cboxClose{position:absolute; bottom:0; right:0; background:url(<?php echo $this->plugin_meta ['url'].'/images/controls.png'?>) no-repeat -25px 0; width:25px; height:25px; text-indent:-9999px;}
        #cboxClose:hover{background-position:-25px -25px;}

/*
  The following fixes a problem where IE7 and IE8 replace a PNG's alpha transparency with a black fill
  when an alpha filter (opacity change) is set on the element or ancestor element.  This style is not applied to or needed in IE9.
  See: http://jacklmoore.com/notes/ie-transparency-problems/
*/
.cboxIE #cboxTopLeft,
.cboxIE #cboxTopCenter,
.cboxIE #cboxTopRight,
.cboxIE #cboxBottomLeft,
.cboxIE #cboxBottomCenter,
.cboxIE #cboxBottomRight,
.cboxIE #cboxMiddleLeft,
.cboxIE #cboxMiddleRight {
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#00FFFFFF,endColorstr=#00FFFFFF);
}

</style>
	</head>
	<body>
		<div class="container">
			<div class="nav">
				<ul>
                	<li><a id="nav-manage-files" href="<?php echo wp_get_referer() ?>"><?php _e('Manage Files', 'nm-filemanager'); ?></a></li>
				</ul>
			</div><!--- End Navigation --->
			<div class="content">
				<div id="filemanager-filedetail">
					<h3><?php _e('My File Details', 'nm-filemanager'); ?></h3>
					<div class="image-preview">
					<?php
                        $share_files = $meta = get_post_meta($_REQUEST['pid'], 'uploaded_file_names', true);
                        $meta = explode(",", $meta);
                        $params = array('pid'		=> $_REQUEST['pid'],
                                        'file_name' => $share_files,
                                        'do'		=> 'delete');
                        $urlDelete   = $this->fixRequestURI($params);
                        
                        if ( !$share_files == '' ) {
                    ?>
                        <div class="flexslider">
                            <ul class="slides">
                        	<?php
							  foreach ( $meta as $key => $val ) {
								  $file_url = $dir_path . $val;
								  $ext = pathinfo($file_url, PATHINFO_EXTENSION);
								  $ext = strtolower($ext);
								  //if(!in_array($ext, $show_file) )
								  if ( !$this -> is_image($val) )
									  $file_url = $this->plugin_meta ['url'] . '/images/ext/48px/'.$ext.'.png';
									  //$file_url = 'meta is '.$meta;
							?>
							  <li>
								<a class='gallery' href="<?php echo $file_url; ?>"><img class="image-style" src="<?php echo $file_url; ?>"/></a>
							  </li>
                            <?php 
							  }
							?>
						  	</ul>
						</div>
                    <?php 
						}
					?>
					</div>
					<div class="image-details">
                    <?php $this->load_template ( 'render.details.php' ); ?>
                   
                    <div id="nm_file_sharing_box" style="display: none">
                      <p>
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
                      </p>
                    </div>
                    
					<div class="detail-btns">
								<a class="red-bg" 
                                   href="javascript:confirmFirstDelete('<?php echo $urlDelete?>')">
                                   <img src="<?php echo $this->plugin_meta ['url'] ?>/images/delet.png"/>
								   <?php _e('Delete File?', 'nm-filemanager'); ?>
                                </a>
								<a href="#TB_inline?width=400&height=400&inlineId=nm_file_sharing_box" 
                                   class="thickbox" 
                                   title="<?php _e('File sharing', 'nm-filemanager'); ?>">
                                   <img src="<?php echo $this->plugin_meta ['url'] ?>/images/edit-small.png"/>
								   <?php _e('Share this file', 'nm-filemanager')?>
                                </a>
							</div>
						<!--</form>-->
					</div>
				
				</div>				
			</div><!--- End Content --->
		</div><!--- End Container --->
<script type="text/javascript">
jQuery(document).ready(function($){
jQuery(window).load(function() {
  jQuery('.flexslider').flexslider({
    animation: "slide",
    animationLoop: false,
    itemWidth: 210,
    itemMargin: 5
  });
  jQuery('ol.flex-control-nav').hide();
});
  jQuery('a.gallery').colorbox({ opacity:0.5 , rel:'group1' });
});
function confirmFirstDelete(url)
{
	var a = confirm('Are you sure to delete this file?');
	if(a)
	{
		window.location = url;
	}
}
</script>