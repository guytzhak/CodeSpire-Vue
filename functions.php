<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

Class CodeSpire_FrameWork {
    
    public function __construct() {
        
        $this->create_acf_support();

        add_action( 'after_setup_theme', array($this, 'cs_theme_setup') );
        add_action( 'wp_enqueue_scripts', array($this, 'cs_style_and_scripts'));
        add_action( 'init', array($this, 'cs_menus') );
        add_action( 'acf/init', array($this, 'my_acf_init') );
        $this->helper();
    }

    public function create_acf_support() {
        if( function_exists('acf_add_options_page') ) {

            acf_add_options_page(array(
                'page_title' 	=> 'Theme General Settings',
                'menu_title'	=> 'Theme Settings',
                'menu_slug' 	=> 'theme-general-settings',
                'capability'	=> 'edit_posts',
                'redirect'		=> false
            ));

        }
    }

    /**
     * Setup Theme Functions
     */
    public function cs_theme_setup() {
        load_theme_textdomain( 'cs', get_template_directory() . '/languages' );

	    add_theme_support( 'woocommerce' );
        add_theme_support('automatic-feed-links');
        add_theme_support('post-thumbnails');
        add_theme_support('post-formats', array( 'image', 'gallery', 'video' ));
        add_theme_support( 'title-tag' );
        add_theme_support( 'html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ) );

    }

    /** Call to JS & CSS **/
    function cs_style_and_scripts() {
    
		wp_enqueue_style( 'bootstrap-css', get_stylesheet_directory_uri() .'/public/css/vendor.css' );
		wp_enqueue_style( 'bootstrap-css', get_stylesheet_directory_uri() .'/public/css/app.css' );
        wp_enqueue_style( 'main-css', get_stylesheet_uri() );

        /*wp_enqueue_script( 'myscripts', get_stylesheet_directory_uri() . '/assets/js/app.js', '', '1.0.0', true );
        wp_localize_script('myscripts', 'cs_obj', array(
            'ajax_url' => admin_url('admin-ajax.php'),
        ));*/

    }


    // Register Navigation Menus
    public function cs_menus() {
        $locations = array(
            'header_menu' => __( 'Header Menu', 'cs' ),
            'footer_menu' => __( 'Footer Menu', 'cs' ),
        );
        register_nav_menus( $locations );
    }
    
    public function helper(){

        // Includes
        require 'inc/wp_bootstrap_navwalker.php';
        include_once('inc/core_func.php');
    }
	
	function my_acf_init() {
		acf_update_setting('google_api_key', 'AIzaSyC-dKgNvfXlUgcO2aoAKz2bGWkPDVv2jS4');
	}


    
}
// Install Theme
$theme = new CodeSpire_FrameWork();


function theme_js() {
    wp_enqueue_script( 'vendor_js', get_template_directory_uri() . '/public/js/vendor.min.js', '', '', true );
    //To avoid caching I use time, remove it in production.
    wp_enqueue_script( 'theme_js', get_template_directory_uri() . '/public/js/app.min.js', array('vendor_js'), '', true );
}
add_action( 'wp_enqueue_scripts', 'theme_js' );

if( wp_is_mobile() ) {
    show_admin_bar( false );
}

?>
