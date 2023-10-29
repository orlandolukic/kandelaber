<?php

if ( ! class_exists('KandelaberSingleProduct') ) {
    class KandelaberSingleProduct {
        private static $instance;
        public static function get_instance() {
            if (self::$instance == null) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        private $is_single_product;

        private $single_product_slug;

        public function __construct() {
            $this->is_single_product = false;

            // Add actions & filters
            add_action( 'init',  array($this, 'add_rewrite_rules') );
            add_action( 'wp_enqueue_scripts', array($this, 'enqueue_scripts'), 11 );
            add_action( 'kandelaber_action_before_page_template_footer', array($this, 'add_div') );
            add_action( 'wp_ajax_fetch_product', array($this, 'fetch_product') );
            add_action( 'wp_ajax_nopriv_fetch_product', array($this, 'fetch_product') );
            add_action( 'woocommerce_product_options_advanced', array($this, 'add_custom_product_field') );
            add_action( 'wp_head', array($this, 'print_seo') );

            add_filter( 'query_vars', array($this, 'whitelist_query_vars') );
            add_filter( 'template_include', array($this, 'determine_what_to_show') );
            add_filter( 'document_title_parts', array($this, 'custom_modify_page_title') );
            add_filter( 'aioseo_title', array($this, 'custom_modify_page_title_aioseo') );
            add_filter( 'kandelaber_product_vars', array($this, 'get_single_product_data'), 10, 2 );
            add_filter( 'aioseo_disable', array($this, 'aioseo_disable_term_output') );
        }

        public function add_rewrite_rules() {
            add_rewrite_rule( 'proizvod/([a-z0-9\\-]+)[/]?$', 'index.php?single_product=$matches[1]', 'top' );
        }

        public function add_div() {
            ?>
            <div id="single-product-preview"></div>
            <?php
        }

        public function add_custom_product_field() {
            woocommerce_wp_select(array(
                'id'          => '_product_weight',
                'label'       => 'Product Weight (kg)',
                'placeholder' => 'Enter the product weight in kilograms',
                'desc_tip'    => 'true',
                'options'     => array(
                    1 => "1",
                    2 => "2",
                    3 => "3"
                )
            ));
        }

        public function whitelist_query_vars( $query_vars ) {
            $query_vars[] = 'single_product';
            return $query_vars;
        }

        public function determine_what_to_show( $template ) {
            $single_product = get_query_var('single_product');
            if (!$single_product || $single_product == "") {
                return $template;
            }
            $this->is_single_product = true;
            $this->single_product_slug = $single_product;

            // Check if product exists
            $exists = ProductHelper::check_if_product_exists($single_product);
            if (!$exists) {
                header("location: /proizvodi");
                return $template;
            }

            return get_template_directory() . '/product-preview.php';
        }

        public function custom_modify_page_title($title) {
            $single_product = get_query_var('single_product');
            if (!$single_product || $single_product == "") {
                return $title;
            }

            $product = KandelaberProductsHandler::get_instance()->get_product_by_slug($this->single_product_slug);
            $title["title"] = $product[0]->post_title;
            return $title;
        }

        public function custom_modify_page_title_aioseo($title) {
            $single_product = get_query_var('single_product');
            if (!$single_product || $single_product == "") {
                return $title;
            }

            $product = $this->get_current_product();
            return $product->post_title . " - Kandelaber";
        }

        public function enqueue_scripts() {

            if (!$this->is_single_product) {
                return;
            }

            $product = KandelaberProductsHandler::get_instance()->get_product_by_slug($this->single_product_slug);

            $array = array(
                "page"      => "single-product",
                "ajax_url"  => admin_url('admin-ajax.php'),
                "product"   => $product
            );
            // Add variable for react rendered script
            wp_localize_script('react-rendered', 'react_vars', $array);

            // Get all data for product vars
            $product_vars = apply_filters( 'kandelaber_product_vars', array(), KandelaberSingleProduct::get_instance()->get_current_product() );
            wp_localize_script('react-rendered', 'product_vars', $product_vars);
        }

        public function get_single_product_data($array, $product) {
            $leaf_category                          = ProductHelper::get_leaf_category_for_product($product);
            $leaf_category_extended                 = Product_Category_Listing::fetch_data_for_category_by($leaf_category->slug);
            $array["product"]                       = $product;
            $array["recommended_products"]          = ProductHelper::get_random_products_in_category($leaf_category, $product);
            $array["recommended_products_category"] = $leaf_category_extended;

            return $array;
        }

        public function aioseo_disable_term_output($disabled) {
            if (!$this->is_single_product_page()) {
                return $disabled;
            }
            return true;
        }

        public function is_single_product_page() {
            return $this->is_single_product;
        }

        public function get_current_product() {
            $products = KandelaberProductsHandler::get_instance()->get_product_by_slug($this->single_product_slug);
            if (empty($products)) {
                return NULL;
            }
            return $products[0];
        }

        public function fetch_product() {

            $data      = array();
            $errorMessage = "";
            $ret_array = array(
                "found" => false,
                "error"   => &$errorMessage,
                "payload" => &$data
            );
            if ( !isset($_POST['slug']) ) {
                $errorMessage = "SLUG_NOT_DEFINED";
                echo json_encode($ret_array);
                wp_die();
            }

            $slug = $_POST['slug'];
            $product = KandelaberProductsHandler::get_instance()->get_product_by_slug($slug);

            if ( !empty($product) ) {
                $errorMessage = null;
                $ret_array["found"] = true;
                $data = apply_filters( 'kandelaber_product_vars', array(), $product[0] );
            } else {
                $errorMessage = "PRODUCT_NOT_FOUND";
            }

            echo json_encode($ret_array);
            wp_die();

        }

        public function print_seo() {

            if ( KandelaberProductsHandler::get_instance()->is_products_page() || !$this->is_single_product ) {
                return;
            }

            $product = $this->get_current_product();

            ?>
            <meta property="og:locale" content="en_US">
            <meta property="og:site_name" content="Kandelaber - Sve od rasvete, na jednom mestu">
            <meta property="og:type" content="website">
            <meta property="og:title" content="<?= $product->post_title ?> - Kandelaber">
            <meta property="og:description" content="<?= $product->post_content ?>">
            <meta property="og:url" content="https://kandelaberdoo.rs/">
            <meta property="og:image" content="<?= KandelaberSEO::$SEO_IMAGE ?>">
            <meta property="og:image:secure_url" content="<?= KandelaberSEO::$SEO_IMAGE ?>">
            <meta property="og:image:width" content="4416">
            <meta property="og:image:height" content="3312">

            <meta name="twitter:card" content="summary_large_image">
            <meta name="twitter:title" content="<?= $product->post_title ?> - Kandelaber">
            <meta name="twitter:description" content="<?= $product->post_content ?>">
            <meta name="twitter:image" content="<?= KandelaberSEO::$SEO_IMAGE ?>">
            <?php
        }

    }

    KandelaberSingleProduct::get_instance();
}