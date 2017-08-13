<?php
/*
    Plugin Name: Free MailClient FMC
    Plugin URI:
    Description: Small plugin, with a simple way to display Webmail on your website, for use now PHP must have IMAP extension activated.
    Version: 1.0
    Author: CS : ABS-Hosting.nl / Walchum.net
    Author URI: http://www.abs-hosting.nl
    License: GPLv2 or later
    License URI: http://www.gu.org/licenses/gpl-2.0.html
    Donate link: http://www.abs-hosting.nl/cms/blog/mail-client-free/
    Text Domain: mailclient
    Domain Path: /languages/
    Copyright 2009-2015 ABS-Hosting.nl (email: cees@abs-hosting.nl)

    This file is part of oMailCient, a plugin for WordPress.

    MailCient is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 2 of the License, or
    (at your option) any later version.

    MailCient is distributed in the hope that it is useful,
    but WITHOUT ANY WARRANTY; Without even the implied WARRANTY of
    MERCHANTABILITY, ERRORS or FITNESS FOR A PARTICULAR PURPOS.  
    See the GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
    Or look at   License URI: http://www.gnu.org/licenses/gpl-2.0.html


*/

/**
 * Description of oMailClient
 *
 * @author cs
 */
class oMailClient {
    /**
     * Plugin constructor
     */
    public function __construct() {
        add_action('init', array($this, 'init'));
    }
    /**
     * Initiate plugin
     */
    
    public function init() {
        //get some CONSTANT values
        $this->defenitions();

        include_once $this->mailclient_plugin_dir('/include/mailclient_install.php');
        //set install hook, for adding of removing options
        register_activation_hook(__FILE__, '\MAILCLIENT_PLUGIN_NAME\mailclient_install');
        //* Runs on plugin deactivation*/
        register_deactivation_hook(__FILE__, '\MAILCLIENT_PLUGIN_NAME\mailclient_remove');

        //...
        include_once $this->mailclient_plugin_dir('/include/control_mailclient.php');
        //initiate menu
        add_action('admin_menu', array($this, 'mc_actions'));

        //add CSS to page head
        $this->wp_mailclient_add_css_files();
        //add javascripts to page head
        //$this->wp_mailclient_add_javascript_files();

        //load plugin textdomain
        $this->mailclient_load_textdomain();
        
        /*
        if ($this->check_prokey()) {
            
        }
         * 
         */
    }
    /**
     * load textdomain
     * @return type
     */
    function mailclient_load_textdomain() {
        return load_plugin_textdomain('mailclient', false, plugin_basename(dirname(__FILE__)) . '/languages');
    }

    //add stylesheet to Wordpress heading
    function wp_mailclient_add_css_files() {
        wp_enqueue_style('mailclientStyleSheet', $this->mailclient_plugin_url('css/mailclientStyleSheet.css'));
    }

    //add javascript to Wordpress heading
    function wp_mailclient_add_javascript_files() {
        wp_enqueue_script('mailclient_js', $this->mailclient_plugin_url('js/mailclient.js'));
    }

    /**
     * get lugin URL (like http://www.etc.....)
     * @param type $path
     * @return type
     */
    private function mailclient_plugin_url($path = '') {
        return plugins_url($path, MAILCLIENT_PLUGIN_BASENAME);
    }

    /**
     * get lugin DIR (like /home/user/you/wordress/etc...)
     * filesystem value
     * 
     * @param type $path
     * @return type
     */
    private function mailclient_plugin_dir($path = '') {
        return MAILCLIENT_PLUGIN_DIR . $path;
    }

    /**
     * 
     * @param type $file
     */
    private function mc_include($file) {
        $path = $this->mailclient_plugin_dir($file);
        if (file_exists($path)) {
            include ($path);
            return true;
        }
        return false;
    }

    public function mc_load_mailinit() {
        return $this->mc_include('/mailclient_init.php');
    }

    public function mc_get_mail() {
        return $this->mc_include('/mailclient_mailbox.php');
    }

    public function mc_del_mail($id) {
        return $this->mc_include('/mailclient_delete.php');
    }

    public function mc_load_maildoc() {
        return $this->mc_include('/mailclient_doc.php');
    }

    // Link in het admin menu
    public function mc_actions() {
        //set menu only for admin
        add_menu_page('Createmailclient',                       //page_title
                    __('Createmailclient','mailclient'),        //menu_title 
                        "administrators",                       //capability
                        'mailclient',                           //menu_slug
                        array( $this, 'mc_get_mail'));          //function
        
        add_submenu_page('mailclient',                          //parent_slug
                        __('Createmailclient', 'mailclient'),   //page_title
                        __('Mail', 'mailclient'),               //menu_title
                           'administrator',                     //capability
                           'mailbox',                           //menu_slug
                           array( $this, 'mc_get_mail'));       //function
        
        add_submenu_page('mailclient', 
                        __('Createmailclient', 'mailclient'), 
                        __('Init', 'mailclient'), 
                           'administrator', 
                           'mailinit', 
                           array( $this, "mc_load_mailinit"));
        
        add_submenu_page('mailclient', 
                        __('Createmailclient', 'mailclient'), 
                        __('Documentation', 'mailclient'), 
                           'administrator', 
                           'maildoc', 
                           array( $this, "mc_load_maildoc"));
    }
    private function defenitions() {
        //define some constant plugin values
        if (!defined('MAILCLIENT_PLUGIN_BASENAME')) {
            define('MAILCLIENT_PLUGIN_BASENAME', plugin_basename(__FILE__));
        }
        if (!defined('MAILCLIENT_PLUGIN_NAME')) {
            define('MAILCLIENT_PLUGIN_NAME', trim(dirname(MAILCLIENT_PLUGIN_BASENAME), '/'));
        }
        if (!defined('MAILCLIENT_PLUGIN_DIR')) {
            define('MAILCLIENT_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . MAILCLIENT_PLUGIN_NAME);
        }
        if (!defined('MAILCLIENT_PLUGIN_URL')) {
            define('MAILCLIENT_PLUGIN_URL', WP_PLUGIN_URL . '/' . MAILCLIENT_PLUGIN_NAME);
        }
    }
    /*
    private function mailclient_install() {
        $stop = true;
    }

    private function mailclient_remove() {
    // Deletes the database field 
        delete_option('mailclient');
    }
     */
    private function check_prokey($key = '') {
        return $key;
    }
}

new oMailClient();

