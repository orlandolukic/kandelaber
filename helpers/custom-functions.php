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

        private function __construct()
        {
            add_action('lucent_action_after_main_css', array($this, 'add_custom_css'));
        }

        public function add_custom_css() {
            wp_enqueue_style( 'kandelaber-custom', LUCENT_ASSETS_CSS_ROOT . '/custom.css', [], KandelaberHandler::$CSS_VERSION );
        }
    }

    KandelaberHandler::get_instance();
}
