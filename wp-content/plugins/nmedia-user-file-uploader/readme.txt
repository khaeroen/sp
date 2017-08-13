﻿=== WP Fron-end File Manager ===
Contributors: nmedia
Donate link: http://www.najeebmedia.com/donate/
Tags: Front end upload, File uploader, User files, User files manager, File uploaders, User Desgins uploader, Image uploader, ajax based file uploader, progress bar, Document Managemen System
Requires at least: 3.5
Tested up to: 4.7.4
Stable tag: 5.0
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

N-Media file uploader plugin allow site users to upload files and share with admin.

== Description ==
This plugin lets the wordpress site users to upload files for admin. Each file is saved in private directory so each user can download/delete their own files after login. For more control please see PRO feature below. Use folowing shortcode:: <strong>[nm-wp-file-uploader]</strong>

<h4>NOTE: Version 3.0 has major changes, but we have included a migration script which will copy all old files in new version</h4>
<h3>Features</h3>
<ol>
	<li><span style="line-height: 13px;">Upload button title</span></li>
	<li><span style="line-height: 13px;">Upload button font color</span></li>
	<li>Upload button background color</li>
	<li>Max filesize (each file)</li>
	<li>Max files limit = 5</li>
	<li>Upload background color change</li>
	<li>Message control on success and error</li>
	<li>Image thumb</li>
</ol>

<h3>New Pro Demo</h3>
<a href="http://fileupload.theproductionarea.net">See Demo</a>

<h3>Pro Features</h3>
Pro version gives you AWSOME control over this plugin on top of free version. You can control file upload behavior with following shortcode
<ul>
	<li>Create Directory</li>
	<li>Directory Tree View (see screenshots)</li>
	<li>Min files limit</li>
	<li>Max file upload limit set</li>
	<li>Secure download link</li>
	<li>Admin email notification/alert</li>
	<li>Share file (user can send file in email)</li>
	<li>File meta attachment (Awesome feature)</li>
	<li>File sorting, searching, pagination</li>
	<li>Admin: disable/enable upload/download section</li>
	<li>Admin: Allow users to upload files even not registered (public)</li>
	<li>Visual Composer Addon Avaiable</li>	
</ul>

<h3>File Meta</h3>
File meta is another set of shortcodes allow site admin to attach unlimited input fields. These are named as `File Meta`. Admin will receive email on every file upload
with `File Meta`. Following 8 types of input field can be attached:

<ul>
	<li><strong>Text</strong></li>
	<li><strong>Textarea</strong>]</li>
	<li><strong>Select</strong></li>
	<li><strong>Checkbox</strong></li>
	<li><strong>Mask (customized format)</strong></li>
	<li><strong>Email</strong></li>
	<li><strong>Date (datepicker)</strong></li>
	<li><strong>Image</strong></li>
	<li><strong>Checkbox</strong></li>
	
</ul>

<a href="http://www.najeebmedia.com/nmedia-file-uploader-pro/">More info with Demo</a>


== Installation ==

1. Upload plugin directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. After activation, you can set options from `NM FileUploader` menu

== Frequently Asked Questions ==

= Can I change the message when file uploaded or deleted? =

Yes you can.

= Can I see its working Demo? =
Yes, http://www.najeebmedia.com/n-media-file-uploader-plugin-pro-demo/


= Does this uploader will show progressbar =
Yes nice progressbar with percentage

= Why I see HTTP Error message =
it is because of your server side settings, sometime php.ini does not allow to upload big files. You need to check following two settings in php.ini:<br>
1- post_max_size<br>
2- upload_max_filesize
<a href="http://www.najeebmedia.com/how-increase-file-upload-size-limit-in-wordpress/">check this tutorial</a>

== Screenshots ==

1. Frontend view of plgin
2. Admin area for user uploaded files
3. PRO Feature: Create directory
4. PRO Feature: Directory Tree View Template

== Changelog ==

= 5.0 =
* Bug fixed: Some php warnings removed
* Bug fixed: functions renamed properly
* Bug fixed: scripts enqued properly
* Bug fixed: DataTable API version updated
* Bug fixed: offload image removed
* Bug fixed: extra files and directories removed
* Bug fixed: filename sanitzed while uploading and delete
= 4.2 =
* Bug fixed: Invalid filetype error in Wordpress Version 4.7.2
* Bug fixed: Physical files not removed, now it is removing.
* Bug fixed: Intermedia images are generated on each file upload, now only one image and thumb will be generated
= 4.1 =
* Feaure: Tanslation support added for different laguages. Included languages, DE, ES, FR, NL
= 4.0 =
* Bug fixed: Arbitrary File Upload Vulnerability issue fixed
= 3.9 =
* Added: Some admin UI element added
= 3.8 =
* SECURITY ALERT: This version has removed a BUG related to security. Remote invalid file types are NOT allowed
= 3.7 =
* No more Flash needed for IE. It's replaced with HTML5 runtime.
= 3.6 =
* Plupload replace with new version 2.1.2
= 3.5 =
BUG Fixed: Filename will will displayed after uploading for all files. 

= 3.4 =
* IE not supported message will be shown if IE browser detected

= 3.3 =
* issue with FF/IE on file saving is fixed.

= 3.2 =
* reloding the page once file is save.

= 3.1 =
* plugin option menu was replacing appearance menu. Fixed now

= 3.0 =
* developed on new plugin framework which is more efficient
* better upload script using PlUpload
* listing uploaded files with Data Table
* showing images thumbs

= 2.1 =
* Some latin characters like �, �, � etc were not rendered in file upload button, it is fixed now.

= 2.0 = 
* doupload.php and uploadify.php files have removed for best security practice
* front end design is replced with ul/li based structure 
* pagination control

= 1.8 =
* Some major security issues is being fixed, please update to this version

= 1.7 =
* Admin can see the file uploade by users

= 1.6 =
* there was error sometimes when creating directory for users, not it is fixed.

= 1.5 = 
* Physical file deleted from folder
* Every user will have its own upload directory as `user_name`
* File Name field is removed

= 1.4 =
* Change the Upload File button 

= 1.3 =
* Fixed the bug for php short code
= 1.0 =
* It is first version, and working perfectly

= 1.2 =
* Fixed content placement issue when using shortcode in middle of post/page.

= 1.1 =
* Just fixed the delete file bug.


== Upgrade Notice ==

= 1.0 =
Nothing for now.

= 1.1 =
Update to this version, Delete File issue is just fixed.

= 1.2 =
Update to this version, Content Placement issue is being fixed

= 1.3 =
Fixed the bug for php short code

= 1.4 =
Wrapped up the Upload File Button with some CSS.

= 1.5 =
This plugin has three major changes, please update to get these.

= 1.6 =
Upload directory was not creating due to some server side settings, now it is fixed

= 1.7 =
Admin can see the file uploade by users

= 1.8 =
Some major security issues is being fixed, please update to this version

= 2.0 = 
doupload.php and uploadify.php files have removed for best security practice
front end design is replced with ul/li based structure 
pagination control

= 2.1 =
Some latin characters like �, �, � etc were not rendered in file upload button, it is fixed now.

= 3.0 =
this version has major updates. It's not using userfiles table. But we included a migration script which will copy old files into new custom post types. Although some data may be lost.

1. It is very light plugin
2. We are working on more plugins to get our users more excited.
3. More options/controls will be given soon.

= 3.6 =
* Plupload replace with new version 2.1.2, MUST update to this version as older version of plupload have some security issues.

= 3.8 =
* SECURITY ALERT: This version has removed a BUG related to security. Remote invalid file types are NOT allowed