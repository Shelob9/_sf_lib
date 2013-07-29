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
function _sf_js_init_slideSidebar_code($sidebars = 1, $side = 'left') {


	if ( $sidebars == 2 ) {
		
	}
	elseif ($sidebars == 1 && $side == 'right' ) {
		
	}
	else {
		$out = "
			$('#right-menu').toggle( 
				function() {
					$('#content').animate({ right: 250 }, 'slow', function() {
						$('#right-menu').html('Close');
					});
				}, 
				function() {
					$('#content').animate({ right: 0 }, 'slow', function() {
						$('#right-menu').html('Menu');
					});
				}
			);
		";
	}
    echo $out;
}
endif; //! _sf_js_init_slideSidebar_code exists

if (! function_exists('_sf_slideSidebar_triggers') ) :
function _sf_slideSidebar_triggers($sidebars = 1, $side = 'left') {
	if ( $sidebars == 2 ) {
		$out = '
			<div class="slideSidebar-trigger">
				<a id="left-menu" href="#" title="Click To Open Left Side Menu">Menu</a> 
			</div>
			<div class="slideSidebar-trigger">
				<a id="right-menu" href="#" title="Click To Open Right Side Menu">Menu</a>
			</div>
		';
	}
	else  {
		$out = '
			<div class="slideSidebar-trigger">
				<a id="right-menu" href="#" title="Click To Open Menu">Menu</a> 
			</div>
		';
	}
    echo $out;
}
add_action('before', '_sf_slideSidebar_triggers');
endif; //! _sf_slideSidebar_triggers exists

if (! function_exists('_sf_sidebar_hider') ) :
function _sf_sidebar_hider() { ?>
	<style>
		#content{
			background-color: #fff;
			z-index: 5;
			
			
			
		}
	</style>
<?php
}
add_action('wp_head', '_sf_sidebar_hider');
endif;
?>