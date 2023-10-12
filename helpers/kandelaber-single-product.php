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

            add_filter( 'query_vars', array($this, 'whitelist_query_vars') );
            add_filter( 'template_include', array($this, 'determine_what_to_show') );
            add_filter( 'document_title_parts', array($this, 'custom_modify_page_title') );
            add_filter( 'kandelaber_product_vars', array($this, 'get_single_product_data'), 10, 2 );
        }

        public function add_rewrite_rules() {
            add_rewrite_rule( 'proizvod/([a-z0-9\\-]+)[/]?$', 'index.php?single_product=$matches[1]', 'top' );
        }

        public function add_div() {
            ?>
            <div id="single-product-preview"></div>
            <?php
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

    }

    KandelaberSingleProduct::get_instance();
}