<?php

if ( ! class_exists('ProductHelper') ) {

    class ProductHelper
    {
        public static function get_gallery_for_product($product) {

            if (!$product) {
                return array();
            }

            $gallery_attachment_ids = get_post_meta($product->get_id(), '_product_image_gallery', true);
            $array = array();

            if (!empty($gallery_attachment_ids)) {
                $gallery_attachment_ids = explode(',', $gallery_attachment_ids);

                foreach ($gallery_attachment_ids as $attachment_id) {
                    // Get the image URL
                    $image_url = wp_get_attachment_url($attachment_id);

                    // Put image inside array
                    $array[] = $image_url;
                }
            }

            return $array;
        }

        public static function get_attributes_for_product($product) {
            $product_attributes = $product->get_attributes();
            $array = array();

            if (!empty($product_attributes)) {
                foreach ($product_attributes as $attribute) {
                    // Access attribute data
                    $attribute_name = $attribute->get_name();
                    $attribute_values = $attribute->get_options();

                    $array[] = array(
                        "attribute_name" => $attribute_name,
                        "attribute_values" => $attribute_values,
                    );
                }
            }

            return $array;
        }

        public static function get_variations_for_product($product) {
            $array = array();

            // Check if the product has variations
            if ($product->is_type('variable')) {
                // Get the variations
                $variations = $product->get_available_variations();

                if (!empty($variations)) {
                    foreach ($variations as $variation) {
                        $array[] = $variation;
                    }
                }
            }

            return $array;
        }

        public static function get_categories_for_product($product) {

            $array = array();
            $terms = get_the_terms( $product->get_id(), 'product_cat' );
            foreach ($terms as $term) {
                $array[] = $term;

                $termsParent = get_term( $term->parent, 'product_cat' );
                if ($termsParent instanceof WP_Error) {
                    continue;
                }
                $array[] = $termsParent;
                break;
            }

            return $array;
        }

        public static function get_random_products_in_category($category) {

            if ($category == NULL) {
                return array();
            } else if ($category->slug == "led-trake") {
                $category->slug = "rozetne";
            }

            $args = array(
                'post_type'      => 'product',
                'posts_per_page' => 4,
                'orderby'        => 'rand',
                'tax_query'      => array(
                    array(
                        'taxonomy' => 'product_cat',
                        'field'    => 'slug',
                        'terms'    => $category->slug,
                    ),
                ),
            );

            $products = new WP_Query($args);
            $array = array();

            if ($products->have_posts()) {
                return KandelaberProductsHandler::get_instance()->get_all_data_for_products($products);
            }

            return $array;
        }

        public static function get_leaf_category_for_product($product) {
            if (!empty($product->categories)) {
                return $product->categories[count($product->categories)-1];
            }

            return NULL;
        }

        public static function check_if_product_exists($slug) {
            $args = array(
                'post_type'      => 'product',
                'posts_per_page' => -1,
                'name'           => $slug,
                "post_status"    => 'publish'
            );

            $products = new WP_Query($args);

            return $products->have_posts();
        }

    }

}