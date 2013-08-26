<?php
/*
Plugin Name: JJ NextGen JQuery Slider
Description: Allows you to pick a gallery from the 'NextGen Gallery' plugin to use as a 'JQuery Nivo Slider'.
Author: JJ Coder
Version: 1.3.9
*/

if ( ! defined( 'WPJJNGGJ_SLIDER_PLUGIN_BASENAME' ) )
	define( 'WPJJNGGJ_SLIDER_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

if ( ! defined( 'WPJJNGGJ_SLIDER_PLUGIN_NAME' ) )
	define( 'WPJJNGGJ_SLIDER_PLUGIN_NAME', trim( dirname( WPJJNGGJ_SLIDER_PLUGIN_BASENAME ), '/' ) );

if ( ! defined( 'WPJJNGGJ_SLIDER_PLUGIN_DIR' ) )
	define( 'WPJJNGGJ_SLIDER_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . WPJJNGGJ_SLIDER_PLUGIN_NAME );

function WPJJNGGJ_SLIDER_set_plugin_meta($links, $file)
{
  $plugin = WPJJNGGJ_SLIDER_PLUGIN_BASENAME;
  if ($file == $plugin)
  {
    $links[] = '<a href="http://wordpress.org/extend/plugins/jj-nextgen-jquery-slider/">' . 'Visit plugin site' . '</a>';
    $links[] = '<a href="http://www.redcross.org.nz/donate">' . 'Donate to Christchurch Quake' . '</a>';
  }
  return $links;
}
if( is_admin() )
{
  add_filter( 'plugin_row_meta', 'WPJJNGGJ_SLIDER_set_plugin_meta', 10, 2 );
}

require_once WPJJNGGJ_SLIDER_PLUGIN_DIR . '/includes/application.php';