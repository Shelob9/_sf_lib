<?php
/**
* Functions for using reveal to do various things.
*
* @package _sf
* @since 1.1.0
*/

/**
* Add Js for Creating Post In A Reveal Via AJAX
* @since 1.0
* http://stackoverflow.com/questions/17271026/foundation-4-reveal-and-wp-ajax-previous-next-modal-window-from-open-modal-wind
*/

function _sf_post_revealJS() {
wp_enqueue_script('post-reveal', get_stylesheet_directory_uri().'/lib/js/post-reveal.js', array('jquery', 'foundation-js'), false, false);
wp_localize_script( 'post-reveal', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}
add_action('wp_enqueue_scripts', '_sf_post_revealJS');


/**
* Permalink for Reveal (or not)
*
* @since 1.1.0
* @params $reveal (boolean) (optional) (default= false) If true will return the permalink with attributes to load post in a Reveal modal via ajax.
*/
//todo set $reveal via an option

function _sf_the_permalink($reveal = false) { 
	if ($reveal = true) {
	?>
	<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', '_sf' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark" data-id="<?php the_ID(); ?>" class="reveal"><?php the_title(); ?></a>
	<?php }
	else { ?>
	<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', '_sf' ), the_title_attribute( 'echo=0' ) ) ); ?>" "rel="bookmark"><?php the_title(); ?></a>
	<?php }
}


/**
* Function to call the content loaded for logged-in and anonymous users
* @since 1.0
*/

add_action( 'wp_ajax_load-content', '_sf_load_ajax_content');
add_action( 'wp_ajax_nopriv_load-content', '_sf_load_ajax_content' );
    
function _sf_load_ajax_content ( $post_id ) {
	$post_id = $_POST[ 'post_id' ];
	$response = get_the_title($post_id);
	echo $response;
 }