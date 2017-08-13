<br />
<div class="container">
    <div class="rcorners0">
        <h2><?php 
                echo __('MMessageReadaMessage', 'mailclient'); 
            ?>
        </h2>
    </div>
    <div class="rcorners2">
        <?php
        if (!isset($mess1)) {
            $mess1  = '<h2><span style="color: #ff0000;">Webmail easy for You ♥ </span></h2>';
            $mess1 .= 'Recieve and Send your friends e-mails';
        }
        /**
         * split sender e-mail address/notation to real mail-address
         * best done in  mailclient_mailbox
         */
        //check for a subject
        $subject  = (isset($msg['mess']['subject']))?$msg['mess']['subject']:"";
        //dubble check $mailaddress, prevent a html error
        isset($mailaddress)||$mailaddress="";
        $content = '<div>' . $mess1 . '</div>';
        $editor_id = 'mc_editor';
        global $proID;
        $settings['media_buttons'] = false;
        add_filter("mce_buttons", "tinymce_editor_buttons", 99); //targets the first line
        add_filter("mce_buttons_2", "tinymce_editor_buttons_second_row", 99); //targets the second line
        function tinymce_editor_buttons($buttons) {
            return array('separator');
        }
        function tinymce_editor_buttons_second_row($buttons) {
           //return an empty array to remove this line
            return array();
        }
        $edit = wp_editor($content, $editor_id, $settings);
        ?>
        <br />
        <div style="text-align: left" class="rcorners6">    
            <form method="post" action="admin.php" id="maiform">
                <?php echo '<div class="bxleft"><strong>' . __('Subject', 'mailclient') . '</strong></div>'; ?> 
                <input placeholder="<?php echo __('Subject', 'mailclient'); ?>" 
                       id="subject" 
                       name="subject" 
                       size="60" 
                       value="<?php echo $subject;?>" /><br />
                <?php echo '<div class="bxleft"><strong>' . __('Recieved from', 'mailclient') . '</strong></div>'; ?> 
                <input placeholder="<?php echo __('Recieved from', 'mailclient'); ?>" 
                       id="sendto" 
                       name="sendto" 
                       size="50" 
                       value="<?php echo $mailaddress;?>"  /><br />
                <?php echo '<div  style="margin-left:20%">'; ?> 
                <br /> 
                <?php
                    if (isset($proID)&&$proID) {
                ?>        
                <input class="button" value="<?php echo __('Send', 'mailclient'); ?>" type="submit">
                <?php
                    }
                ?>
                <?php echo '</div>'; ?>
            </form><br />
        </div>
    </div>
    <!--
    <div class="rcorners9">
        <h2><?php echo __('MMessageSendaMessage', 'mailclient'); ?></h2>
    </div>
    //-->
</div>    
