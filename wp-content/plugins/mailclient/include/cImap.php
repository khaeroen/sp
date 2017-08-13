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

/**
 * Description of cImap
 *
 * @author cs
 */
class cImap {

    /**
     * mailbox url string
     */
    private $mailbox = "";

    /**
     * currentfolder
     */
    private $folder = "Inbox";

    /**
     * imap connection
     */
    private $imap = false;
    private $service = 'imap';
    private $allowedServices = array('imap', 'pop3'); //if need more, extend array's
    private $port = array('imap' => '143', 'pop3' => '110');
    private $automaticSwitch = true;
    private $ssl = false;
    private $nntp = false;
    public $imgPath;

    /*
     * __construct can be used without parameters, 
     *   Just create Instance
     */

    /**
     * cImap Class constructor
     * 
     * @param type $mserver
     * @param type $username
     * @param type $password
     * @param type $encryption
     */
    public function __construct($mserver = null, $username = "", $password = "", $service = "imap", $encryption = false) {
        if ($mserver !== "" && // mailbox  must be given
            $username !== "" && // username must be given
            $password !== "") { // password must be given
            $this->imap_init($mserver, $username, $password, $service, $encryption);
        }
    }

    /**
     * initialize cImap
     * 
     * @param type $mserver
     * @param type $username
     * @param type $password
     * @param type $service
     * @param type $encryption ssl or tls
     */
    private function imap_init($mserver, $username, $password, $service = "", $encryption = false) {
        $enc = "";
        //look for service and port is given, seperated with semicolon :
        if (strpos(':', $service) > 0) {
            $serviceAr = explode(':', $service);
            //check given service and port, and if allowed
            if (count($serviceAr) == 2 && in_array($serviceAr[1], $this->allowedServices)) {
                //if can used
                $enc .= ':' . $this->port[$serviceAr[0]];
                $enc .= '/' . $serviceAr[1];
            } else {
                //false call, use standard
                $enc .= ':' . $this->port[$service];
                $enc .= '/' . $this->service;
            }
        } else {
            //if services is given take that
            ($service !== "") || $this->serice = $service;
            //take port that belongs to service
            $enc .= ':' . $this->port[$service];
            $enc .= '/' . $this->service;
        }
        //get kind of encryption
        if ($encryption != null && isset($encryption) && $encryption == 'ssl') {
            $enc = '/imap/ssl/novalidate-cert';
        } else if ($encryption != null && isset($encryption) && $encryption == 'tls') {
            $enc = '/imap/tls/novalidate-cert';
        } else if (!$encryption) {
            $enc = '/imap/novalidate-cert';
        }
        //set self->mailbox
        $this->mailbox = "{" . $mserver . $enc . "}";
        //open mailbox with settings
        $this->imap = imap_open($this->mailbox, $username, $password);
        if (!$this->imap &&
            $this->automaticSwitch) {
            //Switch to other service imap || pop3 || socket
        }
    }

    /**
     * close connection
     */
    function __destruct() {
        if ($this->imap !== false) {
            imap_close($this->imap);
        }
    }

    /**
     * returns imap instance
     * 
     * @return type imap
     */
    public function getInstance() {
        return $this->imap;
    }

    /**
     * returns true if connected
     *
     * @return bool true on success
     */
    public function isConnected() {
        return $this->imap !== false;
    }

    /**
     * 
     * @return  int
     */
    public function countMessages() {
        return imap_num_msg($this->imap);
    }

    /**
     * 
     * @return int
     */
    public function countUnreadMessages() {
        $result = imap_search($this->imap, 'UNSEEN');
        if ($result === false) {
            return 0;
        }
        return count($result);
    }

    /**
     * returns last imap error
     *
     * @return string error message
     */
    public function getError() {
        return imap_last_error();
    }

    public function selectFolder($folder) {
        $result = imap_reopen($this->imap, $this->mailbox . $folder);
        if ($result === true) {
            $this->folder = $folder;
        }
        return $result;
    }

    public function getFolders() {
        $folders = imap_list($this->imap, $this->mailbox, "*");
        return str_replace($this->mailbox, "", $folders);
    }

    /**
     * returns unseen emails in folder
     * 
     * @param $body type boolean
     * @return type array
     */
    public function getUnreadMessages($body = true) {
        $emails = array();
        $result = imap_search($this->imap, 'UNSEEN');
        if ($result) {
            //foreach ($result as $k => $i) {
            foreach ($result as $i) {
                $emails[] = $this->structureMessage($i, $body);
            }
        }
        return $emails;
    }

    /**
     * returns all emails in folder
     *
     * @return array messages
     * @param $withbody without body
     */
    public function getMessages($body = true) {
        $count = $this->countMessages();
        $emails = array();
        for ($i = 1; $i <= $count; $i++) {
            $emails[] = $this->structureMessage($i, $body);
        }

        return $emails;
    }

    /**
     * get number of messages
     * @param type $anum
     * @param type $to
     * @param type $subject
     * @param type $nobody
     * @return type                                    140    142
     */
    public function getNumToMessages($snum = 0, $to = 1, $subject = true) {
        $emails = '';
        $num = ($snum !== 0) ? $snum : $this->countMessages() - 1;
        $count = ($to === 0) ? $num - $to : $to;
        for ($i = $count; $i >= $num; $i--) {
            $email = $this->structureMessage($i);
            if ($subject) {
                $emails .= '<strong>' . $email['subject'] . '</strong><br />';
            }
            $emails .= $email['body'] . '<br /><br />';
        }
        $ret = (isset($emails)) ? $emails : 0; //0 == prefend error    
        return $ret;
    }

    /**
     * returns email by given id
     *
     * @return array messages
     * @param $id
     * @param $withbody without body
     */
    public function getMessage($id, $withbody = true) {
        return $this->structureMessage($id, $withbody);
    }

    private function structureMessage($id, $body = true) {
        //get header
        $header = imap_headerinfo($this->imap, $id);

        // fetch uid
        $uid = imap_uid($this->imap, $id);

        // get data
        $asubject = '';
        if (isset($header->subject) && strlen($header->subject) > 0) {
            foreach (imap_mime_header_decode($header->subject) as $obj) {
                $asubject .= $obj->text;
            }
        }
        // subject
        $subject = $this->convertToUtf8($asubject);
        //mail meta info
        $email = array(
            'to' => isset($header->to) ? $this->arrayToAddress($header->to) : '',
            'from' => $this->toAddress($header->from[0]),
            'date' => $header->date,
            'subject' => $subject,
            'uid' => $uid,
            'unread' => strlen(trim($header->Unseen)) > 0,
            'answered' => strlen(trim($header->Answered)) > 0
        );
        // cc address to?
        if (isset($header->cc)) {
            $email['cc'] = $this->arrayToAddress($header->cc);
        }

        // get mail body
        if ($body === true) {
            $abody = $this->getBody($uid);
            $email['body'] = $abody['body'];
            $email['html'] = $abody['html'];
        }

        // fetch structure
        $mailStruct = imap_fetchstructure($this->imap, $id);
        // get attachments
        $attachments = $this->attachments2name($this->getAttachments($this->imap, $id, $mailStruct, ""));
        if (count($attachments) > 0) {

            foreach ($attachments as $val) {
                foreach ($val as $k => $t) {
                    if ($k == 'name') {
                        /*decode type  @return array The decoded elements are returned in an array 
                         * of objects, where each object has two properties, charset and text.
                         */
                        $decodedName = imap_mime_header_decode($t);
                        //UTF 8 translation
                        $t = $this->convertToUtf8($decodedName[0]->text);
                    }
                    $arr[$k] = $t;
                }
                $email['attachments'][] = $arr;
            }
        }
        return $email;
    }

    /**
     * convert to utf8
     *
     * @return true or false
     * @param $string utf8 encoded
     */
    function convertToUtf8($str) {
        if (mb_detect_encoding($str, "UTF-8, ISO-8859-1, GBK") != "UTF-8")
            $str = utf8_encode($str);
        $str = iconv('UTF-8', 'UTF-8//IGNORE', $str);
        return $str;
    }

    /**
     * del message
     * @param type $id
     * @return type
     */
    public function delMessage($id) {
        $ok = imap_delete($this->imap, $id) && imap_expunge($this->imap);
        return $ok;
    }

    /**
     * delete given message
     *
     * @return bool success or not
     * @param $id of the message
     */
    public function deleteMessage($id) {
        return $this->deleteMessages(array($id));
    }

    /**
     * delete messages
     *
     * @return bool success or not
     * @param $ids array of ids
     */
    public function deleteMessages($ids) {
        if (imap_mail_move($this->imap, implode(",", $ids), $this->getTrash(), CP_UID) == false) {
            return false;
        }
        return imap_expunge($this->imap);
    }

    /**
     * move given message in new folder
     *
     * @return bool success or not
     * @param $id of the message
     * @param $target new folder
     */
    public function moveMessage($id, $target) {
        return $this->moveMessages(array($id), $target);
    }

    /**
     * move given message in new folder
     *
     * @return bool success or not
     * @param $ids array of message ids
     * @param $target new folder
     */
    public function moveMessages($ids, $target) {
        if (imap_mail_move($this->imap, implode(",", $ids), $target, CP_UID) === false) {
            return false;
        }
        return imap_expunge($this->imap);
    }

    /**
     * mark message as read/unread
     *
     * @return bool success or not
     * @param $id of the message
     * @param $seen true = message is read, false = message is unread
     */
    public function setUnseenMessage($id, $seen = true) {
        $header = $this->getMessageHeader($id);
        if ($header == false) {
            return false;
        }

        $flags = "";
        $flags .= (strlen(trim($header->Answered)) > 0 ? "\\Answered " : '');
        $flags .= (strlen(trim($header->Flagged)) > 0 ? "\\Flagged " : '');
        $flags .= (strlen(trim($header->Deleted)) > 0 ? "\\Deleted " : '');
        $flags .= (strlen(trim($header->Draft)) > 0 ? "\\Draft " : '');

        $flags .= (($seen == true) ? '\\Seen ' : ' ');
        imap_clearflag_full($this->imap, $id, '', ST_UID);
        return imap_setflag_full($this->imap, $id, trim($flags), ST_UID);
    }

    /**
     * mark message as read
     * @param type $id
     * @return type
     */
    public function setSeenMessage($id) {
        $status = imap_setflag_full($this->imap, $id, "\\Seen");
        return $status;
    }

    /**
     * fetch message by id
     *
     * @return header
     * @param $id of the message
     */
    private function getMessageHeader($id) {
        $count = $this->countMessages();
        for ($i = 1; $i <= $count; $i++) {
            $uid = imap_uid($this->imap, $i);
            if ($uid == $id) {
                $header = imap_headerinfo($this->imap, $i);
                return $header;
            }
        }
        return false;
    }

    /**
     * get message headers in table(div) structure
     * @param type $tableDiv
     * @param type $amount
     * @param type $showFirst
     * @return type
     */
    public function getMessageHeaders($tableDiv/* class */, $amount = 10, $showFirst = false) {
        $imapInstance = $this->imap; // imap->getInstance();
        $numMessages = imap_num_msg($imapInstance);
        $html = "";
        $confirm = __('Delete this E-mail?', 'mailclient');
        //array for Div_table class
        $data = array();
        //some heading
        if ($tableDiv->delete) {
            $data[] = array('<input type="checkbox" id="toggle" onclick="toggle_checkboxes()" />',
                __('From', 'mailclient'),
                __('Subject', 'mailclient'),
                __('Read', 'mailclient'),
                __('Delete', 'mailclient'));
        } else {
            $data[] = array(__('From', 'mailclient'),
                __('Subject', 'mailclient'),
                __('Read', 'mailclient'),
                __('Delete', 'mailclient'));
        }

        for ($i = $numMessages; $i > ($numMessages - $amount); $i--) {
            $header = imap_header($imapInstance, $i);

            $fromInfo = $header->from[0];
            $replyInfo = $header->reply_to[0];
            $details = array(
                "fromAddr" => (isset($fromInfo->mailbox) && isset($fromInfo->host)) ? $fromInfo->mailbox . "@" . $fromInfo->host : "",
                "fromName" => (isset($fromInfo->personal)) ? $fromInfo->personal : "",
                "replyAddr" => (isset($replyInfo->mailbox) && isset($replyInfo->host)) ? $replyInfo->mailbox . "@" . $replyInfo->host : "",
                "replyName" => (isset($replyTo->personal)) ? $replyto->personal : "",
                "subject" => (isset($header->subject)) ? $header->subject : "",
                "udate" => (isset($header->udate)) ? $header->udate : "",
                "unseen" => (isset($header->Unseen) && ($header->Unseen == "U"))
            );
            /*
              if ((isset($header->Unseen)&&($header->Unseen=="U")||($i==$numMessages))&&$showFirst) {
              //$details['content'] = imap_body($imapInstance, $i);
              //get message body and cut Doctype/<html><body> etc....
              $details['content'] = $this->imap->cut_body_part($this->imap->getMessage($i)['body']);
              }
             */
            $uid = imap_uid($imapInstance, $i);
            //data for Div_Table
            if (isset($details["fromName"])) {
                isset($details["fromName"]) || $details["fromName"] = '&nbsp;';
                isset($details["subject"]) || $details["subject"] = '&nbsp;';
                isset($this->folder) || $this->folder = '&nbsp;';
                isset($uid) || $uid = '&nbsp;';
                //set some alert for unseen messages
                (!$details['unseen']) ||
                    $details["fromName"] = '<span class="alert">* </span>' . //some star
                    '<span class="mailNew">' . $details["fromName"] . '</span>';
                //set some data like name, subject and link to message
                //*
                unset($tmp);
                if ($tableDiv->delete) {
                    $tmp[] = '<input type="checkbox" name="delete[]" value="del[]" />';
                }
                $tmp[] = $details["fromName"];
                $tmp[] = $details["subject"];
                //$tmp[] = '<a href="admin.php?page=mailbox\?mail_id=' .
                //    $i . '/ ">' . __('readMail','mailclient') . '</a>';
                $tmp[] = '<a href="' . '../wp-admin/admin.php?page=mailbox&mail_id=' . $i . '" ' .
                    'title="' . __('Read E-Mail', 'mailclient') . '">' .
                    ' <img src="' . $this->imgPath . 'edit_25.png "></a>';
                $tmp[] = '<a href="../wp-admin/admin.php?page=mailbox&del_mail_id=' . $i . '" ' .
                    'title="' . __('Delete this E-mail?', 'mailclient') . '"><img ' .
                    'onClick="return confirm(\' ' . $confirm . '\')" src="' . $this->imgPath . 'delete_25.png "></a>';
                /* $tmp[] = '<a href="#" onclick="get_ajax_info(\'mailHeaders\',\' ' .
                  MAILCLIENT_PLUGIN_URL . '/mailControl/delMailMessage/' .
                  $i . '/ \')' . '">' . __('delMail','mailclient') . '</a>'; */
                $data[] = $tmp;
                //*/
                /*
                  $data[] = array($details["fromName"],//delMailMessage
                  $details["subject"],
                  '<a href="#" onclick="get_ajax_info(\'ajax_container\',\' ' .
                  SELFDIR . 'fmail_control/readMailMessage/' .
                  $i . '/ \')' . '">' . lang('readMail') . '</a>',
                  '<a href="#" onclick="get_ajax_info(\'mailHeaders\',\' ' .
                  SELFDIR . 'fmail_control/delMailMessage/' .
                  $i . '/ \')' . '">' . lang('delMail') . '</a>');
                  // */
                //if (isset($tmp)) $data[] = $tmp + $data;
            }
            /*
              $html .= "<ul>";
              $html .= "<li><strong>From:</strong>" . $details["fromName"];
              $html .= " " . $details["fromAddr"] . "</li>";
              $html .= "<li><strong>Subject:</strong> " . $details["subject"];
              if (($details["unseen"])||($i==$numMessages)) {
              $html .= '<strong style="color:red">*</strong><br />';
              if (isset($details['content']))
              $html .= '<p>' . $details['content'] . '</p>';
              }
              $html .= "</li>";
              $html .= '<li><a href="' . SELFDIR . 'fmail_control/read/' . $this->folder . '&uid=' . $uid . '&func=read">Read</a>';
              $html .= " | ";
              $html .= '<a href="mail.php?folder=' . $this->folder . '&uid=' . $uid . '&func=delete">Delete</a></li>';
              $html .= "</ul>";
             * 
             */
        }
        $tableDiv->data = $data;
        //$html .= '<br /><br />';
        $html .= $tableDiv->show_div_table();
        return $html;
    }

    /**
     * convert attachment in array(name => ..., 
     *                             size => ...).
     *
     * @return array
     * @param $attachments
     */
    private function attachments2name($attachments) {
        $names = array();
        foreach ($attachments as $attachment) {
            $names[] = array(
                'name' => $attachment['name'],
                'size' => $attachment['size']
            );
        }
        return $names;
    }

    /**
     * convert imap given address in string
     *
     * @return string in format "Name <email@bla.de>"
     * @param $headerinfos the infos given by imap
     */
    private function toAddress($headerinfos) {
        $email = "";
        $name = "";
        if (isset($headerinfos->mailbox) && isset($headerinfos->host)) {
            $email = $headerinfos->mailbox . "@" . $headerinfos->host;
        }

        if (!empty($headerinfos->personal)) {
            $name = imap_mime_header_decode($headerinfos->personal);
            $name = $name[0]->text;
        } else {
            $name = $email;
        }

        $name = $this->convertToUtf8($name);

        return $name . " <" . $email . ">";
    }

    /**
     * converts imap given array of addresses in strings
     *
     * @return array with strings (e.g. ["Name <email@bla.de>", "Name2 <email2@bla.de>"]
     * @param $addresses imap given addresses as array
     */
    private function arrayToAddress($addresses) {
        $addressesAsString = array();
        foreach ($addresses as $address) {
            $addressesAsString[] = $this->toAddress($address);
        }
        return $addressesAsString;
    }

    /**
     * returns body of the email. First search for html version of the email, then the plain part.
     *
     * @return string email body
     * @param $uid message id
     */
    private function getBody($uid) {
        $body = $this->get_part($this->imap, $uid, "TEXT/HTML");
        $html = true;
        // if HTML body is empty, try getting text body
        if ($body == "") {
            $body = $this->get_part($this->imap, $uid, "TEXT/PLAIN");
            $html = false;
        }
        $body = $this->convertToUtf8($body);
        return array('body' => $body, 'html' => $html);
    }

    /**
     * returns a part with a given mimetype
     * taken from http://www.sitepoint.com/exploring-phps-imap-library-2/
     *
     * @return string email body
     * @param $imap imap stream
     * @param $uid message id
     * @param $mimetype
     */
    private function get_part($imap, $uid, $mimetype, $structure = false, $partNumber = false) {
        if (!$structure) {
            /**
             * This optional parameter only has a single option, FT_UID, 
             * which tells the function to treat the msg_number argument as a UID. 
             */
            //get stuture
            $structure = imap_fetchstructure($imap, $uid, FT_UID);
        }
        if ($structure) {
            if ($mimetype == $this->get_mime_type($structure)) {
                if (!$partNumber) {
                    $partNumber = 1;
                }
                /**
                  FT_UID - The msg_number is a UID
                  FT_PEEK - Do not set the \Seen flag if not already set
                  FT_INTERNAL - The return string is in internal format, will not canonicalize to CRLF.
                 */
                $text = imap_fetchbody($imap, $uid, $partNumber, FT_UID | FT_PEEK);
                switch ($structure->encoding) {
                    case 3: return imap_base64($text);
                    case 4: return imap_qprint($text);
                    default: return $text;
                }
            }

            // multipart 
            if ($structure->type == 1) {
                foreach ($structure->parts as $index => $subStruct) {
                    $prefix = "";
                    if ($partNumber) {
                        $prefix = $partNumber . ".";
                    }
                    $data = $this->get_part($imap, $uid, $mimetype, $subStruct, $prefix . ($index + 1));
                    if ($data) {
                        return $data;
                    }
                }
            }
        }
        return false;
    }

    /**
     * 
     * @param type $structure
     * @return string
     */
    private function get_mime_type($structure) {
        $primaryMimetype = array("TEXT", "MULTIPART", "MESSAGE", "APPLICATION", "AUDIO", "IMAGE", "VIDEO", "OTHER");

        if ($structure->subtype) {
            return $primaryMimetype[(int) $structure->type] . "/" . $structure->subtype;
        }
        return "TEXT/PLAIN";
    }

    private function getAttachments($imap, $mailNum, $part, $partNum) {
        $attachments = array();

        if (isset($part->parts)) {
            foreach ($part->parts as $key => $subpart) {
                if ($partNum != "") {
                    $newPartNum = $partNum . "." . ($key + 1);
                } else {
                    $newPartNum = ($key + 1);
                }
                $result = $this->getAttachments($imap, $mailNum, $subpart, $newPartNum);
                if (count($result) != 0) {
                    array_push($attachments, $result);
                }
            }
        } else if (isset($part->disposition)) {
            if (strtolower($part->disposition) == "attachment") {
                $partStruct = imap_bodystruct($imap, $mailNum, $partNum);
                $attachmentDetails = array(
                    "name" => $part->dparameters[0]->value,
                    "partNum" => $partNum,
                    "enc" => $partStruct->encoding,
                    "size" => $part->bytes
                );
                return $attachmentDetails;
            }
        }

        return $attachments;
    }

}
