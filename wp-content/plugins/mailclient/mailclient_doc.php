<?php
/*
 * SUCKS!!!!! Wordpress NEEDS input Name instead of ID
 */
?>
<div class="rcorners6">
    <div><h1>Description of our Free Webmail plugin!</h1></div>
    <strong>
        This simple program is a tool for using Webmail on your OWN Website!.<br />
        <br /><span style="color:red;">
            Without PHP IMAP extention activated this version will not operate!</span><br /><br />
    </strong>
    After some simple initialsation, like servername, username and password. You are ready to read, delete and send mail messages.
    <br /><br />
    You can use it in backend and frontend of your website.<br />
    For use in the admin (backend), look for menu item [Make Mailbox]. (depends on language) <br /><br />

    Use shortcode [webmail] in a page or widget, thats all!<br />
    <br />
    <h3> Some simple initialization for admin is provided</h3>
    <img style="border:1px solid red" src="<?php echo MAILCLIENT_PLUGIN_URL; ?>/images/Createmailclient.png" class="imgwidth50" /><br />
    <p>Plugin (Lite&Pro) determined what protocol is needed for your mail server</p>  
    <h3> In backend or on Frontend the result can be some mail INBOX like this:</H3>
    <img src="<?php echo MAILCLIENT_PLUGIN_URL; ?>/images/inbox.jpg" class="imgwidth100" /><br />
    <h3>When you have selected a message:</h3>
    <span style="color:red">That spam ;) message can be showed<br />
    </span> 
    <img src="<?php echo MAILCLIENT_PLUGIN_URL; ?>/images/spam.png" class="imgwidth100" /><br />
    <h3 style="color:red">You want to respond with a email message?</h3> 
    <h4> No problem <span style="color:red">Wordpress</span> WYSIWYG editor is there for your needs</h4>
    <img src="<?php echo MAILCLIENT_PLUGIN_URL; ?>/images/editor.jpg"  class="imgwidth100" /><br />
    <br />
    <div class="rcorners1">
        (FMC) == Free Mail Client is freeware and can be used without
        any restrictions <br>
        (LMC) == Lite version of the professional version of Mail Client<br>
        (PMC) == Professional version of Mail Client<br>
        <br>
        <div class="whitebox">
            <table style="text-align: left; width: 100%;"
                   cellpadding="2" cellspacing="2">
                <tbody>
                    <tr class="header">
                        <td style="width: 70%;">Features</td>
                        <td style="width: 10%;" class="text_center"><?php echo __('Free'); ?></td>
                        <td style="width: 10%;" class="text_center"><?php echo __('Lite'); ?></td>
                        <td style="width: 10%;" class="text_center"><?php echo __('Prof'); ?></td>
                    </tr>
                    <tr class="text_bold odd">
                        <td>Recieve mail from mail server</td>
                        <td class="feature_select">x</td>
                        <td class="feature_select">x</td>
                        <td class="feature_select">x</td>
                    </tr>                    
                    <tr class="text_bold even">
                        <td>Recieve mail from multiple mail servers</td>
                        <td class="feature_select">&nbsp;</td>
                        <td class="feature_select">&nbsp;</td>
                        <td class="feature_select">x</td>
                    </tr>
                    <tr class="text_bold odd">
                        <td>Read mail from mail server</td>
                        <td class="feature_select">x</td>
                        <td class="feature_select">x</td>
                        <td class="feature_select">x</td>
                    </tr>                    
                    <tr class="text_bold even">
                        <td>Read mail from multiple mail servers</td>
                        <td class="feature_select">&nbsp;</td>
                        <td class="feature_select">&nbsp;</td>
                        <td class="feature_select">x</td>
                    </tr>
                    <tr class="text_bold odd">
                        <td>Delete mail from mail server</td>
                        <td class="feature_select">x</td>
                        <td class="feature_select">x</td>
                        <td class="feature_select">x</td>
                    </tr>                    
                    <tr class="text_bold even">
                        <td>Delete mail from multiple mail servers</td>
                        <td class="feature_select">&nbsp;</td>
                        <td class="feature_select">&nbsp;</td>
                        <td class="feature_select">x</td>
                    </tr>
                    <tr class="text_bold odd">
                        <td>Send mail message</td>
                        <td class="feature_select">&nbsp;</td>
                        <td class="feature_select">x</td>
                        <td class="feature_select">x</td>
                    </tr>                    
                    <tr class="text_bold even">
                        <td>Send response mail</td>
                        <td class="feature_select">&nbsp;</td>
                        <td class="feature_select">x</td>
                        <td class="feature_select">x</td>
                    </tr>
                    <tr class="text_bold odd">
                        <td>Use of contact list</td>
                        <td class="feature_select">&nbsp;</td>
                        <td class="feature_select">&nbsp;</td>
                        <td class="feature_select">x</td>
                    </tr>                    
                    <tr class="text_bold even">
                        <td>Use of black list</td>
                        <td class="feature_select">&nbsp;</td>
                        <td class="feature_select">&nbsp;</td>
                        <td class="feature_select">x</td>
                    </tr>
                    <tr class="text_bold odd">
                        <td>Use of white list</td>
                        <td class="feature_select">&nbsp;</td>
                        <td class="feature_select">&nbsp;</td>
                        <td class="feature_select">x</td>
                    </tr>                    
                    <tr class="text_bold even">
                        <td>Automatic responce</td>
                        <td class="feature_select">&nbsp;</td>
                        <td class="feature_select">&nbsp;</td>
                        <td class="feature_select">x</td>
                    </tr>
                    <tr class="text_bold odd">
                        <td>Etcetera ...</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td class="feature_select">x</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>    
    <br>
    <div class="rcorners1">
        <div class="whitebox">
            <table style="text-align: left; width: 100%;"
                   cellpadding="2" cellspacing="2">
                <tbody>
                    <tr class="header">
                        <td style="width: 70%;"><?php echo __('Price of'); ?></td>
                        <td style="width: 10%;" class="text_center"><?php echo __('Free'); ?></td>
                        <td style="width: 10%;" class="text_center"><?php echo __('Lite'); ?></td>
                        <td style="width: 10%;" class="text_center"><?php echo __('Prof'); ?></td>
                    </tr>
                    <tr class="text_bold odd">
                        <td><?php echo __('Price of MailClient Version'); ?></td>
                        <td class="feature_select">0.00 &dollar;</td>
                        <td class="feature_select">1    &dollar;</td>
                        <td class="feature_select">10    &dollar;</td>
                    </tr>                    
                </tbody>
            </table>
        </div>
    </div>
</div>

