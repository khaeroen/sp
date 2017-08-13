<?php namespace MAILCLIENT_PLUGIN_NAME;

/*  
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


class mailControl {

    //put your code here
    public $imap;
    private $mClient;
    private $imgPath;
    private $boxes = array('INBOX', 'SENT', 'TRASH');
    private $columns =array('1' => '4%', '2' => '20%', '3' => '66%', '4' => '5%', '5' => '5%');
    
    public function __construct() {
        //parent::__construct();
        //load options specific to plugin
        $this->mClient = get_option('mailclient');
        //include imap.class and div_table.class
        if (file_exists(MAILCLIENT_PLUGIN_DIR . '/include/cImap.php')) {
            $this->imgPath = MAILCLIENT_PLUGIN_URL . '/images/';
            include MAILCLIENT_PLUGIN_DIR .'/include/cImap.php';
            include MAILCLIENT_PLUGIN_DIR .'/include/div_table.php';
            try {
                if (function_exists('imap_open')) {
                    $this->imap = new cImap($this->mClient['server'], 
                                            $this->mClient['username'], 
                                            $this->mClient['password']);
                    if (isset($this->imap)) {
                        if (isset($this->imgPath)) $this->imap->imgPath = $this->imgPath;
                        // stop on error
                        if ($this->imap->isConnected() === false)
                            die($this->imap->getError());
                    }
                } else {
                    echo "<br />"; 
                    include MAILCLIENT_PLUGIN_DIR . '/mailclient_no_imap.php';
                }
                
            } catch (Exception $e) {
                echo $e->getTraceAsString();
            }
        }
    }
    public function get_username() {
        if (isset($this->mClient['username'])) {
            return $this->mClient['username'];
        }
        return '';
    }
    /**
     * 
     * @param type $aMbox
     */
    public function get_mail_headers($aMbox = 'INBOX') {
        $html= '';
        $html .= '<div id="ajax_container>">';
        $html .= '<fieldset><legend><h3>' . $this->mClient['username'] . '</h3></legend>';
        $html .= $this->getmailHeaders() . '<br />' .
            '<div id="ajax_message"></div>' . '<br />';
        $html .= '</fieldset>';
        $html .= '</div>';
        return $html;
    }
    /**
     * 
     * @param type $mbox
     * @param type $boxes
     * @return string
     */
    public function getMailBoxes($mbox, $boxes) {
        // get all folders as array of strings
        $sfolders = '<div class="centered"><button class="sds-button">Mailbox</button></div><br />';
        $folders = $this->imap->getFolders();
        sort($folders);
        foreach ($folders as $folder) {
            if (in_array($folder, $boxes)) {
                if ($folder == $mbox) {
                    $sfolders .= '<strong><h3>' . $folder . '</h3></strong><hr class="hr" />';
                } else {
                    $sfolders .= $folder . '<hr class="hr" />';
                }
            }
        }
        $sfolders .= '<br /><br />';

        $sfolders .= $mbox . ' -> ';
        // select folder Inbox
        $this->imap->selectFolder($mbox);
        // count messages in current folder
        $sfolders .= 'Berichten ' . $this->imap->countMessages() . '  ';
        $sfolders .= 'Ongelezen ' . $this->imap->countUnreadMessages();
        return $sfolders;
    }

    private function getmailHeaders() {
        $html = '<div id="mailHeaders">';
        $tableDiv = new \MAILCLIENT_PLUGIN_NAME\mc_div_table();
        $tableDiv->delete = true;
        $tableDiv->column = false;
        $tableDiv->hr = true;
        $tableDiv->bgcolumn = true;
        $tableDiv->heading = true;
        $tableDiv->center = false;
        $tableDiv->widthColumn = $this->columns;
        //$tableDiv->width = "90%";
        $html .= $this->imap->getMessageHeaders($tableDiv);
        $html .= '</div>';
        return $html;
    }
    /**
     * 
     * @param type $num
     * @param type $to
     * @return boolean
     */
    private function getMailMessages($num = 0, $to = 0) {
        $counted = $this->imap->countMessages();
        //check if range num<=>to is present
        if (($counted > $num) && ($counted > $to)) {
            return $this->imap->getNumToMessages($num, $to);
        } else {
            return false;
        }
    }
    /**
     * 
     * @param type $id
     */
    public function readMailMessage($id) {//ajax call, perhaps load on mouseup MailHeadrers !
        $msg = $this->imap->getMessage($id);
        $this->imap->setSeenMessage($id);
        $html = '<p class="messageSubject">' . $msg['subject'] . '</p>';
        $html .= $msg['body'];
        $ret = array('html'=>$html, 'mess'=>$msg);
        return $ret;
    }
    /**
     * 
     * @param type $id
     */
    function delMailMessage($id) {//ajax call, perhaps load on mouseup MailHeadrers !
        $html = '<div id="mailHeaders">';
        //$trash = $this->imap->getTrashFolder();
        $ok = $this->imap->delMessage($id);
        $tableDiv = new mc_div_table();
        $tableDiv->delete = true;
        $tableDiv->column = false;
        $tableDiv->hr = true;
        $tableDiv->bgcolumn = true;
        $tableDiv->heading = true;
        $tableDiv->center = false;
        $tableDiv->widthColumn = $this->columns;
        $html .= $this->imap->getMessageHeaders($tableDiv);
        $html .= '</div>';
        echo $html;
    }
    /**
     * 
     * @param type $str
     * @return type
     */
    private function _escape($str) {
        return str_replace(array('\\', '"'), array('\\\\', '\"'), $str);
    }


}
