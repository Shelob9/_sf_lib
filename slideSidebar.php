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
		$('#right-menu').toggle( 
			function() {
				$('#content').removeClass('large-12').addClass('large-9 push-3');
				$('#secondary').show();
				$('#right-menu').html('Close');
				
			}, 
			function() {
				$('#content').removeClass('large-9').addClass('large-12');
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
			<a id="right-menu" class="button small radius success" href="#" title="Click To Open Menu">Menu</a> 
		</div>
	';
    echo $out;
}
add_action('before', '_sf_slideSidebar_triggers');
endif; //! _sf_slideSidebar_triggers exists

if (! function_exists('_sf_sidebar_hider') ) :
function _sf_sidebar_hider() { ?>
	<style>
		#content{
			z-index: 5;	
		}
	</style>
<?php
}
add_action('wp_head', '_sf_sidebar_hider');
endif;
?>