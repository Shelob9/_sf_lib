<?php
/**
* Functions for using reveal to do various things.
*
* @package _sf
* @since 1.1.0
*/


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
	<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', '_sf' ), the_title_attribute( 'echo=0' ) ) ); ?>" data-reveal-id="<?php the_id(); ?>" data-reveal-ajax="true" "rel="bookmark"><?php the_title(); ?></a>
	<?php }
	else { ?>
	<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', '_sf' ), the_title_attribute( 'echo=0' ) ) ); ?>" "rel="bookmark"><?php the_title(); ?></a>
	<?php }
}
