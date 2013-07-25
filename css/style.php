<?php 
/**
*
* This file adds style to the header using returned values from theme customizer. 
* Since 1.0.5
*
**/

global $options;
add_action('wp_head','_sf_custom_style');

function _sf_custom_style($options) {
echo '<style>';


echo '</style>';
}

 
