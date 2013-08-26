<?php

	/* 
		Plugin Name: Social Stickers
		Plugin URI: http://wpplugz.is-leet.com
		Description: A simple plugin that shows the social networks you use.
		Version: 2.1
		Author: Bostjan Cigan
		Author URI: http://bostjan.gets-it.net
		License: GPL v2
	*/ 
	
	// 2.1 TODO
	// Add column output
	// Add Twitch TV - DONE
	
	// Add Twitter libraries
	if(!class_exists('tmhOAuth')) {
		require 'lib/tmhOAuth.php';
	}
	if(!class_exists('tmhUtilities')) {
		require 'lib/tmhUtilities.php';
	}
	
	// Wordpress formalities here ...
	// Lets register things
	register_activation_hook(__FILE__, 'social_stickers_install');
	add_action('admin_menu', 'social_stickers_admin_menu_create');
	add_action('widgets_init', create_function('', 'return register_widget("social_stickers_widget");')); // Register the widget
	add_action('admin_init', 'social_stickers_sortable_script'); // Add javascript for sorting but only in admin area
	add_shortcode('social_stickers', 'social_stickers_shortcode_handler');

	function social_stickers_sortable_script() {
		wp_enqueue_script('social-stickers-sortable-script', plugin_dir_url(__FILE__).'js/sortable.js', array("jquery", "jquery-ui-core", "jquery-ui-sortable"));
	}
	
	// Create the admin menu
	function social_stickers_admin_menu_create() {
		$settings_page = add_options_page('Social Stickers Settings', 'Social Stickers', 'administrator', __FILE__, 'social_stickers_settings');
		add_action("load-{$settings_page}", 'social_stickers_load_settings_page'); // Add action for tab submitting and saving
	}

	// The messages array - containing error messages used in saving
	global $social_stickers_error_msg;
	$social_stickers_error_msg = array(
		0 => 'The Social Sticker you are trying to add already exists.',
		1 => 'You forgot to fill out all the neccessary data.',
		2 => 'The Social Sticker you are trying to add contains invalid characters (spaces, lowercase letters only).',
		3 => 'Custom Social Sticker added.',
		4 => 'Custom Social Sticker was deleted.',
		5 => 'Custom Social Sticker could not be deleted or does not exist.',
		6 => 'Stickers usernames were updated.',
		7 => 'All Social Stickeres data was deleted. You can now deactivate the plugin.',
		8 => 'All Social Stickers data was deleted. If you are seeing this message by error, reactivate the plugin.',
		9 => 'General settings were saved.',
		10 => 'Advanced settings were saved.'
	);
	
	// The installation array, also used for the update procedure
	global $social_stickers_options_install;
	$social_stickers_options_install = array(
		'version' => '2.1',
		'powered_by_msg' => false,
		'mode' => 0, // Mode of output - 0 is 32x32 icon, 1 is 64x64 icon, 2 is 128x128 icon, 3 is small icon and text
		'theme' => 'default',
		'show_edit_url' => false,
		'link_new' => false,
		'theme_stickers_order' => array( // Each theme has its own order of sticker positions
			"default" => NULL
		),
		'twitter_data' => array(
			'username' => '',
			'consumer_key' => '',
			'consumer_secret' => '',
			'user_token' => '',
			'user_secret' => '',
			'data' => NULL
		),
		'facebook_data' => array(
			'page' => '',
			'data' => NULL
		),
		'refresh_time' => 5,
		'advanced_view' => false,
		'last_access' => time(),
		'custom_html' => false,
		'column_output' => array(
			'active' => false,
			'width' => 3
		),
		'custom_html_text' => '<p>Add me on any of the social networks!</p>

<p>
{$stickers_start}
	<a href="{$sticker_url}" target="_blank" title="{$sticker_name}">{$sticker_img_32}</a>
{$stickers_end}
</p>

<p>We have {$facebook_likes} likes on Facebook and {$facebook_talking_about} people talking about us. We also have {$twitter_followers} followers on Twitter!',
		'stickers' => array(
			'500px' => array(
				'url' => 'http://500px.com/[:username]',
				'name' => '500px',
				'custom' => false,
				'username' => ''
			),
			'aboutme' => array(
				'url' => 'http://about.me/[:username]',
				'name' => 'About Me',
				'custom' => false,
				'username' => ''
			),
			'academia' => array(
				'url' => '[:username]',
				'name' => 'Academia',
				'custom' => false,
				'username' => ''
			),
			'aim' => array(
				'url' => 'aim:goim?screenname=[:username]',
				'name' => 'AIM',
				'custom' => false,
				'username' => ''
			),
			'anobii' => array(
				'url' => 'http://www.anobii.com/[:username]/books',
				'name' => 'Anobii',
				'custom' => false,
				'username' => ''
			),				
			'appnet' => array(
				'url' => 'https://alpha.app.net/[:username]',
				'name' => 'app.net',
				'custom' => false,
				'username' => ''
			),
			'behance' => array(
				'url' => 'http://behance.net/[:username]',
				'name' => 'Behance',
				'custom' => false,
				'username' => ''
			),
			'bebo' => array(
				'url' => 'http://bebo.com/[:username]',
				'name' => 'Bebo',
				'custom' => false,
				'username' => ''
			),
			'blogconnect' => array(
				'url' => 'http://blog-connect.com/a?id=[:username]',
				'name' => 'Blogconnect',
				'custom' => false,
				'username' => ''
			),
			'blogger' => array(
				'url' => 'http://[:username].blogspot.com/',
				'name' => 'Blogger',
				'custom' => false,
				'username' => ''
			),
			'bloglovin' => array(
					'url' => 'http://www.bloglovin.com/en/blog/[:username]',
					'name' => 'Bloglovin',
					'custom' => false,
					'username' => ''
			),
			'coderwall' => array(
				'url' => 'http://coderwall.com/[:username]',
				'name' => 'Coderwall',
				'custom' => false,
				'username' => ''
			),
			'dailybooth' => array(
				'url' => 'http://dailybooth.com/[:username]',
				'name' => 'Dailybooth',
				'custom' => false,
				'username' => ''
			),
			'delicious' => array(
				'url' => 'http://delicious.com/[:username]',
				'name' => 'Delicious',
				'custom' => false,
				'username' => ''
			),
			'designfloat' => array(
				'url' => 'http://www.designfloat.com/user/profile/[:username]',
				'name' => 'Designfloat',
				'custom' => false,
				'username' => ''
			),
			'deviantart' => array(
				'url' => 'http://[:username].deviantart.com',
				'name' => 'Deviantart',
				'custom' => false,
				'username' => ''
			),
			'digg' => array(
				'url' => 'http://digg.com/[:username]',
				'name' => 'Digg',
				'custom' => false,
				'username' => ''
			),
			'dribble' => array(
				'url' => 'http://dribbble.com/[:username]',
				'name' => 'Dribble',
				'custom' => false,
				'username' => ''
			),
			'ebay' => array(
				'url' => 'http://myworld.ebay.com/[:username]',
				'name' => 'Ebay',
				'custom' => false,
				'username' => ''
			),
			'email' => array(
				'url' => 'mailto:[:username]',
				'name' => 'Email',
				'custom' => false,
				'username' => ''				
			),
			'exfm' => array(
				'url' => 'http://ex.fm/[:username]',
				'name' => 'exfm',
				'custom' => false,
				'username' => ''
			),
			'etsy' => array(
				'url' => 'http://[:username].etsy.com',
				'name' => 'Etsy',
				'custom' => false,
				'username' => ''
			),
			'flickr' => array(
				'url' => 'http://www.flickr.com/people/[:username]',
				'name' => 'Flickr',
				'custom' => false,
				'username' => ''
			),
			'facebook' => array(
				'url' => 'http://facebook.com/[:username]',
				'name' => 'Facebook',
				'custom' => false,
				'username' => ''
			),
			'forrst' => array(
				'url' => 'http://forrst.me/[:username]',
				'name' => 'Forrst',
				'custom' => false,
				'username' => ''
			),
			'formspring' => array(
				'url' => 'http://www.formspring.me/[:username]',
				'name' => 'Formspring',
				'custom' => false,
				'username' => ''
			),
			'foursquare' => array(
				'url' => 'https://foursquare.com/[:username]',
				'name' => 'Foursquare',
				'custom' => false,
				'username' => ''
			),
			'github' => array(
				'url' => 'http://github.com/[:username]',
				'name' => 'Github',
				'custom' => false,
				'username' => ''
			),
			'geeklist' => array(
				'url' => 'http://geekli.st/[:username]',
				'name' => 'Geeklist',
				'custom' => false,
				'username' => ''
			),
			'googleplus' => array(
				'url' => 'http://plus.google.com/[:username]',
				'name' => 'Google+',
				'custom' => false,
				'username' => ''
			),
			'goodreads' => array(
				'url' => 'http://www.goodreads.com/[:username]',
				'name' => 'Goodreads',
				'custom' => false,
				'username' => ''
			),
			'gravatar' => array(
				'url' => 'http://gravatar.com/[:username]',
				'name' => 'Gravatar',
				'custom' => false,
				'username' => ''
			),
			'grooveshark' => array(
				'url' => 'http://grooveshark.com/[:username]',
				'name' => 'Grooveshark',
				'custom' => false,
				'username' => ''
			),
			'hi5' => array(
				'url' => 'http://www.hi5.com/[:username]',
				'name' => 'Hi5',
				'custom' => false,
				'username' => ''
			),				
			'imdb' => array(
				'url' => 'http://imdb.com/user/[:username]',
				'name' => 'IMDB',
				'custom' => false,
				'username' => ''
			),
			'instagram' => array(
				'url' => 'http://instagram.com/[:username]',
				'name' => 'Instagram',
				'custom' => false,
				'username' => ''
			),
			'lastfm' => array(
				'url' => 'http://last.fm/user/[:username]',
				'name' => 'LastFM',
				'custom' => false,
				'username' => ''
			),
			'livejournal' => array(
				'url' => 'http://[:username].livejournal.com/',
				'name' => 'Livejournal',
				'custom' => false,
				'username' => ''
			),
			'linkedin' => array(
				'url' => 'http://linkedin.com/in/[:username]',
				'name' => 'Linkedin',
				'custom' => false,
				'username' => ''
			),
			'lovelybooks' => array(
				'url' => 'http://www.lovelybooks.de/mitglied/[:username]',
				'name' => 'Lovelybooks',
				'custom' => false,
				'username' => ''
			),
			'mixcloud' => array(
				'url' => 'http://www.mixcloud.com/[:username]',
				'name' => 'Mixcloud',
				'custom' => false,
				'username' => ''
			),
			'myspace' => array(
				'url' => 'http://myspace.com/[:username]',
				'name' => 'Myspace',
				'custom' => false,
				'username' => ''
			),
			'newsvine' => array(
				'url' => 'http://[:username].newsvine.com/',
				'name' => 'Newsvine',
				'custom' => false,
				'username' => ''
			),
			'orkut' => array(
				'url' => 'http://www.orkut.com/Profile.aspx?uid=[:username]',
				'name' => 'Orkut',
				'custom' => false,
				'username' => ''
			),
			'picasa' => array(
				'url' => 'http://picasaweb.google.com/[:username]',
				'name' => 'Picasa',
				'custom' => false,
				'username' => ''
			),
			'pinboard' => array(
				'url' => 'https://pinboard.in/u:[:username]',
				'name' => 'Pinboard',
				'custom' => false,
				'username' => ''
			),
			'pinterest' => array(
				'url' => 'http://pinterest.com/[:username]',
				'name' => 'Pinterest',
				'custom' => false,
				'username' => ''
			),
			'posterous' => array(
				'url' => 'http://[:username].posterous.com',
				'name' => 'Posterous',
				'custom' => false,
				'username' => ''
			),
			'ravelry' => array(
				'url' => 'http://www.ravelry.com/people/[:username]',
				'name' => 'Ravelry',
				'custom' => false,
				'username' => ''
			),
			'rss' => array(
				'url' => '[:username]',
				'name' => 'RSS',
				'custom' => false,
				'username' => ''
			),
			'quora' => array(
				'url' => 'http://quora.com/[:username]',
				'name' => 'Quora',
				'custom' => false,
				'username' => ''
			),
			'orkut' => array(
				'url' => 'http://www.orkut.com/Profile.aspx?uid=[:username]',
				'name' => 'Orkut',
				'custom' => false,
				'username' => ''
			),
			'qik' => array(
				'url' => 'http://qik.com/[:username]',
				'name' => 'Qik',
				'custom' => false,
				'username' => ''
			),
			'slashdot' => array(
				'url' => 'http://[:username].slashdot.org',
				'name' => 'Slashdot',
				'custom' => false,
				'username' => ''
			),
			'slideshare' => array(
				'url' => 'http://www.slideshare.net/[:username]',
				'name' => 'Slideshare',
				'custom' => false,
				'username' => ''
			),
			'snapjoy' => array(
				'url' => 'https://[:username].snapjoy.com',
				'name' => 'Snapjoy',
				'custom' => false,
				'username' => ''
			),
			'soundcloud' => array(
				'url' => 'http://soundcloud.com/[:username]',
				'name' => 'Soundcloud',
				'custom' => false,
				'username' => ''
			),
			'skype' => array(
				'url' => 'skype:[:username]?call',
				'name' => 'Skype',
				'custom' => false,
				'username' => ''
			),
			'spotify' => array(
				'url' => 'http://open.spotify.com/user/[:username]',
				'name' => 'Spotify',
				'custom' => false,
				'username' => ''
			),
			'stackoverflow' => array(
				'url' => 'http://stackoverflow.com/users/[:username]',
				'name' => 'Stackoverflow',
				'custom' => false,
				'username' => ''
			),
			'steam' => array(
				'url' => 'http://steamcommunity.com/id/[:username]',
				'name' => 'Steam',
				'custom' => false,
				'username' => ''
			),
			'stumbleupon' => array(
				'url' => 'http://stumbleupon.com/stumbler/[:username]',
				'name' => 'Stumbleupon',
				'custom' => false,
				'username' => ''
			),
			'tumblr' => array(
				'url' => 'http://[:username].tumblr.com',
				'name' => 'Tumblr',
				'custom' => false,
				'username' => ''
			),
			'orkut' => array(
				'url' => 'http://www.orkut.com/Profile.aspx?uid=[:username]',
				'name' => 'Orkut',
				'custom' => false,
				'username' => ''
			),
			'tout' => array(
				'url' => 'http://www.tout.com/u/[:username]',
				'name' => 'Tout',
				'custom' => false,
				'username' => ''
			),
			'twitter' => array(
				'url' => 'http://twitter.com/[:username]',
				'name' => 'Twitter',
				'custom' => false,
				'username' => ''
			),
			'twitchtv' => array(
				'url' => 'http://twitch.tv/[:username]',
				'name' => 'Twitch TV',
				'custom' => false,
				'username' => ''
			),			
			'vimeo' => array(
				'url' => 'http://vimeo.com/[:username]',
				'name' => 'Vimeo',
				'custom' => false,
				'username' => ''
			),
			'youtube' => array(
				'url' => 'http://youtube.com/[:username]',
				'name' => 'Youtube',
				'custom' => false,
				'username' => ''
			),
			'yelp' => array(
				'url' => 'http://[:username].yelp.com',
				'name' => 'Yelp',
				'custom' => false,
				'username' => ''
			),
			'zerply' => array(
				'url' => 'http://zerp.ly/[:username]',
				'name' => 'Zerply',
				'custom' => false,
				'username' => ''
			),
			'zootool' => array(
				'url' => 'http://zootool.com/user/[:username]',
				'name' => 'Zootool',
				'custom' => false,
				'username' => ''
			),
			'xing' => array(
				'url' => 'http://www.xing.com/profile/[:username]',
				'name' => 'Xing',
				'custom' => false,
				'username' => ''
			),
			'wordpress' => array(
				'url' => 'http://[:username].wordpress.com',
				'name' => 'Wordpress',
				'custom' => false,
				'username' => ''
			)
		) // End of stickers
	);	
	
	// Update script ...
	$options = get_option('social_stickers_settings');
	if(is_array($options)) {
		if(((float)$options['version']) < 2.1) {
			update_social_stickers();
		}	
	}

	function social_stickers_install() {
		global $social_stickers_options_install;
		add_option('social_stickers_settings', $social_stickers_options_install);
	}

	// The update procedure in 2.0 is a lot less messier than in previous versions ... :)
	function update_social_stickers() {

		global $social_stickers_options_install;
		$options = get_option('social_stickers_settings');
		
		if(((float) $options['version']) < 2.1) {

			unset($options['prefix']); // These two are deprecated in v2.0
			unset($options['suffix']);
			
			// First lets update the deprecated 'active' to 'custom' label
			foreach($options['stickers'] as $key => $value) {
				if(isset($options['stickers'][$key]['active'])) {
					unset($options['stickers'][$key]['active']);
				}
				if(!isset($options['stickers'][$key]['custom'])) {
					if(array_key_exists($key, $social_stickers_options_install['stickers'])) {
						$options['stickers'][$key]['custom'] = false;
					}
					else {
						$options['stickers'][$key]['custom'] = true;
					}
				}
			}
			
			if(isset($options['stickers']['picassa'])) {
				$options['stickers']['picasa'] = array(
					'url' => 'http://picasaweb.google.com/[:username]',
					'name' => 'Picasa',
					'custom' => false,
					'username' => (isset($options['stickers']['picassa']['username']) && strlen($options['stickers']['picassa']['username']) > 0) ? $options['stickers']['picassa']['username'] : ''
				);
				unset($options['stickers']['picassa']);
			}
			
			// Now lets compare the array in the DB with the fresh array and update values respectively
			foreach($social_stickers_options_install['stickers'] as $key => $value) {
				if(array_key_exists($key, $options['stickers'])) {
					$options['stickers'][$key] = array(
						'url' => (isset($options['stickers'][$key]['url']) && strlen($options['stickers'][$key]['url']) > 0) ? $options['stickers'][$key]['url'] : $social_stickers_options_install['stickers'][$key]['url'],
						'name' => (isset($options['stickers'][$key]['name']) && strlen($options['stickers'][$key]['name']) > 0) ? $options['stickers'][$key]['name'] : $social_stickers_options_install['stickers'][$key]['name'], 
						'custom' => (isset($options['stickers'][$key]['custom'])) ? $options['stickers'][$key]['custom'] : $social_stickers_options_install['stickers'][$key]['custom'],
						'username' =>  (isset($options['stickers'][$key]['username']) && strlen($options['stickers'][$key]['username']) > 0) ? $options['stickers'][$key]['username'] : $social_stickers_options_install['stickers'][$key]['username']
					);					
				}
				else {
					$options['stickers'][$key] = array(
						'url' => $social_stickers_options_install['stickers'][$key]['url'],
						'name' => $social_stickers_options_install['stickers'][$key]['name'],
						'custom' => $social_stickers_options_install['stickers'][$key]['custom'],
						'username' => $social_stickers_options_install['stickers'][$key]['username']
					);
				}
			}
			
			foreach($social_stickers_options_install as $key => $value) {
					if(!isset($options[$key])) {
						$options[$key] = $value;
					}
			}

			$options['version'] = '2.1';
			update_option('social_stickers_settings', $options);
			
		}

	}

	function social_stickers_load_settings_page() {
		if($_POST["social-stickers-settings-submit"] == 'Y') {
			$url_parameters = admin_url('options-general.php?page=social-stickers/social-stickers.php');
			$tab_param = isset($_GET['tab']) ? '&tab='.$_GET['tab'] : '';
			$url_parameters .= $tab_param;
			social_stickers_save_settings($url_parameters);
		}
	}

	function social_stickers_save_settings($url_parameters) {

		global $pagenow;
		$options = get_option('social_stickers_settings');
	
		if($pagenow == 'options-general.php' && $_GET['page'] == 'social-stickers/social-stickers.php' && isset($_POST['social-stickers-settings-submit']) && current_user_can('manage_options')) {
			if(isset($_GET['tab'])) {
				$tab = $_GET['tab'];
			}
			else {
				$tab = 'general';
			}

			switch($tab) { 

				case 'general':

					$options['theme'] = $_POST['theme'];
					$options['powered_by_msg'] = (isset($_POST['powered_by'])) ? true : false;
					$options['link_new'] = (isset($_POST['link_new'])) ? true : false;
					$options['mode'] = intval($_POST['social_stickers_mode']);
					
					$order = $_POST['social_stickers_order'];
					
					$stickers_order = array();
					
					if(isset($order) && strlen($order) > 0) {
						$social_stickers_order_ar = explode("social[]=", $order);
						foreach($social_stickers_order_ar as $sticker) {
							$sticker = str_replace("&", "", $sticker);
							if(strlen($sticker) > 0) {
								array_push($stickers_order, $sticker);
							}
						}	
						$options['theme_stickers_order'][$options['theme']] = $stickers_order;
					}	

					if(!isset($options['theme_stickers_order'][$options['theme']]) || count($options['theme_stickers_order'][$options['theme']]) < 1) {
						foreach($options['stickers'] as $key => $data) {
							if(social_stickers_social_icon_exists($key, $options['theme']) && strlen($options['stickers'][$key]['username']) > 0) {
								array_push($stickers_order, $key);
							}
						}
						$options['theme_stickers_order'][$_POST['theme']] = $stickers_order;
					}
					
					$url_parameters .= "&msg=9";

				break; 
				
				case 'cleanup':
				
					if(isset($_POST['delete_data'])) {
						delete_option('social_stickers_settings');
						$url_parameters .= "&msg=7";
					}
				
				break;
				
				case 'social_networks':

					$add_sticker_array = array(); // This became complicated in 2.0, must keep a separate array for removing and adding new stickers
					$remove_sticker_array = array();
					
					foreach($options['stickers'] as $key => $data) {
						$current_sticker = $_POST[$key];
						if($options['show_edit_url']) {
							$url = $_POST[$key.'_url'];
							if(isset($url) && strlen($url) > 0) {
								$options['stickers'][$key]['url'] = $url;
							}
						}
						if(isset($current_sticker)) {
							if(strlen($current_sticker) > 0) {
								$options['stickers'][$key]['username'] = $current_sticker;
								array_push($add_sticker_array, $key);
							}
							else {
								array_push($remove_sticker_array, $key);
							}
						}
					}

					$current_theme_orders = $options['theme_stickers_order'];
					
					foreach($current_theme_orders as $theme => $order) {
						$current_theme_order = $order;
						$continue = true;
						if(is_array($current_theme_order) && count($current_theme_order) > 0) { // If an order exists, add a sticker if it is not in the order yet
							foreach($add_sticker_array as $key => $sticker) {
								if(!in_array($sticker, $current_theme_order) && social_stickers_social_icon_exists($sticker, $theme)) {
									array_push($current_theme_order, $sticker);
								}
							}
							$current_theme_order = array_values($current_theme_order);
						}
						else { // An order does not exist, use the default one
							$options['theme_stickers_order'][$theme] = $add_sticker_array;
							$continue = false;
						}
						
						if($continue) { // If an order already exists, remove the stickers that the user wants to remove
							foreach($remove_sticker_array as $key => $sticker) {
								$options['stickers'][$sticker]['username'] = "";
								if(in_array($sticker, $current_theme_order)) {
									$del_key = array_search($sticker, $current_theme_order);
									unset($current_theme_order[$del_key]);
								}
							}
							$current_theme_order = array_values($current_theme_order); // Reindex array
							$options['theme_stickers_order'][$theme] = $current_theme_order;
						}
					}
					$url_parameters .= "&msg=6";
					
				break;
				
				case 'custom_sticker':
				
					$sticker_id = $_POST['custom_sticker_id'];
					$sticker_url = $_POST['custom_sticker_url'];
					$sticker_name = $_POST['custom_sticker_name'];
					
					if(strlen($sticker_id) == 0 || strlen($sticker_url) == 0 || strlen($sticker_name) == 0) {
						$url_parameters .= "&msg=1";
						break;
					}
					
					if(!social_stickers_check_custom_id_validity($sticker_id)) {
						$url_parameters .= "&msg=2";
						break;
					}
					
					if(array_key_exists($sticker_id, $options['stickers'])) {
						$url_parameters .= "&msg=0";
						break;
					}
					else {
						$url_parameters .= "&msg=3";
						$options['stickers'][$sticker_id] = array(
							'url' => $sticker_url,
							'name' => $sticker_name,
							'custom' => true,
							'username' => ''
						);
						
					}
					
			
				break;

				case 'switch_simple':
						
					$options['advanced_view'] = false;
					$url_parameters = admin_url('options-general.php?page=social-stickers/social-stickers.php');
						
				break;
						
				case 'switch_advanced':

					$options['advanced_view'] = true;
					$url_parameters = admin_url('options-general.php?page=social-stickers/social-stickers.php');
						
				break;
				
				case 'advanced':

					$custom_html = stripslashes(html_entity_decode($_POST['twitget_custom_output']));
					$options['show_edit_url'] = (isset($_POST['show_edit_url'])) ? true : false;
					$options['custom_html'] = (isset($_POST['custom_html'])) ? true : false;
					$options['twitter_data']['username'] = stripslashes(html_entity_decode($_POST['twitter_username']));
					$options['twitter_data']['consumer_key'] = stripslashes(html_entity_decode($_POST['twitter_consumer_key']));
					$options['twitter_data']['consumer_secret'] = stripslashes(html_entity_decode($_POST['twitter_consumer_secret']));
					$options['twitter_data']['user_token'] = stripslashes(html_entity_decode($_POST['twitter_user_token']));
					$options['twitter_data']['user_secret'] = stripslashes(html_entity_decode($_POST['twitter_user_secret']));
					$options['facebook_data']['page'] = stripslashes(html_entity_decode($_POST['facebook_page']));
					$options['custom_html_text'] = stripslashes(html_entity_decode($_POST['custom_html_text']));
					$options['refresh_time'] = intval($_POST['refresh_time']);
					$options['column_output']['width'] = intval($_POST['rows_count']);
					$options['column_output']['active'] = (isset($_POST['column_output'])) ? true : false;
					
					$url_parameters .= "&msg=10";
					
				break;
				
			}
		}
		
		if(!isset($_POST['delete_data'])) {
			update_option('social_stickers_settings', $options);
		}
		
		wp_redirect($url_parameters);
		exit;
		
	}

	
	// ******************************************************************************************************************************************************************* //
	//														DATA UPDATE FUNCTIONS FOR FACEBOOK, TWITTER																	   //
	// ******************************************************************************************************************************************************************* //

	function social_stickers_facebook_data_update() {
	
		$options = get_option('social_stickers_settings');
	
		if(strlen($options['facebook_data']['page']) > 0) {
			if(time() - $options['last_access'] > $options['refresh_time'] * 60) {
				$fb_url = "http://graph.facebook.com/".$options['facebook_data']['page'];
				$fb_data = file_get_contents($fb_url);
				$data = json_decode($fb_data, true);
				$options['facebook_data']['data'] = $data;
				update_option('social_stickers_settings', $options);
			}
		}
		
	}

	function social_stickers_twitter_data_update() {
	
		$options = get_option('social_stickers_settings');

		if((time() - $options['last_access'] > $options['refresh_time'] * 60) && strlen($options['twitter_data']['consumer_key']) > 0 && strlen($options['twitter_data']['consumer_secret']) > 0
				&& strlen($options['twitter_data']['user_token']) > 0 && strlen($options['twitter_data']['user_secret']) > 0 && strlen($options['twitter_data']['username']) > 0) {
		
			$tmhOAuth = new tmhOAuth(
										array(
											'consumer_key' => $options['twitter_data']['consumer_key'],
											'consumer_secret' => $options['twitter_data']['consumer_secret'],
											'user_token' => $options['twitter_data']['user_token'],
											'user_secret' => $options['twitter_data']['user_secret'],
											'curl_ssl_verifypeer' => false 
										)
									);
	 
			$request_array = array(
				'count' => '1',
				'screen_name' => $options['twitter_data']['username']
			);

			$code = $tmhOAuth->request('GET', $tmhOAuth->url('1.1/statuses/user_timeline'), $request_array);
	 
			$response = $tmhOAuth->response['response'];
			$tweets = json_decode($response, true);
			
			$options['twitter_data']['data'] = $tweets;

			update_option('social_stickers_settings', $options);
				
		}

	}
	
	// Check if custom social sticker name is valid
	// Must contain letters nad numbers only, no spaces and other characters
	function social_stickers_check_custom_id_validity($sticker_name) {

		if(preg_match('/^[a-zA-Z0-9_]+$/', $sticker_name)) {
			return true;
		}
		
		return false;
		
	}

	// Scan themes directory for themes
	function social_stickers_get_themes() {

		$themes = array();
		$theme_path = plugin_dir_path(__FILE__)."/themes";
		foreach(new DirectoryIterator($theme_path) as $file) {
			if($file->isDot()) continue;
			if(is_dir($theme_path.'/'.$file->getFilename())) {
				$theme_data = array(
					'id' => '',
					'name' => '',
					'author' => '',
					'webpage' => '',
					'description' => ''
				);
				$path = plugin_dir_path(__FILE__).'themes/'.$file->getFilename().'/theme.txt';
				if(file_exists($path)) {
					$contents = file_get_contents($path);
					$author = substr($contents, strpos($contents, "Author: ")+8, strpos($contents, "Webpage: ") - strpos($contents, "Author: ") - 10);
					$name = substr($contents, strpos($contents, "Name: ")+6, strpos($contents, "Author: ") - strpos($contents, "Name: ")-6);
					$webpage = substr($contents, strpos($contents, "Webpage: ")+9, strpos($contents, "Description: ") - 9 - strpos($contents, "Webpage: "));
					$description = substr($contents, strpos($contents, "Description: ")+13, strlen($contents)-1);
					$theme_data['author'] = $author;
					$theme_data['name'] = $name;
					$theme_data['webpage'] = $webpage;
					$theme_data['description'] = $description;;
					$theme_data['id'] = $file->getFilename();
					array_push($themes, $theme_data);
				}
			}
		}

		return $themes;
		
	}

	function social_stickers_admin_tabs($current = "general") { 

		$options = get_option('social_stickers_settings');
		$tabs = array('general' => 'General', 'social_networks' => 'Networks', 'cleanup' => 'Cleanup', 'switch_advanced' => 'Switch to advanced mode');
		if($options['advanced_view']) {
			$tabs = array('general' => 'General', 'social_networks' => 'Networks', 'custom_sticker' => 'Custom networks', 'advanced' => 'Advanced', 'cleanup' => 'Cleanup', 'switch_simple' => 'Switch to simple mode');
		}
		$links = array();
		echo '<div id="icon-themes" class="icon32"><br></div>';
		echo '<h2 class="nav-tab-wrapper">';
		foreach($tabs as $tab => $name) {
			$class = ($tab == $current) ? ' nav-tab-active' : '';
			echo "<a class='nav-tab$class' href='?page=social-stickers/social-stickers.php&tab=$tab'>$name</a>";
		}
		echo '</h2>';

	}
		
	// The plugin admin page
	function social_stickers_settings() {


		$options = get_option('social_stickers_settings');
		
		if(!is_array($options)) {
			$message = "You've successfully deleted all Social Stickers data from the database. You can now deactivate the plugin.";	
		}
		
?>

		<div id="icon-options-general" class="icon32"></div><h2>Social Stickers</h2>
		
			<table class="form-table">
			<tr>
				<th scope="row"><img src="<?php echo plugin_dir_url(__FILE__).'images/main.png'; ?>" height="96px" width="96px" /></th>
				<td>
					<p>Thank you for using this plugin. If you like the plugin, you can <a href="http://gum.co/social-stickers" target="_blank">buy me a cup of coffee</a> :)</p>
					<p>You can visit the official website and download more themes @ <a href="http://wpplugz.is-leet.com">wpPlugz</a>.</p>
					<p>This plugin uses icons from <a href="http://www.visualpharm.com/">VisualPharm</a> in the settings pages and the <a href="https://github.com/themattharris/tmhOAuth">tmhOAuth</a> library by Matt Harris.</p>
				</td>
			</tr>
			</table>

<?php

		global $social_stickers_error_msg;
		$message = "";
		if(isset($_GET['msg'])) {
			$message = $social_stickers_error_msg[(int) $_GET['msg']];
		}
		else if(!is_array($options) && !isset($_GET['msg'])) {
			$message = $social_stickers_error_msg[8];		
		}

		if(strlen($message) > 0) {
		
?>

			<div id="message" class="updated">
				<p><strong><?php echo $message; ?></strong></p>
			</div>
			
			
<?php
			
		}

		if(is_array($options)) {
		
?>
				
			<div class="wrap">
		
<?php
			
			if(isset($_GET['tab'])) {
				social_stickers_admin_tabs($_GET['tab']);
			}
			else {
				social_stickers_admin_tabs('general');
			}

?>

		<div id="poststuff">
			<form method="post" action="<?php admin_url('options-general.php?page=social-stickers/social-stickers.php'); ?>">
<?php
				
					if(isset($_GET['tab'])) {
						$tab = $_GET['tab']; 
					}
					else {
						$tab = 'general'; 
					}
?>

				<table class="form-table">
					
<?php					
					switch($tab) {
					
						case 'switch_simple':

?>

							<tr>
								<th scope="row"><img src="<?php echo plugin_dir_url(__FILE__).'images/view.png'; ?>" height="48px" width="48px" /></th>
								<td>
									<p>If you only want to add social networks or you are confused, you can switch back to the simple view by clicking the button bellow.</p>
									<p>You can always switch back.</p>
								</td>
							</tr>
							</table>
							<p class="submit" style="clear: both;">
								<input type="submit" name="Submit"  class="button-primary" value="Switch to simple view" />
								<input type="hidden" name="social-stickers-settings-submit" value="Y" />
							</p>

<?php
							
						break;
						
						case 'switch_advanced':

?>

							<tr>
								<th scope="row"><img src="<?php echo plugin_dir_url(__FILE__).'images/view.png'; ?>" height="48px" width="48px" /></th>
								<td>
									<p>If you want to edit more settings like add custom social networks, Facebook and Twitter data, fully customize your HTML output, then switch to the advanced view.</p>
									<p>You can always switch back.</p>
								</td>
							</tr>
							</table>
							<p class="submit" style="clear: both;">
								<input type="submit" name="Submit"  class="button-primary" value="Switch to advanced view" />
								<input type="hidden" name="social-stickers-settings-submit" value="Y" />
							</p>

<?php					
						
						break;
					
						case 'cleanup':
						
?>

					<tr>
						<th scope="row"><img src="<?php echo plugin_dir_url(__FILE__).'images/clear_data.png'; ?>" height="48px" width="48px" /></th>
						<td>
							<p>If you want to clear all data considering the Social Stickers plugin from the database, you can do that here.</p>
							<p>Bear in mind that this action is unrecoverable.</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="delete_data">Delete all plugin data</label></th>
						<td>
                        	<input name="delete_data" id="delete_data" type="checkbox" />
                            <br /><span class="description">If you plan on deleting this plugin, mark this checkbox to delete all the data from the database.</span>
                        </td>
					</tr>
					</table>
					<p class="submit" style="clear: both;">
						<input type="submit" name="Submit"  class="button-primary" value="Delete all data" />
						<input type="hidden" name="social-stickers-settings-submit" value="Y" />
					</p>

<?php
						
						break;
					
						case 'advanced':
?>

					<tr>
						<th scope="row"><img src="<?php echo plugin_dir_url(__FILE__).'images/settings.png'; ?>" height="48px" width="48px" /></th>
						<td>
							<p>If you want to add custom text, the number of Twitter followers and Facebook likes in your social stickers output, you can do that here.</p>
							<p>Make sure you enter the usernames and API keys before using the custom variables.</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="show_edit_url">Edit social network URLs</label></th>
						<td>
							<input name="show_edit_url" id="show_edit_url" type="checkbox" <?php if($options['show_edit_url']) { ?> checked="checked" <?php } ?>/>
							<br /><span class="description">Check this box and update to show fields for updating social network URLs in the Networks tab.</span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="refresh_time">Feed refresh time</label></th>
						<td>
							<input type="text" name="refresh_time" id="refresh_time" size="20" value="<?php echo $options['refresh_time']; ?>" /><br />
							<span class="description">Enter the time (in minutes) your social data from Facebook and Twitter refreshes.</span>
						</td>
					</tr>
					<tr>
					<th scope="row"><label for="twitget_data">Twitter settings</label></th>
					<td>
						<table class="form-table">
						<tr>
							<th scope="row"><label for="twitter_username">Username</label></th>
							<td>
								<input type="text" name="twitter_username" id="twitter_username" size="70" value="<?php echo $options['twitter_data']['username']; ?>" /><br />
								<span class="description">Enter your Twitter username (without the @ character).</span>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="twitter_consumer_key">Consumer key</label></th>
							<td>
								<input type="text" name="twitter_consumer_key" id="twitter_consumer_key" size="70" value="<?php echo $options['twitter_data']['consumer_key']; ?>" /><br />
								<span class="description">Enter your consumer key here.</span>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="twitter_consumer_secret">Consumer secret</label></th>
							<td>
								<input type="text" name="twitter_consumer_secret" id="twitter_consumer_secret" size="70" value="<?php echo $options['twitter_data']['consumer_secret']; ?>" /><br />
								<span class="description">Enter your consumer secret key here.</span>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="twitter_user_token">Access token</label></th>
							<td>
								<input type="text" name="twitter_user_token" id="twitter_user_token" size="70" value="<?php echo $options['twitter_data']['user_token']; ?>" /><br />
								<span class="description">Enter your access token key here.</span>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="twitter_user_secret">Access token secret</label></th>
							<td>
								<input type="text" name="twitter_user_secret" id="twitter_user_secret" size="70" value="<?php echo $options['twitter_data']['user_secret']; ?>" /><br />
								<span class="description">Enter your access token secret key here.</span>
							</td>
						</tr>							
						</table>
						<span class="description">To use Twitter data, you must create an application on Twitter and enter the keys here. Follow the <a href="http://youtu.be/noB3P-K-wb4" target="_blank">tutorial</a> 
						on Youtube if you're lost.</span>	
					</td>
					</tr>
					<tr>
						<th scope="row"><label for="facebook_page">Facebook Page ID</label></th>
						<td>
							<input type="text" name="facebook_page" id="facebook_page" size="40" value="<?php echo $options['facebook_data']['page']; ?>">
							<br />
            				<span class="description">If you want to output the number of likes on your Facebook page, enter the Facebook page name here.</span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="column_output">Use column output</label></th>
						<td>
							<input name="column_output" id="column_output" type="checkbox" <?php if($options['column_output']['active']) { ?> checked="checked" <?php } ?>/>
							<br /><span class="description">Check this box if you want to use a column output for your stickers (specify number of stickers in a row below).</span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="rows_count">Number of stickers in row</label></th>
						<td>
							<input type="text" name="rows_count" id="rows_count" size="5" value="<?php echo $options['column_output']['width']; ?>" /><br />
							<span class="description">Specify number of stickers in row (if using column output).</span>
						</td>
					</tr>							
					<tr>
						<th scope="row"><label for="custom_html">Use custom HTML output</label></th>
						<td>
							<input name="custom_html" id="custom_html" type="checkbox" <?php if($options['custom_html']) { ?> checked="checked" <?php } ?>/>
							<br /><span class="description">Check this box if you want to use custom HTML output.</span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="custom_html_text">Custom HTML output</label></th>
						<td>
		                    <textarea rows="10" cols="100" name="custom_html_text" id="custom_html_text" ><?php echo esc_attr(stripslashes($options['custom_html_text'])); ?></textarea>
                            <br />
							<span class="description">
							<p>You can enter custom HTML in the box above and achieve the output you want.</p>
							<p>When marking the output of stickers in a loop, you must include {$stickers_start} at the start and {$stickers_end} in the end.</p>

							<strong><p>AVAILABLE VARIABLES</p></strong>
							<strong><p>Used inside of loop</p></strong>
							{$sticker_img_16} - output sticker image, width 16px<br />
							{$sticker_img_32} - output sticker image, width 32px<br />
							{$sticker_img_64} - output sticker image, width 64px<br />
							{$sticker_img_128} - output sticker image, width 128px<br />
							{$sticker_name} - output sticker name<br />
							{$sticker_url} - output sticker profile URL<br /><br />
							<strong><p>Used outside or inside of loop</p></strong>
							{$stickers} - output all the images of your social networks using settings in the general tab<br />
							{$facebook_likes} - output the number of Facebook likes on your page (you must set the Facebook variables first)<br />
							{$facebook_talking_about} - output how many people are talking about your Facebook page (you must set the Facebook variables first)<br />
							{$twitter_followers} - output the number of Twitter followers you have (you must set the Twitter variables first)<br />
							{$twitter_following} - output the number of Twitter users following you (you must set the Twitter variables first)
							</span>
                        </td>
					</tr>
					</table>
					<p class="submit" style="clear: both;">
						<input type="submit" name="Submit"  class="button-primary" value="Update Advanced Settings" />
						<input type="hidden" name="social-stickers-settings-submit" value="Y" />
					</p>

<?php					
						
						break;
						
						case 'general' :
							?>
							
	                <input id="social_stickers_order" name="social_stickers_order" type="hidden" value="" />

                    <tr>
						<th scope="row"><label for="theme">Pick your theme</label></th>
						<td>
							<select name="theme" id="theme" onchange="document.forms[0].submit()">
<?php

							$theme_string = "";
							$themes = social_stickers_get_themes();
							foreach($themes as $theme) {
?>
								<option value="<?php echo $theme['id']; ?>" <?php if($theme['id'] == $options['theme']) { ?> selected="selected" <?php } ?>><?php echo esc_attr($theme['name']); ?></option>
<?php 							if($theme['id'] == $options['theme']) {
									$theme_string = $theme_string.$theme['name'].' by <a href="'.$theme['webpage'].'">'.$theme['author'].'</a> - '.$theme['description'];								
								}
							}
?>
							</select>
                            <br /><span class="description"><?php echo sprintf("%s", $theme_string); ?></span> 
                        </td>
					</tr>
					<tr>
						<th scope="row">Social stickers order</th>
						<td>
                        	<?php display_social_stickers(true); ?>
                            <br /><br /><span class="description">Enter your social network usernames <a href="?page=social-stickers/social-stickers.php&tab=social_networks">here</a> and then hold and move around the icons to change the order.</span>
                        </td>
					</tr>
					<tr>
						<th scope="row"><label for="social_stickers_mode">Social stickers size</label></th>
						<td>
							<select name="social_stickers_mode" id="social_stickers_mode">
								<option value="0" <?php if($options['mode'] == 0) { ?> selected <?php } ?>>Small (32px)</option>
								<option value="1" <?php if($options['mode'] == 1) { ?> selected <?php } ?>>Medium (64px)</option>
								<option value="2" <?php if($options['mode'] == 2) { ?> selected <?php } ?>>Large (128px)</option>
								<option value="3" <?php if($options['mode'] == 3) { ?> selected <?php } ?>>Icons and text (16px)</option>
							</select>
                            <br /><span class="description">Pick how the social stickers should be shown.</span>
                        </td>
					</tr>
					<tr>
						<th scope="row"><label for="link_new">Open links in new window</label></th>
						<td>
                        	<input name="link_new" id="link_new" type="checkbox" <?php if($options['link_new']) { ?> checked="checked" <?php } ?>/>
                            <br /><span class="description">Check this to open all social links in new tabs.</span>
                        </td>
					</tr>
					<tr>
						<th scope="row"><label for="powered_by">Show powered by message</label></th>
						<td>
                        	<input name="powered_by" id="powered_by" type="checkbox" <?php if($options['powered_by_msg']) { ?> checked="checked" <?php } ?>/>
                            <br /><span class="description">Show powered by message, if you decide not to show it, please consider a <a href="http://gum.co/social-stickers" target="_blank">donation</a>.</span>
                        </td>
					</tr>
					</table>
					<p class="submit" style="clear: both;">
						<input type="submit" name="Submit"  class="button-primary" value="Update Settings" />
						<input type="hidden" name="social-stickers-settings-submit" value="Y" />
					</p>

<?php

						break; 
						case 'social_networks':

?>

			<tr>
				<th scope="row"><img src="<?php echo plugin_dir_url(__FILE__).'images/stickers.png'; ?>" height="48px" width="48px" /></th>
				<td>
					<p>Here you can enter the usernames for the social networks you want to show on your main page.</p>
					<p>The number of supported networks depends on the theme you are using.</p>
				</td>
			</tr>


<?php
							foreach($options['stickers'] as $name => $data) {
								if(social_stickers_social_icon_exists($name, $options['theme'])) {

?>				

								<tr>
									<th scope="row"><label for="<?php echo $name; ?>"><?php echo $data['name']; ?></label></th>
									<td>
										<input type="text" name="<?php echo $name; ?>" value="<?php echo esc_attr(stripslashes($data['username'])); ?>" id="<?php echo $name; ?>" size="40"/>
										<a href=""><img src=""></a>
										<br />
										<span class="description">Trouble finding your username? Hint: <?php echo str_replace(array('[:', ']'), array('', ''), $data['url']); ?>.</span>
<?php 
					
										if($options['show_edit_url']) { 
										
?>

											<br />
											<input type="text" name="<?php echo $name.'_url'; ?>" value="<?php echo esc_attr(stripslashes($data['url'])); ?>" id="<?php echo $name.'_url'; ?>" size="40"/>
											<br />
											<span class="description">Customize your <?php echo $data['name']; ?> profile URL.</span>
<?php 

										} 
										
?>

									</td>
								</tr>

<?php 

								} 

							}
							
?>
							</table>
							<p class="submit" style="clear: both;">
								<input type="submit" name="Submit"  class="button-primary" value="Update usernames" />
								<input type="hidden" name="social-stickers-settings-submit" value="Y" />
							</p>

<?php
								
						break;

						case 'custom_sticker' :
						
							if(isset($_GET['del'])) {
								social_stickers_delete_custom_sticker($_GET['del']);
								$options = get_option('social_stickers_settings');
							}
						
						?>

					<tr>
						<th scope="row"><img src="<?php echo plugin_dir_url(__FILE__).'images/custom.png'; ?>" height="48px" width="48px" /></th>
						<td>
							<p>You can add a custom network here or delete it. The sticker ID must contain lowercase letters only, and that will also be the name of the image file.</p>
							<p>The sticker URL looks like <span class="description">http://mysocialnetwork.com/[:username]</span> where the <span class="description">[:username]</span>
							will be replaced by the username you enter in the networks tab.</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="custom_sticker_id">Sticker ID</label></th>
						<td>
							<input type="text" name="custom_sticker_id" id="custom_sticker_id" size="40">
							<br />
            				<span class="description">The ID of the sticker (lowercase letters and no spaces), this will also have to be the name of the image you use.</span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="custom_sticker_url">Sticker URL</label></th>
						<td>
							<input type="text" name="custom_sticker_url" id="custom_sticker_url" size="40">
							<br />
            				<span class="description">To make it generic, please add the [:username] instead of your username in the URL.</span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="custom_sticker_name">Sticker name</label></th>
						<td>
							<input type="text" name="custom_sticker_name" id="custom_sticker_name" size="40">
							<br />
            				<span class="description">The name of the social network you are adding.</span>
						</td>
					</tr>
					<tr>
						<th scope="row">Delete custom stickers</th>
						<td>
<?php

							$msg_custom = true;
							foreach($options['stickers'] as $key => $data) {
								if($options['stickers'][$key]['custom']) {
									$msg_custom = false;
									echo $options['stickers'][$key]['name'];
?>

									<a href="?page=social-stickers/social-stickers.php&tab=custom_sticker&del=<?php echo $key; ?>" title="Delete sticker">X</a><br />

									<?php
								}
							}

							if($msg_custom) {
							
?>

								There are currently no custom social networks.

<?php

							}

?>
							
							<br />
            				<span class="description">Here you can delete the custom stickers that you have made.</span>
						</td>
					</tr>
					</table>
					<p class="submit" style="clear: both;">
						<input type="submit" name="Submit"  class="button-primary" value="Update Custom Stickers" />
						<input type="hidden" name="social-stickers-settings-submit" value="Y" />
					</p>

						<?php
						break;
					}

?>					
					
			</form>
			
		</div>

	</div>

<?php

		}
		
	}

	// ******************************************************************************************************************************************************************* //
	//														FUNCTIONS FOR HTML OUTPUT AND STICKER PRESENTATION															   //
	// ******************************************************************************************************************************************************************* //
	
	function social_stickers_admin_show($options) {
		
		$output = "";

		if(isset($options['theme_stickers_order'][$options['theme']]) && is_array($options['theme_stickers_order'][$options['theme']]) && count($options['theme_stickers_order'][$options['theme']]) > 0) {
			$output .= '<div id="sortable">';
			foreach($options['theme_stickers_order'][$options['theme']] as $key => $value) {
				$file = plugin_dir_path(__FILE__).'themes/'.$options['theme'].'/'.$value.'.png';
				$file_url = plugin_dir_url(__FILE__).'themes/'.$options['theme'].'/'.$value.'.png';
				$social_url = str_replace("[:username]", $options['stickers'][$value]['username'], $options['stickers'][$value]['url']);
				if(file_exists($file)) {
					$output .= '<div id="social_'.$value.'" style="margin-left: 3px; float: left;"> <a href="'.$social_url.'" title="'.$options['stickers'][$value]['name'].'"><img src="'.$file_url.'" height="32" width="32" alt="'.$options['stickers'][$value]['name'].'" /></a></div>';
				}
			}
			$output .= "</div>";
		}
		else {
			$output = "There are currently no usernames entered.";
		}
			
		return $output;
		
	}
	
	function social_stickers_custom_html_show($options) {
	
		$custom_html = $options['custom_html_text'];
		$sticker_string = social_stickers_get_substring($custom_html, "{\$stickers_start}", "{\$stickers_end}");
		
		$stickers_whole_string = "";
		
		if(isset($options['theme_stickers_order'][$options['theme']]) && is_array($options['theme_stickers_order'][$options['theme']]) && count($options['theme_stickers_order'][$options['theme']]) > 0 
			&& strlen($sticker_string) > 0) {
			$social_stickers_column_count = 1;
			foreach($options['theme_stickers_order'][$options['theme']] as $key => $value) {
				$file = plugin_dir_path(__FILE__).'themes/'.$options['theme'].'/'.$value.'.png';
				$file_url = plugin_dir_url(__FILE__).'themes/'.$options['theme'].'/'.$value.'.png';
				$sticker_output_tmp = str_replace("{\$sticker_img_16}", '<img src="'.$file_url.'" height="16" width="16" /> ', $sticker_string);
				$sticker_output_tmp = str_replace("{\$sticker_img_32}", '<img src="'.$file_url.'" height="32" width="32" /> ', $sticker_output_tmp);
				$sticker_output_tmp = str_replace("{\$sticker_img_64}", '<img src="'.$file_url.'" height="64" width="64" /> ', $sticker_output_tmp);
				$sticker_output_tmp = str_replace("{\$sticker_img_128}", '<img src="'.$file_url.'" height="128" width="128" /> ', $sticker_output_tmp);
				$sticker_output_tmp = str_replace("{\$sticker_name}", $options['stickers'][$value]['name'], $sticker_output_tmp);
				$sticker_url = str_replace("[:username]", $options['stickers'][$value]['username'], $options['stickers'][$value]['url']);
				$sticker_output_tmp = str_replace("{\$sticker_url}", $sticker_url, $sticker_output_tmp);
				$stickers_whole_string .= $sticker_output_tmp;
				if($options['column_output']['active']) {
					if($social_stickers_column_count % $options['column_output']['width'] == 0) {
						$stickers_whole_string .= "<br />";
					}
					$social_stickers_column_count++;
				}
			}
		}
		else if(strlen($sticker_string) > 0) {
			$social_stickers_column_count = 1;
			foreach($options['stickers'] as $key => $value) {
				$file = plugin_dir_path(__FILE__).'themes/'.$options['theme'].'/'.$key.'.png';
				$file_url = plugin_dir_url(__FILE__).'themes/'.$options['theme'].'/'.$key.'.png';
				if(file_exists($file) && strlen($value['username']) > 0) {
					$sticker_output_tmp = str_replace("{\$sticker_img_16}", '<img src="'.$file_url.'" height="16" width="16" /> ', $sticker_string);
					$sticker_output_tmp = str_replace("{\$sticker_img_32}", '<img src="'.$file_url.'" height="32" width="32" /> ', $sticker_output_tmp);
					$sticker_output_tmp = str_replace("{\$sticker_img_64}", '<img src="'.$file_url.'" height="64" width="64" /> ', $sticker_output_tmp);
					$sticker_output_tmp = str_replace("{\$sticker_img_128}", '<img src="'.$file_url.'" height="128" width="128" /> ', $sticker_output_tmp);
					$sticker_output_tmp = str_replace("{\$sticker_name}", $options['stickers'][$key]['name'], $sticker_output_tmp);
					$sticker_url = str_replace("[:username]", $options['stickers'][$key]['username'], $options['stickers'][$key]['url']);
					$sticker_output_tmp = str_replace("{\$sticker_url}", $sticker_url, $sticker_output_tmp);
					$stickers_whole_string .= $sticker_output_tmp;
					if($options['column_output']['active']) {
						if($social_stickers_column_count % $options['column_output']['width'] == 0) {
							$stickers_whole_string .= "<br />";
						}
						$social_stickers_column_count++;
					}
				}
			}	
		}

		if(strlen($sticker_string) > 0) {
			$sticker_start = "{\$stickers_start}";
			$sticker_end = "{\$stickers_end}";
			$start_pos = strrpos($custom_html, $sticker_start);
			$end_pos = strrpos($custom_html, $sticker_end) + strlen($sticker_end);
			$tag_length = $end_pos - $start_pos + 1;

			$custom_html = substr_replace($custom_html, $stickers_whole_string, $start_pos, $tag_length);
		}
		
		$custom_html = str_replace("{\$facebook_likes}", $options['facebook_data']['data']['likes'], $custom_html);
		$custom_html  = str_replace("{\$facebook_talking_about}", $options['facebook_data']['data']['talking_about_count'], $custom_html);
		$custom_html  = str_replace("{\$twitter_followers}", $options['twitter_data']['data'][0]['user']['followers_count'], $custom_html);
		$custom_html  = str_replace("{\$twitter_following}", $options['twitter_data']['data'][0]['user']['friends_count'], $custom_html);
		$stickers_general = social_stickers_general_html_show($options);
		$custom_html = str_replace("{\$stickers}", $stickers_general, $custom_html);
		
		return $custom_html;
	
	}

	function social_stickers_general_html_show($options) {
	
		$output = "";
		
		$blank = "";
		if($options['link_new']) {
			$blank = ' target="_blank"';
		}
		
		$no_profiles = true;
		
		$img_size = $options['mode'];
		$img_append = "";
		if($img_size == 0) $img_append = ' width="32" height="32" ';
		else if($img_size == 1) $img_append = ' width="64" height="64" ';
		else if($img_size == 2) $img_append = ' width="128" height="128" ';
		else if($img_size == 3) $img_append = ' width="16" height="16" ';

		$social_stickers_column_count = 1;
		if(isset($options['theme_stickers_order'][$options['theme']]) && is_array($options['theme_stickers_order'][$options['theme']]) 
			&& count($options['theme_stickers_order'][$options['theme']]) > 0) {
			foreach($options['theme_stickers_order'][$options['theme']] as $key => $value) {
				$file = plugin_dir_path(__FILE__).'themes/'.$options['theme'].'/'.$value.'.png';
				$file_url = plugin_dir_url(__FILE__).'themes/'.$options['theme'].'/'.$value.'.png';
				$url = str_replace("[:username]", $options['stickers'][$value]['username'], $options['stickers'][$value]['url']);
				$name = $options['stickers'][$value]['name'];
				$no_profiles = false;
				if($img_size == 3) {
					$output .= '<img src="'.$file_url.'" '.$img_append.'/> <a href="'.$url.'"'.$blank.' title="'.$name.'">'.$name.'</a><br />';
				}
				else {
					$output .= '<a href="'.$url.'"'.$blank.' title="'.$name.'"><img src="'.$file_url.'" '.$img_append.'/></a> ';
				}
				if($options['column_output']['active']) {
					if($social_stickers_column_count % $options['column_output']['width'] == 0) {
						$output .= "<br />";
					}
					$social_stickers_column_count++;
				}
			}
		}
		else {
			$social_stickers_column_count = 1;
			foreach($options['stickers'] as $key => $value) {
				$file = plugin_dir_path(__FILE__).'themes/'.$options['theme'].'/'.$key.'.png';
				$file_url = plugin_dir_url(__FILE__).'themes/'.$options['theme'].'/'.$key.'.png';
				$url = str_replace("[:username]", $options['stickers'][$key]['username'], $options['stickers'][$key]['url']);
				$name = $options['stickers'][$key]['name'];
				$count = 1;
				if(file_exists($file) && strlen($value['username']) > 0) {
					$no_profiles = false;
					if($img_size == 3) {
						$output .= '<img src="'.$file_url.'" '.$img_append.'/> <a href="'.$url.'"'.$blank.' title="'.$name.'">'.$name.'</a><br />';
					}
					else {
						$output .= '<a href="'.$url.'"'.$blank.' title="'.$name.'"><img src="'.$file_url.'" '.$img_append.'/></a> ';
					}
					if($options['column_output']['active']) {
						if($social_stickers_column_count % $options['column_output']['width'] == 0) {
							$output .= "<br />";
						}
						$social_stickers_column_count++;
					}
				}
			}		
		}
		
		if($no_profiles) {
			$output .= "There are currently no active social stickers.";
		}
		
		return $output;
	
	}
	
	// Get substring between two strings
	function social_stickers_get_substring($string, $start, $end) {

		$pos = stripos($string, $start);
		$str = substr($string, $pos);
		$str_two = substr($str, strlen($start));
		$second_pos = stripos($str_two, $end);
		$str_three = substr($str_two, 0, $second_pos);
		$unit = trim($str_three);
		
		return $unit;
	}
	
	function social_stickers_social_icon_exists($file, $theme) {
		$path = plugin_dir_path(__FILE__).'themes/'.$theme.'/'.$file.'.png';
		if(file_exists($path)) {
			return true;	
		}
		return false;
	}

	function display_social_stickers($sortable = false, $shortcode = false) {
	
		// Update Facebook and Twitter data
		social_stickers_facebook_data_update();
		social_stickers_twitter_data_update();

		$options = get_option('social_stickers_settings');
		$output = NULL;
		
		if($sortable) { // If we are in the admin area
			$output = social_stickers_admin_show($options);
		}
		else if(!$sortable && $options['custom_html']) { // If the user has decided to use custom HTML
			$output = social_stickers_custom_html_show($options);
		}
		else if(!$sortable && !$options['custom_html']) { // Default mode
			$output = social_stickers_general_html_show($options);
		}
		
		if($options['powered_by_msg']) {
			$output = $output.'<br /><br />Powered by <a href="http://wpplugz.is-leet.com">wpPlugz</a>.';
		}

		if(!$shortcode) {
			echo $output;
		}
		else {
			return $output;
		}
		
	}
	
	function social_stickers_shortcode_handler($attributes, $content = null) {
		return display_social_stickers(false, true);
	}
	
	/**
	 * social_stickers_delete_custom_sticker
	 * Deletes a custom sticker from the sticker list.
	 *
	 * @param string Sticker ID The Sticker ID we are deleting
	 */	
	function social_stickers_delete_custom_sticker($sticker_id) {

		$options = get_option('social_stickers_settings');

		if(isset($options['stickers'][$sticker_id])) {
			if($options['stickers'][$sticker_id]['custom']) {
				unset($options['stickers'][$sticker_id]);
				foreach($options['theme_stickers_order'] as $key => $value) { // When deleting a custom sticker, make sure it is removed from all the themes it was used in
					if(($value = array_search($sticker_id, $options['theme_stickers_order'][$key])) !== false) {
						unset($options['theme_stickers_order'][$key][$value]);
						$corrected_array = array_values($options['theme_stickers_order'][$key]); // Correct the keys so that they range from 0 - n again with 1 step
						$options['theme_stickers_order'][$key] = $corrected_array;
					}									
				}
			}
		}
		
		update_option('social_stickers_settings', $options);
		
	}

	// Here, the widget code begins
	class social_stickers_widget extends WP_Widget {
		
		function social_stickers_widget() {
			$widget_ops = array('classname' => 'social_stickers_widget', 'description' => 'Display the social networks you use!' );			
			$this->WP_Widget('social_stickers_widget', 'Social Stickers', $widget_ops);
		}
		
		function widget($args, $instance) {
			
			extract($args);
			$title = apply_filters('widget_title', $instance['title']);
			
			echo $before_widget;

			if($title) {
				echo $before_title . $title . $after_title;
			}
			
			// The widget code and the widgeet output
			
			display_social_stickers();
			
			// End of widget output
			
			echo $after_widget;
			
		}
		
		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			return $instance;
		}
		
		function form($instance) {
 
			$title = esc_attr($instance['title']);
		
		?>

			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>">
					<?php _e('Title: '); ?>
				</label> 
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
			</p>

		<?php 

		}

	}
	
?>
