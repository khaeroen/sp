<?php

$cminds_plugin_config = array(
	'plugin-is-pro'				 => false,
	'plugin-has-addons'			 => TRUE,
	'plugin-version'			 => '2.5.12',
	'plugin-abbrev'				 => 'cmdm',
	'plugin-file'				 => CMDM_PLUGIN_FILE,
	'plugin-affiliate'               => '',
	'plugin-redirect-after-install'  => admin_url( 'admin.php?page=CMDM_admin_settings' ),
	'plugin-show-guide'                 => TRUE,
	'plugin-guide-text'                 => '    <div style="display:block">
        <ol>
         <li>Go to the plugin <strong>"Setting"</strong> and click on <strong>"Link to downloads frontend list"</strong></li>
         <li>Click on  <strong>"Manage My Downloads"</strong> button at the right side of the screen</li>
            <li>From the user dashboard click on <strong>Add New</strong> to upload your first download</li>
            <li>Fill up for form and upload your first download, make sure you mark the category.</li>
            <li><strong>View</strong> the download created</li>
            <li>In the <strong>Plugin Settings</strong> you can set the file extensions which are accepted, the default image and more.</li>
            <li>You can add or change category names from the <strong>Plugin Admin Menu</strong></li>
            <li><strong>Troubleshooting:</strong> Make sure that you are using Post name permalink structure in the WP Admin Settings -> Permalinks.</li>
            <li><strong>Troubleshooting:</strong> If post type archive does not show up or displays 404 then install Rewrite Rules Inspector plugin and use the Flush rules button.</li>
            <li><strong>Troubleshooting:</strong> If the settings cannot be saved eg. 403 Forbidden error shows up after pressed the Save button, then contact your hosting provider and ask for the restrictions for POST requests to the /wp-admin/admin.php.</li>
        </ol>
    </div>',
	'plugin-guide-video-height'         => 240,
	'plugin-guide-videos'               => array(
		array( 'title' => 'Installation tutorial', 'video_id' => '159673805' ),
	),
	'plugin-dir-path'			 => plugin_dir_path( CMDM_PLUGIN_FILE ),
	'plugin-dir-url'			 => plugin_dir_url( CMDM_PLUGIN_FILE ),
	'plugin-basename'			 => plugin_basename( CMDM_PLUGIN_FILE ),
	'plugin-icon'				 => '',
	'plugin-name'				 => 'CM Download Manager',
	'plugin-license-name'		 => 'CM Download Manager',
	'plugin-slug'				 => '',
	'plugin-short-slug'			 => 'cm-download-manager',
	'plugin-menu-item'			 => 'CMDM_downloads_menu',
	'plugin-textdomain'			 => 'cm-download-manager',
	'plugin-userguide-key'		 => '8-cm-download-manager',
	'plugin-store-url'			 => 'https://www.cminds.com/wordpress-plugins-library/downloadsmanager/',
	'plugin-support-url'		 => 'http://wordpress.org/support/plugin/cm-download-manager',
	'plugin-review-url'			 => 'http://wordpress.org/support/view/plugin-reviews/cm-download-manager',
	'plugin-changelog-url'		 => 'https://downloadsmanager.cminds.com/release-notes/',
	'plugin-licensing-aliases'	 => array( 'CM Download Manager' ),
	'plugin-compare-table'	 => '
           <div class="suite-package" style="padding-left:10px;"><h2>The premium version of this plugin is included in CreativeMinds All plugins suite:</h2><a href="https://www.cminds.com/wordpress-plugins-library/cm-wordpress-plugins-yearly-membership/" target="_blank"><img src="'.plugin_dir_url( __FILE__ ).'CMWPPluginssuite.png"></a></div>
            <hr style="width:1000px; height:3px;">
            <div class="pricing-table" id="pricing-table"><h2 style="padding-left:10px;">Upgrade The Download Manager Plugin:</h2>
                <ul>
                    <li class="heading" style="background-color:black;">Current Edition</li>
                    <li class="price">FREE<br /></li>
                    <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Unlimited Downloads <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Add unlimited number of downloads each having a dedicated page with description and additional information"></span></li>
                    <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Download Categories <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Assign each download to one or more categories. Browse downloads by categories"></span></li>
                    <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Voting <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Vote for each downloads using a star rating system"></span></li>
                    <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>View count <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Automaticly show the number of views for each download page"></span></li>
                    <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Support forum <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Add a support forum to each download so user can ask question or add comments"></span></li>
                    <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Internal Search <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Search the downloads using keywords"></span></li>
                    <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Only logged-in users can download <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Only logged-in user can view downloads"></span></li>
                 </ul>

               <ul>
                    <li class="heading">Pro<a href="https://www.cminds.com/wordpress-plugins-library/downloadsmanager/" style="float:right;font-size:11px;color:white;" target="_blank">More</a></li>
                    <li class="price">$39.00<br /> <span style="font-size:14px;">(For one Year / Site)<br />Additional pricing options available <a href="https://www.cminds.com/wordpress-plugins-library/downloadsmanager/" target="_blank"> >>> </a></span> <br /></li>
                    <li class="action"><a href="https://www.cminds.com/?edd_action=add_to_cart&download_id=1930&wp_referrer=https://www.cminds.com/checkout/&edd_options[price_id]=2" style="font-size:18px;" target="_blank">Upgrade Now</a></li>
                     <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>All Free Version Features <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="All free features are supported in the pro"></span></li>
                     <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>All users can download <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Downloads are visible to all users. Also to non logged-in users"></span></li>
                    <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>User Groups Permissions <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Add user to groups and set access restriction to categories or individual downloads by group"></span></li>
                    <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Downloads can be Password Protected <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Support asking for a password before file can be downloaded "></span></li>
                    <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Admin can define Upload Restriction <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Uploading files can be restricted to specific users or user groups"></span></li>
                    <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Admin can define View Restrictions <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Viewing specific downloads or categories can be restricted to specific users or user group"></span></li>
                    <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Shortcodes for downloads list <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Several shortcodes are supported. Shortcode case show  most recent downloads, most popular downloads and more"></span></li>
                    <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Moderation Support <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="File uploaded by users can be moderated before posted"></span></li>
                    <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>File Preview Option <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Support showing content with external viewer. Download including PDF / DOC / XLS files can be viewed with Google docs viewer"></span></li>
                    <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Audio & Video Player Option <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Ability to show a video or audio player in the download page and to play a video / audio file before it is downloaded"></span></li>
                    <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Customize Plugin Permalink <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Download URL can be set using a permalink in the plugin settings"></span></li>
                    <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>User Profile with all Downloads <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Each user has a profile page showing all his uploads and basic information about the user"></span></li>
                    <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Extended internal Search </li>
                    <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Multiple index views <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Download index page has multiple view options"></span></li>
                    <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Log & Statistics <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Detailed reports and statistics about your downloads including who downloaded each file"></span></li>
                    <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Second Level Navigation <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Support second level navigation in the category tree "></span></li>
                    <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Download Shortcodes in Post/Pages <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Include in each post or page a download shortcode showing a single file download button with information about download "></span></li>
                    <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Multiple File Upload <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Each download page can contain multiple files. File can be downloaded separately or using a zip to download all"></span></li>
                    <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Automatic Zip Compression <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="When multiple files are uploaded user can download each file or all using a zip file. User can also choose the files he needs and download them as a zip file"></span></li>
                    <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Social Media Integration <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Ability to let users register and login using their social media accounts"></span></li>
                    <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Multisite Support <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Works in WordPress multisite environment. Please check licensing information regarding Multisite support"></span></li>
                    <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Customize labels <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Customize all plugin labels. This will ensure you can easily change messages and labels to adjust them to your language and terminology"></span></li>
                    <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Widgets <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Several sidebar widgets can be added. Widget can show most recent downloads, most popular download and more "></span></li>
                    <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Disclaimer Support <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="A disclaimer can be shown before user is able to view downloads. User needs to approve disclaimer in order to view download"></span></li>
                    <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Gravatar Support <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Show user gravatar in user list of downloads"></span></li>
                    <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Customize Download Page <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Download page can be customized to remove or add components from it."></span></li>
                    <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Integration with Store plugins <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Integration with external cart system such as EDD to show a shortcode instead of the download button"></span></li>
                  <li class="support" style="background-color:lightgreen; text-align:left; font-size:14px;"><span class="dashicons dashicons-yes"></span> One year of expert support <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:grey" title="You receive 365 days of a WordPress expert support. We will answer questions you have and also support any issue related to the plugin. We also provide on site support."></span><br />
                        <span class="dashicons dashicons-yes"></span> Unlimited product updates <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:grey" title="During the year, you can update the plugin as many times as needed and receive any new release and security update"></span><br />
                        <span class="dashicons dashicons-yes"></span> Plugin can be used forever <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:grey" title="If you choose not to renew the plugin license, you can still continue to use a long as you want."></span><br />
                        <span class="dashicons dashicons-yes"></span> Save 35% once renewing license <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:grey" title="If you choose to renew the plugin license you can do this anytime you choose. The renewal cost will be 35% off the product cost."></span></li>
                   </ul>
                  <ul>
                    <li class="heading">Client Zone<a href="https://www.cminds.com/wordpress-plugins-library/downloadsmanager/" style="float:right;font-size:11px;color:white;" target="_blank">More</a></li>
                    <li class="price">$49.00<br /> <span style="font-size:14px;">(For one Year / Site)<br />Additional pricing options available <a href="https://www.cminds.com/wordpress-plugins-library/downloadsmanager/" target="_blank"> >>> </a></span> <br /></li>
                    <li class="action"><a href="https://www.cminds.com/?edd_action=add_to_cart&download_id=51133&wp_referrer=https://www.cminds.com/checkout/&edd_options[price_id]=1" style="font-size:18px;" target="_blank">Upgrade Now</a></li>
                     <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>All Free and Pro Version Features <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="All free and Pro features are supported "></span></li>

                     <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Restricted Customer Zone <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Allow your users to create a DropBox like solution in which users can upload files which they and admin can only see or send files directly to specific users"></span></li>
                    <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Send and receive files from admin <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Admin can send files to specific users or group of users. User can upload files and send them to admin"></span></li>
                   <li class="support" style="background-color:lightgreen; text-align:left; font-size:14px;"><span class="dashicons dashicons-yes"></span> One year of expert support <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:grey" title="You receive 365 days of a WordPress expert support. We will answer questions you have and also support any issue related to the plugin. We also provide on site support."></span><br />
                        <span class="dashicons dashicons-yes"></span> Unlimited product updates <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:grey" title="During the year, you can update the plugin as many times as needed and receive any new release and security update"></span><br />
                        <span class="dashicons dashicons-yes"></span> Plugin can be used forever <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:grey" title="If you choose not to renew the plugin license, you can still continue to use a long as you want."></span><br />
                        <span class="dashicons dashicons-yes"></span> Save 35% once renewing license <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:grey" title="If you choose to renew the plugin license you can do this anytime you choose. The renewal cost will be 35% off the product cost."></span></li>
              </ul>
        
                    <ul>
                    <li class="heading">Ultimate<a href="https://www.cminds.com/wordpress-plugins-library/downloadsmanager/" style="float:right;font-size:11px;color:white;" target="_blank">More</a></li>
                    <li class="price">$99.00<br /> <span style="font-size:14px;">(For one Year / Site)<br />Additional pricing options available <a href="https://www.cminds.com/wordpress-plugins-library/downloadsmanager/" target="_blank"> >>> </a></span> <br /></li>
                    <li class="action"><a href="https://www.cminds.com/?edd_action=add_to_cart&download_id=87963&wp_referrer=https://www.cminds.com/checkout/&edd_options[price_id]=1" style="font-size:18px;" target="_blank">Upgrade Now</a></li>
                     <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>All Free and pro Version Features <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="All free and pro features are supported"></span></li>
                     <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Integration with Micropayments <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Integrates with CM MicroPayments plugin. This will support adding a transaction layer to each upload or download. For example when a user download a file he than needs to pay X amount of virtual coins"></span></li>
                    <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Payment Support <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Support paying with real money for downloading or uploading files. Payment system is based on Easy Digital Downloads cart system"></span></li>
                    <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Registration support <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Support registration of users directly into groups defined by admin. Each group has its own permission to view files. Registration support includes login and registration shortcodes together with an invitation code system "></span></li>
                    <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Anonymous Uploads <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Support anonymous uploads of files to the download manager"></span></li>
                    <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Integration with PeepSo <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Integrates with PeepSo social networks. Let members upload and share files between groups"></span></li>
                    <li style="text-align:left;"><span class="dashicons dashicons-yes"></span>Download visual widgets <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:green" title="Add visual widgets to the download manager which you can add to your widgets and sidebar"></span></li>
                     <li class="support" style="background-color:lightgreen; text-align:left; font-size:14px;"><span class="dashicons dashicons-yes"></span> One year of expert support <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:grey" title="You receive 365 days of a WordPress expert support. We will answer questions you have and also support any issue related to the plugin. We also provide on site support."></span><br />
                        <span class="dashicons dashicons-yes"></span> Unlimited product updates <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:grey" title="During the year, you can update the plugin as many times as needed and receive any new release and security update"></span><br />
                        <span class="dashicons dashicons-yes"></span> Plugin can be used forever <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:grey" title="If you choose not to renew the plugin license, you can still continue to use a long as you want."></span><br />
                        <span class="dashicons dashicons-yes"></span> Save 35% once renewing license <span class="dashicons dashicons-admin-comments cminds-package-show-tooltip" style="color:grey" title="If you choose to renew the plugin license you can do this anytime you choose. The renewal cost will be 35% off the product cost."></span></li>

              </ul>

            </div>',
);

