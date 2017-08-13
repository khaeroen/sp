<?php
/**
 * @package cPanel E-Mail Manager
 */

/*
Plugin Name: cPanel E-Mail Manager
Plugin URI: http://www.insyncbusinessgroup.com/
Description: cPanel E-Mail Manager is a WordPress plugin that integrates with the POP3 e-mail functionality of cPanel allowing the creation and deletion of e-mail accounts for WordPress users.
Version: 1.3
Author: InSync Business Group, LLC
Author URI: http://www.insyncbusinessgroup.com/
License: GPLv2 or later
Text Domain: cpanel-e-mail-manager
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/** MINIMUM REQUIREMENTS:
* PHP 5
* MYSQL 5
* CPANEL 11.32
*/

/** TESTED WITH:
* PHP 5.2.17
* MYSQL 5.5.33-31.1
* CPANEL 11.38.2
*/

if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

//ini_set('display_errors', 1);
//error_reporting(E_ALL);

define('EMAILMGR_CURRENT_VERSION', "1.3");
define('EMAILMGR_SCRIPT_PATH', realpath(dirname(__FILE__)));
define('EMAILMGR_PRO_SCRIPT_PATH_REMOTE', realpath(dirname(__FILE__) . '/..') . '/cpanel-e-mail-manager-pro');

if (!class_exists('xmlapi', false)) {
	if (file_exists(EMAILMGR_SCRIPT_PATH.'/cpanelapi.php')) {
		include_once EMAILMGR_SCRIPT_PATH.'/cpanelapi.php';
	} else {
		die("The required cpanelapi.php file cannot be found.");
	}
}

/*
////////////// MODULE CONFIGURATION OPTIONS //////////////
*/

register_activation_hook( __FILE__, 'emailmanager_activate' );
register_deactivation_hook( __FILE__, 'emailmanager_deactivate' );

/*
////////////// LOAD MODULE HOOKS //////////////
*/

add_action('admin_menu', 'emailmanager_menu');
add_action('delete_user', 'emailmanager_remove_user'); 
add_action('show_user_profile', 'emailmanager_user_change_password_display');
add_action('bp_core_general_settings_before_submit', 'emailmanager_user_change_password_display', 10, 0);
add_action('bp_core_general_settings_after_save', 'emailmanager_user_change_password', 10, 0);
add_action('personal_options_update', 'emailmanager_user_change_password');
add_action('plugins_loaded', 'emailmanager_version_check');
if (!is_multisite()) {
	add_action('wp_dashboard_setup', 'emailmanager_add_dashboard_widget');
}
include_once(ABSPATH . 'wp-admin/includes/plugin.php');
if (!is_plugin_active('cpanel-e-mail-manager-pro/emailmanager_pro.php')) {
	emailmanager_update_config("emailmgr_autogen", 0);
} else {
	if (!function_exists('wp_new_user_notification')) {
		if (emailmanager_config_options("emailmgr_autogen")) {
			function wp_new_user_notification() { }
		}
	}
}

/*
////////////// CORE MODULE FUNCTIONS //////////////
*/

function emailmanager_activate() {
	global $wpdb;
	if (function_exists('is_multisite') && is_multisite()) {
		// check if it is a network activation - if so, run the activation function for each blog id
		if ($networkwide) {
			$old_blog = $wpdb->blogid;
			// Get all blog ids
			$blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
			foreach ($blogids as $blog_id) {
				switch_to_blog($blog_id);
				_emailmanager_activate();
			}
			switch_to_blog($old_blog);
			return;
		}   
	} 
	_emailmanager_activate();  
}

function _emailmanager_activate() {
	global $wpdb;
	$blogdomain = "";
	if (function_exists('is_multisite') && is_multisite()) {
		$blogdomain = $wpdb->get_var( $wpdb->prepare( 
			"
			SELECT domain 
			FROM $wpdb->blogs
			WHERE blog_id = %d
			", 
			get_current_blog_id()
		) );
	}
	$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_domain", "option_value"=>$blogdomain));
	$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_autogen", "option_value"=>0));
	$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_groups", "option_value"=>"Administrator"));
	$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_quota", "option_value"=>250));
	$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_webmailurl", "option_value"=>""));
	$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_cpaneluser", "option_value"=>""));
	$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_cpanelpass", "option_value"=>""));
	$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_cpanelip", "option_value"=>"localhost"));
	$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_emailmsg", "option_value"=>"Greetings {\$user},\n\nYour e-mail account {\$email} has been activated and is ready for use!\n\nTemporary Password: {\$temppw}\n\nNote: For security reasons we recommend changing your password upon first login.\n\nYou can access your e-mail account here: {\$server}\n\nThank you!"));
	$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_aliasformat", "option_value"=>"first.last"));
	$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_sendemail", "option_value"=>0));
	$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_encryption_salt", "option_value"=>emailmanager_generatePassword()));
	$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_version", "option_value"=>EMAILMGR_CURRENT_VERSION));
	$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_matchpw", "option_value"=>0));
	$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_showpwfields", "option_value"=>0));
	$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_disablewpnotify", "option_value"=>0));
	$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_removeuser", "option_value"=>0));
	$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_mailinglist_cleanup", "option_value"=>0));
	$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_pw_uppercase", "option_value"=>1));
	$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_pw_lowercase", "option_value"=>1));
	$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_pw_numbers", "option_value"=>1));
	$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_pw_symbols", "option_value"=>1));
	$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_pw_characters", "option_value"=>6));
}

function emailmanager_deactivate() {
	global $wpdb;
	if (function_exists('is_multisite') && is_multisite()) {
		// check if it is a network activation - if so, run the activation function for each blog id
		if ($networkwide) {
			$old_blog = $wpdb->blogid;
			// Get all blog ids
			$blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
			foreach ($blogids as $blog_id) {
				switch_to_blog($blog_id);
				_emailmanager_deactivate();
			}
			switch_to_blog($old_blog);
			return;
		}   
	} 
	_emailmanager_deactivate();  
}

function _emailmanager_deactivate() {
	global $wpdb;
	$wpdb->delete($wpdb->options, array("option_name"=>"emailmgr_autogen"));
	$wpdb->delete($wpdb->options, array("option_name"=>"emailmgr_groups"));
	$wpdb->delete($wpdb->options, array("option_name"=>"emailmgr_domain"));
	$wpdb->delete($wpdb->options, array("option_name"=>"emailmgr_quota"));
	$wpdb->delete($wpdb->options, array("option_name"=>"emailmgr_webmailurl"));
	$wpdb->delete($wpdb->options, array("option_name"=>"emailmgr_cpaneluser"));
	$wpdb->delete($wpdb->options, array("option_name"=>"emailmgr_cpanelpass"));
	$wpdb->delete($wpdb->options, array("option_name"=>"emailmgr_cpanelip"));
	$wpdb->delete($wpdb->options, array("option_name"=>"emailmgr_emailmsg"));
	$wpdb->delete($wpdb->options, array("option_name"=>"emailmgr_aliasformat"));
	$wpdb->delete($wpdb->options, array("option_name"=>"emailmgr_sendemail"));
	$wpdb->delete($wpdb->options, array("option_name"=>"emailmgr_encryption_salt"));
	$wpdb->delete($wpdb->options, array("option_name"=>"emailmgr_version"));
	$wpdb->delete($wpdb->options, array("option_name"=>"emailmgr_matchpw"));
	$wpdb->delete($wpdb->options, array("option_name"=>"emailmgr_showpwfields"));
	$wpdb->delete($wpdb->options, array("option_name"=>"emailmgr_disablewpnotify"));
	$wpdb->delete($wpdb->options, array("option_name"=>"emailmgr_removeuser"));
	$wpdb->delete($wpdb->options, array("option_name"=>"emailmgr_mailinglist_cleanup"));
	$wpdb->delete($wpdb->options, array("option_name"=>"emailmgr_pw_uppercase"));
	$wpdb->delete($wpdb->options, array("option_name"=>"emailmgr_pw_lowercase"));
	$wpdb->delete($wpdb->options, array("option_name"=>"emailmgr_pw_numbers"));
	$wpdb->delete($wpdb->options, array("option_name"=>"emailmgr_pw_symbols"));
	$wpdb->delete($wpdb->options, array("option_name"=>"emailmgr_pw_characters"));
}

function emailmanager_menu() {
	if (current_user_can('list_users')) {
		add_users_page(__('E-Mail Manager', 'cpanel-e-mail-manager'), __('E-Mail Manager', 'cpanel-e-mail-manager'), 'read', 'emailmanager', 'emailmanager_output');
	}
}

function emailmanager_create_email($user_id, $firstname, $lastname, $username, $email, $customformat = null, $customalias = null, $customquota = null, $custompass = null) {
	//Set config options
	$emaildomain = emailmanager_config_options("emailmgr_domain");
	$emailquota = (!empty($customquota) ? $customquota : emailmanager_config_options("emailmgr_quota"));
	$webmailurl = emailmanager_config_options("emailmgr_webmailurl");
	$cpaneluser = emailmanager_config_options("emailmgr_cpaneluser");
	$cpanelpass = emailmanager_config_options("emailmgr_cpanelpass");
	$cpanelserver = emailmanager_config_options("emailmgr_cpanelip");
	$emailmsg = emailmanager_config_options("emailmgr_emailmsg");
	$aliasformat = (!empty($customformat) ? $customformat : emailmanager_config_options("emailmgr_aliasformat"));
	$sendemail = emailmanager_config_options("emailmgr_sendemail");
	$emailalias = emailmanager_assign_alias($firstname, $lastname, $username, $email, $customformat, $customalias);

	//ADD USER TO CPANEL
	$randpass = emailmanager_generatePassword();
	$optional['email_domain'] = $emaildomain;
	$optional['email_user'] = $emailalias;
	if (!empty($custompass)) {
		$optional['email_pass'] = $custompass;
	} else {
		$optional['email_pass'] = $randpass;
	}
	$optional['email_quota'] = $emailquota;

	$newemail = simplexml_load_string(emailmanager_cpanelAPI($cpaneluser, $cpanelpass, $cpanelserver, "createemail", $optional));

	if ($newemail->data->result == 1 && !empty($user_id)) {
		global $wpdb;
		update_user_option( $user_id, 'emailmgr_alias', $emailalias );

		$options['firstname'] = (!empty($firstname) ? $firstname : "User");
		$options['lastname'] = (!empty($lastname) ? $lastname : "");
		$options['regemail'] = (!empty($email) ? $email : "");
		$options['username'] = (!empty($username) ? $username : "");
		$options['email'] = $emailalias . "@" . $emaildomain;
		$options['temppw'] = $optional['email_pass'];
		$options['server'] = $webmailurl;
		if (preg_match('/(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\/~\b[a-zA-Z0-9_]+\b)/i', get_home_url())) {
			$options['siteurl'] = "";
		} else {
			$options['siteurl'] = get_home_url();
		}

		if ($sendemail) {
			emailmanager_pro_sendEmail($user_id, $email, __('E-Mail Account Activated', 'cpanel-e-mail-manager'), $emailmsg, $options);
		}
		return '<div class="updated"><p>' . sprintf(__('The e-mail account %1$s@%2$s has been successfully created', 'cpanel-e-mail-manager'), $emailalias, $emaildomain) . '!</p></div><br>';
	} else {
		return '<div class="error"><p>' . __('The following error occurred', 'cpanel-e-mail-manager') . ': ' . (!empty($newemail->data->reason) ? $newemail->data->reason : sprintf(__('An unknown error occurred while trying to create the e-mail account for %s', 'cpanel-e-mail-manager'), $emailalias)) . '</p>
		</div><br>';
	}
}

function emailmanager_remove_user($user_id) {
	global $wpdb;
	$removeuser = emailmanager_config_options("emailmgr_removeuser");
	//Set config options
	$emailalias = emailmanager_usermeta($user_id, $wpdb->prefix . "emailmgr_alias");
	delete_user_option( $user_id, "emailmgr_alias" );
	if ($removeuser) {
		emailmanager_remove_email($emailalias);
	}
}

function emailmanager_remove_email($emailalias) {
	//Set config options
	$emaildomain = emailmanager_config_options("emailmgr_domain");
	$cpaneluser = emailmanager_config_options("emailmgr_cpaneluser");
	$cpanelpass = emailmanager_config_options("emailmgr_cpanelpass");
	$cpanelserver = emailmanager_config_options("emailmgr_cpanelip");
	$emailaddress = $emailalias . "@" . $emaildomain;

	//REMOVE USER FROM CPANEL
	$optional['email_domain'] = $emaildomain;
	$optional['email_user'] = $emailalias;

	emailmanager_cpanelAPI($cpaneluser, $cpanelpass, $cpanelserver, "deleteemail", $optional);

	if (EMAILMGR_STD_MODE == 0) {
		$optional['domain'] = $emaildomain;
		$lists = simplexml_load_string(emailmanager_pro_cpanelAPI($cpaneluser, $cpanelpass, $cpanelserver, "listlists", $optional));
		if ($lists->data->list != "") {
			if ($lists->data) {
				foreach ($lists->data as $list) {
					emailmanager_pro_mailinglist_remove(str_replace("@", "_", $list->list), $emaildomain, $emailaddress, $cpanelpass);
				}
			}
		}
	}

	global $wpdb;
	$user_id = emailmanager_emailalias_userid($emailalias);
	delete_user_option( $user_id, "emailmgr_alias" );
}

function emailmanager_add_dashboard_widget() {
	if (current_user_can('list_users')) {
		wp_add_dashboard_widget(
       	          'emailmanager_dashboard_widget',         // Widget slug.
       	           __('cPanel E-Mail Manager Statistics', 'cpanel-e-mail-manager'),         // Title.
       	          'emailmanager_dashboard_widget_function' // Display function.
        	);	
	}
}

function emailmanager_version_check() {
	if (function_exists('emailmanager_pro_init')) {
		include_once EMAILMGR_PRO_SCRIPT_PATH_REMOTE.'/emailmanager_pro.php';
		define('EMAILMGR_STD_MODE', 0);
	} else {
		define('EMAILMGR_STD_MODE', 1);
		define('EMAILMGR_CPANEL_HIDE', 0);
		define('EMAILMGR_CPANEL_GLOBAL', 0);
		define('EMAILMGR_CPANEL_USERNAME', "");
		define('EMAILMGR_CPANEL_PASSWORD', "");
		define('EMAILMGR_CPANEL_SERVER', "");
	}
	if (emailmanager_config_options("emailmgr_version") != EMAILMGR_CURRENT_VERSION) {
		emailmanager_upgrade();
	}
	$plugin_dir = basename(dirname(__FILE__));
	load_plugin_textdomain('cpanel-e-mail-manager', false, $plugin_dir . '/languages/');
}

function emailmanager_upgrade() {
	global $wpdb;
	$currentversion = emailmanager_config_options("emailmgr_version");
	if (empty($currentversion)) {
		/* Upgrade to 1.2.4 */
		$blogdomain = "";
		if (function_exists('is_multisite') && is_multisite()) {
			$blogdomain = $wpdb->get_var( $wpdb->prepare( 
				"
				SELECT domain 
				FROM $wpdb->blogs
				WHERE blog_id = %d
				", 
				get_current_blog_id()
			) );
		}
		$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_domain", "option_value"=>$blogdomain));
		$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_autogen", "option_value"=>0));
		$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_groups", "option_value"=>"Administrator"));
		$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_quota", "option_value"=>250));
		$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_webmailurl", "option_value"=>""));
		$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_cpaneluser", "option_value"=>""));
		$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_cpanelpass", "option_value"=>""));
		$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_cpanelip", "option_value"=>"localhost"));
		$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_emailmsg", "option_value"=>"Greetings {\$user},\n\nYour e-mail account {\$email} has been activated and is ready for use!\n\nTemporary Password: {\$temppw}\n\nNote: For security reasons we recommend changing your password upon first login.\n\nYou can access your e-mail account here: {\$server}\n\nThank you!"));
		$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_aliasformat", "option_value"=>"first.last"));
		$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_sendemail", "option_value"=>0));
		$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_encryption_salt", "option_value"=>emailmanager_generatePassword()));
		$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_version", "option_value"=>"1.2.4"));
		$currentversion = "1.2.4";
	} 
	if ($currentversion < "1.2.5") {
		/* Upgrade from 1.2.4 to 1.2.5 */
		$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_matchpw", "option_value"=>0));
		$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_showpwfields", "option_value"=>0));
		$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_disablewpnotify", "option_value"=>0));
		$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_removeuser", "option_value"=>0));
		$currentversion = "1.2.5";
	}
	if ($currentversion < "1.2.7") {
		/* Upgrade to 1.2.7 */
		$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_mailinglist_cleanup", "option_value"=>0));
		$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_pw_uppercase", "option_value"=>1));
		$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_pw_lowercase", "option_value"=>1));
		$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_pw_numbers", "option_value"=>1));
		$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_pw_symbols", "option_value"=>1));
		$wpdb->insert($wpdb->options, array("option_name"=>"emailmgr_pw_characters", "option_value"=>6));
		$currentversion = "1.2.7";
	}
	/* Set to current version */
	emailmanager_update_config("emailmgr_version", EMAILMGR_CURRENT_VERSION);
}

/*
////////////// MODULE OUTPUT //////////////
*/

function emailmanager_output() {
	global $wpdb;
	$output = "<h1>cPanel E-Mail Manager " . emailmanager_version() . "</h1>";
	$cpaneluser = emailmanager_config_options("emailmgr_cpaneluser");
	$cpanelpass = emailmanager_config_options("emailmgr_cpanelpass");
	$cpanelserver = emailmanager_config_options("emailmgr_cpanelip");
	$emaildomain = emailmanager_config_options("emailmgr_domain");
	if ($cpaneluser != "" && $cpanelpass != "" && $cpanelserver != "") {
		if (function_exists('is_multisite') && is_multisite() && $_GET["action"] != "createdns") {
			if (empty($emaildomain)) {
				$emaildomain = $wpdb->get_var( $wpdb->prepare( 
					"
					SELECT domain 
					FROM $wpdb->blogs
					WHERE blog_id = %d
					", 
					get_current_blog_id()
				) );
				emailmanager_update_config("emailmgr_domain", $emaildomain);
			}
			$domains = simplexml_load_string(emailmanager_cpanelAPI($cpaneluser, $cpanelpass, $cpanelserver, "listemaildomains"));
			$domainfound = 0;
			foreach ($domains->data as $domain) {
				if ($domain->domain == $emaildomain) {
					$domainfound = 1;
					break;
				}
			}
			if ($domainfound == 0) {
				echo '<h2 style="display:inline;">' . __('Configuration Options', 'cpanel-e-mail-manager') . '</h2><p>&nbsp;</p><p>&nbsp;</p><p style="text-align:center; font-weight: bold;font-size: 16px;">' . __('There are currently no domains configured in cPanel for this WordPress MU site. Please contact your server administrator for assistance or click the button below to create a new DNS entry for this domain.', 'cpanel-e-mail-manager') . '</p>
				<p>&nbsp;</p>
				<form action="users.php?page=emailmanager&mode=config&action=createdns" name="updatedns" id="updatedns" action="" method="post">
				<input type="hidden" name="emaildomain" id="emaildomain" value="' .  $emaildomain . '">
				<p align="center"><input type="submit" value="' . __('Create DNS Entry', 'cpanel-e-mail-manager') . '" class="button button-primary"></p>
				</form>';
				exit;
			}
		}
	}
	if (isset( $_GET['mode'] ) && !empty( $_GET['mode'] )) {
		if ($_GET["mode"] == "list") {
			$output .= emailmanager_output_list();
		}
		if ($_GET["mode"] == "add") {
			$output .= emailmanager_output_adduser();
		}
		if ($_GET["mode"] == "mailinglist") {
			if (EMAILMGR_STD_MODE == 0) {
				$output .= emailmanager_pro_output_mailinglist();
			} else {
				$output .= emailmanager_output_upgradeplugin();
			}
		}
		if ($_GET["mode"] == "managelist") {
			if (EMAILMGR_STD_MODE == 0) {
				$output .= emailmanager_pro_output_managelist();
			} else {
				$output .= emailmanager_output_upgradeplugin();
			}
		}
		if ($_GET["mode"] == "upgradeplugin") {
			$output .= emailmanager_output_upgradeplugin();
		}
		if ($_GET["mode"] == "config") {
			$output .= emailmanager_output_config($emaildomain);
		}
		if ($_GET["mode"] == "linkaccounts") {
			$output .= emailmanager_output_linkexisting();
		}
	} else {
		if ($cpaneluser != "" && $cpanelpass != "" && $cpanelserver != "" && $emaildomain != "") {
			$accountinfo = simplexml_load_string(emailmanager_cpanelAPI($cpaneluser, $cpanelpass, $cpanelserver, "diskusage"));
			if ($accountinfo->data->max != "") {
				$output .= emailmanager_output_list();
			} else {
				$output .= emailmanager_output_config($emaildomain);
			}
		} else {
			$output .= emailmanager_output_config($emaildomain);
		}
	}
echo $output;
}

function emailmanager_output_config($mudomain = null) {
	global $wpdb;
	$output = '';
	if (!empty($_POST)) {
		if (isset($_GET["action"])) {
			if ($_GET["action"] == "createdns") {
				//Set config options
				$emaildomain = emailmanager_config_options("emailmgr_domain");
				$cpaneluser = emailmanager_config_options("emailmgr_cpaneluser");
				$cpanelpass = emailmanager_config_options("emailmgr_cpanelpass");
				$cpanelserver = emailmanager_config_options("emailmgr_cpanelip");

				$optional['domain'] = $_POST['emaildomain'];
				$result = simplexml_load_string(emailmanager_cpanelAPI($cpaneluser, $cpanelpass, $cpanelserver, "addparkeddomain", $optional));
				if ($result->data->result == 1) {
					return '<h2 style="display:inline;">' . __('Configuration Options', 'cpanel-e-mail-manager') . '</h2><p>&nbsp;</p><p>&nbsp;</p><p style="text-align:center; font-weight: bold;font-size: 16px;">' . sprintf(__('The DNS entry for %s has been successfully added', 'cpanel-e-mail-manager'), $_POST['emaildomain']) . '!</p><p style="text-align:center; font-weight: bold;font-size: 16px;">' . __('Continue to', 'cpanel-e-mail-manager') . ' <a href="users.php?page=emailmanager&mode=config">' . __('Configuration Options', 'cpanel-e-mail-manager') . '</a>.</p>';
					exit;
				} else {
					$error = emailmanager_resultLogging(__('Park Domain', 'cpanel-e-mail-manager'), (!isset($result->data->reason) ? sprintf(__('An unspecified error has occurred while trying to add a parked domain for %s.', 'cpanel-e-mail-manager'), $_POST['emaildomain']) : $result->data->reason));
					return '<h2 style="display:inline;">' . __('Configuration Options', 'cpanel-e-mail-manager') . '</h2><p>&nbsp;</p><p>&nbsp;</p><p style="text-align:center; font-weight: bold;font-size: 16px;">' . $error . '</p><p style="text-align:center; font-weight: bold;font-size: 16px;">' . __('Please contact your server administrator for assistance.', 'cpanel-e-mail-manager') . '</p>';
					exit;
				}
			} elseif ($_GET["action"] == "setcpanel") {
				$emaildomain = emailmanager_config_options("emailmgr_domain");
				$cpaneluser = (isset($_POST['cpaneluser']) ? $_POST['cpaneluser'] : "");
				$cpanelpass = (isset($_POST['cpanelpass']) ? $_POST['cpanelpass'] : "");
				$cpanelserver = (isset($_POST['cpanelserver']) ? $_POST['cpanelserver'] : "");
				if ($cpaneluser != "") {
					emailmanager_update_config("emailmgr_cpaneluser", $cpaneluser);
				}
				if ($cpanelpass != "") {
					emailmanager_update_config("emailmgr_cpanelpass", $cpanelpass);
				}
				if ($cpanelserver != "") {
					emailmanager_update_config("emailmgr_cpanelip", $cpanelserver);
				}
				$domainfound = 0;
				if (function_exists('is_multisite') && is_multisite()) {
					$domains = simplexml_load_string(emailmanager_cpanelAPI($cpaneluser, $cpanelpass, $cpanelserver, "listemaildomains"));
					foreach ($domains->data as $domain) {
						if ($domain->domain == $emaildomain) {
							$domainfound = 1;
							break;
						}
					}
					if ($domainfound == 0 && !empty($emaildomain)) {
						echo '<h1>cPanel E-Mail Manager</h1><h2 style="display:inline;">' . __('Configuration Options', 'cpanel-e-mail-manager') . '</h2><p>&nbsp;</p><p>&nbsp;</p><p style="text-align:center; font-weight: bold;font-size: 16px;">' . __('There are currently no domains configured in cPanel for this WordPress MU site. Please contact your server administrator for assistance or click the button below to create a new DNS entry for this domain.', 'cpanel-e-mail-manager'). ' </p>
						<p>&nbsp;</p>
						<form action="users.php?page=emailmanager&mode=config&action=createdns" name="updatedns" id="updatedns" action="" method="post">
						<input type="hidden" name="emaildomain" id="emaildomain" value="' .  $emaildomain . '">
						<p align="center"><input type="submit" value="' . __('Create DNS Entry', 'cpanel-e-mail-manager') . '" class="button button-primary"></p>
						</form>';
						exit;
					}
				}
			}
		} else {
			$autogenerate = (isset($_POST['autogenerate']) ? $_POST['autogenerate'] : 0);
			$wordpressgroups = (isset($_POST['wordpressgroups']) ? implode(",", $_POST['wordpressgroups']) : "");
			$emaildomain = (isset($_POST['emaildomain']) ? $_POST['emaildomain'] : "");
			$webmailurl = (isset($_POST['webmailurl']) ? $_POST['webmailurl'] : "");
			if ($webmailurl == "") {
				$webmailurl = (isset($_POST['emaildomain']) ? "mail." . $_POST['emaildomain'] : "");
			}
			$emailquota = (isset($_POST['emailquota']) ? $_POST['emailquota'] : 250);	
			$cpaneluser = (isset($_POST['cpaneluser']) ? $_POST['cpaneluser'] : "");
			$cpanelpass = (isset($_POST['cpanelpass']) ? $_POST['cpanelpass'] : "");
			$cpanelserver = (isset($_POST['cpanelserver']) ? $_POST['cpanelserver'] : "");
			$emailmsg = (isset($_POST['emailmsg']) ? $_POST['emailmsg'] : "");
			$aliasformat = (isset($_POST['aliasformat']) ? $_POST['aliasformat'] : "");
			$sendemail = (isset($_POST['sendemail']) ? $_POST['sendemail'] : 0);
			$matchpw = (isset($_POST['matchpw']) ? $_POST['matchpw'] : 0);
			$showpwfields = (isset($_POST['showpwfields']) ? $_POST['showpwfields'] : 0);
			$disablewpnotify = (isset($_POST['disablewpnotify']) ? $_POST['disablewpnotify'] : 0);
			$removeuser = (isset($_POST['removeuser']) ? $_POST['removeuser'] : 0);
			$mailinglist_cleanup = (isset($_POST['mailinglist_cleanup']) ? $_POST['mailinglist_cleanup'] : 0);
			$pw_uppercase = (isset($_POST['pw_uppercase']) ? $_POST['pw_uppercase'] : 1);
			$pw_lowercase = (isset($_POST['pw_lowercase']) ? $_POST['pw_lowercase'] : 1);
			$pw_numbers = (isset($_POST['pw_numbers']) ? $_POST['pw_numbers'] : 1);
			$pw_symbols = (isset($_POST['pw_symbols']) ? $_POST['pw_symbols'] : 1);
			$pw_characters = (isset($_POST['pw_characters']) ? $_POST['pw_characters'] : 6);

			//Update database
			emailmanager_update_config("emailmgr_autogen", $autogenerate);
			if ($wordpressgroups != "") {
				emailmanager_update_config("emailmgr_groups", $wordpressgroups);
			}
			emailmanager_update_config("emailmgr_domain", $emaildomain);
			emailmanager_update_config("emailmgr_quota", $emailquota);
			emailmanager_update_config("emailmgr_webmailurl", $webmailurl);
			if ($cpaneluser != "") {
				emailmanager_update_config("emailmgr_cpaneluser", $cpaneluser);
			}
			if ($cpanelpass != "") {
				emailmanager_update_config("emailmgr_cpanelpass", $cpanelpass);
			}
			if ($cpanelserver != "") {
				emailmanager_update_config("emailmgr_cpanelip", $cpanelserver);
			}
			if ($emailmsg != "") {
				emailmanager_update_config("emailmgr_emailmsg", $emailmsg);
			}
			if ($aliasformat != "") {
				emailmanager_update_config("emailmgr_aliasformat", $aliasformat);
			}
			emailmanager_update_config("emailmgr_sendemail", $sendemail);
			emailmanager_update_config("emailmgr_matchpw", $matchpw);
			emailmanager_update_config("emailmgr_showpwfields", $showpwfields);
			emailmanager_update_config("emailmgr_disablewpnotify", $disablewpnotify);
			emailmanager_update_config("emailmgr_removeuser", $removeuser);
			emailmanager_update_config("emailmgr_mailinglist_cleanup", $mailinglist_cleanup);
			emailmanager_update_config("emailmgr_pw_uppercase", $pw_uppercase);
			emailmanager_update_config("emailmgr_pw_lowercase", $pw_lowercase);
			emailmanager_update_config("emailmgr_pw_numbers", $pw_numbers);
			emailmanager_update_config("emailmgr_pw_symbols", $pw_symbols);
			emailmanager_update_config("emailmgr_pw_characters", $pw_characters);
		}
		$output .= '<div class="updated">
		<p>' . __('Configuration options have been updated', 'cpanel-e-mail-manager') . '!</p>
		</div><br>';
	}

	//Set config options
	$autogenerate = (emailmanager_config_options("emailmgr_autogen") ? " checked" : "");
	$wordpressgroups = emailmanager_config_options("emailmgr_groups");
	$emaildomain = emailmanager_config_options("emailmgr_domain");
	$emailquota = emailmanager_config_options("emailmgr_quota");
	$webmailurl = emailmanager_config_options("emailmgr_webmailurl");
	$cpaneluser = emailmanager_config_options("emailmgr_cpaneluser");
	$cpanelpass = emailmanager_config_options("emailmgr_cpanelpass");
	$cpanelserver = emailmanager_config_options("emailmgr_cpanelip");
	$emailmsg = emailmanager_config_options("emailmgr_emailmsg");
	$aliasformat = emailmanager_config_options("emailmgr_aliasformat");
	$sendemail = (emailmanager_config_options("emailmgr_sendemail") ? " checked" : "");
	$matchpw = (emailmanager_config_options("emailmgr_matchpw") ? " checked" : "");
	$showpwfields = (emailmanager_config_options("emailmgr_showpwfields") ? " checked" : "");
	$disablewpnotify = (emailmanager_config_options("emailmgr_disablewpnotify") ? " checked" : "");
	$removeuser = (emailmanager_config_options("emailmgr_removeuser") ? " checked" : "");
	$mailinglist_cleanup = (emailmanager_config_options("emailmgr_mailinglist_cleanup") ? " checked" : "");
	$pw_uppercase = emailmanager_config_options("emailmgr_pw_uppercase");
	$pw_lowercase = emailmanager_config_options("emailmgr_pw_lowercase");
	$pw_numbers = emailmanager_config_options("emailmgr_pw_numbers");
	$pw_symbols = emailmanager_config_options("emailmgr_pw_symbols");
	$pw_characters = emailmanager_config_options("emailmgr_pw_characters");

	if ($cpaneluser != "" && $cpanelpass != "" && $cpanelserver != "") {
		$accountinfo = simplexml_load_string(emailmanager_cpanelAPI($cpaneluser, $cpanelpass, $cpanelserver, "diskusage"));
		if ($accountinfo->data->max != "") {
			$output .= '<h2 style="display:inline;">' . __('Configuration Options', 'cpanel-e-mail-manager'). '</h2> - <a href="users.php?page=emailmanager&mode=list">' . __('List of Accounts', 'cpanel-e-mail-manager') . '</a> | <a href="users.php?page=emailmanager&mode=linkaccounts">' . __('Link Existing Accounts', 'cpanel-e-mail-manager'). '</a> | <a href="users.php?page=emailmanager&mode=add">' . __('Add New Account', 'cpanel-e-mail-manager') . '</a> | <a href="users.php?page=emailmanager&mode=mailinglist">' . __('Manage Mailing Lists', 'cpanel-e-mail-manager') . '</a>' . (EMAILMGR_STD_MODE == 1 ? ' | <a href="users.php?page=emailmanager&mode=upgradeplugin">' . __('Upgrade Plugin', 'cpanel-e-mail-manager') . '</a>' : '');
			$output .= '<script type="text/javascript">
			function submitform() {
			var message = "' . __('The following fields are required', 'cpanel-e-mail-manager') . ':\n\n";
			if (document.getElementById("wordpressgroups").value == "") {
			  message = message + "' . __('At least one WordPress user group must be selected.', 'cpanel-e-mail-manager') . '\n";
			}
			if (document.getElementById("emaildomain").value == "") {
			  message = message + "' . __('E-Mail Domain', 'cpanel-e-mail-manager') . '\n";
			}
			if (document.getElementById("aliasformat").value == "") {
			  message = message + "' . __('E-Mail Alias Format', 'cpanel-e-mail-manager') . '\n";
			}
			if (document.getElementById("cpaneluser").value == "") {
			  message = message + "' . __('cPanel User ID', 'cpanel-e-mail-manager') . '\n";
			}
			if (document.getElementById("cpanelpass").value == "") {
			  message = message + "' . __('cPanel Password', 'cpanel-e-mail-manager') . '\n";
			}
			if (document.getElementById("cpanelserver").value == "") {
			  message = message + "' . __('cPanel Server IP (or localhost)', 'cpanel-e-mail-manager') . '\n";
			}
			if (message != "' . __('The following fields are required', 'cpanel-e-mail-manager') . ':\n\n") {
			  alert(message);
			  return false;
			}
			}
			function automationfields() {
			if (document.getElementById("autogenerate").checked) {
			  document.getElementById("wordpressgroups").disabled = false;
			  document.getElementById("emailquota").disabled = false;
			  document.getElementById("aliasformat").disabled = false;
			  document.getElementById("matchpw").disabled = false;
			  document.getElementById("showpwfields").disabled = false;
			  document.getElementById("disablewpnotify").disabled = false;
			  document.getElementById("mailinglist_cleanup").disabled = false;
			  document.getElementById("pw_uppercase").disabled = false;
			  document.getElementById("pw_lowercase").disabled = false;
			  document.getElementById("pw_numbers").disabled = false;
			  document.getElementById("pw_symbols").disabled = false;
			  document.getElementById("pw_characters").disabled = false;
			} else {
			  document.getElementById("wordpressgroups").disabled = true;
			  document.getElementById("emailquota").disabled = true;
			  document.getElementById("aliasformat").disabled = true;
			  document.getElementById("matchpw").disabled = true;
			  document.getElementById("showpwfields").disabled = true;
			  document.getElementById("disablewpnotify").disabled = true;
			  document.getElementById("mailinglist_cleanup").disabled = true;
			  document.getElementById("pw_uppercase").disabled = true;
			  document.getElementById("pw_lowercase").disabled = true;
			  document.getElementById("pw_numbers").disabled = true;
			  document.getElementById("pw_symbols").disabled = true;
			  document.getElementById("pw_characters").disabled = true;
			}
			}
			function emailfields() {
			if (document.getElementById("sendemail").checked) {
			  document.getElementById("emailmsg").disabled = false;
			} else {
			  document.getElementById("emailmsg").disabled = true;
			}
			}
			</script>
			<form action="users.php?page=emailmanager&mode=config" method="post" name="emailmgrconfig" id="emailmgrconfig" onsubmit="return submitform();"><table width="100%">
			<tr><td colspan="3"><p style="font-weight: bold; text-decoration:underline;">' . __('Automation', 'cpanel-e-mail-manager') . '</p></td></tr>';
			if (EMAILMGR_STD_MODE == 0) {
				$output .= '<tr><td align="left" width="35%"><label for="autogenerate" style="vertical-align: top;">' . __('Automatically Create E-mail Accounts', 'cpanel-e-mail-manager') . ':</label></td><td align="left" width="20%"><input type="checkbox" name="autogenerate" id="autogenerate"' . $autogenerate . ' onchange="automationfields();"></td><td align="left" width="45%"><label for="autogenerate" style="vertical-align: top;"><em>' . __('Automatically create e-mail account when a new user is registered.', 'cpanel-e-mail-manager') . '</em></label></td></tr><tr><td align="left" colspan="3">&nbsp;</td></tr>';
			} else {
				$output .= '<tr><td align="left" width="35%"><label for="autogenerate" style="vertical-align: top;">' . __('Automatically Create E-mail Accounts', 'cpanel-e-mail-manager') . '.</label></td><td align="left" width="20%"><input type="checkbox" name="autogenerate" id="autogenerate"' . $autogenerate . ' disabled></td><td align="left" width="45%"><label for="autogenerate" style="vertical-align: top;"><em>' . __('Automatically create e-mail account when a new user is registered.', 'cpanel-e-mail-manager') . '</em> (<a href="users.php?page=emailmanager&mode=upgradeplugin">' . __('Upgrade plugin', 'cpanel-e-mail-manager') . '</a> ' .__('to use this feature', 'cpanel-e-mail-manager') . '!)</label></td></tr><tr><td align="left" colspan="3">&nbsp;</td></tr>';
			}
			$output .= '<tr><td align="left" width="35%">
			<label for="wordpressgroups" style="vertical-align: top;">' . __('WordPress Groups', 'cpanel-e-mail-manager') . ':</label></td><td align="left" width="20%"><select name="wordpressgroups[]" id="wordpressgroups" size="6" multiple>';
			if ($wordpressgroups != "") {
				$wordpressgroups = explode(",", $wordpressgroups);
				if (in_array("Administrator", $wordpressgroups)) {
					$output .= '<option value="Administrator" selected>Administrator</option>';
				} else {
					$output .= '<option value="Administrator">Administrator</option>';
				}
				if (in_array("Author", $wordpressgroups)) {
					$output .= '<option value="Author" selected>Author</option>';
				} else {
					$output .= '<option value="Author">Author</option>';
				}
				if (in_array("Contributor", $wordpressgroups)) {
					$output .= '<option value="Contributor" selected>Contributor</option>';
				} else {
					$output .= '<option value="Contributor">Contributor</option>';
				}
				if (in_array("Editor", $wordpressgroups)) {
					$output .= '<option value="Editor" selected>Editor</option>';
				} else {
					$output .= '<option value="Editor">Editor</option>';
				}
				if (in_array("Subscriber", $wordpressgroups)) {
					$output .= '<option value="Subscriber" selected>Subscriber</option>';
				} else {
					$output .= '<option value="Subscriber">Subscriber</option>';
				}
			} else {
				$output .= '<option value="Administrator">Administrator</option>';
				$output .= '<option value="Author">Author</option>';
				$output .= '<option value="Contributor">Contributor</option>';
				$output .= '<option value="Editor">Editor</option>';
				$output .= '<option value="Subscriber">Subscriber</option>';
			}
			$output .= '</select></td><td align="left" width="45%"><label for="wordpressgroups" style="vertical-align: top;"><em>' . __('Select the user groups eligible for automatic e-mail accounts.', 'cpanel-e-mail-manager') . '</em></label></td></tr><tr><td align="left" colspan="3">&nbsp;</td></tr>
			<tr><td align="left" width="35%"><label for="emailquota" style="vertical-align: top;">' . __('E-Mail Quota', 'cpanel-e-mail-manager') . ' (MB):</label></td><td align="left" width="20%"><input type="text" name="emailquota" id="emailquota" size="10" value="' . $emailquota . '"></td><td align="left" width="45%"><label for="emailquota" style="vertical-align: top;"><em>' . __('Enter', 'cpanel-e-mail-manager') . ' \'unlimited\' ' . __('for unlimited storage', 'cpanel-e-mail-manager') . '.</em></label></td></tr><tr><td align="left" colspan="3">&nbsp;</td></tr>
			<tr><td align="left" width="35%"><label for="aliasformat" style="vertical-align: top;">' . __('E-Mail Alias Format', 'cpanel-e-mail-manager') . ':</label></td><td align="left" width="20%"><select name="aliasformat" id="aliasformat">';
			if ($aliasformat == "first.last") {
				$output .= '<option value="first.last" selected>First Name . Last Name</option>';
			} else {
				$output .= '<option value="first.last">First Name . Last Name</option>';
			}
			if ($aliasformat == "firstlast") {
				$output .= '<option value="firstlast" selected>First Name + Last Name</option>';
			} else {
				$output .= '<option value="firstlast">First Name + Last Name</option>';
			}
			if ($aliasformat == "firstl") {
				$output .= '<option value="firstl" selected>First Name + Last Initial</option>';
			} else {
				$output .= '<option value="firstl">First Name + Last Initial</option>';
			}
			if ($aliasformat == "username") {
				$output .= '<option value="username" selected>WordPress Username</option>';
			} else {
				$output .= '<option value="username">WordPress Username</option>';
			}
			$output .= '</select></td><td align="left" width="45%">&nbsp;</td></tr><tr><td align="left" colspan="3">&nbsp;</td></tr>
			<tr><td align="left" width="35%"><label for="matchpw" style="vertical-align: top;">' . __('Password Match', 'cpanel-e-mail-manager') . ':</label></td><td align="left" width="20%"><input type="checkbox" name="matchpw" id="matchpw"' . $matchpw . '></td><td align="left" width="45%"><label for="matchpw" style="vertical-align: top;"><em>' . __('If enabled, the plugin will match the WordPress login and cPanel e-mail passwords during WordPress user registrations.', 'cpanel-e-mail-manager') . '<br><br><strong>' . __('Note: If the WordPress password provided during registration does not meet minimum requirements, it will be replaced with a stronger password.', 'cpanel-e-mail-manager') . '</strong><br><br>' . __('Note: If enabled and using a community plugin such as BuddyPress or Ultimate Member, the e-mail account will be created at the time of user registration and not once the user\'s WordPress account has been activated/approved.', 'cpanel-e-mail-manager') . '</em></label></td></tr><tr><td align="left" colspan="3">&nbsp;</td></tr>
			<tr><td align="left" colspan="3"><label for="pwstrength" style="vertical-align: top;font-weight:bold;"><em>' . __('Password Strength', 'cpanel-e-mail-manager') . '</em></label></td></tr><tr><td align="left" colspan="3">&nbsp;</td></tr>	
			<tr><td align="left" width="35%">' . __('Number of Lowercase Characters', 'cpanel-e-mail-manager') . ':</td><td align="left" width="20%"><select name="pw_lowercase" id="pw_lowercase">
			<option value="1" ' . ($pw_lowercase == 1 ? "selected" : "") . '>1</option>
			<option value="2" ' . ($pw_lowercase == 2 ? "selected" : "") . '>2</option>
			<option value="3" ' . ($pw_lowercase == 3 ? "selected" : "") . '>3</option>
			<option value="4" ' . ($pw_lowercase == 4 ? "selected" : "") . '>4</option>
			<option value="5" ' . ($pw_lowercase == 5 ? "selected" : "") . '>5</option>
			</select></td><td align="left" width="45%" rowspan="5"><label for="pwstrength" style="vertical-align: top;"><em>' . __('Set the password strength criteria for cPanel e-mail accounts. If this password strength criteria is not met for new accounts, a stronger password will be generated.', 'cpanel-e-mail-manager') . '</em></label></td></tr>
			<tr><td align="left" width="35%">' . __('Number of Numeric Characters', 'cpanel-e-mail-manager') . ':</td><td align="left" width="20%"><select name="pw_numbers" id="pw_numbers">
			<option value="1" ' . ($pw_numbers == 1 ? "selected" : "") . '>1</option>
			<option value="2" ' . ($pw_numbers == 2 ? "selected" : "") . '>2</option>
			<option value="3" ' . ($pw_numbers == 3 ? "selected" : "") . '>3</option>
			<option value="4" ' . ($pw_numbers == 4 ? "selected" : "") . '>4</option>
			<option value="5" ' . ($pw_numbers == 5 ? "selected" : "") . '>5</option>
			</select></td></tr>
			<tr><td align="left" width="35%">' . __('Number of Uppercase Characters', 'cpanel-e-mail-manager') . ':</td><td align="left" width="20%"><select name="pw_uppercase" id="pw_uppercase">
			<option value="0" ' . ($pw_uppercase == 0 ? "selected" : "") . '>0</option>
			<option value="1" ' . ($pw_uppercase == 1 ? "selected" : "") . '>1</option>
			<option value="2" ' . ($pw_uppercase == 2 ? "selected" : "") . '>2</option>
			<option value="3" ' . ($pw_uppercase == 3 ? "selected" : "") . '>3</option>
			<option value="4" ' . ($pw_uppercase == 4 ? "selected" : "") . '>4</option>
			<option value="5" ' . ($pw_uppercase == 5 ? "selected" : "") . '>5</option>
			</select></td></tr>
			<tr><td align="left" width="35%">' . __('Number of Special Characters', 'cpanel-e-mail-manager') . ':</td><td align="left" width="20%"><select name="pw_symbols" id="pw_symbols">
			<option value="0" ' . ($pw_symbols == 0 ? "selected" : "") . '>0</option>
			<option value="1" ' . ($pw_symbols == 1 ? "selected" : "") . '>1</option>
			<option value="2" ' . ($pw_symbols == 2 ? "selected" : "") . '>2</option>
			<option value="3" ' . ($pw_symbols == 3 ? "selected" : "") . '>3</option>
			<option value="4" ' . ($pw_symbols == 4 ? "selected" : "") . '>4</option>
			<option value="5" ' . ($pw_symbols == 5 ? "selected" : "") . '>5</option>
			</select></td></tr>
			<tr><td align="left" width="35%">' . __('Minimum Password Length', 'cpanel-e-mail-manager') . ':</td><td align="left" width="20%"><select name="pw_characters" id="pw_characters">
			<option value="5" ' . ($pw_characters == 5 ? "selected" : "") . '>5</option>
			<option value="6" ' . ($pw_characters == 6 ? "selected" : "") . '>6</option>
			<option value="7" ' . ($pw_characters == 7 ? "selected" : "") . '>7</option>
			<option value="8" ' . ($pw_characters == 8 ? "selected" : "") . '>8</option>
			<option value="9" ' . ($pw_characters == 9 ? "selected" : "") . '>9</option>
			<option value="10" ' . ($pw_characters == 10 ? "selected" : "") . '>10</option>
			<option value="11" ' . ($pw_characters == 11 ? "selected" : "") . '>11</option>
			<option value="12" ' . ($pw_characters  == 12 ? "selected" : "") . '>12</option>
			<option value="13" ' . ($pw_characters == 13 ? "selected" : "") . '>13</option>
			<option value="14" ' . ($pw_characters == 14 ? "selected" : "") . '>14</option>
			<option value="15" ' . ($pw_characters == 15 ? "selected" : "") . '>15</option>
			</select></td></tr><tr><td align="left" colspan="3">&nbsp;</td></tr>
			<tr><td align="left" width="35%"><label for="showpwfields" style="vertical-align: top;">' . __('Display Password Fields', 'cpanel-e-mail-manager') . ':</label></td><td align="left" width="20%"><input type="checkbox" name="showpwfields" id="showpwfields"' . $showpwfields . '></td><td align="left" width="45%"><label for="showpwfields" style="vertical-align: top;"><em>' . __('Enabling this option will allow users to manually set their own password during new user registration. If these fields are not shown, a password will be automatically set.', 'cpanel-e-mail-manager') . '<br><strong>(' . __('Not applicable for BuddyPress installations', 'cpanel-e-mail-manager') . ')</strong></em></label></td></tr><tr><td align="left" colspan="3">&nbsp;</td></tr>
			<tr><td align="left" width="35%"><label for="disablewpnotify" style="vertical-align: top;"> ' .__('Disable Registration E-Mail', 'cpanel-e-mail-manager') . ':</label></td><td align="left" width="20%"><input type="checkbox" name="disablewpnotify" id="disablewpnotify"' . $disablewpnotify . '></td><td align="left" width="45%"><label for="disablewpnotify" style="vertical-align: top;"><em>' . __('You can choose to disable the WordPress New User Registration E-Mail from being sent.', 'cpanel-e-mail-manager') . '</em></label></td></tr><tr><td align="left" colspan="3">&nbsp;</td></tr>
			<tr><td align="left" width="35%"><label for="mailinglist_cleanup" style="vertical-align: top;">' . __('Mailing List Cleanup', 'cpanel-e-mail-manager') . ':</label></td><td align="left" width="20%"><input type="checkbox" name="mailinglist_cleanup" id="mailinglist_cleanup"' . $mailinglist_cleanup . '></td><td align="left" width="45%"><label for="mailinglist_cleanup" style="vertical-align: top;"><em>' . __('Automatically remove WordPress user e-mail addresses from associated mailing lists when a WordPress user is deleted.', 'cpanel-e-mail-manager') . '</em></label></td></tr>
			<tr><td align="left" colspan="3"><hr></td></tr>
			<tr><td align="left" colspan="3"><p style="font-weight: bold; text-decoration:underline;">' . __('E-Mail Settings', 'cpanel-e-mail-manager') . '</p></td></tr>';
			if (!is_multisite()) {
				$output .= '<tr><td align="left" width="35%"><span style="vertical-align: top;">' . __('E-Mail Accounts', 'cpanel-e-mail-manager') . ':</span></td><td align="left" width="20%">';
				$accountinfo = simplexml_load_string(emailmanager_cpanelAPI($cpaneluser, $cpanelpass, $cpanelserver, "emailcount"));
				$output .= $accountinfo->data->count . ' / ' . $accountinfo->data->max;
				$output .= '</td><td align="left" width="45%">&nbsp;</td></tr><tr><td align="left" colspan="3">&nbsp;</td></tr>
				<tr><td align="left" width="35%"><span style="vertical-align: top;">' . __('Disk Space Usage', 'cpanel-e-mail-manager') . ': </span></td><td align="left" width="20%">';
				$accountinfo = simplexml_load_string(emailmanager_cpanelAPI($cpaneluser, $cpanelpass, $cpanelserver, "diskusage"));
				$output .= $accountinfo->data->count . ' / ' . $accountinfo->data->max;
				$output .= '</td><td align="left" width="45%">&nbsp;</td></tr><tr><td align="left" colspan="3">&nbsp;</td></tr>';
			}
			$output .= '<tr><td align="left" width="35%"><label for="emaildomain" style="vertical-align: top;">' . __('E-Mail Domain', 'cpanel-e-mail-manager') . ':</label></td><td align="left" width="20%"><select name="emaildomain" id="emaildomain">';
			if ($cpaneluser != "" && $cpanelpass != "" && $cpanelserver != "") {
				$domains = simplexml_load_string(emailmanager_cpanelAPI($cpaneluser, $cpanelpass, $cpanelserver, "listemaildomains"));
				foreach ($domains->data as $domain) {
					if (function_exists('is_multisite') && is_multisite()) {
						if ($domain->domain == $emaildomain) {
							$output .= '<option value="'. $domain->domain .'" selected>'. $domain->domain .'</option>';
						}
					} else {
						if ($domain->domain == $emaildomain) {
							$output .= '<option value="'. $domain->domain .'" selected>'. $domain->domain .'</option>';
						} else {
							$output .= '<option value="'. $domain->domain .'">'. $domain->domain .'</option>';
						}
					}
				}
			}
			$output .= '</select></td><td align="left" width="45%">&nbsp;</td></tr><tr><td align="left" colspan="3">&nbsp;</td></tr>
			<tr><td align="left" width="35%"><label for="webmailurl" style="vertical-align: top;">' . __('Webmail URL', 'cpanel-e-mail-manager') . ':</label></td><td align="left" width="20%"><input type="text" name="webmailurl" id="webmailurl" value="' . $webmailurl . '" size="50"></td><td align="left" width="45%">&nbsp;</td></tr><tr><td align="left" colspan="3">&nbsp;</td></tr>
			<tr><td align="left" width="35%"><label for="removeuser" style="vertical-align: top;">' . __('Remove User', 'cpanel-e-mail-manager') . ':</label></td><td align="left" width="20%"><input type="checkbox" name="removeuser" id="removeuser"' . $removeuser . '></td><td align="left" width="45%"><label for="removeuser" style="vertical-align: top;"><em>' . __('Automatically remove associated cPanel e-mail accounts when a WordPress user is deleted.', 'cpanel-e-mail-manager') . ' <font color="red"><strong><u>' . __('WARNING', 'cpanel-e-mail-manager') . ':</u> ' . __('This action is irreversible and will delete all data from the user\'s e-mail account.', 'cpanel-e-mail-manager') . '</strong></font></em></label></td></tr>';
			if (EMAILMGR_CPANEL_HIDE == 1 || EMAILMGR_CPANEL_GLOBAL == 1) {
				//HIDE CPANEL FIELDS
			} else {
				$output .= '<tr><td align="left" colspan="3"><hr></td></tr>
				<tr><td align="left" colspan="3"><p style="font-weight: bold; text-decoration:underline;">' . __('cPanel Server Credentials', 'cpanel-e-mail-manager') . '</p></td></tr>
				<tr><td align="left" width="35%"><label for="cpaneluser" style="vertical-align: top;">' . __('cPanel User ID', 'cpanel-e-mail-manager') . ':</label></td><td align="left" width="20%"><input type="text" name="cpaneluser" id="cpaneluser" value="' . $cpaneluser . '"></td><td align="left" width="45%">&nbsp;</td></tr><tr><td align="left" colspan="3">&nbsp;</td></tr>
				<tr><td align="left" width="35%"><label for="cpanelpass" style="vertical-align: top;">' . __('cPanel Password', 'cpanel-e-mail-manager') . ':</label></td><td align="left" width="20%"><input type="password" name="cpanelpass" id="cpanelpass" value="' . $cpanelpass . '"></td><td align="left" width="45%">&nbsp;</td></tr><tr><td align="left" colspan="3">&nbsp;</td></tr>
				<tr><td align="left" width="35%"><label for="cpanelserver" style="vertical-align: top;">' . __('cPanel Server IP', 'cpanel-e-mail-manager') . ':</label></td><td align="left" width="20%"><input type="text" name="cpanelserver" id="cpanelserver" value="' . $cpanelserver . '"></td><td align="left" width="45%"><label for="cpanelserver" style="vertical-align: top;"><em>' . __('Enter', 'cpanel-e-mail-manager') . ' \'localhost\' ' . __('or', 'cpanel-e-mail-manager') . ' \'127.0.0.1\' ' . __('if logging into cPanel on the same server as this WordPress install', 'cpanel-e-mail-manager') . '.</em></label></td></tr>';
			}
			$output .= '<tr><td align="left" colspan="3"><hr></td></tr>
			<tr><td align="left" colspan="3"><p style="font-weight: bold; text-decoration:underline;">' . __('E-Mail Notification Settings (will send to user\'s primary e-mail account)', 'cpanel-e-mail-manager') . '</p></td></tr>';
			if (EMAILMGR_STD_MODE == 0) {
				$output .= '<tr><td align="left" width="35%"><label for="sendemail" style="vertical-align: top;">' . __('Enable E-Mail Notification', 'cpanel-e-mail-manager') . ':</label></td><td align="left" width="20%"><input type="checkbox" name="sendemail" id="sendemail"' . $sendemail . ' onchange="emailfields();"></td><td align="left" width="45%">&nbsp;</td></tr><tr><td align="left" colspan="3">&nbsp;</td></tr>';
			} else {
				$output .= '<tr><td align="left" width="35%"><label for="sendemail" style="vertical-align: top;">' . __('Enable E-Mail Notification', 'cpanel-e-mail-manager') . ':</label></td><td align="left" width="20%"><input type="checkbox" name="sendemail" id="sendemail"' . $sendemail . ' disabled> (<a href="users.php?page=emailmanager&mode=upgradeplugin">' . __('Upgrade plugin', 'cpanel-e-mail-manager') . '</a> ' . __('to use this feature', 'cpanel-e-mail-manager') . '!)</td><td align="left" width="45%">&nbsp;</td></tr><tr><td align="left" colspan="3">&nbsp;</td></tr>';
			}
				$output .= '<tr><td align="left" width="35%"><label for="emailmsg" style="vertical-align: top;">' . __('E-Mail Message', 'cpanel-e-mail-manager') . ':</label></td><td align="left" colspan="2"><textarea name="emailmsg" id="emailmsg" rows="15" cols="70">' . $emailmsg . '</textarea></td></tr>
				<tr><td align="left" width="35%">&nbsp;</td><td align="left" colspan="2"><label for="emailmsg" style="vertical-align: top;"><em>' . __('The following variables can be used', 'cpanel-e-mail-manager') . ':<br><br>{$firstname} = ' . __('User\'s first name', 'cpanel-e-mail-manager') . '<br>{$lastname} = ' . __('User\'s last name', 'cpanel-e-mail-manager') . '<br>{$username} = ' . __('User\'s username/login ID', 'cpanel-e-mail-manager') . '<br>{$regemail} = ' . __('User\'s registered WordPress e-mail address', 'cpanel-e-mail-manager') . '<br>{$email} = ' . __('cPanel e-mail address assigned to user', 'cpanel-e-mail-manager') . '<br>{$temppw} = ' . __('Temporary password for webmail', 'cpanel-e-mail-manager') . '<br>{$webmailurl} = ' . __('Webmail URL', 'cpanel-e-mail-manager') . (preg_match('/(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\/~\b[a-zA-Z0-9_]+\b)/i', get_home_url()) ? "" : '<br>{$siteurl} = ' . __('WordPress Site URL', 'cpanel-e-mail-manager')) . '</em></label></td></tr>
			<script type="text/javascript">
			automationfields();
			emailfields();
			</script>
			<tr><td align="left" colspan="3"><input type="submit" value="' . __('Save Settings', 'cpanel-e-mail-manager') . '" class="button button-primary"></td></tr></table>
			</form>
			';
		} else {
			if (EMAILMGR_CPANEL_HIDE == 1 || EMAILMGR_CPANEL_GLOBAL == 1) {
				return '<h2 style="display:inline;">' . __('Configuration Options', 'cpanel-e-mail-manager') . '</h2><p>&nbsp;</p><p>&nbsp;</p><p style="text-align:center; font-weight: bold;font-size: 16px;">' . __('The cPanel credentials entered are not valid or a valid domain has not been selected. A connection could not be established.', 'cpanel-e-mail-manager') . '</p><p style="text-align:center; font-weight: bold;font-size: 16px;">' . __('Please contact your server administrator for assistance.', 'cpanel-e-mail-manager') . '</p>';
				exit;
			} else {
				$output .= '<div class="error">
				<p>' . __('The cPanel credentials entered are not valid or a valid domain has not been selected. A connection could not be established.', 'cpanel-e-mail-manager') . '</p>
				</div><br>
				<h2>' . __('Configuration Options', 'cpanel-e-mail-manager') . '</h2>
				<script type="text/javascript">
				function submitform() {
				var message = "' . __('The following fields are required', 'cpanel-e-mail-manager') . ':\n\n";
				if (document.getElementById("cpaneluser").value == "") {
				  message = message + "' . __('cPanel User ID', 'cpanel-e-mail-manager') . '\n";
				}
				if (document.getElementById("cpanelpass").value == "") {
				  message = message + "' . __('cPanel Password', 'cpanel-e-mail-manager') . '\n";
				}
				if (document.getElementById("cpanelserver").value == "") {
				  message = message + "' . __('cPanel Server IP (or localhost)', 'cpanel-e-mail-manager') . '\n";
				}
				if (message != "' . __('The following fields are required', 'cpanel-e-mail-manager') . ':\n\n") {
				  alert(message);
				  return false;
				}
				}
				</script>
				<form action="users.php?page=emailmanager&mode=config&action=setcpanel" method="post" name="emailmgrconfig" id="emailmgrconfig" onsubmit="return submitform();">
				<p style="font-size:14px;">' . __('Please enter your cPanel login credentials and click Save Changes to continue.', 'cpanel-e-mail-manager') . '</p>
				<p><label for="cpaneluser" style="vertical-align: top; float: left; width: 10em;">' . __('cPanel User ID', 'cpanel-e-mail-manager') . ':</label> <input type="text" name="cpaneluser" id="cpaneluser" value="' . $cpaneluser . '"></p>
				<p><label for="cpanelpass" style="vertical-align: top; float: left; width: 10em;">' . __('cPanel Password', 'cpanel-e-mail-manager') . ':</label> <input type="password" name="cpanelpass" id="cpanelpass" value="' . $cpanelpass . '"></p>
				<p><label for="cpanelserver" style="vertical-align: top; float: left; width: 10em;">' . __('cPanel Server IP', 'cpanel-e-mail-manager') . ':</label> <input type="text" name="cpanelserver" id="cpanelserver" value="' . $cpanelserver . '" style="float: left;"><label for="cpanelserver" style="vertical-align: top; float: left; width: 50em; margin-left: 1em;"><em>' . __('Enter', 'cpanel-e-mail-manager') . ' \'localhost\' ' . __('or', 'cpanel-e-mail-manager') . ' \'127.0.0.1\' ' . __('if logging into cPanel on the same server as this WordPress install', 'cpanel-e-mail-manager') . '.</em></label></p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p><input type="submit" value="' . __('Save Settings', 'cpanel-e-mail-manager') . '" class="button button-primary"></p>
				</form>
				';
			}
		}
	} else {
		if (EMAILMGR_CPANEL_HIDE == 1 || EMAILMGR_CPANEL_GLOBAL == 1) {
			return '<h2 style="display:inline;">' . __('Configuration Options', 'cpanel-e-mail-manager') . '</h2><p>&nbsp;</p><p>&nbsp;</p><p style="text-align:center; font-weight: bold;font-size: 16px;">' . __('The cPanel credentials entered are not valid or a valid domain has not been selected. A connection could not be established.', 'cpanel-e-mail-manager') . '</p><p style="text-align:center; font-weight: bold;font-size: 16px;">' . __('Please contact your server administrator for assistance.', 'cpanel-e-mail-manager') . '</p>';
				exit;
		} else {
			$output .= '<h2>' . __('Configuration Options', 'cpanel-e-mail-manager') . '</h2>
			<script type="text/javascript">
			function submitform() {
			var message = "' . __('The following fields are required', 'cpanel-e-mail-manager') . ':\n\n";
			if (document.getElementById("cpaneluser").value == "") {
			  message = message + "' . __('cPanel User ID', 'cpanel-e-mail-manager') . '\n";
			}
			if (document.getElementById("cpanelpass").value == "") {
			  message = message + "' . __('cPanel Password', 'cpanel-e-mail-manager') . '\n";
			}
			if (document.getElementById("cpanelserver").value == "") {
			  message = message + "' . __('cPanel Server IP (or localhost)', 'cpanel-e-mail-manager') . '\n";
			}
			if (message != "' . __('The following fields are required', 'cpanel-e-mail-manager') . ':\n\n") {
			  alert(message);
			  return false;
			}
			}
			</script>
			<form action="users.php?page=emailmanager&mode=config&action=setcpanel" method="post" name="emailmgrconfig" id="emailmgrconfig" onsubmit="return submitform();">
			<p style="font-size:14px;">' . __('Please enter your cPanel login credentials and click Save Changes to continue.', 'cpanel-e-mail-manager') . '</p>
			<p><label for="cpaneluser" style="vertical-align: top; float: left; width: 10em;">' . __('cPanel User ID', 'cpanel-e-mail-manager') . ':</label> <input type="text" name="cpaneluser" id="cpaneluser" value="' . $cpaneluser . '"></p>
			<p><label for="cpanelpass" style="vertical-align: top; float: left; width: 10em;">' . __('cPanel Password', 'cpanel-e-mail-manager') . ':</label> <input type="password" name="cpanelpass" id="cpanelpass" value="' . $cpanelpass . '"></p>
			<p><label for="cpanelserver" style="vertical-align: top; float: left; width: 10em;">' . __('cPanel Server IP', 'cpanel-e-mail-manager') . ':</label> <input type="text" name="cpanelserver" id="cpanelserver" value="' . $cpanelserver . '" style="float: left;"><label for="cpanelserver" style="vertical-align: top; float: left; width: 50em; margin-left: 1em;"><em>' . __('Enter', 'cpanel-e-mail-manager') . ' \'localhost\' ' . __('or', 'cpanel-e-mail-manager') . ' \'127.0.0.1\' ' . __('if logging into cPanel on the same server as this WordPress install', 'cpanel-e-mail-manager') . '.</em></label></p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p><input type="submit" value="' . __('Save Settings', 'cpanel-e-mail-manager') . '" class="button button-primary"></p>
			</form>
			';
		}
	}
return $output;
}

function emailmanager_output_list() {
	global $wpdb;
	$output = '';
	//PROCESS UPDATES
	if ( isset( $_GET['action'] ) && !empty( $_GET['action'] ) && isset( $_POST['uservalue'] ) && !empty( $_POST['uservalue'] ) && isset( $_POST['user'] ) && !empty( $_POST['user'] ) ) {
		if ($_GET["action"] == "changepw") {
			//Set config options
			$emaildomain = emailmanager_config_options("emailmgr_domain");
			$cpaneluser = emailmanager_config_options("emailmgr_cpaneluser");
			$cpanelpass = emailmanager_config_options("emailmgr_cpanelpass");
			$cpanelserver = emailmanager_config_options("emailmgr_cpanelip");

			//REMOVE USER FROM CPANEL
			$optional['email_domain'] = $emaildomain;
			$optional['email_user'] = $_POST['user'];
			$optional['password'] = $_POST['uservalue'];

			emailmanager_cpanelAPI($cpaneluser, $cpanelpass, $cpanelserver, "changepw", $optional);
			$output .= '<div class="updated">
			<p>' . sprintf(__('The password for %s has been successfully changed', 'cpanel-e-mail-manager'), $_POST['user']) . '!</p>
			</div><br>';
		}
		if ($_GET["action"] == "changequota") {
			//Set config options
			$emaildomain = emailmanager_config_options("emailmgr_domain");
			$cpaneluser = emailmanager_config_options("emailmgr_cpaneluser");
			$cpanelpass = emailmanager_config_options("emailmgr_cpanelpass");
			$cpanelserver = emailmanager_config_options("emailmgr_cpanelip");

			//REMOVE USER FROM CPANEL
			$optional['email_domain'] = $emaildomain;
			$optional['email_user'] = $_POST['user'];
			$optional['quota'] = $_POST['uservalue'];

			emailmanager_pro_cpanelAPI($cpaneluser, $cpanelpass, $cpanelserver, "changequota", $optional);
			$output .= '<div class="updated">
			<p>' . sprintf(__('The quota for %s has been successfully changed', 'cpanel-e-mail-manager'), $_POST['user']) . '!</p>
			</div><br>';
		}
		if ($_GET["action"] == "delete") {
			emailmanager_remove_email($_POST["user"]);
			$output .= '<div class="updated">
			<p>' . sprintf(__('The e-mail account for %s has been successfully removed', 'cpanel-e-mail-manager'), $_POST['user']) . '!</p>
			</div><br>';
		}
		if ($_GET["action"] == "add") {
			//Get submitted values
			$userid = (isset($_POST['user']) ? $_POST['user'] : "");
			if (empty($userid) || $userid == "na") {
				$firstname = "";
				$lastname = "";
				$username = "";
				$email = "";
			} else {
				$user_info = get_userdata($userid);
				$firstname = $user_info->first_name;
				$lastname = $user_info->last_name;
				$username = $user_info->user_login;
				$email = $user_info->user_email;
			}
			$customformat = (isset($_POST['aliasformat']) ? $_POST['aliasformat'] : "");
			$customalias = (isset($_POST['emailalias']) ? $_POST['emailalias'] : "");
			$customquota = (isset($_POST['emailquota']) ? $_POST['emailquota'] : "");
			$custompass = (isset($_POST['passwd']) ? $_POST['passwd'] : "");
			if ($customformat != "custom") {
				$customalias = "";
			}
			$output .= emailmanager_create_email($userid, $firstname, $lastname, $username, $email, $customformat, $customalias, $customquota, $custompass);
		}
		if ($_GET["action"] == "link") {
			$cnt = 1;
			$accounts = array();
			while ($cnt <= $_POST['numfields']) {
				if ($_POST['email_' . $cnt] != "" && $_POST['user_' . $cnt] != "") {
					$accounts[] = $_POST['email_' . $cnt];
				}
				$cnt++;
			}
			if (!emailmanager_array_dup($accounts)) {
				$cnt = 1;
				while ($cnt <= $_POST['numfields']) {
					if ($_POST['email_' . $cnt] != "" && $_POST['user_' . $cnt] != "") {
						update_user_option( $_POST['user_' . $cnt], 'emailmgr_alias', $_POST['email_' . $cnt] );
					}
					$cnt++;
				}
				$output .= '<div class="updated">
				<p>' . __('User accounts have been successfully linked', 'cpanel-e-mail-manager') . '!</p>
				</div><br>';
			} else {
				$output .= '<div class="error">
				<p>' . __('More than one user account was assigned to the same e-mail address. You can only assign each e-mail address to a single WordPress user account.', 'cpanel-e-mail-manager') . '</p>
				</div><br>';
			}
		}
	}

	//DISPLAY LIST OF ACCOUNTS
	//Set config options
	$cpaneluser = emailmanager_config_options("emailmgr_cpaneluser");
	$cpanelpass = emailmanager_config_options("emailmgr_cpanelpass");
	$cpanelserver = emailmanager_config_options("emailmgr_cpanelip");
	$emaildomain = emailmanager_config_options("emailmgr_domain");

	if ($cpaneluser != "" && $cpanelpass != "" && $cpanelserver != "" && $emaildomain != "") {
		$output .= '<h2 style="display:inline;">' . __('List of Accounts', 'cpanel-e-mail-manager') . '</h2> - <a href="users.php?page=emailmanager&mode=config">' . __('Configuration Options', 'cpanel-e-mail-manager') . '</a> | <a href="users.php?page=emailmanager&mode=linkaccounts">' . __('Link Existing Accounts', 'cpanel-e-mail-manager') . '</a> | <a href="users.php?page=emailmanager&mode=add">' . __('Add New Account', 'cpanel-e-mail-manager') . '</a> | <a href="users.php?page=emailmanager&mode=mailinglist">' . __('Manage Mailing Lists', 'cpanel-e-mail-manager') . '</a>';
		$optional['domain'] = $emaildomain;
		$accounts = simplexml_load_string(emailmanager_cpanelAPI($cpaneluser, $cpanelpass, $cpanelserver, "getaccountlist", $optional));

		if ($accounts->data->user != "") {
			$output .= '<script type="text/javascript">
			var openMenu = 0;
			var openMenuType = "";
			function changepw(user, row) {
			var passwd = document.getElementById("passwd_" + row).value;
			var passwdstr = document.getElementById("passwdstr_" + row).value;
			var errmsg = "";
			if (passwd=="")
			  {
			  errmsg = errmsg + "' . __('You must enter a valid password.', 'cpanel-e-mail-manager') . '\n";
			  }
			if (passwdstr<2)
			  {
			  errmsg = errmsg + "' . __('The password entered does not meet minimum requirements.', 'cpanel-e-mail-manager') . '\n";
			  }
			if (errmsg=="")
			  {
			  document.getElementById("uservalue").value = passwd;
			  document.getElementById("user").value = user;
			  document.updateuser.action = location.protocol + "//" + location.hostname + location.pathname + "?page=emailmanager&mode=list" + "&action=changepw";
			  document.updateuser.submit();
			  } else {
			  alert("' . __('The password could not be updated for the following reasons', 'cpanel-e-mail-manager') . ':\n\n" + errmsg);
			  }
			  return false;
			}
			function changequota(user, row) {
			var quotachk=jQuery(\'input[name=quotagrp_\' + row + \']:checked\').val();
			if (quotachk=="unlimited")
			  {
			  var quota="unlimited";
			  } else {
			  var quota=document.getElementById("quota_" + row).value;
			  }
			if (isNaN(quota)==true && quota!="unlimited")
			  {
			  alert("' . __('You must enter a valid quota.', 'cpanel-e-mail-manager') . '");
			  return false;
			  }
			if (quota!="")
			  {
			  document.getElementById("uservalue").value = quota;
			  document.getElementById("user").value = user;
			  document.updateuser.action = location.protocol + "//" + location.hostname + location.pathname + "?page=emailmanager&mode=list" + "&action=changequota";
			  document.updateuser.submit();
			  } else {
			  alert("' . __('You must enter a valid quota.', 'cpanel-e-mail-manager') . '");
			  }
			  return false;
			}
			function removeacct(user) {
			var r=confirm("' . __('Are you sure you want to delete this e-mail account and all associated data?', 'cpanel-e-mail-manager') . '");
			if (r==true)
			  {
			  document.getElementById("user").value = user;
			  document.getElementById("uservalue").value = "na";
			  document.updateuser.action = location.protocol + "//" + location.hostname + location.pathname + "?page=emailmanager&mode=list" + "&action=delete";
			  document.updateuser.submit();
			  }
			}
			function generatepw(field, row) {
			  var length = 15,
			  charset = "abcdefghijklnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()",
			  retVal = "";
			  for (var i = 0, n = charset.length; i < length; ++i) {
			    retVal += charset.charAt(Math.floor(Math.random() * n));
			  }
			  document.getElementById(field).value = retVal;
			  chkPasswordStrength(document.getElementById(field).value, row);
			}
			function showchpasswd(row, type) {
			  hidechpasswd(openMenu);
			  hidechquota(openMenu);
			  if (openMenu != row || openMenuType != type) {
			  jQuery(\'#chpasswordtd_\' + row).show();
			  jQuery(\'#chpassword_\' + row).slideDown(\'slow\');
			  openMenu = row;
			  openMenuType = type;
			  } else {
			  openMenu = 0;
			  openMenuType = "";
			  }
			}
			function hidechpasswd(row) {
			  jQuery(\'#chpasswordtd_\' + row).hide();
			  jQuery(\'#chpassword_\' + row).hide();
			}
			function showchquota(row, type) {
			  hidechquota(openMenu);
			  hidechpasswd(openMenu);
			  if (openMenu != row || openMenuType != type) {
			  jQuery(\'#chquotatd_\' + row).show();
			  jQuery(\'#chquota_\' + row).slideDown(\'slow\');
			  openMenu = row;
			  openMenuType = type;
			  } else {
			  openMenu = 0;
			  openMenuType = "";
			  }
			}
			function hidechquota(row) {
			  jQuery(\'#chquotatd_\' + row).hide();
			  jQuery(\'#chquota_\' + row).hide();
			}
			function chkPasswordStrength(txtpass, row)
			   {
			     var desc = new Array();
			     desc[0] = "' . __('Very Weak (Password is not strong enough)', 'cpanel-e-mail-manager') . '";
			     desc[1] = "' . __('Weak (Password is not strong enough)', 'cpanel-e-mail-manager') . '";
			     desc[2] = "' . __('Better', 'cpanel-e-mail-manager') . '";
			     desc[3] = "' . __('Medium', 'cpanel-e-mail-manager') . '";
			     desc[4] = "' . __('Strong', 'cpanel-e-mail-manager') . '";
			     desc[5] = "' . __('Strongest', 'cpanel-e-mail-manager') . '";

			     document.getElementById("strenghtMsg_" + row).innerHTML = "";
			     var score   = 0;

			     //if txtpass bigger than 6 give 1 point
			     if (txtpass.length > 6) score++;

			     //if txtpass has both lower and uppercase characters give 1 point
			     if ( ( txtpass.match(/[a-z]/) ) && ( txtpass.match(/[A-Z]/) ) ) score++;

			     //if txtpass has at least one number give 1 point
			     if (txtpass.match(/\d+/)) score++;

			     //if txtpass has at least one special caracther give 1 point
			     if ( txtpass.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/) ) score++;

			     //if txtpass bigger than 12 give another 1 point
			     if (txtpass.length > 12) score++;

			     document.getElementById("strenghtMsg_" + row).innerHTML = "' . __('Strength', 'cpanel-e-mail-manager') . ': " + desc[score];
			     document.getElementById("passwdstr_" + row).value = score;

			     if (txtpass.length < 6)
			     {
			     document.getElementById("strenghtMsg_" + row).innerHTML = "' . __('Password Should be Minimum 6 Characters', 'cpanel-e-mail-manager') . '";
			     }
			   }
			</script>';
			if (!is_multisite()) {
				$output .= '<p style="text-align: center;">' . __('Active Accounts', 'cpanel-e-mail-manager') . ': <em>';
				$accountinfo = simplexml_load_string(emailmanager_cpanelAPI($cpaneluser, $cpanelpass, $cpanelserver, "emailcount"));
				$output .= $accountinfo->data->count . ' / ' . $accountinfo->data->max;
				$output .= '</em>&nbsp;&nbsp;-&nbsp;&nbsp;' . __('Disk Usage', 'cpanel-e-mail-manager') . ': <em>';
				$accountinfo = simplexml_load_string(emailmanager_cpanelAPI($cpaneluser, $cpanelpass, $cpanelserver, "diskusage"));
				$output .= $accountinfo->data->count . ' / ' . $accountinfo->data->max;
				$output .= '</em></p>';
			} else {
				$output .= '<p>&nbsp;</p>';
			}

			$numrecords = count($accounts->data);
			$numpages = ceil($numrecords / 10);
			if ( isset( $_GET['paged'] ) && !empty( $_GET['paged'] )) {
				$currpage = $_GET['paged'];
			} else {
				$currpage = 1;
			}

			if ($currpage == 1) {
				$startrec = 0;
				$prevpage = 1;
			} else {
				$startrec = ($currpage * 10) - 10;
				$prevpage = $currpage - 1;
			}

			if ($currpage != $numpages) {
				$nextpage = $currpage + 1;
			} else {
				$nextpage = $currpage;
			}

			$output .= '<table style="width:96%; margin-left:auto; margin-right:auto;"><tr><td align="right"><div class="tablenav-pages"><span class="displaying-num">(' . $numrecords . ' ' . __('items', 'cpanel-e-mail-manager') . ')&nbsp;</span><span class="pagination-links">';

			if ($currpage != 1) {
				$output .= '<span class="tablenav-pages-navspan" onclick="location.href = \'users.php?page=emailmanager&paged=1\'" style="cursor: pointer;">&laquo;</span>&nbsp;<span class="tablenav-pages-navspan" onclick="location.href = \'users.php?page=emailmanager&paged=' . $prevpage . '\'" style="cursor: pointer;">&lsaquo;</span>&nbsp;';
			}

			$output .=  __('Current Page', 'cpanel-e-mail-manager') . ' <input class="current-page" id="paged" type="text" name="paged" value="' . $currpage . '" size="1" /> ' . __('of', 'cpanel-e-mail-manager') . ' ' . $numpages;

			if ($currpage != $numpages) {
				$output .= '&nbsp;<span class="tablenav-pages-navspan" onclick="location.href = \'users.php?page=emailmanager&paged=' . $nextpage . '\'" style="cursor: pointer;">&rsaquo;</span>&nbsp;<span class="tablenav-pages-navspan" onclick="location.href = \'users.php?page=emailmanager&paged=' . $numpages . '\'" style="cursor: pointer;">&raquo;</span>';
			}

			$output .= '</span></div></td></tr></table><table class="wp-list-table widefat fixed pages" style="width:96%; margin-left:auto; margin-right:auto;">
			<thead>
			<tr>
			<th class="manage-column">' . __('User', 'cpanel-e-mail-manager') . '</th>
			<th class="manage-column" style="text-align:center;">' . __('E-Mail Account', 'cpanel-e-mail-manager') . '</th>
			<th class="manage-column" style="text-align:center;">' . __('Disk Usage', 'cpanel-e-mail-manager') . '</th>
			<th class="manage-column" style="text-align:center;">' . __('Actions', 'cpanel-e-mail-manager') . '</th>
			</tr></thead><tbody>';
			$sortarray = "";
			$cnt = 0;
			foreach ($accounts->data as $account) {
				if (emailmanager_emailalias_userid($account->user) != "") {
					$user_info = get_userdata(emailmanager_emailalias_userid($account->user));
					$sortarray[$cnt]['login'] = $account->login;
					$sortarray[$cnt]['humandiskused'] = $account->humandiskused;
					$sortarray[$cnt]['humandiskquota'] = ($account->humandiskquota == "None" ? "Unlimited" : $account->humandiskquota);
					$sortarray[$cnt]['user'] = $account->user;
					$sortarray[$cnt]['first_name'] = $user_info->first_name;
					$sortarray[$cnt]['last_name'] = $user_info->last_name;
					$sortarray[$cnt]['wplogin'] = $user_info->user_login;
				} else {
					$user_info = get_userdata(emailmanager_emailalias_userid($account->user));
					$sortarray[$cnt]['login'] = $account->login;
					$sortarray[$cnt]['humandiskused'] = $account->humandiskused;
					$sortarray[$cnt]['humandiskquota'] = ($account->humandiskquota == "None" ? "Unlimited" : $account->humandiskquota);
					$sortarray[$cnt]['user'] = $account->user;
					$sortarray[$cnt]['first_name'] = __('Not Linked to WordPress', 'cpanel-e-mail-manager');
					$sortarray[$cnt]['last_name'] = "";
					$sortarray[$cnt]['wplogin'] = "<a href='users.php?page=emailmanager&mode=linkaccounts'>" . __('Link Now', 'cpanel-e-mail-manager') . "</a>";
				}
				$cnt++;
			}
			if ($sortarray != "") {
				usort($sortarray, "emailmanager_sort_by_login");
				$accounts = $sortarray;
				$cnt = 1;
				foreach (array_slice($accounts, $startrec, 10) as $account) 
				{
					$output .= '<tr class="type-page status-publish hentry ' . ($cnt % 2 == 0 ? "alternate " : "") . 'iedit author-self level-0">
					<td>' . $account['last_name'] . (!empty($account['last_name']) && !empty($account['first_name']) ? ", " : "") . $account['first_name'] . ' (' . $account['wplogin'] . ')</td>
					<td style="text-align:center;">' . $account['login'] . '</td>
					<td style="text-align:center;">' . $account['humandiskused'] . ' / ' . $account['humandiskquota'] . ' </td>';
					if (EMAILMGR_STD_MODE == 0) {
						$output .= '<td style="text-align:center;"><button onclick="showchpasswd(' . $cnt . ', \'pass\');" class="button button-primary">' . __('Change Password', 'cpanel-e-mail-manager') . '</button>&nbsp;<button onclick="showchquota(' . $cnt . ', \'quota\');" class="button button-primary">' . __('Change Quota', 'cpanel-e-mail-manager') . '</button>&nbsp;<button onclick="removeacct(\'' . $account['user'] . '\');" class="button button-primary" style="background-color: #C00000;">' . __('Delete Account', 'cpanel-e-mail-manager') . '</button></td></tr>';
					} else {
						$output .= '<td style="text-align:center;"><button onclick="showchpasswd(' . $cnt . ');" class="button button-primary" disabled>' . __('Change Password', 'cpanel-e-mail-manager') . '</button>&nbsp;<button onclick="showchquota(' . $cnt . ');" class="button button-primary" disabled>' . __('Change Quota', 'cpanel-e-mail-manager') . '</button>&nbsp;<button onclick="removeacct(\'' . $account['user'] . '\');" class="button button-primary" style="background-color: #C00000;">' . __('Delete Account', 'cpanel-e-mail-manager') . '</button><br>(<a href="users.php?page=emailmanager&mode=upgradeplugin">' . __('Upgrade plugin', 'cpanel-e-mail-manager') . '</a> ' . __('to enable all features', 'cpanel-e-mail-manager') . '!)</td></tr>';
					}
					$output .= '<tr class="type-page status-publish hentry ' . ($cnt % 2 == 0 ? "alternate " : "") . 'iedit author-self level-0"><td colspan="4" id="chpasswordtd_' . $cnt . '" name="chpasswordtd_' . $cnt . '" align="center" style="display:none;">
					<div id="chpassword_' . $cnt . '" name="chpassword_' . $cnt . '" style="display:none;background-color:#00CCFF;padding-top:5px;padding-bottom:5px;">
					<form name="passwdupdate_' . $cnt . '" id="passwdupdate_' . $cnt . '" method="post" onsubmit="return changepw(\'' . $account['user'] . '\', ' . $cnt . ');">
					<strong>' . __('Change Password', 'cpanel-e-mail-manager') . ':</strong>&nbsp;&nbsp;&nbsp;' . __('Enter New Password', 'cpanel-e-mail-manager') . ': <input type="text" name="passwd_' . $cnt . '" id="passwd_' . $cnt . '" onkeyup="chkPasswordStrength(this.value, ' . $cnt . ');">&nbsp;&nbsp;<input type="hidden" name="passwdstr_' . $cnt . '" id="passwdstr_' . $cnt . '"><span id="strenghtMsg_' . $cnt . '" name="strenghtMsg_' . $cnt . '"></span>&nbsp;&nbsp;
					<button onclick="generatepw(\'passwd_' . $cnt . '\', ' . $cnt . '); return false;" class="button button-primary">' . __('Generate Password', 'cpanel-e-mail-manager') . '</button>&nbsp;&nbsp;<input type="submit" class="button button-primary" value="' . __('Change Password', 'cpanel-e-mail-manager') . '"></form></div></td></tr>
					<tr class="type-page status-publish hentry ' . ($cnt % 2 == 0 ? "alternate " : "") . 'iedit author-self level-0"><td colspan="4" id="chquotatd_' . $cnt . '" name="chquotatd_' . $cnt . '" align="center" style="display:none;"><div id="chquota_' . $cnt . '" name="chquota_' . $cnt . '" style="display:none;background-color:#00CCFF;padding-top:5px;padding-bottom:5px;">
					<form name="quotaupdate_' . $cnt . '" id="quotaupdate_' . $cnt . '" method="post" onsubmit="return changequota(\'' . $account['user'] . '\', ' . $cnt . ');">
					<strong>' . __('Change Quota', 'cpanel-e-mail-manager') . ':</strong>&nbsp;&nbsp;&nbsp;';
					if ($account['humandiskquota'] == "Unlimited") {
						$output .= '<input type="radio" name="quotagrp_' . $cnt . '" value="custom" onclick="getElementById(\'quota_' . $cnt . '\').disabled=false;">' . __('Custom Quota', 'cpanel-e-mail-manager') . ': <input type="text" name="quota_' . $cnt . '" id="quota_' . $cnt . '" size="5" disabled>&nbsp;&nbsp;<input type="radio" name="quotagrp_' . $cnt . '" value="unlimited" onclick="getElementById(\'quota_' . $cnt . '\').disabled=true;" checked> ' . __('Unlimited', 'cpanel-e-mail-manager');
					} else {
						$output .= '<input type="radio" name="quotagrp_' . $cnt . '" value="custom" onclick="getElementById(\'quota_' . $cnt . '\').disabled=false;" checked>' . __('Custom Quota', 'cpanel-e-mail-manager') . ': <input type="text" name="quota_' . $cnt . '" id="quota_' . $cnt . '" size="5" value="' . str_replace("MB", "", $account['humandiskquota']) . '"> (in MB)&nbsp;&nbsp;<input type="radio" name="quotagrp_' . $cnt . '" value="unlimited" onclick="getElementById(\'quota_' . $cnt . '\').disabled=true;"> ' . __('Unlimited', 'cpanel-e-mail-manager');
					}
					$output .= '&nbsp;&nbsp;<input type="submit" class="button button-primary" value="' . __('Change Quota', 'cpanel-e-mail-manager') . '"></form></div></td></tr>';
					$cnt++;
				}
			} else {
				$output .= '<tr><td colspan="4"><p>&nbsp;</p><p style="text-align: center; font-weight: bold;font-size: 16px;">' . __('There are no accounts to display.', 'cpanel-e-mail-manager') . '</p><p style="text-align: center; font-size: 16px;"><a href="users.php?page=emailmanager&mode=add">' . __('Add a New Account', 'cpanel-e-mail-manager') . '</a></p><p>&nbsp;</p></td></tr>';
			}
			$output .= '</tbody></table>
			<form name="updateuser" id="updateuser" action="" method="post">
			<input type="hidden" name="uservalue" id="uservalue">
			<input type="hidden" name="user" id="user">
			</form>
			<script type="text/javascript">
			jQuery("#paged").keypress(function(event){
			    var keycode = (event.keyCode ? event.keyCode : event.which);
			    if (keycode == 13) {
				var paged = document.getElementById("paged").value;
				if (isNaN(paged)) {
				    alert("' . __('You must enter a valid page number.', 'cpanel-e-mail-manager') . '");
				} else if (paged > ' . $numpages . ') {
				    alert("' . __('The page number you have entered exceeds the total number of pages available.', 'cpanel-e-mail-manager') . '");
				} else {
				    location.href = "users.php?page=emailmanager&paged=" + paged;
				} 
			    }
			});
			</script>
			';
		} else {
			$output .= '<p>&nbsp;</p><p>&nbsp;</p><p style="text-align: center; font-weight: bold;font-size: 16px;">' . __('There are no accounts to display.', 'cpanel-e-mail-manager') . '</p><p style="text-align: center; font-size: 16px;"><a href="users.php?page=emailmanager&mode=add">' . __('Add a New Account', 'cpanel-e-mail-manager') . '</a></p>';
		}
	} else {
		$output .= '<h2 style="display:inline;">' . __('List of Accounts', 'cpanel-e-mail-manager') . '</h2><p>&nbsp;</p><p>&nbsp;</p><p style="text-align:center; font-weight: bold;font-size: 16px;">' . __('To use this plugin, please enter your configuration details', 'cpanel-e-mail-manager') . ' <a href="users.php?page=emailmanager&mode=config">' . __('here', 'cpanel-e-mail-manager') . '</a>.</p>';
	}
	return $output;
}

function emailmanager_output_linkexisting() {
	global $wpdb;
	$output = '';
	//DISPLAY LIST OF ACCOUNTS
	//Set config options
	$cpaneluser = emailmanager_config_options("emailmgr_cpaneluser");
	$cpanelpass = emailmanager_config_options("emailmgr_cpanelpass");
	$cpanelserver = emailmanager_config_options("emailmgr_cpanelip");
	$emaildomain = emailmanager_config_options("emailmgr_domain");

	if ($cpaneluser != "" && $cpanelpass != "" && $cpanelserver != "" && $emaildomain != "") {
		$output .= '<h2 style="display:inline;">' . __('Link Existing Accounts', 'cpanel-e-mail-manager') . '</h2> - <a href="users.php?page=emailmanager&mode=config">' . __('Configuration Options', 'cpanel-e-mail-manager') . '</a> | <a href="users.php?page=emailmanager&mode=list">' . __('List of Accounts', 'cpanel-e-mail-manager') . '</a> | <a href="users.php?page=emailmanager&mode=add">' . __('Add New Account', 'cpanel-e-mail-manager') . '</a> | <a href="users.php?page=emailmanager&mode=mailinglist">' . __('Manage Mailing Lists', 'cpanel-e-mail-manager') . '</a>';
		$optional['domain'] = $emaildomain;
		$accounts = simplexml_load_string(emailmanager_cpanelAPI($cpaneluser, $cpanelpass, $cpanelserver, "getaccountlist", $optional));
		if ($accounts->data->user != "") {
			$output .= '<p>&nbsp;</p><form name="linkaccounts" id="linkaccounts" method="post" action="users.php?page=emailmanager&mode=list&action=link"><table class="wp-list-table widefat fixed pages" style="width:70%; margin-left:auto; margin-right:auto;">
			<thead>
			<tr>
			<th class="manage-column">' . __('WordPress User', 'cpanel-e-mail-manager') . '</th>
			<th class="manage-column">' . __('E-Mail Account', 'cpanel-e-mail-manager') . '</th>
			</tr></thead><tbody>';
			$sortarray = "";
			$cnt = 0;
			$optionlist = "";
			foreach ($accounts->data as $account) {
				$sortarray[$cnt]['login'] = $account->login;
				$sortarray[$cnt]['user'] = $account->user;
				$cnt++;
			}
			if ($sortarray != "") {
				usort($sortarray, "emailmanager_sort_by_user");
				$accounts = $sortarray;
				$cnt = 1;
				foreach ($accounts as $account) 
				{
					if (emailmanager_emailalias_userid($account['user']) == "") {
						$optionlist .= '<option value="' . $account['user'] . '">' . $account['login'] . '</option>';
						$cnt++;
					}
				}
			}
			$unassigned = emailmanager_emailalias_userid_unassigned();
			if (!empty($unassigned) && $optionlist != "") {
				$cnt = 1;
				foreach ($unassigned as $wpuser)
				{
					$output .= '<tr class="type-page status-publish hentry ' . ($cnt % 2 == 0 ? "alternate " : "") . 'iedit author-self level-0">
					<td>' . $wpuser->user_nicename . '</td><td><select name="email_' . $cnt . '" id="email_' . $cnt . '"><option value=""></option>' . $optionlist . '</select><input type="hidden" id="user_' . $cnt . '" name="user_' . $cnt . '" value="' . $wpuser->ID . '"></td>
					</tr>';
					$cnt++;
				}
				$lastrow = $cnt - 1;
				$output .= '<tr><td colspan="2"><p>&nbsp;</p><p style="text-align: center; font-weight: bold;font-size: 16px;"><input type="hidden" id="numfields" name="numfields" value="' .  $lastrow . '"><input type="hidden" name="uservalue" id="uservalue" value="na"><input type="hidden" name="user" id="user" value="na"><input type="submit" class="button button-primary" value="' . __('Update User Profiles', 'cpanel-e-mail-manager') . '"></td></tr>';
			} else {
				$output .= '<tr><td colspan="2"><p>&nbsp;</p><p style="text-align: center; font-weight: bold;font-size: 16px;">' . __('There are no unassigned WordPress accounts to display.', 'cpanel-e-mail-manager') . '</p><p>&nbsp;</p></td></tr>';
			}
			$output .= '</tbody></table></form>';
		} else {
			$output .= '<p>&nbsp;</p><p>&nbsp;</p><p style="text-align: center; font-weight: bold;font-size: 16px;">' . __('There are no unassigned cPanel accounts to display.', 'cpanel-e-mail-manager') . '</p><p>&nbsp;</p></p>';
		}
	} else {
		$output .= '<h2 style="display:inline;">' . __('List of Accounts', 'cpanel-e-mail-manager') . '</h2><p>&nbsp;</p><p>&nbsp;</p><p style="text-align:center; font-weight: bold;font-size: 16px;">' . __('To use this plugin, please enter your configuration details', 'cpanel-e-mail-manager') . ' <a href="users.php?page=emailmanager&mode=config">' . __('here', 'cpanel-e-mail-manager') . '</a>.</p>';
	}
	return $output;
}

function emailmanager_output_adduser() {
	global $wpdb;
	$output = '';
	//ADD NEW E-MAIL ACCOUNT
	//Set config options
	$cpaneluser = emailmanager_config_options("emailmgr_cpaneluser");
	$cpanelpass = emailmanager_config_options("emailmgr_cpanelpass");
	$cpanelserver = emailmanager_config_options("emailmgr_cpanelip");
	$emaildomain = emailmanager_config_options("emailmgr_domain");
	$emailquota = emailmanager_config_options("emailmgr_quota");
	if ($cpaneluser != "" && $cpanelpass != "" && $cpanelserver != "" && $emaildomain != "") {
		$accountinfo = simplexml_load_string(emailmanager_cpanelAPI($cpaneluser, $cpanelpass, $cpanelserver, "diskusage"));
		if ($accountinfo->data->max != "") {
			$accountinfo = simplexml_load_string(emailmanager_cpanelAPI($cpaneluser, $cpanelpass, $cpanelserver, "emailcount"));
			if ((int)$accountinfo->data->count < (int)$accountinfo->data->max || $accountinfo->data->max == "unlimited") {
				$accounts = emailmanager_emailalias_userid_unassigned();
				$output .= '<script type="text/javascript">
				function submitform() {
				var passwd = document.getElementById("passwd").value;
				var passwdstr = document.getElementById("passwdstr").value;
				var message = "' . __('The following fields are required', 'cpanel-e-mail-manager') . ':\n\n";
				if (document.getElementById("emailquota").value == "") {
				  message = message + "' . __('E-Mail Quota', 'cpanel-e-mail-manager') . ' (in MB).\n";
				}
				if (document.getElementById("aliasformat").value == "custom" && document.getElementById("emailalias").value == "") {
				  message = message + "' . __('You must enter a valid e-mail alias.', 'cpanel-e-mail-manager') . '\n";
				}
				if (passwd=="")
				  {
				  message = message + "' . __('You must enter a valid password.', 'cpanel-e-mail-manager') . '\n";
				  }
				if (passwdstr<2)
				  {
				  message = message + "' . __('The password entered does not meet minimum requirements.', 'cpanel-e-mail-manager') . '\n";
				  }
				if (message != "' . __('The following fields are required', 'cpanel-e-mail-manager') . ':\n\n") {
				  alert(message);
				  return false;
				}
				  document.getElementById("aliasformat").disabled = false;
				}
				function generatepw() {
				  var length = 15,
				  charset = "abcdefghijklnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()",
				  retVal = "";
				  for (var i = 0, n = charset.length; i < length; ++i) {
				    retVal += charset.charAt(Math.floor(Math.random() * n));
				  }
				  document.getElementById("passwd").value = retVal;
				  chkPasswordStrength(document.getElementById("passwd").value);
				}
				function chkPasswordStrength(txtpass)
				   {
				     var desc = new Array();
				     desc[0] = "' . __('Very Weak (Password is not strong enough)', 'cpanel-e-mail-manager') . '";
				     desc[1] = "' . __('Weak (Password is not strong enough)', 'cpanel-e-mail-manager') . '";
				     desc[2] = "' . __('Better', 'cpanel-e-mail-manager') . '";
				     desc[3] = "' . __('Medium', 'cpanel-e-mail-manager') . '";
				     desc[4] = "' . __('Strong', 'cpanel-e-mail-manager') . '";
				     desc[5] = "' . __('Strongest', 'cpanel-e-mail-manager') . '";

				     document.getElementById("strenghtMsg").innerHTML = "";
				     var score   = 0;

				     //if txtpass bigger than 6 give 1 point
				     if (txtpass.length > 6) score++;

				     //if txtpass has both lower and uppercase characters give 1 point
				     if ( ( txtpass.match(/[a-z]/) ) && ( txtpass.match(/[A-Z]/) ) ) score++;

				     //if txtpass has at least one number give 1 point
				     if (txtpass.match(/\d+/)) score++;

				     //if txtpass has at least one special caracther give 1 point
				     if ( txtpass.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/) ) score++;

				     //if txtpass bigger than 12 give another 1 point
				     if (txtpass.length > 12) score++;

				     document.getElementById("strenghtMsg").innerHTML = "' . __('Strength', 'cpanel-e-mail-manager') . ': " + desc[score];
				     document.getElementById("passwdstr").value = score;

				     if (txtpass.length < 6)
				     {
				     document.getElementById("strenghtMsg").innerHTML = "' . __('Password Should be Minimum 6 Characters', 'cpanel-e-mail-manager') . '";
				     }
				   }
				function enableAliasFormat(txtuser)
				   {
				     if (txtuser != "na") {
				     document.getElementById("aliasformat").disabled = false;
				     } else {
				     document.getElementById("aliasformat").value = "custom";
				     document.getElementById("aliasformat").selectedIndex = 4;
				     document.getElementById("aliasformat").disabled = true;
				     document.getElementById("emailalias").disabled = false;
				     }
				   }
				</script>
				<form action="users.php?page=emailmanager&mode=list&action=add" method="post" name="emailmgrconfig" id="emailmgrconfig" onsubmit="return submitform();">
				<h2 style="display:inline;">' . __('Add New Account', 'cpanel-e-mail-manager') . '</h2> - <a href="users.php?page=emailmanager&mode=config">' . __('Configuration Options', 'cpanel-e-mail-manager') . '</a> | <a href="users.php?page=emailmanager&mode=list">' . __('List of Accounts', 'cpanel-e-mail-manager') . '</a> | <a href="users.php?page=emailmanager&mode=linkaccounts">' . __('Link Existing Accounts', 'cpanel-e-mail-manager') . '</a> | <a href="users.php?page=emailmanager&mode=mailinglist">' . __('Manage Mailing Lists', 'cpanel-e-mail-manager') . '</a>';
				$output .= '<table width="100%"><tr><td align="left" colspan="3"><p style="font-size:14px;font-weight:bold;"><em>' . __('Complete the form below to create a new e-mail account', 'cpanel-e-mail-manager') . '</em></p></td></tr>
				<tr><td align="left" width="30%"><label for="user" style="vertical-align: top;">' . __('Link to a WordPress User', 'cpanel-e-mail-manager') . ':</label></td><td align="left" width="20%"><select name="user" id="user" onchange="enableAliasFormat(this.value);">
				<option value="na"></option>';
				$sortarray = "";
				$cnt = 0;
				foreach ($accounts as $account) 
				{
					$sortarray[$cnt]['id'] = $account->user_id;
					$sortarray[$cnt]['first_name'] = emailmanager_usermeta($account->user_id, "first_name");
					$sortarray[$cnt]['last_name'] = emailmanager_usermeta($account->user_id, "last_name");
					$sortarray[$cnt]['login'] = $account->user_login;
					$cnt++;
				}
				if ($sortarray != "") {
					usort($sortarray, "emailmanager_sort_by_lastname");
					$accounts = $sortarray;
					foreach ($accounts as $account) 
					{
						$output .= '<option value="' . $account['id'] . '">' . $account['last_name'] . ', ' . $account['first_name'] . ' (' . $account['login'] . ')</option>';
					}
				}
				$output .= '</select></td><td align="left" width="50%"><label for="user" style="vertical-align: top;"><em>(' . __('Optional', 'cpanel-e-mail-manager') . ')</em></label></td></tr><tr><td align="left" colspan="3">&nbsp;</td></tr>
				<tr><td align="left" width="30%"><label for="emailquota" style="vertical-align: top;">' . __('E-Mail Quota', 'cpanel-e-mail-manager') . ' (MB):</label></td><td align="left" width="20%"><input type="text" name="emailquota" id="emailquota" size="10" value="' . $emailquota . '"></td><td align="left" width="50%"><label for="emailquota" style="vertical-align: top;"><em>' . __('Enter', 'cpanel-e-mail-manager') . ' \'unlimited\' ' . __('for unlimited storage', 'cpanel-e-mail-manager') . '.</em></label></td></tr><tr><td align="left" colspan="3">&nbsp;</td></tr>
				<tr><td align="left" width="30%"><label for="aliasformat" style="vertical-align: top;">' . __('E-Mail Alias Format', 'cpanel-e-mail-manager') . ':</label></td><td align="left" width="20%"><select name="aliasformat" id="aliasformat" onchange=\'((this.options[this.selectedIndex].value == "custom") ? document.getElementById("emailalias").disabled = false : document.getElementById("emailalias").disabled = true);\' disabled>';
				$output .= '<option value="first.last">First Name . Last Name</option>';
				$output .= '<option value="firstlast">First Name + Last Name</option>';
				$output .= '<option value="firstl">First Name + Last Initial</option>';
				$output .= '<option value="username">WordPress Username</option>';
				$output .= '<option value="custom" selected>Custom</option>';
				$output .= '</select></td><td align="left" width="50%">&nbsp;</td></tr><tr><td align="left" colspan="3">&nbsp;</td></tr>
				<tr><td align="left" width="30%"><label for="emailalias" style="vertical-align: top;">' . __('Custom E-Mail Alias', 'cpanel-e-mail-manager') . ':</label></td><td align="left" width="20%"><input type="text" name="emailalias" id="emailalias" value=""></td><td align="left" width="50%"><label for="emailalias" style="vertical-align: top;"><em>@ ' . $emaildomain . ' (' . __('Note: You must select the Custom option in the field above for this setting to be applied.', 'cpanel-e-mail-manager') . ')</em></label></td></tr><tr><td align="left" colspan="3">&nbsp;</td></tr>
				<tr><td align="left" width="30%">' . __('User Password', 'cpanel-e-mail-manager') . ': </td><td align="left" width="20%"><input type="text" name="passwd" id="passwd" onkeyup="chkPasswordStrength(this.value);">&nbsp;&nbsp;<input type="hidden" name="passwdstr" id="passwdstr"></td><td align="left" width="50%"><button onclick="generatepw(); return false;" class="button button-primary">' . __('Generate Password', 'cpanel-e-mail-manager') . '</button>&nbsp;&nbsp;<span id="strenghtMsg" name="strenghtMsg"></span></td></tr><tr><td align="left" colspan="3">&nbsp;</td></tr>
				<tr><td align="left" colspan="3"><input type="submit" value="' . __('Save Settings', 'cpanel-e-mail-manager') . '" class="button button-primary"></td></tr></table>
				<input type="hidden" name="uservalue" id="uservalue" value="na">
				</form>';
			} else {
				$output .= '<h2 style="display:inline;">' . __('Add New Account', 'cpanel-e-mail-manager') . '</h2> - <a href="users.php?page=emailmanager&mode=config">' . __('Configuration Options', 'cpanel-e-mail-manager') . '</a> | <a href="users.php?page=emailmanager&mode=list">' . __('List of Accounts', 'cpanel-e-mail-manager') . '</a> | <a href="users.php?page=emailmanager&mode=linkaccounts">' . __('Link Existing Accounts', 'cpanel-e-mail-manager') . '</a> | <a href="users.php?page=emailmanager&mode=mailinglist">' . __('Manage Mailing Lists', 'cpanel-e-mail-manager') . '</a><p>&nbsp;</p><p>&nbsp;</p><p style="text-align:center; font-weight: bold;font-size: 16px;">' . __('You have reached maximum capacity for e-mail accounts on this server.', 'cpanel-e-mail-manager') . '</p>
				<p style="text-align:center; font-weight: bold;font-size: 16px;">' . __('Please contact your e-mail hosting provider to increase capacity or delete an existing e-mail account to add another.', 'cpanel-e-mail-manager') . '</p>';
			}
		} else {
			$output .= '<h2 style="display:inline;">' . __('Add New Account', 'cpanel-e-mail-manager') . '</h2><p>&nbsp;</p><p>&nbsp;</p><p style="text-align:center; font-weight: bold;font-size: 16px;">' . __('The cPanel credentials entered are not valid or a valid domain has not been selected. A connection could not be established.', 'cpanel-e-mail-manager') . '</p>
			<p style="text-align:center; font-weight: bold;font-size: 16px;">' . __('Please update your configuration settings', 'cpanel-e-mail-manager') . ' <a href="users.php?page=emailmanager&mode=config">' . __('here', 'cpanel-e-mail-manager') . '</a>.</p>';
		}
	} else {
		$output .= '<h2 style="display:inline;">' . __('Add New Account', 'cpanel-e-mail-manager') . '</h2><p>&nbsp;</p><p>&nbsp;</p><p style="text-align:center; font-weight: bold;font-size: 16px;">' . __('To use this plugin, please enter your configuration details', 'cpanel-e-mail-manager') . ' <a href="users.php?page=emailmanager&mode=config">' . __('here', 'cpanel-e-mail-manager') . '</a>.</p>';
	}
	return $output;
}

function emailmanager_output_upgradeplugin() {
	global $wpdb;
	$output = '';
	$output .= '<h2 style="display:inline;">' . __('Upgrade Plugin', 'cpanel-e-mail-manager') . '</h2> - <a href="users.php?page=emailmanager&mode=config">' . __('Configuration Options', 'cpanel-e-mail-manager') . '</a><p>&nbsp;</p><p style="text-align:center; font-weight: bold;font-size: 16px;">' . __('To use advanced features of this plugin, please upgrade to the professional version.', 'cpanel-e-mail-manager') . ' <a href="https://secure.insyncbusinessgroup.com/cart.php?a=add&pid=66" target="_blank">' . __('Click here', 'cpanel-e-mail-manager') . '</a> ' . __('to purchase cPanel E-Mail Manager Pro now', 'cpanel-e-mail-manager') . '!</p>';
	$output .= '<p style="text-align:center; font-weight: bold;font-size: 16px;">' . __('Note: You must download the additional files required to use the professional version of this plugin before the advanced features will become active.', 'cpanel-e-mail-manager') . '</p>';
	return $output;
}

function emailmanager_user_change_password_display($user = null) {
	//Set config options
	$cpaneluser = emailmanager_config_options("emailmgr_cpaneluser");
	$cpanelpass = emailmanager_config_options("emailmgr_cpanelpass");
	$cpanelserver = emailmanager_config_options("emailmgr_cpanelip");
	$emaildomain = emailmanager_config_options("emailmgr_domain");
	$webmailurl = emailmanager_config_options("emailmgr_webmailurl");
	$pw_uppercase = emailmanager_config_options("emailmgr_pw_uppercase");
	$pw_lowercase = emailmanager_config_options("emailmgr_pw_lowercase");
	$pw_numbers = emailmanager_config_options("emailmgr_pw_numbers");
	$pw_symbols = emailmanager_config_options("emailmgr_pw_symbols");
	$pw_characters = emailmanager_config_options("emailmgr_pw_characters");
	$optional['domain'] = $emaildomain;
	$accounts = simplexml_load_string(emailmanager_cpanelAPI($cpaneluser, $cpanelpass, $cpanelserver, "getaccountlist", $optional));
	foreach ($accounts->data as $account) {
		if (is_plugin_active('buddypress/bp-loader.php')) {
			if (emailmanager_emailalias_userid($account->user) == bp_displayed_user_id()) {
				$output = '<h4>' . __('Assigned E-Mail Account Information', 'cpanel-e-mail-manager') . '</h4>
<label for="email">' . __('Your Current E-Mail Address', 'cpanel-e-mail-manager') . '</label>';
				$output .= $account->login . ' (<a href="http://' . $webmailurl . '/login/?user=' . $account->login . '" target="_blank">' . __('Check Webmail', 'cpanel-e-mail-manager') . '</a>)';
				$output .= '
				<label for="emailpass1">' . __('New E-Mail Password', 'cpanel-e-mail-manager') . '</label>
				<input type="password" name="emailpass1" id="emailpass1" class="regular-text" size="16" value="" autocomplete="off" onkeyup="chkPasswordStrength(this.value);" />&nbsp;&nbsp;<span id="strenghtMsg" name="strenghtMsg" style="color:red;"></span>
				<span class="description" for="emailpass1">' . __('If you would like to change your e-mail account password, please enter it here. Otherwise leave these fields blank.', 'cpanel-e-mail-manager') . '</span>
				<label for="emailpass2">' . __('Repeat New E-Mail Password', 'cpanel-e-mail-manager') . '</label>
				<input name="emailpass2" type="password" id="emailpass2" class="regular-text" size="16" value="" autocomplete="off" onkeyup="chkPasswordMatch(this.value);" /><input type="hidden" name="passwdstr" id="passwdstr">&nbsp;&nbsp;<span id="matchMsg" name="matchMsg" style="color:red;"></span>
				<span class="description" for="emailpass2">' . __('Type your new e-mail password again.', 'cpanel-e-mail-manager') . '</span>
				<script type="text/javascript">
				function chkPasswordStrength(txtpass)
				   {
				     var desc = new Array();
				     desc[0] = "' . __('Very Weak (Password is not strong enough)', 'cpanel-e-mail-manager') . '";
				     desc[1] = "' . __('Weak (Password is not strong enough)', 'cpanel-e-mail-manager') . '";
				     desc[2] = "' . __('Better (Password does not meet minimum requirements)', 'cpanel-e-mail-manager') . '";
				     desc[3] = "' . __('Medium (Password does not meet minimum requirements)', 'cpanel-e-mail-manager') . '";
				     desc[4] = "' . __('Strong (Password does not meet minimum requirements)', 'cpanel-e-mail-manager') . '";
				     desc[5] = "' . __('Strongest', 'cpanel-e-mail-manager') . '";

				     strenghtMsg.innerHTML = "";
				     var score   = 0;

				     //password length
				     if (txtpass.length >= ' . $pw_characters . ') score++;

				     //number of uppercase letters
				     if (txtpass.replace(/[^A-Z]/g, "").length >= ' . $pw_uppercase . ') score++;

				     //number of lowercase letters
				     if (txtpass.replace(/[^a-z]/g, "").length >= ' . $pw_lowercase . ') score++;

				     //number of numeric characters
				     if (txtpass.replace(/[^0-9]/g, "").length >= ' . $pw_numbers . ') score++;

				     //number of special characters
				     if (txtpass.replace(/[^!@#\$\%\^\&\*\(\)]/g, "").length >= ' . $pw_symbols . ') score++;

				     strenghtMsg.innerHTML = "' . __('Strength', 'cpanel-e-mail-manager') . ': " + desc[score] + "<br />";
				     passwdstr.value = score;

				     if (txtpass.length < ' . $pw_characters . ')
				     {
				     strenghtMsg.innerHTML = "' . __('Password Should be Minimum', 'cpanel-e-mail-manager') . ' ' . $pw_characters . ' ' . __('Characters', 'cpanel-e-mail-manager') . '<br />";
				     }
				   }
				function chkPasswordMatch(txtpass)
				   {
				     if (txtpass != document.getElementById("emailpass1").value) {
				     matchMsg.innerHTML = "' . __('Passwords do not match', 'cpanel-e-mail-manager') . '<br />";
				     } else {
				     matchMsg.innerHTML = "";
				     }
				   }
				</script>';
				echo $output;
				break;
			}
		} else {
			if (emailmanager_emailalias_userid($account->user) == $user->ID) {
				$output = '<h3>' . __('Assigned E-Mail Account Information', 'cpanel-e-mail-manager') . '</h3>
				<table class="form-table">
				<tr>
				<th><label for="email">' . __('Your Current E-Mail Address', 'cpanel-e-mail-manager') . '</label></th>
				<td>';
				$output .= $account->login . ' (<a href="http://' . $webmailurl . '/login/?user=' . $account->login . '" target="_blank">' . __('Check Webmail', 'cpanel-e-mail-manager') . '</a>)';
				$output .= '</td>
				</tr>
				<tr>
				<th><label for="emailpass1">' . __('New E-Mail Password', 'cpanel-e-mail-manager') . '</label></th>
				<td>
				<input type="password" name="emailpass1" id="emailpass1" class="regular-text" size="16" value="" autocomplete="off" onkeyup="chkPasswordStrength(this.value);" />&nbsp;&nbsp;<span id="strenghtMsg" name="strenghtMsg"></span><br />
				<span class="description" for="emailpass1">' . __('If you would like to change your e-mail account password, please enter it here. Otherwise leave these fields blank.', 'cpanel-e-mail-manager') . '</span>
				</td>
				</tr>
				<tr>
				<th scope="row"><label for="emailpass2">' . __('Repeat New E-Mail Password', 'cpanel-e-mail-manager') . '</label></th>
				<td>
				<input name="emailpass2" type="password" id="emailpass2" class="regular-text" size="16" value="" autocomplete="off" onkeyup="chkPasswordMatch(this.value);" /><input type="hidden" name="passwdstr" id="passwdstr">&nbsp;&nbsp;<span id="matchMsg" name="matchMsg"></span><br />
				<span class="description" for="emailpass2">' . __('Type your new e-mail password again.', 'cpanel-e-mail-manager') . '</span>
				</td>
				</tr>
				</table>
				<script type="text/javascript">
				function chkPasswordStrength(txtpass)
				   {
				     var desc = new Array();
				     desc[0] = "' . __('Very Weak (Password is not strong enough)', 'cpanel-e-mail-manager') . '";
				     desc[1] = "' . __('Weak (Password is not strong enough)', 'cpanel-e-mail-manager') . '";
				     desc[2] = "' . __('Better (Password does not meet minimum requirements)', 'cpanel-e-mail-manager') . '";
				     desc[3] = "' . __('Medium (Password does not meet minimum requirements)', 'cpanel-e-mail-manager') . '";
				     desc[4] = "' . __('Strong (Password does not meet minimum requirements)', 'cpanel-e-mail-manager') . '";
				     desc[5] = "' . __('Strongest', 'cpanel-e-mail-manager') . '";

				     strenghtMsg.innerHTML = "";
				     var score   = 0;

				     //password length
				     if (txtpass.length >= ' . $pw_characters . ') score++;

				     //number of uppercase letters
				     if (txtpass.replace(/[^A-Z]/g, "").length >= ' . $pw_uppercase . ') score++;

				     //number of lowercase letters
				     if (txtpass.replace(/[^a-z]/g, "").length >= ' . $pw_lowercase . ') score++;

				     //number of numeric characters
				     if (txtpass.replace(/[^0-9]/g, "").length >= ' . $pw_numbers . ') score++;

				     //number of special characters
				     if (txtpass.replace(/[^!@#\$\%\^\&\*\(\)]/g, "").length >= ' . $pw_symbols . ') score++;

				     strenghtMsg.innerHTML = "' . __('Strength', 'cpanel-e-mail-manager') . ': " + desc[score] + "<br />";
				     passwdstr.value = score;

				     if (txtpass.length < ' . $pw_characters . ')
				     {
				     strenghtMsg.innerHTML = "' . __('Password Should be Minimum', 'cpanel-e-mail-manager') . ' ' . $pw_characters . ' ' . __('Characters', 'cpanel-e-mail-manager') . '<br />";
				     }
				   }
				function chkPasswordMatch(txtpass)
				   {
				     if (txtpass != document.getElementById("emailpass1").value) {
				     matchMsg.innerHTML = "' . __('Passwords do not match', 'cpanel-e-mail-manager') . '";
				     } else {
				     matchMsg.innerHTML = "";
				     }
				   }
				</script>';
				echo $output;
				break;
			}
		}
	}
}

function emailmanager_user_change_password($user_id = null) {
	global $wpdb;
	if (is_plugin_active('buddypress/bp-loader.php')) {
		if (current_user_can('edit_user',bp_displayed_user_id())) {
			$pass1 = (isset($_POST['emailpass1']) ? $_POST['emailpass1'] : "");
			$pass2 = (isset($_POST['emailpass2']) ? $_POST['emailpass2'] : "");
			$pwstrength = (isset($_POST['passwdstr']) ? $_POST['passwdstr'] : "");
			if ($pass1 != "") {
				if ($pass1 == $pass2) {
					if ($pwstrength == 5) {
						$cpaneluser = emailmanager_config_options("emailmgr_cpaneluser");
						$cpanelpass = emailmanager_config_options("emailmgr_cpanelpass");
						$cpanelserver = emailmanager_config_options("emailmgr_cpanelip");
						$emaildomain = emailmanager_config_options("emailmgr_domain");
						$emailalias = emailmanager_usermeta(bp_displayed_user_id(), $wpdb->prefix . "emailmgr_alias");
						if ($cpaneluser != "" && $cpanelpass != "" && $cpanelserver != "" && $emaildomain != "") {
							$optional['email_domain'] = $emaildomain;
							$optional['email_user'] = $emailalias;
							$optional['password'] = $pass1;
							emailmanager_cpanelAPI($cpaneluser, $cpanelpass, $cpanelserver, "changepw", $optional);
							bp_core_add_message(__('Your e-mail password has been successfully updated.', 'cpanel-e-mail-manager'), 'success');
						}
					} else {
						bp_core_add_message(__('The e-mail password provided does not meet minimum requirements.', 'cpanel-e-mail-manager'), 'error');
					}
				} else {
					bp_core_add_message(__('The e-mail passwords provided do not match.', 'cpanel-e-mail-manager'), 'error');
				}
			}
		}
	} else {
		if (current_user_can('edit_user',$user_id)) {
			$pass1 = (isset($_POST['emailpass1']) ? $_POST['emailpass1'] : "");
			$pass2 = (isset($_POST['emailpass2']) ? $_POST['emailpass2'] : "");
			$pwstrength = (isset($_POST['passwdstr']) ? $_POST['passwdstr'] : "");
			if ($pass1 != "") {
				if ($pass1 == $pass2) {
					if ($pwstrength == 5) {
						$cpaneluser = emailmanager_config_options("emailmgr_cpaneluser");
						$cpanelpass = emailmanager_config_options("emailmgr_cpanelpass");
						$cpanelserver = emailmanager_config_options("emailmgr_cpanelip");
						$emaildomain = emailmanager_config_options("emailmgr_domain");
						$emailalias = emailmanager_usermeta($user_id, $wpdb->prefix . "emailmgr_alias");
						if ($cpaneluser != "" && $cpanelpass != "" && $cpanelserver != "" && $emaildomain != "") {
							$optional['email_domain'] = $emaildomain;
							$optional['email_user'] = $emailalias;
							$optional['password'] = $pass1;
							emailmanager_cpanelAPI($cpaneluser, $cpanelpass, $cpanelserver, "changepw", $optional);
						}
					} else {
						add_filter('user_profile_update_errors', 'emailmanager_profile_upadate_password_error_strength', 10, 3);
					}
				} else {
					add_filter('user_profile_update_errors', 'emailmanager_profile_upadate_password_error_match', 10, 3);
				}
			}
		}
	}
}

function emailmanager_profile_upadate_password_error_strength($errors, $update, $user) {
	$errors->add('email_password_error',__('The e-mail password provided does not meet minimum requirements.', 'cpanel-e-mail-manager'));
}

function emailmanager_profile_upadate_password_error_match($errors, $update, $user) {
	$errors->add('email_password_error',__('The e-mail passwords provided do not match.', 'cpanel-e-mail-manager'));
}

function emailmanager_dashboard_widget_function() {
	//Set config options
	$cpaneluser = emailmanager_config_options("emailmgr_cpaneluser");
	$cpanelpass = emailmanager_config_options("emailmgr_cpanelpass");
	$cpanelserver = emailmanager_config_options("emailmgr_cpanelip");
	$emaildomain = emailmanager_config_options("emailmgr_domain");
	if ($cpaneluser != "" && $cpanelpass != "" && $cpanelserver != "" && $emaildomain != "") {
		$accountinfo = simplexml_load_string(emailmanager_cpanelAPI($cpaneluser, $cpanelpass, $cpanelserver, "diskusage"));
		if ($accountinfo->data->max != "") {
			$output = '<p>' . __('E-Mail Accounts', 'cpanel-e-mail-manager') . ': ';
			$accountinfo = simplexml_load_string(emailmanager_cpanelAPI($cpaneluser, $cpanelpass, $cpanelserver, "emailcount"));
			$output .= $accountinfo->data->count . ' / ' . $accountinfo->data->max;
			$output .= '</p>
			<p>' . __('Disk Space Usage', 'cpanel-e-mail-manager') .': ';
			$accountinfo = simplexml_load_string(emailmanager_cpanelAPI($cpaneluser, $cpanelpass, $cpanelserver, "diskusage"));
			$output .= $accountinfo->data->count . ' / ' . $accountinfo->data->max;
			$output .= '</p>
			<p style="text-align:center;"><a href="users.php?page=emailmanager&mode=add">' . __('Add New Account', 'cpanel-e-mail-manager') . '</a> | <a href="users.php?page=emailmanager&mode=list">' . __('List of Accounts', 'cpanel-e-mail-manager') . '</a> | <a href="users.php?page=emailmanager&mode=config">' . __('Configuration Options', 'cpanel-e-mail-manager') . '</a></p>';
		} else {
			$output = '<p style="text-align:center;">' . __('The cPanel credentials entered are not valid or a valid domain has not been selected. A connection could not be established.', 'cpanel-e-mail-manager') . '</p>
			<p style="text-align:center;">' . __('Please update your configuration settings', 'cpanel-e-mail-manager') . ' <a href="users.php?page=emailmanager&mode=config">' . __('here', 'cpanel-e-mail-manager') . '</a>.</p>';
		}
	} else {
			$output = '<p style="text-align:center;">' . __('To use this plugin, please enter your configuration details', 'cpanel-e-mail-manager') . ' <a href="users.php?page=emailmanager&mode=config">' . __('here', 'cpanel-e-mail-manager') . '</a>.</p>';
	}
echo $output;
} 

/*
////////////// CPANEL API FUNCTIONS //////////////
*/

function emailmanager_cpanelAPI($cpaneluser, $cpanelpass, $cpanelserver, $action, $optional = null) {
	// CPANEL XML LOGIN
	$xmlapi = new xmlapi($cpanelserver);
	$xmlapi->set_port(2083);
	$xmlapi->set_output('xml');
	$xmlapi->password_auth($cpaneluser, $cpanelpass);
	$xmlapi->set_debug(0);

	/*
	//BEGIN XML FUNCTIONS
	*/

	//CREATE CPANEL EMAIL ACCOUNT
	if ($action == "checkcredentials") {
		return $xmlapi;
	}

	//CREATE CPANEL EMAIL ACCOUNT
	if ($action == "createemail") {
		$call = array('domain'=>$optional['email_domain'], 'email'=>$optional['email_user'], 'password'=>$optional['email_pass'], 'quota'=>$optional['email_quota']);
		return $xmlapi->api2_query($cpaneluser, "Email", "addpop", $call);
	}

	//DELETE CPANEL EMAIL ACCOUNT
	if ($action == "deleteemail") {
		$call = array('domain'=>$optional['email_domain'], 'email'=>$optional['email_user'],'flags'=>"passwd");
		$xmlapi->api2_query($cpaneluser, "Email", "delpop", $call);
	}

	//CHANGE PASSWORD
	if ($action == "changepw") {
		$call = array('domain'=>$optional['email_domain'], 'email'=>$optional['email_user'], 'password'=>$optional['password']);
		$xmlapi->api2_query($cpaneluser, "Email", "passwdpop", $call);
	}

	//ADD PARKED DOMAIN
	if ($action == "addparkeddomain") {
		$call = array('domain'=>$optional['domain']);
		return $xmlapi->api2_query($cpaneluser, "Park", "park", $call);
	}

	//LIST EMAIL DOMAINS
	if ($action == "listemaildomains") {
		return $xmlapi->api2_query($cpaneluser, "Email", "listmaildomains");
	}

	//GET EMAIL ACCOUNT DATA
	if ($action == "getaccountinfo") {
		$call = array('regex'=>$optional['email_user'] . "@");
		return $xmlapi->api2_query($cpaneluser, "Email", "listpopswithdisk", $call);
	}

	//GET ACCOUNT LIST
	if ($action == "getaccountlist") {
		$call = array('regex'=>'@'.$optional['domain']);
		return $xmlapi->api2_query($cpaneluser, "Email", "listpopswithdisk", $call);
	}

	//EMAIL ACCOUNT STATS
	if ($action == "emailcount") {
		$call = array('display'=>"emailaccounts");
		return $xmlapi->api2_query($cpaneluser, "StatsBar", "stat", $call);
	}

	//DISK SPACE INFO
	if ($action == "diskusage") {
		$call = array('display'=>"diskusage");
		return $xmlapi->api2_query($cpaneluser, "StatsBar", "stat", $call);
	}
}

/*
////////////// HELPER FUNCTIONS //////////////
*/

function emailmanager_generatePassword() {
	$alphabet = "abcdefghijklnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()";
	$pass = array();
	$alphaLength = strlen($alphabet) - 1;
	for ($i = 0; $i < 15; $i++) {
		$n = rand(0, $alphaLength);
		$pass[] = $alphabet[$n];
	}
	return implode($pass);
}

function emailmanager_config_options($option_name) {
	global $wpdb;
	if (!defined('EMAILMGR_CPANEL_GLOBAL')) {
		$globalval = 0;
	} else {
		$globalval = EMAILMGR_CPANEL_GLOBAL;
	}
	if ($option_name != "") {
		if ($globalval == 1) {
			if ($option_name == "emailmgr_cpaneluser") {
				return EMAILMGR_CPANEL_USERNAME;
			} elseif ($option_name == "emailmgr_cpanelpass") {
				return EMAILMGR_CPANEL_PASSWORD;
			} elseif ($option_name == "emailmgr_cpanelip") {
				return EMAILMGR_CPANEL_SERVER;
			} else {
				$result = $wpdb->get_var( $wpdb->prepare( 
					"
					SELECT option_value 
					FROM $wpdb->options 
					WHERE option_name = %s
					", 
					$option_name
				) );
				if ($option_name == "emailmgr_cpaneluser" || $option_name == "emailmgr_cpanelpass") {
					$c = new Cpanelcipher(emailmanager_config_options("emailmgr_encryption_salt"));
					$result = $c->decrypt($result);
				}
			return $result;
			}

		} else {
			$result = $wpdb->get_var( $wpdb->prepare( 
				"
				SELECT option_value 
				FROM $wpdb->options 
				WHERE option_name = %s
				", 
				$option_name
			) );
			if ($option_name == "emailmgr_cpaneluser" || $option_name == "emailmgr_cpanelpass") {
				$c = new Cpanelcipher(emailmanager_config_options("emailmgr_encryption_salt"));
				$result = $c->decrypt($result);
			}
		return $result;
		}
	}
}

function emailmanager_usermeta($userid, $meta_key) {
	if ($meta_key != "" && $userid != "") {
		global $wpdb;
		$result = $wpdb->get_var( $wpdb->prepare( 
			"
			SELECT meta_value 
			FROM $wpdb->usermeta 
			WHERE user_id = %d AND meta_key = %s
			", 
			$userid, $meta_key
		) );
		return $result;
	}
}

function emailmanager_user_email($userid) {
	if ($userid != "") {
		global $wpdb;
		$result = $wpdb->get_var( $wpdb->prepare( 
			"
			SELECT user_email
			FROM $wpdb->users 
			WHERE id = %d
			", 
			$userid
		) );
		return $result;
	}
}

function emailmanager_emailalias_userid($emailalias = "") {
	global $wpdb;
	$key = $wpdb->prefix . 'emailmgr_alias';
	$result = $wpdb->get_var( $wpdb->prepare( 
		"
		SELECT user_id 
		FROM $wpdb->usermeta 
		WHERE meta_key = %s AND meta_value = %s
		", 
		$key, $emailalias
	) );
	return $result;
}

function emailmanager_emailalias_userid_assigned() {
	global $wpdb;
	$key = $wpdb->prefix . 'emailmgr_alias';
	$result = $wpdb->get_results( $wpdb->prepare( 
		"
		SELECT ID, user_id, user_nicename, user_login
		FROM $wpdb->usermeta
		INNER JOIN $wpdb->users ON user_id = ID
		WHERE meta_key = %s AND meta_value <> '' 
		",
		$key
	) );
	return $result;
}

function emailmanager_emailalias_userid_unassigned() {
	global $wpdb;
	$key = $wpdb->prefix . 'emailmgr_alias';
	$result = $wpdb->get_results( $wpdb->prepare( 
		"
		SELECT ID, user_id, user_nicename, user_login
		FROM $wpdb->usermeta tbl1
		INNER JOIN $wpdb->users ON user_id = ID
		WHERE (meta_key = %s AND meta_value = '') OR (NOT EXISTS (
		SELECT user_id 
		FROM $wpdb->usermeta tbl2
		WHERE meta_key = %s AND user_id =  ID)) GROUP BY user_id
		",
		$key, $key
	) );
	return $result;
}

function emailmanager_update_config($option_name, $option_value) {
	if ($option_name != "") {
		$option_value = stripslashes_deep($option_value);
		global $wpdb;
		if ($option_name == "emailmgr_cpaneluser" || $option_name == "emailmgr_cpanelpass") {
			$c = new Cpanelcipher(emailmanager_config_options("emailmgr_encryption_salt"));
			$option_value = $c->encrypt($option_value);
		}
		$wpdb->query( $wpdb->prepare( 
			"
			UPDATE $wpdb->options 
			SET option_value = %s
			WHERE option_name = %s
			",
			$option_value, $option_name 
		) );
	}
}

function emailmanager_assign_alias($firstname, $lastname, $username, $email, $customformat = null, $customalias = null) {
	//Set config options
	$autogenerate = (emailmanager_config_options("emailmgr_autogen") ? " checked" : "");
	$wordpressgroups = emailmanager_config_options("emailmgr_groups");
	$emaildomain = emailmanager_config_options("emailmgr_domain");
	$webmailurl = emailmanager_config_options("emailmgr_webmailurl");
	$cpaneluser = emailmanager_config_options("emailmgr_cpaneluser");
	$cpanelpass = emailmanager_config_options("emailmgr_cpanelpass");
	$cpanelserver = emailmanager_config_options("emailmgr_cpanelip");
	$emailmsg = emailmanager_config_options("emailmgr_emailmsg");
	$aliasformat = (!is_null($customformat) ? $customformat : emailmanager_config_options("emailmgr_aliasformat"));
	$sendemail = emailmanager_config_options("emailmgr_sendemail");

	if (!is_null($customalias) && $customalias != "") {
		$alias = $customalias;
	} else {
		$alias = "";
		if ($firstname != "" && $lastname != "") {
			if ($aliasformat == "first.last") {
				$alias = $firstname . "." . $lastname;
			} elseif ($aliasformat == "firstlast") {
				$alias = $firstname . $lastname;
			} elseif ($aliasformat == "firstl") {
				$alias = $firstname . substr($lastname, 0, 1);
			}
		}
		if ($username != "") {
			if ($aliasformat == "username") {
				$alias = $username;
			}
		}
		if ($alias == "") {
			$subalias = explode("@", $email);
			$alias = $subalias[0];
		}
	}

	if ($alias != "") {
		$alias_tmp = $alias;
		$cnt = 1;
		$optional['email_domain'] = $emaildomain;
		$optional['email_user'] = $alias;
		$aliascheck = simplexml_load_string(emailmanager_cpanelAPI($cpaneluser, $cpanelpass, $cpanelserver, "getaccountinfo", $optional));
		$aliascheck = $aliascheck->data->user;

		while ($aliascheck != "") {
			$alias_tmp = $alias . "." . $cnt;
			$optional['email_domain'] = $emaildomain;
			$optional['email_user'] = $alias_tmp;
			$aliascheck = simplexml_load_string(emailmanager_cpanelAPI($cpaneluser, $cpanelpass, $cpanelserver, "getaccountinfo", $optional));
			$aliascheck = $aliascheck->data->user;
			$cnt++;
		}
	}
return $alias_tmp;
}

function emailmanager_version() {
	if (EMAILMGR_STD_MODE == 1) {
		return __('Standard', 'cpanel-e-mail-manager') . ' v' . EMAILMGR_CURRENT_VERSION . ' <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank" style="display:inline;">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="LUNYEYBEQC8Q6">
		<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="' . __('PayPal - The safer, easier way to pay online!', 'cpanel-e-mail-manager') . '" style="display:inline;">
		<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
		</form>';
	} else {
		return __('Pro', 'cpanel-e-mail-manager') . ' v' . EMAILMGR_CURRENT_VERSION;
	}
}

function emailmanager_sort_by_login($a, $b) {
	return strcmp($a["login"], $b["login"]);
}

function emailmanager_sort_by_user($a, $b) {
	return strcmp($a["user"], $b["user"]);
}

function emailmanager_sort_by_lastname($a, $b) {
	return strcmp($a["last_name"], $b["last_name"]);
}

function emailmanager_array_dup($array){
	$dupe_array = array();
	foreach($array as $val){
		if (++$dupe_array[$val] > 1) {
			return true;
		}
	}
	return false;
}

/*
////////////// ENCRYPTION/DECRYPTION //////////////
*/

class Cpanelcipher
{
	private $securekey;
	private $iv_size;

	function __construct($textkey)
	{
		$this->iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
		$this->securekey = hash('sha256', $textkey, TRUE);
    	}

    	function encrypt($input)
    	{
		if ($input != "") {
        		$iv = mcrypt_create_iv($this->iv_size);
	        	return base64_encode($iv . mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->securekey, $input, MCRYPT_MODE_CBC, $iv));
		}
	}

	function decrypt($input)
	{
		if ($input != "") {
			$input = base64_decode($input);
			$iv = substr($input, 0, $this->iv_size);
			$cipher = substr($input, $this->iv_size);
			return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->securekey, $cipher, MCRYPT_MODE_CBC, $iv));
		}
	}
}

/*
////////////// ERROR LOGGING //////////////
*/

function emailmanager_resultLogging($action, $response = null) {
	if (!$response) {
		$response = "No error reported.";
	}
	$response = "Plugin Action: " . $action . " - " . $response;

	//OUTPUT TO WORDPRESS LOG FILE
	if (defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
		error_log("cPanel E-Mail Manager " . $response);
	}

	//OUTPUT TO MODULE
	return $response;
}
?>