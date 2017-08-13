<?php
include_once MAILCLIENT_PLUGIN_DIR . '/include/control_mailclient.php';
if (isset($_POST)) {
    $mclient = \MAILCLIENT_PLUGIN_NAME\mc_checkMailServer($_POST);
}
//tmp test for additional HTML
$html = '<h1><span style="color: #ff0000;">Webmail easy for You ♥ </span></h1>';
$server= isset($mclient['server'])?$mclient['server']:''; 
$username= isset($mclient['username'])?$mclient['username']:''; 
$password= isset($mclient['password'])?$mclient['password']:''; 
?>
<div class="borderred">
    <div><h1><?php echo __('Initialize a mailbox!','mailclient');?></h1></div>
    <form method="post" action="" id="maiform">
        <?php echo '<div class="bxleft">' .__('Servername','mailclient'). '</div>';?> 
        <input placeholder="<?php echo __('Your Mailserver','mailclient');?>" 
               id="server" 
               name="server" 
               size="50" 
               value="<?php echo $server;?>" /><br />
        <?php echo '<div class="bxleft">' .__('Username','mailclient'). '</div>';?> 
        <input placeholder="<?php echo __('Your Username','mailclient');?>" 
               id="username" 
               name="username" 
               size="30" 
               value="<?php echo $username;?>" /><br />
        <?php echo '<div  class="bxleft">' .__('Password','mailclient'). '</div>';?> 
        <input type="password" 
               placeholder="<?php echo __('Your Password','mailclient');?>" 
               id="password" 
               name="password" 
               size="25" 
               value="<?php echo $password;?>" /><br />
        <?php echo '<div  class="bxleft">' .__('Your Licence key','mailclient'). '</div>';?> 
        <input type="password" 
               placeholder="<?php echo __('Your Licence key','mailclient');?>" 
               id="prokey" 
               name="prokey" 
               size="32" /><br />
        <?php echo '<div  style="margin-left:20%">';?> 
        <input class="button" value="<?php echo __('Send','mailclient');?>" type="submit">
        <?php echo  '</div>';?>
    </form><br />
    <div style="width:100%">    
    <?php
        echo $html;
    ?>
    </div>
</div>