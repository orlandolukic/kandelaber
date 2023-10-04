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

        public function __construct() {
            $this->is_single_product = false;

            // Add actions & filters
            add_action( 'init',  array($this, 'add_rewrite_rules') );
            add_action( 'wp_enqueue_scripts', array($this, 'enqueue_scripts'), 11 );
            add_action('kandelaber_action_before_page_template_footer', array($this, 'add_div'));

            add_filter( 'query_vars', array($this, 'whitelist_query_vars') );
            add_filter( 'template_include', array($this, 'determine_what_to_show') );
            add_filter( 'document_title_parts', array($this, 'custom_modify_page_title') );
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
            return get_template_directory() . '/product-preview.php';
        }

        public function custom_modify_page_title($title) {
            $single_product = get_query_var('single_product');
            if (!$single_product || $single_product == "") {
                return $title;
            }
            $title["title"] = "asds";
            return $title;
        }

        public function enqueue_scripts() {

            if (!$this->is_single_product) {
                return;
            }

            $array = array(
                "page" => "single-product",
                "ajax_url" => admin_url('admin-ajax.php')
            );
            // Add variable for react rendered script
            wp_localize_script('react-rendered', 'react_vars', $array);
        }

        public function is_single_product_page() {
            return $this->is_single_product;
        }

    }

    KandelaberSingleProduct::get_instance();
}