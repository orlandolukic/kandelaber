<?php

if ( !class_exists('KandelaberProductCategory') ) {
    class KandelaberProductCategory {

        public static $LINK_FIELD = "link_field";
        private static $instance;
        public static function get_instance() {
            if (self::$instance === NULL) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public function __construct() {
            add_action('product_cat_edit_form_fields', array($this, 'add_custom_category_field'));
            add_action('edited_product_cat', array($this, 'save_custom_category_field'));
        }

        public function add_custom_category_field($term) {

            $term_id = $term->term_id;
            $value = esc_attr(get_term_meta($term_id, self::$LINK_FIELD, true));
            ?>
            <tr class="form-field">
                <th scope="row"><label for="<?= self::$LINK_FIELD ?>">Link for Category</label></th>
                <td>
                    <textarea type="text" rows="5" name="<?= self::$LINK_FIELD ?>" id="<?= self::$LINK_FIELD ?>"><?= $value ?></textarea>
                    <p class="description">If you enter link here, website will open it on category selection</p>
                </td>
            </tr>
            <?php
        }

        public function save_custom_category_field($term_id) {
            $link_field = $_POST[self::$LINK_FIELD];
            if (isset($link_field) && is_valid_link($link_field)) {
                update_term_meta($term_id, self::$LINK_FIELD, sanitize_text_field($link_field));
            } else {
                update_term_meta($term_id, self::$LINK_FIELD, '');
            }
        }
    }

    KandelaberProductCategory::get_instance();
}