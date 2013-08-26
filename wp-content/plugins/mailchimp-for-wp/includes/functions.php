<?php

if(!function_exists('mc4wp_show_checkbox')) {
	function mc4wp_show_checkbox()
	{
		$mc4wp = MC4WP_Lite::get_instance();

		if($mc4wp->checkbox) {
			$mc4wp->checkbox->output_checkbox();
		}
	}
}


// end of file