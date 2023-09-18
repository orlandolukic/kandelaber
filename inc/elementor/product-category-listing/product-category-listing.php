<?php

class Product_Category_Listing {
    private static $instance;

    public static function get() {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'elementor/widgets/register', array($this, 'register_new_widgets') );
        add_action( 'elementor/init', array($this, 'load_all_elementor_files') );
        add_action( 'lucent_action_after_main_js', array($this, 'enqueue_scripts'));
    }

    public function load_all_elementor_files() {
        require LUCENT_INC_ROOT_DIR . "/elementor/product-category-listing/product-category-listing-elementor-widget.php";
    }

    public function register_new_widgets( $widgets_manager ) {
        $widgets_manager->register( new Product_Category_Listing_Elementor_Widget() );
    }

    public function enqueue_scripts() {
        wp_enqueue_script('react-dom');
        wp_enqueue_script('react-rendered');
    }

}

// Create widget
Product_Category_Listing::get();

