<?php

if ( !class_exists('KandelaberSEO') ) {

    class KandelaberSEO {

        public static $SEO_IMAGE = 'https://kandelaberdoo.rs/wp-content/uploads/2023/10/Social-media-cover.min_.png';

        private static $instance;

        public static function get_instance() {
            if (self::$instance == NULL) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public function __constuct() {
            add_action( 'wp_head', array($this, 'print_seo') );
        }

        public function print_seo() {

        }

    }

}

KandelaberSEO::get_instance();