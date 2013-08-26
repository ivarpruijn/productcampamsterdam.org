=== JJ NextGen JQuery Slider ===
Contributors: JJ Coder
Donate link: http://www.redcross.org.nz/donate
Tags: image, picture, photo, widgets, gallery, images, nextgen-gallery, jquery, niveo-slider, slider, javascript
Requires at least: 2.8
Tested up to: 3.2
Stable tag: 1.3.9

Allows you to pick a gallery from the 'NextGen Gallery' plugin to use as a 'JQuery Nivo slider'.

== Description ==

The 'JJ NextGen JQuery Slider' allows you to create a 'Nivo slider' (http://nivo.dev7studios.com/) as a widget or as a shortcode.
This plugin uses the 'NextGen Gallery' plugin for its images.

Requirements:

- NextGen Gallery Plugin (http://wordpress.org/extend/plugins/nextgen-gallery/)

NextGen Gallery Integration:

- This plugin uses the original width and height of the image uploaded so make sure the images are the correct dimensions when uploaded.
- Alt & Title Text Field: Provide a full url here and the image will link to this. Only works if alt field starts with either of these; /, http, or ftp.
- Description Field: Will be used as a caption.

You can specify the following parameters:

NOTE: sc means shortcode:

- Title: Title for slider. Leave blank for no title. (sc: title="My Slider")
- Gallery: Leave blank to use all galleries or choose a gallery to use. (sc: gallery="galleryid")
- Order: Order to display results in. You can choose; Random, Latest First, Oldest First, or NextGen Sortorder. Random will still work when a page is cached. (sc: order="random"|"asc"|"desc"|"sortorder")
- Tags: comma separated list of tags to filter results by. (sc: tags="tag1, tag2")
- Shuffle: If order is random and this is true will shuffle images with javascript. Useful if your are caching your pages. (sc: shuffle="true"|"false")
- Max pictures: The maximum amount of pictures to use. (sc: max_pictures="6")
- HTML id: HTML id to use. Defaults to 'slider'. Needs to be different for multiple sliders on same page. (sc: html_id="slider")
- Width: Width to use on slider. (sc: width="200")
- Height: Height to use on slider. (sc: height="150")
- Center: Centers content in container. Requires width to be set. (sc: center="1")

Nivo slider settings:

Please check the Nivo slider home page for more details (http://nivo.dev7studios.com/#usage).

- effect: Specify sets like: 'fold,fade,sliceDown'. (sc: effect="setting")
- slices: (sc: slices="setting")
- boxCols: (sc: boxcols="setting")
- boxRows: (sc: boxrows="setting")
- animSpeed: Slide transition speed. (sc: animspeed="setting")
- pauseTime: (sc: pausetime="setting")
- startSlide: Set starting Slide (0 index). (sc: startslide="setting")
- directionNav: Next & Prev. (sc: directionnav="setting")
- directionNavHide: Only show on hover. (sc: directionnavhide="setting")
- controlNav: 1,2,3... (sc: controlnav="setting")
- controlNavThumbs: Use thumbnails for Control Nav. You can choose 'nextgen thumbs'' that will automatically use the image's thumbnail from nextgen gallery or 'nextgen original' that will use the current image. (sc: controlnavthumbs="true"|"false"|"nextgen_thumbs"|"nextgen_original")
- thumbsWidth: Resize thumbnail to this width. Recommended to set if using thumbnails. (sc: thumbswidth="20")
- thumbsHeight: Resize thumbnail to this height. Recommended to set if using thumbnails. (sc: thumbsheight="20")
- thumbsContainerHeight: Height for thumbnails container. Calculation should be 'number of thumbnail image rows' x 'thumbsheight'. (sc: thumbscontainerheight="20")
- thumbsGap: Gap between thumbnails. (sc: thumbsgap="5")
- controlNavThumbsFromRel: Use image rel for thumbs. (sc: controlnavthumbsfromrel="setting")
- controlNavThumbsSearch: Replace this with... (sc: controlnavthumbssearch="setting")
- controlNavThumbsReplace: ...this in thumb Image src. (sc: controlnavthumbsreplace="setting")
- keyboardNav: Use left & right arrows. (sc: keyboardnav="setting")
- pauseOnHover: Stop animation while hovering. (sc: pauseonhover="setting")
- manualAdvanc: Force manual transitions. (sc: manualadvance="setting")
- captionOpacity: Universal caption opacity. (sc: captionopacity="setting")
- Disable captions: (sc: disablecaptions="1")
- beforeChange: (sc: beforechange="setting")
- afterChange: (sc: afterchange="setting")
- slideshowEnd: Triggers after all slides have been shown. (sc: slideshowend="setting")
- lastSlide: Triggers when last slide is shown. (sc: lastslide="setting")
- afterLoad: Triggers when slider has loaded. (sc: afterload="setting")

Nivo Effects:

- sliceDown
- sliceDownLeft
- sliceUp
- sliceUpLeft
- sliceUpDown
- sliceUpDownLeft
- fold
- fade
- random
- slideInRight
- slideInLeft
- boxRandom
- boxRain
- boxRainReverse
- boxRainGrow
- boxRainGrowReverse

Shortcodes:

- [jj-ngg-jquery-slider html_id="about-slider"]
- [jj-ngg-jquery-slider title="Hello" gallery="1" html_id="about-slider" width="200" height="150" center="1"]
- [jj-ngg-jquery-slider html_id="about-slider" directionnav="false" controlnav="false"]

Try out my other plugins:

- JJ NextGen JQuery Carousel (http://wordpress.org/extend/plugins/jj-nextgen-jquery-carousel/)
- JJ NextGen JQuery Cycle (http://wordpress.org/extend/plugins/jj-nextgen-jquery-cycle/)
- JJ NextGen Unload (http://wordpress.org/extend/plugins/jj-nextgen-unload/)
- JJ NextGen Image List (http://wordpress.org/extend/plugins/jj-nextgen-image-list/)
- JJ SwfObject (http://wordpress.org/extend/plugins/jj-swfobject/)

== Installation ==

Please refer to the description for requirements and how to use this plugin.

1. Copy the entire directory from the downloaded zip file into the /wp-content/plugins/ folder.
2. Activate the "JJ NextGEN JQuery Slider" plugin in the Plugin Management page.
3. Refer to the description to use the plugin as a widget and or a shortcode.

== Frequently Asked Questions ==

Question: 

- Why is my slider not showing up in IE?

Answer: 

- Try to set the width and height properties.

Question: 

- If images are different sizes how can you stop previous image from showing in background?

Answer: 

- Try adding code below to afterChange callback substitute #test_slider for your html_id
`function() { jQuery("#test_slider").css("background-image", "none"); }`

Question:

- How can I use plugin inside normal PHP code?

Answer:

- echo do_shortcode('[jj-ngg-jquery-slider title="Hello" gallery="1" html_id="about-slider" width="200" height="150" center="1"]');

Question:

- Doesn't work after upgrade? or Doesn't work with this theme?
  
Answer:

- Please check that you don't have two versions of jQuery loading, this is the problem most of the time. Sometimes a theme puts in <br> tags at the end of newlines aswell.
== Screenshots ==

1. Screenshot 1: Using text based navigation.
2. Screenshot 2. Navigation turned off.
3. Screenshot 3: Thumbnail navigation generated from nextgen gallery.

Try out my other plugins:

- JJ NextGen JQuery Carousel (http://wordpress.org/extend/plugins/jj-nextgen-jquery-carousel/)
- JJ NextGen JQuery Cycle (http://wordpress.org/extend/plugins/jj-nextgen-jquery-cycle/)
- JJ NextGen Unload (http://wordpress.org/extend/plugins/jj-nextgen-unload/)
- JJ NextGen Image List (http://wordpress.org/extend/plugins/jj-nextgen-image-list/)
- JJ SwfObject (http://wordpress.org/extend/plugins/jj-swfobject/)

== Changelog ==

- 1.3.9: Trying to get wordpress to update properly.
- 1.3.8: Tag filter now works with max picture limit.
- 1.3.7: Ability to filter results by tags. (Thanks to #henare)
- 1.3.6: Got rid of border on image and added alt text even though alt text its pretty useless for nivo slider but should validate. (#Chad)
- 1.3.5: Arrow fix. Make sure stylesheets and javascript is refreshed. (AGAIN!)
- 1.3.4: Arrow fix. Make sure stylesheets and javascript is refreshed.
- 1.3.3: Upgraded to Nivo Slider 2.6. Added two new nivo slider settings boxCols and boxRows. New nivo box effects available; boxRain, boxRainReverse, boxRainGrow, boxRainGrowReverse.
- 1.3.2: FAQ.
- 1.3.1: Donate to Christchurch Quake.
- 1.3.0: Wordpress update.
- 1.2.9: Ability to disable captions.
- 1.2.8: FAQ.
- 1.2.7: Readme.
- 1.2.6: Controlnavthumbs fix if false.
- 1.2.5: CSS Fix for space at bottom if text navigation is on.
- 1.2.4: Image description from nextgen field is not being escaped now for captions. Adjustments to allow links to be put in to captions eg <a href="http://site.com">site</a> 
- 1.2.3: No more IE6 png fix. Doesn't work very well and can make arrows not work if links are on images.
- 1.2.2: Margin left for first image of thumbs if centering thumbs. New shuffle field. If order is random and this is true will shuffle images with javascript. Useful if you are caching your pages. This use to be always on in previous verions but some people want images to load in order so if not caching the page no need to be turned on.
- 1.2.1: CSS.
- 1.2.0: Fix.
- 1.1.9: Optimisation.
- 1.1.8: Few fixes with callbacks.
- 1.1.7: NextGen images that are excluded now don't show up.
- 1.1.6: New screenshot.
- 1.1.5: Loading indicator change. Added ability to use nextgen gallery thumbnails automatically for thumbnail navigation or you can use the original nextgen image. You can select this option from the controlNavThumbs property. New fields; thumbsWidth, thumbsHeight, thumbsContainerHeight and thumbsGap.
- 1.1.4: Image attribute border="0" added. before_title, after_title, before_widget, after_widget enabled for widgets. No styling on ul and li now in widget.
- 1.1.3: Widget header is now a h2 tag. Widget output fix.
- 1.1.2: FAQ update.
- 1.1.0: Better support for NextGen galleries already created. Alt text is now checked to see if its a url.
- 1.0.9: Upgraded Nivo Slider to version 2.4.
- 1.0.8: IE6 js now checks image extension. New height field for slider.
- 1.0.7: Arrow image fix.  
- 1.0.6: Not extra space at bottom if controlNav is turned off. IE6 alignment fixes. IE6 directionNav transparency fix.           
- 1.0.5: Tidy up.
- 1.0.4: All params are now lowercase to support shortcodes better.
- 1.0.3: Unobstrusive js is required for this plugin to work consistently.
- 1.0.2: Unobstrusive js not required so now faster to load.
- 1.0.0: First version.

== Contributors ==
