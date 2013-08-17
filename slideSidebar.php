<?php



if (! function_exists('_sf_js_init_slideSidebar') ) :
function _sf_js_init_slideSidebar() { 
	echo "
		<script>
			jQuery(document).ready(function($) {
	";
	_sf_js_init_slideSidebar_code();
	echo "
			}); //end no conflict wrapper
		</script>
	";
}
add_action('wp_footer', '_sf_js_init_slideSidebar');
endif; //! _sf_js_init_slideSidebar

if (! function_exists('_sf_js_init_slideSidebar_code') ) :
function _sf_js_init_slideSidebar_code() {
	$out = "
		$('#secondary').hide();
		$('#menu-toggle').toggle( 
			function() {
				$('#content').removeClass('large-12').addClass('large-9 push-3');
				$('#secondary').show();
				$('#right-menu').html('Close');
				
			}, 
			function() {
				$('#content').removeClass('large-9 push-3').addClass('large-12');
				$('#secondary').hide();
				$('#right-menu').html('Menu');
				
			}
		);
	";
	
    echo $out;
}
endif; //! _sf_js_init_slideSidebar_code exists

if (! function_exists('_sf_slideSidebar_triggers') ) :
function _sf_slideSidebar_triggers() {
	$out = '
		<div class="slideSidebar-trigger">
			<a id="menu-toggle" class="button radius secondary" href="#" title="Click To Open Menu">Menu</a> 
		</div>
	';
    echo $out;
}
add_action('tha_header_before', '_sf_slideSidebar_triggers');
endif; //! _sf_slideSidebar_triggers exists

if (! function_exists('_sf_sidebar_hider') ) :
function _sf_sidebar_hider() { 
	$out = "#content{z-index: 5;}";
	$out .= "#menu-toggle, .slideSidebar-trigger{z-index: 110;}";
	if ( is_user_logged_in() ) { 
		$out .= " #menu-toggle{top:24px;}";
	}
	echo "<style>";
	echo $out;
	echo "</style>";

}
add_action('wp_head', '_sf_sidebar_hider');
endif;
?>