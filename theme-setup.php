<?php
/**
 * _sf theme setup
 *
 * @package _sf
 * since 1.5.1
 */
 
 /**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */
	

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
if ( ! function_exists( '_sf_setup' ) ) :
	function _sf_setup() {

		/**
		 * Make theme available for translation
		 * Translations can be filed in the /languages/ directory
		 * If you're building a theme based on _s, use a find and replace
		 * to change '_sf' to the name of your theme in all the template files
		 */
		load_theme_textdomain( '_sf', get_template_directory() . '/languages' );

		/**
		 * Add default posts and comments RSS feed links to head
		 */
		add_theme_support( 'automatic-feed-links' );

		/**
		 * Enable support for Post Thumbnails
		 */
		add_theme_support( 'post-thumbnails' );

		/**
		 * This theme uses wp_nav_menu() in one location.
		 */
		register_nav_menus( array(
			'primary' => __( 'Primary Menu', '_sf' ),
		) );

		/**
		 * Enable support for Post Formats
		 */
		add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );
	}
	add_action( 'after_setup_theme', '_sf_setup' );
endif; // _sf_setup

/**
 * Register widgetized area and update sidebar with default widgets
 */
if (! function_exists('_sf_widgets_init') ) :
function _sf_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', '_sf' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h5 class="widget-title">',
		'after_title'   => '</h5>',
	) );
}
add_action( 'widgets_init', '_sf_widgets_init' );
endif; //! _sf_widgets_init exists


/**
* Filters for the_excerpt 
*/

//add class of excerpt to excerpt
if (! function_exists('_sf_add_class_to_excerpt' ) ) :
function _sf_add_class_to_excerpt( $excerpt ) {
    return str_replace('<p', '<p class="excerpt"', $excerpt);
}
add_filter( "the_excerpt", "_sf_add_class_to_excerpt" );
endif; //! ! _sf_add_class_to_excerpt exists

if (! function_exists('_sf_new_excerpt_more' ) ) :
function _sf_new_excerpt_more( $more ) {
	if (function_exists('_sf_scripts_masonry')) {
		return ' <a class="read-more" href="'. get_permalink( get_the_ID() ) . '">Read More</a>';
	}
}
add_filter( 'excerpt_more', '_sf_new_excerpt_more' );
endif; //! _sf_new_excerpt_more exists

if (! function_exists('_sf_custom_excerpt_length') ) :
function _sf_custom_excerpt_length( $length ) {
	//get the value of the masonry excerpt length option, or set it to 10 if !isset
	$masonry_excerpt_length = get_theme_mod('masonry_excerpt_length', 10);
	//if masonry functions exists, since we're using it set using $masonry_excerpt_length, else leave at 55.
	if (function_exists('_sf_scripts_masonry')) {
		return $masonry_excerpt_length;
	}
	else {
		return 55;
	}
}
add_filter( 'excerpt_length', '_sf_custom_excerpt_length', 999 );
endif; // ! _sf_custom_excerpt_length exists

/**
* Masonry Brick Width As A Percentage
* Use to set value with width selector.
*/

if (! function_exists('_sf_masonry_width') ) :
function _sf_masonry_width() {
	//get the theme_mod that tells us how many wide we want to go. If it isn't set return 4 so we don't get an error. 4 is a nice looking number.
	$howmany = get_theme_mod('masonry_how_many', 4);
	//divide that by 100 to get the percent width
	$percent = 100/$howmany;
	//echo it with a % sign.
	echo $percent.'%;';
}
endif; //_sf_masonry_width exists

/**
* Theme Credit Links
*
* @since _sf 0.1
*/
if (! function_exists('_sf_credit_links') ) :
function _sf_credit_links() { ?>
	<div class="site-info large-12 columns">
		<a href="http://wordpress.org/" title="<?php esc_attr_e( 'A Semantic Personal Publishing Platform', '_sf' ); ?>" rel="generator"><?php printf( __( 'Powered by %s', '_sf' ), 'WordPress' ); ?></a>
					<span class="sep"> | </span>
					<?php printf( __( 'Theme: %1$SSecond Foundation by %2$s.', '_sf' ), '_Second Foundation', '<a href="http://ComplexWaveform.com/" rel="designer">Josh Pollock</a>' ); ?>
	</div><!-- .site-info -->
<?php
}
add_action('tha_footer_bottom', '_sf_credit_links');
endif; //! _sf_credit_links exists