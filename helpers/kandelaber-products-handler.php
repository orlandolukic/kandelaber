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
            add_action( 'edited_term_taxonomy', array($this, 'custom_update_product_cat'), 10, 3 );
            add_action( 'deleted_term_taxonomy', array($this, 'delete_product_cat_taxonomy') );

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
            $queryVars = get_query_var('product_category');
            if (empty($queryVars)) {
                return $title;
            }

            // Customize the page title here
            $title['title'] = "Custom Page Title";
            return $title;
        }

        public function custom_product_cat_fields($term) {
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
            ?>
            <div class="form-field">
                <label for="is_product_and_category">Is product?</label>
                <input type="checkbox" name="is_product_and_category" id="is_product_and_category" value="0" />
                <p class="description">Check this box if this category is the product at the same time.</p>
            </div>
            <?php
        }

        // Add an action hook to update or modify product category data after it has been edited
        public function custom_update_product_cat($term_id, $slug, $args) {
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
            // Release custom fields
            delete_term_meta($term_id, 'is_product_and_category');
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
    }

    KandelaberProductsHandler::get_instance();
}