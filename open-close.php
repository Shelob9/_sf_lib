<?php
/**
 * _sf open and close functions. Sets up main content area grid and sidebar
 * I am the Great Opener and Closer!
 *
 * @package _sf
 * since 1.5.1
 */
 
 /**
* Sidebar Position
* Can get the value set with get_theme_mod('_sf_default_sidebar'); but don't do it inside these functions, do it in the templates before calling this function, that way we can set $sidebar manually or with a conditional there if we want ina  child theme or when customizing theme.
*/
//first  a function to translate dropdown (value1, value2, value3) into a left, right, none.

if (! function_exists('_sf_translate_dropdown_value') ):
function _sf_translate_dropdown_value($sidebar = null) {
	/**
	'value1' => 'right',
	'value2' => 'left',
	'value3' => 'none',
	**/
	if ($sidebar == 'value1' || 'value2' || 'value3') :
		if ($sidebar == 'value1') {
			$sidebar = 'right';
		}
		if ($sidebar == 'value2') {
			$sidebar = 'left';
		}
		if ($sidebar == 'value3') {
			($sidebar = 'none');
		}
	endif; //$sidebar = value 1,2,3
	return $sidebar;
	
}
endif; // ! _sf_translate_dropdown_value exists

//functions for opening and closing .primary, .content
if (! function_exists('_sf_open') ) :
function _sf_open($sidebar = 'value1') {

	$sidebar = _sf_translate_dropdown_value($sidebar);
	if ($sidebar == 'none') {
		echo   '<div id="primary" class="content-area row primary-sidebar-none">';
		do_action( 'tha_content_before' );
		echo   '<div id="content" class="site-content large-12 columns" role="main">';
		do_action( 'tha_content_top' );	
	}
	elseif ($sidebar == 'left') {
		echo  '<div id="primary" class="content-area row primary-sidebar-left">';
		do_action( 'tha_content_before' );
		echo  '<div id="content" class="site-content large-9 push-3 columns" role="main">';
		do_action( 'tha_content_top' );
	}
	else {
		echo   '<div id="primary" class="content-area row primary-sidebar-right">';
		do_action( 'tha_content_before' );
		echo   '<div id="content" class="site-content large-9 columns" role="main">';
		do_action( 'tha_content_top' );
	}
	
}
endif; //! _sf_open exists

if (! function_exists('_sf_close') ) :
function _sf_close($sidebar = 'value1', $sidebarName = null) {
	//if ($sidebar == '') {
	//	$sidebar = 'none';
	//}
	$sidebar = _sf_translate_dropdown_value($sidebar);
	if ($sidebar == 'none') {
		do_action( 'tha_content_bottom' );
		echo   '</div><!-- #content -->';
		do_action( 'tha_content_after' );
		echo   '</div><!-- #primary -->';
		echo  get_footer();
	}
	else {
		do_action( 'tha_content_bottom' );
		echo   '</div><!-- #content -->';
		do_action( 'tha_content_after' );
		echo  get_sidebar();
		echo   '</div><!-- #primary -->';
		echo  get_footer();
	}
}
endif; //! _sf_close exists

if (! function_exists('_sf_sidebar_starter') ) :
function _sf_sidebar_starter($sidebar = 'value1') {
	$sidebar = _sf_translate_dropdown_value($sidebar);
	if ($sidebar == 'left') {
		echo '<div id="secondary" class="widget-area large-3 pull-9 columns" role="complementary">';
	}
	elseif ($sidebar == 'none') {
	
	}
	else {	
		echo '<div id="secondary" class="widget-area large-3 columns" role="complementary">';
    }
}
endif; // ! _sf_sidebar_starter exists

