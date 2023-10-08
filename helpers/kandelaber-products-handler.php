<?php

/**
 * Class responsible for product handling within the app
 */
if ( ! class_exists('KandelaberProductsHandler' ) ) {
    class KandelaberProductsHandler {
        private static $instance;
        public static function get_instance() {
            if (self::$instance === null) {
                self::$instance = new KandelaberProductsHandler();
            }
            return self::$instance;
        }

        private $is_category;
        private $is_subcategory;

        private $category_field;
        private $subcategory_field;

        public function __construct() {
            // Add actions & filters
            add_action( 'init',  array($this, 'add_rewrite_rules') );
            add_action( 'product_cat_edit_form_fields', array($this, 'custom_product_cat_fields') );
            add_action( 'product_cat_add_form_fields', array($this, 'add_custom_product_cat_fields') );
            add_action( 'edited_term_taxonomy', array($this, 'custom_update_product_cat'), 10, 2);
            add_action( 'deleted_term_taxonomy', array($this, 'delete_product_cat_taxonomy') );
            add_action( 'wp_enqueue_scripts', array($this, 'enqueue_scripts'), 11 );
            add_action( 'init', function() {
                //$this->get_products_in_category('unutrasnja-rasveta');
            });

            // Ajax requests
            add_action('wp_ajax_fetch_products_for_category', array($this, 'fetch_products_for_category_ajax') );
            add_action('wp_ajax_nopriv_fetch_products_for_category', array($this, 'fetch_products_for_category_ajax') );

            add_filter( 'query_vars', array($this, 'whitelist_query_vars') );
            add_filter( 'template_include', array($this, 'determine_what_to_show') );
            add_filter( 'document_title_parts', array($this, 'custom_modify_page_title') );
        }

        public function add_rewrite_rules() {
            add_rewrite_rule( 'proizvodi/([a-z0-9\\-]+)[/]?$', 'index.php?product_category=$matches[1]', 'top' );
            add_rewrite_rule( 'proizvodi/([a-z0-9\\-]+)/([a-z0-9\\-]+)[/]?$', 'index.php?product_category=$matches[1]&product_subcategory=$matches[2]', 'top' );
        }

        public function whitelist_query_vars( $query_vars ) {
            $query_vars[] = 'product_category';
            $query_vars[] = 'product_subcategory';
            return $query_vars;
        }

        public function determine_what_to_show( $template ) {
            $product_category = get_query_var( 'product_category' );
            if ( !$product_category || $product_category == '' ) {
                $this->is_category = false;
                $this->is_subcategory = false;
                return $template;
            }

            // Check if given product category exists within the site
            $category = get_term_by( 'slug', $product_category, 'product_cat' );
            if (!$category) {
                return get_template_directory() . '/404.php';
            }
            $this->is_category = true;
            $this->is_subcategory = false;
            $this->category_field = $product_category;

            // Check if subcategory is present
            $product_subcategory = get_query_var( 'product_subcategory' );
            if ( !$product_subcategory || $product_subcategory == '' ) {
                $this->is_subcategory = false;
                return get_template_directory() . '/category-preview.php';
            };

            // Check if given product subcategory exists within the site
            $subcategory = get_term_by( 'slug', $product_subcategory, 'product_cat' );
            if (!$subcategory || $subcategory->parent !== $category->term_id) {
                return get_template_directory() . '/404.php';
            }
            $this->is_subcategory = true;
            $this->subcategory_field = $product_subcategory;

            return get_template_directory() . '/category-preview.php';
        }

        public function custom_modify_page_title($title) {

            if (KandelaberSingleProduct::get_instance()->is_single_product_page()) {
                return $title;
            }

            if (!$this->is_category && !$this->is_subcategory) {
                return $title;
            }

            if ($this->is_subcategory) {
                $category_slug = get_query_var('product_subcategory');
            } else {
                $category_slug = get_query_var('product_category');
            }

            $args = array(
                'taxonomy'     => 'product_cat',
                'hide_empty'   => 0
            );
            $all_categories = get_categories( $args );

            $newTitle = '';
            // Go through all categories and fetch thumbnail
            for ($i=0; $i < count($all_categories); $i++) {
                if ($all_categories[$i]->slug === $category_slug) {
                    $newTitle = $all_categories[$i]->name;
                    break;
                }
            }

            // Customize the page title here
            $title['title'] = $newTitle;
            return $title;
        }

        public function enqueue_scripts() {

            $this->enqueue_mutual();

            if (KandelaberSingleProduct::get_instance()->is_single_product_page()) {
                return;
            }

            $array = array(
                'ajax_url' => admin_url('admin-ajax.php'),
                "category" => get_query_var('product_category'),
                "subcategory" => get_query_var('product_subcategory') !== '' ? get_query_var('product_subcategory') : null,
                "subcategories" => null,
                "products" => null,
                "page" => "opened-category"
            );

            $array["subcategories"] = Product_Category_Listing::fetch_subcategories_for($array["category"]);

            if ($this->get_is_only_category()) {
                $array["products"] = KandelaberProductsHandler::get_products_in_category($array["category"]);
                $array["category"] = Product_Category_Listing::fetch_data_for_category_by($array["category"]);
            } else if ($this->is_subcategory) {
                $array["products"] = KandelaberProductsHandler::get_products_in_category($array["subcategory"]);
                $array["category"] = Product_Category_Listing::fetch_data_for_category_by($array["category"]);
                $array["subcategory"] = Product_Category_Listing::fetch_data_for_category_by($array["subcategory"]);
            }

            // Add variable for react rendered script
            wp_localize_script('react-rendered', 'react_vars', $array);
        }

        private function enqueue_mutual() {

            $array = array(
                "categories" => null
            );

            $array["categories"] = Product_Category_Listing::get_root_categories();

            // Add variable for react rendered script
            wp_localize_script('react-rendered', 'react_top', $array);
        }

        public function custom_product_cat_fields($term) {

            if (KandelaberSingleProduct::get_instance()->is_single_product_page()) {
                return;
            }

            $term_id = $term->term_id;
            $is_product = get_term_meta($term_id, 'is_product_and_category', true);
            ?>
            <tr class="form-field">
                <th scope="row">
                    <label for="is_product_and_category">Is product?</label>
                </th>
                <td>
                    <input type="checkbox" name="is_product_and_category" id="is_product_and_category" value="<?= $is_product === "1" ? "1" : "0" ?>" <?php checked($is_product, '1'); ?> />
                    <p class="description">Check this box if this category is the product at the same time.</p>
                    <script type="text/javascript">
                        jQuery("#is_product_and_category").on("click", function(e) {
                            if (jQuery(this).attr("checked")) {
                                jQuery(this).attr('value', '0');
                                jQuery(this).attr("checked", false);
                            } else {
                                jQuery(this).attr('value', '1');
                                jQuery(this).attr("checked", true);
                            }
                        });
                    </script>
                </td>
            </tr>
            <?php
        }

        public function add_custom_product_cat_fields() {

            if (!$this->is_products_page()) {
                return;
            }

            ?>
            <div class="form-field">
                <label for="is_product_and_category">Is product?</label>
                <input type="checkbox" name="is_product_and_category" id="is_product_and_category" value="0" />
                <p class="description">Check this box if this category is the product at the same time.</p>
            </div>
            <?php
        }

        // Add an action hook to update or modify product category data after it has been edited
        public function custom_update_product_cat($term_id, $slug) {

            if (KandelaberSingleProduct::get_instance()->is_single_product_page()) {
                return;
            }

            if ($slug === 'product_cat') {
                if (isset($_POST['is_product_and_category'])) {
                    $custom_field_value = ($_POST['is_product_and_category'] === '1') ? '1' : '0';
                    update_term_meta($term_id, 'is_product_and_category', $custom_field_value);
                } else {
                    update_term_meta($term_id, 'is_product_and_category', "0");
                }
            }
        }

        public function delete_product_cat_taxonomy($term_id) {

            if (KandelaberSingleProduct::get_instance()->is_single_product_page()) {
                return;
            }

            // Release custom fields
            delete_term_meta($term_id, 'is_product_and_category');
        }

        public function get_products_in_category($slug) {

            if (KandelaberSingleProduct::get_instance()->is_single_product_page()) {
                return array();
            }

            $args = array(
                'post_type'      => 'product',
                'posts_per_page' => -1,
                'tax_query'      => array(
                    array(
                        'taxonomy'         => 'product_cat',
                        'field'            => 'slug',
                        'terms'            => $slug,
                        'include_children' => false
                    ),
                ),
            );
            $products = new WP_Query($args);

            return $this->get_all_data_for_products($products);
        }

        public function fetch_products_for_category_ajax() {

            $slug = sanitize_text_field($_POST['slug']);

            echo json_encode($this->get_products_in_category($slug));

            // Always use die() at the end of your callback function
            die();
        }

        public function get_product_by_slug($slug) {

            $args = array(
                'post_type'      => 'product',
                'posts_per_page' => -1,
                'name'           => $slug,
            );
            $products = new WP_Query($args);

            return $this->get_all_data_for_products($products);
        }

        private function get_all_data_for_products($products) {
            if ($products->have_posts()) :
                $products_arr = $products->get_posts();
                for($i=0; $i<count($products_arr); $i++) {
                    // Get the product thumbnail (featured image) ID
                    $product_thumbnail_id = get_post_thumbnail_id($products_arr[$i]->ID);
                    // Get the product thumbnail (featured image) URL
                    $thumbnail_url = wp_get_attachment_image_src($product_thumbnail_id, 'full');

                    // Check if there is no featured image
                    if (empty($thumbnail_url)) {
                        $thumbnail_url = array(get_template_directory_uri() . "/assets/img/no-image.png");
                    }

                    $products_arr[$i]->featured_image = $thumbnail_url;

                    // Get the product object
                    $product = wc_get_product($products_arr[$i]->ID);

                    if ($product) {
                        $products_arr[$i]->title = $product->get_title();
                        $products_arr[$i]->tags = $product->get_tags();
                        $products_arr[$i]->gallery = ProductHelper::get_gallery_for_product($product);
                        $products_arr[$i]->attributes = ProductHelper::get_attributes_for_product($product);
                        $products_arr[$i]->variations = ProductHelper::get_variations_for_product($product);
                        $products_arr[$i]->categories = ProductHelper::get_categories_for_product($product);
                    }
                }
                return $products_arr;
            endif;
            return array();
        }

        public function get_is_category() {
            return $this->is_category;
        }

        public function get_is_subcategory() {
            return $this->is_subcategory;
        }

        public function get_category() {
            return $this->category_field;
        }

        public function get_subcategory() {
            return $this->subcategory_field;
        }

        public function get_is_only_category() {
            return $this->is_category && !$this->is_subcategory;
        }

        public function is_products_page() {
            return $this->is_category && !$this->is_subcategory || $this->is_category && $this->is_subcategory;
        }
    }

    KandelaberProductsHandler::get_instance();
}