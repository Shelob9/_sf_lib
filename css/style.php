<?php 
/**
*
* This file adds style to the header using returned values from theme customizer. 
* Since 1.0.5
*
**/
add_action('wp_head','_sf_custom_style');
global $options;

function _sf_custom_style() {

	echo '
		#full-slide {background-image:url('.$options['header_img'].');}
	';

echo '</style>';

}


 
