<?php
/**
* Helper Functions.
*
**/

//Main Video Function
function VSWShowVideo($videosource,$videoid,$autoplaysetting,$videowidth,$videoheight,$admin,$shortcode){

//admin = true to show in widget admin
//admin = false to show in blog sidebar

        $v_autoplay2 = $autoplaysetting;
        $v_id2 = $videoid;
		$v_source = $videosource;		
        $v_width2 = $videowidth;
        $v_height2 = $videoheight;
        //declare empty variable to prevent WordPress debug error
        $flashvar = "";
        $flashvar2 = "";
  
      	$source = $v_source;
        
if(!empty($source)): //do this only if video source not empty, to fix widget page loading in IE 9        
        
		//test for source and assign codes accordingly	
		switch ($source) {
		
		case "":
		$value = "";
		$flashvar = "";
		$flashvar2 = "";
		break;		
	
	/**	@since 5.8 we are not using this anymore.. it been quite a while that I made an update!
        case 'YouTube':
        //Youtube changed API.
        //previous modification in version 5.4 does not work for some user, hope this works for everyone.
        //thanks to LoSan for the hint about the need to add ? in embed url. http://wordpress.org/support/topic/cant-turn-off-autoplay
        $value = "http://www.youtube.com/v/$v_id2?autoplay=$v_autoplay2&loop=0&rel=0";
		$flashvar = "";
		$flashvar2 = "";
        break;
		
		case 'Vimeo':
		$value =  "http://vimeo.com/moogaloop.swf?clip_id=$v_id2&amp;server=vimeo.com&amp;loop=0&amp;fullscreen=1&amp;autoplay=$v_autoplay2";
		$flashvar = "";
		$flashvar2 = "";
        break;
   **/     
		
		case 'MySpace':
		$value =  "http://mediaservices.myspace.com/services/media/embed.aspx/m=$v_id2,t=1,mt=video,ap=$v_autoplay2";
		$flashvar = "";
		$flashvar2 = "";
        break;
		
		case 'Veoh':
		$value = "http://www.veoh.com/static/swf/webplayer/WebPlayer.swf?version=AFrontend.5.4.2.20.1002&";
		$value.= "permalinkId=$v_id2&player=videodetailsembedded&id=anonymous&videoAutoPlay=$v_autoplay2";
		$flashvar = "";
		$flashvar2 = "";
        break;
		
	    case 'Blip':
		$value =  "http://blip.tv/play/$v_id2";
		$flashvar = "";
		$flashvar2 = "";
        break;
		
	    case 'WordPress':
		$value =  "http://s0.videopress.com/player.swf?v=1.02";
		$flashvar = "<param name='flashvars' value='$v_id2'>";
		$flashvar2 = 'flashvars="guid='.$v_id2.'"';
        break;
		
		case 'Viddler':
		$value =  "http://www.viddler.com/player/$v_id2";
		if($v_autoplay2=='1'){
		$flashvar = "<param name=\"flashvars\" value=\"autoplay=t\" />\n";
		$flashvar2 = 'flashvars="autoplay=t" ';
		}
        break;
		
		case 'DailyMotion':
		$value =  "http://www.dailymotion.com/swf/$v_id2&autoStart=$v_autoplay2&related=0";
		$flashvar = "";
		$flashvar2 = "";
        break;
				
		
		case 'Revver':
		$value = "http://flash.revver.com/player/1.0/player.swf?mediaId=$v_id2&autoStart=$v_autoplay2";
		$flashvar = "";
		$flashvar2 = "";
		break;
		
	/** @since 6.0, we are not using this anymore... Metacafe uses iframe embed now, and does not have any parameters.. cannot stop autoplay.
		case 'Metacafe':
		$id = split('/',$v_id2);
		$value = "http://www.metacafe.com/fplayer/$id[0]/$id[1].swf";
		if($v_autoplay2=='1'){
		$flashvar = "";
		$flashvar2 = 'flashVars="playerVars=showStats=no|autoPlay=yes|"';
		}
		break;
    **/
				
		case 'Tudou':
		$value = "$v_id2";
		$flashvar = "";
		$flashvar2 = "";
		break;
		
		case 'Youku':
		$value = "$v_id2";
		$flashvar = "";
		$flashvar2 = "";
		break;
		
		case 'cn6':
		$value = "$v_id2";
		$flashvar = "";
		$flashvar2 = "";
		break;
		
		case 'Google':
		$value = "http://video.google.com/googleplayer.swf?docid=$v_id2&hl=en&fs=true";
		if($v_autoplay2=='1'){
		$flashvar = "";
		$flashvar2 = 'FlashVars="autoPlay=true&playerMode=embedded"';
		}
		break;
		
		case 'Tangle':
		$value = "http://www.tangle.com/flash/swf/flvplayer.swf";
		if($v_autoplay2=='1'){
		$flashvar = "";
		$flashvar2 = "FlashVars=\"viewkey=$v_id2&autoplay=$v_autoplay2\"";
		}else{
		$flashvar = "";
		$flashvar2 = "FlashVars=\"viewkey=$v_id2\"";
		}
		break;
	
		}
		
		if($shortcode=="true"){
		
        	if($source == 'YouTube'):
        		/*This is the latest embed iframe code format, we use it now.
        		/*<iframe width="560" height="315" src="//www.youtube.com/embed/OMOVFvcNfvE" frameborder="0" allowfullscreen></iframe>
        		*/
        		return "<iframe width='$v_width2' height='$v_height2' src='//www.youtube.com/embed/$v_id2?autoplay=$v_autoplay2&loop=0&rel=0' frameborder='0' allowfullscreen></iframe>";
 
        	
        	elseif($source == 'Vimeo'):
        		/*This is the latest embed iframe code format, we use it now.
        		*<iframe src="//player.vimeo.com/video/113758779" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
				*/
				return "<iframe src='//player.vimeo.com/video/$v_id2?autoplay=$v_autoplay2' width='$v_width2' height='$v_height2' frameborder='0' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
				
			elseif($source == 'Metacafe'):
			
			return "<iframe width='$v_width2' height='$v_height2' src='http://www.metacafe.com/embed/$v_id2' frameborder='0' allowfullscreen></iframe>";
       	
        	
        	else:		
		
				//added in version 2.3
				//return instead of echo video on blog using shortcode
				$vsw_code = "\n<object width=\"$v_width2\" height=\"$v_height2\">\n";
				$vsw_code .= $flashvar;
				$vsw_code .= "<param name=\"allowfullscreen\" value=\"true\" />\n";
				$vsw_code .= "<param name=\"allowscriptaccess\" value=\"always\" />\n";
				$vsw_code .= "<param name=\"movie\" value=\"$value\" />\n";
				$vsw_code .= "<param name=\"wmode\" value=\"transparent\">\n";
				$vsw_code .= "<embed src=\"$value\" type=\"application/x-shockwave-flash\" wmode=\"transparent\" ";
				$vsw_code .= "allowfullscreen=\"true\" allowscriptaccess=\"always\" ";
				$vsw_code .= $flashvar2;
				$vsw_code .= "width=\"$v_width2\" height=\"$v_height2\">\n";
				$vsw_code .= "</embed>\n";
				$vsw_code .= "</object>\n\n";
				return $vsw_code;
			
			endif;
		
		}
		elseif($admin=="true"){
		    
		    //determine admin video width.
		    global $current_screen;
		    if(@$current_screen->id == 'post' || @$current_screen->id == 'page'){
		    	$admin_video_width = '250';
		    	$admin_video_height = '141';
		    }else{
		    	$admin_video_width = '400';
		    	$admin_video_height = '225';		    
		    }

			
        	if($source == 'YouTube'):
        		/*This is the latest embed iframe code format, we use it now.
        		/*<iframe width="560" height="315" src="//www.youtube.com/embed/OMOVFvcNfvE" frameborder="0" allowfullscreen></iframe>
        		*/
        		echo "<br/><iframe width='$admin_video_width' height='$admin_video_height' src='//www.youtube.com/embed/$v_id2?autoplay=0&loop=0&rel=0' frameborder='0' allowfullscreen></iframe>";
 
        	
        	elseif($source == 'Vimeo'):
        		/*This is the latest embed iframe code format, we use it now.
        		*<iframe src="//player.vimeo.com/video/113758779" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
				*/
				echo "<br/><iframe src='//player.vimeo.com/video/$v_id2?autoplay=0' width='$admin_video_width' height='$admin_video_height' frameborder='0' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
				
				
			elseif($source == 'Metacafe'):
			
			echo "<br/><iframe width='$admin_video_width' height='$admin_video_height' src='http://www.metacafe.com/embed/$v_id2' frameborder='0' allowfullscreen></iframe>";
			
			
			else:
			
				// echo video in admin
		        echo "<br/><object width=\"$admin_video_width\" height=\"$admin_video_height\">\n";
		    	echo $flashvar;
		    	echo "<param name=\"allowfullscreen\" value=\"true\" />\n";
		    	echo "<param name=\"allowscriptaccess\" value=\"always\" />\n";
		    	echo "<param name=\"movie\" value=\"$value\" />\n";
		    	echo "<param name=\"wmode\" value=\"transparent\">\n";
		    	echo "<embed src=\"$value\" type=\"application/x-shockwave-flash\" wmode=\"transparent\" ";
		    	echo "allowfullscreen=\"true\" allowscriptaccess=\"always\" ";
		    	echo $flashvar2;
		    	echo "width=\"$admin_video_width\" height=\"$admin_video_height\">\n";
		    	echo "</embed>\n";
		    	echo "</object>\n\n";
		    	
		    endif;

        }else{
        
        
        	if($source == 'YouTube'):
        		/*This is the latest embed iframe code format, we use it now.
        		/*<iframe width="560" height="315" src="//www.youtube.com/embed/OMOVFvcNfvE" frameborder="0" allowfullscreen></iframe>
        		*/
        		echo "<iframe width='$v_width2' height='$v_height2' src='//www.youtube.com/embed/$v_id2?autoplay=$v_autoplay2&loop=0&rel=0' frameborder='0' allowfullscreen></iframe>";
 
        	
        	elseif($source == 'Vimeo'):
        		/*This is the latest embed iframe code format, we use it now.
        		*<iframe src="//player.vimeo.com/video/113758779" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
				*/
				echo "<iframe src='//player.vimeo.com/video/$v_id2?autoplay=$v_autoplay2' width='$v_width2' height='$v_height2' frameborder='0' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
       	
       		elseif($source == 'Metacafe'):
			
			echo "<iframe width='$v_width2' height='$v_height2' src='http://www.metacafe.com/embed/$v_id2' frameborder='0' allowfullscreen></iframe>";
        	
        	else:
		
				// echo video on blog
				echo "\n<object width=\"$v_width2\" height=\"$v_height2\">\n";
				echo $flashvar;
				echo "<param name=\"allowfullscreen\" value=\"true\" />\n";
				echo "<param name=\"allowscriptaccess\" value=\"always\" />\n";
				echo "<param name=\"movie\" value=\"$value\" />\n";
				echo "<param name=\"wmode\" value=\"transparent\">\n";
				echo "<embed src=\"$value\" type=\"application/x-shockwave-flash\" wmode=\"transparent\" ";
				echo "allowfullscreen=\"true\" allowscriptaccess=\"always\" ";
				echo $flashvar2;
				echo "width=\"$v_width2\" height=\"$v_height2\">\n";
				echo "</embed>\n";
				echo "</object>\n\n";
				
			endif;

		}
endif;

}//end of function VSWShowVideo

/**
* Added in Version 2.3
* Shortcode to echo out video
* Usage [vsw id="123456" source="vimeo" width="400" height="300" autoplay="no"]
**/

function vsw_show_video($atts, $content = null) {
	extract(shortcode_atts(array(
	    "id" => ' ',
		"source" => ' ',
		"width" => ' ',
		"height" => ' ',
		"autoplay" => ' ',
	), $atts));
	
return vsw_show_video_class($id,$source,$width,$height,$autoplay);
}

add_shortcode("vsw", "vsw_show_video");

//function to be used in shortcode or directly in theme
function vsw_show_video_class($id,$source,$width,$height,$autoplay){

        $vsw_id = $id;
		$vsw_width = $width;
		$vsw_height = $height;
		

		//convert string of source to lowercase
        $source = strtolower($source);

        //should have used all lowercase in previous functions
		//now have to switch it.
		switch ($source) {
				
		case "":
		$vsw_source = "";
		break;
		
		case 'youtube':
		$vsw_source = 'YouTube';
		break;
		
		case 'vimeo':
		$vsw_source = 'Vimeo';
        break;
		
		case 'myspace':
		$vsw_source = 'MySpace';
        break;
		
		case 'veoh':
		$vsw_source = 'Veoh';
        break;
		
	    case 'bliptv':
		$vsw_source = 'Blip';
        break;
		
	    case 'wordpress':
		$vsw_source = 'WordPress';
        break;
		
		case 'viddler':
		$vsw_source = 'Viddler';
        break;
		
		case 'dailymotion':
		$vsw_source = 'DailyMotion';
        break;
				
		
		case 'revver':
		$vsw_source = 'Revver';
		break;
		
		case 'metacafe':
		$vsw_source = 'Metacafe';
		break;
		
		case 'tudou':
		$vsw_source = 'Tudou';
		break;
		
		case 'youku':
		$vsw_source = 'Youku';
		break;
		
		case 'cn6':
		$vsw_source = 'cn6';
		break;
		
		case 'google':
		$vsw_source = 'Google';
		break;
		
		case 'tangle':
		$vsw_source = 'Tangle';
		break; 
		
		}
		
		//string to lowercase
		$autoplay = strtolower($autoplay);
		
		//switch autoplay yes or no to 1 or 0
		switch ($autoplay) {
		
		case "":
		$vsw_autoplay = 0;
		break;
		
		case 'no':
		$vsw_autoplay = 0;
		break;
		
		case 'yes':
		$vsw_autoplay = 1;
		break;
		
		}
			
	
$vsw_code = VSWShowVideo($vsw_source,$vsw_id,$vsw_autoplay,$vsw_width,$vsw_height,'false','true');

return $vsw_code;
}
?>