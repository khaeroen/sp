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
 * 
 * @param type $post
 * @return type
 */
function mc_checkMailServer($post) {
    if (isset($post['server'])&&isset($post['username'])&&isset($post['password'])) {
        //check all input
        foreach ($post as $fld=> $value) {
            $post[$fld] = sanitize_text_field($value);
        }
        $mailinfo = array('server'  =>$post['server'],
                          'username'=>$post['username'],
                          'password'=>$post['password']);
        isset($mailinfo['prokey'])||$mailinfo['prokey']=0;
        if ((isset($post['prokey']))&&(mc_checkProKey($post['prokey']))) {
            $mailinfo['prokey'] = $post['prokey'];
        }
        update_option('mailclient', $mailinfo);
    }
    return $mailinfo = get_option('mailclient');
}

/**
 * 
 * @param type $key
 * @return boolean
 */    
function mc_checkProKey($key='') {
    $file = MAILCLIENT_PLUGIN_DIR . '/proversion.key';
    if (file_exists(MAILCLIENT_PLUGIN_DIR . '/proversion.key')) {
        $lines = file(MAILCLIENT_PLUGIN_DIR . '/proversion.key');
        if (($key !== 0) && ($lines[0] === $key)) {
            $ret = $lines[0]===$key;
            return $ret;
        }
        return false;
    } else {
        return null;
    }
}
