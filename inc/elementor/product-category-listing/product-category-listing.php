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
            'slug'         => $slug,
            'meta_query'   => array(
                'meta_key' => 'is_product_and_category'
            )
        );
        $all_categories = get_categories( $args );

        $thumbnail_id = get_term_meta($all_categories[0]->term_id, 'thumbnail_id', true);
        if ($thumbnail_id) {
            $image = wp_get_attachment_image_src($thumbnail_id, 'full');
            $all_categories[0]->image = $image;
        }
        $is_product_and_category = get_term_meta($all_categories[0]->term_id, 'is_product_and_category', true);
        if ($is_product_and_category) {
            $all_categories[0]->is_product_and_category = $is_product_and_category === "1";
        } else {
            $all_categories[0]->is_product_and_category = false;
        }
        $external_link = get_term_meta($all_categories[0]->term_id, KandelaberProductCategory::$LINK_FIELD, true);
        $all_categories[0]->external_link = $external_link !== "" ? $external_link : NULL;

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
            'hide_empty'   => 0
        ));
        $subcategories_array = [];

        if (!empty($subcategories)) {
            // Loop through and display subcategories.
            foreach ($subcategories as $subcategory) {
                array_push($subcategories_array, $subcategory);

                // Get image for subcategory
                $thumbnail_id = get_term_meta($subcategory->term_id, 'thumbnail_id', true);
                if ($thumbnail_id) {
                    $image = wp_get_attachment_image_src($thumbnail_id, 'full');
                    $subcategory->image = $image;
                }

                $is_product_and_category = get_term_meta($subcategory->term_id, 'is_product_and_category', true);
                if ($is_product_and_category) {
                    $subcategory->is_product_and_category = $is_product_and_category === "1";
                } else {
                    $subcategory->is_product_and_category = false;
                }
                $external_link = get_term_meta($subcategory->term_id, KandelaberProductCategory::$LINK_FIELD, true);
                $subcategory->external_link = $external_link !== "" ? $external_link : NULL;
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

    private static function get_uncategorized_id() {
        $term = get_term_by('slug', 'uncategorized', 'product_cat');
        if ($term) {
            return $term->term_id;
        }
        return 0;
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
            'exclude'      => [self::get_uncategorized_id()],
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
                $image = wp_get_attachment_image_src($thumbnail_id, 'full');
                $all_categories[$i]->image = $image;
            }
            $is_product_and_category = get_term_meta($all_categories[$i]->term_id, 'is_product_and_category', true);
            if ($is_product_and_category) {
                $all_categories[$i]->is_product_and_category = $is_product_and_category === "1";
            } else {
                $all_categories[$i]->is_product_and_category = false;
            }
            $external_link = get_term_meta($all_categories[$i]->term_id, KandelaberProductCategory::$LINK_FIELD, true);
            $all_categories[$i]->external_link = $external_link !== "" ? $external_link : NULL;

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

            $all_categories[$i]->subcategories = Product_Category_Listing::fetch_subcategories_for($all_categories[$i]->slug);
        }

        return $all_categories;
    }

}

// Create widget
Product_Category_Listing::get();

