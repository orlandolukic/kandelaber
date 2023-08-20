<?php

if ( ! class_exists('KandelaberHandler') ) {
    class KandelaberHandler {

        private static $instance;
        public static function get_instance() {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        private static $CSS_VERSION = '1.0.0';
        private static $JS_VERSION = '1.0.0';

        private function __construct()
        {
            add_action( 'lucent_action_after_main_js', array( $this, 'include_js_scripts' ) );
            add_action('lucent_action_after_main_css', array($this, 'add_custom_css'));

            add_action('lucent_action_after_body_tag_open', array($this, 'set_loading_screen'));
        }

        public function add_custom_css() {
            wp_enqueue_style( 'kandelaber-custom', LUCENT_ASSETS_CSS_ROOT . '/custom.css', [], KandelaberHandler::$CSS_VERSION );
            wp_enqueue_style( 'bootstrap-grid', LUCENT_ASSETS_CSS_ROOT . '/bootstrap-grid.css', [], '5.3.1' );
            wp_enqueue_style( 'fontawesome', LUCENT_ASSETS_ROOT . '/fonts/fontawesome/css/fontawesome.css', [], '6.4.2' );
            wp_enqueue_style( 'fontawesome-brands', LUCENT_ASSETS_ROOT . '/fonts/fontawesome/css/brands.css', [], '6.4.2' );
            wp_enqueue_style( 'fontawesome-solid', LUCENT_ASSETS_ROOT . '/fonts/fontawesome/css/solid.css', [], '6.4.2' );
        }

        public function set_loading_screen() {
            echo lucent_get_template_part('footer', 'templates/parts/page-loader');
        }

        public function include_js_scripts() {
            wp_enqueue_script( 'kandelaber-main', LUCENT_ASSETS_JS_ROOT . '/kandelaber-main.js', [], KandelaberHandler::$JS_VERSION );
        }
    }

    KandelaberHandler::get_instance();
}
