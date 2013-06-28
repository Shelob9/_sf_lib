<?php
/**
 * _sf scripts and styles
 * I am the Great Enqueuer!
 *
 * @package _sf
 * since 1.0.5.1
 *
 * Pattern used:
 * 3 functions per plugin. 1) _sf_init_SLUG enqueues scripts, styles. 2) _sf_scripts_SLUG_code contains initialization code. 3) _sf_scripts_SLUG_init calls _sf_scripts_SLUG_init and wraps it in script tags and /or JQuery no conflict wrapper.
 * _sf_scripts_SLUG_init is hooked to wp_footer/header to initialize plugin. _sf_scripts_SLUG_code is called in the ajax Menu function to reinitialize it--with out script tags or no conflict wrappers that _sf_scripts_SLUG_init has--since that would ruin everything.
 * BTW Since everything is conditionally enqueued, initialized and reinitialized based on the theme options, if you modify theme to use these plugins in some other manner, you're probably going to need to modify these conditionals or modify the options. If that is an issue, best bet is probably to over ride this whole system by enqueueing, initializing everything in a more traditional manner in a child theme. Double BTW: If you have a file of same name in childtheme dir/inc it will over ride this one. Also there is a starter child theme available at http://github.com/shelob9/_second-speaker . Bonus points for laughing at the dumb joke I've got going with my naming scheme:) TODO: Add the simpler version of this file to that, but don't include it by default.
 *
 *
 * BTW I, your humble narrator, did it this way, which probably seems silly to you at first, to avoid several things: 1) having individual js files to initialize each plugin and all of the resulting HTTP requests. 2) Having to keep current the copy pasta between the initialization file (or the consolidated one in the last version) and the reinitialization in the ajax menu function. 3) Because once I made it so that I wasn't enqueueing plugins that weren't being used due to options settings that created problems with the consolidated initialization function and reinits in the ajax menu thingy, since the (re)initialization code would have no object if the plugin (that wasn't doing anything) wasn't included. 4) I'm probably going to add more jQuery plugins as time goes on and that's going to make my page load time/ copypasta concerns greater. 
 * TL;DR This file is more complex, but I'm avoiding loading unessasary plugins, HTTP gets, copypasta/ console errors.
*/
 

/**
 * Enqueue Scripts, styles separated by use. Initializing via wp_footer.
 * In child theme can deactivate each one via remove_action
 * See: http://codex.wordpress.org/Function_Reference/remove_action
 *
 * Note: 
 */

//first wrap all front-end scripts in a big, old if ! is_admin
if (! is_admin() ) :

/**
* Foundation
*/
//Foundation
if (! function_exists('_sf_scripts_foundation') ) :
function _sf_scripts_foundation() {
//scripts
	wp_enqueue_script('foundation-js', get_template_directory_uri().'/js/foundation.min.js', array( 'jquery' ), false, true);
	wp_enqueue_script('modernizer', get_template_directory_uri().'/js/custom.modernizr.js');
//styles
	wp_enqueue_style('normalize', get_template_directory_uri().'/css/normalize.css');
	wp_enqueue_style('foundation-css', get_template_directory_uri().'/css/foundation.min.css');
}
add_action( 'wp_enqueue_scripts', '_sf_scripts_foundation' );
endif; //! _sf_scripts_foundation exists

if (! function_exists('_sf_js_init_foundation_code') ) :
function _sf_js_init_foundation_code() {
	echo "
			$(document)
				.foundation('interchange')
					.foundation('orbit')
					.foundation( 
					'topbar', {stickyClass: 'sticky-topbar'}
					);
		";
}
endif; // if ! _sf_js_init_foundation_code exists

if (! function_exists('_sf_js_init_foundation') ) :
function _sf_js_init_foundation() { 
	echo "
		<script>
			jQuery(document).ready(function($) {
	";
	_sf_js_init_foundation_code();
	echo "
			}); //end no conflict wrapper
		</script>
	";
}
add_action('wp_footer', '_sf_js_init_foundation');
endif; //! _sf_js_init_foundation

/**
*Infinite Scroll
* 	Method from: http://wptheming.com/2012/03/infinite-scroll-to-wordpress-theme/
*/

//first test to see if we need infinite scroll:
if (  (get_theme_mod( '_sf_inf-scroll' ) == '' ) &&  (! get_theme_mod( '_sf_masonry' ) == '' ) ) :
if (! function_exists('_sf_scripts_infScroll') ) :
function _sf_scripts_infScroll() {
	wp_register_script( 'infinite_scroll',  get_template_directory_uri() . '/js/jquery.infinitescroll.min.js', array('jquery'), false, false );
	wp_enqueue_script('infinite_scroll');
}
add_action( 'wp_enqueue_scripts', '_sf_scripts_infScroll' );
endif; //! _sf_scripts exists_infScroll

if (! function_exists('_sf_js_init_infScroll_code') ) :
function _sf_js_init_infScroll_code() { ?>

		var infinite_scroll = {
			loading: {
				img: "<?php echo get_template_directory_uri(); ?>/images/ajax-loader.gif",
				msgText: "<?php _e( 'Loading the next set of posts...', 'custom' ); ?>",
				finishedMsg: "<?php _e( 'All posts loaded.', 'custom' ); ?>"
			},
			"nextSelector":"#nav-below .nav-previous a",
			"navSelector":"#nav-below",
			"itemSelector":"article",
			"contentSelector":"#content"
		};
		jQuery( infinite_scroll.contentSelector ).infinitescroll( infinite_scroll );
<?php
}
endif; // if ! _sf_js_init_infScroll_code exists

if (! function_exists('_sf_js_init_infScroll') )  :
function _sf_js_init_infScroll() {
	echo '
		<script>
	';
	_sf_js_init_infScroll_code();
	echo '	
		</script>
	';
}
add_action('wp_footer', '_sf_js_init_infScroll', 10);

endif; //! _sf_js_init_infScroll
endif; //we need infscroll

/**
*masonry
*/

//first check if masonry is being used, if so do all the things we need, if not fuck it.
if ( get_theme_mod( '_sf_masonry' ) == '' ) :
if (! function_exists('_sf_scripts_masonry') ) :
function _sf_scripts_masonry() {
	wp_enqueue_script('masonry', get_template_directory_uri().'/js/jquery.masonry.min.js');
}
add_action( 'wp_enqueue_scripts', '_sf_scripts_masonry' );
endif; //! _sf_scripts_masonry exists

if (! function_exists('_sf_js_init_masonry_code') ) :
function _sf_js_init_masonry_code() {
	//get the theme_mod that tells us how many wide we want to go. If it isn't set return 4 so we don't get an error and it goes 4 wide, because I said so and you didn't.
	$howmany = get_theme_mod('masonry_how_many', 4);
	echo "
				$('#masonry-loop').masonry({
					  itemSelector: '.masonry-entry',
					  // set columnWidth a fraction of the container width
					  columnWidth: function( containerWidth ) {
		
	";
	echo "					return containerWidth / ".$howmany.";
					  }
				});
		";
}
endif; // if ! _sf_js_init_masonry_code exists

if (! function_exists('_sf_js_init_masonry') ) :
function _sf_js_init_masonry() {
	echo "
		<script>
			jQuery(document).ready(function($) {
	";
	_sf_js_init_masonry_code();
	echo"
			}); //end no conflict wrapper
		</script>
	";
}
	add_action('wp_footer', '_sf_js_init_masonry');
endif; //! _sf_js_init_masonry
endif; //do we need masonry?
//

/**
* Ajax Menus
* 	method from: http://wptheming.com/2011/12/ajax-themes/
*/

if (! function_exists('_sf_scripts_ajaxMenus') ) :
function _sf_scripts_ajaxMenus() {
	if ( get_theme_mod( '_sf_ajax' ) == '' ) :
		wp_deregister_script('historyjs');
		wp_register_script( 'historyjs', get_template_directory_uri(). '/js/jquery.history.js', array( 'jquery' ), '1.7.1' );
		wp_enqueue_script( 'historyjs' );	
	endif; // get_theme_mod( '_sf_ajax' ) == ''
}
add_action( 'wp_enqueue_scripts', '_sf_scripts_ajaxMenus' );
endif; //! _sf_scripts exists

if (! function_exists('_sf_js_init_ajaxMenus') ) :
function _sf_js_init_ajaxMenus() { 
	echo'
	<script>
		jQuery(document).ready(function($) {
			// Establish Variables
			var
				History = window.History, // Note: Using a capital H instead of a lower h
				State = History.getState(),
				$log = $("#log");
	
			// If the link goes to somewhere else within the same domain, trigger the pushstate
			$("#site-navigation a").on("click", function(e) {
				e.preventDefault();
				var path = $(this).attr("href");
				var title = $(this).text();
				History.pushState("ajax",title,path);
			});
		
			// Bind to state change
			// When the statechange happens, load the appropriate url via ajax
			History.Adapter.bind(window,"statechange",function() { // Note: Using statechange instead of popstate
				load_site_ajax();
			});
	
			// Load Ajax
			function load_site_ajax() {
				State = History.getState(); // Note: Using History.getState() instead of event.state
				// History.log("statechange:", State.data, State.title, State.url);
				//console.log(event);
				$("#primary").prepend(\'<div id="ajax-loader"><h4>Loading...</h4></div>\');
				$("#ajax-loader").fadeIn();
				$("#site-description").fadeTo(200,0);
				$("#content").fadeTo(200,.3);
				$("#main").load(State.url + " #primary ", function(data) {
					/* After the content loads you can make additional callbacks*/
					$("#site-description").text("Ajax loaded: " + State.url);
					$("#site-description").fadeTo(200,1);
					$("#content").fadeTo(200,1);
	';
	echo '//re-initialize foundation';
	_sf_js_init_foundation_code();
	
	//check if the infinite scroll functions exist, which they only do if any options are set to use it. If so reinitialize it.
	if ( function_exists('_sf_js_init_infScroll') ) {
		echo '//re-initialize infinite scroll
		';
			_sf_js_init_infScroll_code();
	}
	//check if the masonry exist, which they only do if any options are set to use it. If so reinitialize it.
	if ( function_exists('_sf_js_init_masonry') ) {
	echo '//re-initialize masonry
		';
		_sf_js_init_masonry_code();
	}
	//check if the backstretch functions exist, which they only do if any options are set to use it. If so reinitialize it.
	if ( function_exists('_sf_scripts_backstretch') ) {
		//use=reinit so backstretch code functions are wrapped right.
		$use = 'reinit';
		echo '//re-initialize backstretch
		';
		_sf_js_init_backstretch($use);
	}
	echo '
					// Updates the menu
					var request = $(data);
					$("#access").replaceWith($("#access", request));
			
				});
			}
		}); //end no conflict wrapper
	</script>
	';
}
add_action('wp_footer', '_sf_js_init_ajaxMenus');
endif; //! _sf_js_init_ajaxMenus

/**
* Backstretch
*/

//get urls of background images. Doing this first to test if they have a value in the big if statement that is about to happen.
$body_img_url = get_theme_mod('body_bg_img');
$header_img_url = get_theme_mod('header_bg_img');
$content_img_url = get_theme_mod('content_bg_img');
if (
//if we're using full screen background image, and one is set (which btw may be the default one.)
get_theme_mod( 'body_bg_choice' ) == '' 
//or we're using a background image for the header and  and one is set 
|| ! get_theme_mod( 'header_bg_choice' ) == '' && ! $header_img_url == ''
//or we're using a background image for the content area and  and one is set 
|| ! get_theme_mod( 'content_bg_choice' ) == '' && ! $content_img_url == ''
) :
if (! function_exists('_sf_scripts_backstretch') ) :
function _sf_scripts_backstretch() {
	wp_enqueue_script('backstretch', get_template_directory_uri().'/js/jquery.backstretch.min.js');
}
add_action( 'wp_enqueue_scripts', '_sf_scripts_backstretch' );
endif; //! _sf_scripts exists

if (! function_exists('_sf_js_init_backstretch') ) :
function _sf_js_init_backstretch($use = '') {
	if ( get_theme_mod('body_bg_img') == '' ) {
		$body_img_url = get_template_directory_uri().'/images/bg.jpg';
	}
	else {
		$body_img_url = get_theme_mod('body_bg_img');
	}
	$header_img_url = get_theme_mod('header_bg_img');
	$content_img_url = get_theme_mod('content_bg_img');
	
	//$use = 'reinit' in the ajax menu callback, so we don't get style tags in the middle of that.
	if (! $use == 'reinit') {
		echo '<script>     ';
	}
	
	//if (!  get_theme_mod( 'body_bg_choice' ) == ''  ) {
		$img = $body_img_url;
		echo ' jQuery.backstretch("';
		echo $img;
		echo '");     ';
	//} 
	
	if ( ! get_theme_mod( 'header_bg_choice' ) == '' && ! $header_img_url == '' ) {
		// store the image ID in a var
		$img = $header_img_url;
		
		echo 'jQuery("#masthead").backstretch("';
		echo $img;
		echo '");    ';
	}
	if ( ! get_theme_mod( 'content_bg_choice' ) == '' && ! $content_img_url == '' ) {
		$img = $content_img_url;
		echo 'jQuery("#primary").backstretch("';
		echo $img;
		echo '");    ';
	}
	
	if (! $use == 'reinit') {
		echo '</script>';
	}
}
add_action('wp_footer', '_sf_js_init_backstretch');
endif; //! _sf_js_init_backstretch
endif; //the big one.

if (! function_exists('_sf_style') ) :
function _sf_style() {
	wp_enqueue_style( '_sf-style', get_stylesheet_uri() );
}
add_action( 'wp_enqueue_scripts', '_sf_style' );
endif; //! _sf_style exists

endif; // ! is_admin
/**
* Other scripts
*/

//extra description/ insturctions in themes.php
if (! function_exists('_sf_extraDesc') ):
function _sf_extraDesc($hook) {
    if( 'themes.php' != $hook )
        return;
    wp_enqueue_script( 'extra-desc', get_template_directory_uri().'/js/extra-desc.js' );
}
add_action( 'admin_enqueue_scripts', '_sf_extraDesc' );
endif; //! _sf_extraDesc exists

