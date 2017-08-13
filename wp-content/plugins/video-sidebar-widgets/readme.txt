=== Video Sidebar Widgets ===
Contributors: denzel_chia
Donate link: https://github.com/denzelchia/Video-Sidebar-Widgets/wiki
Author link: https://github.com/denzelchia/
Tags:video, widget, widgets, sidebar, videos, video sidebar widget, video widget, embed video, video embed, video in sidebar, youtube, vimeo, veoh, myspace video, revver, blip tv, tudou, youku, 6.cn, google videos, tangle
Requires at least:2.8.1
Tested up to: 4.7
Stable tag:6.0

== Description ==

 A collection of sidebar widgets for displaying Flash Videos from 14 video sharing networks.
 Video networks includes Youtube, Vimeo, BlipTV, Google, etc. This plugin consist of 2 widgets and 1 TinyMCE editor button.

== Changelog ==

= 6.0 =

* Updated Metacafe code
* Stop using PHP split function


= 5.9 =

* Change to use PHP 5 constructor.


= 5.8 =

* Let YouTube and Vimeo video print iframe embed code, the others remains unchanged.
* Now Youtube and Vimeo video plays in iOS.
* Use two forward slashes for Youtube and Vimeo video src as shown in their original embed code, therefore should work in both http and https environment. 
* Some adjustments to admin layout. Thanks to qcdars for the pointers.


= 5.7 =

* Added check to random video widget, do not print video caption p tags if user did not enter a video caption.


= 5.6 =

* Corrected typo error for constant WP_PLUGIN_URL in vsw_tinymce.php


= 5.5 =

* Previous YouTube autoplay fix does not work for some users. Redo it according to loSan and qcdars's solution http://wordpress.org/support/topic/cant-turn-off-autoplay
* Fix YouTube autoplay in Random Video Widget. (missed this in previous version 5.4)


= 5.4 =

* Fix autoplay of YouTube Videos. The videos started autoplay probably due to a change in YouTube API.


= 5.3 =

* Fix loading in widget page in IE 9

= 5.2 =

* Replaced all null value to blank, to tackle an IE 9 bug reported by user.
* Solution provided by Ryan Willis. (Thanks!)
* Refer to http://wordpress.org/support/topic/plugin-video-sidebar-widgets-ie9-widget-administration-problem

= 5.1 =

* Fixed PHP errors in WordPress debug mode.

= 5.0 =

* Added Post Meta Video Widget (instructions within Widget)

= 4.1 =

* Fixed WordPress Video in random video widget (Sorry forgotten about this!)

= 4.0 =

* Fixed WordPress Video
* Dropped Tangle Video (GodTube)
* added admin settings page for disabling widgets

= 3.0 =

* added video quicktag at post editor to insert video shortcode into post or page.
* Shortcode to allow posting of video on post or page, using video function already available in plugin.

= 2.3 =

* Removed auto play video option for Blip.tv video, which is causing the video not to load in Internet Explorer.
* Allowed HTML links in video caption

= 2.2 =

* Included support for Tangle.com videos

= 2.1 =

* re-arranged Random Video Sidebar Widget Layout

* Included fixed size, no autoplay, video preview in widget form for easier identification of videos. Video will display after setting up and clicking save button. Video may not display due to slow connection, browser caching, wrong setting, or video removed from source.

* Included support for google videos

* Included Video Caption at bottom of Video

* Video Caption has a style class of "VideoCaption" to enable user to indicate font-height etc.

= 2.0 =

* Included Random Video Sidebar Widget to randomly display 1 out of 5 preset video.


== Installation ==

= Install Plugin Using Search =

   1. Search for Video Sidebar widgets in Add New Plugins
   2. Click 'Install Now' in search results row
   3. Click 'OK' to accept plugin installation
   4. Once installed, click Activate Plugin link

= or, install Plugin in .ZIP Format =

   1. Visit Plugins > Add New > Upload in WordPress admin
   2. Upload video-sidebar-widgets.zip file
   3. Once uploaded, click Activate Plugin link


== Can I post video in post or page content other than the sidebar? ==

As of version 3.0, shortcode is added to enable user to post video in content.
User can use the video Quicktag at Tinymce editor to insert video shortcode.

== Where to find Video ID ? ==

Please visit the following documentation

https://github.com/denzelchia/Video-Sidebar-Widgets/wiki#youtube-video-id

== How to use Video Sidebar Widget ? ==

Please visit the following documentation

https://github.com/denzelchia/Video-Sidebar-Widgets/wiki#how-to-use-video-sidebar-widgets


== How to use Random Video Sidebar Widget ? ==

Please visit the following documentation

https://github.com/denzelchia/Video-Sidebar-Widgets/wiki#how-to-use-random-video-widget


== How to use Post Meta Video Widget ? ==

Please visit the following documentation

https://github.com/denzelchia/Video-Sidebar-Widgets/wiki#how-to-use-post-meta-video-widget


== How to Embed Video in Post / Page Content? ==

Please visit the following documentation

https://github.com/denzelchia/Video-Sidebar-Widgets/wiki#embed-video-in-post--page-content


== Frequently Asked Questions ==

1. Why is Video Sidebar Widget not compatible with WordPress 2.8 ?

It is not compatible because a bug was found to cause settings of multiple instance of a widget to disappear or mixed up.
The bug was fixed in WordPress 2.8.1

2. Why is my Random Video sidebar Widget not working?

Reload the webpage where you use the widget, each time you reload, the widget will randomly pick 1 of the 5 video you set for display. there are chances that it displays the same video after you reload. Just try reloading again.

Random Video Sidebar Widget will not work on blogs that uses cache plugins. It is because the cache plugin will deliver the generated static page instead of regenerating blog page or post through server script. However, it may work if your theme has a page template with a different sidebar. You can then set your cache plugin not to cache that particular page or post, if there is such option available in that cache plugin. 


== Screenshots ==

Please visit plugin wiki for details. https://github.com/denzelchia/Video-Sidebar-Widgets/wiki




