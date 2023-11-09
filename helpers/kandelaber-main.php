<?php

if ( ! class_exists('KandelaberMain') ) {
    class KandelaberMain {

        private static $instance;
        public static function get_instance() {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        private static $CSS_VERSION = '1.0.0';
        private static $JS_VERSION = '1.0.2';

        private function __construct()
        {
            // Require all files necessary for theme development
            $this->require_files();

            // Add actions
            add_action( 'lucent_action_after_main_js', array( $this, 'include_js_scripts' ) );
            add_action('lucent_action_after_main_css', array($this, 'add_custom_css') );
            add_action('lucent_action_after_body_tag_open', array($this, 'set_loading_screen') );
            add_action('lucent_action_before_wrapper_close_tag', array($this, 'add_loading_overlay') );
            add_action( 'wpforms_process', array($this, 'wpf_dev_process'), 10, 3 );
            add_action( 'remove_categories_for_products', array($this, 'remove_categories') );
            add_action( 'init', array($this, 'init') );

            // Add filters
            add_filter( 'image_resize_dimensions', array($this, 'preserve_image_dimensions'), 10, 6 );
            add_filter( 'admin_post_thumbnail_html', array($this, 'set_category_thumbnail_size') );
            add_filter( 'intermediate_image_sizes_advanced', array($this, 'disable_image_sizes') );
            add_filter( 'tiny_mce_before_init', array($this, 'set_mce_colors') );
            add_filter( 'lucent_filter_header_inner_class', array($this, 'header_classes') );
            add_filter( 'document_title_parts', array($this, 'set_title_of_the_document') );
            add_action( 'wp_head', array($this, 'google_analytic') );
        }

        public function google_analytic() {
            ?>
            <meta name="google-site-verification" content="CnaAkEsNS7KzxT-0du2roDNZh8-vnakfrZokdzoFG_M" />

            <!-- Google tag (gtag.js) -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=G-G47W0S5B8N"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());

                gtag('config', 'G-G47W0S5B8N');
            </script>
            <?php
        }

        public function set_title_of_the_document($title) {
            if (is_404()) {
                $title['title'] = __('Stranica nije pronađena', 'kandelaber');
                return $title;
            }

            return $title;
        }

        public function init() {
            //do_action('remove_categories_for_products', 'led-paneli' );
        }

        private function require_files() {
            require LUCENT_ROOT_DIR . '/helpers/kandelaber-seo.php';
            require LUCENT_INC_ROOT_DIR . "/elementor/product-category-listing/product-category-listing.php";
        }

        public function header_classes($classes) {
            $classes[] = "kandelaber-header";
            return $classes;
        }

        public function add_custom_css() {
            wp_enqueue_style( 'kandelaber-custom', LUCENT_ASSETS_CSS_ROOT . '/custom.css', [], KandelaberMain::$CSS_VERSION );
            wp_enqueue_style( 'bootstrap-grid', LUCENT_ASSETS_CSS_ROOT . '/bootstrap-grid.css', [], '5.3.1' );
            wp_enqueue_style( 'fontawesome', LUCENT_ASSETS_ROOT . '/fonts/fontawesome/css/fontawesome.css', [], '6.4.2' );
            wp_enqueue_style( 'fontawesome-brands', LUCENT_ASSETS_ROOT . '/fonts/fontawesome/css/brands.css', [], '6.4.2' );
            wp_enqueue_style( 'fontawesome-solid', LUCENT_ASSETS_ROOT . '/fonts/fontawesome/css/solid.css', [], '6.4.2' );
            wp_enqueue_style( 'splide', LUCENT_ASSETS_ROOT . '/css/splide.min.css', [], KandelaberMain::$CSS_VERSION );
        }

        public function set_loading_screen() {
            echo lucent_get_template_part('footer', 'templates/parts/page-loader');
        }

        public function add_loading_overlay() {
            echo lucent_get_template_part('footer', 'templates/parts/overlay-loader');
            echo lucent_get_template_part('footer', 'templates/parts/footer-call-button');
        }

        public function include_js_scripts() {
            wp_enqueue_script( 'tweenmax', LUCENT_ASSETS_JS_ROOT . '/tweenmax-1.20.2.js', ['kandelaber-main'], '1.20.2' );
            wp_enqueue_script( 'confetti', LUCENT_ASSETS_JS_ROOT . '/confetti.browser.js', [], "1.6.0" );
            wp_enqueue_script( 'kandelaber-main', LUCENT_ASSETS_JS_ROOT . '/kandelaber-main.js', [], KandelaberMain::$JS_VERSION );
            wp_enqueue_script( 'popperjs', LUCENT_ASSETS_JS_ROOT . '/popper.min.js', [], '2.11.8' );
            wp_enqueue_script( 'tippy-bundle', LUCENT_ASSETS_JS_ROOT . '/tippy-bundle.umd.min.js', ['popperjs'], '6.3.7' );
            wp_enqueue_script( 'splide', LUCENT_ASSETS_JS_ROOT . '/splide.min.js', [], KandelaberMain::$JS_VERSION );

            //wp_register_script('react', LUCENT_ASSETS_JS_ROOT . "/react.production.min.js", [], '18');
            //wp_register_script('react-dom', LUCENT_ASSETS_JS_ROOT . "/react-dom.production.min.js", ['react'], '18');
            wp_register_script('react-rendered', LUCENT_ASSETS_JS_ROOT . "/react-rendered.js", ['react-dom', 'tippy-bundle'], KandelaberMain::$JS_VERSION );

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

        public function remove_categories($category_slug) {

            $category = get_categories(array(
                'taxonomy' => 'product_cat',
                'hide_empty' => 0,
                'fields' => 'ids',
                'slug' => $category_slug
            ));
            if (empty($category)) {
                ?>
                <script type="text/javascript">alert("Nema proizvoda u kategoriji '<?= $category_slug ?>'");</script>
                <?php
                return;
            }
            $category_id = $category[0];

            // Query products that are in the category you want to remove.
            $args = array(
                'post_type' => 'product',
                'posts_per_page' => -1,
                'tax_query' => array(
                    "terms" => $category_slug,
                    'field' => 'slug',
                    'taxonomy' => 'product_cat',
                    'include_children' => false
                )
            );
            $products = new WP_Query($args);

            if ($products->have_posts()) {
                $count = 0;
                while ($products->have_posts()) {
                    $products->the_post();
                    $product_id = get_the_ID();

                    // Remove the category from the product.
                    wp_remove_object_terms($product_id, $category_id, 'product_cat');
                    $count++;
                }

                // Reset post data.
                wp_reset_postdata();

                ?>
                <script type="text/javascript">alert("Uspesno ste obrisali kategoriju '<?= $category_slug ?>' za sve proizvode [obrisano <?= $count ?>]");</script>
                <?php
            } else {
                ?>
                <script type="text/javascript">alert("Nema proizvoda u kategoriji '<?= $category_slug ?>'");</script>
                <?php
            }
        }

        public function preserve_image_dimensions( $default, $orig_w, $orig_h, $new_w, $new_h, $crop )
        {
            if ($new_w == $orig_w && $new_h == $orig_h) {
                return false;
            }
            return $default;
        }

        public function set_category_thumbnail_size($content) {
            // Check if we are currently editing a category term.
            if (strpos($content, 'id="taxonomy-category"') !== false) {
                // Replace 'thumbnail' with 'full' to set the size to 'full'.
                $content = str_replace('thumbnail', 'full', $content);
            }
            return $content;
        }

        public function disable_image_sizes( $sizes ) {
            return array(
                'thumbnail' => false,
                'medium' => false,
                'medium_large' => false,
                'large' => false,
            );
        }

    }

    KandelaberMain::get_instance();
}