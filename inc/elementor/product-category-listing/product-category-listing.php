<?php

class Product_Category_Listing {
    private static $instance;

    public static function get() {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function fetch_data_for_category_by($slug) {

        $args = array(
            'taxonomy'     => 'product_cat',
            'hide_empty'   => 0,
            'slug'         => $slug
        );
        $all_categories = get_categories( $args );

        $thumbnail_id = get_term_meta($all_categories[0]->term_id, 'thumbnail_id', true);
        if ($thumbnail_id) {
            $image = wp_get_attachment_image_src($thumbnail_id); // Change 'thumbnail' to the desired image size
            $all_categories[0]->image = $image;
        }
        $is_product_and_category = get_term_meta($all_categories[0]->term_id, 'is_product_and_category', true);
        if ($is_product_and_category) {
            $all_categories[0]->is_product_and_category = $is_product_and_category === "1";
        } else {
            $all_categories[0]->is_product_and_category = false;
        }

        // Check if category has products within
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => 1,
            'tax_query'      => array(
                array(
                    'taxonomy'         => 'product_cat',
                    'field'            => 'slug',
                    'terms'            => $slug,
                    'include_children' => false, // Exclude products from child categories.
                ),
            ),
        );
        $products = new WP_Query($args);

        if ($products->have_posts()) :
            $all_categories[0]->has_products = true;
            wp_reset_postdata(); // Reset post data.
        else :
            $all_categories[0]->has_products = false;
        endif;

        return $all_categories[0];
    }

    public static function fetch_subcategories_for($category_slug) {

        $args = array(
            'taxonomy'     => 'product_cat',
            'hide_empty'   => 0,
            'slug'         => $category_slug
        );
        $all_categories = get_categories( $args );

        if (empty($all_categories)) {
            return array();
        }

        // Check for subcategories
        // Use the parent category ID to retrieve its child categories (subcategories).
        $subcategories = get_categories(array(
            'taxonomy'      => 'product_cat',
            'child_of'      => $all_categories[0]->term_id,
            'hide_empty'   => 0,
        ));
        $subcategories_array = [];

        if (!empty($subcategories)) {
            // Loop through and display subcategories.
            foreach ($subcategories as $subcategory) {
                array_push($subcategories_array, $subcategory);

                // Get image for subcategory
                $thumbnail_id = get_term_meta($subcategory->term_id, 'thumbnail_id', true);
                if ($thumbnail_id) {
                    $image = wp_get_attachment_image_src($thumbnail_id); // Change 'thumbnail' to the desired image size
                    $subcategory->image = $image;
                }
            }

        }

        return $subcategories_array;
    }

    private function __construct() {
        add_action( 'elementor/widgets/register', array($this, 'register_new_widgets') );
        add_action( 'elementor/init', array($this, 'load_all_elementor_files') );
        add_action( 'lucent_action_after_main_js', array($this, 'enqueue_scripts'));

        //add_action('wp_ajax_get_root_categories', array($this, 'get_root_categories'));
        //add_action('wp_ajax_nopriv_get_root_categories', array($this, 'get_root_categories'));
    }

    public function load_all_elementor_files() {
        require LUCENT_INC_ROOT_DIR . "/elementor/product-category-listing/product-category-listing-elementor-widget.php";
    }

    public function register_new_widgets( $widgets_manager ) {
        $widgets_manager->register( new Product_Category_Listing_Elementor_Widget() );
    }

    public function enqueue_scripts() {
        wp_enqueue_script('react-dom');
        wp_enqueue_script('react-rendered');
        wp_enqueue_script('react-main');
    }

    public static function get_root_categories() {

        $taxonomy     = 'product_cat';
        $orderby      = 'menu_order';
        $show_count   = 1;      // 1 for yes, 0 for no
        $pad_counts   = 1;      // 1 for yes, 0 for no
        $hierarchical = 1;      // 1 for yes, 0 for no
        $title        = '';
        $empty        = 0;

        $args = array(
            'taxonomy'     => $taxonomy,
            'orderby'      => $orderby,
            'show_count'   => $show_count,
            'pad_counts'   => $pad_counts,
            'hierarchical' => $hierarchical,
            'title_li'     => $title,
            'hide_empty'   => $empty,
            'exclude'      => [15],
            'parent'       => 0,
            'meta_query'   => array(
                'meta_key' => 'is_product_and_category'
            )
        );
        $all_categories = get_categories( $args );


        // Go through all categories and fetch thumbnail
        for ($i=0; $i < count($all_categories); $i++) {
            $thumbnail_id = get_term_meta($all_categories[$i]->term_id, 'thumbnail_id', true);
            if ($thumbnail_id) {
                $image = wp_get_attachment_image_src($thumbnail_id); // Change 'thumbnail' to the desired image size
                $all_categories[$i]->image = $image;
            }
            $is_product_and_category = get_term_meta($all_categories[$i]->term_id, 'is_product_and_category', true);
            if ($is_product_and_category) {
                $all_categories[$i]->is_product_and_category = $is_product_and_category === "1";
            } else {
                $all_categories[$i]->is_product_and_category = false;
            }

            // Check if this category have products
            // Check if category has products within
            $args = array(
                'post_type'      => 'product',
                'posts_per_page' => 1,
                'tax_query'      => array(
                    array(
                        'taxonomy'         => 'product_cat',
                        'field'            => 'slug',
                        'terms'            => $all_categories[$i]->slug,
                        'include_children' => false, // Exclude products from child categories.
                    ),
                ),
            );
            $products = new WP_Query($args);

            if ($products->have_posts()) :
                $all_categories[$i]->has_products = true;
                wp_reset_postdata(); // Reset post data.
            else :
                $all_categories[$i]->has_products = false;
            endif;

            // Check for subcategories
            // Use the parent category ID to retrieve its child categories (subcategories).
            $subcategories = get_categories(array(
                'taxonomy' => $taxonomy,
                'child_of' => $all_categories[$i]->term_id,
                'orderby' => $orderby,
                'hide_empty'   => 0,
            ));
            $subcategories_array = [];

            if (!empty($subcategories)) {
                // Loop through and display subcategories.
                foreach ($subcategories as $subcategory) {
                    array_push($subcategories_array, $subcategory);

                    // Get image for subcategory
                    $thumbnail_id = get_term_meta($subcategory->term_id, 'thumbnail_id', true);
                    if ($thumbnail_id) {
                        $image = wp_get_attachment_image_src($thumbnail_id); // Change 'thumbnail' to the desired image size
                        $subcategory->image = $image;
                    }
                }
                $all_categories[$i]->subcategories = $subcategories_array;
            } else {
                $all_categories[$i]->subcategories = NULL;
            }
        }

        return $all_categories;
    }

}

// Create widget
Product_Category_Listing::get();

