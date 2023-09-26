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
            // Require all files necessary for theme development
            $this->require_files();

            add_action( 'lucent_action_after_main_js', array( $this, 'include_js_scripts' ) );
            add_action('lucent_action_after_main_css', array($this, 'add_custom_css'));

            add_action('lucent_action_after_body_tag_open', array($this, 'set_loading_screen'));
            add_action('lucent_action_before_wrapper_close_tag', array($this, 'add_loading_overlay'));

            add_filter('tiny_mce_before_init', array($this, 'set_mce_colors'));
            add_action( 'wpforms_process', array($this, 'wpf_dev_process'), 10, 3 );
        }

        private function require_files() {
            require LUCENT_INC_ROOT_DIR . "/elementor/product-category-listing/product-category-listing.php";
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

        public function add_loading_overlay() {
            echo lucent_get_template_part('footer', 'templates/parts/overlay-loader');
        }

        public function include_js_scripts() {
            wp_enqueue_script( 'tweenmax', LUCENT_ASSETS_JS_ROOT . '/tweenmax-1.20.2.js', ['kandelaber-main'], '1.20.2' );
            wp_enqueue_script( 'confetti', LUCENT_ASSETS_JS_ROOT . '/confetti.browser.js', [], "1.6.0" );
            wp_enqueue_script( 'kandelaber-main', LUCENT_ASSETS_JS_ROOT . '/kandelaber-main.js', [], KandelaberHandler::$JS_VERSION );
            wp_enqueue_script( 'popperjs', LUCENT_ASSETS_JS_ROOT . '/popper.min.js', [], '2.11.8' );
            wp_enqueue_script( 'tippy-bundle', LUCENT_ASSETS_JS_ROOT . '/tippy-bundle.umd.min.js', ['popperjs'], '6.3.7' );

            //wp_register_script('react', LUCENT_ASSETS_JS_ROOT . "/react.production.min.js", [], '18');
           //wp_register_script('react-dom', LUCENT_ASSETS_JS_ROOT . "/react-dom.production.min.js", ['react'], '18');
            wp_register_script('react-rendered', LUCENT_ASSETS_JS_ROOT . "/react-rendered.js", ['react-dom', 'tippy-bundle'], KandelaberHandler::$JS_VERSION );

            wp_localize_script('kandelaber-main', 'ajax_object', array(
                'ajax_url' => admin_url('admin-ajax.php'),
            ));
        }

        public function set_mce_colors( $init ) {
            $custom_colours = '"679436", "Primary", "427AA1", "Secondary", "313131", "Text", "064789", "Accent", "A5BE00", "CL1", "EBF2FA", "White"';
            $init['textcolor_map'] = '['.$custom_colours.']';
            $init['textcolor_rows'] = 6;
            return $init;
        }

        /**
         * Action that fires during form entry processing after initial field validation.
         *
         * @link   https://wpforms.com/developers/wpforms_process/
         *
         * @param  array  $fields    Sanitized entry field. values/properties.
         * @param  array  $entry     Original $_POST global.
         * @param  array  $form_data Form data and settings.
         */
        public function wpf_dev_process( $fields, $entry, $form_data ) {
            $sec_check_value = $fields[4][ 'value' ];
            if ( $sec_check_value !== "8" )  {
                wpforms()->process->errors[ $form_data[ 'id' ] ] [ '4' ] = esc_html__( 'Dogodila se greška', 'kandelaber' );
            }
        }

        public function custom_ajax_callback() {
            // Handle the AJAX request here

            $parameter1 = $_POST['parameter1'];
            $parameter2 = $_POST['parameter2'];

            // Perform your server-side logic here

            // Return a response
            echo 'Hello, AJAX! Received parameter1: ' . $parameter1 . ', parameter2: ' . $parameter2;

            // Always exit to prevent extra output
            wp_die();
        }

    }

    KandelaberHandler::get_instance();
}