<?php
    echo '<h3>' . $mailControl->get_username() . '</h3>';
    echo 'Message ID: ' . $del_mail_id . ' DELETED!<br />';
    echo $mailControl->delMailMessage($del_mail_id);