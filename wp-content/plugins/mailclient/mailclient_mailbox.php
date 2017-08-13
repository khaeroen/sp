<?php
global $mess1;
if (!$mailinfo = get_option('mailclient')) {
    $this->mc_load_mailinit();
    exit;
} else {
//$pp = $_GET['mail_id'];
    $maddress = explode('@', $mailinfo['username']);
    $domain = $maddress[1];
    //check if mail server address is known
    if ($domain!==('localhost')&&!checkdnsrr($domain, "MX")) {
        //if NOT, initiate mailaddress with server, user and password
        $this->mc_load_mailinit();
        exit;
    } else {
        include_once MAILCLIENT_PLUGIN_DIR . '/include/mailControl.php';
        $mailControl = new \MAILCLIENT_PLUGIN_NAME\mailControl();
        if (isset($_GET['mail_id'])) {
            try {
                $msg = $mailControl->readMailMessage($_GET['mail_id']);
            } catch (Exception $e) {
                echo $e->getTraceAsString();
                exit;
            }
            
            if (isset($msg['mess']['from'])) {
                $pos1 = strpos($msg['mess']['from'], '<')+1;
                $pos2 = strlen($msg['mess']['from'])- ($pos1+1);
                if ($pos1&&$pos2) {
                    $mailaddress = substr($msg['mess']['from'], $pos1, $pos2);
                } else {
                    $mailaddress = $msg['mess']['from'];
                }
            }
            $mess1 = (isset($msg['html']))?$msg['html']:'';
            //if null set to empty string
            include MAILCLIENT_PLUGIN_DIR . './mailclient_message.php';
        } elseif (isset($_GET['del_mail_id'])) {
            //echo 'start delete';
            //$mess1 = $mailControl->readMailMessage($_GET['del_mail_id']);
            $del_mail_id = $_GET['del_mail_id'];
            include MAILCLIENT_PLUGIN_DIR . './mailclient_delete.php';
        } else {
            if (function_exists('imap_open')) {
                echo $mailControl->get_mail_headers();
            } else {
                include MAILCLIENT_PLUGIN_DIR . './mailclient_no_imap.php';
            }

        }
    }

?>
<br />
<?php
}
?>
