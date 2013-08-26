<?php

require_once WPJJNGGJ_SLIDER_PLUGIN_DIR . '/includes/functions.php';
require_once WPJJNGGJ_SLIDER_PLUGIN_DIR . '/includes/jj_ngg_jquery_slider.php';

add_action( 'widgets_init', create_function('', 'return register_widget("JJ_NGG_JQuery_Slider");') );
add_shortcode( 'jj-ngg-jquery-slider', 'jj_ngg_jquery_slider_shortcode_handler' ); 
  
if ( !is_admin() )
{
  add_action( 'init', 'WPJJNGGJ_SLIDER_enqueue_scripts' );
  add_action( 'init', 'WPJJNGGJ_SLIDER_enqueue_styles' );
}

?>