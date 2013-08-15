<?php
/**
 * _sf image stuff
 * I am the Great Doer of Stuff To Images
 *
 * @package _sf
 * since 1.5.1
 */
 
 /**
 * Add all image sizes at once
 */
 if ( function_exists( 'add_image_size' ) ) {
 	//for masonry
	add_image_size( 'masonry-thumb',  235, 180, true );
	//for interchange responsive image thing
	add_image_size( 'fd-lrg', 1024, 99999);
	add_image_size( 'fd-med', 768, 99999);
	add_image_size( 'fd-sm', 320, 9999);
}



/**
* FGt id of attachment by full url
* http://pippinsplugins.com/retrieve-attachment-id-from-image-url/
* as of 1.0.5.1 is no longer used. But I'm keeping it beacuse it is neat.
*/
if (! function_exists('_sf_get_image_id') ) :

function _sf_get_image_id($image_url) {

		global $wpdb;
		$prefix = $wpdb->prefix;
		$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM " . $prefix . "posts" . " WHERE guid='%s';", $image_url )); 
		return $attachment[0]; 
}
endif; // !  _sf_get_image_id exists


/**
* Responsive Image Thing
*
*/
if (! function_exists('_sf_responsive_img') ) :
function _sf_responsive_img($html, $post_id, $post_thumbnail_id, $size, $attr) {
	//make image links
	$attachment_id = $post_thumbnail_id;
	$default = wp_get_attachment_image_src($attachment_id);
	$size = 'fd-sm';
	$small = wp_get_attachment_image_src($attachment_id, $size);
	$size = 'fd-med';
	$med = wp_get_attachment_image_src($attachment_id, $size);
	$size = 'fd-lrg';
	$lrg = wp_get_attachment_image_src($attachment_id, $size);
	//create image tag with queries
	$html = '<img src="'.$default[0].'"';
	$html .= 'data-interchange="['.$default[0].', (default)],';
	$html .= '['.$small[0].', (only screen and (min-width: 320px))],';
	$html .= '['.$med[0].', (only screen and (min-width: 768px))],';
	$html .= '['.$lrg[0].', (only screen and (min-width: 1024px))],';
	$html .='">';
	return $html;
}
add_filter('post_thumbnail_html', '_sf_responsive_img', 5, 5);
endif; // ! _sf_responsive_img exists

/**
* Conditionally Add Slider For Home Page
*/
if (! function_exists('_sf_home_slider') ) :
function _sf_home_slider() { 

	if (! get_theme_mod( '_sf_slider_visibility' ) == '' ) { 
	if ( is_front_page() ) : 
	get_template_part( 'slider' );
	endif;
	}
}
endif; //! _sf_home_slider exists
?>
