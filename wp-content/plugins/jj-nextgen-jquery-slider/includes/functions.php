<?php

function WPJJNGGJ_SLIDER_plugin_url( $path = '' ) 
{
  return plugins_url( $path, WPJJNGGJ_SLIDER_PLUGIN_BASENAME );
}

function WPJJNGGJ_SLIDER_enqueue_scripts() 
{
  if( !is_admin() ) 
  {
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'jquery-niveo-slider', WPJJNGGJ_SLIDER_plugin_url( 'script/jquery.nivo.slider.pack.js' ), array('jquery'), '2.4', false );    
    wp_enqueue_script( 'jquery-shuffle', WPJJNGGJ_SLIDER_plugin_url( 'script/jquery.jj_ngg_shuffle.js' ), array('jquery'), '', false );    
    wp_enqueue_script( 'jquery-jjnggutils', WPJJNGGJ_SLIDER_plugin_url( 'script/jjnggutils.js' ), array('jquery'), '', false );  
  }
}

function WPJJNGGJ_SLIDER_enqueue_styles() 
{
  if( !is_admin() ) 
  {  
    wp_enqueue_style( 'jquery-plugins-slider-style', WPJJNGGJ_SLIDER_plugin_url( 'stylesheets/nivo-slider.css' ), array(), '', 'all' );
  }
}

function WPJJNGGJ_SLIDER_use_default($instance, $key)
{
  return !array_key_exists($key, $instance) || trim($instance[$key]) == '';
}

function jj_ngg_jquery_slider_shortcode_handler($atts) 
{      
  $instance = array();
  foreach($atts as $att => $val)
  {
    $instance[$att] = $val;
  }
  
  // Set defaults
  if(WPJJNGGJ_SLIDER_use_default($instance, 'html_id')) { $instance['html_id'] = 'slider'; } 
  if(WPJJNGGJ_SLIDER_use_default($instance, 'order')) { $instance['order'] = 'random'; } 
  if(WPJJNGGJ_SLIDER_use_default($instance, 'center')) { $instance['center'] = '1'; } 
  $instance['shortcode'] = '1';
  
  ob_start();
  the_widget("JJ_NGG_JQuery_Slider", $instance, array());
  $output = ob_get_contents();
  ob_end_clean();
  return $output;    
}

?>