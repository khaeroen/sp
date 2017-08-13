<?php
//class start
class NM_addCustomButton_filemanager {

public $btn_arr;
public $js_file;
/*
 * call the constructor and set class variables
 * From the constructor call the functions via wordpress action/filter
*/
function __construct($seperator, $btn_name,$javascrip_location){
  $this->btn_arr = array("Seperator"=>$seperator,"Name"=>$btn_name);
  $this->js_file = $javascrip_location;
  add_action('init', array(&$this,'addTinyMCEButton'));
  add_filter( 'tiny_mce_version', array(&$this,'refreshMCEVersion'));

}
/*
 * create the buttons only if the user has editing privs.
 * If so we create the button and add it to the tinymce button array
*/
function addTinyMCEButton() {
   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
     return;
   if ( get_user_option('rich_editing') == 'true') {
     //the function that adds the javascript
     add_filter('mce_external_plugins', array(&$this,'addNewTinyMCEPlugin'));
	 //adds the button to the tinymce button array
     add_filter('mce_buttons', array(&$this,'registerNewButton')); 
   }
}
/*
 * add the new button to the tinymce array
*/
function registerNewButton($buttons) {
   array_push($buttons, $this->btn_arr["Seperator"],$this->btn_arr["Name"]);
   return $buttons;
}
/*
 * Call the javascript file that loads the 
 * instructions for the new button
*/
function addNewTinyMCEPlugin($plugin_array) {
   $plugin_array[$this->btn_arr['Name']] = $this->js_file;
   return $plugin_array;
}
/*
 * This function tricks tinymce in thinking 
 * it needs to refresh the buttons
*/
function refreshMCEVersion($ver) {
  $ver += 3;
  return $ver;
}

}//class end
?>