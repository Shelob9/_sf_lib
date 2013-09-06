<?php
/**
 * _sf Theme Customizer
 *
 * @package _sf
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
if (! function_exists('_sf_customize_preview_js') ) :
function _sf_customize_preview_js() {
	wp_enqueue_script( '_s_customizer', get_template_directory_uri() . '/lib/js/customizer.js', array( 'customize-preview' ), '20130304', true );
}
add_action( 'customize_preview_init', '_sf_customize_preview_js' );
endif; //! _sf_customize_preview_js exists

/**
* Theme Customizer Settings
**/
if (! function_exists('_sf_customize_register') ) :
function _sf_customize_register( $wp_customize ){

	//Remove unnecessary defaults controls, settings and sections
	$wp_customize-> remove_section('background_image');
	$wp_customize-> remove_section('static_front_page');
	$wp_customize-> remove_section('colors');

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
	
//define some colors in variables
$fLightBlue = '#2ba6cb';
$fBlack = '#222';
$fSilver = '#e9e9e9';

/**
* Sections
*/
//slider
    $wp_customize->add_section('_sf_home_slider', array(
        'title'    => __('Home Page Slider', '_s_f'),
        'priority' => 120,
    ));
//header options
    $wp_customize->add_section('_sf_header_options', array(
        'title'    => __('Header Options', '_sf'),
        'priority' => 120,
    ));
//header colors
    $wp_customize->add_section('_sf_header_colors', array(
        'title'    => __('Header Colors', '_sf'),
        'priority' => 121,
    ));

//Page Options 
    $wp_customize->add_section('_sf_page_options', array(
        'title'    => __('Page Options', '_sf'),
        'priority' => 125,
    ));
//Section For Background Options
	 $wp_customize->add_section('_sf_background_options', array(
        'title'    => __('Background Options', '_sf'),
        'priority' => 128,
    ));
//Section For Background Colors
	 $wp_customize->add_section('_sf_background_colors', array(
        'title'    => __('Background Colors', '_sf'),
        'priority' => 129,
    ));
//content colors
    $wp_customize->add_section('_sf_content_colors', array(
        'title'    => __('Content Area Colors', '_sf'),
        'priority' => 132,
    ));
// Masonry Options
    $wp_customize->add_section('_sf_masonry_options', array(
        'title'    => __('Masonry Options', '_sf'),
        'priority' => 140,
    ));
 //Sidebar Colors
    $wp_customize->add_section('_sf_sidebar_colors', array(
        'title'    => __('Sidebar Colors', '_sf'),
        'priority' => 133,
    ));
/**
* Slider
*/    
    //  ============================
    //  = Show Slider on Home Page? =
    //  =============================
	$wp_customize->add_setting(
    '_sf_slider_visibility'
    );

    $wp_customize->add_control(
    '_sf_slider_visibility',
    array(
        'type' => 'checkbox',
        'label' => __('Show Home Page Slider?', '_sf'),
        'section' => '_sf_home_slider',
        'priority' => '1',
        )
    );
 
    //  ============================
    //  = Number of Slides To Show =
    //  ============================
 
    $wp_customize->add_setting(
    '_sf_slide_numb', array(
    	'default' => 5,
    	'sanitize_callback' => '_sf_sanitize_number'
    	)
    );

    $wp_customize->add_control(
    '_sf_slide_numb',
    array(
        'type' => 'text',
		'default' => 5,
        'label' => __('Number Of Slides To Show - Default is 5. Enter numbers only.', '_sf'),
        'section' => '_sf_home_slider',
        'sanitize_callback' => '_sf_sanitize_number'
        )
    );
   
   	// ==========================
   	// = Which Category To Show = 
   	// ==========================
 	//create category dropdown
    $categories = get_categories();
	$cats = array();
	$i = 0;
	foreach($categories as $category){
		if($i==0){
			$default = $category->slug;
			$i++;
		}
		$cats[$category->slug] = $category->name;
	}
 
	$wp_customize->add_setting('_sf_slide_cat', array(
		'default'        => $default
	));
	$wp_customize->add_control( 'cat_select_box', array(
		'settings' => '_sf_slide_cat',
		'label'   => __('Select Category:', '_sf'),
		'section'  => '_sf_home_slider',
		'type'    => 'select',
		'choices' => $cats,
	));
	
	// =================
	// = Slider Colors =
	// =================
	
	$slider[] = array(
		'slug'=>'slider_bg_color', 
		'default' => $fSilver,
		'label' => __('Slider Background Color', 'sf')
	);
	$slider[] = array(
		'slug'=>'slider_title_color', 
		'default' => $fBlack,
		'label' => __('Slider Title Color', 'sf')
	);
	$slider[] = array(
		'slug'=>'slider_button_bg_color', 
		'default' => '',
		'label' => __('Slider Button Background Color', 'sf')
	);
	$slider[] = array(
		'slug'=>'slider_button_text_color', 
		'default' => '#000',
		'label' => __('Slider Button Text Color', 'sf')
	);
	$slider[] = array(
		'slug'=>'slider_excerpt_text_color',
		'default' => $fBlack,
		'label' => __('Slider Excerpt Color', '_sf')
	);
		foreach( $slider as $color ) {
		// SETTINGS
		$wp_customize->add_setting(
			$color['slug'], array(
				'default' => $color['default'],
				'type' => 'option', 
				'capability' => 
				'edit_theme_options'
			)
		);
		// CONTROLS
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$color['slug'], 
				array('label' => $color['label'], 
				'section' => '_sf_home_slider',
				'settings' => $color['slug'])
			)
		);
	}
 
/**
* Topbar/nav
*/ 
    //  =============================
    //  = Site Name In Menu? =
    //  =============================
    $wp_customize->add_setting('_sf_name_in_menu', array(
        'capability' => 'edit_theme_options',
        
    ));
 
    $wp_customize->add_control('display_menu_name', array(
        'settings' => '_sf_name_in_menu',
        'label'    => __('Don\'t display Name of site in Menu?', '_sf'),
        'section'  => '_sf_header_options',
        'type'     => 'checkbox',
        'priority'	 => '5',
    ));
 	//  =============================
    //  = Menu Sticky? =
    //  =============================
    $wp_customize->add_setting('_sf_menu_sticky', array(
        'capability' => 'edit_theme_options',
    ));
 
    $wp_customize->add_control('menu_sticky', array(
        'settings' => '_sf_menu_sticky',
        'label'    => __('Stick Menu To Top Of Page?', '_sf'),
        'section'  => '_sf_header_options',
        'type'     => 'checkbox',
        'priority'	 => '10',
    ));
    //  ======================
    //  = Search Bar In Menu =
    //  ======================
    $wp_customize->add_setting('_sf_menu_search', array(
        'capability' => 'edit_theme_options',
    ));
 
    $wp_customize->add_control('menu_search', array(
        'settings' => '_sf_menu_search',
        'label'    => __('Search Bar In Menu?', '_sf'),
        'section'  => '_sf_header_options',
        'type'     => 'checkbox',
        'priority'	 => '15',
    ));
 
/**
* Fancy JS
*/
/* DISABLED DUE TO INFINITE SCROLL INCOMPATIBILITY
    //  ============================
    //  = Disable Infinite Scroll? =
    //  =============================
	$wp_customize->add_setting(
    '_sf_inf-scroll'
    );

    $wp_customize->add_control(
    '_sf_inf-scroll',
    array(
        'type' => 'checkbox',
        'label' => __('Disable Infinite Scroll?', '_sf'),
        'section' => '_sf_page_options',
        )
    );
*/
    //  ============================
    //  = Disable AJAX Page Loads? =
    //  ============================
	$wp_customize->add_setting(
    '_sf_ajax'
    );

    $wp_customize->add_control(
    '_sf_ajax',
    array(
        'type' => 'checkbox',
        'label' => __('Disable AJAX Page Loads?', '_sf'),
        'section' => '_sf_page_options',
        )
    );
    
 
/**
* Color Controls
*/

 	//  ==================
    //  = Color Controls =
    //  ==================
    
//menu/header colors
	$menu = array();
	//MENU
	$menu[] = array(
		'slug'=>'site_name_color', 
		'default' => ' ',
		'label' => __('Site Name Color', 'sf'),
	);
	$menu[] = array(
		'slug'=>'menu_text_color', 
		'default' => ' ',
		'label' => __('Menu Text Color', 'sf'),
		
	);
	$menu[] = array(
		'slug'=>'menu_search_txt_color', 
		'default' => '#fff',
		'label' => __('Search Button Text Color', 'sf')
	);
	$menu[] = array(
		'slug'=>'menu_search_bg_color', 
		'default' => '',
		'label' => __('Search Button Background Color', 'sf')
	);
	$menu[] = array(
		'slug'=>'menu_search_bg_color_hv', 
		'default' => '',
		'label' => __('Search Button Background Hover Color', 'sf')
	);
	$menu[] = array(
		'slug'=>'menu_bg_color', 
		'default' => ' ',
		'label' => __('Menu Background Color', 'sf')
	);
	
	$menu[] = array(
		'slug'=>'menu_hover_color', 
		'default' => ' ',
		'label' => __('Menu Hover Color', 'sf')
	);
	$menu[] = array(
		'slug'=>'site_description_color', 
		'default' => ' ',
		'label' => __('Site Description Color', 'sf')
	);
		foreach( $menu as $color ) {
		// SETTINGS
		$wp_customize->add_setting(
			$color['slug'], array(
				'default' => $color['default'],
				'type' => 'option', 
				'capability' => 
				'edit_theme_options'
			)
		);
		// CONTROLS
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$color['slug'], 
				array('label' => $color['label'], 
				'section' => '_sf_header_colors',
				'settings' => $color['slug'])
			)
		);
	}
	
//content area colors
	$content[] = array(
	'slug'=>'content_text_color', 
	'default' => $fBlack,
	'label' => __('Content Text Color', 'sf')
	);
	$content[] = array(
		'slug'=>'content_link_color', 
		'default' => $fLightBlue,
		'label' => __('Content Link Color', 'sf')
	);
	$content[] = array(
		'slug'=>'post_title_color', 
		'default' => $fBlack,
		'label' => __('Post Title Color', 'sf')
	);
	$content[] = array(
		'slug'=>'page_title_color', 
		'default' => $fBlack,
		'label' => __('Page Title Color', 'sf')
	);
	
			foreach( $content as $color ) {
		// SETTINGS
		$wp_customize->add_setting(
			$color['slug'], array(
				'default' => $color['default'],
				'type' => 'option', 
				'capability' => 
				'edit_theme_options'
			)
		);
		// CONTROLS
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$color['slug'], 
				array('label' => $color['label'], 
				'section' => '_sf_content_colors',
				'settings' => $color['slug'])
			)
		);
	}
//sidebar area colors

	$sidebar[] = array(
	'slug'=>'sidebar_text_color', 
	'default' => '#000',
	'label' => __('Sidebar Text Color', 'sf')
	);
	$sidebar[] = array(
		'slug'=>'sidebar_link_color', 
		'default' => $fLightBlue,
		'label' => __('Sidebar Link Color', 'sf')
	);
	$sidebar[] = array(
		'slug'=>'widget_title_color', 
		'default' => $fBlack,
		'label' => __('Widget Title Color', 'sf')
	);
			foreach( $sidebar as $color ) {
		// SETTINGS
		$wp_customize->add_setting(
			$color['slug'], array(
				'default' => $color['default'],
				'type' => 'option', 
				'capability' => 
				'edit_theme_options'
			)
		);
		// CONTROLS
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$color['slug'], 
				array('label' => $color['label'], 
				'section' => '_sf_sidebar_colors',
				'settings' => $color['slug'])
			)
		);
	}

//read more button
	//sidebar area colors

	$readmore[] = array(
	'slug'=>'excerpt_button_text_color', 
	'default' => $fBlack,
	'label' => __('Read More Button Text Color', 'sf')
	);
	$readmore[] = array(
		'slug'=>'excerpt_button_bg_color', 
		'default' => ' ',
		'label' => __('Read More Button Background Color', 'sf')
	);

			foreach( $readmore as $color ) {
		// SETTINGS
		$wp_customize->add_setting(
			$color['slug'], array(
				'default' => $color['default'],
				'type' => 'option', 
				'capability' => 
				'edit_theme_options'
			)
		);
		// CONTROLS
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$color['slug'], 
				array('label' => $color['label'], 
				'section' => '_sf_page_options',
				'settings' => $color['slug'])
			)
		);
	}

/**
* Background(s)
* 
*/
  // =====================
  // = background colors =
  // =====================
  //TODO: Gradients!
	$bg[] = array(
		'slug'=>'page_bg_color', 
		'default' => '#fff',
		'label' => __('Page Background Color', 'sf')
	);
	$bg[] = array(
		'slug'=>'header_bg_color', 
		'default' => '#fff',
		'label' => __('Header Background Color', 'sf')
	);
	$bg[] = array(
		'slug'=>'content_bg_color', 
		'default' => '#fff',
		'label' => __('Content Area Background Color', 'sf')
	);
	$bg[] = array(
		'slug'=>'sidebar_bg_color', 
		'default' => '#fff',
		'label' => __('Sidebar Background Color', 'sf')
	);
	$bg[] = array(
		'slug'=>'footer_bg_color', 
		'default' => '#fff',
		'label' => __('Footer Background Color', 'sf')
	);
	
		foreach( $bg as $color ) {
		// SETTINGS
		$wp_customize->add_setting(
			$color['slug'], array(
				'default' => $color['default'],
				'type' => 'option', 
				'capability' => 
				'edit_theme_options'
			)
		);
		// CONTROLS
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$color['slug'], 
				array('label' => $color['label'], 
				'section' => '_sf_background_colors',
				'settings' => $color['slug'])
			)
		);
	}
  // =====================================
  // = Content/ Header Area Transperancy =
  // =====================================

//content area
    $wp_customize->add_setting(
    'content-trans-bg'
    );

    $wp_customize->add_control(
    'content-trans-bg',
    array(
        'type' => 'checkbox',
        'label' => 'Transparent Content Area Background?',
        'section' => '_sf_background_options',
        'priority' => '23',
        )
    );
//header area
    $wp_customize->add_setting(
    'header-trans-bg'
    );

    $wp_customize->add_control(
    'header-trans-bg',
    array(
        'type' => 'checkbox',
        'label' => 'Transparent Header Background?',
        'section' => '_sf_background_options',
        'priority' => '13',
        )
    );
//sidebar area
    $wp_customize->add_setting(
    'sidebar-trans-bg'
    );

    $wp_customize->add_control(
    'sidebar-trans-bg',
    array(
        'type' => 'checkbox',
        'label' => 'Transparent Sidebar Background?',
        'section' => '_sf_background_options',
        'priority' => '30',
        )
    );
//footer area
    $wp_customize->add_setting(
    'footer-trans-bg'
    );

    $wp_customize->add_control(
    'footer-trans-bg',
    array(
        'type' => 'checkbox',
        'label' => 'Transparent Footer Background?',
        'section' => '_sf_background_options',
        'priority' => '40',
        )
    );
  	// =========
	// BG IMGs =
	// =========
	
	//Background Color or Full-Width Image?
	$wp_customize->add_setting('body_bg_choice'

	);

    $wp_customize->add_control(
    'body_bg_choice',
    array(
        'type' => 'checkbox',
        'label' => 'Use Background Color Instead of Image For Page?',
        'section' => '_sf_background_options',
        'settings'   => 'body_bg_choice',
        'priority' => '5',
        )
    );
    $defaultbg = get_template_directory_uri().'/images/bg.jpg';
	//page background img
	    $wp_customize->add_setting('body_bg_img', array(
        'default'           => $defaultbg,
        'capability'        => 'edit_theme_options',
    ));
 
    $wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'body_bg_img', array(
        'label'    => __('Upload Page Background', 'sf'),
        'section'    => '_sf_background_options',
        'settings' => 'body_bg_img',
        'priority' => '10',
    )));
    
    //Background Color or Image For Header?
	$wp_customize->add_setting('header_bg_choice', array(
	) );

    $wp_customize->add_control(
    'header_bg_choice',
    array(
        'type' => 'checkbox',
        'label' => 'Use Background Image Instead of Color For Header?',
        'section' => '_sf_background_options',
        'settings'   => 'header_bg_choice',
        'priority' => '12',
        )
    );
	//header background img
	    $wp_customize->add_setting('header_bg_img', array(
        'capability'        => 'edit_theme_options',
    ));
 
    $wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'header_bg_img', array(
        'label'    => __('Upload Background Image For Header', 'sf'),
        'section'    => '_sf_background_options',
        'settings' => 'header_bg_img',
        'priority' => '15',
    )));
    
    //Background Color or Image For Content-Area?
	$wp_customize->add_setting('content_bg_choice', array(
	) );

    $wp_customize->add_control(
    'content_bg_choice',
    array(
        'type' => 'checkbox',
        'label' => 'Use Background Image Instead of Color For Content Area?',
        'section' => '_sf_background_options',
        'settings'   => 'content_bg_choice',
        'priority' => '22',
        )
    );
	//content background img
	    $wp_customize->add_setting('content_bg_img', array(
        'capability'        => 'edit_theme_options',
    ));
 
    $wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'content_bg_img', array(
        'label'    => __('Upload Background Image For Content Area', 'sf'),
        'section'    => '_sf_background_options',
        'settings' => 'content_bg_img',
        'priority' => '25',
    )));
/**
* Default Sidebar Location
*/
$wp_customize->add_setting(
			'_sf_default_sidebar', 
			array(
				'capability'     => 'edit_theme_options'
				)
		);
	$wp_customize->add_control(
   		 '_sf_default_sidebar',
		array(
			'label' => __('Sidebar Location', '_s_f'),
			'section' => '_sf_page_options',
			'default'        => 'value1',
			'type'       => 'select',
			'choices'    => array(
				'value1' => 'Right',
				'value2' => 'Left',
				'value3' => 'None',
			)
		)
    );

    //  ============
    //  = Masonry =
    //  ===========


    // Use Masonry?
	$wp_customize->add_setting(
    '_sf_masonry'
    );

    $wp_customize->add_control(
    '_sf_masonry',
    array(
        'type' => 'checkbox',
        'label' => __('Disable Masonry?', '_sf'),
        'section' => '_sf_masonry_options',
        'priority' => 1
        )
    );

    //How many bricks Wide?
    $wp_customize->add_setting(
    	'masonry_how_many',
    		array(
    			'default' => '4',
    		)
    );
    
    $wp_customize->add_control(
    'masonry_how_many',
    	array(
    		'type' => 'text',
    		'label' => __('How Many Bricks Per Row', '_sf'),
    		'priority' => '10',
    		'section' => '_sf_masonry_options',
    		'callback' => '_sf_sanitize_number',
    	)
    );
 	//show excerpt
 	$wp_customize->add_setting(
    '_sf_masonry_excerpt'
    );

    $wp_customize->add_control(
    '_sf_masonry_excerpt',
		array(
			'type' => 'checkbox',
			'label' => __('Hide Excerpt In Masonry Box?', '_sf'),
			'section' => '_sf_masonry_options',
			'priority' => '50',
			)
    );
    
    //How long is excerpt?
    $wp_customize->add_setting(
   	'masonry_excerpt_length',
   		array(
   			'default' => '25',
   		)
   	);
   	
   	$wp_customize->add_control(
   	'masonry_excerpt_length',
   		array (
   			'type' => 'text',
   			'label' => __('Masonry Excerpt Length (enter numbers only)', '_sf'),
   			'section' => '_sf_masonry_options',
   			'callback' => '_sf_sanitize_number',
   			'priority' => '52',
   			)
   	);
   	
   	//MOBILE MASONRY CONTROLS
   	// Disable Masonry On Mobile?
	$wp_customize->add_setting(
    '_sf_masonry_mobile'
    );

    $wp_customize->add_control(
    '_sf_masonry_mobile',
    array(
        'type' => 'checkbox',
        'label' => __('Use Masonry On Mobile? (Masonry Must Be Enabled)', '_sf'),
        'section' => '_sf_masonry_options',
        'priority' => 201
        )
    );
   	//Mobile How long is excerpt?
    $wp_customize->add_setting(
   	'masonry_mobileExcerpt_length',
   		array(
   			'default' => 10,
   		)
   	);
   	
   	$wp_customize->add_control(
   	'masonry_mobileExcerpt_length',
   		array (
   			'type' => 'text',
   			'label' => __('Masonry Mobile Excerpt Length (enter numbers only)', '_sf'),
   			'section' => '_sf_masonry_options',
   			'callback' => '_sf_sanitize_number',
   			'priority' => 210,
   			)
   	);
   	
   	 //Mobile How many bricks Wide?
    $wp_customize->add_setting(
    	'masonry_mobileHow_many',
    		array(
    			'default' => 2,
    		)
    );
    
    $wp_customize->add_control(
    'masonry_mobileHow_many',
    	array(
    		'type' => 'text',
    		'label' => __('How Many Bricks Per Row On Mobile?', '_sf'),
    		'section' => '_sf_masonry_options',
    		'callback' => '_sf_sanitize_number',
    		'priority' => 220,
    	)
    );
    
    //mobile show excerpt
 	$wp_customize->add_setting(
    '_sf_masonry_mobileExcerpt'
    );

    $wp_customize->add_control(
    '_sf_masonry_mobileExcerpt',
		array(
			'type' => 'checkbox',
			'label' => __('Hide Excerpt In Mobile Masonry Box?', '_sf'),
			'section' => '_sf_masonry_options',
			'priority' => '250',
			)
    );
   	
   	
    //masonry colors
	$masonry[] = array(
		'slug'=>'masonry_bg_color', 
		'default' => '#fff',
		'label' => __('Background Color', '_sf')
	);
	$masonry[] = array(
	'slug'=>'masonry_excerpt_text_color', 
	'default' => '#000',
	'label' => __('Excerpt Text Color', '_sf')
	);
	$masonry[] = array(
		'slug'=>'masonry_title_color', 
		'default' => $fLightBlue,
		'label' => __('Title Color', '_sf')
	);
	$masonry[] = array(
		'slug'=>'masonry_border_color', 
		'default' => ' ',
		'label' => __('Border Color', '_sf')
	);
			foreach( $masonry as $color ) {
		// SETTINGS
		$wp_customize->add_setting(
			$color['slug'], array(
				'default' => $color['default'],
				'type' => 'option', 
				'capability' => 
				'edit_theme_options'
			)
		);
		// CONTROLS
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$color['slug'], 
				array('label' => $color['label'], 
				'section' => '_sf_masonry_options',
				'settings' => $color['slug'])
			)
		);
	}
}
add_action('customize_register', '_sf_customize_register');
endif; //! _sf_customize_register exists

/**
* Add links to Customizer
* 1.0.5.1
*/
//Add WordPress customizer page to the admin menu.
if(!function_exists('_sf_add_options_menu')) :

	function _sf_add_options_menu() {
	    $theme_page = add_theme_page(
	        __( 'Customize Theme', '_sf' ),   // Name of page
	        __( 'Customize Theme', '_sf' ),   // Label in menu
	        'edit_theme_options',          // Capability required
	        'customize.php'             // Menu slug, used to uniquely identify the page
	    );
	}
add_action ('admin_menu', '_sf_add_options_menu');
endif; // ! _sf_add_options_menu exists

//Add WordPress customizer page to the admin bar menu.
if(!function_exists('_sf_add_admin_bar_options_menu')) :
	function _sf_add_admin_bar_options_menu() {
	   if ( current_user_can( 'edit_theme_options' ) ) {
	     global $wp_admin_bar;
	     $wp_admin_bar->add_menu( array(
	       'parent' => false,
	       'id' => 'theme_editor_admin_bar',
	       'title' =>  __( 'Customize Theme', '_sf' ),
	       'href' => admin_url( 'customize.php')
	     ));
	   }
	}
add_action( 'wp_before_admin_bar_render', '_sf_add_admin_bar_options_menu' );
endif; // ! _sf_add_admin_bar_options_menu exists

?>