<?php

if ( ! class_exists( 'KandelaberProductCategoryPreview' ) ) {

    class KandelaberProductCategoryPreview {

        private static $instance;
        public static function get_instance() {
            if ( self::$instance === null ) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public function __construct() {
            add_action('kandelaber_action_before_page_template_footer', array($this, 'add_div'));
        }

        public function add_div() {
            ?>
            <div id="product-category-preview"></div>
            <?php
        }

        public function temporary() {

        }

    }

    KandelaberProductCategoryPreview::get_instance();

}