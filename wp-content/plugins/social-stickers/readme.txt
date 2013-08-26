=== Social Stickers ===
Contributors: ZeroCool51
Donate link: http://gum.co/social-stickers
Tags: social profile, social icons, social, social widget, facebook, twitter, social widget
Requires at least: 3.0
Tested up to: 3.5
Stable tag: 2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A simple plugin that shows the various social networks you use. Also supports themes.

== Description ==

This is a simple plugin that shows the various social networks you use in the form of "stickers" (see screenshots). It is also fully themable and you can make your own theme in a minute.

What this plugin offers:

* Themes (fully themable, you can easily create your own theme)
* You can customize the order of the icons (each theme has its own order)
* Add your own social networks
* Widget mode
* Supports shortcodes
* You can also output number of Twitter followers and Facebook page likes
* Fully customize your HTML output

Currently supported social networks:

* 500px
* About Me
* Academia.edu
* AIM
* Anobii
* APP.net
* Behance
* Bebo
* Blogconnect
* Bloglovin
* Blogger
* Coderwall
* Dailybooth
* Delicious
* Designfloat
* Deviantart
* Digg
* Dribble
* Ebay
* Email
* exfm
* Etsy
* Flickr
* Facebook
* Forrst
* Formspring
* Foursquare
* Github
* Geeklist
* Goodreads
* Google+
* Gravatar
* Grooveshark
* Hi5
* IMDB
* Instagram
* LastFM
* Lovelybooks
* Livejournal
* Linkedin
* Mixcloud
* Myspace
* Newsvine
* Orkut
* Picassa
* Pinboard
* Pinterest
* Posterous
* Ravelry
* RSS
* Snapjoy
* Spotify
* Skype
* Stackoverflow
* Quora
* Qik
* Slashdot
* Spotify
* Slideshare
* Soundcloud
* Steam
* Stumbleupon
* Tout
* Tumblr
* Twitter
* Vimeo
* Youtube
* Yelp
* Zerply
* Zootool
* Xing
* Wordpress

== Installation ==

1. Upload the plugin directory to to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Customize the settings in Settings->Social stickers
4. Add the widget, call the function <?php display_social_stickers(); ?> anywhere in your theme or use a shortcode [social_stickers].

== Frequently Asked Questions ==

None at the moment.

== Screenshots ==

1. Plugin in widget mode (default theme - small icon size)
2. Plugin in widget mode (picons social theme - medium icon size)
3. Plugin settings page (simple plugin mode settings)
4. Plugin settings page (advanced plugin mode settings)

== Changelog ==

= 2.1 =
* [New] Added the ability to output stickers in rows and columns
* [New] Added TwitchTV to social networks list.

= 2.0.2 =
* [Fix] Fixed the height and width attributes of images to mark HTML specifications.

= 2.0.1 =
* [Fix] Fixed a small bug which caused "There are currently no active social stickers" to show, even though profiles were entered.

= 2.0 =
* [New] Add custom social networks
* [New] You can now fully customize stickers output via HTML
* [New] Added the ability to output number of Twitter followers
* [New] Added the ability to output number of Facebook page likes
* [New] User interface, now tabbed
* [New] Added new theme to default set, Media Baloons by Jack Cai
* [Fix] Moved some javascript to admin only area (sortable.js)
* [Fix] Fixed Picasa error (not showing in networks)
* [Deprecated] Removed the field prefix from options (deprecated because of new function - custom HTML output)
* [Deprecated] Removed the field suffix from options (deprecated because of new function - custom HTML output)
* Added Spotify to social networks.

= 1.5.5 =
* Added Academia.edu
* Added Anobii
* Added alt attribute to stickers

= 1.5.4 =
* Added Tout
* Added Mixcloud
* Added Stackoverflow
* Changed Myspace icon
* Changed Posterous icon.

= 1.5.3 =
* Added Lovelybook
* Added Bloglovin
* Added Blogconnect.

= 1.5.2 =
* Added Ravelry.

= 1.5.1 =
* Added Snapjoy
* Added App.net.

= 1.5 =
* Added Coderwall
* Added RSS
* Added 500px
* Changed Goodreads icon to official Dan Leech set
* Added email.

= 1.4 =
* Added Instagram
* Added Instagram icon to the default theme.

= 1.3 =
* Added the option to change profile URLs.

= 1.2 =
* Added Goodreads
* Changed Xing and Google+ URLs to official links
* Added Xing to picons theme (thanks to Joachim)
* Added the options to open links in new tab.

= 1.1 =
* [Fix] Fixed a bug that caused stickers to replicate when some actions were triggered.

= 1.0 =
* Initial version.

== Upgrade Notice ==

= 2.0 =
This upgrade deprecates two fields in previous versions, the prefix and suffix before the Social Stickers output. Use custom HTML instead in the advanced view.

= 1.1 =
This upgrade fixes a bug that could duplicate your social entries. Please delete all the data, disable the plugin and reactivate it.

== Making your own theme ==

To make your own theme, create a folder in the themes/ folder and copy the png (only this format is supported) files in it. Rename them accordingly (remove the spaces and use lowercase letters only, example: facebook is facebook.png, Last Fm is lastfm.png etc.).

Then create a file theme.txt in the same folder and put the following text in it (change the name, author, webpage and description accordingly).

`Name: Simple Icons (default)
Author: Dan Leech
Webpage: http://demos.danleech.com/simpleicons/
Description: The default theme of Social Stickers.`

The file must have all the text listed above and in the exact same order. You must create the file otherwise the theme won't be recognized.

== Custom HTML output ==

You can fully customize your HTML output via variables. Some variables can only be used inside a feed loop, others anywhere. Before you output your stickers, you must write the following:

`{$stickers_start}
// Your other data here ...
{$stickers_end}`

Once inside a loop, you can use any of the following variables:

`{$sticker_img_16} - output sticker image, width 16px
{$sticker_img_32} - output sticker image, width 32px
{$sticker_img_64} - output sticker image, width 64px
{$sticker_img_128} - output sticker image, width 128px
{$sticker_name} - output sticker name
{$sticker_url} - output sticker profile URL`

Outside a loop you can use the following:

`{$stickers} - output all the images of your social networks using settings in the general tab
{$facebook_likes} - output the number of Facebook likes on your page (you must set the Facebook variables first)
{$facebook_talking_about} - output how many people are talking about your Facebook page (you must set the Facebook variables first)
{$twitter_followers} - output the number of Twitter followers you have (you must set the Twitter variables first)
{$twitter_following} - output the number of Twitter users following you (you must set the Twitter variables first)`

For example if you want to output stickers in size 32px and add some Facebook and Twitter data in the end you would enter the following in the custom HTML box:

`<p>Add me on any of the social networks!</p>

<p>
{$stickers_start}
	<a href="{$sticker_url}" target="_blank" title="{$sticker_name}">{$sticker_img_32}</a>
{$stickers_end}
</p>

<p>We have {$facebook_likes} likes on Facebook and {$facebook_talking_about} people talking about us. We also have {$twitter_followers} followers on Twitter!`

And this is basically it.

== Packed themes ==

Social Stickers comes packed (originally) with the following themes:

* Simple Icons by Dan Leech
* Circle Media by Umar Irshad
* Picons Social by Morphix Studio
* Simplito by DesignDeck
* Somicro by Veodesign
* Media Baloons by Jack Cai

All themes, their authors and links are respectfully credited in the plugin itself when you select a theme. All themes are either under the GPLv2 license or are allowed to be freely distributed.

== Author ==

The author of this plugin is Bostjan Cigan, visit the [homepage](http://bostjan.gets-it.net "homepage").

== Homepage ==

Visit the [homepage](http://wpplugz.is-leet.com "homepage of social stickers") of the plugin.

== Donators ==

Thank you to the following people who have donated so far:

* Pastrana Gomez
* Ollie Smith
* Sue Parker
* Jay
* Paal
* David Chartier
