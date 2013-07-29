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
function _sf_js_init_slideSidebar_code($sideBars = 1, $side = 'left') {


	if ( $menus == 2 ) {
		
	}
	elseif ($menus == 1 && $side == 'right' ) {
		
	}
	else {
		$out = "
			$('#button').toggle( 
				function() {
					$('#right').animate({ left: 250 }, 'slow', function() {
						$('#button').html('Close');
					});
				}, 
				function() {
					$('#right').animate({ left: 0 }, 'slow', function() {
						$('#button').html('Menu');
					});
				}
			);
		";
	}
    echo $out;
}
endif; //! _sf_js_init_slideSidebar_code exists

?>