<?php
/**
* 
* Custom Controls For The Customizer
*
* @ package _sf
* @ since 1.1.0
*
*/

/**
*
* Image Control With Ability To Pull From Media Manager.
*
* Adapted from: http://shibashake.com/wordpress-theme/how-to-add-the-media-manager-menu-to-the-theme-preview-interface
*/

function _sf_library_tab() {
    global $_sf_image_controls;
    static $tab_num = 0; // Sync tab to each image control
     
    $control = array_slice($_sf_image_controls, $tab_num, 1);
    ?>
    <a class="choose-from-library-link button"
        data-controller = "<?php esc_attr_e( key($control) ); ?>">
        <?php _e( 'Open Library' ); ?>
    </a>
     
    <?php
    $tab_num++;
}  

//scripts for this

function _sf_customizer_scripts($hook) {
	if( 'customize.php' != $hook )
        return;
    wp_enqueue_media();
   wp_enqueue_script('media-manager-control', get_template_directory_uri().'/js/mm.control.js/');
}
add_action( 'wp_enqueue_scripts', '_sf_customizer_scripts' );  


function _sf_customize_styles() { ?>
    <style>
    .wp-full-overlay {
        z-index: 150000 !important;
    }
    </style>

<?php }
add_action( 'customize_controls_print_styles', '_sf_customize_styles', 50);




